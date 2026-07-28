<div class="row g-3 text-dark">
    
    <!-- Seção 1: Identificação Básica -->
    <div class="col-12">
        <h5 class="text-dark border-bottom pb-2 mb-3"><i class="ri-user-line text-primary me-2 align-middle fs-18"></i> 1. Identificação Cadastral</h5>
        <div class="row g-3">
            <div class="col-md-3 col-12">
                {!!Form::text('cpf_cnpj', 'CPF/CNPJ')->attrs(['class' => 'form-control cpf_cnpj', 'id' => 'inp-cpf_cnpj'])->required()!!}
                <div class="form-text text-muted fs-11 mt-1">Insira CNPJ para consultar dados automaticamente.</div>
            </div>

            <div class="col-md-5 col-12">
                {!!Form::text('razao_social', 'Razão Social / Nome Completo')->attrs(['class' => 'form-control', 'id' => 'inp-razao_social'])->required()!!}
            </div>

            <div class="col-md-4 col-12">
                {!!Form::text('nome_fantasia', 'Nome Fantasia')->attrs(['class' => 'form-control', 'id' => 'inp-nome_fantasia'])!!}
            </div>

            <div class="col-md-3 col-6">
                {!!Form::text('ie', 'Inscrição Estadual (IE)')->attrs(['class' => 'form-control ie', 'id' => 'inp-ie'])!!}
            </div>

            <div class="col-md-3 col-6">
                {!!Form::tel('telefone', 'Telefone de Contato')->attrs(['class' => 'form-control fone', 'id' => 'inp-telefone'])->required()!!}
            </div>

            <div class="col-md-2 col-6">
                {!!Form::select('contribuinte', 'Contribuinte ICMS', [0 => 'Não', 1 => 'Sim'])->attrs(['class' => 'form-select', 'id' => 'inp-contribuinte'])!!}
            </div>

            <div class="col-md-2 col-6">
                {!!Form::select('consumidor_final', 'Consumidor Final', [0 => 'Não', 1 => 'Sim'])->attrs(['class' => 'form-select', 'id' => 'inp-consumidor_final'])!!}
            </div>

            <div class="col-md-2 col-6">
                {!!Form::select('status', 'Status (Ativo)', [1 => 'Sim', 0 => 'Não'])->attrs(['class' => 'form-select', 'id' => 'inp-status'])!!}
            </div>

            <div class="col-md-6 col-12">
                {!!Form::text('email', 'E-mail')->attrs(['class' => 'form-control', 'id' => 'inp-email'])->type('email')!!}
            </div>
        </div>
    </div>

    <!-- Seção 2: Endereço & Localização -->
    <div class="col-12 mt-4">
        <h5 class="text-dark border-bottom pb-2 mb-3"><i class="ri-map-pin-line text-primary me-2 align-middle fs-18"></i> 2. Endereço & Localização</h5>
        <div class="row g-3">
            <div class="col-md-4 col-12">
                @isset($item)
                {!!Form::select('cidade_id', 'Cidade')
                ->attrs(['class' => 'select2 form-select', 'id' => 'inp-cidade_id'])->options(($item != null && $item->cidade) ? [$item->cidade_id => $item->cidade->info] : [])
                ->required()!!}
                @else
                {!!Form::select('cidade_id', 'Cidade')
                ->attrs(['class' => 'select2 form-select', 'id' => 'inp-cidade_id'])
                ->required()!!}
                @endisset
            </div>

            <div class="col-md-5 col-12">
                {!!Form::text('rua', 'Logradouro / Rua')->attrs(['class' => 'form-control', 'id' => 'inp-rua'])->required()!!}
            </div>

            <div class="col-md-3 col-12">
                {!!Form::text('numero', 'Número')->attrs(['class' => 'form-control', 'id' => 'inp-numero'])->required()!!}
            </div>

            <div class="col-md-3 col-6">
                {!!Form::text('cep', 'CEP')->attrs(['class' => 'form-control cep', 'id' => 'inp-cep'])->required()!!}
            </div>

            <div class="col-md-3 col-6">
                {!!Form::text('bairro', 'Bairro')->attrs(['class' => 'form-control', 'id' => 'inp-bairro'])->required()!!}
            </div>

            <div class="col-md-6 col-12">
                {!!Form::text('complemento', 'Complemento / Referência')->attrs(['class' => 'form-control', 'id' => 'inp-complemento'])!!}
            </div>
        </div>
    </div>

    <!-- Seção 3: Financeiro, Crédito & Cashback -->
    <div class="col-12 mt-4">
        <h5 class="text-dark border-bottom pb-2 mb-3"><i class="ri-wallet-line text-primary me-2 align-middle fs-18"></i> 3. Parâmetros de Crédito & Comercial</h5>
        <div class="row g-3">
            <div class="col-md-3 col-6">
                {!!Form::tel('valor_cashback', 'Saldo Cashback (R$)')->attrs(['class' => 'form-control moeda', 'id' => 'inp-valor_cashback'])
                ->value(isset($item) ? __moeda($item->valor_cashback) : '')!!}
            </div>

            <div class="col-md-3 col-6">
                {!!Form::text('valor_credito', 'Saldo Crédito (R$)')->attrs(['class' => 'form-control moeda', 'id' => 'inp-valor_credito'])
                ->value(isset($item) ? __moeda($item->valor_credito) : '')!!}
            </div>

            <div class="col-md-3 col-6">
                {!!Form::text('limite_credito', 'Limite de Crédito (R$)')->attrs(['class' => 'form-control moeda', 'id' => 'inp-limite_credito'])
                ->value(isset($item) ? __moeda($item->limite_credito) : '')!!}
                <div class="form-text text-muted fs-11 mt-1">Limite máximo de crédito para compras a prazo.</div>
            </div>

            <div class="col-md-3 col-6">
                {!!Form::select('lista_preco_id', 'Tabela / Lista de Preço', ['' => 'Padrão'] + $listasPreco->pluck('nome', 'id')->all())
                ->attrs(['class' => 'form-select', 'id' => 'inp-lista_preco_id'])!!}
            </div>
        </div>
    </div>

    <!-- Rodapé de Envio -->
    <div class="col-12 mt-4">
        <hr class="text-muted opacity-25">
        <div class="d-flex align-items-center justify-content-end gap-2">
            <a href="{{ route('clientes.index') }}" class="btn btn-outline-secondary px-4">
                <i class="ri-close-line align-middle me-1"></i> Cancelar
            </a>
            <button type="submit" class="btn {{ $formType === 'edit' ? 'btn-primary' : 'btn-success' }} px-4" id="btn-store">
                <i class="ri-save-line align-middle me-1"></i> {{ $formType === 'edit' ? 'Salvar Alterações' : 'Salvar Cliente' }}
            </button>
        </div>
    </div>

</div>

@section('js')
<script type="text/javascript">
    $(document).on("blur", "#inp-cpf_cnpj", function () {
        let cpf_cnpj = $(this).val().replace(/[^0-9]/g,'')

        if(cpf_cnpj.length == 14){
            $.get('https://publica.cnpj.ws/cnpj/' + cpf_cnpj)
            .done((data) => {
                if (data != null) {
                    let ie = ''
                    if (data.estabelecimento.inscricoes_estaduais.length > 0) {
                        ie = data.estabelecimento.inscricoes_estaduais[0].inscricao_estadual
                    }
                    
                    $('#inp-ie').val(ie)
                    if(ie != ""){
                        $('#inp-contribuinte').val(1).change()
                    }
                    $('#inp-razao_social').val(data.razao_social)
                    $('#inp-nome_fantasia').val(data.estabelecimento.nome_fantasia)
                    $("#inp-rua").val(data.estabelecimento.tipo_logradouro + " " + data.estabelecimento.logradouro)
                    $('#inp-numero').val(data.estabelecimento.numero)
                    $("#inp-bairro").val(data.estabelecimento.bairro);
                    let cep = data.estabelecimento.cep.replace(/[^\d]+/g, '');
                    $('#inp-cep').val(cep.substring(0, 5) + '-' + cep.substring(5, 9))
                    $('#inp-email').val(data.estabelecimento.email)
                    $('#inp-telefone').val(data.estabelecimento.telefone1)

                    findCidade(data.estabelecimento.cidade.ibge_id)
                }
            })
            .fail((err) => {
                console.log(err)
            })
        }
    })

    function findCidade(codigo_ibge){
        $('#inp-cidade_id').html('')
        $.get(path_url + "api/cidadePorCodigoIbge/" + codigo_ibge)
        .done((res) => {
            var newOption = new Option(res.info, res.id, false, false);
            $('#inp-cidade_id').append(newOption).trigger('change');
        })
        .fail((err) => {
            console.log(err)
        })
    }

    $('#inp-ie').blur(() => {
        if($('#inp-ie').val() != ""){
            $('#inp-contribuinte').val(1).change()
        }
    })
</script>
@endsection
