@extends('layouts.app', ['title' => 'Alterar Estado da OS'])
@section('content')

<div class="mt-3">
    <div class="row justify-content-center">
        <div class="col-lg-12">
            <div class="card border-0 shadow-sm text-dark">
                <!-- Cabeçalho -->
                <div class="card-header bg-transparent border-bottom py-3">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <h4 class="mb-1 text-dark d-flex align-items-center">
                                <i class="ri-refresh-line me-2 text-info fs-22"></i>
                                Alterar Estado / Status da OS #{{ $ordem->codigo_sequencial }}
                            </h4>
                            <p class="text-muted mb-0 fs-13">Prossiga ou cancele o status de andamento de expediente da ordem de serviço.</p>
                        </div>
                        <div>
                            <a href="{{ route('ordem-servico.show', [$ordem->id]) }}" class="btn btn-danger btn-sm px-3">
                                <i class="ri-arrow-left-line align-middle me-1"></i> Voltar
                            </a>
                        </div>
                    </div>
                </div>
                <!-- Corpo do Formulário -->
                <div class="card-body p-4">
                    {!!Form::open()
                    ->post()
                    ->route('ordem-servico.update-estado', [$ordem->id])
                    !!}
                    @csrf
                    
                    <div class="row g-3">
                        <div class="col-12 mb-2">
                            <div class="p-3 bg-light border rounded shadow-sm d-flex align-items-center justify-content-between">
                                <span class="fw-semibold text-dark">Estado Atual da OS:</span>
                                <div>
                                    @if($ordem->estado == 'pd')
                                    <span class="badge bg-warning-subtle text-warning border border-warning-subtle px-3 py-2 fs-12">PENDENTE</span>
                                    @elseif($ordem->estado == 'ap')
                                    <span class="badge bg-success-subtle text-success border border-success-subtle px-3 py-2 fs-12">APROVADA</span>
                                    @elseif($ordem->estado == 'rp')
                                    <span class="badge bg-danger-subtle text-danger border border-danger-subtle px-3 py-2 fs-12">REPROVADA</span>
                                    @else
                                    <span class="badge bg-info-subtle text-info border border-info-subtle px-3 py-2 fs-12">FINALIZADA</span>
                                    @endif
                                </div>
                            </div>
                        </div>

                        @if($ordem->estado != 'fz' && $ordem->estado != 'rp')
                        
                        <div class="col-md-4 col-12">
                            <label class="form-label fw-semibold text-dark mb-1 required">Selecione o Novo Estado</label>
                            @if($ordem->estado == 'pd')
                            <select required class="form-select" id="estado" name="novo_estado">
                                <option value="ap">APROVADO</option>
                                <option value="rp">REPROVADO</option>
                            </select>
                            @elseif($ordem->estado == 'ap')
                            <select class="form-select" id="estado" name="novo_estado">
                                <option value="fz">FINALIZADO</option>
                            </select>
                            @endif
                        </div>

                        <div class="col-md-4 col-12 d-none div-fatura">
                            {!!Form::select('faturar', 'Gerar faturamento', [0 => 'Não', 1 => 'Sim'])
                            ->attrs(['class' => 'form-select'])->required()
                            !!}
                        </div>

                        <div class="col-12 mt-4">
                            <hr class="text-muted opacity-25">
                            <div class="d-flex align-items-center justify-content-end gap-2">
                                <a href="{{ route('ordem-servico.show', [$ordem->id]) }}" class="btn btn-light px-4">Cancelar</a>
                                <button type="submit" class="btn btn-success px-4">
                                    <i class="ri-check-line align-middle me-1"></i> Atualizar Status
                                </button>
                            </div>
                        </div>

                        @elseif($ordem->estado == 'fz')
                        <div class="col-12 py-3 text-center">
                            <i class="ri-checkbox-circle-line text-success display-4 mb-2 d-block"></i>
                            <h4 class="text-success fw-bold">Ordem de Serviço Finalizada!</h4>
                            <p class="text-muted">Esta ordem já foi concluída e não permite novas alterações de status.</p>
                            <a href="{{ route('ordem-servico.show', [$ordem->id]) }}" class="btn btn-light mt-2 px-4">Voltar para a OS</a>
                        </div>
                        @else
                        <div class="col-12 py-3 text-center">
                            <i class="ri-close-circle-line text-danger display-4 mb-2 d-block"></i>
                            <h4 class="text-danger fw-bold">Ordem de Serviço Reprovada!</h4>
                            <p class="text-muted">Esta ordem foi reprovada e arquivada.</p>
                            <a href="{{ route('ordem-servico.show', [$ordem->id]) }}" class="btn btn-light mt-2 px-4">Voltar para a OS</a>
                        </div>
                        @endif
                    </div>

                    {!!Form::close()!!}
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@section('js')
<script type="text/javascript">
    $(function(){
        changeEstado()
    })

    $('#estado').change(() => {
        changeEstado()
    })

    function changeEstado(){
        let estado = $('#estado').val()
        if(estado == 'fz'){
            $('.div-fatura').removeClass('d-none')
        }else{
            $('.div-fatura').addClass('d-none')
        }
    }
</script>
@endsection