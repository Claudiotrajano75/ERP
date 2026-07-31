@section('css')
<style type="text/css">
    /* Títulos de Seção */
    .section-title {
        font-size: 16px;
        font-weight: 700;
        color: #4f46e5 !important;
        margin-top: 24px;
        margin-bottom: 16px;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .section-title i {
        font-size: 18px;
    }

    /* Formulários de Filtro e Cadastro */
    .form-control, .form-select, select, input[type="text"], input[type="tel"] {
        border: 1px solid #e2e8f0 !important;
        border-radius: 10px !important;
        padding: 10px 14px !important;
        font-size: 13px !important;
        color: #334155 !important;
        transition: all 0.2s ease !important;
        box-shadow: none !important;
        background-color: #ffffff !important;
    }

    .form-control:focus, .form-select:focus, select:focus {
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

    /* Container de Módulos Inclusos */
    .modules-container {
        background-color: #f8fafc !important;
        border: 1px solid rgba(0, 0, 0, 0.05) !important;
        border-radius: 12px !important;
        padding: 24px !important;
        margin-bottom: 24px !important;
    }

    .modules-container h5 {
        font-size: 14px !important;
        font-weight: 700 !important;
        color: #1e293b !important;
        margin-bottom: 16px !important;
        display: flex;
        align-items: center;
        gap: 8px;
        border-bottom: 1px solid rgba(0, 0, 0, 0.04);
        padding-bottom: 10px;
    }

    .modules-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
        gap: 12px;
        margin-top: 14px;
    }

    .form-check-input {
        width: 20px !important;
        height: 20px !important;
        border-radius: 5px !important;
        border: 1px solid #cbd5e1 !important;
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .form-check-input:checked {
        background-color: #4f46e5 !important;
        border-color: #4f46e5 !important;
    }

    .form-check-label {
        font-size: 13px !important;
        font-weight: 500 !important;
        color: #475569 !important;
        cursor: pointer;
        user-select: none;
    }

    /* Imagem de Destaque */
    .image-upload-card {
        border: 1px dashed #cbd5e1 !important;
        border-radius: 12px !important;
        background: #ffffff !important;
        padding: 20px !important;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        text-align: center;
        position: relative;
    }

    .image-upload-card label {
        margin-top: 12px !important;
        padding: 8px 16px !important;
        background-color: #f1f5f9 !important;
        border: 1px solid #cbd5e1 !important;
        border-radius: 8px !important;
        cursor: pointer;
        font-size: 12px !important;
        font-weight: 600 !important;
        color: #475569 !important;
        transition: all 0.2s ease;
    }

    .image-upload-card label:hover {
        background-color: #e2e8f0 !important;
    }

    .image-upload-card input[type="file"] {
        display: none;
    }

    .image-upload-card .preview {
        position: relative;
        width: 120px;
        height: 120px;
        border-radius: 12px;
        overflow: hidden;
        border: 1px solid rgba(0, 0, 0, 0.08);
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.04);
        background: #f8fafc;
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
        top: 4px;
        right: 4px;
        z-index: 10;
        width: 24px;
        height: 24px;
        border-radius: 50% !important;
        padding: 0 !important;
        background-color: #ef4444 !important;
        border: none !important;
        color: #ffffff !important;
        font-size: 12px !important;
        font-weight: bold !important;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.15) !important;
    }

    #btn-remove-imagem:hover {
        background-color: #dc2626 !important;
    }

    hr {
        border-color: rgba(0, 0, 0, 0.06) !important;
        opacity: 1 !important;
        margin: 24px 0 !important;
    }
</style>
@endsection

<div class="row g-3">
    <!-- SEÇÃO: DADOS GERAIS DO PLANO -->
    <div class="col-12 mt-2">
        <h5 class="section-title"><i class="ri-information-line"></i> Dados Gerais do Plano</h5>
    </div>

    <div class="col-md-3">
        {!!Form::text('nome', 'Nome do Plano')
        ->required()
        !!}
    </div>
    <div class="col-md-5">
        {!!Form::text('descricao', 'Descrição detalhada')
        ->required()
        !!}
    </div>
    <div class="col-md-2">
        {!!Form::tel('valor', 'Valor Mensal (R$)')
        ->required()
        ->attrs(['class' => 'moeda'])
        ->value(isset($item) ? __moeda($item->valor) : '')
        !!}
    </div>
    <div class="col-md-2">
        {!!Form::tel('valor_implantacao', 'Implantação (R$)')
        ->attrs(['class' => 'moeda'])
        ->value(isset($item) ? __moeda($item->valor_implantacao) : '')
        !!}
    </div>

    <!-- SEÇÃO: LIMITES DO PLANO -->
    <div class="col-12 mt-4">
        <h5 class="section-title"><i class="ri-shield-flash-line"></i> Limites e Recursos Operacionais</h5>
    </div>

    <div class="col-md-2">
        {!!Form::tel('maximo_nfes', 'NFe/mês')
        ->required()
        !!}
    </div>
    <div class="col-md-2">
        {!!Form::tel('maximo_nfces', 'NFCe/mês')
        ->required()
        !!}
    </div>
    <div class="col-md-2">
        {!!Form::tel('maximo_ctes', 'CTe/mês')
        ->required()
        !!}
    </div>
    <div class="col-md-2">
        {!!Form::tel('maximo_mdfes', 'MDFe/mês')
        ->required()
        !!}
    </div>
    <div class="col-md-2">
        {!!Form::tel('maximo_usuarios', 'Qtd. Usuários')
        ->required()
        !!}
    </div>
    <div class="col-md-2">
        {!!Form::tel('maximo_locais', 'Qtd. Locais')
        ->required()
        !!}
    </div>

    <!-- SEÇÃO: REGRAS E VISIBILIDADE -->
    <div class="col-12 mt-4">
        <h5 class="section-title"><i class="ri-settings-5-line"></i> Regras e Visibilidade</h5>
    </div>

    <div class="col-md-2">
        {!!Form::select('status', 'Plano Ativo?', ['1' => 'Sim', '0' => 'Não'])
        ->required()
        ->attrs(['class' => 'form-select'])
        !!}
    </div>
    <div class="col-md-3">
        {!!Form::select('visivel_clientes', 'Visível para Clientes?', ['1' => 'Sim', '0' => 'Não'])
        ->required()
        ->attrs(['class' => 'form-select'])
        !!}
    </div>
    <div class="col-md-3">
        {!!Form::select('visivel_contadores', 'Visível para Contadores?', ['0' => 'Não', '1' => 'Sim'])
        ->required()
        ->attrs(['class' => 'form-select'])
        !!}
    </div>
    <div class="col-md-2">
        {!!Form::tel('intervalo_dias', 'Validade (dias)')
        ->required()
        !!}
    </div>
    <div class="col-md-2">
        {!!Form::select('auto_cadastro', 'Auto Cadastro?', ['0' => 'Não', '1' => 'Sim'])
        ->required()
        ->attrs(['class' => 'form-select'])
        !!}
    </div>
    <div class="col-md-2">
        {!!Form::select('fiscal', 'Emissão Fiscal?', ['1' => 'Sim', '0' => 'Não'])
        ->required()
        ->attrs(['class' => 'form-select'])
        !!}
    </div>
    <div class="col-md-3">
        {!!Form::select('segmento_id', 'Segmento Comercial', ['' => 'Selecione'] + $segmentos->pluck('nome', 'id')->all())
        ->attrs(['class' => 'form-select'])
        !!}
    </div>

    <!-- SEÇÃO: MÓDULOS INCLUSOS -->
    <div class="col-12 mt-4">
        <div class="modules-container">
            <h5><i class="ri-apps-2-line text-primary"></i> Módulos do Sistema Inclusos no Plano</h5>
            
            @if(!isset($item))
            <div class="d-flex align-items-center mb-3">
                <input type="checkbox" class="form-check-input check_todos" id="check_todos_modulos">
                <label class="form-check-label ms-2" for="check_todos_modulos">Marcar todos os módulos</label>
            </div>
            @endif

            <div class="modules-grid">
                @foreach($modulos as $key => $m)
                <div class="d-flex align-items-center">
                    <input name="modulos[]" value="{{$m}}" type="checkbox" class="form-check-input check-module" id="modulo_{{$key}}" @isset($item) @if(sizeof($item->modulos) > 0 && in_array($m, $item->modulos)) checked="true" @endif @endif>
                    <label class="form-check-label ms-2" for="modulo_{{$key}}">{{$m}}</label>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- SEÇÃO: IMAGEM DE DESTAQUE -->
    <div class="col-12 mt-2">
        <h5 class="section-title"><i class="ri-image-line"></i> Identidade Visual do Plano</h5>
    </div>

    <div class="col-md-4">
        <div class="image-upload-card">
            <div class="preview">
                <button type="button" id="btn-remove-imagem" class="btn">×</button>
                @isset($item)
                <img id="file-ip-1-preview" src="{{ $item->img }}">
                @else
                <img id="file-ip-1-preview" src="/imgs/no-image.png">
                @endif
            </div>
            <label for="file-ip-1"><i class="ri-upload-2-line"></i> Escolher Imagem</label>
            <input type="file" id="file-ip-1" name="image" accept="image/*" onchange="showPreview(event);">
        </div>
        @if($errors->has('image'))
        <div class="text-danger mt-2">
            {{ $errors->first('image') }}
        </div>
        @endif
    </div>

    <hr class="mt-4">
    <div class="col-12 d-flex justify-content-end">
        <button type="submit" class="btn btn-success px-5" id="btn-store">
            <i class="ri-save-line"></i> Salvar Plano
        </button>
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
            $('.check-module').prop('checked', 1)
        }else{
            $('.check-module').prop('checked', 0)
        }
    }
</script>
@endsection
