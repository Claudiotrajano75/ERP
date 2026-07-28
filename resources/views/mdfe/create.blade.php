@extends('layouts.app', ['title' => 'Nova MDF-e'])

@section('css')
<style>
/* ─── Header Gradiente ─── */
.modulo-header-gradient { background: linear-gradient(135deg, #0f0c29 0%, #302b63 50%, #24243e 100%); border-radius: 12px 12px 0 0 !important; border-bottom: none !important; }
.modulo-header-gradient .modulo-title { color: #fff; font-weight: 700; letter-spacing: -0.3px; }
.modulo-header-gradient .modulo-title i { background: rgba(255,255,255,0.12); padding: 8px; border-radius: 10px; color: #a8b5ff; }
.modulo-header-gradient .modulo-subtitle { color: rgba(255,255,255,0.6) !important; font-weight: 400; }
.modulo-header-gradient .btn { border-radius: 8px; font-weight: 600; transition: all 0.2s ease; }
.modulo-header-gradient .btn:hover { transform: translateY(-1px); box-shadow: 0 4px 14px rgba(0,0,0,0.25); }

/* ─── Botão Salvar ─── */
.btn-salvar-modulo { border-radius: 10px; font-weight: 700; font-size: 14px; padding: 10px 36px; letter-spacing: 0.3px; transition: all 0.2s ease; box-shadow: 0 4px 14px rgba(40,167,69,0.25); }
.btn-salvar-modulo:hover:not(:disabled) { transform: translateY(-2px); box-shadow: 0 6px 20px rgba(40,167,69,0.35); }
.btn-salvar-modulo:disabled { opacity: 0.55; cursor: not-allowed; }

/* ─── Seções de formulário ─── */
.form-section-card { border: 1px solid #eef0f5; border-radius: 10px; background: #fff; margin-bottom: 16px; }
.form-section-card .section-header { padding: 12px 16px; border-bottom: 1px solid #eef0f5; background: #f8f9fc; border-radius: 10px 10px 0 0; }
.form-section-card .section-header h5 { margin: 0; font-weight: 700; font-size: 13px; text-transform: uppercase; letter-spacing: 0.4px; color: #5a5a7a; }
.form-section-card .section-body { padding: 20px; }
</style>
@endsection

@section('content')
<div class="mt-3 text-dark">
    <div class="row">
        <div class="card border-0 shadow-sm text-dark">

            {{-- ═══ CABEÇALHO PREMIUM ═══ --}}
            <div class="card-header modulo-header-gradient py-3 px-4">
                <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                    <div>
                        <h4 class="mb-1 modulo-title d-flex align-items-center gap-2">
                            <i class="ri-road-map-line"></i>
                            Nova MDF-e
                        </h4>
                        <p class="text-muted mb-0 modulo-subtitle fs-13">
                            Preencha os dados para emitir o manifesto.
                        </p>
                    </div>
                    <div>
                        <a href="{{ route('mdfe.index') }}" class="btn btn-light btn-sm px-3 text-dark">
                            <i class="ri-arrow-left-line align-middle me-1"></i> Voltar
                        </a>
                    </div>
                </div>
            </div>

            <div class="card-body p-4">
                {!!Form::open()
                ->post()
                ->route('mdfe.store')
                ->multipart()
                !!}

                @include('mdfe._forms')

                {!!Form::close()!!}
            </div>

        </div>
    </div>
</div>
@endsection

@section('js')
<script src="/js/mdfe.js"></script>
@endsection
