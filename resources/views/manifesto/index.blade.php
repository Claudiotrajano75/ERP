@extends('layouts.app', ['title' => 'Manifesto'])

@section('css')
<style>
.modulo-header-gradient { background: linear-gradient(135deg, #0f0c29 0%, #302b63 50%, #24243e 100%); border-radius: 12px 12px 0 0 !important; border-bottom: none !important; }
.modulo-header-gradient .modulo-title { color: #fff; font-weight: 700; letter-spacing: -0.3px; }
.modulo-header-gradient .modulo-title i { background: rgba(255,255,255,0.12); padding: 8px; border-radius: 10px; color: #a8b5ff; }
.modulo-header-gradient .modulo-subtitle { color: rgba(255,255,255,0.6) !important; font-weight: 400; }
.modulo-header-gradient .btn { border-radius: 8px; font-weight: 600; transition: all 0.2s ease; }
.modulo-header-gradient .btn:hover { transform: translateY(-1px); box-shadow: 0 4px 14px rgba(0,0,0,0.25); }
.modulo-glass-filter { background: rgba(255,255,255,0.7); backdrop-filter: blur(12px); -webkit-backdrop-filter: blur(12px); border: 1px solid rgba(255,255,255,0.8) !important; border-radius: 12px; box-shadow: 0 2px 20px rgba(0,0,0,0.04); }
.modulo-glass-filter label { font-weight: 600; font-size: 11px; text-transform: uppercase; letter-spacing: 0.4px; color: #5a5a7a; margin-bottom: 2px; }
.modulo-glass-filter .form-control, .modulo-glass-filter .form-select { height: 38px; } .modulo-glass-filter .btn { border-radius: 8px; font-weight: 600; font-size: 13px; height: 38px; padding-top: 0; padding-bottom: 0; display: inline-flex; align-items: center; justify-content: center; transition: all 0.2s; }
.modulo-glass-filter .btn:hover { transform: translateY(-1px); }
.modulo-table-wrap { border-radius: 12px; border: 1px solid #eef0f5; overflow: hidden; }
.modulo-table-wrap table { margin-bottom: 0; }
.modulo-table-wrap thead th { background: #f8f9fc; color: #5a5a7a; font-weight: 700; font-size: 11px; text-transform: uppercase; letter-spacing: 0.4px; padding: 12px 14px; border-bottom: 2px solid #e8eaf6; }
.modulo-table-wrap tbody td { padding: 12px 14px; vertical-align: middle; border-bottom: 1px solid #f0f2f8; transition: background 0.15s ease; font-size: 13px; }
.modulo-table-wrap tbody tr { transition: all 0.15s ease; }
.modulo-table-wrap tbody tr:hover { background: #f5f6fe; }
.modulo-table-wrap tbody tr:last-child td { border-bottom: none; }
@media (max-width: 768px) { .modulo-header-gradient .modulo-title { font-size: 18px; } }
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
                            <i class="ri-article-line"></i>
                            Manifesto do Destinatário (DF-e)
                        </h4>
                        <p class="text-muted mb-0 modulo-subtitle fs-13">Consulte todas as notas fiscais eletrônicas emitidas contra o CNPJ de sua empresa no SEFAZ e realize a manifestação.</p>
                    </div>
                    <div>
                        <a href="{{ route('manifesto.novaConsulta') }}" class="btn btn-light btn-sm px-3 text-dark">
                            <i class="ri-refresh-line align-middle me-1"></i> Consultar SEFAZ
                        </a>
                    </div>
                </div>
            </div>

            <div class="card-body p-4">
                
                <!-- ═══ Filtros Glass ═══ -->
                <div class="modulo-glass-filter p-3 mb-4">
                    {!!Form::open()->fill(request()->all())->get()!!}
                    <div class="row g-2 align-items-end">
                        <div class="col-md-3 col-6">
                            {!!Form::date('start_date', 'Data Inicial')!!}
                        </div>
                        <div class="col-md-3 col-6">
                            {!!Form::date('end_date', 'Data Final')!!}
                        </div>
                        <div class="col-md-3 col-12">
                            {!!Form::select('tipo', 'Tipo / Estado Manifesto', [
                                '' => 'Todos',
                                1 => 'Ciência',
                                2 => 'Confirmada',
                                3 => 'Desconhecido',
                                4 => 'Op. não Realizada'
                            ])->attrs(['class' => 'form-select'])!!}
                        </div>
                        <div class="col-md-3 col-12 ms-auto">
                            <div class="d-flex gap-2">
                                <button class="btn btn-primary btn-sm flex-grow-1" type="submit">
                                    <i class="ri-search-line me-1"></i> Pesquisar
                                </button>
                                <a class="btn btn-danger btn-sm px-3" href="{{ route('manifesto.index') }}">
                                    <i class="ri-eraser-line me-1"></i> Limpar
                                </a>
                            </div>
                        </div>
                    </div>
                    {!!Form::close()!!}
                </div>

                <!-- ═══ Tabela Premium ═══ -->
                <div class="modulo-table-wrap">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                            <tr>
                                <th>Nome do Emitente</th>
                                <th>CNPJ / CPF</th>
                                <th>Valor NFe (R$)</th>
                                <th>Data Emissão</th>
                                <th>Num. Protocolo</th>
                                <th>Chave de Acesso</th>
                                <th>Estado SEFAZ</th>
                                <th class="text-end" style="width: 150px;">Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($data as $item)
                            <tr>
                                <td class="fw-semibold text-dark">{{ $item->nome }}</td>
                                <td class="fw-bold text-muted">{{ $item->documento }}</td>
                                <td class="fw-bold text-success">R$ {{ __moeda($item->valor) }}</td>
                                <td>{{ __data_pt($item->data_emissao) }}</td>
                                <td>{{ $item->num_prot ?? '--' }}</td>
                                <td class="text-muted fs-11">{{ $item->chave }}</td>
                                <td>
                                    @if($item->tipo == 2)
                                    <span class="badge bg-success-subtle text-success border border-success-subtle px-2 py-1 fs-11">{{ $item->estado() }}</span>
                                    @elseif($item->tipo == 1)
                                    <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-2 py-1 fs-11">{{ $item->estado() }}</span>
                                    @elseif($item->tipo == 3)
                                    <span class="badge bg-danger-subtle text-danger border border-danger-subtle px-2 py-1 fs-11">{{ $item->estado() }}</span>
                                    @elseif($item->tipo == 4)
                                    <span class="badge bg-warning-subtle text-warning border border-warning-subtle px-2 py-1 fs-11">{{ $item->estado() }}</span>
                                    @else
                                    <span class="badge bg-light text-dark border px-2 py-1 fs-11">{{ $item->estado() }}</span>
                                    @endif
                                </td>
                                <td class="text-end">
                                    <div class="d-flex flex-column gap-1">
                                        @if($item->tipo == 1 || $item->tipo == 2)
                                        <a href="{{ route('manifesto.download', [$item->id]) }}" class="btn btn-success btn-xs py-0.5 text-white">Importar XML</a>
                                        <a target="_blank" href="{{ route('manifesto.danfe', [$item->id]) }}" class="btn btn-primary btn-xs py-0.5 text-white">Visualizar DANFE</a>
                                        @elseif($item->tipo == 3)
                                        <span class="badge bg-danger text-white">Desconhecida</span>
                                        @elseif($item->tipo == 4)
                                        <span class="badge bg-warning text-white">Não realizada</span>
                                        @endif
                                        @if($item->tipo != 2)
                                        <button class="btn btn-info btn-xs py-0.5 text-white" onclick="setChave('{{$item->chave}}')">Realizar Manifesto</button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="8" class="text-center text-muted py-4">Nenhum documento fiscal emitido contra a empresa localizado.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Paginação -->
                <div class="d-flex align-items-center justify-content-end mt-3">
                    {!! $data->appends(request()->all())->links() !!}
                </div>

            </div>
        </div>
    </div>
</div>

<!-- Modal Realizar Manifesto -->
<div class="modal fade" id="modal-evento" tabindex="-1" aria-labelledby="modalEventoLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form class="modal-content text-dark" method="post" action="{{ route('manifesto.manifestar') }}">
            @csrf
            <div class="modal-header">
                <h5 class="modal-title" id="modalEventoLabel">Manifestação de NFe Destinatário</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" name="chave" id="chave">
                <div class="row g-3">
                    <div class="col-12">
                        {!! Form::select('tipo', 'Tipo de Operação / Evento', [
                            1 => "Ciência da Emissão",
                            2 => "Confirmação da Operação",
                            3 => "Desconhecimento da Operação",
                            4 => "Operação não Realizada"
                        ])->attrs(['class' => 'form-select', 'id' => 'inp-tipo'])->required() !!}
                    </div>

                    <div class="col-12 just d-none">
                        {!! Form::text('justificativa', 'Justificativa do Evento (Mínimo 15 caracteres)')->attrs(['class' => 'form-control']) !!}
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light btn-sm" data-bs-dismiss="modal">Fechar</button>
                <button type="submit" class="btn btn-primary btn-sm px-4">Transmitir Manifesto</button>
            </div>
        </form>
    </div>
</div>

@endsection

@section('js')
<script type="text/javascript">
    function setChave(chave) {
        $('#chave').val(chave);
        $('#modal-evento').modal('show');
    }

    $(document).on("change", "#inp-tipo", function() {
        if ($(this).val() > 2) {
            $('.just').removeClass('d-none');
            $('#inp-justificativa').attr('required', 'required');
        } else {
            $('.just').addClass('d-none');
            $('#inp-justificativa').removeAttr('required');
        }
    });
</script>
@endsection
