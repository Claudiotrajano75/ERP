<div class="row g-3 text-dark">
    
    <!-- Seção 1: Identificação Cadastral -->
    <div class="col-12">
        <div class="card card-secao-fiscal border p-3 rounded-3 mb-3 bg-white">
            <h5 class="text-dark border-bottom pb-2 mb-3 d-flex align-items-center gap-2 fs-14 fw-bold">
                <i class="ri-truck-line text-primary"></i> 1. Identificação da Transportadora
            </h5>
            <div class="row g-3">
                <div class="col-md-3 col-12">
                    {!!Form::tel('cpf_cnpj', 'CPF/CNPJ')->attrs(['class' => 'form-control cpf_cnpj', 'id' => 'inp-cpf_cnpj', 'placeholder' => '00.000.000/0000-00'])->required()!!}
                    <div class="form-text text-muted fs-11 mt-1">Insira o CNPJ para preencher os dados automaticamente.</div>
                </div>

                <div class="col-md-5 col-12">
                    {!!Form::text('razao_social', 'Razão Social')->attrs(['class' => 'form-control', 'id' => 'inp-razao_social'])->required()!!}
                </div>

                <div class="col-md-4 col-12">
                    {!!Form::text('nome_fantasia', 'Nome Fantasia')->attrs(['class' => 'form-control', 'id' => 'inp-nome_fantasia'])->required()!!}
                </div>

                <div class="col-md-3 col-6">
                    {!!Form::tel('ie', 'Inscrição Estadual (IE)')->attrs(['class' => 'form-control ie', 'id' => 'inp-ie', 'placeholder' => 'Isento ou número'])!!}
                </div>

                <div class="col-md-3 col-6">
                    {!!Form::tel('antt', 'Registro ANTT')->attrs(['class' => 'form-control', 'id' => 'inp-antt', 'placeholder' => 'Ex: RNTRC 12345678'])!!}
                </div>

                <div class="col-md-3 col-6">
                    {!!Form::tel('telefone', 'Telefone de Contato')->attrs(['class' => 'form-control fone', 'id' => 'inp-telefone', 'placeholder' => '(00) 00000-0000'])->required()!!}
                </div>

                <div class="col-md-3 col-6">
                    {!!Form::text('email', 'E-mail de Contato')->attrs(['class' => 'form-control', 'id' => 'inp-email', 'placeholder' => 'contato@transportadora.com.br'])->required()!!}
                </div>
            </div>
        </div>
    </div>

    <!-- Seção 2: Endereço & Localização -->
    <div class="col-12">
        <div class="card card-secao-fiscal border p-3 rounded-3 mb-3 bg-white">
            <h5 class="text-dark border-bottom pb-2 mb-3 d-flex align-items-center gap-2 fs-14 fw-bold">
                <i class="ri-map-pin-line text-primary"></i> 2. Endereço da Sede
            </h5>
            <div class="row g-3">
                <div class="col-md-3 col-6">
                    {!!Form::tel('cep', 'CEP')->attrs(['class' => 'form-control cep', 'id' => 'inp-cep', 'placeholder' => '00000-000'])->required()!!}
                </div>

                <div class="col-md-5 col-12">
                    {!!Form::tel('rua', 'Logradouro / Rua')->attrs(['class' => 'form-control', 'id' => 'inp-rua'])->required()!!}
                </div>

                <div class="col-md-2 col-6">
                    {!!Form::tel('numero', 'Número')->attrs(['class' => 'form-control', 'id' => 'inp-numero'])->required()!!}
                </div>

                <div class="col-md-2 col-12">
                    {!!Form::text('bairro', 'Bairro')->attrs(['class' => 'form-control', 'id' => 'inp-bairro'])->required()!!}
                </div>

                <div class="col-md-6 col-12">
                    @isset($item)
                    {!!Form::select('cidade_id', 'Cidade')
                    ->attrs(['class' => 'select2 form-select', 'id' => 'inp-cidade_id'])
                    ->options($item != null && $item->cidade_id ? [$item->cidade_id => $item->cidade->info] : [])
                    ->required()!!}
                    @else
                    {!!Form::select('cidade_id', 'Cidade')
                    ->attrs(['class' => 'select2 form-select', 'id' => 'inp-cidade_id'])
                    ->required()!!}
                    @endisset
                </div>

                <div class="col-md-6 col-12">
                    {!!Form::text('complemento', 'Complemento / Referência')->attrs(['class' => 'form-control', 'id' => 'inp-complemento', 'placeholder' => 'Ex: Galpão Logístico 3'])!!}
                </div>
            </div>
        </div>
    </div>

    <!-- Rodapé de Envio -->
    <div class="col-12 d-flex align-items-center justify-content-end gap-2 pt-2">
        <a href="{{ route('transportadoras.index') }}" class="dash-btn dash-btn-light">
            <i class="ri-close-line me-1"></i> Cancelar
        </a>
        <button type="submit" class="dash-btn dash-btn-primary px-5" id="btn-store">
            <i class="ri-save-line me-1"></i> {{ isset($formType) && $formType === 'edit' ? 'Salvar Alterações' : 'Salvar Transportadora' }}
        </button>
    </div>

</div>

@section('js')
<script type="text/javascript">
    $(document).on("blur", "#inp-cpf_cnpj", function () {
        let cpf_cnpj = $(this).val().replace(/[^0-9]/g,'');

        if(cpf_cnpj.length == 14){
            $.get('https://publica.cnpj.ws/cnpj/' + cpf_cnpj)
            .done((data) => {
                if (data != null) {
                    let ie = '';
                    if (data.estabelecimento.inscricoes_estaduais && data.estabelecimento.inscricoes_estaduais.length > 0) {
                        ie = data.estabelecimento.inscricoes_estaduais[0].inscricao_estadual;
                    }
                    
                    $('#inp-ie').val(ie);
                    if(data.razao_social) $('#inp-razao_social').val(data.razao_social);
                    if(data.estabelecimento && data.estabelecimento.nome_fantasia) {
                        $('#inp-nome_fantasia').val(data.estabelecimento.nome_fantasia);
                    } else if(data.razao_social) {
                        $('#inp-nome_fantasia').val(data.razao_social);
                    }
                    if(data.estabelecimento) {
                        if(data.estabelecimento.logradouro) {
                            $("#inp-rua").val((data.estabelecimento.tipo_logradouro ? data.estabelecimento.tipo_logradouro + " " : "") + data.estabelecimento.logradouro);
                        }
                        if(data.estabelecimento.numero) $('#inp-numero').val(data.estabelecimento.numero);
                        if(data.estabelecimento.bairro) $("#inp-bairro").val(data.estabelecimento.bairro);
                        if(data.estabelecimento.cep) {
                            let cep = data.estabelecimento.cep.replace(/[^\d]+/g, '');
                            $('#inp-cep').val(cep.substring(0, 5) + '-' + cep.substring(5, 9));
                        }
                        if(data.estabelecimento.email) $('#inp-email').val(data.estabelecimento.email);
                        if(data.estabelecimento.telefone1) $('#inp-telefone').val(data.estabelecimento.telefone1);
                        if(data.estabelecimento.cidade && data.estabelecimento.cidade.ibge_id) {
                            findCidade(data.estabelecimento.cidade.ibge_id);
                        }
                    }
                }
            })
            .fail((err) => {
                console.log(err);
            });
        }
    });

    $(document).on("blur", "#inp-cep", function () {
        let cep = $(this).val().replace(/[^0-9]/g,'');
        if(cep.length == 8){
            $.get('https://viacep.com.br/ws/'+cep+'/json')
            .done((res) => {
                if(!res.erro) {
                    findCidade(res.ibge);
                    $('#inp-rua').val(res.logradouro);
                    $('#inp-bairro').val(res.bairro);
                }
            })
            .fail((err) => {
                console.log(err);
            });
        }
    });

    function findCidade(codigo_ibge){
        $('#inp-cidade_id').html('');
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