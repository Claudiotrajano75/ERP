<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\VideoSuporte;
use App\Models\VideoSuporteCategoria;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class CentralAjudaController extends Controller
{
    /**
     * Portal público de suporte para todas as empresas usuárias.
     */
    public function index(Request $request)
    {
        $busca = trim($request->input('busca', ''));
        $categoriaSlug = $request->input('categoria');
        $tagFiltro = trim($request->input('tag', ''));

        $query = VideoSuporte::with('categoriaRelacionada')
            ->ativo()
            ->disponivelPara(Auth::user()->empresa_id ?? null);

        // Busca textual inteligente (título, descrição, categoria, tags)
        if (!empty($busca)) {
            $query->where(function ($q) use ($busca) {
                $q->where('titulo', 'LIKE', "%{$busca}%")
                  ->orWhere('descricao', 'LIKE', "%{$busca}%")
                  ->orWhere('categoria', 'LIKE', "%{$busca}%")
                  ->orWhere('tags', 'LIKE', "%{$busca}%");
            });
        }

        // Filtro por Categoria
        if (!empty($categoriaSlug) && $categoriaSlug !== 'todas') {
            $cat = VideoSuporteCategoria::where('slug', $categoriaSlug)
                ->orWhere('nome', $categoriaSlug)
                ->first();
            if ($cat) {
                $query->where('categoria_id', $cat->id);
            } else {
                $query->where('categoria', $categoriaSlug);
            }
        }

        // Filtro por Tag
        if (!empty($tagFiltro)) {
            $query->where('tags', 'LIKE', "%{$tagFiltro}%");
        }

        // Ordenação padrão da central: ordem_exibicao asc, depois mais recentes
        $videos = $query->orderBy('ordem_exibicao', 'asc')
                        ->orderBy('created_at', 'desc')
                        ->paginate(12)
                        ->appends($request->all());

        // Categorias com contagem de vídeos ativos
        $categorias = VideoSuporteCategoria::where('status', 'ATIVO')
            ->withCount(['videos' => function ($q) {
                $q->where('status', 'ATIVO');
            }])
            ->orderBy('ordem_exibicao', 'asc')
            ->get();

        // Extrair lista única de tags mais populares
        $todasTags = VideoSuporte::ativo()
            ->whereNotNull('tags')
            ->pluck('tags')
            ->flatMap(function ($tagString) {
                return array_map('trim', explode(',', $tagString));
            })
            ->filter()
            ->countBy()
            ->sortDesc()
            ->take(12);

        $totalGeral = VideoSuporte::ativo()->count();

        return view('central_ajuda.index', compact(
            'videos',
            'categorias',
            'todasTags',
            'busca',
            'categoriaSlug',
            'tagFiltro',
            'totalGeral'
        ));
    }

    /**
     * Obter detalhes do vídeo e tutoriais relacionados para a modal.
     */
    public function show($id)
    {
        $video = VideoSuporte::ativo()->findOrFail($id);

        // Vídeos relacionados da mesma categoria
        $relacionados = VideoSuporte::ativo()
            ->where('id', '!=', $video->id)
            ->when($video->categoria_id, function ($q) use ($video) {
                return $q->where('categoria_id', $video->categoria_id);
            })
            ->orderBy('ordem_exibicao', 'asc')
            ->orderBy('created_at', 'desc')
            ->take(4)
            ->get()
            ->map(function ($rel) {
                return [
                    'id' => $rel->id,
                    'titulo' => $rel->titulo,
                    'descricao_curta' => \Illuminate\Support\Str::limit($rel->descricao, 70),
                    'categoria' => $rel->categoria,
                    'tags' => $rel->tags_array,
                    'stream_url' => $rel->stream_url,
                ];
            });

        return response()->json([
            'id' => $video->id,
            'titulo' => $video->titulo,
            'descricao' => $video->descricao,
            'categoria' => $video->categoria ?? ($video->categoriaRelacionada->nome ?? 'Geral'),
            'tags' => $video->tags_array,
            'stream_url' => $video->stream_url,
            'data_publicacao' => $video->created_at ? $video->created_at->format('d/m/Y') : '',
            'tamanho' => $video->tamanho_formatado,
            'duracao' => $video->duracao_formatada,
            'relacionados' => $relacionados,
        ]);
    }

    /**
     * Streaming do vídeo para as empresas.
     */
    public function stream(Request $request, $id)
    {
        $video = VideoSuporte::ativo()->findOrFail($id);

        if (empty($video->arquivo_path) || !Storage::disk('public')->exists($video->arquivo_path)) {
            if (!empty($video->url_video)) {
                return redirect()->away($video->url_video);
            }
            abort(404, 'Vídeo não encontrado no servidor.');
        }

        $path = Storage::disk('public')->path($video->arquivo_path);
        $size = filesize($path);
        $mime = $video->mime_type ?: 'video/mp4';

        $start = 0;
        $end = $size - 1;
        $status = 200;

        $headers = [
            'Content-Type' => $mime,
            'Accept-Ranges' => 'bytes',
            'Content-Disposition' => 'inline; filename="' . ($video->arquivo_original ?: 'tutorial.mp4') . '"',
        ];

        if ($request->server('HTTP_RANGE')) {
            $range = $request->server('HTTP_RANGE');
            if (preg_match('/bytes=\h*(\d+)-(\d*)[\D.*]?/i', $range, $matches)) {
                $start = intval($matches[1]);
                if (!empty($matches[2])) {
                    $end = intval($matches[2]);
                }
            }
            $status = 206;
            $length = $end - $start + 1;
            $headers['Content-Range'] = sprintf('bytes %d-%d/%d', $start, $end, $size);
            $headers['Content-Length'] = $length;
        } else {
            $headers['Content-Length'] = $size;
        }

        $stream = function () use ($path, $start, $end) {
            $file = fopen($path, 'rb');
            fseek($file, $start);
            $buffer = 1024 * 16;
            while (!feof($file) && ($pos = ftell($file)) <= $end) {
                if ($pos + $buffer > $end) {
                    $buffer = $end - $pos + 1;
                }
                echo fread($file, $buffer);
                flush();
            }
            fclose($file);
        };

        return response()->stream($stream, $status, $headers);
    }
}
