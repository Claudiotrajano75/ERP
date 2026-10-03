<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\VideoSuporte;
use App\Models\VideoSuporteCategoria;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class VideoSuporteController extends Controller
{
    /**
     * Tamanho máximo configurável de vídeo (em megabytes).
     */
    protected function maxVideoSizeMb(): int
    {
        return (int) env('MAX_VIDEO_SUporte_SIZE_MB', 500);
    }

    /**
     * Listagem administrativa de vídeos para o SuperAdmin.
     */
    public function index(Request $request)
    {
        $busca = $request->input('busca', $request->input('pagina'));
        $status = $request->input('status');
        $categoriaId = $request->input('categoria_id');
        $ordem = $request->input('ordem', 'ordem_exibicao_asc');

        $query = VideoSuporte::with('categoriaRelacionada');

        if (!empty($busca)) {
            $query->where(function ($q) use ($busca) {
                $q->where('titulo', 'LIKE', "%{$busca}%")
                  ->orWhere('descricao', 'LIKE', "%{$busca}%")
                  ->orWhere('tags', 'LIKE', "%{$busca}%")
                  ->orWhere('categoria', 'LIKE', "%{$busca}%")
                  ->orWhere('arquivo_original', 'LIKE', "%{$busca}%")
                  ->orWhere('pagina', 'LIKE', "%{$busca}%");
            });
        }

        if (!empty($status) && in_array($status, ['ATIVO', 'INATIVO'])) {
            $query->where('status', $status);
        }

        if (!empty($categoriaId)) {
            $query->where('categoria_id', $categoriaId);
        }

        // Ordenação
        switch ($ordem) {
            case 'titulo_asc':
                $query->orderBy('titulo', 'asc');
                break;
            case 'titulo_desc':
                $query->orderBy('titulo', 'desc');
                break;
            case 'data_desc':
                $query->orderBy('created_at', 'desc');
                break;
            case 'data_asc':
                $query->orderBy('created_at', 'asc');
                break;
            case 'tamanho_desc':
                $query->orderBy('tamanho_arquivo', 'desc');
                break;
            case 'ordem_exibicao_asc':
            default:
                $query->orderBy('ordem_exibicao', 'asc')->orderBy('created_at', 'desc');
                break;
        }

        // KPIs
        $stats = [
            'total' => VideoSuporte::count(),
            'ativos' => VideoSuporte::where('status', 'ATIVO')->count(),
            'inativos' => VideoSuporte::where('status', 'INATIVO')->count(),
            'total_bytes' => VideoSuporte::sum('tamanho_arquivo') ?: 0,
            'categorias' => VideoSuporteCategoria::where('status', 'ATIVO')->count(),
        ];

        // Formatar tamanho total
        $bytes = $stats['total_bytes'];
        $power = $bytes > 0 ? floor(log($bytes, 1024)) : 0;
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        $stats['total_espaco'] = $bytes > 0 ? number_format($bytes / pow(1024, $power), 1, ',', '.') . ' ' . ($units[$power] ?? 'MB') : '0 MB';

        $data = $query->paginate(env("PAGINACAO", 15))->appends($request->all());
        $categorias = VideoSuporteCategoria::where('status', 'ATIVO')->orderBy('ordem_exibicao')->get();

        return view('video_suporte.index', compact('data', 'stats', 'categorias'));
    }

    /**
     * Tela de criação.
     */
    public function create()
    {
        $categorias = VideoSuporteCategoria::where('status', 'ATIVO')->orderBy('ordem_exibicao')->get();
        $maxSizeMb = $this->maxVideoSizeMb();
        return view('video_suporte.create', compact('categorias', 'maxSizeMb'));
    }

    /**
     * Armazenar novo vídeo.
     */
    public function store(Request $request)
    {
        $maxKb = $this->maxVideoSizeMb() * 1024;

        $request->validate([
            'titulo' => 'required|string|max:150',
            'descricao' => 'required|string|max:1000',
            'categoria_id' => 'nullable|exists:video_suporte_categorias,id',
            'categoria' => 'nullable|string|max:100',
            'tags' => 'nullable|string',
            'status' => 'required|in:ATIVO,INATIVO',
            'ordem_exibicao' => 'nullable|integer',
            'tipo_publicacao' => 'nullable|in:GLOBAL,EMPRESA_ESPECIFICA',
            'video_file' => 'nullable|file|mimes:mp4|max:' . $maxKb,
            'url_video' => 'nullable|string|max:255',
        ], [
            'titulo.required' => 'O título do vídeo é obrigatório.',
            'titulo.max' => 'O título não pode ultrapassar 150 caracteres.',
            'descricao.required' => 'A descrição é obrigatória.',
            'descricao.max' => 'A descrição não pode ultrapassar 1.000 caracteres.',
            'video_file.mimes' => 'Apenas arquivos no formato MP4 são permitidos.',
            'video_file.max' => 'O tamanho do vídeo não pode exceder ' . $this->maxVideoSizeMb() . ' MB.',
        ]);

        try {
            $dados = $request->except(['video_file', '_token']);
            $dados['ordem_exibicao'] = (int) ($request->ordem_exibicao ?? 0);
            $dados['usuario_criacao_id'] = Auth::id();
            $dados['tipo_publicacao'] = $request->tipo_publicacao ?? 'GLOBAL';

            // Categoria sincronizada
            if (!empty($request->categoria_id)) {
                $cat = VideoSuporteCategoria::find($request->categoria_id);
                if ($cat) {
                    $dados['categoria'] = $cat->nome;
                }
            }

            // Upload do arquivo MP4 se fornecido
            if ($request->hasFile('video_file') && $request->file('video_file')->isValid()) {
                $file = $request->file('video_file');
                $originalName = $file->getClientOriginalName();
                $extension = strtolower($file->getClientOriginalExtension());
                $size = $file->getSize();
                $mime = $file->getMimeType();

                if ($extension !== 'mp4') {
                    if ($request->ajax()) {
                        return response()->json(['success' => false, 'message' => 'Apenas arquivos MP4 são permitidos.'], 422);
                    }
                    session()->flash('flash_error', 'Apenas arquivos MP4 são permitidos.');
                    return redirect()->back()->withInput();
                }

                $novoNome = Str::slug(pathinfo($originalName, PATHINFO_FILENAME)) . '_' . time() . '.' . $extension;
                $caminho = $file->storeAs('videos_suporte', $novoNome, 'public');

                $dados['arquivo_original'] = $originalName;
                $dados['arquivo_path'] = $caminho;
                $dados['mime_type'] = $mime ?: 'video/mp4';
                $dados['tamanho_arquivo'] = $size;
                $dados['arquivo_url'] = Storage::disk('public')->url($caminho);
            }

            $video = VideoSuporte::create($dados);

            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Vídeo cadastrado com sucesso!',
                    'redirect' => route('video-suporte.index'),
                    'id' => $video->id
                ]);
            }

            session()->flash('flash_success', 'Vídeo cadastrado com sucesso!');
            return redirect()->route('video-suporte.index');

        } catch (\Exception $e) {
            if ($request->ajax()) {
                return response()->json(['success' => false, 'message' => 'Erro ao salvar vídeo: ' . $e->getMessage()], 500);
            }
            session()->flash('flash_error', 'Algo deu errado ao cadastrar o vídeo: ' . $e->getMessage());
            return redirect()->back()->withInput();
        }
    }

    /**
     * Tela de edição.
     */
    public function edit($id)
    {
        $item = VideoSuporte::findOrFail($id);
        $categorias = VideoSuporteCategoria::where('status', 'ATIVO')->orderBy('ordem_exibicao')->get();
        $maxSizeMb = $this->maxVideoSizeMb();
        return view('video_suporte.edit', compact('item', 'categorias', 'maxSizeMb'));
    }

    /**
     * Atualização do vídeo.
     */
    public function update(Request $request, $id)
    {
        $item = VideoSuporte::findOrFail($id);
        $maxKb = $this->maxVideoSizeMb() * 1024;

        $request->validate([
            'titulo' => 'required|string|max:150',
            'descricao' => 'required|string|max:1000',
            'categoria_id' => 'nullable|exists:video_suporte_categorias,id',
            'categoria' => 'nullable|string|max:100',
            'tags' => 'nullable|string',
            'status' => 'required|in:ATIVO,INATIVO',
            'ordem_exibicao' => 'nullable|integer',
            'tipo_publicacao' => 'nullable|in:GLOBAL,EMPRESA_ESPECIFICA',
            'video_file' => 'nullable|file|mimes:mp4|max:' . $maxKb,
            'url_video' => 'nullable|string|max:255',
        ], [
            'titulo.required' => 'O título do vídeo é obrigatório.',
            'titulo.max' => 'O título não pode ultrapassar 150 caracteres.',
            'descricao.required' => 'A descrição é obrigatória.',
            'descricao.max' => 'A descrição não pode ultrapassar 1.000 caracteres.',
            'video_file.mimes' => 'Apenas arquivos no formato MP4 são permitidos.',
            'video_file.max' => 'O tamanho do vídeo não pode exceder ' . $this->maxVideoSizeMb() . ' MB.',
        ]);

        try {
            $dados = $request->except(['video_file', '_token', '_method']);
            $dados['ordem_exibicao'] = (int) ($request->ordem_exibicao ?? 0);
            $dados['usuario_atualizacao_id'] = Auth::id();

            // Categoria sincronizada
            if (!empty($request->categoria_id)) {
                $cat = VideoSuporteCategoria::find($request->categoria_id);
                if ($cat) {
                    $dados['categoria'] = $cat->nome;
                }
            }

            // Substituição do arquivo MP4 se novo arquivo for enviado
            if ($request->hasFile('video_file') && $request->file('video_file')->isValid()) {
                $file = $request->file('video_file');
                $originalName = $file->getClientOriginalName();
                $extension = strtolower($file->getClientOriginalExtension());
                $size = $file->getSize();
                $mime = $file->getMimeType();

                if ($extension !== 'mp4') {
                    if ($request->ajax()) {
                        return response()->json(['success' => false, 'message' => 'Apenas arquivos MP4 são permitidos.'], 422);
                    }
                    session()->flash('flash_error', 'Apenas arquivos MP4 são permitidos.');
                    return redirect()->back()->withInput();
                }

                // Remover arquivo anterior se existir no storage
                if (!empty($item->arquivo_path) && Storage::disk('public')->exists($item->arquivo_path)) {
                    Storage::disk('public')->delete($item->arquivo_path);
                }

                $novoNome = Str::slug(pathinfo($originalName, PATHINFO_FILENAME)) . '_' . time() . '.' . $extension;
                $caminho = $file->storeAs('videos_suporte', $novoNome, 'public');

                $dados['arquivo_original'] = $originalName;
                $dados['arquivo_path'] = $caminho;
                $dados['mime_type'] = $mime ?: 'video/mp4';
                $dados['tamanho_arquivo'] = $size;
                $dados['arquivo_url'] = Storage::disk('public')->url($caminho);
            }

            $item->update($dados);

            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Vídeo atualizado com sucesso!',
                    'redirect' => route('video-suporte.index'),
                    'id' => $item->id
                ]);
            }

            session()->flash('flash_success', 'Vídeo atualizado com sucesso!');
            return redirect()->route('video-suporte.index');

        } catch (\Exception $e) {
            if ($request->ajax()) {
                return response()->json(['success' => false, 'message' => 'Erro ao atualizar: ' . $e->getMessage()], 500);
            }
            session()->flash('flash_error', 'Algo deu errado: ' . $e->getMessage());
            return redirect()->back()->withInput();
        }
    }

    /**
     * Alternar status Ativo / Inativo via AJAX.
     */
    public function toggleStatus($id)
    {
        $item = VideoSuporte::findOrFail($id);
        $novoStatus = ($item->status === 'ATIVO') ? 'INATIVO' : 'ATIVO';
        $item->status = $novoStatus;
        $item->usuario_atualizacao_id = Auth::id();
        $item->save();

        return response()->json([
            'success' => true,
            'status' => $novoStatus,
            'message' => "Vídeo marcado como {$novoStatus} com sucesso!"
        ]);
    }

    /**
     * Excluir (Soft Delete).
     */
    public function destroy($id)
    {
        $item = VideoSuporte::findOrFail($id);

        try {
            $item->usuario_atualizacao_id = Auth::id();
            $item->save();
            $item->delete(); // Soft Delete

            session()->flash('flash_success', 'Vídeo removido com sucesso!');
        } catch (\Exception $e) {
            session()->flash('flash_error', 'Algo deu errado: ' . $e->getMessage());
        }

        return redirect()->route('video-suporte.index');
    }

    /**
     * Streaming seguro com suporte a HTTP 206 Partial Content (Seek/Time scrubbing).
     */
    public function stream(Request $request, $id)
    {
        $video = VideoSuporte::findOrFail($id);

        if (empty($video->arquivo_path) || !Storage::disk('public')->exists($video->arquivo_path)) {
            // Se for link externo cadastrado
            if (!empty($video->url_video)) {
                return redirect()->away($video->url_video);
            }
            abort(404, 'Arquivo de vídeo não encontrado no servidor.');
        }

        $path = Storage::disk('public')->path($video->arquivo_path);

        if (!file_exists($path)) {
            abort(404, 'Arquivo de vídeo não encontrado no disco.');
        }

        return response()->file($path, [
            'Content-Type' => $video->mime_type ?: 'video/mp4',
            'Accept-Ranges' => 'bytes',
            'Content-Disposition' => 'inline; filename="' . ($video->arquivo_original ?: 'tutorial.mp4') . '"',
        ]);
    }
}
