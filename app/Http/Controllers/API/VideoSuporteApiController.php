<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\VideoSuporte;
use App\Models\VideoSuporteCategoria;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class VideoSuporteApiController extends Controller
{
    /**
     * API Pública: Listar vídeos ativos
     */
    public function indexPublico(Request $request)
    {
        $busca = $request->input('busca');
        $categoria = $request->input('categoria');
        $tag = $request->input('tag');

        $query = VideoSuporte::ativo();

        if ($busca) {
            $query->where(function ($q) use ($busca) {
                $q->where('titulo', 'LIKE', "%{$busca}%")
                  ->orWhere('descricao', 'LIKE', "%{$busca}%")
                  ->orWhere('tags', 'LIKE', "%{$busca}%");
            });
        }

        if ($categoria) {
            $query->where(function ($q) use ($categoria) {
                $q->where('categoria', $categoria)
                  ->orWhere('categoria_id', $categoria);
            });
        }

        if ($tag) {
            $query->where('tags', 'LIKE', "%{$tag}%");
        }

        $videos = $query->orderBy('ordem_exibicao', 'asc')
                        ->orderBy('created_at', 'desc')
                        ->paginate($request->input('per_page', 12));

        return response()->json($videos, 200);
    }

    /**
     * API Pública: Detalhe de um vídeo
     */
    public function showPublico($id)
    {
        $video = VideoSuporte::ativo()->find($id);
        if (!$video) {
            return response()->json(['error' => 'Vídeo não encontrado'], 404);
        }

        return response()->json($video, 200);
    }

    /**
     * API Pública: Listar categorias
     */
    public function categoriasPublico()
    {
        $categorias = VideoSuporteCategoria::where('status', 'ATIVO')
            ->orderBy('ordem_exibicao')
            ->get();

        return response()->json($categorias, 200);
    }

    /**
     * API Admin: Listar todos os vídeos
     */
    public function indexAdmin(Request $request)
    {
        $query = VideoSuporte::query();

        if ($request->status) {
            $query->where('status', $request->status);
        }

        if ($request->busca) {
            $busca = $request->busca;
            $query->where(function ($q) use ($busca) {
                $q->where('titulo', 'LIKE', "%{$busca}%")
                  ->orWhere('descricao', 'LIKE', "%{$busca}%")
                  ->orWhere('tags', 'LIKE', "%{$busca}%");
            });
        }

        $videos = $query->orderBy('ordem_exibicao', 'asc')->paginate(15);
        return response()->json($videos, 200);
    }

    /**
     * API Admin: Criar vídeo
     */
    public function storeAdmin(Request $request)
    {
        $request->validate([
            'titulo' => 'required|string|max:150',
            'descricao' => 'required|string|max:1000',
            'status' => 'required|in:ATIVO,INATIVO',
        ]);

        $dados = $request->all();
        $dados['usuario_criacao_id'] = Auth::id();
        $video = VideoSuporte::create($dados);

        return response()->json(['message' => 'Vídeo criado com sucesso', 'data' => $video], 201);
    }

    /**
     * API Admin: Detalhe do vídeo
     */
    public function showAdmin($id)
    {
        $video = VideoSuporte::findOrFail($id);
        return response()->json($video, 200);
    }

    /**
     * API Admin: Atualizar dados do vídeo
     */
    public function updateAdmin(Request $request, $id)
    {
        $video = VideoSuporte::findOrFail($id);
        $dados = $request->all();
        $dados['usuario_atualizacao_id'] = Auth::id();
        $video->update($dados);

        return response()->json(['message' => 'Vídeo atualizado com sucesso', 'data' => $video], 200);
    }

    /**
     * API Admin: Remover vídeo (Soft Delete)
     */
    public function destroyAdmin($id)
    {
        $video = VideoSuporte::findOrFail($id);
        $video->delete();

        return response()->json(['message' => 'Vídeo removido com sucesso'], 200);
    }

    /**
     * API Admin: Atualizar status
     */
    public function statusAdmin(Request $request, $id)
    {
        $video = VideoSuporte::findOrFail($id);
        $novoStatus = $request->input('status', ($video->status === 'ATIVO' ? 'INATIVO' : 'ATIVO'));
        $video->status = $novoStatus;
        $video->save();

        return response()->json(['message' => "Status atualizado para {$novoStatus}", 'status' => $novoStatus], 200);
    }

    /**
     * API Admin: Upload de vídeo MP4
     */
    public function uploadAdmin(Request $request, $id)
    {
        $video = VideoSuporte::findOrFail($id);

        if (!$request->hasFile('video_file') || !$request->file('video_file')->isValid()) {
            return response()->json(['error' => 'Arquivo inválido ou não enviado'], 422);
        }

        $file = $request->file('video_file');
        if (strtolower($file->getClientOriginalExtension()) !== 'mp4') {
            return response()->json(['error' => 'Apenas arquivos MP4 são permitidos'], 422);
        }

        if ($video->arquivo_path && Storage::disk('public')->exists($video->arquivo_path)) {
            Storage::disk('public')->delete($video->arquivo_path);
        }

        $nome = Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME)) . '_' . time() . '.mp4';
        $caminho = $file->storeAs('videos_suporte', $nome, 'public');

        $video->update([
            'arquivo_original' => $file->getClientOriginalName(),
            'arquivo_path' => $caminho,
            'arquivo_url' => Storage::disk('public')->url($caminho),
            'mime_type' => 'video/mp4',
            'tamanho_arquivo' => $file->getSize(),
        ]);

        return response()->json(['message' => 'Upload realizado com sucesso', 'data' => $video], 200);
    }
}
