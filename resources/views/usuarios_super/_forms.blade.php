@section('css')
<style type="text/css">
    /* Formulários de Filtro e Cadastro */
    .form-control, .form-select, select, input[type="text"], input[type="password"] {
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

    /* Input Group para Senha */
    .input-group {
        position: relative;
        display: flex;
        align-items: stretch;
        width: 100%;
    }

    .input-group-text {
        display: flex;
        align-items: center;
        padding: 0 16px !important;
        font-size: 16px !important;
        color: #64748b !important;
        background-color: #f8fafc !important;
        border: 1px solid #e2e8f0 !important;
        border-left: none !important;
        border-top-right-radius: 10px !important;
        border-bottom-right-radius: 10px !important;
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .input-group-text:hover {
        background-color: #f1f5f9 !important;
        color: #4f46e5 !important;
    }

    .input-group .form-control {
        border-top-right-radius: 0 !important;
        border-bottom-right-radius: 0 !important;
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

    hr {
        border-color: rgba(0, 0, 0, 0.06) !important;
        opacity: 1 !important;
        margin: 24px 0 !important;
    }
</style>
@endsection

<div class="row g-3">
    <div class="col-md-4">
        {!!Form::text('name', 'Nome')
        ->attrs(['class' => 'form-control'])
        ->required()
        !!}
    </div>
    <div class="col-md-4">
        {!!Form::text('email', 'Email')
        ->attrs(['class' => 'form-control'])
        ->required()
        !!}
    </div>
    <div class="col-md-2">
        {!!Form::select('admin', 'Administrador?', [0 => 'Não', 1 => 'Sim'])
        ->attrs(['class' => 'form-select'])
        ->required()
        !!}
    </div>
    <div class="col-md-4">
        {!!Form::select('empresa', 'Empresa Associada', ['' => 'Selecione'] + $empresas->pluck('info', 'id')->all())
        ->attrs(['class' => 'form-select select2'])
        ->value(isset($item) && $item->empresa ? $item->empresa->empresa_id : null)
        !!}
    </div>

    <div class="col-md-3">
        <label for="senha">Senha</label>
        <div class="input-group" id="show_hide_password">
            <input type="password" class="form-control" id="senha" name="password" autocomplete="off"
                @if(isset($senhaCookie)) value="{{$senhaCookie}}" @endif>
            <a class="input-group-text"><i class='ri-eye-line'></i></a>
        </div>
    </div>

    @if(sizeof($roles) > 0)
        <div class="col-md-3">
            {!!Form::select('role_id', 'Controle de acesso', ['' => 'Selecione'] + $roles->pluck('description', 'id')->all())
            ->attrs(['class' => 'form-select select2'])
            ->value(isset($item) && $item->roles && count($item->roles) > 0 ? $item->roles->first()->id : null)
            ->required()
            !!}
        </div>
    @endif

    <hr class="mt-4">
    <div class="col-12 d-flex justify-content-end">
        <button type="submit" class="btn btn-success px-5" id="btn-store">
            <i class="ri-save-line"></i> Salvar Usuário
        </button>
    </div>
</div>

@section('js')
    <script>
        $(document).ready(function () {
            $("#show_hide_password a").on('click', function (event) {
                event.preventDefault();
                if ($('#show_hide_password input').attr("type") == "text") {
                    $('#show_hide_password input').attr('type', 'password');
                    $('#show_hide_password i').addClass("ri-eye-line");
                    $('#show_hide_password i').removeClass("ri-eye-off-line");
                } else if ($('#show_hide_password input').attr("type") == "password") {
                    $('#show_hide_password input').attr('type', 'text');
                    $('#show_hide_password i').removeClass("ri-eye-line");
                    $('#show_hide_password i').addClass("ri-eye-off-line");
                }
            });
        });
    </script>
@endsection