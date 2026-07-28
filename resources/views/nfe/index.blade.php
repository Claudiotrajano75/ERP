@extends('layouts.app', ['title' => 'Vendas / NFe'])

@section('css')
<style>
/* ─── Header Gradiente ─── */
.modulo-header-gradient { background: linear-gradient(135deg, #0f0c29 0%, #302b63 50%, #24243e 100%); border-radius: 12px 12px 0 0 !important; border-bottom: none !important; }
.modulo-header-gradient .modulo-title { color: #fff; font-weight: 700; letter-spacing: -0.3px; }
.modulo-header-gradient .modulo-title i { background: rgba(255,255,255,0.12); padding: 8px; border-radius: 10px; color: #a8b5ff; }
.modulo-header-gradient .modulo-subtitle { color: rgba(255,255,255,0.6) !important; font-weight: 400; }
.modulo-header-gradient .btn { border-radius: 8px; font-weight: 600; transition: all 0.2s ease; }
.modulo-header-gradient .btn:hover { transform: translateY(-1px); box-shadow: 0 4px 14px rgba(0,0,0,0.25); }

/* ─── Glass Filters ─── */
.modulo-glass-filter { background: rgba(255,255,255,0.7); backdrop-filter: blur(12px); -webkit-backdrop-filter: blur(12px); border: 1px solid rgba(255,255,255,0.8) !important; border-radius: 12px; box-shadow: 0 2px 20px rgba(0,0,0,0.04); }
.modulo-glass-filter label { font-weight: 600; font-size: 11px; text-transform: uppercase; letter-spacing: 0.4px; color: #5a5a7a; margin-bottom: 2px; }
.modulo-glass-filter .form-control, .modulo-glass-filter .form-select { height: 38px; } .modulo-glass-filter .btn { border-radius: 8px; font-weight: 600; font-size: 13px; height: 38px; padding-top: 0; padding-bottom: 0; display: inline-flex; align-items: center; justify-content: center; transition: all 0.2s; }
.modulo-glass-filter .btn:hover { transform: translateY(-1px); }

/* ─── Premium Table ─── */
.modulo-table-wrap { border-radius: 12px; border: 1px solid #eef0f5; overflow: hidden; }
.modulo-table-wrap table { margin-bottom: 0; }
.modulo-table-wrap thead th { background: #f8f9fc; color: #5a5a7a; font-weight: 700; font-size: 11px; text-transform: uppercase; letter-spacing: 0.4px; padding: 12px 14px; border-bottom: 2px solid #e8eaf6; }
.modulo-table-wrap tbody td { padding: 12px 14px; vertical-align: middle; border-bottom: 1px solid #f0f2f8; transition: background 0.15s ease; font-size: 13px; }
.modulo-table-wrap tbody tr { transition: all 0.15s ease; }
.modulo-table-wrap tbody tr:hover { background: #f5f6fe; }
.modulo-table-wrap tbody tr:last-child td { border-bottom: none; }

/* ─── Empty State ─── */
.modulo-empty { padding: 48px 20px; text-align: center; }
.modulo-empty i { font-size: 48px; color: #c5cae9; margin-bottom: 12px; display: block; }
.modulo-empty p { color: #9e9eb8; font-size: 14px; margin: 0; }

/* ─── Botões de Ação do Formulário / Grid ─── */
.modulo-action-group { display: flex; align-items: center; justify-content: flex-end; gap: 4px; flex-wrap: wrap !important; }
.modulo-action-group .btn { padding: 5px 8px; font-size: 12px; border-radius: 6px; }

/* ─── Modal Premium ─── */
.modal-content { border: none; border-radius: 14px; overflow: hidden; box-shadow: 0 10px 30px rgba(0,0,0,0.15); }
.modal-header { background: linear-gradient(135deg, #0f0c29 0%, #302b63 50%, #24243e 100%); border: none; padding: 16px 20px; }
.modal-header .modal-title { color: #fff; font-weight: 700; font-size: 15px; letter-spacing: -0.2px; }
.modal-header .modal-title i { color: #a8b5ff; }
.modal-header .btn-close { filter: invert(1) grayscale(1) brightness(2); opacity: 0.8; }
.modal-body { padding: 24px 20px; background: #fafbfe; }
.modal-body label { font-weight: 600; font-size: 12px; color: #5a5a7a; margin-bottom: 6px; }
.modal-body .form-control { border-radius: 8px; border: 1px solid #e0e3eb; font-size: 13px; padding: 10px 14px; background: #fff; transition: all 0.15s ease; }
.modal-body .form-control:focus { border-color: #302b63; box-shadow: 0 0 0 3px rgba(48,43,99,0.08); }
.modal-footer { background: #fff; border-top: 1px solid #f0f2f8; padding: 14px 20px; }
.modal-footer .btn { border-radius: 8px; font-weight: 600; font-size: 13px; padding: 8px 18px; transition: all 0.2s ease; }
.modal-footer .btn-light { background: #f0f2f8; border-color: #f0f2f8; color: #5a5a7a; }
.modal-footer .btn-light:hover { background: #e4e7f0; border-color: #e4e7f0; color: #43435c; }
</style>
@endsection

@section('content')
<div class="mt-3 text-dark">
    <div class="row">
        <div class="card border-0 shadow-sm text-dark modulo-form-card">

            <!-- CABEÇALHO PREMIUM -->
            <div class="card-header modulo-header-gradient py-3 px-4">
                <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                    <div>
                        <h4 class="mb-1 modulo-title d-flex align-items-center gap-2">
                            <i class="ri-receipt-line"></i>
                            Painel de Vendas (NFe)
                        </h4>
                        <p class="text-muted mb-0 modulo-subtitle fs-13">Gerencie notas fiscais de saída: emissão, transmissão ao SEFAZ, cancelamento e impressão do DANFE.</p>
                    </div>
                    <div class="d-inline-flex gap-1">
                        @can('nfe_create')
                        <a href="{{ route('nfe.create') }}" class="btn btn-success btn-sm px-3">
                            <i class="ri-add-circle-line align-middle me-1"></i> Nova Venda
                        </a>
                        @endcan
                        @if(__isPlanoFiscal())
                        <button id="btn-consulta-sefaz" class="btn btn-light btn-sm px-3 text-dark">
                            <i class="ri-refresh-line align-middle me-1"></i> Status SEFAZ
                        </button>
                        @endif
                    </div>
                </div>
            </div>

            <div class="card-body p-4">

                @if($contigencia != null)
                <div class="alert alert-danger border-danger-subtle bg-danger-subtle text-danger p-3 mb-4 d-flex align-items-start">
                    <i class="ri-error-warning-line me-2 fs-20 mt-0.5"></i>
                    <div>
                        <strong>Contingência Ativada!</strong>
                        Tipo: <strong>{{ $contigencia->tipo }}</strong> &mdash; Início: <strong>{{ __data_pt($contigencia->created_at) }}</strong>
                    </div>
                </div>
                @endif

                <!-- FILTROS GLASS -->
                <div class="modulo-glass-filter p-3 mb-4">
                    {!!Form::open()->fill(request()->all())->get()!!}
                    <div class="row g-2 align-items-end">
                        <div class="col-md-3 col-12">
                            {!!Form::select('cliente_id', 'Cliente')->attrs(['class' => 'select2 form-select'])!!}
                        </div>
                        <div class="col-md-2 col-6">
                            {!!Form::date('start_date', 'Data Inicial')!!}
                        </div>
                        <div class="col-md-2 col-6">
                            {!!Form::date('end_date', 'Data Final')!!}
                        </div>
                        @if(__isPlanoFiscal())
                        <div class="col-md-2 col-6">
                            {!!Form::select('estado', 'Estado NFe', [
                                'novo' => 'Novas',
                                'rejeitado' => 'Rejeitadas',
                                'cancelado' => 'Canceladas',
                                'aprovado' => 'Aprovadas',
                                '' => 'Todos'
                            ])->attrs(['class' => 'form-select'])!!}
                        </div>
                        <div class="col-md-1 col-6">
                            {!!Form::select('tpNF', 'Tipo', [
                                '1' => 'Saída',
                                '0' => 'Entrada',
                                '-' => 'Todos'
                            ])->attrs(['class' => 'form-select'])!!}
                        </div>
                        @endif
                        @if(__countLocalAtivo() > 1)
                        <div class="col-md-2 col-6">
                            {!!Form::select('local_id', 'Local', ['' => 'Selecione'] + __getLocaisAtivoUsuario()->pluck('descricao', 'id')->all())
                            ->attrs(['class' => 'select2 form-select'])!!}
                        </div>
                        @endif
                        <div class="col-md-2 col-12 ms-auto">
                            <div class="d-flex gap-2">
                                <button class="btn btn-primary btn-sm flex-grow-1" type="submit">
                                    <i class="ri-search-line me-1"></i> Pesquisar
                                </button>
                                <a class="btn btn-danger btn-sm px-3" href="{{ route('nfe.index') }}">
                                    <i class="ri-eraser-line me-1"></i> Limpar
                                </a>
                            </div>
                        </div>
                    </div>
                    {!!Form::close()!!}
                </div>

                <!-- TABELA PREMIUM -->
                <div class="modulo-table-wrap">
                    <div class="table-responsive">
                        <table class="table table-centered table-hover align-middle mb-0 text-dark">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Cliente / Fornecedor</th>
                                    @if(__countLocalAtivo() > 1)
                                    <th>Local</th>
                                    @endif
                                    <th>Usuário</th>
                                    <th>Nº Nota</th>
                                    <th>Valor (R$)</th>
                                    @if(__isPlanoFiscal())
                                    <th>Estado</th>
                                    <th>Ambiente</th>
                                    @endif
                                    <th>Cadastro</th>
                                    <th>Emissão</th>
                                    <th>Origem</th>
                                    <th>Tipo</th>
                                    <th>Ref.</th>
                                    <th class="text-end" style="min-width: 220px;">Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($data as $item)
                                <tr>
                                    <td class="fw-bold text-muted">{{ $item->numero_sequencial }}</td>
                                    @if($item->cliente)
                                    <td>
                                        <span class="fw-semibold text-dark d-block">{{ $item->cliente->razao_social }}</span>
                                        <span class="text-muted fs-11">{{ $item->cliente->cpf_cnpj }}</span>
                                    </td>
                                    @else
                                    <td>
                                        <span class="fw-semibold text-dark d-block">{{ $item->fornecedor ? $item->fornecedor->razao_social : '--' }}</span>
                                        <span class="text-muted fs-11">{{ $item->fornecedor ? $item->fornecedor->cpf_cnpj : '--' }}</span>
                                    </td>
                                    @endif
                                    @if(__countLocalAtivo() > 1)
                                    <td class="text-danger fw-bold fs-12">{{ $item->localizacao->descricao ?? '' }}</td>
                                    @endif
                                    <td class="fs-12">{{ $item->user ? $item->user->name : '--' }}</td>
                                    <td class="fw-bold">{{ $item->numero ?: '--' }}</td>
                                    <td class="fw-bold text-success">R$ {{ __moeda($item->total) }}</td>
                                    @if(__isPlanoFiscal())
                                    <td>
                                        @if($item->estado == 'aprovado')
                                        <span class="badge bg-success-subtle text-success border border-success-subtle px-2 py-1 fs-11">Aprovado</span>
                                        @elseif($item->estado == 'cancelado')
                                        <span class="badge bg-danger-subtle text-danger border border-danger-subtle px-2 py-1 fs-11">Cancelado</span>
                                        @elseif($item->estado == 'rejeitado')
                                        <span class="badge bg-warning-subtle text-warning border border-warning-subtle px-2 py-1 fs-11">Rejeitado</span>
                                        @else
                                        <span class="badge bg-info-subtle text-info border border-info-subtle px-2 py-1 fs-11">Novo</span>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge bg-light text-dark border fs-11">
                                            {{ $item->ambiente == 2 ? 'Homolog.' : 'Produção' }}
                                        </span>
                                    </td>
                                    @endif
                                    <td class="fs-12">{{ __data_pt($item->created_at) }}</td>
                                    <td class="fs-12">{{ $item->data_emissao ? __data_pt($item->data_emissao, 1) : '--' }}</td>
                                    <td>
                                        @if($item->api)
                                        <span class="badge bg-success-subtle text-success border border-success-subtle fs-11">API</span>
                                        @else
                                        <span class="badge bg-primary-subtle text-primary border border-primary-subtle fs-11">Painel</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($item->tpNF)
                                        <span class="fw-bold text-success fs-12">Saída</span>
                                        @else
                                        <span class="fw-bold text-primary fs-12">Entrada</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($item->pedidoEcommerce)
                                        <a title="Pedido E-commerce" class="badge bg-danger text-white text-decoration-none" href="{{ route('pedidos-ecommerce.show', [$item->pedidoEcommerce->id]) }}">EC</a>
                                        @elseif($item->ordemServico)
                                        <a title="Ordem de Serviço" class="badge bg-primary text-white text-decoration-none" href="{{ route('ordem-servico.show', [$item->ordemServico->id]) }}">OS</a>
                                        @elseif($item->pedidoMercadoLivre)
                                        <a title="Pedido Mercado Livre" class="badge bg-warning text-dark text-decoration-none" href="{{ route('mercado-livre-pedidos.show', [$item->pedidoMercadoLivre->id]) }}">ML</a>
                                        @elseif($item->pedidoNuvemShop)
                                        <a title="Pedido Nuvem Shop" class="badge bg-dark text-white text-decoration-none" href="{{ route('nuvem-shop-pedidos.show', [$item->pedidoNuvemShop->pedido_id]) }}">NS</a>
                                        @elseif($item->reserva)
                                        <a title="Reserva" class="badge bg-secondary text-white text-decoration-none" href="{{ route('reservas.show', [$item->reserva->id]) }}">RS</a>
                                        @elseif($item->pedidoWoocomerce)
                                        <a title="Pedido WooCommerce" class="badge bg-info text-dark text-decoration-none" href="{{ route('woocommerce-pedidos.show', [$item->pedidoWoocomerce->id]) }}">WO</a>
                                        @else
                                        <span class="text-muted">--</span>
                                        @endif
                                    </td>
                                    <td class="text-end">
                                        <form action="{{ route('nfe.destroy', $item->id) }}" method="post" id="form-{{$item->id}}" class="m-0">
                                            @method('delete')
                                            @csrf
                                            <div class="modulo-action-group">

                                                @if($item->estado == 'cancelado')
                                                <a class="btn btn-danger btn-sm text-white" target="_blank" href="{{ route('nfe.imprimir-cancela', [$item->id]) }}" title="Imprimir Cancelamento">
                                                    <i class="ri-printer-line"></i>
                                                </a>
                                                @endif

                                                @if($item->estado == 'aprovado')
                                                <button type="button" onclick="imprimir('{{$item->id}}', '{{$item->numero}}')" class="btn btn-primary btn-sm text-white" title="Imprimir NFe">
                                                    <i class="ri-printer-line"></i>
                                                </button>
                                                @can('nfe_transmitir')
                                                <button title="Cancelar NFe" type="button" class="btn btn-danger btn-sm text-white" onclick="cancelar('{{$item->id}}', '{{$item->numero}}')">
                                                    <i class="ri-close-circle-line"></i>
                                                </button>
                                                <button title="Carta de Correção" type="button" class="btn btn-warning btn-sm text-white" onclick="corrigir('{{$item->id}}', '{{$item->numero}}')">
                                                    <i class="ri-file-warning-line"></i>
                                                </button>
                                                @endcan
                                                @endif

                                                @if($item->estado == 'aprovado' || $item->estado == 'cancelado' || $item->estado == 'rejeitado')
                                                <button title="Detalhes do Retorno SEFAZ" type="button" class="btn btn-dark btn-sm text-white" onclick="info('{{$item->motivo_rejeicao}}', '{{$item->chave}}', '{{$item->estado}}', '{{$item->recibo}}')">
                                                    <i class="ri-file-line"></i>
                                                </button>
                                                @endif

                                                @if($item->estado == 'novo' || $item->estado == 'rejeitado')
                                                @can('nfe_edit')
                                                <a class="btn btn-warning btn-sm text-white" href="{{ route('nfe.edit', $item->id) }}" title="Editar">
                                                    <i class="ri-pencil-line"></i>
                                                </a>
                                                @endcan
                                                @if(__isPlanoFiscal())
                                                <a target="_blank" title="XML Temporário" class="btn btn-light btn-sm text-dark" href="{{ route('nfe.xml-temp', $item->id) }}">
                                                    <i class="ri-file-code-line"></i>
                                                </a>
                                                @endif
                                                @can('nfe_delete')
                                                <button type="button" class="btn btn-danger btn-sm btn-delete" title="Excluir">
                                                    <i class="ri-delete-bin-line"></i>
                                                </button>
                                                @endcan
                                                @if(__isPlanoFiscal())
                                                @can('nfe_transmitir')
                                                <button title="Transmitir ao SEFAZ" type="button" class="btn btn-success btn-sm text-white" onclick="transmitir('{{$item->id}}')">
                                                    <i class="ri-send-plane-fill"></i>
                                                </button>
                                                @endcan
                                                @endif
                                                @endif

                                                <a class="btn btn-info btn-sm text-white" title="Imprimir Pedido" target="_blank" href="{{ route('nfe.imprimirVenda', [$item->id]) }}">
                                                    <i class="ri-file-text-line"></i>
                                                </a>

                                                @if($item->estado == 'aprovado' || $item->estado == 'cancelado' || $item->estado == 'rejeitado')
                                                <button title="Consultar Protocolo SEFAZ" type="button" class="btn btn-light btn-sm text-dark" onclick="consultar('{{$item->id}}', '{{$item->numero}}')">
                                                    <i class="ri-file-search-line"></i>
                                                </button>
                                                @endif

                                                @if(__isPlanoFiscal())
                                                @can('nfe_edit')
                                                <a title="Alterar Estado Fiscal" class="btn btn-secondary btn-sm text-white" href="{{ route('nfe.alterar-estado', $item->id) }}">
                                                    <i class="ri-arrow-up-down-line"></i>
                                                </a>
                                                @endcan
                                                @endif

                                                <a class="btn btn-light btn-sm text-dark" title="Detalhes da Venda" href="{{ route('nfe.show', $item->id) }}">
                                                    <i class="ri-eye-line"></i>
                                                </a>

                                                @if($item->estado != 'aprovado')
                                                <a class="btn btn-danger btn-sm text-white" title="DANFE Temporária" target="_blank" href="{{ route('nfe.danfe-temporaria', [$item->id]) }}">
                                                    <i class="ri-printer-fill"></i>
                                                </a>
                                                @endif

                                                <a class="btn btn-primary btn-sm text-white" href="{{ route('nfe.duplicar', [$item->id]) }}" title="Duplicar Venda">
                                                    <i class="ri-file-copy-line"></i>
                                                </a>

                                                @if($item->estado == 'aprovado')
                                                <button title="Enviar por E-mail" type="button" class="btn btn-light btn-sm text-dark" onclick="enviarEmail('{{$item->id}}', '{{$item->numero}}')">
                                                    <i class="ri-mail-send-line"></i>
                                                </button>
                                                <a title="Download XML" href="{{ route('nfe.download-xml', [$item->id]) }}" class="btn btn-dark btn-sm text-white">
                                                    <i class="ri-download-line"></i>
                                                </a>
                                                @endif

                                            </div>
                                        </form>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="14">
                                        <div class="modulo-empty">
                                            <i class="ri-inbox-2-line"></i>
                                            <p>Nenhuma venda encontrada no período.</p>
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Paginação & Soma -->
                <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mt-4">
                    <div>
                        <h5 class="m-0 text-dark">Total das Vendas no Grid: <strong class="text-success fs-16">R$ {{ __moeda($data->sum('total')) }}</strong></h5>
                    </div>
                    <div>
                        {!! $data->appends(request()->all())->links() !!}
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

<!-- Modal Imprimir NFe -->
<div class="modal fade" id="modal-print" tabindex="-1" aria-labelledby="modalPrintLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content text-dark">
            <div class="modal-header">
                <h5 class="modal-title d-flex align-items-center gap-2" id="modalPrintLabel">
                    <i class="ri-printer-line"></i> Imprimir NFe <strong class="ref-numero text-white"></strong>
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row g-2">
                    <div class="col-12 col-lg-4">
                        <button type="button" class="btn btn-success w-100 btn-sm py-2" onclick="gerarDanfe('danfe')">
                            <i class="ri-printer-line me-1"></i> DANFE Padrão
                        </button>
                    </div>
                    <div class="col-12 col-lg-4">
                        <button type="button" class="btn btn-primary w-100 btn-sm py-2" onclick="gerarDanfe('simples')">
                            <i class="ri-printer-line me-1"></i> DANFE Simples
                        </button>
                    </div>
                    <div class="col-12 col-lg-4">
                        <button type="button" class="btn btn-dark w-100 btn-sm py-2" onclick="gerarDanfe('etiqueta')">
                            <i class="ri-printer-line me-1"></i> DANFE Etiqueta
                        </button>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light btn-sm" data-bs-dismiss="modal">Fechar</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Cancelar NFe -->
<div class="modal fade" id="modal-cancelar" tabindex="-1" aria-labelledby="modalCancelarLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content text-dark">
            <div class="modal-header">
                <h5 class="modal-title d-flex align-items-center gap-2" id="modalCancelarLabel">
                    <i class="ri-close-circle-line"></i> Cancelar NFe <strong class="ref-numero text-white"></strong>
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row g-2">
                    <div class="col-12">
                        {!!Form::text('motivo-cancela', 'Motivo da Justificativa (mín. 15 caracteres)')->required()!!}
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light btn-sm" data-bs-dismiss="modal">Fechar</button>
                <button type="button" id="btn-cancelar" class="btn btn-danger btn-sm">Confirmar Cancelamento</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal E-mail NFe -->
<div class="modal fade" id="modal-email" tabindex="-1" aria-labelledby="modalEmailLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content text-dark">
            <div class="modal-header">
                <h5 class="modal-title d-flex align-items-center gap-2" id="modalEmailLabel">
                    <i class="ri-mail-send-line"></i> Enviar NFe por E-mail <strong class="ref-numero text-white"></strong>
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row g-2">
                    <div class="col-md-12">
                        {!!Form::text('email', 'Endereço de E-mail')->required()->type('email')!!}
                    </div>
                    <div class="col-md-6 mt-3">
                        {!!Form::checkbox('danfe', 'Incluir DANFE')!!}
                    </div>
                    <div class="col-md-6 mt-3">
                        {!!Form::checkbox('xml', 'Incluir XML')!!}
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light btn-sm" data-bs-dismiss="modal">Fechar</button>
                <button type="button" id="btn-enviar-email" class="btn btn-success btn-sm">Enviar E-mail</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Corrigir NFe (CC-e) -->
<div class="modal fade" id="modal-corrigir" tabindex="-1" aria-labelledby="modalCorrigirLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content text-dark">
            <div class="modal-header">
                <h5 class="modal-title d-flex align-items-center gap-2" id="modalCorrigirLabel">
                    <i class="ri-file-warning-line"></i> Carta de Correção (CC-e) <strong class="ref-numero text-white"></strong>
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row g-2">
                    <div class="col-12">
                        {!!Form::text('motivo-corrigir', 'Texto de Correção (mín. 15 caracteres)')->required()!!}
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light btn-sm" data-bs-dismiss="modal">Fechar</button>
                <button type="button" id="btn-corrigir" class="btn btn-warning btn-sm">Transmitir CC-e</button>
            </div>
        </div>
    </div>
</div>

@endsection

@section('js')
<script type="text/javascript">
    function info(motivo_rejeicao, chave, estado, recibo) {
        if (estado == 'rejeitado') {
            let text = "Motivo: " + motivo_rejeicao + "\n\n"
            text += "Chave: " + chave + "\n"
            swal("Nota Rejeitada", text, "warning")
        } else {
            let text = "Chave: " + chave + "\n"
            text += "Recibo: " + recibo + "\n"
            swal("Nota Autorizada", text, "success")
        }
    }

    $('#btn-consulta-sefaz').click(() => {
        $.post(path_url + 'api/nfe_painel/consulta-status-sefaz', {
            empresa_id: $('#empresa_id').val(),
            usuario_id: $('#usuario_id').val(),
        })
        .done((res) => {
            let msg = "cStat: " + res.cStat
            msg += "\nMotivo: " + res.xMotivo
            msg += "\nAmbiente: " + (res.tpAmb == 2 ? "Homologação" : "Produção")
            msg += "\nVerAplic: " + res.verAplic
            swal("Status SEFAZ", msg, "success")
        })
        .fail((err) => {
            try { swal("Erro", err.responseText, "error") }
            catch { swal("Erro", "Algo deu errado", "error") }
        })
    })
</script>
<script type="text/javascript" src="/js/nfe_transmitir.js"></script>
@endsection
