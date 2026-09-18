{{-- ═══ SEÇÃO: DADOS PRINCIPAIS ═══ --}}
<div class="card card-secao-fiscal">
    <div class="card-header">
        <h5><i class="ri-building-4-line text-primary"></i> Dados Principais</h5>
    </div>
    <div class="card-body">
        <div class="row g-3">
            <div class="col-md-3 col-6">
                {!!Form::tel('cnpj', 'CNPJ')
                ->attrs(['class' => 'form-control cnpj', 'id' => 'inp-cnpj', 'placeholder' => '00.000.000/0000-00'])
                !!}
            </div>
            <div class="col-md-3 col-6">
                {!!Form::tel('cpf', 'CPF')
                ->attrs(['class' => 'form-control cpf', 'id' => 'inp-cpf', 'placeholder' => '000.000.000-00'])
                !!}
            </div>
            <div class="col-md-6 col-12">
                {!!Form::text('razao_social', 'Razão Social')
                ->attrs(['class' => 'form-control', 'id' => 'inp-razao_social'])
                ->required()
                !!}
            </div>
            <div class="col-md-6 col-12">
                {!!Form::text('nome_fantasia', 'Nome Fantasia')
                ->attrs(['class' => 'form-control', 'id' => 'inp-nome_fantasia'])
                ->required()
                !!}
            </div>
            <div class="col-md-3 col-6">
                {!!Form::tel('ie', 'Inscrição Estadual')
                ->attrs(['class' => 'form-control', 'id' => 'inp-ie'])
                !!}
            </div>
            <div class="col-md-3 col-6">
                {!!Form::tel('crc', 'CRC')
                ->attrs(['class' => 'form-control', 'id' => 'inp-crc'])
                !!}
            </div>
            <div class="col-md-6 col-12">
                {!!Form::text('email', 'E-mail do Escritório')
                ->attrs(['class' => 'form-control', 'id' => 'inp-email', 'placeholder' => 'contabil@escritorio.com.br'])
                ->required()
                ->type('email')
                !!}
            </div>
            <div class="col-md-6 col-12">
                {!!Form::select('envio_xml_automatico', 'Enviar XML Automaticamente ao Transmitir?', [0 => 'Não', 1 => 'Sim (Recomendado)'])
                ->attrs(['class' => 'form-select'])
                !!}
                <div class="tooltip-info">
                    <i class="ri-information-line"></i>
                    <span>Se ativado, cada NFe/NFCe transmitida será enviada por email para o contador.</span>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- ═══ SEÇÃO: LOCALIZAÇÃO E CONTATO ═══ --}}
<div class="card card-secao-fiscal">
    <div class="card-header">
        <h5><i class="ri-map-pin-line text-primary"></i> Localização e Contato</h5>
    </div>
    <div class="card-body">
        <div class="row g-3">
            <div class="col-md-2 col-6">
                {!!Form::tel('cep', 'CEP')
                ->attrs(['class' => 'form-control cep', 'id' => 'inp-cep', 'placeholder' => '00000-000'])
                ->required()
                !!}
            </div>
            <div class="col-md-5 col-12">
                {!!Form::text('rua', 'Logradouro / Rua')
                ->attrs(['class' => 'form-control', 'id' => 'inp-rua'])
                ->required()
                !!}
            </div>
            <div class="col-md-2 col-6">
                {!!Form::text('numero', 'Número')
                ->attrs(['class' => 'form-control', 'id' => 'inp-numero'])
                ->required()
                !!}
            </div>
            <div class="col-md-3 col-12">
                {!!Form::text('bairro', 'Bairro')
                ->attrs(['class' => 'form-control', 'id' => 'inp-bairro'])
                ->required()
                !!}
            </div>
            <div class="col-md-6 col-12">
                {!!Form::select('cidade_id', 'Cidade')
                ->required()
                ->attrs(['class' => 'form-select select2', 'id' => 'inp-cidade_id'])
                ->options($item != null && $item->cidade ? [$item->cidade->id => $item->cidade->info] : [])
                !!}
            </div>
            <div class="col-md-6 col-12">
                {!!Form::tel('telefone', 'Telefone / Celular')
                ->attrs(['class' => 'form-control fone', 'id' => 'inp-telefone'])
                ->required()
                !!}
            </div>
        </div>
    </div>
</div>

{{-- ═══ BOTÃO SALVAR ═══ --}}
<div class="col-12 d-flex justify-content-end pt-2">
    <button type="submit" class="dash-btn dash-btn-primary px-5" id="btn-store">
        <i class="ri-save-line me-1"></i> Salvar Configurações
    </button>
</div>

@section('js')
<script type="text/javascript">
    $(document).on("blur", "#inp-cnpj", function () {
        let cnpj = $(this).val().replace(/[^0-9]/g,'');
        if(cnpj.length == 14){
            $.get('https://publica.cnpj.ws/cnpj/' + cnpj)
            .done((data) => {
                if (data != null) {
                    let ie = '';
                    if (data.estabelecimento.inscricoes_estaduais && data.estabelecimento.inscricoes_estaduais.length > 0) {
                        ie = data.estabelecimento.inscricoes_estaduais[0].inscricao_estadual;
                    }
                    $('#inp-ie').val(ie);
                    if(ie != ""){
                        $('#inp-contribuinte').val(1).change();
                    }
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

    $(document).on("blur", ".cep", function () {
        let cep = $(".cep").val().replace(/[^0-9]/g,'');
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
