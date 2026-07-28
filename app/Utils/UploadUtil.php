<?php

namespace App\Utils;

use Illuminate\Support\Str;

class UploadUtil
{
    public function uploadImage($request, $dir, $attr = 'image', $file_name = '')
    {
        if (!is_dir(public_path('uploads') . $dir)) {
            mkdir(public_path('uploads') . $dir, 0777, true);
        }
        $file = $request->file($attr);
        $ext = $file->getClientOriginalExtension();

        if ($file_name == '') {
            $file_name = Str::random(20) . ".$ext";
        }

        $file->move(public_path('uploads') . $dir, $file_name);
        return $file_name;
    }

    public function uploadFile($file, $dir)
    {
        if (!is_dir(public_path('uploads') . $dir)) {
            mkdir(public_path('uploads') . $dir, 0777, true);
        }

        $ext = $file->getClientOriginalExtension();

        $file_name = Str::random(20) . ".$ext";

        $file->move(public_path('uploads') . $dir, $file_name);
        return $file_name;
    }

    public function unlinkImage($item, $dir, $attr = 'image')
    {
        if (isset($item->imagem)) {
            if (file_exists(public_path('uploads') . $dir . "/$item->imagem") && $item->imagem != "") {
                unlink(public_path('uploads') . $dir . "/$item->imagem");
            }
        } else {
            $fileName = $item[$attr];
            if (file_exists(public_path('uploads') . $dir . "/$fileName") && $fileName != "") {
                unlink(public_path('uploads') . $dir . "/$fileName");
            }
        }
    }

    public function uploadImageArray($file, $dir)
    {
        if (!is_dir(public_path('uploads') . $dir)) {
            mkdir(public_path('uploads') . $dir, 0777, true);
        }

        $ext = $file->getClientOriginalExtension();
        $file_name = Str::random(20) . ".$ext";

        $file->move(public_path('uploads') . $dir, $file_name);
        return $file_name;
    }

    /**
     * Baixa uma imagem de uma URL externa e salva no diretório de uploads
     *
     * @param string $url URL pública da imagem na internet
     * @param string $dir Diretório relativo dentro de public/uploads/
     * @return string|null Nome do arquivo salvo, ou null em caso de erro
     */
    public function uploadFromUrl($url, $dir)
    {
        if (empty($url)) return null;

        // Valida a URL
        if (!filter_var($url, FILTER_VALIDATE_URL)) return null;

        // Garante que o diretório existe
        $uploadDir = public_path('uploads') . $dir;
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        try {
            // Baixa o conteúdo da imagem
            $context = stream_context_create([
                'http' => [
                    'timeout' => 30,
                    'user_agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36'
                ],
                'ssl' => [
                    'verify_peer' => false,
                    'verify_peer_name' => false
                ]
            ]);

            $contents = @file_get_contents($url, false, $context);
            if ($contents === false) return null;

            // Detecta a extensão pelo content-type ou pela URL
            $ext = $this->detectExtensionFromUrl($url, $contents);
            if (!$ext) return null;

            // Gera nome único
            $fileName = Str::random(20) . '.' . $ext;
            $fullPath = $uploadDir . '/' . $fileName;

            // Salva o arquivo
            file_put_contents($fullPath, $contents);

            return $fileName;
        } catch (\Exception $e) {
            return null;
        }
    }

    /**
     * Detecta a extensão do arquivo baseado na URL e/ou conteúdo
     */
    private function detectExtensionFromUrl($url, $contents = null)
    {
        // Tenta extrair extensão da URL
        $pathInfo = pathinfo(parse_url($url, PHP_URL_PATH) ?? '');
        $extFromUrl = strtolower($pathInfo['extension'] ?? '');

        $validExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp', 'bmp', 'svg', 'ico'];

        if (in_array($extFromUrl, $validExtensions)) {
            // Normaliza jpeg para jpg
            return $extFromUrl === 'jpeg' ? 'jpg' : $extFromUrl;
        }

        // Tenta detectar pelo conteúdo (magic bytes)
        if ($contents) {
            $finfo = new \finfo(FILEINFO_MIME_TYPE);
            $mimeType = $finfo->buffer($contents);

            $mimeMap = [
                'image/jpeg' => 'jpg',
                'image/png' => 'png',
                'image/gif' => 'gif',
                'image/webp' => 'webp',
                'image/bmp' => 'bmp',
                'image/svg+xml' => 'svg',
                'image/x-icon' => 'ico',
                'image/vnd.microsoft.icon' => 'ico',
            ];

            if (isset($mimeMap[$mimeType])) {
                return $mimeMap[$mimeType];
            }
        }

        return null;
    }
}
