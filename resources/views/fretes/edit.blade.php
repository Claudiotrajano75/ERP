@extends('layouts.app', ['title' => 'Editar Frete'])

@section('css')
<style>
.modulo-header-gradient { background: linear-gradient(135deg, #0f0c29 0%, #302b63 50%, #24243e 100%); border-radius: 12px 12px 0 0 !important; border-bottom: none !important; }
.modulo-header-gradient .modulo-title { color: #fff; font-weight: 700; letter-spacing: -0.3px; }
.modulo-header-gradient .modulo-title i { background: rgba(255,255,255,0.12); padding: 8px; border-radius: 10px; color: #a8b5ff; }
.modulo-header-gradient .modulo-subtitle { color: rgba(255,255,255,0.6) !important; font-weight: 400; }
.modulo-header-gradient .btn { border-radius: 8px; font-weight: 600; transition: all 0.2s ease; }
.modulo-header-gradient .btn:hover { transform: translateY(-1px); box-shadow: 0 4px 14px rgba(0,0,0,0.25); }

.modulo-form-card { border: 1px solid #eef0f5; border-radius: 12px; overflow: hidden; }
.modulo-form-card .card-body { background: #fff; }
.modulo-form-card .form-label,
.modulo-form-card label:not(.form-check-label) { font-weight: 600; font-size: 12px; color: #5a5a7a; margin-bottom: 4px; }
.modulo-form-card .form-control,
.modulo-form-card .form-select { border-radius: 8px; border-color: #e0e3eb; font-size: 13px; padding: 8px 12px; transition: all 0.15s ease; }
.modulo-form-card .form-control:focus,
.modulo-form-card .form-select:focus { border-color: #302b63; box-shadow: 0 0 0 3px rgba(48,43,99,0.08); }

.modulo-actions { padding: 16px 0 0; border-top: 1px solid #f0f2f8; margin-top: 24px; }
.modulo-actions .btn { border-radius: 8px; font-weight: 600; font-size: 13px; padding: 8px 20px; transition: all 0.2s ease; }
.modulo-actions .btn:hover { transform: translateY(-1px); }

@media (max-width: 768px) {
    .modulo-header-gradient .modulo-title { font-size: 18px; }
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
                    <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                        <div>
                            <h4 class="mb-1 modulo-title d-flex align-items-center gap-2">
                                <i class="ri-edit-line"></i>
                                Editar Frete
                            </h4>
                            <p class="text-muted mb-0 modulo-subtitle fs-13">
                                Atualize os dados do frete selecionado.
                            </p>
                        </div>
                        <div>
                            <a href="{{ route('fretes.index') }}" class="btn btn-light btn-sm px-3 text-dark">
                                <i class="ri-arrow-left-line align-middle me-1"></i> Voltar
                            </a>
                        </div>
                    </div>
                </div>

                <!-- ═══ CORPO DO FORMULÁRIO ═══ -->
                <div class="card-body p-4">
                    {!!Form::open()->fill($item)
                    ->put()
                    ->route('fretes.update', [$item->id])
                    ->multipart()
                    !!}

                    @include('fretes._forms')

                    {!!Form::close()!!}
                </div>

            </div>
        </div>
    </div>
</div>
@include('modals._novo_cliente')
@endsection

@section('js')
<script src="/js/frete.js"></script>
<script src="/js/novo_cliente.js"></script>
@endsection
