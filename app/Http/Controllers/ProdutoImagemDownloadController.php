<?php

namespace App\Http\Controllers;

use App\Models\Produto;
use App\Models\GaleriaProduto;
use App\Models\ProdutoVariacao;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use ZipArchive;

class ProdutoImagemDownloadController extends Controller
{

    public function __construct()
    {
        $this->middleware('permission:produtos_view', ['only' => [
            'downloadProdutoZip',
            'downloadSelecionadosZip',
            'downloadImagemPrincipal',
            'downloadImagemGaleria',
        ]]);
    }

    /**
     * Caminho absoluto do diretório de uploads de produtos
     */
    private function getUploadPath()
    {
        return public_path('uploads/produtos');
    }

    /**
     * Retorna o nome do arquivo físico no disco
     */
    private function getFilePath($filename)
    {
        if (empty($filename)) return null;
        $path = $this->getUploadPath() . '/' . $filename;
        return file_exists($path) ? $path : null;
    }

    /**
     * Extrai as informações de arquivos de imagem de um produto (principal, galeria, variações)
     * @return array [ ['path' => string, 'name' => string], ... ]
     */
    private function extrairImagensProduto($produto)
    {
        $arquivos = [];

        // Imagem principal
        if (!empty($produto->imagem)) {
            $filePath = $this->getFilePath($produto->imagem);
            if ($filePath) {
                $ext = pathinfo($produto->imagem, PATHINFO_EXTENSION);
                $arquivos[] = [
                    'path' => $filePath,
                    'name' => 'principal.' . $ext
                ];
            }
        }

        // Galeria
        foreach ($produto->galeria as $g) {
            $filePath = $this->getFilePath($g->imagem);
            if ($filePath) {
                $ext = pathinfo($g->imagem, PATHINFO_EXTENSION);
                $arquivos[] = [
                    'path' => $filePath,
                    'name' => 'galeria_' . $g->id . '.' . $ext
                ];
            }
        }

        // Variações
        $variacoes = ProdutoVariacao::where('produto_id', $produto->id)
            ->whereNotNull('imagem')
            ->where('imagem', '!=', '')
            ->get();
        foreach ($variacoes as $v) {
            $filePath = $this->getFilePath($v->imagem);
            if ($filePath) {
                $ext = pathinfo($v->imagem, PATHINFO_EXTENSION);
                $nomeLimpo = preg_replace('/[^a-zA-Z0-9_-]/', '_', $v->descricao);
                $arquivos[] = [
                    'path' => $filePath,
                    'name' => 'variacao_' . $v->id . '_' . $nomeLimpo . '.' . $ext
                ];
            }
        }

        return $arquivos;
    }

    /**
     * Cria um arquivo ZIP com as imagens dos produtos fornecidos
     * @param array $items Array de [ 'produto' => Produto, 'prefixo' => string ]  
     *                      ou [ 'produto' => Produto ] (sem subpasta)
     * @param string $zipName Nome do arquivo ZIP
     * @return array [ 'zipPath' => string, 'total' => int ]
     */
    private function criarZip(array $items, $zipName)
    {
        $zipPath = storage_path('app/temp/' . $zipName);

        if (!is_dir(storage_path('app/temp'))) {
            mkdir(storage_path('app/temp'), 0755, true);
        }

        $zip = new ZipArchive();
        if ($zip->open($zipPath, ZipArchive::CREATE | ZipArchive::OVERWRITE) !== true) {
            throw new \RuntimeException('Não foi possível criar o arquivo ZIP.');
        }

        $totalAdicionadas = 0;

        foreach ($items as $item) {
            $produto = $item['produto'];
            $prefixo = $item['prefixo'] ?? '';

            $arquivos = $this->extrairImagensProduto($produto);
            foreach ($arquivos as $arq) {
                $zip->addFile($arq['path'], $prefixo . $arq['name']);
                $totalAdicionadas++;
            }
        }

        $zip->close();

        if ($totalAdicionadas === 0 && file_exists($zipPath)) {
            @unlink($zipPath);
        }

        return [
            'zipPath' => $zipPath,
            'total' => $totalAdicionadas
        ];
    }

    /**
     * ========== WEB ==========
     */

    /**
     * Download do ZIP com todas as imagens de um produto (principal + galeria + variações)
     */
    public function downloadProdutoZip($id)
    {
        $produto = Produto::with('galeria')->findOrFail($id);
        __validaObjetoEmpresa($produto);

        $zipName = 'produto_' . $produto->id . '_' . preg_replace('/[^a-zA-Z0-9_-]/', '_', $produto->nome) . '.zip';

        try {
            $result = $this->criarZip([
                ['produto' => $produto, 'prefixo' => '']
            ], $zipName);
        } catch (\RuntimeException $e) {
            session()->flash("flash_error", $e->getMessage());
            return redirect()->back();
        }

        if ($result['total'] === 0) {
            session()->flash("flash_warning", "Este produto não possui imagens.");
            return redirect()->back();
        }

        __createLog(request()->empresa_id, 'Produto', 'download_imagens', "Download ZIP: {$produto->nome} ({$result['total']} imagens)");

        return response()->download($result['zipPath'], $zipName)->deleteFileAfterSend(true);
    }

    /**
     * Download de ZIP com imagens de múltiplos produtos selecionados
     */
    public function downloadSelecionadosZip(Request $request)
    {
        $ids = $request->input('ids', []);

        if (empty($ids) || !is_array($ids)) {
            session()->flash("flash_error", "Selecione pelo menos um produto.");
            return redirect()->back();
        }

        $produtos = Produto::with('galeria')
            ->whereIn('id', $ids)
            ->where('empresa_id', request()->empresa_id)
            ->get();

        if ($produtos->isEmpty()) {
            session()->flash("flash_error", "Nenhum produto encontrado.");
            return redirect()->back();
        }

        $zipName = 'produtos_imagens_' . date('Ymd_His') . '.zip';

        $items = [];
        foreach ($produtos as $produto) {
            $prefixo = $produto->id . '_' . preg_replace('/[^a-zA-Z0-9_-]/', '_', substr($produto->nome, 0, 30)) . '/';
            $items[] = ['produto' => $produto, 'prefixo' => $prefixo];
        }

        try {
            $result = $this->criarZip($items, $zipName);
        } catch (\RuntimeException $e) {
            session()->flash("flash_error", $e->getMessage());
            return redirect()->back();
        }

        if ($result['total'] === 0) {
            session()->flash("flash_warning", "Nenhuma imagem encontrada nos produtos selecionados.");
            return redirect()->back();
        }

        __createLog(request()->empresa_id, 'Produto', 'download_imagens_lote', "Download ZIP lote: {$produtos->count()} produtos ({$result['total']} imagens)");

        return response()->download($result['zipPath'], $zipName)->deleteFileAfterSend(true);
    }

    /**
     * Download da imagem principal do produto
     */
    public function downloadImagemPrincipal($id)
    {
        $produto = Produto::findOrFail($id);
        __validaObjetoEmpresa($produto);

        if (empty($produto->imagem)) {
            session()->flash("flash_warning", "Este produto não possui imagem principal.");
            return redirect()->back();
        }

        $filePath = $this->getFilePath($produto->imagem);
        if (!$filePath) {
            session()->flash("flash_error", "Arquivo de imagem não encontrado no disco.");
            return redirect()->back();
        }

        $ext = pathinfo($produto->imagem, PATHINFO_EXTENSION);
        $nomeArquivo = 'produto_' . $produto->id . '_' . preg_replace('/[^a-zA-Z0-9_-]/', '_', $produto->nome) . '_principal.' . $ext;

        __createLog(request()->empresa_id, 'Produto', 'download_imagem', "Download imagem principal: {$produto->nome}");

        return response()->download($filePath, $nomeArquivo);
    }

    /**
     * Download de uma imagem específica da galeria
     */
    public function downloadImagemGaleria($id)
    {
        $galeria = GaleriaProduto::with('produto')->findOrFail($id);
        __validaObjetoEmpresa($galeria->produto);

        if (empty($galeria->imagem)) {
            session()->flash("flash_warning", "Imagem não encontrada.");
            return redirect()->back();
        }

        $filePath = $this->getFilePath($galeria->imagem);
        if (!$filePath) {
            session()->flash("flash_error", "Arquivo de imagem não encontrado no disco.");
            return redirect()->back();
        }

        $ext = pathinfo($galeria->imagem, PATHINFO_EXTENSION);
        $nomeProduto = preg_replace('/[^a-zA-Z0-9_-]/', '_', $galeria->produto->nome);
        $nomeArquivo = 'produto_' . $galeria->produto_id . '_' . $nomeProduto . '_galeria_' . $galeria->id . '.' . $ext;

        __createLog(request()->empresa_id, 'Produto', 'download_imagem', "Download imagem galeria: {$galeria->produto->nome}");

        return response()->download($filePath, $nomeArquivo);
    }

    /**
     * ========== API (protegida por Token) ==========
     */

    /**
     * API: Download ZIP de todas as imagens de um produto
     */
    public function apiDownloadProdutoZip(Request $request, $id)
    {
        $produto = Produto::with('galeria')->findOrFail($id);
        if ($produto->empresa_id != $request->empresa_id) {
            return response()->json(['error' => 'Produto não pertence à sua empresa.'], 403);
        }

        $zipName = 'produto_' . $produto->id . '_' . preg_replace('/[^a-zA-Z0-9_-]/', '_', $produto->nome) . '.zip';

        try {
            $result = $this->criarZip([
                ['produto' => $produto, 'prefixo' => '']
            ], $zipName);
        } catch (\RuntimeException $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }

        if ($result['total'] === 0) {
            return response()->json(['message' => 'Produto sem imagens.', 'imagens' => 0], 200);
        }

        return response()->download($result['zipPath'], $zipName)->deleteFileAfterSend(true);
    }

    /**
     * API: Download ZIP de múltiplos produtos
     */
    public function apiDownloadLote(Request $request)
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'integer'
        ]);

        $produtos = Produto::with('galeria')
            ->whereIn('id', $request->ids)
            ->where('empresa_id', $request->empresa_id)
            ->get();

        if ($produtos->isEmpty()) {
            return response()->json(['error' => 'Nenhum produto encontrado.'], 404);
        }

        $zipName = 'produtos_imagens_' . date('Ymd_His') . '.zip';

        $items = [];
        foreach ($produtos as $produto) {
            $prefixo = $produto->id . '_' . preg_replace('/[^a-zA-Z0-9_-]/', '_', substr($produto->nome, 0, 30)) . '/';
            $items[] = ['produto' => $produto, 'prefixo' => $prefixo];
        }

        try {
            $result = $this->criarZip($items, $zipName);
        } catch (\RuntimeException $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }

        if ($result['total'] === 0) {
            return response()->json(['message' => 'Nenhuma imagem encontrada.', 'imagens' => 0], 200);
        }

        return response()->download($result['zipPath'], $zipName)->deleteFileAfterSend(true);
    }

    /**
     * API: Obter URLs públicas de todas as imagens de um produto
     */
    public function apiListarImagens(Request $request, $id)
    {
        $produto = Produto::with('galeria')->findOrFail($id);

        if ($produto->empresa_id != $request->empresa_id) {
            return response()->json(['error' => 'Produto não pertence à sua empresa.'], 403);
        }

        $principal = null;
        if (!empty($produto->imagem)) {
            $principal = $produto->img_app;
        }

        $galeria = [];
        foreach ($produto->galeria as $g) {
            $galeria[] = [
                'id' => $g->id,
                'url' => url($g->img),
            ];
        }

        $variacoes = ProdutoVariacao::where('produto_id', $produto->id)
            ->whereNotNull('imagem')
            ->where('imagem', '!=', '')
            ->get();
        $vars = [];
        foreach ($variacoes as $v) {
            $vars[] = [
                'id' => $v->id,
                'descricao' => $v->descricao,
                'url' => url('/uploads/produtos/' . $v->imagem),
            ];
        }

        return response()->json([
            'produto_id' => $produto->id,
            'produto_nome' => $produto->nome,
            'quantidade' => ($principal ? 1 : 0) + count($galeria) + count($vars),
            'imagens' => [
                'principal' => $principal,
                'galeria' => $galeria,
                'variacoes' => $vars,
            ],
        ]);
    }
}
