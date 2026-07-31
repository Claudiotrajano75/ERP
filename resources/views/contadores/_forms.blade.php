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
    .form-control, .form-select, select, input[type="text"], input[type="tel"], input[type="email"], input[type="password"] {
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

    /* Customização dos Toggles de Senha e Inputs em Grupo */
    .input-group-text {
        background-color: #f8fafc !important;
        border: 1px solid #e2e8f0 !important;
        border-radius: 0 10px 10px 0 !important;
        color: #64748b !important;
        cursor: pointer;
        padding: 10px 14px !important;
    }

    .input-group .form-control {
        border-radius: 10px 0 0 10px !important;
    }

    hr {
        border-color: rgba(0, 0, 0, 0.06) !important;
        opacity: 1 !important;
        margin: 24px 0 !important;
    }
</style>
@endsection

<div class="row g-3">
    <!-- SEÇÃO: DADOS DO CONTADOR -->
    <div class="col-12 mt-2">
        <h5 class="section-title"><i class="ri-information-line"></i> Informações Básicas do Contador</h5>
    </div>

    <div class="col-md-3">
        {!!Form::tel('cpf_cnpj', 'CPF/CNPJ')
        ->attrs(['class' => 'form-control cpf_cnpj'])
        ->required()
        !!}
    </div>
    
    <div class="col-md-4">
        {!!Form::text('nome', 'Razão Social / Nome Completo')
        ->attrs(['class' => 'form-control'])
        ->required()
        !!}
    </div>
    <div class="col-md-3">
        {!!Form::text('nome_fantasia', 'Nome Fantasia')
        ->attrs(['class' => 'form-control'])
        ->required()
        !!}
    </div>
    
    <div class="col-md-2">
        {!!Form::tel('ie', 'Inscrição Estadual (IE) / RG')
        ->attrs(['data-mask' => '0000000000'])
        ->required()
        !!}
    </div>

    <!-- SEÇÃO: ENDEREÇO E CONTATO -->
    <div class="col-12 mt-4">
        <h5 class="section-title"><i class="ri-map-pin-line"></i> Endereço e Contato</h5>
    </div>

    <div class="col-md-2">
        {!!Form::tel('cep', 'CEP')
        ->attrs(['class' => 'cep'])
        ->required()
        !!}
    </div>
    <div class="col-md-4">
        {!!Form::text('rua', 'Rua')
        ->required()
        !!}
    </div>
    <div class="col-md-2">
        {!!Form::tel('numero', 'Número')
        ->required()
        !!}
    </div>
    <div class="col-md-4">
        {!!Form::text('bairro', 'Bairro')
        ->required()
        !!}
    </div>

    <div class="col-md-3">
        {!!Form::text('complemento', 'Complemento')
        !!}
    </div>
    <div class="col-md-3">
        @isset($item)
        {!!Form::select('cidade_id', 'Cidade')
        ->attrs(['class' => 'select2'])
        ->options($item != null ? [$item->cidade_id => $item->cidade->info] : [])
        ->required()
        !!}
        @else
        {!!Form::select('cidade_id', 'Cidade')
        ->attrs(['class' => 'select2'])
        ->required()
        !!}
        @endisset
    </div>
    <div class="col-md-3">
        {!!Form::text('email_empresa', 'Email de Contato')
        ->attrs(['class' => ''])
        ->value(isset($item) ? $item->email : '')
        !!}
    </div>
    <div class="col-md-3">
        {!!Form::tel('celular', 'Telefone / Celular')
        ->attrs(['class' => 'fone'])
        ->required()
        !!}
    </div>
    <div class="col-md-3">
        {!!Form::select('status', 'Status do Cadastro', [1 => 'Ativo', 0 => 'Desativado'])
        ->attrs(['class' => 'form-select'])
        !!}
    </div>

    <!-- SEÇÃO: DADOS DE USUÁRIO DE ACESSO -->
    @if(__isMaster())
    @if(!isset($item))
    <div class="col-12 mt-4">
        <h5 class="section-title"><i class="ri-user-settings-line"></i> Usuário de Acesso do Contador</h5>
    </div>

    <div class="col-md-3">
        {!!Form::text('usuario', 'Nome do Usuário')
        ->required()
        !!}
    </div>
    <div class="col-md-3">
        {!!Form::text('email', 'Email de Acesso')
        ->required()
        !!}
    </div>
    <div class="col-md-3">
        <label class="required form-label" for="">Senha</label>
        <div class="input-group" id="show_hide_password">
            <input required type="password" class="form-control" name="password" autocomplete="off" @if(isset($senhaCookie)) value="{{$senhaCookie}}" @endif>
            <a class="input-group-text"><i class='ri-eye-line'></i></a>
        </div>
    </div>
    <div class="col-md-3">
        <label class="required form-label" for="">Confirmar Senha</label>
        <div class="input-group" id="show_hide_password_r">
            <input required type="password" class="form-control" name="password_confirmation" autocomplete="off">
            <a class="input-group-text"><i class='ri-eye-line'></i></a>
        </div>
    </div>
    @endif

    <!-- SEÇÃO: DADOS ADICIONAIS DO CONTADOR -->
    <div class="col-12 mt-4">
        <h5 class="section-title"><i class="ri-settings-4-line"></i> Parâmetros Comerciais e Limites</h5>
    </div>

    <div class="col-md-4">
        {!!Form::text('percentual_comissao', 'Percentual de Comissão (%)')
        ->attrs(['class' => 'comissao'])
        ->required()
        !!}
    </div>
    <div class="col-md-4">
        {!!Form::text('limite_cadastro_empresas', 'Limite de Cadastro de Empresas')
        ->attrs(['data-mask' => '0000'])
        ->required()
        !!}
    </div>
    @endif

    <hr class="mt-4">
    <div class="col-12 d-flex justify-content-end">
        <button type="submit" class="btn btn-success px-5" id="btn-store">
            <i class="ri-save-line"></i> Salvar Cadastro
        </button>
    </div>
</div>

@section('js')
<script>

    $(document).ready(function() {
        $("#show_hide_password a").on('click', function(event) {
            event.preventDefault();
            if ($('#show_hide_password input').attr("type") == "text") {
                $('#show_hide_password input').attr('type', 'password');
                $('#show_hide_password i').addClass("bx-hide");
                $('#show_hide_password i').removeClass("bx-show");
            } else if ($('#show_hide_password input').attr("type") == "password") {
                $('#show_hide_password input').attr('type', 'text');
                $('#show_hide_password i').removeClass("bx-hide");
                $('#show_hide_password i').addClass("bx-show");
            }
        });

        $("#show_hide_password_r a").on('click', function(event) {
            event.preventDefault();
            if ($('#show_hide_password_r input').attr("type") == "text") {
                $('#show_hide_password_r input').attr('type', 'password');
                $('#show_hide_password_r i').addClass("bx-hide");
                $('#show_hide_password_r i').removeClass("bx-show");
            } else if ($('#show_hide_password_r input').attr("type") == "password") {
                $('#show_hide_password_r input').attr('type', 'text');
                $('#show_hide_password_r i').removeClass("bx-hide");
                $('#show_hide_password_r i').addClass("bx-show");
            }
        });
    });

    $('#btn_token').click(() => {

        let token = generate_token(25);
        swal({
            title: "Atenção", 
            text: "Esse token é o responsavel pela comunicação com a API, tenha atenção!!", 
            icon: "warning", 
            buttons: true,
            dangerMode: true
        }).then((confirmed) => {
            if (confirmed) {
                $('#api_token').val(token)
            }
        });
    })

    function generate_token(length) {
        var a = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890".split("");
        var b = [];
        for (var i = 0; i < length; i++) {
            var j = (Math.random() * (a.length - 1)).toFixed(0);
            b[i] = a[j];
        }
        return b.join("");
    }

    $(document).on("blur", "#inp-cpf_cnpj", function () {

        let cpf_cnpj = $(this).val().replace(/[^0-9]/g,'')

        if(cpf_cnpj.length == 14){
            $.get('https://publica.cnpj.ws/cnpj/' + cpf_cnpj)
            .done((data) => {
                if (data!= null) {
                    let ie = ''
                    if (data.estabelecimento.inscricoes_estaduais.length > 0) {
                        ie = data.estabelecimento.inscricoes_estaduais[0].inscricao_estadual
                    }
                    
                    $('#inp-ie').val(ie)
                    $('#inp-nome').val(data.razao_social)
                    $('#inp-nome_fantasia').val(data.estabelecimento.nome_fantasia)
                    $("#inp-rua").val(data.estabelecimento.tipo_logradouro + " " + data.estabelecimento.logradouro)
                    $('#inp-numero').val(data.estabelecimento.numero)
                    $("#inp-bairro").val(data.estabelecimento.bairro);
                    let cep = data.estabelecimento.cep.replace(/[^\d]+/g, '');
                    $('#inp-cep').val(cep.substring(0, 5) + '-' + cep.substring(5, 9))
                    $('#inp-email').val(data.estabelecimento.email)
                    $('#inp-celular').val(data.estabelecimento.telefone1)

                    findCidade(data.estabelecimento.cidade.ibge_id)

                }
            })
            .fail((err) => {
                console.log(err)
                // swal("Algo errado", err.responseJSON['detalhes'], "warning")
            })
        }
    })

    function findCidade(codigo_ibge){

        $.get(path_url + "api/cidadePorCodigoIbge/" + codigo_ibge)
        .done((res) => {
            var newOption = new Option(res.info, res.id, false, false);
            $('#inp-cidade_id').append(newOption).trigger('change');
        })
        .fail((err) => {
            console.log(err)
        })

    }

</script>
@endsection
