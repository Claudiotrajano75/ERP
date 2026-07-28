<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class ProdutoImagemService
{
    /**
     * Busca no Unsplash pelo nome do produto e baixa a imagem automaticamente.
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
            // 1. Consulta a API de busca do Unsplash
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

            // 2. Pega a URL da imagem (regular size)
            $results = $response->json('results');
            $imageUrl = $results[0]['urls']['regular'] ?? null;
            if (empty($imageUrl)) {
                return null;
            }

            // 3. Prepara o diretório de upload
            $uploadDir = public_path('uploads/produtos');
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }

            // 4. Gera nome único baseado no nome do produto (slug)
            $slug = Str::slug($productName);
            $slug = mb_substr($slug, 0, 60); // limita tamanho
            $fileName = $slug . '-' . Str::random(8) . '.jpg';
            $fullPath = $uploadDir . '/' . $fileName;

            // 5. Baixa a imagem usando Laravel HTTP Client com sink (stream direto para disco)
            $downloadResponse = Http::sink($fullPath)
                ->timeout(30)
                ->withUserAgent('LaravelERP/1.0')
                ->get($imageUrl);

            if ($downloadResponse->successful()) {
                return $fileName;
            }

            return null;

        } catch (\Exception $e) {
            // Log do erro mas não interrompe o fluxo
            report($e);
            return null;
        }
    }

    /**
     * Faz o download de uma imagem de uma URL externa e salva no diretório de produtos.
     * Método mantido para compatibilidade, mas prefira o downloadFromUnsplash.
     *
     * @param string $url URL pública da imagem
     * @return string|null Nome do arquivo salvo, ou null em caso de erro
     */
    public function downloadFromUrl(string $url): ?string
    {
        if (empty($url) || !filter_var($url, FILTER_VALIDATE_URL)) {
            return null;
        }

        try {
            $uploadDir = public_path('uploads/produtos');
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }

            // Detecta extensão pela URL
            $pathInfo = pathinfo(parse_url($url, PHP_URL_PATH) ?? '');
            $extFromUrl = strtolower($pathInfo['extension'] ?? '');
            $validExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp', 'bmp', 'svg'];

            if (in_array($extFromUrl, $validExtensions)) {
                $ext = $extFromUrl === 'jpeg' ? 'jpg' : $extFromUrl;
            } else {
                $ext = 'jpg'; // fallback
            }

            $fileName = Str::random(20) . '.' . $ext;
            $fullPath = $uploadDir . '/' . $fileName;

            $downloadResponse = Http::sink($fullPath)
                ->timeout(30)
                ->withUserAgent('LaravelERP/1.0')
                ->get($url);

            if ($downloadResponse->successful()) {
                return $fileName;
            }

            return null;

        } catch (\Exception $e) {
            report($e);
            return null;
        }
    }
}
