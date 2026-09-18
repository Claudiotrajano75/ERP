@extends('layouts.app', ['title' => 'Alterar Estado da OS'])

@section('css')
<style>
/* ─── Cards e Detalhes ─── */
.detail-card { background: #ffffff; border: 1px solid #eef0f5; border-radius: 12px; padding: 20px; box-shadow: 0 4px 12px rgba(0, 0, 0, 0.02); }
.pill { display: inline-flex; align-items: center; gap: 5px; border-radius: 8px; padding: 4px 12px; font-size: 12px; font-weight: 700; }
.pill-ok { background: #dcfce7; color: #15803d; }
.pill-no { background: #fee2e2; color: #b91c1c; }
.pill-info { background: #e0f2fe; color: #0369a1; }
.pill-amber { background: #fef3c7; color: #b45309; }

/* ─── Campos ─── */
.uf-field label, .uf-field .form-label { display: block; font-size: 13px !important; font-weight: 600 !important; color: #374151 !important; margin-bottom: 4px !important; }
.uf-field .form-label i, .uf-field label i { color: #64748b; font-size: 13px; }
.uf-field .form-control, .uf-field .form-select { height: 40px; border-radius: 10px; border: 1px solid #dcdce9; font-size: 13.5px; color: #1f2937; background: #fcfdfe; transition: all .15s ease; }
.uf-field .form-control:focus, .uf-field .form-select:focus { border-color: #4f46e5; box-shadow: 0 0 0 3px rgba(79,70,229,.12); background: #fff; }

/* ─── Rodapé de ações ─── */
.uf-actions { display: flex; align-items: center; justify-content: flex-end; gap: 10px; border-top: 1px solid #eef0f6; padding-top: 16px; margin-top: 20px; }
</style>
@endsection

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
                                <i class="ri-refresh-line"></i>
                                Alterar Estado / Status da OS #{{ $ordem->codigo_sequencial }}
                            </h4>
                            <p class="text-muted mb-0 modulo-subtitle fs-13">
                                Prossiga ou cancele o status de andamento de expediente da ordem de serviço.
                            </p>
                        </div>
                        <div class="d-flex gap-2 flex-wrap">
                            <a href="{{ route('ordem-servico.show', [$ordem->id]) }}" class="dash-btn dash-btn-light">
                                <i class="ri-arrow-left-line"></i> Voltar
                            </a>
                        </div>
                    </div>
                </div>

                <!-- ═══ CORPO DO FORMULÁRIO ═══ -->
                <div class="card-body p-4">
                    {!!Form::open()
                    ->post()
                    ->route('ordem-servico.update-estado', [$ordem->id])
                    !!}
                    @csrf
                    
                    <div class="row g-4">
                        <div class="col-12">
                            <div class="detail-card d-flex align-items-center justify-content-between flex-wrap gap-2">
                                <span class="fw-bold text-dark fs-14">Estado Atual da Ordem:</span>
                                <div>
                                    @if($ordem->estado == 'pd')
                                    <span class="pill pill-amber"><i class="ri-time-line"></i> PENDENTE</span>
                                    @elseif($ordem->estado == 'ap')
                                    <span class="pill pill-ok"><i class="ri-check-line"></i> APROVADA</span>
                                    @elseif($ordem->estado == 'rp')
                                    <span class="pill pill-no"><i class="ri-close-line"></i> REPROVADA</span>
                                    @else
                                    <span class="pill pill-info"><i class="ri-check-double-line"></i> FINALIZADA</span>
                                    @endif
                                </div>
                            </div>
                        </div>

                        @if($ordem->estado != 'fz' && $ordem->estado != 'rp')
                        
                        <div class="col-md-4 col-12 uf-field">
                            <label class="form-label required" for="estado"><i class="ri-exchange-line"></i> Selecione o Novo Estado</label>
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

                        <div class="col-md-4 col-12 d-none div-fatura uf-field">
                            <label class="form-label required"><i class="ri-money-dollar-box-line"></i> Gerar Faturamento</label>
                            {!!Form::select('faturar', '', [0 => 'Não', 1 => 'Sim'])
                            ->attrs(['class' => 'form-select'])->required()
                            !!}
                        </div>

                        <div class="col-12">
                            <div class="uf-actions">
                                <a href="{{ route('ordem-servico.show', [$ordem->id]) }}" class="dash-btn dash-btn-light px-4">
                                    <i class="ri-close-line"></i> Cancelar
                                </a>
                                <button type="submit" class="dash-btn dash-btn-primary px-4">
                                    <i class="ri-check-line"></i> Atualizar Status
                                </button>
                            </div>
                        </div>

                        @elseif($ordem->estado == 'fz')
                        <div class="col-12 py-4 text-center">
                            <i class="ri-checkbox-circle-line text-success display-4 mb-2 d-block"></i>
                            <h4 class="text-success fw-bold">Ordem de Serviço Finalizada!</h4>
                            <p class="text-muted">Esta ordem já foi concluída e não permite novas alterações de status.</p>
                            <a href="{{ route('ordem-servico.show', [$ordem->id]) }}" class="dash-btn dash-btn-light mt-2 px-4">
                                <i class="ri-arrow-left-line"></i> Voltar para a OS
                            </a>
                        </div>
                        @else
                        <div class="col-12 py-4 text-center">
                            <i class="ri-close-circle-line text-danger display-4 mb-2 d-block"></i>
                            <h4 class="text-danger fw-bold">Ordem de Serviço Reprovada!</h4>
                            <p class="text-muted">Esta ordem foi reprovada e arquivada.</p>
                            <a href="{{ route('ordem-servico.show', [$ordem->id]) }}" class="dash-btn dash-btn-light mt-2 px-4">
                                <i class="ri-arrow-left-line"></i> Voltar para a OS
                            </a>
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
    $('#estado').change(() => {
        let estado = $('#estado').val()
        if(estado == 'fz'){
            $('.div-fatura').removeClass('d-none')
        }else{
            $('.div-fatura').addClass('d-none')
        }
    })
</script>
@endsection