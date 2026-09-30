@section('css')
<style>
    /* ─── Seções do formulário ─── */
    .uf-section-title {
        display: flex;
        align-items: center;
        gap: 10px;
        font-size: 14px;
        font-weight: 700;
        color: #1f2937;
        border-bottom: 1px solid #eef0f6;
        padding-bottom: 10px;
        margin-top: 10px;
        margin-bottom: 16px;
    }
    .uf-section-title i {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 28px;
        height: 28px;
        border-radius: 8px;
        background: #eef2ff;
        color: #4338ca;
        font-size: 15px;
    }

    /* ─── Campos do Form ─── */
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
        transition: border-color .15s ease, box-shadow .15s ease;
        background-color: #fff;
    }
    .uf-field .form-control:focus,
    .uf-field .form-select:focus {
        border-color: #6366f1;
        box-shadow: 0 0 0 3px rgba(99,102,241,.15);
        outline: 0;
    }

    /* ─── Container de Módulos Inclusos ─── */
    .modules-card {
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 14px;
        padding: 20px;
        margin-top: 10px;
        margin-bottom: 16px;
    }
    .modules-card-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 12px;
        border-bottom: 1px solid #e2e8f0;
        padding-bottom: 12px;
        margin-bottom: 16px;
    }
    .modules-card-title {
        font-size: 14px;
        font-weight: 700;
        color: #1e293b;
        display: flex;
        align-items: center;
        gap: 8px;
        margin: 0;
    }
    .modules-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
        gap: 10px;
    }
    .module-item {
        background: #fff;
        border: 1px solid #e2e8f0;
        border-radius: 10px;
        padding: 10px 14px;
        display: flex;
        align-items: center;
        gap: 10px;
        transition: all .15s ease;
        cursor: pointer;
    }
    .module-item:hover {
        border-color: #c7d2fe;
        background: #fdfefe;
    }
    .module-item .form-check-input {
        width: 18px;
        height: 18px;
        border-radius: 5px;
        border: 1.5px solid #94a3b8;
        cursor: pointer;
        margin-top: 0;
    }
    .module-item .form-check-input:checked {
        background-color: #4f46e5;
        border-color: #4f46e5;
    }
    .module-item label {
        font-size: 13px;
        font-weight: 500;
        color: #334155;
        cursor: pointer;
        user-select: none;
        margin: 0;
        line-height: 1.2;
    }

    /* ─── Upload de Imagem ─── */
    .image-upload-card {
        border: 2px dashed #cbd5e1;
        border-radius: 14px;
        background: #fafbfc;
        padding: 20px;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        text-align: center;
        position: relative;
    }
    .image-upload-card .preview {
        position: relative;
        width: 120px;
        height: 120px;
        border-radius: 14px;
        overflow: hidden;
        border: 1px solid #e2e8f0;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.05);
        background: #fff;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .image-upload-card .preview img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    #btn-remove-imagem {
        position: absolute;
        top: 6px;
        right: 6px;
        z-index: 10;
        width: 24px;
        height: 24px;
        border-radius: 50%;
        padding: 0;
        background-color: #ef4444;
        border: none;
        color: #ffffff;
        font-size: 14px;
        font-weight: bold;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
    }
    #btn-remove-imagem:hover { background-color: #dc2626; }
    .image-upload-card label.btn-upload {
        margin-top: 12px;
        padding: 8px 18px;
        background-color: #fff;
        border: 1px solid #d1d5db;
        border-radius: 9px;
        cursor: pointer;
        font-size: 12.5px;
        font-weight: 600;
        color: #374151;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        transition: all 0.15s ease;
    }
    .image-upload-card label.btn-upload:hover {
        background-color: #f3f4f6;
        border-color: #9ca3af;
    }
    .image-upload-card input[type="file"] { display: none; }

    /* ─── Ações Rodapé ─── */
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

<div class="row g-3">
    {{-- ═══ DADOS GERAIS DO PLANO ═══ --}}
    <div class="col-12">
        <div class="uf-section-title">
            <i class="ri-information-line"></i> Dados Gerais do Plano
        </div>
    </div>

    <div class="col-md-3 uf-field">
        {!!Form::text('nome', 'Nome do Plano')
        ->attrs(['class' => 'form-control', 'id' => 'inp-nome', 'placeholder' => 'Ex: Plano Premium'])
        ->required()
        !!}
    </div>
    <div class="col-md-5 uf-field">
        {!!Form::text('descricao', 'Descrição detalhada')
        ->attrs(['class' => 'form-control', 'id' => 'inp-descricao', 'placeholder' => 'Breve resumo dos recursos inclusos'])
        ->required()
        !!}
    </div>
    <div class="col-md-2 uf-field">
        {!!Form::tel('valor', 'Valor Mensal (R$)')
        ->required()
        ->attrs(['class' => 'form-control moeda', 'id' => 'inp-valor'])
        ->value(isset($item) ? __moeda($item->valor) : '')
        !!}
    </div>
    <div class="col-md-2 uf-field">
        {!!Form::tel('valor_implantacao', 'Implantação (R$)')
        ->attrs(['class' => 'form-control moeda', 'id' => 'inp-valor_implantacao'])
        ->value(isset($item) ? __moeda($item->valor_implantacao) : '')
        !!}
    </div>

    {{-- ═══ LIMITES E RECURSOS ═══ --}}
    <div class="col-12">
        <div class="uf-section-title">
            <i class="ri-shield-flash-line"></i> Limites e Recursos Operacionais
        </div>
    </div>

    <div class="col-md-2 uf-field">
        {!!Form::tel('maximo_nfes', 'NFe / mês')
        ->attrs(['class' => 'form-control', 'id' => 'inp-maximo_nfes', 'placeholder' => '0'])
        ->required()
        !!}
    </div>
    <div class="col-md-2 uf-field">
        {!!Form::tel('maximo_nfces', 'NFCe / mês')
        ->attrs(['class' => 'form-control', 'id' => 'inp-maximo_nfces', 'placeholder' => '0'])
        ->required()
        !!}
    </div>
    <div class="col-md-2 uf-field">
        {!!Form::tel('maximo_ctes', 'CTe / mês')
        ->attrs(['class' => 'form-control', 'id' => 'inp-maximo_ctes', 'placeholder' => '0'])
        ->required()
        !!}
    </div>
    <div class="col-md-2 uf-field">
        {!!Form::tel('maximo_mdfes', 'MDFe / mês')
        ->attrs(['class' => 'form-control', 'id' => 'inp-maximo_mdfes', 'placeholder' => '0'])
        ->required()
        !!}
    </div>
    <div class="col-md-2 uf-field">
        {!!Form::tel('maximo_usuarios', 'Qtd. Usuários')
        ->attrs(['class' => 'form-control', 'id' => 'inp-maximo_usuarios', 'placeholder' => '1'])
        ->required()
        !!}
    </div>
    <div class="col-md-2 uf-field">
        {!!Form::tel('maximo_locais', 'Qtd. Locais')
        ->attrs(['class' => 'form-control', 'id' => 'inp-maximo_locais', 'placeholder' => '1'])
        ->required()
        !!}
    </div>

    {{-- ═══ REGRAS E VISIBILIDADE ═══ --}}
    <div class="col-12">
        <div class="uf-section-title">
            <i class="ri-settings-5-line"></i> Regras e Visibilidade
        </div>
    </div>

    <div class="col-md-2 uf-field">
        {!!Form::select('status', 'Plano Ativo?', ['1' => 'Sim', '0' => 'Não'])
        ->attrs(['class' => 'form-select', 'id' => 'inp-status'])
        ->required()
        !!}
    </div>
    <div class="col-md-3 uf-field">
        {!!Form::select('visivel_clientes', 'Visível para Clientes?', ['1' => 'Sim', '0' => 'Não'])
        ->attrs(['class' => 'form-select', 'id' => 'inp-visivel_clientes'])
        ->required()
        !!}
    </div>
    <div class="col-md-3 uf-field">
        {!!Form::select('visivel_contadores', 'Visível para Contadores?', ['0' => 'Não', '1' => 'Sim'])
        ->attrs(['class' => 'form-select', 'id' => 'inp-visivel_contadores'])
        ->required()
        !!}
    </div>
    <div class="col-md-2 uf-field">
        {!!Form::tel('intervalo_dias', 'Validade (dias)')
        ->attrs(['class' => 'form-control', 'id' => 'inp-intervalo_dias', 'placeholder' => '30'])
        ->required()
        !!}
    </div>
    <div class="col-md-2 uf-field">
        {!!Form::select('auto_cadastro', 'Auto Cadastro?', ['0' => 'Não', '1' => 'Sim'])
        ->attrs(['class' => 'form-select', 'id' => 'inp-auto_cadastro'])
        ->required()
        !!}
    </div>
    <div class="col-md-2 uf-field">
        {!!Form::select('fiscal', 'Emissão Fiscal?', ['1' => 'Sim', '0' => 'Não'])
        ->attrs(['class' => 'form-select', 'id' => 'inp-fiscal'])
        ->required()
        !!}
    </div>
    <div class="col-md-3 uf-field">
        {!!Form::select('segmento_id', 'Segmento Comercial', ['' => 'Selecione'] + $segmentos->pluck('nome', 'id')->all())
        ->attrs(['class' => 'form-select', 'id' => 'inp-segmento_id'])
        !!}
    </div>

    {{-- ═══ MÓDULOS INCLUSOS ═══ --}}
    <div class="col-12">
        <div class="modules-card">
            <div class="modules-card-header">
                <h5 class="modules-card-title">
                    <i class="ri-apps-2-line text-primary"></i> Módulos do Sistema Inclusos no Plano
                </h5>
                
                @if(!isset($item))
                <div class="form-check d-inline-flex align-items-center mb-0">
                    <input type="checkbox" class="form-check-input check_todos" id="check_todos_modulos">
                    <label class="form-check-label ms-2 fw-semibold fs-13 text-dark" for="check_todos_modulos">Marcar todos os módulos</label>
                </div>
                @endif
            </div>

            <div class="modules-grid">
                @foreach($modulos as $key => $m)
                <label class="module-item mb-0" for="modulo_{{$key}}">
                    <input name="modulos[]" value="{{$m}}" type="checkbox" class="form-check-input check-module" id="modulo_{{$key}}" @isset($item) @if(sizeof($item->modulos) > 0 && in_array($m, $item->modulos)) checked="true" @endif @endif>
                    <span>{{$m}}</span>
                </label>
                @endforeach
            </div>
        </div>
    </div>

    {{-- ═══ IDENTIDADE VISUAL DO PLANO ═══ --}}
    <div class="col-12">
        <div class="uf-section-title">
            <i class="ri-image-line"></i> Identidade Visual do Plano
        </div>
    </div>

    <div class="col-md-4">
        <div class="image-upload-card">
            <div class="preview">
                <button type="button" id="btn-remove-imagem" class="btn" title="Remover Imagem">×</button>
                @isset($item)
                <img id="file-ip-1-preview" src="{{ $item->img }}" alt="Imagem do Plano">
                @else
                <img id="file-ip-1-preview" src="/imgs/no-image.png" alt="Imagem do Plano">
                @endif
            </div>
            <label for="file-ip-1" class="btn-upload"><i class="ri-upload-2-line"></i> Escolher Imagem</label>
            <input type="file" id="file-ip-1" name="image" accept="image/*" onchange="showPreview(event);">
        </div>
        @if($errors->has('image'))
        <div class="text-danger mt-2 fs-12">
            {{ $errors->first('image') }}
        </div>
        @endif
    </div>

    {{-- ═══ BOTÃO SALVAR ═══ --}}
    <div class="col-12">
        <div class="uf-actions">
            <button type="submit" class="uf-btn uf-btn-primary px-5" id="btn-store">
                <i class="ri-save-line"></i> Salvar Plano
            </button>
        </div>
    </div>
</div>

@section('js')
<script type="text/javascript">
    $(function(){
        @if(!isset($item))
        setTimeout(() => {
            checkTodos()
        }, 10)
        @endif
    })

    $('body').on('click', '.check_todos', function () {
        setTimeout(() => {
            checkTodos()
        }, 10)
    })

    function checkTodos(){
        if($('.check_todos').is(':checked')){
            $('.check-module').prop('checked', true)
        }else{
            $('.check-module').prop('checked', false)
        }
    }
</script>
@endsection
