@extends('layouts.app', ['title' => 'Nova Remessa'])

@section('css')
<style>
/* ─── Filtro de Pesquisa Premium ─── */
.modulo-glass-filter-premium {
    background: #ffffff;
    border: 1px solid #eef0f6 !important;
    border-radius: 14px;
    box-shadow: 0 4px 14px rgba(0, 0, 0, 0.02);
    padding: 20px !important;
    margin-bottom: 24px;
}
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
    color: #4f46e5;
    margin-right: 6px;
}
.modulo-glass-filter-premium label {
    font-size: 11px !important;
    font-weight: 700 !important;
    text-transform: uppercase;
    letter-spacing: 0.4px;
    color: #64748b !important;
    margin-bottom: 6px !important;
    display: flex;
    align-items: center;
    gap: 5px;
}
.modulo-glass-filter-premium .form-control,
.modulo-glass-filter-premium .form-select {
    height: 40px !important;
    border-radius: 9px !important;
    border: 1px solid #e2e8f0 !important;
    font-size: 13px !important;
    padding: 6px 12px !important;
    color: #334155 !important;
    background-color: #fcfdfe !important;
    transition: all 0.2s ease;
}
.modulo-glass-filter-premium .form-control:focus,
.modulo-glass-filter-premium .form-select:focus {
    border-color: #4f46e5 !important;
    background-color: #fff !important;
    box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.12) !important;
}
.modulo-glass-filter-premium .btn-pesquisar {
    background: linear-gradient(135deg, #4f46e5 0%, #3730a3 100%) !important;
    border: none !important;
    color: #fff !important;
    font-weight: 600 !important;
    height: 40px;
    border-radius: 9px !important;
    font-size: 13px !important;
    transition: all 0.2s ease !important;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 6px;
}
.modulo-glass-filter-premium .btn-pesquisar:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(79, 70, 229, 0.25) !important;
}
.modulo-glass-filter-premium .btn-limpar {
    background: #f1f5f9 !important;
    border: 1px solid #e2e8f0 !important;
    color: #64748b !important;
    font-weight: 600 !important;
    height: 40px;
    border-radius: 9px !important;
    font-size: 13px !important;
    transition: all 0.2s ease !important;
    display: inline-flex;
    align-items: center;
    justify-content: center;
}
.modulo-glass-filter-premium .btn-limpar:hover {
    background: #e2e8f0 !important;
    color: #334155 !important;
}

/* ─── Tabela ─── */
.tb-wrap { border-radius: 14px; border: 1px solid #eef0f5; overflow: hidden; background: #fff; }
.tb-wrap table { margin-bottom: 0; }
.tb-wrap thead th { background: #f8f9fc; color: #5a5a7a; font-weight: 700; font-size: 11px; text-transform: uppercase; letter-spacing: .4px; padding: 13px 16px; border-bottom: 1px solid #e8eaf6; white-space: nowrap; }
.tb-wrap tbody td { padding: 13px 16px; vertical-align: middle; border-bottom: 1px solid #f0f2f8; font-size: 13.5px; color: #374151; }
.tb-wrap tbody tr:hover { background: #f5f6fe; }
.tb-wrap tbody tr:last-child td { border-bottom: none; }
</style>
@endsection

@section('content')
<div class="mt-3 text-dark">
    <div class="row justify-content-center">
        <div class="col-lg-12">
            <div class="card border-0 shadow-sm text-dark modulo-form-card">

                <!-- CABEÇALHO PREMIUM -->
                <div class="card-header modulo-header-gradient py-3 px-4">
                    <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                        <div>
                            <h4 class="mb-1 modulo-title d-flex align-items-center gap-2">
                                <i class="ri-add-circle-line"></i>
                                Gerar Arquivo de Remessa CNAB
                            </h4>
                            <p class="text-muted mb-0 modulo-subtitle fs-13">Filtre os boletos gerados e selecione os títulos a serem incluídos no novo arquivo de remessa.</p>
                        </div>
                        <div>
                            <a href="{{ route('remessa-boleto.index') }}" class="dash-btn dash-btn-light">
                                <i class="ri-arrow-left-line"></i> Voltar
                            </a>
                        </div>
                    </div>
                </div>

                <div class="card-body p-4">

                    <!-- Filtros de Busca de Boletos -->
                    <div class="modulo-glass-filter-premium">
                        <div class="filtro-premium-header">
                            <h5 class="filtro-premium-title">
                                <i class="ri-search-line"></i> Filtrar Boletos para Remessa
                            </h5>
                        </div>

                        {!!Form::open()->fill(request()->all())->get()!!}
                        <div class="row g-3">
                            <div class="col-md-3 col-6">
                                <label class="form-label"><i class="ri-calendar-line"></i> Vencimento Inicial</label>
                                {!!Form::date('start_date', '')->attrs(['class' => 'form-control'])!!}
                            </div>
                            <div class="col-md-3 col-6">
                                <label class="form-label"><i class="ri-calendar-line"></i> Vencimento Final</label>
                                {!!Form::date('end_date', '')->attrs(['class' => 'form-control'])!!}
                            </div>
                            <div class="col-md-3 col-12">
                                <label class="form-label"><i class="ri-bank-card-line"></i> Conta de Boleto</label>
                                {!!Form::select('conta_boleto_id', '', ['' => 'Todas as Contas'] + $contasBoleto->pluck('info', 'id')->all())
                                ->attrs(['class' => 'form-select'])!!}
                            </div>
                            <div class="col-md-3 col-12 ms-auto d-flex align-items-end">
                                <div class="d-flex gap-2 w-100">
                                    <button class="btn btn-pesquisar flex-grow-1" type="submit">
                                        <i class="ri-search-line"></i> Filtrar Boletos
                                    </button>
                                    <a class="btn btn-limpar px-3" href="{{ route('remessa-boleto.create') }}" title="Limpar Filtro">
                                        <i class="ri-eraser-line"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                        {!!Form::close()!!}
                    </div>

                    <!-- Formulário de Criação -->
                    {!!Form::open()
                    ->post()
                    ->route('remessa-boleto.store')
                    !!}
                    
                    @include('remessa_boletos._forms')
                    
                    {!!Form::close()!!}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
