@extends('layouts.app', ['title' => 'Novo Carrossel'])

@section('css')
<style>
    .modulo-header-gradient { background: linear-gradient(135deg, #0f0c29 0%, #302b63 50%, #24243e 100%); border-radius: 12px 12px 0 0 !important; border-bottom: none !important; }
    .modulo-header-gradient .modulo-title { color: #fff; font-weight: 700; letter-spacing: -0.3px; }
    .modulo-header-gradient .modulo-title i { background: rgba(255,255,255,0.12); padding: 8px; border-radius: 10px; color: #a8b5ff; }
    .modulo-header-gradient .btn { border-radius: 8px; font-weight: 600; transition: all 0.2s ease; }
    .modulo-header-gradient .btn:hover { transform: translateY(-1px); box-shadow: 0 4px 14px rgba(0,0,0,0.25); }
    .modulo-form-card { border: 1px solid #eef0f5; border-radius: 12px; overflow: hidden; }
    .modulo-form-card .card-body label:not(.form-check-label) { font-weight: 600; font-size: 12px; color: #5a5a7a; margin-bottom: 4px; }
    .modulo-form-card .form-control, .modulo-form-card .form-select { border-radius: 8px; border-color: #e0e3eb; font-size: 13px; padding: 8px 12px; }
</style>
@endsection

@section('content')
<div class="mt-3 text-dark">
    <div class="card border-0 shadow-sm modulo-form-card">
        <div class="card-header modulo-header-gradient py-3 px-4">
            <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                <h4 class="mb-0 modulo-title d-flex align-items-center gap-2">
                    <i class="ri-image-add-line"></i> Novo Carrossel
                </h4>
                <a href="{{ route('carrossel.index') }}" class="btn btn-light btn-sm px-3 text-dark d-flex align-items-center gap-1">
                    <i class="ri-arrow-left-line fs-16"></i> Voltar
                </a>
            </div>
        </div>
        <div class="card-body p-4">
            {!!Form::open()
            ->post()
            ->route('carrossel.store')
            ->multipart()
            !!}
            @include('carrossel._forms')
            {!!Form::close()!!}
        </div>
    </div>
</div>
@endsection
