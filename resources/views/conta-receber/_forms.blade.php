<div class="row g-3 text-dark">

    <!-- 1. Dados Básicos da Conta -->
    @if(__countLocalAtivo() > 1)
        <div class="col-md-4">
            <div class="form-group">
                <label class="form-label">Local</label>
                <select id="inp-local_id" required class="form-select select2 class-required" data-toggle="select2" name="local_id">
                    <option value="">Selecione</option>
                    @foreach(__getLocaisAtivoUsuario() as $local)
                    <option @isset($item) @if($item->local_id == $local->id) selected @endif @endif value="{{ $local->id }}">{{ $local->descricao }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="col-md-4">
            {!!Form::text('descricao', 'Descrição')
            ->attrs(['class' => 'form-control'])
            ->required()
            !!}
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <label class="form-label">Cliente</label>
                <div class="input-group flex-nowrap">
                    <select id="inp-fornecedor_id" name="cliente_id" class="form-select select2 cliente_id">
                        @if(isset($item) && $item->cliente)
                        <option value="{{ $item->cliente_id }}">{{ $item->cliente->razao_social }}</option>
                        @endif
                    </select>
                    @can('clientes_create')
                    <button class="btn btn-dark" data-bs-toggle="modal" data-bs-target="#modal_novo_cliente" type="button" title="Novo Cliente">
                        <i class="ri-add-circle-fill"></i>
                    </button>
                    @endcan
                </div>
            </div>
        </div>
    @else
        <input id="inp-local_id" type="hidden" value="{{ __getLocalAtivo() ? __getLocalAtivo()->id : '' }}" name="local_id">
        <div class="col-md-6">
            {!!Form::text('descricao', 'Descrição')
            ->attrs(['class' => 'form-control'])
            ->required()
            !!}
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label class="form-label">Cliente</label>
                <div class="input-group flex-nowrap">
                    <select id="inp-fornecedor_id" name="cliente_id" class="form-select select2 cliente_id">
                        @if(isset($item) && $item->cliente)
                        <option value="{{ $item->cliente_id }}">{{ $item->cliente->razao_social }}</option>
                        @endif
                    </select>
                    @can('clientes_create')
                    <button class="btn btn-dark" data-bs-toggle="modal" data-bs-target="#modal_novo_cliente" type="button" title="Novo Cliente">
                        <i class="ri-add-circle-fill"></i>
                    </button>
                    @endcan
                </div>
            </div>
        </div>
    @endif

    <!-- 2. Valores e Vencimento -->
    <div class="col-md-4">
        {!!Form::text('valor_integral', 'Valor Integral')
        ->attrs(['class' => 'moeda form-control'])
        ->value(isset($item) ? __moeda($item->valor_integral) : '')
        ->required()
        !!}
    </div>
    <div class="col-md-4">
        {!!Form::date('data_vencimento', 'Data Vencimento')
        ->attrs(['class' => 'form-control'])
        ->required()
        !!}
    </div>
    <div class="col-md-4">
        {!!Form::select('status', 'Conta Recebida', ['0' => 'Não (Pendente)', '1' => 'Sim (Recebida)'])
        ->attrs(['class' => 'form-select'])
        ->required()
        !!}
    </div>

    <!-- 3. Pagamento e Detalhes -->
    <div class="col-md-4">
        {!!Form::select('tipo_pagamento', 'Tipo Pagamento', App\Models\ContaReceber::tiposPagamento())
        ->attrs(['class' => 'form-select'])
        ->required()
        !!}
    </div>
    <div class="col-md-8">
        {!!Form::text('observacao', 'Observação')
        ->attrs(['class' => 'form-control'])
        !!}
    </div>

    <!-- 4. Upload de Arquivos -->
    <div class="col-md-12">
        <div class="form-group">
            {!! Form::file('file', 'Anexo de Comprovante ou Documento (PDF ou Imagem)')
            ->attrs(['accept' => '.pdf, image/*', 'class' => 'form-control']) 
            !!}
            <span class="text-danger fs-12 mt-1 d-block" id="filename"></span>
        </div>
    </div>

    @if(isset($item) && $item->arquivo != null)
    <div class="col-12 mt-1">
        <div class="alert alert-info border-0 bg-info-subtle text-info d-flex align-items-center mb-0 py-2">
            <i class="ri-file-info-line fs-18 me-2"></i>
            <div>
                Existe um arquivo anexo para esta conta: 
                <a href="{{ route('conta-receber.download-file', [$item->id]) }}" class="fw-bold text-info text-decoration-underline ms-1">
                    <i class="ri-file-download-line align-middle"></i> Baixar arquivo anexo
                </a>
            </div>
        </div>
    </div>
    @endif

    <!-- 5. Seção de Recorrência (Apenas no cadastro) -->
    @if(!isset($item))
    <div class="col-12 mt-3">
        <div class="card border bg-light-subtle shadow-none mb-0">
            <div class="card-body p-3">
                <h5 class="text-danger mb-2 d-flex align-items-center">
                    <i class="ri-refresh-line me-1 align-middle"></i>
                    Recorrência da Conta
                </h5>
                <p class="text-muted fs-13 mb-3">Preencha o campo abaixo apenas se houver recorrência mensal para este registro (as parcelas serão geradas automaticamente).</p>
                
                <div class="row g-3">
                    <div class="col-md-4">
                        {!!Form::tel('recorrencia', 'Gerar até (Data mm/aa)')
                        ->attrs(['data-mask' => '00/00', 'class' => 'form-control'])
                        ->placeholder('Exemplo: 12/26')
                        !!}
                    </div>
                </div>

                <div class="row tbl-recorrencia d-none mt-3">
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- 6. Botões de Confirmação -->
    <div class="col-12 mt-4">
        <hr class="text-muted opacity-25">
        <div class="d-flex align-items-center justify-content-end gap-2">
            <a href="{{ route('conta-receber.index') }}" class="btn btn-light px-4">Cancelar</a>
            <button type="submit" class="btn btn-success px-4" id="btn-store">
                <i class="ri-save-line align-middle me-1"></i> Salvar Conta
            </button>
        </div>
    </div>
</div>

@section('js')
<script src="/js/novo_cliente.js"></script>
<script>
    $('#inp-recorrencia').blur(() => {
        let data = $('#inp-recorrencia').val()
        if (data.length == 5) {
            let vencimento = $('#inp-data_vencimento').val()
            let valor = $('#inp-valor_integral').val()
            if (valor && vencimento) {
                let item = {
                    data: data, 
                    vencimento: vencimento, 
                    valor: valor
                }
                $.get(path_url + 'api/conta-receber/recorrencia', item)
                .done((html) => {
                    $('.tbl-recorrencia').html(html)
                    $('.tbl-recorrencia').removeClass('d-none')

                }).fail((err) => {
                    console.log(err)
                })
            } else {
                swal("Algo saiu errado", "Informe o valor e vencimento data conta base!", "warning")
            }
        } else {
            swal("Algo saiu errado", "Informe uma data válida mm/aa exemplo 12/25", "warning")
        }
    })
</script>
@endsection
