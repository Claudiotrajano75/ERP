<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class ProdutoImagemService
{
    /**
     * Busca inteligente de imagem para produto utilizando múltiplos provedores em cascata.
     * 
     * Ordem de execução:
     * 1. Se possuir código de barras EAN válido (>= 8 dígitos): consulta Open Food Facts.
     * 2. Sanitiza o nome do produto removendo códigos de cardápio/garçom (ex: "502-", "495-", "01.")
     *    e gramaturas/porções (ex: "(300G)", "100 G(ATÉ 12 ANOS)").
     * 3. Provedor 1: Bing Image Engine (alta resolução, sem captcha, excelente para pratos e produtos brasileiros).
     * 4. Provedor 2: Google Images Thumbnail Scraper (fallback).
     * 5. Provedor 3: DuckDuckGo Images (fallback).
     * 6. Provedor 4: Unsplash (fallback artístico).
     *
     * @param string $productName Nome do produto
     * @param string|null $barcode Código de barras EAN (opcional)
     * @return string|null Nome do arquivo salvo no disco ou null
     */
    public function searchAndDownloadImage(string $productName, ?string $barcode = null): ?string
    {
        $productName = trim($productName);
        if (empty($productName)) {
            return null;
        }

        // 1. Provedor por Código de Barras: Open Food/Products Facts (se for produto industrializado com EAN)
        $cleanBarcode = !empty($barcode) ? preg_replace('/\D/', '', $barcode) : '';
        if (strlen($cleanBarcode) >= 8 && $cleanBarcode !== '00000000') {
            try {
                $imageUrl = $this->searchOpenFoodFacts($cleanBarcode);
                if ($imageUrl) {
                    $saved = $this->downloadAndSaveImage($imageUrl, $productName);
                    if ($saved) return $saved;
                }
            } catch (\Exception $e) {
                report($e);
            }
        }

        // 2. Higieniza e gera variantes do nome do produto (remove códigos de cardápio, garçom, gramaturas)
        $searchTerms = $this->sanitizeTermsForSearch($productName);

        // 3. Itera sobre os termos higienizados (começando pelo termo mais limpo e comercial)
        foreach ($searchTerms as $term) {
            // Provedor 1: Bing Images (Web Search Direto)
            try {
                $imageUrl = $this->searchBingImage($term);
                if ($imageUrl) {
                    $saved = $this->downloadAndSaveImage($imageUrl, $term);
                    if ($saved) return $saved;
                }
            } catch (\Exception $e) {
                report($e);
            }

            // Provedor 2: Google Images Thumbnail Scraper
            try {
                $imageUrl = $this->searchGoogleThumbnail($term);
                if ($imageUrl) {
                    $saved = $this->downloadAndSaveImage($imageUrl, $term);
                    if ($saved) return $saved;
                }
            } catch (\Exception $e) {
                report($e);
            }

            // Provedor 3: DuckDuckGo Images (se disponível)
            try {
                $imageUrl = $this->searchDuckDuckGoImage($term);
                if ($imageUrl) {
                    $saved = $this->downloadAndSaveImage($imageUrl, $term);
                    if ($saved) return $saved;
                }
            } catch (\Exception $e) {
                report($e);
            }
        }

        // 4. Provedor 4: Unsplash (Fallback legado mantido)
        try {
            $saved = $this->downloadFromUnsplash($searchTerms[0] ?? $productName);
            if ($saved) return $saved;
        } catch (\Exception $e) {
            report($e);
        }

        return null;
    }

    /**
     * Higieniza o nome do produto para busca na web, removendo:
     * - Códigos de cardápio / garçom no início (ex: "502- ", "495-", "01. ", "#10 ", "123 - ")
     * - Gramaturas e volumes (ex: "(300G)", "100 G", "2L", "350ML", "1KG")
     * - Observações entre parênteses (ex: "(ATÉ 12 ANOS)", "(INDIVIDUAL)", "[SERVE 2]")
     *
     * @param string $productName Nome bruto do produto
     * @return array Lista de termos de busca em ordem de prioridade
     */
    public function sanitizeTermsForSearch(string $productName): array
    {
        $raw = trim($productName);
        if (empty($raw)) return [];

        $terms = [];

        // 1. Remove código de cardápio / garçom no início
        // Casos tratados:
        // "502- QUEIJO" => "QUEIJO"
        // "495-PRATO"   => "PRATO"
        // "01. PIZZA"   => "PIZZA"
        // "#10 COCA"    => "COCA"
        // "12 - SUCO"   => "SUCO"
        $semCodigo = preg_replace('/^\s*#?\s*\d{1,6}\s*[-–—._:]*\s*/iu', '', $raw);
        $semCodigo = trim($semCodigo);

        if (!empty($semCodigo) && $semCodigo !== $raw) {
            // 2. Remove parênteses e colchetes explicativos (ex: "(300G)", "(ATÉ 12 ANOS)", "[PORÇÃO]")
            $semParenteses = preg_replace('/\([^)]{1,50}\)/u', ' ', $semCodigo);
            $semParenteses = preg_replace('/\[[^\]]{1,50}\]/u', ' ', $semParenteses);

            // 3. Remove gramaturas e medidas soltas (ex: "100 G", "300g", "2L", "350ml", "1kg", "500 ml")
            $semMedidas = preg_replace('/\b\d+(?:[.,]\d+)?\s*(?:g|kg|ml|l|gr|gramas|litros|un|und|unid|unidades)\b/iu', ' ', $semParenteses);

            // 4. Limpa múltiplos espaços e pontuações residuais
            $termoLimpo = preg_replace('/\s+/', ' ', trim($semMedidas));
            $termoLimpo = trim($termoLimpo, " -–—._:;,/|\\()[]{}");

            if (mb_strlen($termoLimpo) >= 3) {
                $terms[] = $termoLimpo; // 1ª Prioridade: Nome limpo da comida (ex: "QUEIJO À MILANESA")
            }

            // 2ª Prioridade: Nome sem o código inicial mas com os complementos
            $semCodigoLimpo = preg_replace('/\s+/', ' ', $semCodigo);
            if (!in_array($semCodigoLimpo, $terms)) {
                $terms[] = $semCodigoLimpo;
            }
        }

        // Caso o nome original não tenha código numérico no início (ou como último fallback)
        if (!in_array($raw, $terms)) {
            $terms[] = $raw;
        }

        return array_values($terms);
    }

    /**
     * Busca fotos no Bing Images (Web Image Engine).
     * Alta taxa de acerto para marcas e culinária brasileira, sem necessidade de chave de API.
     *
     * @param string $query Termo higienizado
     * @return string|null URL direta da imagem
     */
    public function searchBingImage(string $query): ?string
    {
        $cleanQuery = trim($query);
        if (empty($cleanQuery)) return null;

        $url = "https://www.bing.com/images/search?q=" . urlencode($cleanQuery) . "&form=HDRSC2&first=1";
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/122.0.0.0 Safari/537.36');
        curl_setopt($ch, CURLOPT_TIMEOUT, 6);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $html = curl_exec($ch);
        $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($code !== 200 || empty($html)) {
            return null;
        }

        // 1. Procura imagens em resolução original via 'murl'
        if (preg_match_all('/murl&quot;:&quot;(https?:\/\/[^&"]+)&quot;/i', $html, $matches)) {
            foreach ($matches[1] as $imgUrl) {
                $imgUrl = html_entity_decode($imgUrl, ENT_QUOTES | ENT_HTML5);
                if (filter_var($imgUrl, FILTER_VALIDATE_URL) && !preg_match('/\.(svg|gif)$/i', $imgUrl)) {
                    return $imgUrl;
                }
            }
        }

        // 2. Fallback: miniaturas do Bing (tse)
        if (preg_match_all('/src="(https?:\/\/[^"]*tse[^"]*)"/i', $html, $matches)) {
            foreach ($matches[1] as $imgUrl) {
                $imgUrl = html_entity_decode($imgUrl, ENT_QUOTES | ENT_HTML5);
                if (filter_var($imgUrl, FILTER_VALIDATE_URL)) {
                    return $imgUrl;
                }
            }
        }

        return null;
    }

    /**
     * Fallback de miniaturas do Google Images (gstatic).
     *
     * @param string $query Termo higienizado
     * @return string|null URL direta da imagem
     */
    public function searchGoogleThumbnail(string $query): ?string
    {
        $cleanQuery = trim($query);
        if (empty($cleanQuery)) return null;

        $url = "https://www.google.com/search?tbm=isch&q=" . urlencode($cleanQuery) . "&tbs=isz:m";
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36');
        curl_setopt($ch, CURLOPT_TIMEOUT, 6);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $html = curl_exec($ch);
        $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($code === 200 && !empty($html)) {
            if (preg_match_all('/(https:\/\/encrypted-tbn0\.gstatic\.com\/images\?q=[^"&]+)/i', $html, $m)) {
                foreach ($m[1] as $imgUrl) {
                    $imgUrl = html_entity_decode($imgUrl, ENT_QUOTES | ENT_HTML5);
                    if (filter_var($imgUrl, FILTER_VALIDATE_URL)) {
                        return $imgUrl;
                    }
                }
            }
        }

        return null;
    }

    /**
     * Consulta a API de imagens do DuckDuckGo para encontrar a foto do produto na web.
     * Utiliza cURL com cookie jar para máxima compatibilidade.
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
            curl_setopt($ch, CURLOPT_TIMEOUT, 8);
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
            curl_setopt($ch, CURLOPT_TIMEOUT, 8);
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
