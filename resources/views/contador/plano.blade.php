@extends('layouts.app', ['title' => 'Atribuir Plano'])

@section('css')
<style>
    .uf-field { margin-bottom: 14px; }
    .uf-field label {
        font-size: 12px;
        font-weight: 600;
        color: #374151;
        margin-bottom: 5px;
        display: block;
    }
    .uf-field label.required:after { content: " *"; color: #ef4444; font-weight: 700; }
    .uf-field .form-control,
    .uf-field .form-select {
        border-radius: 9px;
        border: 1px solid #d1d5db;
        padding: 9px 12px;
        font-size: 13px;
        color: #111827;
        background-color: #fff;
    }
    .uf-field .form-control:focus,
    .uf-field .form-select:focus {
        border-color: #6366f1;
        box-shadow: 0 0 0 3px rgba(99,102,241,.15);
        outline: 0;
    }
    .uf-actions {
        display: flex;
        align-items: center;
        justify-content: flex-end;
        gap: 10px;
        padding-top: 18px;
        margin-top: 20px;
        border-top: 1px solid #eef0f6;
    }
    .uf-btn {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 9px 22px;
        border-radius: 9px;
        font-size: 13px;
        font-weight: 600;
        border: 0;
        cursor: pointer;
        transition: transform .1s ease, box-shadow .15s ease;
        text-decoration: none;
    }
    .uf-btn:hover { transform: translateY(-1px); }
    .uf-btn-primary { background: linear-gradient(135deg, #16a34a, #15803d); color: #fff; box-shadow: 0 4px 10px rgba(22,163,74,.25); }
    .uf-btn-primary:hover { color: #fff; box-shadow: 0 6px 14px rgba(22,163,74,.35); }
</style>
@endsection

@section('content')
<div class="mt-3">
    <div class="row justify-content-center">
        <div class="col-lg-8 col-12">
            <div class="card border-0 shadow-sm modulo-form-card">

                {{-- ═══ CABEÇALHO ═══ --}}
                <div class="card-header modulo-header-gradient py-3 px-4">
                    <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                        <div>
                            <h4 class="mb-1 modulo-title d-flex align-items-center gap-2">
                                <i class="ri-vip-diamond-line"></i>
                                Atribuir Plano
                            </h4>
                            <p class="text-muted mb-0 modulo-subtitle fs-13">
                                Selecione e configure o plano para a empresa: <strong>{{ $item->nome }}</strong>
                            </p>
                        </div>
                        <div>
                            <a href="{{ route('home') }}" class="dash-btn dash-btn-light">
                                <i class="ri-arrow-left-line"></i> Voltar
                            </a>
                        </div>
                    </div>
                </div>

                {{-- ═══ CORPO DO FORMULÁRIO ═══ --}}
                <div class="card-body p-4">
                    {!!Form::open()->fill($item)
                    ->put()
                    ->route('contador-empresa.set-plano', [$item->id])
                    !!}

                    <div class="row g-3">
                        <div class="col-md-8 uf-field">
                            <label class="required">Plano de Assinatura</label>
                            <select required id="plano" name="plano_id" class="form-select select2">
                                <option value="">Selecione o plano</option>
                                @foreach($planos as $p)
                                <option value="{{ $p->id }}" data-valor="{{ $p->valor }}">{{ $p->nome }} — R$ {{ __moeda($p->valor)}}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-4 uf-field">
                            {!!Form::tel('valor', 'Valor Mensal (R$)')
                            ->required()
                            ->attrs(['class' => 'form-control moeda', 'id' => 'inp-valor'])
                            !!}
                        </div>

                        <div class="col-12">
                            <div class="uf-actions">
                                <button type="submit" class="uf-btn uf-btn-primary px-5" id="btn-store">
                                    <i class="ri-save-line"></i> Salvar Plano
                                </button>
                            </div>
                        </div>
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
    $(document).on("change", "#plano", function () {
        if($(this).val()){
            let valor = $('#plano option:selected').data('valor')
            $('#inp-valor').val(convertFloatToMoeda(valor))
        }else{
            $('#inp-valor').val(convertFloatToMoeda(0))
        }
    });
</script>
@endsection
