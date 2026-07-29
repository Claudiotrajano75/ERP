@extends('layouts.app', ['title' => 'Escritório Contábil'])

@section('css')
<style>
    .modulo-header-gradient { background: linear-gradient(135deg, #0d2b40 0%, #1a4a6e 100%); border-radius: 12px 12px 0 0 !important; border-bottom: none !important; }
    .modulo-header-gradient .modulo-title { color: #fff; font-weight: 700; letter-spacing: -0.3px; }
    .modulo-header-gradient .modulo-title i { background: rgba(255,255,255,0.15); padding: 8px; border-radius: 10px; color: #fff; }
    .modulo-header-gradient .modulo-subtitle { color: rgba(255,255,255,0.85) !important; font-weight: 400; }
    
    .modulo-form-card { border: 1px solid #eef0f5; border-radius: 12px; overflow: hidden; background: #fff; }

    /* ─── Cards Internos (Secções) ─── */
    .modulo-section-card {
        background: #fdfdfd;
        border: 1px solid #e9ecef;
        border-radius: 10px;
        margin-bottom: 20px;
    }
    .modulo-section-card .card-header {
        background: #f8f9fa;
        border-bottom: 1px solid #e9ecef;
        border-radius: 10px 10px 0 0;
        padding: 12px 20px;
    }
    .modulo-section-card .card-header h4 {
        margin: 0;
        font-size: 15px;
        font-weight: 600;
        color: #343a40;
        display: flex;
        align-items: center;
    }
</style>
@endsection

@section('content')
<div class="mt-3">
    <div class="card modulo-form-card shadow-sm">
        <div class="card-header modulo-header-gradient py-3 px-4">
            <h4 class="mb-1 modulo-title d-flex align-items-center gap-2">
                <i class="ri-team-fill"></i>
                Escritório Contábil
            </h4>
            <p class="mb-0 modulo-subtitle fs-13">
                Gerencie os dados da sua assessoria contábil.
            </p>
        </div>
        <div class="card-body p-4">
            {!!Form::open()->fill($item)
            ->post()
            ->route('escritorio-contabil.store')
            !!}
            <div class="pl-lg-4">
                @include('escritorio_contabil._forms')
            </div>
            {!!Form::close()!!}
        </div>
    </div>
</div>
@endsection
