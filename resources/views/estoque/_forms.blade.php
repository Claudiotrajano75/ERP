<div class="row g-3 text-dark">
    
    <!-- Seção 1: Seleção de Produto -->
    <div class="col-12">
        <h5 class="text-dark border-bottom pb-2 mb-3"><i class="ri-information-line text-primary me-2 align-middle fs-18"></i> 1. Identificação do Item</h5>
        <div class="row g-3">
            <div class="col-md-6 col-12">
                {!!Form::select('produto_id', 'Produto')
                ->attrs(['class' => 'form-select select2'])->required()
                ->options(isset($item) ? [$item->produto->id => $item->produto->nome] : [])
                ->disabled(isset($item) ? true : false)
                !!}
                @if(isset($item))
                <input type="hidden" name="produto_id" value="{{ $item->produto->id }}">
                @endif
            </div>
        </div>
    </div>

    <!-- Seção 2: Quantidades e Locais de Estoque -->
    <div class="col-12 mt-4">
        <h5 class="text-dark border-bottom pb-2 mb-3"><i class="ri-map-pin-line text-primary me-2 align-middle fs-18"></i> 2. Detalhes de Localização & Quantidade</h5>
        
        @if(isset($item) && __countLocalAtivo() > 1)
        <!-- Caso Edição e Múltiplos Locais -->
        <div class="table-responsive rounded border">
            <table class="table table-centered table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Localização</th>
                        <th style="width: 250px;">Quantidade em Estoque</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($locais as $l)
                    <tr>
                        <td class="fw-semibold text-dark">
                            @if($l->local)
                            <input type="hidden" name="local_id[]" value="{{ $l->id }}">
                            {{ $l->local->descricao }}
                            @else
                            <input type="hidden" name="local_id[]" value="{{ $firstLocation->id }}">
                            {{ $firstLocation->nome }}
                            <input type="hidden" name="novo_estoque" value="1">
                            @endif
                        </td>
                        <td>
                            <input type="text" class="form-control quantidade text-end fw-bold text-primary" value="{{ number_format($l->quantidade, 3, '.', '') }}" required name="quantidade[]">
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @else
        <!-- Caso Criação ou Local Único -->
        <div class="row g-3">
            <div class="col-md-3 col-6">
                {!!Form::text('quantidade', 'Quantidade')
                ->attrs(['class' => 'form-control quantidade text-end fw-bold text-primary'])->required()
                ->value(isset($item) ? number_format($item->quantidade, 3, '.', '') : '')
                !!}
            </div>

            @if(__countLocalAtivo() > 1)
            <div class="col-md-4 col-6">
                <label class="form-label fw-semibold text-dark mb-1">Localização</label>
                <select required class="form-select select2" name="local_id" data-placeholder="Selecione a localização">
                    <option value="">Selecione</option>
                    @foreach(__getLocaisAtivoUsuario() as $local)
                    <option @isset($item) @if($item->local_id == $local->id) selected @endif @endif value="{{ $local->id }}">{{ $local->descricao }}</option>
                    @endforeach
                </select>
            </div>
            @endif
        </div>
        @endif
    </div>

    <!-- Inputs ocultos e botões de ação -->
    <input name="produto_variacao_id" id="produto_variacao_id" type="hidden">
    
    <div class="col-12 mt-4">
        <hr class="text-muted opacity-25">
        <div class="d-flex align-items-center justify-content-end gap-2">
            <a href="{{ route('estoque.index') }}" class="btn btn-light px-4">Cancelar</a>
            <button type="submit" class="btn btn-success px-4" id="btn-store">
                <i class="ri-save-line align-middle me-1"></i> Salvar Estoque
            </button>
        </div>
    </div>

</div>

@include('modals._variacao')

@section('js')
<script type="text/javascript">
    $(function(){
        $('#produto_variacao_id').val('');

        // Limpar valor inicial do campo de quantidade ao focar para facilitar digitação
        $('.quantidade').on('focus', function() {
            if($(this).val() == '0.000' || $(this).val() == '0') {
                $(this).val('');
            }
        });
    });

    $(document).on("change", "#inp-produto_id", function () {
        $('#produto_variacao_id').val('');

        let product_id = $(this).val();
        if(!product_id) return;

        $.get(path_url + "api/produtos/find", { produto_id: product_id })
        .done((e) => {
            let dataSelect = $(this).select2('data')[0];
            let codigo_variacao = dataSelect ? dataSelect.codigo_variacao : null;

            if(e.variacao_modelo_id && !codigo_variacao){
                buscarVariacoes(product_id);
            }

            if(codigo_variacao > 0){
                $('#produto_variacao_id').val(codigo_variacao);
            }
        })
        .fail((err) => {
            console.log(err);
        });
    });

    function buscarVariacoes(produto_id){
        $.get(path_url + "api/variacoes/find", { produto_id: produto_id })
        .done((res) => {
            $('#modal_variacao .modal-body').html(res);
            $('#modal_variacao').modal('show');
        })
        .fail((err) => {
            console.log(err);
            swal("Algo deu errado", "Erro ao buscar variações do produto", "error");
        });
    }

    function selecionarVariacao(id, descricao, valor){
        $('#produto_variacao_id').val(id);
        $('#modal_variacao').modal('hide');
    }
</script>
@endsection