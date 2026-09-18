@extends('layouts.app', ['title' => 'Gerador Sintegra'])

@section('css')
<style>
/* ─── Painéis de Seção Interna ─── */
.card-secao-fiscal {
    border: 1px solid #eef2f6 !important;
    border-radius: 14px !important;
    box-shadow: 0 2px 8px rgba(0,0,0,0.02) !important;
    margin-bottom: 24px !important;
    background: #ffffff;
    overflow: hidden;
}
.card-secao-fiscal .card-header {
    background: #f8fafc;
    border-bottom: 1px solid #edf2f7;
    padding: 14px 20px;
}
.card-secao-fiscal .card-header h5 {
    margin: 0;
    font-size: 14px;
    font-weight: 700;
    color: #1e293b;
    display: flex;
    align-items: center;
    gap: 8px;
}
.card-secao-fiscal .card-body { padding: 24px !important; }
</style>
@endsection

@section('content')
<div class="mt-3 text-dark">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="card border-0 shadow-sm text-dark modulo-form-card">

                <!-- ═══ CABEÇALHO ═══ -->
                <div class="card-header modulo-header-gradient py-3 px-4">
                    <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                        <div>
                            <h4 class="mb-1 modulo-title d-flex align-items-center gap-2">
                                <i class="ri-file-download-line"></i>
                                Geração de Arquivo Magnético Sintegra
                            </h4>
                            <p class="text-muted mb-0 modulo-subtitle fs-13">
                                Exporte os registros fiscais consolidados de entradas, saídas e estoque no padrão Sintegra estadual.
                            </p>
                        </div>
                        <div class="d-flex align-items-center gap-2">
                            <a href="{{ route('config.index') }}" class="dash-btn dash-btn-light">
                                <i class="ri-arrow-left-line"></i> Config. Fiscais
                            </a>
                        </div>
                    </div>
                </div>

                <!-- ═══ CORPO DO FORMULÁRIO ═══ -->
                <div class="card-body p-4">
                    {!!Form::open()->post()->route('sintegra.store')!!}

                    <div class="card card-secao-fiscal">
                        <div class="card-header">
                            <h5><i class="ri-calendar-event-line text-primary"></i> Período e Local de Emissão</h5>
                        </div>
                        <div class="card-body">
                            <div class="row g-3">
                                <div class="col-md-6 col-12">
                                    <label class="form-label fw-semibold">Data Inicial do Período</label>
                                    {!!Form::date('start_date', '')->attrs(['class' => 'form-control', 'value' => date('Y-m-01')])->required()!!}
                                </div>

                                <div class="col-md-6 col-12">
                                    <label class="form-label fw-semibold">Data Final do Período</label>
                                    {!!Form::date('end_date', '')->attrs(['class' => 'form-control', 'value' => date('Y-m-t')])->required()!!}
                                </div>

                                @if(__countLocalAtivo() > 1)
                                <div class="col-12 mt-2">
                                    <label class="form-label fw-semibold">Filtrar por Estabelecimento / Local</label>
                                    {!!Form::select('local_id', '', ['' => 'Todos os Estabelecimentos'] + __getLocaisAtivoUsuario()->pluck('descricao', 'id')->all())
                                    ->attrs(['class' => 'form-select select2'])
                                    !!}
                                </div>
                                @endif
                            </div>

                            <div class="row mt-4">
                                <div class="col-12">
                                    <div class="alert alert-info border-0 shadow-sm py-3 px-3 mb-0" style="border-radius: 12px; background: #f0f4ff;">
                                        <div class="d-flex align-items-start gap-2">
                                            <i class="ri-information-line fs-18 text-primary mt-0.5"></i>
                                            <div class="fs-13 text-dark">
                                                <strong>O que é o Sintegra:</strong> O Sistema Integrado de Informações sobre Operações Interestaduais com Mercadorias e Serviços compila os registros fiscais (tipos 10, 11, 50, 54, 61, 70, 75 e 90) das notas autorizadas (NFe/NFCe) para validação junto à SEFAZ e envio à sua contabilidade.
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- ═══ BOTÃO GERAR ═══ -->
                    <div class="d-flex align-items-center justify-content-end gap-2 pt-2">
                        <a href="{{ route('config.index') }}" class="dash-btn dash-btn-light">
                            <i class="ri-close-line me-1"></i> Cancelar
                        </a>
                        <button type="submit" class="dash-btn dash-btn-primary px-5">
                            <i class="ri-download-cloud-2-line me-1"></i> Gerar e Baixar Arquivo .TXT
                        </button>
                    </div>

                    {!!Form::close()!!}
                </div>

            </div>
        </div>
    </div>
</div>
@endsection