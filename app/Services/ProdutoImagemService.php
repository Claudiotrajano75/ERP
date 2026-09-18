<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class ProdutoImagemService
{
    /**
     * Busca inteligente de imagem para produto utilizando múltiplos provedores em cascata.
     * 1. Web Product Search (DuckDuckGo Image Engine via cURL) - Alta assertividade para o mercado brasileiro
     * 2. Open Food/Products Facts (Se código de barras EAN-13 estiver disponível)
     * 3. Unsplash (Fallback legado mantido)
     *
     * @param string $productName Nome do produto
     * @param string|null $barcode Código de barras EAN (opcional)
     * @return string|null Nome do arquivo salvo no disco ou null
     */
    public function searchAndDownloadImage(string $productName, ?string $barcode = null): ?string
    {
        if (empty(trim($productName))) {
            return null;
        }

        // 1. Provedor 1: DuckDuckGo Web Images (produtos reais brasileiros de e-commerces)
        try {
            $imageUrl = $this->searchDuckDuckGoImage($productName);
            if ($imageUrl) {
                $saved = $this->downloadAndSaveImage($imageUrl, $productName);
                if ($saved) return $saved;
            }
        } catch (\Exception $e) {
            report($e);
        }

        // 2. Provedor 2: Open Food/Products Facts (se tiver EAN válido com 8 a 14 dígitos)
        if (!empty($barcode) && strlen(trim($barcode)) >= 8 && trim($barcode) !== '0') {
            try {
                $imageUrl = $this->searchOpenFoodFacts(trim($barcode));
                if ($imageUrl) {
                    $saved = $this->downloadAndSaveImage($imageUrl, $productName);
                    if ($saved) return $saved;
                }
            } catch (\Exception $e) {
                report($e);
            }
        }

        // 3. Provedor 3: Unsplash (Fallback legado)
        try {
            $saved = $this->downloadFromUnsplash($productName);
            if ($saved) return $saved;
        } catch (\Exception $e) {
            report($e);
        }

        return null;
    }

    /**
     * Consulta a API de imagens do DuckDuckGo para encontrar a foto do produto na web.
     * Utiliza cURL com cookie jar para máxima compatibilidade e evitar 403.
     *
     * @param string $query Termo de busca (nome do produto)
     * @return string|null URL direta da imagem
     */
    public function searchDuckDuckGoImage(string $query): ?string
    {
        $cleanQuery = trim($query);
        if (empty($cleanQuery)) return null;

        $cookieFile = tempnam(sys_get_temp_dir(), 'ddg_');

        try {
            // Passo 1: Obter o token de busca VQD
            $tokenUrl = "https://duckduckgo.com/?q=" . urlencode($cleanQuery) . "&t=h_&iax=images&ia=images";
            $ch = curl_init($tokenUrl);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_COOKIEJAR, $cookieFile);
            curl_setopt($ch, CURLOPT_COOKIEFILE, $cookieFile);
            curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/122.0.0.0 Safari/537.36');
            curl_setopt($ch, CURLOPT_TIMEOUT, 10);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            $html = curl_exec($ch);
            curl_close($ch);

            $vqd = null;
            if (preg_match('/vqd=([0-9-_]+)/i', $html, $matches) || preg_match('/vqd=[\'"]([0-9-_]+)[\'"]/i', $html, $matches)) {
                $vqd = $matches[1];
            }

            if (!$vqd) {
                @unlink($cookieFile);
                return null;
            }

            // Passo 2: Buscar imagens com o token VQD
            $searchUrl = "https://duckduckgo.com/i.js?l=wt-wt&o=json&q=" . urlencode($cleanQuery) . "&vqd={$vqd}&f=,,,&p=1";
            $ch = curl_init($searchUrl);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_COOKIEJAR, $cookieFile);
            curl_setopt($ch, CURLOPT_COOKIEFILE, $cookieFile);
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                'Referer: https://duckduckgo.com/',
                'Accept: application/json, text/javascript, */*; q=0.01',
                'Sec-Fetch-Dest: empty',
                'Sec-Fetch-Mode: cors',
                'Sec-Fetch-Site: same-origin'
            ]);
            curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/122.0.0.0 Safari/537.36');
            curl_setopt($ch, CURLOPT_TIMEOUT, 10);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            $res = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);
            @unlink($cookieFile);

            if ($httpCode === 200 && !empty($res)) {
                $data = json_decode($res, true);
                if (!empty($data['results'])) {
                    foreach ($data['results'] as $item) {
                        if (!empty($item['image']) && filter_var($item['image'], FILTER_VALIDATE_URL)) {
                            return $item['image'];
                        }
                    }
                }
            }
        } catch (\Exception $e) {
            report($e);
            @unlink($cookieFile);
        }

        return null;
    }

    /**
     * Consulta a base pública Open Food Facts pelo código de barras GTIN/EAN.
     *
     * @param string $barcode Código EAN-13/GTIN
     * @return string|null URL direta da imagem
     */
    public function searchOpenFoodFacts(string $barcode): ?string
    {
        try {
            $url = "https://world.openfoodfacts.org/api/v0/product/{$barcode}.json";
            $response = Http::timeout(6)
                ->withHeaders(['User-Agent' => 'LaravelERP/1.0'])
                ->get($url);

            if ($response->successful()) {
                $data = $response->json();
                if (!empty($data['product'])) {
                    if (!empty($data['product']['image_front_url'])) {
                        return $data['product']['image_front_url'];
                    }
                    if (!empty($data['product']['image_url'])) {
                        return $data['product']['image_url'];
                    }
                }
            }
        } catch (\Exception $e) {
            report($e);
        }

        return null;
    }

    /**
     * Baixa a imagem de uma URL externa e a salva no diretório `public/uploads/produtos`.
     *
     * @param string $imageUrl URL pública da imagem
     * @param string $productName Nome para gerar slug amigável
     * @return string|null Nome do arquivo salvo
     */
    public function downloadAndSaveImage(string $imageUrl, string $productName = ''): ?string
    {
        if (empty($imageUrl) || !filter_var($imageUrl, FILTER_VALIDATE_URL)) {
            return null;
        }

        try {
            $uploadDir = public_path('uploads/produtos');
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }

            // Identifica ou define a extensão
            $pathInfo = pathinfo(parse_url($imageUrl, PHP_URL_PATH) ?? '');
            $ext = strtolower($pathInfo['extension'] ?? '');
            $validExtensions = ['jpg', 'jpeg', 'png', 'webp', 'bmp'];
            if (!in_array($ext, $validExtensions)) {
                $ext = 'jpg';
            } elseif ($ext === 'jpeg') {
                $ext = 'jpg';
            }

            $slug = !empty($productName) ? Str::slug($productName) : 'prod';
            $slug = mb_substr($slug, 0, 50);
            $fileName = $slug . '-' . Str::random(8) . '.' . $ext;
            $fullPath = $uploadDir . '/' . $fileName;

            // Download via cURL com stream para disco
            $fp = fopen($fullPath, 'w+');
            $ch = curl_init($imageUrl);
            curl_setopt($ch, CURLOPT_FILE, $fp);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
            curl_setopt($ch, CURLOPT_TIMEOUT, 20);
            curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/122.0.0.0 Safari/537.36');
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);
            fclose($fp);

            if ($httpCode === 200 && file_exists($fullPath) && filesize($fullPath) > 500) {
                return $fileName;
            }

            if (file_exists($fullPath)) {
                @unlink($fullPath);
            }

            return null;
        } catch (\Exception $e) {
            report($e);
            return null;
        }
    }

    /**
     * Busca no Unsplash pelo nome do produto e baixa a imagem automaticamente.
     * (Método mantido para compatibilidade total).
     *
     * @param string $productName Nome do produto para buscar
     * @return string|null Nome do arquivo salvo, ou null se não encontrar
     */
    public function downloadFromUnsplash(string $productName): ?string
    {
        $accessKey = config('services.unsplash.access_key');
        if (empty($accessKey)) {
            return null;
        }

        try {
            $response = Http::withHeaders([
                'Authorization' => "Client-ID {$accessKey}",
                'Accept-Version' => 'v1',
            ])->get('https://api.unsplash.com/search/photos', [
                'query' => $productName,
                'per_page' => 1,
                'orientation' => 'squarish',
            ]);

            if ($response->failed() || empty($response->json('results'))) {
                return null;
            }

            $results = $response->json('results');
            $imageUrl = $results[0]['urls']['regular'] ?? null;
            if (empty($imageUrl)) {
                return null;
            }

            return $this->downloadAndSaveImage($imageUrl, $productName);
        } catch (\Exception $e) {
            report($e);
            return null;
        }
    }

    /**
     * Faz o download de uma imagem de uma URL externa e salva no diretório de produtos.
     * (Método mantido para compatibilidade total).
     *
     * @param string $url URL pública da imagem
     * @return string|null Nome do arquivo salvo, ou null em caso de erro
     */
    public function downloadFromUrl(string $url): ?string
    {
        return $this->downloadAndSaveImage($url, 'url_prod');
    }
}
