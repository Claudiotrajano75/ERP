@extends('layouts.app', ['title' => 'MDF-e — Documentos Não Encerrados'])

@section('content')
<div class="mt-3">
    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm">

                <!-- ═══ CABEÇALHO PREMIUM ═══ -->
                <div class="card-header modulo-header-gradient py-3 px-4">
                    <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                        <div>
                            <h4 class="mb-1 modulo-title d-flex align-items-center gap-2">
                                <i class="ri-alert-line"></i>
                                MDF-e — Manifestos Não Encerrados
                            </h4>
                            <p class="text-muted mb-0 modulo-subtitle fs-13">
                                Documentos autorizados que ainda não foram encerrados no ambiente da SEFAZ.
                            </p>
                        </div>
                        <div class="d-inline-flex align-items-center gap-2">
                            <a href="{{ route('mdfe.index') }}" class="dash-btn dash-btn-light">
                                <i class="ri-arrow-left-line"></i> Voltar
                            </a>
                        </div>
                    </div>
                </div>

                <div class="card-body p-4">
                    @if(count($data) > 0)
                    <div class="tb-wrap mb-3">
                        <div class="table-responsive">
                            <table class="table table-centered table-hover align-middle mb-0">
                                <thead>
                                    <tr>
                                        <th>Chave de Acesso MDF-e</th>
                                        <th>Protocolo de Autorização</th>
                                        <th class="text-end" style="width: 160px;">Ação</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($data as $m)
                                    <tr>
                                        <td class="fs-13 text-dark font-monospace fw-bold">{{ $m['chave'] }}</td>
                                        <td class="fs-13 text-secondary font-monospace">{{ $m['protocolo'] }}</td>
                                        <td class="text-end">
                                            <button type="button" class="dash-btn dash-btn-danger"
                                                    onclick="encerrar('{{ $m['chave'] }}', '{{ $m['protocolo'] }}')">
                                                <i class="ri-stop-circle-line"></i> Encerrar MDF-e
                                            </button>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    @else
                    <div class="text-center py-5">
                        <i class="ri-checkbox-circle-line text-success" style="font-size: 52px;"></i>
                        <h5 class="mt-3 text-dark">Nenhum MDF-e pendente de encerramento!</h5>
                        <p class="text-muted fs-13">Todos os manifestos autorizados já foram devidamente encerrados na SEFAZ.</p>
                    </div>
                    @endif
                </div>

            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modal-encerrar" aria-labelledby="modal-encerrar-label" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title text-white" id="modal-encerrar-label"><i class="ri-stop-circle-line me-1"></i> Encerrar MDF-e</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <div class="row g-3">
                    <div class="col-md-12">
                        <label class="form-label fw-bold fs-13"><i class="ri-map-pin-line me-1"></i> Município de Encerramento</label>
                        <select name="municipio_encerramento" id="municipio_encerramento" class="select2 form-select">
                            <option value="">Selecione o município de encerramento</option>
                            @foreach($cidades as $c)
                            <option value="{{ $c->id }}">{{ $c->info }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
            <div class="modal-footer bg-light">
                <button type="button" class="dash-btn dash-btn-light" data-bs-dismiss="modal">Fechar</button>
                <button type="button" id="btn-encerrar" class="dash-btn dash-btn-danger">
                    <i class="ri-stop-circle-line"></i> Confirmar Encerramento
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('js')
<script type="text/javascript" src="/js/mdfe_transmitir.js"></script>
@endsection
