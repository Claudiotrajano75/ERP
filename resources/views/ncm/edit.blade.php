@extends('layouts.app', ['title' => 'Editar NCM'])

@section('css')
<style type="text/css">
    /* Estilos Personalizados para a Página */
    .card {
        border: 1px solid rgba(0, 0, 0, 0.06) !important;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.02) !important;
        border-radius: 16px !important;
        overflow: hidden;
        background: #fff;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1) !important;
        margin-bottom: 24px;
    }

    .card-body {
        padding: 28px !important;
    }

    /* Cabeçalho de Gradiente Premium */
    .modulo-header-gradient {
        background: linear-gradient(135deg, #0f0c29 0%, #302b63 50%, #24243e 100%) !important;
        border-radius: 12px 12px 0 0 !important;
        border-bottom: none !important;
        padding: 20px 24px !important;
    }

    .modulo-header-gradient .modulo-title {
        color: #fff !important;
        font-weight: 700 !important;
        letter-spacing: -0.3px !important;
        margin: 0 !important;
        display: flex !important;
        align-items: center !important;
        gap: 12px !important;
    }

    .modulo-header-gradient .modulo-title i {
        background: rgba(255, 255, 255, 0.1) !important;
        padding: 8px !important;
        border-radius: 10px !important;
        color: #a8b5ff !important;
        font-size: 20px !important;
        display: inline-flex !important;
        align-items: center !important;
        justify-content: center !important;
    }

    .modulo-header-gradient .modulo-subtitle {
        color: rgba(255, 255, 255, 0.6) !important;
        font-weight: 400 !important;
        font-size: 13px !important;
        margin-top: 4px !important;
        margin-bottom: 0 !important;
    }

    /* Inputs e Selects */
    .form-control, select {
        border: 1px solid #e2e8f0 !important;
        border-radius: 10px !important;
        padding: 10px 14px !important;
        font-size: 13px !important;
        color: #334155 !important;
        transition: all 0.2s ease !important;
        box-shadow: none !important;
    }

    .form-control:focus, select:focus {
        border-color: #4f46e5 !important;
        box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.1) !important;
    }

    .form-label, label {
        font-weight: 600 !important;
        color: #475569 !important;
        font-size: 13px !important;
        margin-bottom: 6px !important;
    }

    /* Botões */
    .btn {
        border-radius: 10px !important;
        font-weight: 500 !important;
        font-size: 13px !important;
        padding: 10px 20px !important;
        transition: all 0.2s ease !important;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
    }

    .btn-sm {
        padding: 6px 14px !important;
        font-size: 12px !important;
        border-radius: 8px !important;
    }

    .btn-success {
        background-color: #10b981 !important;
        border-color: #10b981 !important;
        color: #fff !important;
    }

    .btn-success:hover {
        background-color: #059669 !important;
        border-color: #059669 !important;
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(16, 185, 129, 0.2) !important;
    }

    .modulo-actions {
        border-top: 1px solid #f1f5f9;
        padding-top: 20px;
        margin-top: 24px;
    }
</style>
@endsection

@section('content')
<div class="mt-3 text-dark">
    <div class="row justify-content-center">
        <div class="col-lg-12">
            <div class="card border-0 shadow-sm">

                <!-- ═══ CABEÇALHO PREMIUM ═══ -->
                <div class="card-header modulo-header-gradient">
                    <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                        <div>
                            <h4 class="mb-0 modulo-title">
                                <i class="ri-pencil-line"></i>
                                Editar NCM
                            </h4>
                            <p class="modulo-subtitle">
                                Atualize as informações do código fiscal ou da descrição do item.
                            </p>
                        </div>
                        <div>
                            <a href="{{ route('ncm.index') }}" class="btn btn-light btn-sm text-dark">
                                <i class="ri-arrow-left-line me-1"></i> Voltar
                            </a>
                        </div>
                    </div>
                </div>

                <!-- ═══ FORMULÁRIO ═══ -->
                <div class="card-body">
                    {!!Form::open()->fill($item)->put()->route('ncm.update', [$item->id])!!}
                    @include('ncm._forms')
                    {!!Form::close()!!}
                </div>

            </div>
        </div>
    </div>
</div>
@endsection
