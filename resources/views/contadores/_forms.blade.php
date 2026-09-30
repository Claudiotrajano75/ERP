@section('css')
<style>
    /* ─── Seções do formulário ─── */
    .uf-section-title { display: flex; align-items: center; gap: 10px; font-size: 14px; font-weight: 700; color: #1f2937; border-bottom: 1px solid #eef0f6; padding-bottom: 10px; margin-bottom: 16px; }
    .uf-section-title .uf-ico { width: 30px; height: 30px; border-radius: 9px; background: #eef0ff; color: #4f46e5; display: inline-flex; align-items: center; justify-content: center; font-size: 15px; }
    .uf-section-title small { font-weight: 500; color: #94a3b8; font-size: 12px; margin-left: auto; }

    /* ─── Campos ─── */
    .uf-field label, .uf-field .form-label { display: block; font-size: 11px; font-weight: 700; letter-spacing: .04em; text-transform: uppercase; color: #64748b; margin-bottom: 6px; }
    .uf-field .form-label i, .uf-field label i { color: #a8a8c0; font-size: 12px; }
    .uf-field .form-control, .uf-field .form-select, .uf-field .input-group .form-control { height: 40px; border-radius: 10px; border: 1px solid #dcdce9; font-size: 13.5px; color: #1f2937; background: #fcfdfe; transition: all .15s ease; }
    .uf-field .form-control:focus, .uf-field .form-select:focus { border-color: #4f46e5; box-shadow: 0 0 0 3px rgba(79,70,229,.12); background: #fff; }
    .uf-field .input-group-text { border-radius: 0 10px 10px 0; background: #fff; border-color: #dcdce9; color: #64748b; cursor: pointer; }
    .uf-required::after { content: ' *'; color: #dc2626; font-weight: 700; }

    /* ─── Rodapé de ações ─── */
    .uf-actions { display: flex; align-items: center; justify-content: flex-end; gap: 10px; border-top: 1px solid #eef0f6; padding-top: 16px; margin-top: 20px; }
</style>
@endsection

<div class="row g-4">

    {{-- SEÇÃO 1: INFORMAÇÕES BÁSICAS --}}
    <div class="col-12 uf-section">
        <div class="uf-section-title">
            <span class="uf-ico"><i class="ri-information-line"></i></span>
            1. Informações Básicas do Contador
            <small>Dados de identificação</small>
        </div>
        <div class="row g-3">
            <div class="col-md-3 col-12 uf-field">
                {!!Form::tel('cpf_cnpj', 'CPF / CNPJ')
                ->attrs(['class' => 'form-control cpf_cnpj'])
                ->required()
                !!}
            </div>
            <div class="col-md-4 col-12 uf-field">
                {!!Form::text('nome', 'Razão Social / Nome Completo')
                ->attrs(['class' => 'form-control'])
                ->required()
                !!}
            </div>
            <div class="col-md-3 col-12 uf-field">
                {!!Form::text('nome_fantasia', 'Nome Fantasia')
                ->attrs(['class' => 'form-control'])
                ->required()
                !!}
            </div>
            <div class="col-md-2 col-12 uf-field">
                {!!Form::tel('ie', 'IE / RG')
                ->attrs(['data-mask' => '0000000000'])
                ->required()
                !!}
            </div>
        </div>
    </div>

    {{-- SEÇÃO 2: ENDEREÇO E CONTATO --}}
    <div class="col-12 uf-section">
        <div class="uf-section-title">
            <span class="uf-ico"><i class="ri-map-pin-line"></i></span>
            2. Endereço e Contato
            <small>Localização e meios de contato</small>
        </div>
        <div class="row g-3">
            <div class="col-md-2 col-6 uf-field">
                {!!Form::tel('cep', 'CEP')
                ->attrs(['class' => 'form-control cep'])
                ->required()
                !!}
            </div>
            <div class="col-md-4 col-12 uf-field">
                {!!Form::text('rua', 'Rua')
                ->attrs(['class' => 'form-control'])
                ->required()
                !!}
            </div>
            <div class="col-md-2 col-6 uf-field">
                {!!Form::tel('numero', 'Número')
                ->attrs(['class' => 'form-control'])
                ->required()
                !!}
            </div>
            <div class="col-md-4 col-12 uf-field">
                {!!Form::text('bairro', 'Bairro')
                ->attrs(['class' => 'form-control'])
                ->required()
                !!}
            </div>
        </div>
        <div class="row g-3 mt-1">
            <div class="col-md-3 col-12 uf-field">
                {!!Form::text('complemento', 'Complemento')
                ->attrs(['class' => 'form-control'])
                !!}
            </div>
            <div class="col-md-3 col-12 uf-field">
                @isset($item)
                {!!Form::select('cidade_id', 'Cidade')
                ->attrs(['class' => 'form-control select2'])
                ->options($item != null ? [$item->cidade_id => $item->cidade->info] : [])
                ->required()
                !!}
                @else
                {!!Form::select('cidade_id', 'Cidade')
                ->attrs(['class' => 'form-control select2'])
                ->required()
                !!}
                @endisset
            </div>
            <div class="col-md-3 col-12 uf-field">
                {!!Form::text('email_empresa', 'Email de Contato')
                ->attrs(['class' => 'form-control'])
                ->value(isset($item) ? $item->email : '')
                !!}
            </div>
            <div class="col-md-3 col-12 uf-field">
                {!!Form::tel('celular', 'Telefone / Celular')
                ->attrs(['class' => 'form-control fone'])
                ->required()
                !!}
            </div>
        </div>
        <div class="row g-3 mt-1">
            <div class="col-md-3 col-12 uf-field">
                {!!Form::select('status', 'Status do Cadastro', [1 => 'Ativo', 0 => 'Desativado'])
                ->attrs(['class' => 'form-select'])
                !!}
            </div>
        </div>
    </div>

    {{-- SEÇÃO 3: USUÁRIO DE ACESSO (só no create e se for master) --}}
    @if(__isMaster())
    @if(!isset($item))
    <div class="col-12 uf-section">
        <div class="uf-section-title">
            <span class="uf-ico"><i class="ri-user-settings-line"></i></span>
            3. Usuário de Acesso do Contador
            <small>Credenciais de login</small>
        </div>
        <div class="row g-3">
            <div class="col-md-3 col-12 uf-field">
                {!!Form::text('usuario', 'Nome do Usuário')
                ->attrs(['class' => 'form-control'])
                ->required()
                !!}
            </div>
            <div class="col-md-3 col-12 uf-field">
                {!!Form::text('email', 'Email de Acesso')
                ->attrs(['class' => 'form-control'])
                ->required()
                !!}
            </div>
            <div class="col-md-3 col-12 uf-field">
                <label class="required form-label uf-required"><i class="ri-lock-line"></i> Senha</label>
                <div class="input-group" id="show_hide_password">
                    <input required type="password" class="form-control" name="password" autocomplete="off"
                        @if(isset($senhaCookie)) value="{{ $senhaCookie }}" @endif>
                    <button type="button" class="btn btn-outline-secondary input-group-text"><i class="ri-eye-line"></i></button>
                </div>
            </div>
            <div class="col-md-3 col-12 uf-field">
                <label class="required form-label uf-required"><i class="ri-lock-2-line"></i> Confirmar Senha</label>
                <div class="input-group" id="show_hide_password_r">
                    <input required type="password" class="form-control" name="password_confirmation" autocomplete="off">
                    <button type="button" class="btn btn-outline-secondary input-group-text"><i class="ri-eye-line"></i></button>
                </div>
            </div>
        </div>
    </div>

    {{-- SEÇÃO 4: PARÂMETROS COMERCIAIS --}}
    <div class="col-12 uf-section">
        <div class="uf-section-title">
            <span class="uf-ico"><i class="ri-settings-4-line"></i></span>
            4. Parâmetros Comerciais e Limites
            <small>Configurações de comissão</small>
        </div>
        <div class="row g-3">
            <div class="col-md-4 col-12 uf-field">
                {!!Form::text('percentual_comissao', 'Percentual de Comissão (%)')
                ->attrs(['class' => 'form-control comissao'])
                ->required()
                !!}
            </div>
            <div class="col-md-4 col-12 uf-field">
                {!!Form::text('limite_cadastro_empresas', 'Limite de Cadastro de Empresas')
                ->attrs(['class' => 'form-control', 'data-mask' => '0000'])
                ->required()
                !!}
            </div>
        </div>
    </div>
    @endif
    @endif

    {{-- RODAPÉ COM BOTÕES --}}
    <div class="col-12">
        <div class="uf-actions">
            <a href="{{ route('contadores.index') }}" class="dash-btn dash-btn-light px-4"><i class="ri-close-line"></i> Cancelar</a>
            <button type="submit" class="dash-btn dash-btn-primary px-4" id="btn-store">
                <i class="ri-save-line"></i> {{ isset($formType) && $formType === 'edit' ? 'Salvar Alterações' : 'Salvar' }}
            </button>
        </div>
    </div>

</div>

@section('js')
<script>
    $(document).ready(function() {

        // Mostrar/ocultar senha principal
        $("#show_hide_password button").on('click', function(e) {
            e.preventDefault();
            let input = $('#show_hide_password input'), icon = $('#show_hide_password i');
            if (input.attr("type") === "text") {
                input.attr('type', 'password');
                icon.addClass("ri-eye-line").removeClass("ri-eye-off-line");
            } else {
                input.attr('type', 'text');
                icon.removeClass("ri-eye-line").addClass("ri-eye-off-line");
            }
        });

        // Mostrar/ocultar confirmação de senha
        $("#show_hide_password_r button").on('click', function(e) {
            e.preventDefault();
            let input = $('#show_hide_password_r input'), icon = $('#show_hide_password_r i');
            if (input.attr("type") === "text") {
                input.attr('type', 'password');
                icon.addClass("ri-eye-line").removeClass("ri-eye-off-line");
            } else {
                input.attr('type', 'text');
                icon.removeClass("ri-eye-line").addClass("ri-eye-off-line");
            }
        });
    });

    // Autocomplete CNPJ
    $(document).on("blur", "#inp-cpf_cnpj", function () {
        let cpf_cnpj = $(this).val().replace(/[^0-9]/g, '');
        if (cpf_cnpj.length == 14) {
            $.get('https://publica.cnpj.ws/cnpj/' + cpf_cnpj)
            .done((data) => {
                if (data != null) {
                    let ie = '';
                    if (data.estabelecimento.inscricoes_estaduais.length > 0) {
                        ie = data.estabelecimento.inscricoes_estaduais[0].inscricao_estadual;
                    }
                    $('#inp-ie').val(ie);
                    $('#inp-nome').val(data.razao_social);
                    $('#inp-nome_fantasia').val(data.estabelecimento.nome_fantasia);
                    $("#inp-rua").val(data.estabelecimento.tipo_logradouro + " " + data.estabelecimento.logradouro);
                    $('#inp-numero').val(data.estabelecimento.numero);
                    $("#inp-bairro").val(data.estabelecimento.bairro);
                    let cep = data.estabelecimento.cep.replace(/[^\d]+/g, '');
                    $('#inp-cep').val(cep.substring(0, 5) + '-' + cep.substring(5, 9));
                    $('#inp-email').val(data.estabelecimento.email);
                    $('#inp-celular').val(data.estabelecimento.telefone1);
                    findCidade(data.estabelecimento.cidade.ibge_id);
                }
            })
            .fail((err) => {
                console.log(err);
            });
        }
    });

    function findCidade(codigo_ibge) {
        $.get(path_url + "api/cidadePorCodigoIbge/" + codigo_ibge)
        .done((res) => {
            var newOption = new Option(res.info, res.id, false, false);
            $('#inp-cidade_id').append(newOption).trigger('change');
        })
        .fail((err) => {
            console.log(err);
        });
    }
</script>
@endsection
