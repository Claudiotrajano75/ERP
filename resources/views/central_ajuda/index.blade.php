@extends('layouts.app', ['title' => 'Central de Ajuda'])

@section('css')
<style>
    /* ─── Filtro (Padrão Oficial do ERP) ─── */
    .filter-wrap {
        background: #fff;
        border: 1px solid #e9ecf3;
        border-radius: 14px;
        box-shadow: 0 1px 2px rgba(16,24,40,.04);
        padding: 18px 20px;
        margin-bottom: 20px;
    }
    .filter-title { font-size: 13px; font-weight: 700; color: #3f3e6a; text-transform: uppercase; letter-spacing: .5px; }
    .filter-title i { color: #4f46e5; margin-right: 6px; }
    .filter-wrap label { font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: .4px; color: #8c8ca6; margin-bottom: 5px; }
    .filter-wrap .form-control, .filter-wrap .form-select { height: 42px; border-radius: 10px; border: 1px solid #dcdce9; font-size: 13.5px; background: #fcfdfe; }
    .filter-wrap .form-control:focus, .filter-wrap .form-select:focus { border-color: #4f46e5; box-shadow: 0 0 0 3px rgba(79,70,229,.12); background: #fff; }

    /* ─── Pílulas de Categorias ─── */
    .category-pills-bar {
        display: flex;
        align-items: center;
        gap: 8px;
        overflow-x: auto;
        padding-bottom: 6px;
        margin-bottom: 22px;
        scrollbar-width: thin;
    }
    .category-pill {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 7px 14px;
        border-radius: 20px;
        font-size: 12.5px;
        font-weight: 600;
        color: #475569;
        background: #ffffff;
        border: 1px solid #e2e8f0;
        text-decoration: none;
        white-space: nowrap;
        transition: all 0.2s ease;
        box-shadow: 0 1px 2px rgba(0, 0, 0, 0.02);
    }
    .category-pill:hover {
        color: #4338ca;
        border-color: #c7d2fe;
        background: #f5f3ff;
        transform: translateY(-1px);
        text-decoration: none;
    }
    .category-pill.active {
        color: #ffffff;
        background: linear-gradient(135deg, #4f46e5 0%, #4338ca 100%);
        border-color: #4338ca;
        box-shadow: 0 4px 12px rgba(79, 70, 229, 0.3);
    }
    .category-pill .pill-count {
        font-size: 10.5px;
        background: rgba(0, 0, 0, 0.07);
        padding: 2px 6px;
        border-radius: 10px;
    }
    .category-pill.active .pill-count {
        background: rgba(255, 255, 255, 0.25);
    }

    /* ─── Cards dos Tutoriais em Vídeo ─── */
    .video-tutorial-card {
        border-radius: 14px;
        background: #ffffff;
        border: 1px solid #eef0f5;
        box-shadow: 0 2px 8px rgba(16,24,40,.04);
        transition: all 0.22s cubic-bezier(0.4, 0, 0.2, 1);
        overflow: hidden;
        height: 100%;
        display: flex;
        flex-direction: column;
    }
    .video-tutorial-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 12px 28px rgba(79, 70, 229, 0.12);
        border-color: #c7d2fe;
    }

    .video-thumb-cover {
        height: 160px;
        background: linear-gradient(135deg, #1e1b4b 0%, #312e81 100%);
        position: relative;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        overflow: hidden;
    }
    .video-thumb-cover::before {
        content: '';
        position: absolute;
        inset: 0;
        background: radial-gradient(circle at center, rgba(99, 102, 241, 0.35) 0%, transparent 70%);
        transition: transform 0.3s ease;
    }
    .video-tutorial-card:hover .video-thumb-cover::before {
        transform: scale(1.3);
    }
    .play-button-overlay {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.95);
        color: #4f46e5;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 24px;
        box-shadow: 0 6px 18px rgba(0, 0, 0, 0.28);
        transition: all 0.22s ease;
        z-index: 2;
    }
    .video-tutorial-card:hover .play-button-overlay {
        transform: scale(1.12);
        background: #ffffff;
        color: #4338ca;
        box-shadow: 0 8px 24px rgba(79, 70, 229, 0.45);
    }
    .video-badge-category {
        position: absolute;
        top: 10px;
        left: 10px;
        background: rgba(15, 23, 42, 0.8);
        backdrop-filter: blur(6px);
        color: #ffffff;
        font-size: 11px;
        font-weight: 700;
        padding: 3px 9px;
        border-radius: 6px;
        z-index: 2;
        letter-spacing: 0.2px;
    }
    .video-badge-duration {
        position: absolute;
        bottom: 10px;
        right: 10px;
        background: rgba(0, 0, 0, 0.82);
        backdrop-filter: blur(4px);
        color: #ffffff;
        font-size: 11px;
        font-weight: 700;
        padding: 2px 7px;
        border-radius: 4px;
        z-index: 2;
        font-family: monospace;
    }

    .video-card-body {
        padding: 16px;
        flex-grow: 1;
        display: flex;
        flex-direction: column;
    }
    .video-card-title {
        font-size: 14.5px;
        font-weight: 700;
        color: #1e293b;
        line-height: 1.4;
        margin-bottom: 6px;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    .video-card-description {
        font-size: 12.5px;
        color: #64748b;
        line-height: 1.5;
        margin-bottom: 12px;
        display: -webkit-box;
        -webkit-line-clamp: 3;
        -webkit-box-orient: vertical;
        overflow: hidden;
        flex-grow: 1;
    }
    .video-card-tags {
        display: flex;
        flex-wrap: wrap;
        gap: 4px;
        margin-bottom: 14px;
    }
    .tag-chip {
        font-size: 10.5px;
        padding: 2px 7px;
        background: #f1f5f9;
        color: #475569;
        border-radius: 4px;
        font-weight: 600;
        text-decoration: none;
        transition: all 0.15s ease;
    }
    .tag-chip:hover {
        background: #e0e7ff;
        color: #4338ca;
        text-decoration: none;
    }
    .btn-watch-video {
        width: 100%;
        border-radius: 10px;
        font-weight: 600;
        font-size: 12.5px;
        padding: 8px 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
        background: #f8fafc;
        color: #334155;
        border: 1px solid #cbd5e1;
        transition: all 0.2s ease;
        text-decoration: none;
    }
    .btn-watch-video:hover {
        background: #4f46e5;
        color: #ffffff;
        border-color: #4f46e5;
        box-shadow: 0 4px 12px rgba(79, 70, 229, 0.25);
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

            <!-- ═══ CABEÇALHO PADRÃO OFICIAL DO ERP ═══ -->
            <div class="card-header modulo-header-gradient py-3 px-4">
                <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                    <div>
                        <h4 class="mb-1 modulo-title d-flex align-items-center gap-2">
                            <i class="ri-video-chat-line"></i>
                            Central de Ajuda
                        </h4>
                        <p class="text-muted mb-0 modulo-subtitle fs-13">
                            Assista aos vídeos tutoriais e aprenda a utilizar todos os recursos do sistema com rapidez.
                        </p>
                    </div>
                    <div class="d-flex gap-2 flex-wrap">
                        <a href="{{ route('central-ajuda.index') }}" class="dash-btn dash-btn-light">
                            <i class="ri-refresh-line"></i> Atualizar
                        </a>
                        @if(__isMaster())
                            <a href="{{ route('video-suporte.create') }}" class="dash-btn dash-btn-primary">
                                <i class="ri-add-line"></i> Novo Vídeo
                            </a>
                        @endif
                    </div>
                </div>
            </div>

            <div class="card-body p-4">

                <!-- ═══ FILTRO DE BUSCA (PADRÃO DO ERP) ═══ -->
                <div class="filter-wrap">
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <h5 class="filter-title mb-0"><i class="ri-search-line"></i> O que você deseja aprender hoje?</h5>
                        @if(request('busca') || request('categoria') || request('tag'))
                            <a href="{{ route('central-ajuda.index') }}" class="text-danger fs-12 text-decoration-underline fw-semibold">
                                <i class="ri-close-circle-line me-1"></i> Limpar Filtros
                            </a>
                        @endif
                    </div>
                    <form method="GET" action="{{ route('central-ajuda.index') }}">
                        @if(request('categoria'))
                            <input type="hidden" name="categoria" value="{{ request('categoria') }}">
                        @endif
                        <div class="row g-2 align-items-end">
                            <div class="col-md-9 col-12">
                                <label class="form-label"><i class="ri-search-2-line"></i> Termo de Busca (Título, rotina, módulo ou palavra-chave)</label>
                                <input type="text"
                                       name="busca"
                                       id="mainSearchInput"
                                       class="form-control"
                                       placeholder="Digite o que deseja aprender... (Ex.: cadastrar produto, emitir nfce, fechar caixa, tributação)"
                                       value="{{ request('busca') }}">
                            </div>
                            <div class="col-md-3 col-12">
                                <button type="submit" class="btn btn-primary w-100 fw-bold d-flex align-items-center justify-content-center gap-2" style="border-radius:10px; height: 42px;">
                                    <i class="ri-search-line"></i> Buscar Tutorial
                                </button>
                            </div>
                        </div>
                    </form>

                    <!-- Tags populares -->
                    @if($todasTags && count($todasTags) > 0)
                        <div class="d-flex align-items-center gap-1 flex-wrap mt-3 pt-2 border-top">
                            <span class="fs-11 text-muted fw-bold text-uppercase me-1"><i class="ri-price-tag-3-line"></i> Assuntos:</span>
                            @foreach($todasTags as $tagItem => $count)
                                <a href="{{ route('central-ajuda.index', array_merge(request()->except('page'), ['tag' => $tagItem])) }}"
                                   class="tag-chip {{ request('tag') === $tagItem ? 'bg-primary text-white' : '' }}">
                                    #{{ $tagItem }}
                                </a>
                            @endforeach
                        </div>
                    @endif
                </div>

                <!-- ═══ ABAS / PÍLULAS DE CATEGORIAS ═══ -->
                <div class="category-pills-bar">
                    <a href="{{ route('central-ajuda.index', array_merge(request()->except('categoria', 'page'))) }}"
                       class="category-pill {{ empty(request('categoria')) || request('categoria') === 'todas' ? 'active' : '' }}">
                        <i class="ri-apps-line"></i> Todas as Aulas
                        <span class="pill-count">{{ $totalGeral }}</span>
                    </a>

                    @foreach($categorias as $cat)
                        @if($cat->videos_count > 0 || !empty(request('categoria')))
                            <a href="{{ route('central-ajuda.index', array_merge(request()->except('page'), ['categoria' => $cat->slug])) }}"
                               class="category-pill {{ request('categoria') === $cat->slug ? 'active' : '' }}">
                                <i class="ri-folder-line"></i> {{ $cat->nome }}
                                <span class="pill-count">{{ $cat->videos_count }}</span>
                            </a>
                        @endif
                    @endforeach
                </div>

                <!-- ═══ GRADE DE VÍDEOS TUTORIAIS ═══ -->
                <div class="row g-3">
                    @forelse($videos as $video)
                        <div class="col-12 col-md-6 col-lg-4 col-xl-3">
                            <div class="video-tutorial-card">
                                <div class="video-thumb-cover" onclick="abrirPlayerModal({{ $video->id }})">
                                    @if($video->categoria)
                                        <span class="video-badge-category">
                                            <i class="ri-folder-2-line me-1"></i>{{ $video->categoria }}
                                        </span>
                                    @endif

                                    <div class="play-button-overlay">
                                        <i class="ri-play-fill"></i>
                                    </div>

                                    @if($video->duracao_formatada)
                                        <span class="video-badge-duration">{{ $video->duracao_formatada }}</span>
                                    @elseif($video->tamanho_formatado && $video->tamanho_formatado !== '—')
                                        <span class="video-badge-duration">{{ $video->tamanho_formatado }}</span>
                                    @endif
                                </div>

                                <div class="video-card-body">
                                    <h5 class="video-card-title" title="{{ $video->titulo }}">
                                        {{ $video->titulo }}
                                    </h5>
                                    <p class="video-card-description">
                                        {{ $video->descricao }}
                                    </p>

                                    @if(!empty($video->tags_array))
                                        <div class="video-card-tags">
                                            @foreach(array_slice($video->tags_array, 0, 3) as $tag)
                                                <a href="{{ route('central-ajuda.index', ['tag' => $tag]) }}" class="tag-chip">
                                                    #{{ $tag }}
                                                </a>
                                            @endforeach
                                        </div>
                                    @endif

                                    <button type="button" class="btn-watch-video mt-auto" onclick="abrirPlayerModal({{ $video->id }})">
                                        <i class="ri-play-circle-line"></i> Assistir Vídeo Aula
                                    </button>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-12">
                            <div class="empty-state">
                                <i class="ri-search-eye-line"></i>
                                <h5 class="fw-bold text-dark mt-2 mb-1">Não encontramos nenhum vídeo para sua pesquisa</h5>
                                <p class="text-muted fs-13 mb-3">
                                    Tente buscar por termos como:
                                    <a href="{{ route('central-ajuda.index', ['busca' => 'produto']) }}" class="text-primary fw-bold mx-1">produto</a>,
                                    <a href="{{ route('central-ajuda.index', ['busca' => 'venda']) }}" class="text-primary fw-bold mx-1">venda</a>,
                                    <a href="{{ route('central-ajuda.index', ['busca' => 'caixa']) }}" class="text-primary fw-bold mx-1">caixa</a>,
                                    <a href="{{ route('central-ajuda.index', ['busca' => 'estoque']) }}" class="text-primary fw-bold mx-1">estoque</a> ou
                                    <a href="{{ route('central-ajuda.index', ['busca' => 'nota fiscal']) }}" class="text-primary fw-bold mx-1">nota fiscal</a>.
                                </p>
                                <a href="{{ route('central-ajuda.index') }}" class="dash-btn dash-btn-primary d-inline-flex">
                                    <i class="ri-refresh-line"></i> Ver Todas as Aulas
                                </a>
                            </div>
                        </div>
                    @endforelse
                </div>

                <!-- ═══ FOOTER & PAGINAÇÃO ═══ -->
                <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mt-4 pt-3 border-top">
                    <div class="fs-12" style="color:#94a3b8;">
                        Exibindo <strong>{{ $videos->count() }}</strong> de <strong>{{ $videos->total() }}</strong> vídeo aulas disponíveis
                    </div>
                    <div>{!! $videos->appends(request()->all())->links() !!}</div>
                </div>

            </div>
        </div>
    </div>
</div>

<!-- ═══ MODAL PLAYER CINEMATOGRÁFICO DE VÍDEO ═══ -->
<div class="modal fade" id="modalAjudaPlayer" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 16px; overflow: hidden; background: #0f172a;">

            <!-- Header do Modal -->
            <div class="modal-header border-0 py-3 px-4" style="background: rgba(15, 23, 42, 0.95);">
                <div>
                    <span class="badge bg-primary-subtle text-primary mb-1 fs-11" id="modalPlayerCategoria">Categoria</span>
                    <h5 class="modal-title text-white fs-18 fw-bold mb-0" id="modalPlayerTitulo">Tutorial</h5>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Fechar" onclick="fecharPlayerModal()"></button>
            </div>

            <!-- Corpo do Modal com Player HTML5 -->
            <div class="modal-body p-0">
                <div class="ratio ratio-16x9" style="background: #000;">
                    <video id="playerAjudaHtml5" controls controlsList="nodownload" preload="metadata" style="width: 100%; height: 100%;">
                        <source src="" type="video/mp4" id="playerAjudaSource">
                        Seu navegador não suporta reprodução de vídeos HTML5.
                    </video>
                </div>

                <div class="p-4" style="background: #1e293b; color: #f8fafc;">
                    <div class="row g-4">
                        <div class="col-lg-8 col-12">
                            <h6 class="text-white-50 fs-12 text-uppercase fw-bold mb-2">Sobre esta aula</h6>
                            <p class="fs-14 text-light opacity-90 mb-3" id="modalPlayerDescricao" style="line-height: 1.6;"></p>

                            <div class="d-flex align-items-center gap-2 flex-wrap" id="modalPlayerTags">
                                <!-- Tags dinâmicas -->
                            </div>
                        </div>

                        <!-- Coluna Lateral de Informações e Relacionados -->
                        <div class="col-lg-4 col-12 border-start border-secondary border-opacity-25">
                            <div class="mb-3">
                                <div class="text-white-50 fs-11 text-uppercase fw-semibold mb-1">Publicado em</div>
                                <div class="text-white fs-13 fw-medium" id="modalPlayerData">—</div>
                            </div>

                            <div id="boxRelacionados" class="d-none">
                                <div class="text-white-50 fs-11 text-uppercase fw-bold mb-2">
                                    <i class="ri-movie-2-line me-1"></i> Aulas Relacionadas
                                </div>
                                <div class="d-flex flex-column gap-2" id="listaRelacionados">
                                    <!-- Aulas relacionadas -->
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Rodapé do Modal -->
            <div class="modal-footer border-0 py-2 px-4 justify-content-between" style="background: rgba(15, 23, 42, 0.98);">
                <small class="text-white-50 fs-11">
                    <i class="ri-information-line me-1"></i> Use os controles do player para tela cheia ou ajustar o volume.
                </small>
                <button type="button" class="btn btn-outline-light btn-sm px-3" data-bs-dismiss="modal" onclick="fecharPlayerModal()">
                    <i class="ri-arrow-left-line me-1"></i> Voltar para Vídeos
                </button>
            </div>

        </div>
    </div>
</div>
@endsection

@section('js')
<script>
    let playerVideo = document.getElementById('playerAjudaHtml5');
    let playerSource = document.getElementById('playerAjudaSource');
    let modalElement = document.getElementById('modalAjudaPlayer');
    let bsModalPlayer = null;

    function abrirPlayerModal(id) {
        if (!bsModalPlayer) {
            bsModalPlayer = new bootstrap.Modal(modalElement);
        }

        $.ajax({
            url: `/central-ajuda/${id}`,
            type: 'GET',
            dataType: 'json',
            success: function (video) {
                $('#modalPlayerTitulo').text(video.titulo);
                $('#modalPlayerCategoria').text(video.categoria || 'Geral');
                $('#modalPlayerDescricao').text(video.descricao || 'Sem descrição.');
                $('#modalPlayerData').text(video.data_publicacao || 'Recente');

                let tagsHtml = '';
                if (video.tags && video.tags.length > 0) {
                    video.tags.forEach(t => {
                        tagsHtml += `<span class="badge bg-white bg-opacity-10 text-white border border-white border-opacity-25 fs-11">#${t}</span>`;
                    });
                }
                $('#modalPlayerTags').html(tagsHtml);

                let boxRel = $('#boxRelacionados');
                let listaRel = $('#listaRelacionados');
                listaRel.empty();

                if (video.relacionados && video.relacionados.length > 0) {
                    video.relacionados.forEach(r => {
                        listaRel.append(`
                            <div class="p-2 rounded bg-dark bg-opacity-50 border border-secondary border-opacity-25"
                                 style="cursor: pointer; transition: all 0.2s ease;"
                                 onclick="abrirPlayerModal(${r.id})">
                                <div class="text-white fs-12 fw-bold text-truncate"><i class="ri-play-fill text-primary me-1"></i>${r.titulo}</div>
                                <div class="text-white-50 fs-11 text-truncate">${r.descricao_curta || ''}</div>
                            </div>
                        `);
                    });
                    boxRel.removeClass('d-none');
                } else {
                    boxRel.addClass('d-none');
                }

                playerSource.src = video.stream_url;
                playerVideo.load();
                bsModalPlayer.show();
                playerVideo.play().catch(e => console.log('Autoplay prevenido:', e));
            },
            error: function () {
                toastr.error('Não foi possível carregar o vídeo tutorial.');
            }
        });
    }

    function fecharPlayerModal() {
        if (playerVideo) {
            playerVideo.pause();
        }
    }

    modalElement.addEventListener('hidden.bs.modal', function () {
        fecharPlayerModal();
    });
</script>
@endsection
