@extends('layouts.app', ['title' => 'Compras'])

@section('css')
    <style>
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
            background: rgba(255, 255, 255, 0.12);
            padding: 8px;
            border-radius: 10px;
            color: #a8b5ff;
        }

        .modulo-header-gradient .modulo-subtitle {
            color: rgba(255, 255, 255, 0.6) !important;
            font-weight: 400;
        }

        .modulo-header-gradient .btn {
            border-radius: 8px;
            font-weight: 600;
            transition: all 0.2s ease;
        }

        .modulo-header-gradient .btn:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 14px rgba(0, 0, 0, 0.25);
        }

        /* --- Novo Filtro de Pesquisa Premium --- */
        .modulo-glass-filter-premium {
            background: #ffffff;
            border: 1px solid #eef0f6 !important;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.03);
            padding: 20px !important;
            margin-bottom: 24px;
        }

        /* Título e Header do Filtro */
        .filtro-premium-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            border-bottom: 1px solid #f1f3f9;
            padding-bottom: 12px;
            margin-bottom: 16px;
        }

        .filtro-premium-title {
            font-size: 13px;
            font-weight: 700;
            color: #3f3e6a;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 0;
        }

        .filtro-premium-title i {
            color: #5572f5;
            margin-right: 6px;
        }

        /* Customização dos Inputs dentro do Filtro */
        .modulo-glass-filter-premium label {
            font-size: 10px !important;
            font-weight: 700 !important;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #8c8ca6 !important;
            margin-bottom: 6px !important;
            display: flex;
            align-items: center;
            gap: 4px;
        }

        .modulo-glass-filter-premium label i {
            font-size: 12px;
            color: #a8a8c0;
        }

        .modulo-glass-filter-premium .form-control,
        .modulo-glass-filter-premium .form-select {
            height: 38px !important;
            border-radius: 8px !important;
            border: 1px solid #dcdce9 !important;
            font-size: 13px !important;
            padding: 6px 12px !important;
            color: #374151 !important;
            background-color: #fcfdfe !important;
            transition: all 0.2s ease;
        }

        .modulo-glass-filter-premium .form-control:focus,
        .modulo-glass-filter-premium .form-select:focus {
            border-color: #5572f5 !important;
            background-color: #fff !important;
            box-shadow: 0 0 0 3px rgba(85, 114, 245, 0.12) !important;
        }

        /* Botões do Filtro */
        .modulo-glass-filter-premium .btn-pesquisar {
            background: linear-gradient(135deg, #5572f5 0%, #3d56d4 100%) !important;
            border: none !important;
            color: #fff !important;
            font-weight: 600 !important;
            height: 38px;
            border-radius: 8px !important;
            font-size: 13px !important;
            transition: all 0.2s ease !important;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
        }

        .modulo-glass-filter-premium .btn-pesquisar:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(85, 114, 245, 0.25) !important;
        }

        .modulo-glass-filter-premium .btn-limpar {
            background: #f1f3f9 !important;
            border: 1px solid #e2e5ec !important;
            color: #5a5a7a !important;
            font-weight: 600 !important;
            height: 38px;
            border-radius: 8px !important;
            font-size: 13px !important;
            transition: all 0.2s ease !important;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
        }

        .modulo-glass-filter-premium .btn-limpar:hover {
            background: #e8ebf3 !important;
            color: #302b63 !important;
        }

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

        .modulo-footer {
            padding: 16px 0 0;
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 12px;
        }

        .modulo-footer .modulo-total-label {
            font-size: 13px;
            color: #5a5a7a;
            font-weight: 600;
        }

        .modulo-footer .modulo-total-value {
            font-size: 18px;
            font-weight: 800;
            color: #2e7d32;
            letter-spacing: -0.3px;
        }

        .modulo-action-group {
            display: inline-flex;
            gap: 4px;
            flex-wrap: wrap;
        }

        .modulo-action-group .btn {
            border-radius: 8px;
            padding: 4px 10px;
            font-size: 13px;
            transition: all 0.15s ease;
        }

        .modulo-action-group .btn:hover {
            transform: translateY(-1px);
        }

        .modulo-badge {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 700;
            letter-spacing: 0.2px;
        }

        .modulo-badge-success {
            background: linear-gradient(135deg, #e8f5e9, #c8e6c9);
            color: #2e7d32;
        }

        .modulo-badge-warning {
            background: linear-gradient(135deg, #fff3e0, #ffe0b2);
            color: #e65100;
        }

        .modulo-badge-danger {
            background: linear-gradient(135deg, #fbe9e7, #ffccbc);
            color: #c62828;
        }

        .modulo-badge-info {
            background: linear-gradient(135deg, #e3f2fd, #bbdefb);
            color: #1565c0;
        }

        @media (max-width: 768px) {
            .modulo-header-gradient .modulo-title {
                font-size: 18px;
            }
        }
    </style>
@endsection
@section('content')
    <div class="mt-3 text-dark">
        <div class="row">
            <div class="card border-0 shadow-sm text-dark">

                <!-- ═══ Cabeçalho Premium ═══ -->
                <div class="card-header modulo-header-gradient py-3 px-4">
                    <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                        <div>
                            <h4 class="mb-1 modulo-title d-flex align-items-center gap-2">
                                <i class="ri-logout-box-line"></i>
                                Entradas de Mercadorias (Compras)
                            </h4>
                            <p class="text-muted mb-0 modulo-subtitle fs-13">Gerencie as compras efetuadas, dê entrada nos
                                estoques importando arquivos XML de fornecedores ou manualmente.</p>
                        </div>
                        <div class="d-flex gap-2">
                            @can('compras_create')
                                <a href="{{ route('compras.create') }}" class="btn btn-light btn-sm px-3 text-dark">
                                    <i class="ri-add-circle-line align-middle me-1"></i> Nova Compra
                                </a>
                            @endcan
                            <a href="{{ route('compras.xml') }}" class="btn btn-light btn-sm px-3 text-dark">
                                <i class="ri-file-upload-line align-middle me-1"></i> Importar XML
                            </a>
                        </div>
                    </div>
                </div>

                <div class="card-body p-4">

                    <!-- ═══ Filtros de Busca Premium ═══ -->
                    <div class="modulo-glass-filter-premium">
                        <div class="filtro-premium-header">
                            <h5 class="filtro-premium-title">
                                <i class="ri-search-line"></i> Filtrar Compras
                            </h5>
                        </div>

                        {!!Form::open()->fill(request()->all())->get()!!}
                        <div class="row g-3">
                            <div class="col-md-4 col-12">
                                <label class="form-label"><i class="ri-truck-line"></i> Fornecedor</label>
                                {!!Form::select('fornecedor_id', '', ['' => 'Selecione'] + $fornecedores->pluck('razao_social', 'id')->all())
        ->attrs(['class' => 'select2 form-select'])!!}
                            </div>
                            <div class="col-md-2 col-6">
                                <label class="form-label"><i class="ri-calendar-line"></i> Data Inicial</label>
                                {!!Form::date('start_date', '')->attrs(['class' => 'form-control'])!!}
                            </div>
                            <div class="col-md-2 col-6">
                                <label class="form-label"><i class="ri-calendar-line"></i> Data Final</label>
                                {!!Form::date('end_date', '')->attrs(['class' => 'form-control'])!!}
                            </div>
                            <div class="col-md-2 col-6">
                                <label class="form-label"><i class="ri-equalizer-line"></i> Status</label>
                                {!!Form::select('estado', '', [
        'novo' => 'Novas',
        'rejeitado' => 'Rejeitadas',
        'cancelado' => 'Canceladas',
        'aprovado' => 'Aprovadas',
        '' => 'Todos'
    ])->attrs(['class' => 'form-select'])!!}
                            </div>
                            @if(__countLocalAtivo() > 1)
                                                <div class="col-md-3 col-6">
                                                    <label class="form-label"><i class="ri-store-2-line"></i> Local</label>
                                                    {!!Form::select('local_id', '', ['' => 'Selecione'] + __getLocaisAtivoUsuario()->pluck('descricao', 'id')->all())
                                ->attrs(['class' => 'select2 form-select'])!!}
                                                </div>
                            @endif
                            <div class="col-md-2 col-12 ms-auto d-flex align-items-end">
                                <div class="d-flex gap-2 w-100">
                                    <button class="btn btn-pesquisar flex-grow-1" type="submit">
                                        <i class="ri-search-line"></i> Buscar
                                    </button>
                                    <a class="btn btn-limpar px-3" href="{{ route('compras.index') }}"
                                        title="Limpar Filtros">
                                        <i class="ri-eraser-line"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                        {!!Form::close()!!}
                    </div>

                    @if($contigencia != null)
                        <div
                            class="alert alert-danger border-danger-subtle bg-danger-subtle text-danger p-3 mb-4 d-flex align-items-center">
                            <i class="ri-error-warning-line me-2 fs-20"></i>
                            <div>
                                <strong class="d-block">Contingência Ativada!</strong>
                                <span>Tipo: {{ $contigencia->tipo }} | Início em:
                                    {{ __data_pt($contigencia->created_at) }}</span>
                            </div>
                        </div>
                    @endif

                    <!-- ═══ Tabela Premium ═══ -->
                    <div class="modulo-table-wrap">
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th># Reg.</th>
                                        <th>Fornecedor</th>
                                        @if(__countLocalAtivo() > 1)
                                            <th>Local / Filial</th>
                                        @endif
                                        <th>CPF / CNPJ</th>
                                        <th>Nº Doc</th>
                                        <th>Valor Total (R$)</th>
                                        <th>Estado</th>
                                        <th>Ambiente</th>
                                        <th>Data Emissão</th>
                                        <th>Emissão</th>
                                        <th>Tipo</th>
                                        <th class="text-end" style="width: 250px;">Ações</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($data as $item)
                                        <tr>
                                            <td>{{ $item->numero_sequencial }}</td>
                                            <td class="fw-semibold text-dark">
                                                {{ $item->fornecedor ? $item->fornecedor->razao_social : "--" }}
                                            </td>
                                            @if(__countLocalAtivo() > 1)
                                                <td class="text-danger fw-bold">{{ $item->localizacao->descricao }}</td>
                                            @endif
                                            <td class="fw-bold text-muted">
                                                {{ $item->fornecedor ? $item->fornecedor->cpf_cnpj : "--" }}
                                            </td>
                                            <td class="fw-bold">{{ $item->numero ? $item->numero : '' }}</td>
                                            <td class="fw-bold text-success">R$ {{ number_format($item->total, 2, ',', '.') }}
                                            </td>
                                            <td>
                                                @if($item->estado == 'aprovado')
                                                    <span
                                                        class="badge bg-success-subtle text-success border border-success-subtle px-2 py-1 fs-11">Aprovado</span>
                                                @elseif($item->estado == 'cancelado')
                                                    <span
                                                        class="badge bg-danger-subtle text-danger border border-danger-subtle px-2 py-1 fs-11">Cancelado</span>
                                                @elseif($item->estado == 'rejeitado')
                                                    <span
                                                        class="badge bg-warning-subtle text-warning border border-warning-subtle px-2 py-1 fs-11">Rejeitado</span>
                                                @else
                                                    <span
                                                        class="badge bg-info-subtle text-info border border-info-subtle px-2 py-1 fs-11">Novo</span>
                                                @endif
                                            </td>
                                            <td>
                                                <span class="badge bg-light text-dark border">
                                                    {{ $item->ambiente == 2 ? 'Homologação' : 'Produção' }}
                                                </span>
                                            </td>
                                            <td>{{ \Carbon\Carbon::parse($item->created_at)->format('d/m/Y H:i') }}</td>
                                            <td>
                                                @if($item->api)
                                                    <span
                                                        class="badge bg-success-subtle text-success border border-success-subtle">API</span>
                                                @else
                                                    <span
                                                        class="badge bg-primary-subtle text-primary border border-primary-subtle">Painel</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($item->tpNF)
                                                    <span class="text-success fw-bold">Saída</span>
                                                @else
                                                    <span class="text-primary fw-bold">Entrada</span>
                                                @endif
                                            </td>
                                            <td class="text-end">
                                                <form action="{{ route('nfe.destroy', $item->id) }}" method="post"
                                                    id="form-{{$item->id}}" class="m-0">
                                                    @method('delete')
                                                    @csrf
                                                    <div class="modulo-action-group">
                                                        @if($item->estado == 'cancelado')
                                                            <a class="btn btn-danger btn-sm text-white" target="_blank"
                                                                href="{{ route('nfe.imprimir-cancela', [$item->id]) }}"
                                                                title="Imprimir Cancelamento">
                                                                <i class="ri-printer-line"></i>
                                                            </a>
                                                        @endif
                                                        @if($item->estado == 'aprovado')
                                                            <a class="btn btn-primary btn-sm text-white" target="_blank"
                                                                href="{{ route('nfe.imprimir', [$item->id]) }}"
                                                                title="Imprimir Danfe">
                                                                <i class="ri-printer-line"></i>
                                                            </a>
                                                            @can('nfe_transmitir')
                                                                <button title="Cancelar NFe" type="button"
                                                                    class="btn btn-danger btn-sm text-white"
                                                                    onclick="cancelar('{{$item->id}}', '{{$item->numero}}')">
                                                                    <i class="ri-close-circle-line"></i>
                                                                </button>
                                                                <button title="Carta de Correção CC-e" type="button"
                                                                    class="btn btn-warning btn-sm text-white"
                                                                    onclick="corrigir('{{$item->id}}', '{{$item->numero}}')">
                                                                    <i class="ri-file-warning-line"></i>
                                                                </button>
                                                            @endcan
                                                        @endif

                                                        @if($item->estado == 'aprovado' || $item->estado == 'rejeitado')
                                                            <button type="button" class="btn btn-dark btn-sm text-white"
                                                                onclick="info('{{$item->motivo_rejeicao}}', '{{$item->chave}}', '{{$item->estado}}', '{{$item->recibo}}')"
                                                                title="Ver Detalhes do Retorno">
                                                                <i class="ri-file-line"></i>
                                                            </button>
                                                        @endif

                                                        @if($item->estado == 'novo' || $item->estado == 'rejeitado')
                                                            @if($item->chave_importada == '')
                                                                @can('compras_edit')
                                                                    <a class="btn btn-warning btn-sm text-white"
                                                                        href="{{ route('nfe.edit', $item->id) }}" title="Editar Compra">
                                                                        <i class="ri-edit-line"></i>
                                                                    </a>
                                                                @endcan
                                                            @endif
                                                            <a target="_blank" title="XML Temporário" class="btn btn-light btn-sm"
                                                                href="{{ route('nfe.xml-temp', $item->id) }}">
                                                                <i class="ri-file-line"></i>
                                                            </a>
                                                            @can('compras_delete')
                                                                <button type="button" class="btn btn-danger btn-sm btn-delete"
                                                                    title="Excluir Pré-compra"><i
                                                                        class="ri-delete-bin-line"></i></button>
                                                            @endcan
                                                            @can('nfe_transmitir')
                                                                <button title="Transmitir NFe ao SEFAZ" type="button"
                                                                    class="btn btn-success btn-sm text-white"
                                                                    onclick="transmitir('{{$item->id}}')">
                                                                    <i class="ri-send-plane-fill"></i>
                                                                </button>
                                                            @endcan
                                                        @endif

                                                        @if($item->estado == 'aprovado' || $item->estado == 'cancelado')
                                                            <button title="Consultar Protocolo SEFAZ" type="button"
                                                                class="btn btn-light btn-sm"
                                                                onclick="consultar('{{$item->id}}', '{{$item->numero}}')">
                                                                <i class="ri-file-search-line"></i>
                                                            </button>
                                                        @endif

                                                        @if($item->isItemValidade())
                                                            <a href="{{ route('compras.info-validade', $item->id) }}"
                                                                title="Preencher Vencimentos/Lotes" type="button"
                                                                class="btn btn-info btn-sm text-white"><i
                                                                    class="ri-pencil-line"></i></a>
                                                        @endif

                                                        <a class="btn btn-info btn-sm text-white"
                                                            title="Imprimir Pedido de Compra" target="_blank"
                                                            href="{{ route('nfe.imprimirVenda', [$item->id]) }}">
                                                            <i class="ri-printer-line"></i>
                                                        </a>

                                                        <a class="btn btn-light btn-sm" title="Gerar Etiquetas de Preço/Barras"
                                                            target="_blank" href="{{ route('compras.etiqueta', [$item->id]) }}">
                                                            <i class="ri-barcode-box-line"></i>
                                                        </a>
                                                    </div>
                                                </form>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="12">
                                                <div class="modulo-empty">
                                                    <i class="ri-inbox-2-line"></i>
                                                    <p>Nenhuma compra cadastrada no período.</p>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        <!-- Paginação & Rodapé de Valores -->
                        <!-- ═══ Footer ═══ -->
                        <div class="modulo-footer">
                            <div>
                                <span class="modulo-total-label">Soma das Compras:</span>
                                <span class="modulo-total-value">R$ {{ __moeda($data->sum('total')) }}</span>
                            </div>
                            <div>{!! $data->appends(request()->all())->links() !!}</div>
                        </div>

                    </div>
                </div>
            </div>
        </div>

        <!-- Modal Cancelar -->
        <div class="modal fade" id="modal-cancelar" tabindex="-1" aria-labelledby="modalCancelarLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content text-dark">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalCancelarLabel">Cancelar Nota Fiscal Eletrônica <strong
                                class="ref-numero text-danger"></strong></h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row g-2">
                            <div class="col-12">
                                {!!Form::text('motivo-cancela', 'Motivo da Justificativa (Mínimo 15 caracteres)')->required()->attrs(['class' => 'form-control'])!!}
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light btn-sm" data-bs-dismiss="modal">Fechar</button>
                        <button type="button" id="btn-cancelar" class="btn btn-danger btn-sm">Confirmar
                            Cancelamento</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal Corrigir -->
        <div class="modal fade" id="modal-corrigir" tabindex="-1" aria-labelledby="modalCorrigirLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content text-dark">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalCorrigirLabel">Emitir Carta de Correção (CC-e) <strong
                                class="ref-numero text-warning"></strong></h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row g-2">
                            <div class="col-12">
                                {!!Form::text('motivo-corrigir', 'Texto de Correção (Mínimo 15 caracteres)')->required()->attrs(['class' => 'form-control'])!!}
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light btn-sm" data-bs-dismiss="modal">Fechar</button>
                        <button type="button" id="btn-corrigir" class="btn btn-warning btn-sm">Transmitir Correção</button>
                    </div>
                </div>
            </div>
        </div>

@endsection

    @section('js')
        <script type="text/javascript">
            function info(motivo_rejeicao, chave, estado, recibo) {
                if (estado == 'rejeitado') {
                    let text = "Motivo Rejeição: " + motivo_rejeicao + "\n\n"
                    text += "Chave Acesso: " + chave + "\n"
                    swal("Nota Rejeitada", text, "warning")
                } else {
                    let text = "Chave Acesso: " + chave + "\n"
                    text += "Número Recibo: " + recibo + "\n"
                    swal("Nota Autorizada / Homologada", text, "success")
                }
            }
        </script>
        <script type="text/javascript" src="/js/nfe_transmitir.js"></script>
    @endsection