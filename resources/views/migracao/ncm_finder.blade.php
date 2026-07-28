@extends('layouts.app', ['title' => 'Busca Automática de NCM'])

@section('css')
<style type="text/css">
    /* ─── Header Gradiente ─── */
    .modulo-header-gradient {
        background: linear-gradient(135deg, #0f0c29 0%, #302b63 50%, #24243e 100%);
        border-radius: 12px 12px 0 0 !important;
        border-bottom: none !important;
    }
    .modulo-header-gradient .modulo-title {
        color: #fff;
        font-weight: 700;
        letter-spacing: -0.3px;
    }
    .modulo-header-gradient .modulo-title i {
        background: rgba(255,255,255,0.12);
        padding: 8px;
        border-radius: 10px;
        color: #a8b5ff;
    }
    .modulo-header-gradient .modulo-subtitle {
        color: rgba(255,255,255,0.6) !important;
        font-weight: 400;
    }
    .modulo-header-gradient .btn {
        border-radius: 8px;
        font-weight: 600;
        transition: all 0.2s ease;
    }
    .modulo-header-gradient .btn:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 14px rgba(0,0,0,0.25);
    }

    /* ─── Token Input Glass ─── */
    .modulo-glass-filter {
        background: rgba(255,255,255,0.7);
        backdrop-filter: blur(12px);
        -webkit-backdrop-filter: blur(12px);
        border: 1px solid rgba(255,255,255,0.8) !important;
        border-radius: 12px;
        box-shadow: 0 2px 20px rgba(0,0,0,0.04);
    }
    .modulo-glass-filter label {
        font-weight: 600;
        font-size: 11px;
        text-transform: uppercase;
        letter-spacing: 0.4px;
        color: #5a5a7a;
        margin-bottom: 2px;
    }
    .modulo-glass-filter .form-control {
        height: 38px;
    }

    /* ─── Tabela Premium ─── */
    .modulo-table-wrap {
        border-radius: 12px;
        border: 1px solid #eef0f5;
        overflow: hidden;
    }
    .modulo-table-wrap table {
        margin-bottom: 0;
    }
    .modulo-table-wrap thead th {
        background: #f8f9fc;
        color: #5a5a7a;
        font-weight: 700;
        font-size: 11px;
        text-transform: uppercase;
        letter-spacing: 0.4px;
        padding: 12px 14px;
        border-bottom: 2px solid #e8eaf6;
    }
    .modulo-table-wrap tbody td {
        padding: 12px 14px;
        vertical-align: middle;
        border-bottom: 1px solid #f0f2f8;
        transition: background 0.15s ease;
        font-size: 13px;
    }
    .modulo-table-wrap tbody tr {
        transition: all 0.15s ease;
    }
    .modulo-table-wrap tbody tr:hover {
        background: #f5f6fe;
    }
    .modulo-table-wrap tbody tr:last-child td {
        border-bottom: none;
    }
</style>
@endsection

@section('content')
<div class="mt-3 text-dark">
    <div class="row">
        <div class="card border-0 shadow-sm text-dark">

            {{-- ═══ CABEÇALHO PREMIUM ═══ --}}
            <div class="card-header modulo-header-gradient py-3 px-4">
                <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                    <div>
                        <h4 class="mb-1 modulo-title d-flex align-items-center gap-2">
                            <i class="ri-search-eye-line"></i>
                            Busca Automática de NCM
                        </h4>
                        <p class="text-muted mb-0 modulo-subtitle fs-13">
                            Consulte NCMs em lote ou individualmente utilizando a API Bluesoft Cosmos.
                        </p>
                    </div>
                    <div>
                        <a href="{{ route('migracao.index') }}" class="btn btn-light btn-sm px-3 text-dark">
                            <i class="ri-arrow-left-line align-middle me-1"></i> Voltar
                        </a>
                    </div>
                </div>
            </div>

            <div class="card-body p-4">

                {{-- ═══ ALERTA DA API TOKEN ═══ --}}
                <div class="alert alert-warning border-0 shadow-none d-flex gap-2 mb-4" role="alert">
                    <i class="ri-key-2-line fs-22 text-warning mt-1"></i>
                    <div>
                        <h5 class="alert-heading fw-semibold mb-1">Token de Acesso Requerido</h5>
                        <p class="mb-2 fs-13">Para buscar os NCMs automaticamente, você precisa de um <strong>Token Gratuito</strong> do Bluesoft Cosmos.</p>
                        <ol class="mb-0 fs-13 ps-3">
                            <li>Cadastre-se em <a class="fw-bold" href="https://cosmos.bluesoft.com.br/api" target="_blank">cosmos.bluesoft.com.br/api</a>.</li>
                            <li>Copie seu token gerado e cole no campo de configuração abaixo.</li>
                        </ol>
                    </div>
                </div>

                {{-- ═══ CONFIGURAÇÃO TOKEN ═══ --}}
                <div class="modulo-glass-filter p-3 mb-4">
                    <div class="row align-items-end g-3">
                        <div class="col-md-6 col-12">
                            <label class="form-label">Seu Token Bluesoft Cosmos</label>
                            <input type="text" id="api_token" class="form-control" placeholder="Cole seu token aqui (ex: xxxxxxxxxxxxx)" value="">
                        </div>
                        <div class="col-md-6 col-12 text-md-end">
                            <button id="btn-buscar-todos" class="btn btn-secondary px-3" disabled title="Em breve">
                                <i class="ri-search-eye-line align-middle me-1"></i> Buscar Todos (Em breve)
                            </button>
                        </div>
                    </div>
                </div>

                {{-- ═══ TABELA DE PRODUTOS PENDENTES ═══ --}}
                <div class="modulo-table-wrap mb-3">
                    <div class="table-responsive">
                        <table class="table table-centered align-middle mb-0 text-dark">
                            <thead>
                                <tr>
                                    <th>Produto</th>
                                    <th>Código de Barras (EAN)</th>
                                    <th>NCM Atual</th>
                                    <th>Ação</th>
                                    <th>Status / Retorno da API</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($data as $item)
                                <tr id="row-{{ $item->id }}">
                                    <td class="fw-semibold text-dark">{{ $item->nome }}</td>
                                    <td>
                                        <span class="badge bg-secondary-subtle text-secondary px-2 py-1 fs-11">
                                            {{ $item->codigo_barras }}
                                        </span>
                                    </td>
                                    <td class="ncm-val text-muted">{{ $item->ncm ?: '--' }}</td>
                                    <td>
                                        <button class="btn btn-sm btn-primary btn-buscar px-3" data-id="{{ $item->id }}">
                                            <i class="ri-search-line align-middle me-1"></i> Buscar
                                        </button>
                                    </td>
                                    <td class="status-msg fs-12"></td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="text-center py-4 text-muted">
                                        <i class="ri-checkbox-circle-line text-success fs-20 align-middle me-1"></i>
                                        Nenhum produto com NCM pendente encontrado! 🎉
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="d-flex justify-content-center">
                    {!! $data->appends(request()->all())->links() !!}
                </div>

            </div>
        </div>
    </div>
</div>
@endsection

@section('js')
<script type="text/javascript">
    $('.btn-buscar').click(function() {
        var btn = $(this);
        var id = btn.data('id');
        var token = $('#api_token').val();

        if(!token){
            alert('Por favor, informe o Token do Cosmos primeiro.');
            $('#api_token').focus();
            return;
        }

        btn.prop('disabled', true).html('<i class="ri-loader-4-line ri-spin"></i>');
        
        $.ajax({
            url: '{{ url("/migracao/ncm-finder") }}/' + id,
            method: 'GET',
            data: { token: token },
            success: function(res) {
                $('#row-' + id + ' .ncm-val').text(res.ncm).addClass('text-success fw-bold');
                $('#row-' + id + ' .status-msg').html('✅ ' + res.descricao + '<br><small class="text-muted">' + res.message + '</small>').addClass('text-success');
                btn.html('<i class="ri-check-line"></i>').removeClass('btn-primary').addClass('btn-success');
            },
            error: function(err) {
                console.log(err);
                if (err.status === 429) {
                     var msg = "Muitas requisições! Aguarde 1min.";
                } else {
                     var msg = err.responseJSON ? err.responseJSON.error : 'Erro desconhecido';
                }
                
                $('#row-' + id + ' .status-msg').text('❌ ' + msg).addClass('text-danger');
                btn.prop('disabled', false).html('<i class="ri-search-line"></i> Tentar');
            }
        });
    });
</script>
@endsection
