<div class="row g-3 text-dark">
    <!-- Bloco 1: Dados da Categoria -->
    <div class="col-12">
        <h5 class="text-dark border-bottom pb-2 mb-3"><i
                class="ri-information-line text-primary me-2 align-middle fs-18"></i> 1. Informações Básicas</h5>
        <div class="row g-3">
            @if(__isInternacionalizar(Auth::user()->empresa))
                    <div class="col-md-4 col-12">
                        {!!Form::text('nome', 'Nome')->required()->attrs(['class' => 'form-control'])!!}
                    </div>
                    <div class="col-md-4 col-12">
                        {!!Form::text('nome_en', 'Nome (Inglês)')->attrs(['class' => 'form-control'])!!}
                    </div>
                    <div class="col-md-4 col-12">
                        {!!Form::text('nome_es', 'Nome (Espanhol)')->attrs(['class' => 'form-control'])!!}
                    </div>
                    <div class="col-md-6 col-12">
                        {!!Form::select('categoria_id', 'Vincular a uma Categoria Pai (opcional)')
                ->attrs(['class' => 'form-select'])
                ->options(isset($item) && $item->categoria ? [$item->categoria->id => $item->categoria->nome] : [])
                                    !!}
                        <div class="form-text text-muted fs-11 mt-1">Preencha apenas se esta for uma subcategoria de outro item.
                        </div>
                    </div>
            @else
                    <div class="col-md-6 col-12">
                        {!!Form::text('nome', 'Nome da Categoria')->required()->attrs(['class' => 'form-control'])!!}
                    </div>
                    <div class="col-md-6 col-12">
                        {!!Form::select('categoria_id', 'Vincular a uma Categoria Pai (opcional)')
                ->attrs(['class' => 'form-select'])
                ->options(isset($item) && $item->categoria ? [$item->categoria->id => $item->categoria->nome] : [])
                                    !!}
                        <div class="form-text text-muted fs-11 mt-1">Preencha apenas se esta for uma subcategoria de outro item.
                        </div>
                    </div>
            @endif
        </div>
    </div>

    <!-- Bloco 2: Canais de Exibição / Ativação de Recursos -->
    <div class="col-12 mt-4">
        <h5 class="text-dark border-bottom pb-2 mb-3"><i
                class="ri-global-line text-primary me-2 align-middle fs-18"></i> 2. Canais de Venda & Integrações</h5>
        <div class="row g-3">
            @if(__isActivePlan(Auth::user()->empresa, 'Cardapio'))
                    <div class="col-md-3 col-6">
                        {!!Form::select('cardapio', 'Cardápio', [0 => 'Não', 1 => 'Sim'])
                ->attrs(['class' => 'form-select', 'data-bs-toggle' => 'tooltip', 'title' => 'Marcar como SIM se for usar esta categoria no cardápio digital'])
                                !!}
                    </div>
            @endif

            @if(__isActivePlan(Auth::user()->empresa, 'Delivery'))
                    <div class="col-md-3 col-6">
                        @if(isset($delivery) && $delivery == 1)
                                {!!Form::select('delivery', 'Delivery', [0 => 'Não', 1 => 'Sim'])
                            ->attrs(['class' => 'form-select', 'data-bs-toggle' => 'tooltip', 'title' => 'Marcar como SIM se for usar esta categoria no Delivery/Marketplace'])
                            ->value(1)
                                                !!}
                        @else
                                {!!Form::select('delivery', 'Delivery', [0 => 'Não', 1 => 'Sim'])
                            ->attrs(['class' => 'form-select', 'data-bs-toggle' => 'tooltip', 'title' => 'Marcar como SIM se for usar esta categoria no Delivery/Marketplace'])
                                                !!}
                        @endif
                    </div>

                    <div class="col-md-3 col-6">
                        {!!Form::select('tipo_pizza', 'Tipo Pizza', [0 => 'Não', 1 => 'Sim'])
                ->attrs(['class' => 'form-select'])
                                !!}
                    </div>
            @endif

            @if(__isActivePlan(Auth::user()->empresa, 'Ecommerce'))
                    <div class="col-md-3 col-6">
                        {!!Form::select('ecommerce', 'Ecommerce', [0 => 'Não', 1 => 'Sim'])
                ->attrs(['class' => 'form-select', 'data-bs-toggle' => 'tooltip', 'title' => 'Marcar como SIM se for usar esta categoria no Ecommerce'])
                                !!}
                    </div>
            @endif

            @if(__isActivePlan(Auth::user()->empresa, 'Reservas'))
                    <div class="col-md-3 col-6">
                        {!!Form::select('reserva', 'Reserva', [0 => 'Não', 1 => 'Sim'])
                ->attrs(['class' => 'form-select', 'data-bs-toggle' => 'tooltip', 'title' => 'Marcar como SIM se for usar esta categoria no Módulo de reserva'])
                                !!}
                    </div>
            @endif
        </div>
    </div>

    <!-- Rodapé de Ações -->
    <div class="col-12 mt-4">
        <hr class="text-muted opacity-25">
        <div class="d-flex align-items-center justify-content-end gap-2">
            <a href="{{ route('categoria-produtos.index') }}" class="btn btn-light px-4">Cancelar</a>
            <button type="submit" class="btn btn-success px-4" id="btn-store">
                <i class="ri-save-line align-middle me-1"></i> Salvar Categoria
            </button>
        </div>
    </div>
</div>

@section('js')
    <script type="text/javascript">
        $(function () {
            // Inicializar tooltips do Bootstrap 5
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });

            // Configuração do Select2 para busca AJAX de categoria pai
            $("#inp-categoria_id").select2({
                minimumInputLength: 2,
                language: "pt-BR",
                placeholder: "Digite para buscar a categoria",
                width: "100%",
                ajax: {
                    cache: true,
                    url: path_url + "api/categorias-produto-subcategoria",
                    dataType: "json",
                    data: function (params) {
                        return {
                            pesquisa: params.term,
                            empresa_id: $('#empresa_id').val()
                        };
                    },
                    processResults: function (response) {
                        var results = [];
                        $.each(response, function (i, v) {
                            results.push({
                                id: v.id,
                                text: v.nome
                            });
                        });
                        return {
                            results: results,
                        };
                    },
                },
            });
        });
    </script>
@endsection