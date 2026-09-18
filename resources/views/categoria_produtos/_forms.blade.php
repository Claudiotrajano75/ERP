<style>
    .uf-section-title { display: flex; align-items: center; gap: 10px; font-size: 14px; font-weight: 700; color: #1f2937; border-bottom: 1px solid #eef0f6; padding-bottom: 10px; margin-bottom: 16px; }
    .uf-section-title .uf-ico { width: 30px; height: 30px; border-radius: 9px; background: #eef0ff; color: #4f46e5; display: inline-flex; align-items: center; justify-content: center; font-size: 15px; }
    .uf-section-title small { font-weight: 500; color: #94a3b8; font-size: 12px; margin-left: auto; }

    .uf-field label, .uf-field .form-label { display: block; font-size: 11px; font-weight: 700; letter-spacing: .04em; text-transform: uppercase; color: #64748b; margin-bottom: 6px; }
    .uf-field .form-label i, .uf-field label i { color: #a8a8c0; font-size: 12px; }
    .uf-field .form-control, .uf-field .form-select { height: 40px; border-radius: 10px; border: 1px solid #dcdce9; font-size: 13.5px; color: #1f2937; background: #fcfdfe; transition: all .15s ease; }
    .uf-field .form-control:focus, .uf-field .form-select:focus { border-color: #4f46e5; box-shadow: 0 0 0 3px rgba(79,70,229,.12); background: #fff; }
    .uf-field .form-text { font-size: 11.5px; color: #94a3b8; }
    .uf-required::after { content: ' *'; color: #dc2626; font-weight: 700; }

    .uf-actions { display: flex; align-items: center; justify-content: flex-end; gap: 10px; border-top: 1px solid #eef0f6; padding-top: 16px; margin-top: 20px; }
</style>

<div class="row g-4">

    <!-- ═══ SEÇÃO 1: INFORMAÇÕES BÁSICAS ═══ -->
    <div class="col-12 uf-section">
        <div class="uf-section-title">
            <span class="uf-ico"><i class="ri-information-line"></i></span>
            1. Informações Básicas
            <small>Dados da categoria</small>
        </div>
        <div class="row g-3">
            @if(__isInternacionalizar(Auth::user()->empresa))
                <div class="col-md-4 col-12 uf-field">
                    {!!Form::text('nome', 'Nome')->required()->attrs(['class' => 'form-control'])!!}
                </div>
                <div class="col-md-4 col-12 uf-field">
                    {!!Form::text('nome_en', 'Nome (Inglês)')->attrs(['class' => 'form-control'])!!}
                </div>
                <div class="col-md-4 col-12 uf-field">
                    {!!Form::text('nome_es', 'Nome (Espanhol)')->attrs(['class' => 'form-control'])!!}
                </div>
            @else
                <div class="col-md-6 col-12 uf-field">
                    {!!Form::text('nome', 'Nome da Categoria')->required()->attrs(['class' => 'form-control'])!!}
                </div>
            @endif

            <div class="col-md-6 col-12 uf-field">
                {!!Form::select('categoria_id', 'Vincular a uma Categoria Pai (opcional)')
                ->attrs(['class' => 'form-select'])
                ->options(isset($item) && $item->categoria ? [$item->categoria->id => $item->categoria->nome] : [])
                !!}
                <div class="form-text mt-1">Preencha apenas se esta for uma subcategoria de outro item.</div>
            </div>
        </div>
    </div>

    <!-- ═══ SEÇÃO 2: CANAIS DE VENDA & INTEGRAÇÕES ═══ -->
    <div class="col-12 uf-section mt-2">
        <div class="uf-section-title">
            <span class="uf-ico"><i class="ri-global-line"></i></span>
            2. Canais de Venda &amp; Integrações
            <small>Onde esta categoria será exibida</small>
        </div>
        <div class="row g-3">
            @if(__isActivePlan(Auth::user()->empresa, 'Cardapio'))
            <div class="col-md-3 col-6 uf-field">
                {!!Form::select('cardapio', 'Cardápio', [0 => 'Não', 1 => 'Sim'])
                ->attrs(['class' => 'form-select', 'data-bs-toggle' => 'tooltip', 'title' => 'Usar esta categoria no cardápio digital'])
                !!}
            </div>
            @endif

            @if(__isActivePlan(Auth::user()->empresa, 'Delivery'))
            <div class="col-md-3 col-6 uf-field">
                @if(isset($delivery) && $delivery == 1)
                    {!!Form::select('delivery', 'Delivery', [0 => 'Não', 1 => 'Sim'])
                    ->attrs(['class' => 'form-select', 'data-bs-toggle' => 'tooltip', 'title' => 'Usar esta categoria no Delivery/Marketplace'])
                    ->value(1)
                    !!}
                @else
                    {!!Form::select('delivery', 'Delivery', [0 => 'Não', 1 => 'Sim'])
                    ->attrs(['class' => 'form-select', 'data-bs-toggle' => 'tooltip', 'title' => 'Usar esta categoria no Delivery/Marketplace'])
                    !!}
                @endif
            </div>
            <div class="col-md-3 col-6 uf-field">
                {!!Form::select('tipo_pizza', 'Tipo Pizza', [0 => 'Não', 1 => 'Sim'])->attrs(['class' => 'form-select'])!!}
            </div>
            @endif

            @if(__isActivePlan(Auth::user()->empresa, 'Ecommerce'))
            <div class="col-md-3 col-6 uf-field">
                {!!Form::select('ecommerce', 'Ecommerce', [0 => 'Não', 1 => 'Sim'])
                ->attrs(['class' => 'form-select', 'data-bs-toggle' => 'tooltip', 'title' => 'Usar esta categoria no Ecommerce'])
                !!}
            </div>
            @endif

            @if(__isActivePlan(Auth::user()->empresa, 'Reservas'))
            <div class="col-md-3 col-6 uf-field">
                {!!Form::select('reserva', 'Reserva', [0 => 'Não', 1 => 'Sim'])
                ->attrs(['class' => 'form-select', 'data-bs-toggle' => 'tooltip', 'title' => 'Usar esta categoria no Módulo de reservas'])
                !!}
            </div>
            @endif
        </div>
    </div>

    <!-- ═══ RODAPÉ COM BOTÕES ═══ -->
    <div class="col-12">
        <div class="uf-actions">
            <a href="{{ route('categoria-produtos.index') }}" class="dash-btn dash-btn-light px-4"><i class="ri-close-line"></i> Cancelar</a>
            <button type="submit" class="dash-btn dash-btn-primary px-4" id="btn-store">
                <i class="ri-save-line"></i> Salvar Categoria
            </button>
        </div>
    </div>

</div>

@section('js')
<script type="text/javascript">
    $(function () {
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });

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
                    return { pesquisa: params.term, empresa_id: $('#empresa_id').val() };
                },
                processResults: function (response) {
                    var results = [];
                    $.each(response, function (i, v) {
                        results.push({ id: v.id, text: v.nome });
                    });
                    return { results: results };
                },
            },
        });
    });
</script>
@endsection
