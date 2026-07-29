@extends('layouts.app', ['title' => 'Sintegra'])

@section('css')
    <style>
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
            background: rgba(255, 255, 255, 0.12);
            padding: 8px;
            border-radius: 10px;
            color: #a8b5ff;
        }

        .modulo-header-gradient .modulo-subtitle {
            color: rgba(255, 255, 255, 0.6) !important;
            font-weight: 400;
        }

        /* ─── Form Card ─── */
        .modulo-form-card {
            border: 1px solid #eef0f5;
            border-radius: 12px;
            overflow: hidden;
        }

        .modulo-form-card .card-body {
            background: #fff;
        }

        .modulo-form-card .form-label,
        .modulo-form-card label:not(.form-check-label) {
            font-weight: 600;
            font-size: 12px;
            color: #5a5a7a;
            margin-bottom: 4px;
        }

        .modulo-form-card .form-control,
        .modulo-form-card .form-select {
            border-radius: 8px;
            border-color: #e0e3eb;
            font-size: 13px;
            padding: 8px 12px;
            transition: all 0.15s ease;
        }

        .modulo-form-card .form-control:focus,
        .modulo-form-card .form-select:focus {
            border-color: #302b63;
            box-shadow: 0 0 0 3px rgba(48, 43, 99, 0.08);
        }

        /* ─── Botões de Ação do Formulário ─── */
        .modulo-actions {
            padding: 16px 0 0;
            border-top: 1px solid #f0f2f8;
            margin-top: 24px;
        }

        .modulo-actions .btn {
            border-radius: 8px;
            font-weight: 600;
            font-size: 13px;
            padding: 8px 20px;
            transition: all 0.2s ease;
        }

        .modulo-actions .btn:hover {
            transform: translateY(-1px);
        }
    </style>
@endsection

@section('content')
    <div class="mt-3 text-dark">
        <div class="row justify-content-center">
            <div class="col-lg-12">
                <div class="card border-0 shadow-sm modulo-form-card">

                    <!-- ═══ CABEÇALHO PREMIUM ═══ -->
                    <div class="card-header modulo-header-gradient py-3 px-4">
                        <div>
                            <h4 class="mb-1 modulo-title d-flex align-items-center gap-2">
                                <i class="ri-file-download-line"></i>
                                Gerar Arquivo Sintegra
                            </h4>
                            <p class="text-muted mb-0 modulo-subtitle fs-13">
                                Filtre por período e local para exportar o arquivo do Sintegra.
                            </p>
                        </div>
                    </div>

                    <!-- ═══ CORPO DO FORMULÁRIO ═══ -->
                    <div class="card-body p-4">
                        {!!Form::open()->post()->route('sintegra.store')!!}

                        <div class="row g-3 text-dark">
                            <!-- Seção Principal -->
                            <div class="col-12">
                                <h5 class="text-dark border-bottom pb-2 mb-3">
                                    <i class="ri-calendar-event-line text-primary me-2 align-middle fs-18"></i>
                                    Filtros de Geração
                                </h5>

                                <div class="row g-3">
                                    <div class="col-md-4 col-6">
                                        {!!Form::date('start_date', 'Data de início')->required()!!}
                                    </div>

                                    <div class="col-md-4 col-6">
                                        {!!Form::date('end_date', 'Data de fim')->required()!!}
                                    </div>

                                    @if(__countLocalAtivo() > 1)
                                                                <div class="col-md-4 col-12">
                                                                    {!!Form::select('local_id', 'Local', ['' => 'Selecione'] + __getLocaisAtivoUsuario()->pluck('descricao', 'id')->all())
                                        ->attrs(['class' => 'form-select select2'])
                                                                    !!}
                                                                </div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- ═══ FOOTER AÇÕES ═══ -->
                        <div class="modulo-actions">
                            <div class="d-flex gap-2 justify-content-end">
                                <button type="submit" class="btn btn-success px-4">
                                    <i class="ri-download-cloud-2-line align-middle me-1"></i> Gerar Arquivo
                                </button>
                            </div>
                        </div>

                        {!!Form::close()!!}
                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection