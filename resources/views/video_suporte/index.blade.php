@extends('layouts.app', ['title' => 'Vídeos de Suporte'])

@section('css')
<style>
    /* ─── Cards de Estatísticas (Padrão Contadores) ─── */
    .stat-card {
        border: 0;
        border-radius: 16px;
        padding: 18px 20px;
        height: 100%;
        color: #fff;
        position: relative;
        overflow: hidden;
        transition: transform .18s ease, box-shadow .18s ease;
    }
    .stat-card:hover { transform: translateY(-3px); }
    .stat-card::after {
        content: '';
        position: absolute;
        top: -44px;
        right: -44px;
        width: 130px;
        height: 130px;
        border-radius: 50%;
        background: rgba(255,255,255,.12);
    }
    .stat-indigo { background: linear-gradient(135deg,#6366f1,#4f46e5); box-shadow: 0 6px 18px rgba(79,70,229,.32); }
    .stat-green  { background: linear-gradient(135deg,#24c98a,#109f61); box-shadow: 0 6px 18px rgba(16,185,129,.32); }
    .stat-red    { background: linear-gradient(135deg,#fb7185,#dc2626); box-shadow: 0 6px 18px rgba(220,38,38,.32); }
    .stat-blue   { background: linear-gradient(135deg,#38bdf8,#0284c7); box-shadow: 0 6px 18px rgba(2,132,199,.32); }

    .stat-card .st-label { font-size: 11px; font-weight: 700; letter-spacing: .05em; text-transform: uppercase; color: rgba(255,255,255,.85); }
    .stat-card .st-value { font-size: 26px; font-weight: 800; color: #fff; margin-top: 4px; line-height: 1.1; }
    .stat-card .st-sub { font-size: 11.5px; color: rgba(255,255,255,.75); margin-top: 4px; }
    .stat-card .st-icon { width: 46px; height: 46px; border-radius: 13px; background: rgba(255,255,255,.22); color: #fff; display: flex; align-items: center; justify-content: center; font-size: 22px; }

    /* ─── Filtro (Padrão Contadores) ─── */
    .filter-wrap { background: #fff; border: 1px solid #e9ecf3; border-radius: 14px; box-shadow: 0 1px 2px rgba(16,24,40,.04); padding: 18px 20px; margin-bottom: 18px; }
    .filter-title { font-size: 13px; font-weight: 700; color: #3f3e6a; text-transform: uppercase; letter-spacing: .5px; }
    .filter-title i { color: #4f46e5; margin-right: 6px; }
    .filter-wrap label { font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: .4px; color: #8c8ca6; margin-bottom: 5px; }
    .filter-wrap .form-control, .filter-wrap .form-select { height: 40px; border-radius: 10px; border: 1px solid #dcdce9; font-size: 13px; background: #fcfdfe; }
    .filter-wrap .form-control:focus, .filter-wrap .form-select:focus { border-color: #4f46e5; box-shadow: 0 0 0 3px rgba(79,70,229,.12); background: #fff; }

    /* ─── Tabela (Padrão Contadores) ─── */
    .tb-wrap { border-radius: 14px; border: 1px solid #eef0f5; overflow: hidden; background: #fff; }
    .tb-wrap table { margin-bottom: 0; }
    .tb-wrap thead th { background: #f8f9fc; color: #5a5a7a; font-weight: 700; font-size: 11px; text-transform: uppercase; letter-spacing: .4px; padding: 13px 16px; border-bottom: 1px solid #e8eaf6; white-space: nowrap; }
    .tb-wrap tbody td { padding: 13px 16px; vertical-align: middle; border-bottom: 1px solid #f0f2f8; font-size: 13.5px; color: #374151; }
    .tb-wrap tbody tr:hover { background: #f5f6fe; }
    .tb-wrap tbody tr:last-child td { border-bottom: none; }

    /* ─── Grade de botões de ação (Padrão Contadores) ─── */
    .act-group { display: inline-flex; gap: 6px; align-items: center; }
    .act-btn { width: 34px; height: 34px; border-radius: 10px; border: 0; display: inline-flex; align-items: center; justify-content: center; font-size: 15px; text-decoration: none; cursor: pointer; transition: transform .15s ease, box-shadow .15s ease; }
    .act-btn:hover { transform: translateY(-2px); text-decoration: none; }
    .act-view    { background: #e0f2fe; color: #0284c7; }
    .act-view:hover    { box-shadow: 0 4px 12px rgba(2,132,199,.3); color: #0284c7; }
    .act-edit    { background: #eef0ff; color: #4f46e5; }
    .act-edit:hover    { box-shadow: 0 4px 12px rgba(79,70,229,.3); color: #4f46e5; }
    .act-del     { background: #fee2e2; color: #dc2626; }
    .act-del:hover     { box-shadow: 0 4px 12px rgba(220,38,38,.3); color: #dc2626; }

    /* ─── Badges / Pills ─── */
    .pill { display: inline-flex; align-items: center; gap: 5px; border-radius: 8px; padding: 4px 10px; font-size: 11.5px; font-weight: 700; cursor: pointer; transition: all 0.2s ease; }
    .pill:hover { opacity: 0.85; transform: scale(1.03); }
    .pill-ok  { background: #dcfce7; color: #15803d; }
    .pill-no  { background: #f1f5f9; color: #64748b; }

    /* ─── Miniatura de Vídeo ─── */
    .video-icon-box {
        width: 44px;
        height: 44px;
        border-radius: 12px;
        background: #eef2ff;
        color: #4f46e5;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 22px;
        flex-shrink: 0;
        transition: transform 0.2s ease;
    }
    .tb-wrap tbody tr:hover .video-icon-box {
        transform: scale(1.08);
        background: #4f46e5;
        color: #fff;
    }

    /* ─── Estado Vazio ─── */
    .empty-state { padding: 52px 20px; text-align: center; }
    .empty-state i { font-size: 52px; color: #c5cae9; display: block; margin-bottom: 12px; }
    .empty-state p { color: #9e9eb8; font-size: 14px; margin: 0; }
</style>
@endsection

@section('content')
<div class="mt-3">
    <div class="row">
        <div class="card border-0 shadow-sm">

            {{-- ═══ CABEÇALHO (PADRÃO CONTADORES) ═══ --}}
            <div class="card-header modulo-header-gradient py-3 px-4">
                <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                    <div>
                        <h4 class="mb-1 modulo-title d-flex align-items-center gap-2 text-white">
                            <i class="ri-video-line"></i>
                            Vídeos de Suporte
                        </h4>
                        <p class="text-white-50 mb-0 modulo-subtitle fs-13">
                            Cadastre vídeos tutoriais em formato MP4 para ensinar os usuários do ERP a realizarem rotinas no sistema.
                        </p>
                    </div>
                    <div class="d-flex gap-2 flex-wrap">
                        <a href="{{ route('central-ajuda.index') }}" target="_blank" class="dash-btn dash-btn-light" title="Visualizar como os clientes enxergam">
                            <i class="ri-external-link-line"></i> Central de Ajuda
                        </a>
                        <a href="{{ route('video-suporte.create') }}" class="dash-btn dash-btn-primary">
                            <i class="ri-add-line"></i> Novo Vídeo
                        </a>
                    </div>
                </div>
            </div>

            <div class="card-body p-4">

                {{-- ═══ CARDS DE ESTATÍSTICAS (PADRÃO CONTADORES) ═══ --}}
                <div class="row g-3 mb-3">
                    <div class="col-6 col-xl-3">
                        <div class="stat-card stat-indigo">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <div class="st-label">Total de Vídeos</div>
                                    <div class="st-value">{{ $stats['total'] }}</div>
                                    <div class="st-sub">Tutoriais cadastrados</div>
                                </div>
                                <div class="st-icon"><i class="ri-film-line"></i></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-6 col-xl-3">
                        <div class="stat-card stat-green">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <div class="st-label">Vídeos Ativos</div>
                                    <div class="st-value">{{ $stats['ativos'] }}</div>
                                    <div class="st-sub">Publicados para clientes</div>
                                </div>
                                <div class="st-icon"><i class="ri-checkbox-circle-line"></i></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-6 col-xl-3">
                        <div class="stat-card stat-red">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <div class="st-label">Inativos / Rascunhos</div>
                                    <div class="st-value">{{ $stats['inativos'] }}</div>
                                    <div class="st-sub">Ocultos no sistema</div>
                                </div>
                                <div class="st-icon"><i class="ri-close-circle-line"></i></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-6 col-xl-3">
                        <div class="stat-card stat-blue">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <div class="st-label">Espaço em Disco</div>
                                    <div class="st-value fs-20">{{ $stats['total_espaco'] }}</div>
                                    <div class="st-sub">{{ $stats['categorias'] }} categorias ativas</div>
                                </div>
                                <div class="st-icon"><i class="ri-hard-drive-2-line"></i></div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- ═══ FILTRO DE BUSCA (PADRÃO CONTADORES) ═══ --}}
                <div class="filter-wrap">
                    <div class="d-flex align-items-center justify-content-between">
                        <h5 class="filter-title mb-0"><i class="ri-search-line"></i> Filtrar Vídeos de Suporte</h5>
                    </div>
                    <div class="mt-3">
                        <form method="GET" action="{{ route('video-suporte.index') }}">
                            <div class="row g-3 align-items-end">
                                <div class="col-md-4 col-12">
                                    <label class="form-label"><i class="ri-text"></i> Título ou Descrição</label>
                                    <input type="text" name="busca" class="form-control" placeholder="Buscar por título, descrição, tags..." value="{{ request('busca') }}">
                                </div>

                                <div class="col-md-2 col-6">
                                    <label class="form-label"><i class="ri-toggle-line"></i> Status</label>
                                    <select name="status" class="form-select">
                                        <option value="">Todos</option>
                                        <option value="ATIVO" {{ request('status') === 'ATIVO' ? 'selected' : '' }}>Ativos</option>
                                        <option value="INATIVO" {{ request('status') === 'INATIVO' ? 'selected' : '' }}>Inativos</option>
                                    </select>
                                </div>

                                <div class="col-md-3 col-6">
                                    <label class="form-label"><i class="ri-folder-line"></i> Categoria</label>
                                    <select name="categoria_id" class="form-select">
                                        <option value="">Todas as Categorias</option>
                                        @foreach($categorias as $cat)
                                            <option value="{{ $cat->id }}" {{ request('categoria_id') == $cat->id ? 'selected' : '' }}>
                                                {{ $cat->nome }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-md-3 col-12">
                                    <div class="d-flex gap-2 w-100">
                                        <button class="btn btn-primary flex-grow-1" type="submit" style="border-radius:10px; height: 40px;">
                                            <i class="ri-search-line me-1"></i> Buscar
                                        </button>
                                        <a class="btn btn-light border px-3 d-flex align-items-center justify-content-center" href="{{ route('video-suporte.index') }}" title="Limpar Filtros" style="border-radius:10px; height: 40px;">
                                            <i class="ri-eraser-line"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                {{-- ═══ TABELA DE VÍDEOS (PADRÃO CONTADORES) ═══ --}}
                <div class="tb-wrap">
                    <div class="table-responsive">
                        <table class="table table-centered table-hover align-middle mb-0">
                            <thead>
                                <tr>
                                    <th style="width: 60px;">Mídia</th>
                                    <th>Título &amp; Descrição</th>
                                    <th>Categoria &amp; Tags</th>
                                    <th>Arquivo MP4</th>
                                    <th>Tamanho</th>
                                    <th class="text-center" style="width: 70px;">Ordem</th>
                                    <th class="text-center" style="width: 100px;">Status</th>
                                    <th class="text-end" style="width: 140px;">Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($data as $item)
                                <tr>
                                    <td>
                                        <div class="video-icon-box" title="Vídeo MP4">
                                            <i class="ri-play-circle-line"></i>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="fw-semibold text-dark fs-14 mb-1">
                                            {{ $item->titulo ?: ($item->pagina ?: 'Sem título') }}
                                        </div>
                                        <div class="fs-12 text-muted" style="max-width: 320px; line-height: 1.4;">
                                            {{ \Illuminate\Support\Str::limit($item->descricao ?: 'Vídeo tutorial do sistema ERP', 85) }}
                                        </div>
                                        <small class="fs-11 text-muted d-block mt-1">
                                            <i class="ri-calendar-line me-1"></i> Cadastrado em {{ $item->created_at ? $item->created_at->format('d/m/Y H:i') : '—' }}
                                        </small>
                                    </td>
                                    <td>
                                        @if($item->categoria)
                                            <span class="badge bg-primary-subtle text-primary mb-1">
                                                <i class="ri-folder-2-line me-1"></i>{{ $item->categoria }}
                                            </span>
                                        @endif
                                        @if(!empty($item->tags_array))
                                            <div class="d-flex flex-wrap gap-1 mt-1">
                                                @foreach(array_slice($item->tags_array, 0, 3) as $tag)
                                                    <span class="badge bg-light text-secondary border fs-10">#{{ $tag }}</span>
                                                @endforeach
                                                @if(count($item->tags_array) > 3)
                                                    <span class="badge bg-light text-muted border fs-10">+{{ count($item->tags_array) - 3 }}</span>
                                                @endif
                                            </div>
                                        @endif
                                    </td>
                                    <td>
                                        @if($item->arquivo_original)
                                            <div class="d-flex align-items-center gap-1 text-truncate font-monospace fs-12" style="max-width: 180px;" title="{{ $item->arquivo_original }}">
                                                <i class="ri-file-video-fill text-danger fs-16"></i>
                                                {{ $item->arquivo_original }}
                                            </div>
                                        @elseif($item->url_video)
                                            <a href="{{ $item->url_video }}" target="_blank" class="text-info fs-12 d-inline-flex align-items-center gap-1">
                                                <i class="ri-external-link-line"></i> Link Externo
                                            </a>
                                        @else
                                            <span class="text-muted fs-12">—</span>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge bg-secondary-subtle text-secondary font-monospace">
                                            {{ $item->tamanho_formatado }}
                                        </span>
                                    </td>
                                    <td class="text-center font-monospace fw-bold">
                                        {{ $item->ordem_exibicao }}
                                    </td>
                                    <td class="text-center">
                                        <span class="pill {{ $item->status === 'ATIVO' ? 'pill-ok' : 'pill-no' }}"
                                              onclick="toggleStatus({{ $item->id }})"
                                              id="status-badge-{{ $item->id }}"
                                              title="Clique para alternar status">
                                            <i class="{{ $item->status === 'ATIVO' ? 'ri-checkbox-circle-line' : 'ri-close-circle-line' }}" id="status-icon-{{ $item->id }}"></i>
                                            <span id="status-text-{{ $item->id }}">{{ $item->status === 'ATIVO' ? 'Ativo' : 'Inativo' }}</span>
                                        </span>
                                    </td>
                                    <td class="text-end">
                                        <form action="{{ route('video-suporte.destroy', $item->id) }}" method="post" id="form-{{$item->id}}" class="m-0">
                                            @method('delete')
                                            @csrf
                                            <div class="act-group justify-content-end">
                                                <button type="button" class="act-btn act-view" title="Assistir Vídeo"
                                                        onclick="abrirPlayerModal('{{ addslashes($item->titulo) }}', '{{ $item->stream_url }}', '{{ addslashes($item->descricao) }}')">
                                                    <i class="ri-play-fill"></i>
                                                </button>
                                                <a class="act-btn act-edit" href="{{ route('video-suporte.edit', [$item->id]) }}" title="Editar Vídeo">
                                                    <i class="ri-pencil-line"></i>
                                                </a>
                                                <button type="button" class="act-btn act-del" title="Excluir Vídeo"
                                                        onclick="confirmarExclusao({{ $item->id }}, '{{ addslashes($item->titulo) }}')">
                                                    <i class="ri-delete-bin-line"></i>
                                                </button>
                                            </div>
                                        </form>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="8">
                                        <div class="empty-state">
                                            <i class="ri-movie-line"></i>
                                            <h5 class="fw-bold text-dark mt-2 mb-1">Nenhum vídeo de suporte cadastrado</h5>
                                            <p class="text-muted fs-13 mb-3">Clique em "Novo Vídeo" para cadastrar o primeiro tutorial.</p>
                                            <a href="{{ route('video-suporte.create') }}" class="dash-btn dash-btn-primary d-inline-flex">
                                                <i class="ri-add-line"></i> Novo Vídeo
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                {{-- ═══ FOOTER & PAGINAÇÃO (PADRÃO CONTADORES) ═══ --}}
                <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mt-3">
                    <div class="fs-12" style="color:#94a3b8;">
                        Exibindo <strong>{{ $data->count() }}</strong> de <strong>{{ $data->total() }}</strong> vídeos tutoriais
                    </div>
                    <div>{!! $data->appends(request()->all())->links() !!}</div>
                </div>

            </div>
        </div>
    </div>
</div>

<!-- ═══ MODAL PLAYER DE VÍDEO ═══ -->
<div class="modal fade" id="modalPlayer" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 14px; overflow: hidden;">
            <div class="modal-header bg-dark text-white border-0 py-2 px-3">
                <h5 class="modal-title fs-14 d-flex align-items-center gap-2" id="modalPlayerTitulo">
                    <i class="ri-play-circle-line text-danger"></i> Tutorial
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close" onclick="fecharPlayerModal()"></button>
            </div>
            <div class="modal-body p-0 bg-black text-center">
                <div class="ratio ratio-16x9">
                    <video id="playerHtml5" controls controlsList="nodownload" style="width: 100%; height: 100%;">
                        <source src="" type="video/mp4" id="playerSource">
                        Seu navegador não suporta reprodução de vídeos HTML5.
                    </video>
                </div>
            </div>
            <div class="modal-footer bg-light py-2 px-3">
                <div class="w-100 text-start">
                    <p class="text-muted fs-12 mb-0" id="modalPlayerDescricao"></p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- ═══ MODAL CONFIRMAÇÃO DE EXCLUSÃO ═══ -->
<div class="modal fade" id="modalConfirmDelete" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content border-0 shadow" style="border-radius: 14px;">
            <div class="modal-body text-center p-4">
                <div class="mx-auto mb-3" style="width: 52px; height: 52px; border-radius: 50%; background: #fee2e2; color: #dc2626; display: flex; align-items: center; justify-content: center; font-size: 24px;">
                    <i class="ri-delete-bin-line"></i>
                </div>
                <h5 class="fw-bold mb-1">Excluir Tutorial?</h5>
                <p class="text-muted fs-13 mb-3">
                    Deseja remover o vídeo <strong id="delete-video-title"></strong>? Ele deixará de ser exibido na Central de Ajuda.
                </p>
                <div class="d-flex justify-content-center gap-2">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal" style="border-radius: 8px;">Cancelar</button>
                    <button type="button" class="btn btn-danger px-3" id="btn-confirm-delete" style="border-radius: 8px;">Sim, Excluir</button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Form oculto para exclusão -->
<form id="form-delete" action="" method="POST" style="display: none;">
    @csrf
    @method('DELETE')
</form>

@endsection

@section('js')
<script>
    function toggleStatus(id) {
        let badge = $(`#status-badge-${id}`);
        let icon = $(`#status-icon-${id}`);
        let text = $(`#status-text-${id}`);

        $.ajax({
            url: `/video-suporte/${id}/status`,
            type: 'PATCH',
            data: { _token: '{{ csrf_token() }}' },
            beforeSend: function () {
                badge.css('opacity', '0.5');
            },
            success: function (res) {
                badge.css('opacity', '1');
                if (res.status === 'ATIVO') {
                    badge.removeClass('pill-no').addClass('pill-ok');
                    icon.removeClass('ri-close-circle-line').addClass('ri-checkbox-circle-line');
                    text.text('Ativo');
                } else {
                    badge.removeClass('pill-ok').addClass('pill-no');
                    icon.removeClass('ri-checkbox-circle-line').addClass('ri-close-circle-line');
                    text.text('Inativo');
                }
                toastr.success(res.message);
            },
            error: function () {
                badge.css('opacity', '1');
                toastr.error('Erro ao alterar status do vídeo.');
            }
        });
    }

    function abrirPlayerModal(titulo, url, descricao) {
        $('#modalPlayerTitulo').text(titulo || 'Vídeo de Suporte');
        $('#modalPlayerDescricao').text(descricao || '');
        let video = document.getElementById('playerHtml5');
        let source = document.getElementById('playerSource');
        source.src = url;
        video.load();
        let modal = new bootstrap.Modal(document.getElementById('modalPlayer'));
        modal.show();
        video.play().catch(e => {});
    }

    function fecharPlayerModal() {
        let video = document.getElementById('playerHtml5');
        if (video) video.pause();
    }

    document.getElementById('modalPlayer').addEventListener('hidden.bs.modal', function () {
        fecharPlayerModal();
    });

    let targetDeleteId = null;
    function confirmarExclusao(id, titulo) {
        targetDeleteId = id;
        $('#delete-video-title').text(`"${titulo}"`);
        let modal = new bootstrap.Modal(document.getElementById('modalConfirmDelete'));
        modal.show();
    }

    $('#btn-confirm-delete').on('click', function () {
        if (targetDeleteId) {
            let form = $('#form-delete');
            form.attr('action', `/video-suporte/${targetDeleteId}`);
            form.submit();
        }
    });
</script>
@endsection
