{{-- Formulário de Configuração de Etiquetas de Compra --}}

<div class="row g-3">

    {{-- ═══ LINHA 1: Modelo | Produtos ═══ --}}
    <div class="col-6">
        <div class="card card-secao-fiscal h-100 mb-0">
            <div class="card-header">
                <h5><i class="ri-layout-3-line me-2 text-primary"></i>Modelo de Etiqueta</h5>
            </div>
            <div class="card-body">
                <label class="form-label">Modelo Salvo</label>
                {!!Form::select('modelo_id', '', ['' => 'Selecione um modelo...'] + $modelos->pluck('nome', 'id')->all())
                ->attrs(['class' => 'form-select form-select-sm select2', 'id' => 'inp-modelo_id'])
                !!}
                <small class="text-muted d-block mt-2">
                    <i class="ri-information-line me-1"></i>Ao selecionar, os campos de configuração abaixo são preenchidos automaticamente.
                </small>
            </div>
        </div>
    </div>

    <div class="col-6">
        <div class="card card-secao-fiscal h-100 mb-0">
            <div class="card-header d-flex align-items-center justify-content-between">
                <h5><i class="ri-checkbox-multiple-line me-2 text-primary"></i>Produtos e Qtd. de Etiquetas</h5>
                <div class="d-flex gap-1">
                    <button type="button" class="btn btn-xs btn-outline-primary py-0 px-2" style="font-size:11px;" id="btn-selecionar-todos">
                        <i class="ri-checkbox-line"></i> Todos
                    </button>
                    <button type="button" class="btn btn-xs btn-outline-secondary py-0 px-2" style="font-size:11px;" id="btn-desselecionar-todos">
                        <i class="ri-checkbox-blank-line"></i> Nenhum
                    </button>
                </div>
            </div>
            <div class="card-body">
                <div class="row g-2">
                    @foreach($item->itens as $p)
                    <div class="col-12">
                        <div class="d-flex align-items-center gap-2 border rounded-2 px-3 py-2 bg-light">
                            <input class="form-check-input etiqueta-check flex-shrink-0" type="checkbox"
                                name="produto[]"
                                id="prod_{{ $p->produto_id }}"
                                value="{{ $p->produto_id }}"
                                style="width:18px;height:18px;">
                            <label for="prod_{{ $p->produto_id }}" class="flex-grow-1 mb-0 small fw-semibold text-dark lh-sm" style="cursor:pointer; font-size:12px;">
                                {{ Str::limit($p->produto->nome, 35) }}
                                <span class="badge bg-secondary ms-1" style="font-size:9px;">{{ $p->produto->codigo_barras ?? 'S/ EAN' }}</span>
                            </label>
                            <div class="d-flex align-items-center gap-2 flex-shrink-0">
                                <span class="text-muted" style="font-size:10px;">NF: <b>{{ (int)$p->quantidade }}</b></span>
                                <input type="number"
                                    class="form-control form-control-sm qtd-etiqueta-input text-center"
                                    id="qtd_{{ $p->produto_id }}"
                                    name="qtd_produto_{{ $p->produto_id }}"
                                    value="{{ (int)$p->quantidade }}"
                                    min="1"
                                    title="Qtd. de etiquetas"
                                    style="width:60px; font-size:12px; padding: 3px 6px;">
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    {{-- ═══ LINHA 2: Configurações (col-12) ═══ --}}
    <div class="col-12">
        <div class="card card-secao-fiscal mb-0">
            <div class="card-header">
                <h5><i class="ri-settings-3-line me-2 text-primary"></i>Configurações de Layout e Exibição</h5>
            </div>
            <div class="card-body">

                {{-- Dimensões --}}
                <div class="row g-2 align-items-end">
                    <div class="col-md-2">
                        {!!Form::select('tipo', 'Tipo', ['simples' => 'Simples', 'gondola' => 'Gôndola'])
                        ->attrs(['class' => 'form-select form-select-sm'])
                        ->required()
                        !!}
                    </div>
                    <div class="col-md-1">
                        {!!Form::tel('etiquestas_por_linha', 'Etq/Linha')
                        ->attrs(['class' => 'form-control form-control-sm', 'data-mask' => '00', 'placeholder' => '3'])
                        ->required()
                        !!}
                    </div>
                    <div class="col-md-1">
                        {!!Form::tel('tamanho_fonte', 'Fonte (px)')
                        ->attrs(['class' => 'form-control form-control-sm', 'data-mask' => '000.00', 'data-mask-reverse' => 'true', 'placeholder' => '8'])
                        ->required()
                        !!}
                    </div>
                    <div class="col-md-1">
                        {!!Form::tel('largura', 'Largura (mm)')
                        ->attrs(['class' => 'form-control form-control-sm', 'data-mask' => '000.00', 'data-mask-reverse' => 'true', 'placeholder' => '80'])
                        ->required()
                        !!}
                    </div>
                    <div class="col-md-1">
                        {!!Form::tel('altura', 'Altura (mm)')
                        ->attrs(['class' => 'form-control form-control-sm', 'data-mask' => '000.00', 'data-mask-reverse' => 'true', 'placeholder' => '30'])
                        ->required()
                        !!}
                    </div>
                    <div class="col-md-1">
                        {!!Form::tel('distancia_etiquetas_lateral', 'Dist. Lateral')
                        ->attrs(['class' => 'form-control form-control-sm', 'data-mask' => '000.00', 'data-mask-reverse' => 'true', 'placeholder' => '2'])
                        ->required()
                        !!}
                    </div>
                    <div class="col-md-1">
                        {!!Form::tel('distancia_etiquetas_topo', 'Dist. Topo')
                        ->attrs(['class' => 'form-control form-control-sm', 'data-mask' => '000.00', 'data-mask-reverse' => 'true', 'placeholder' => '2'])
                        ->required()
                        !!}
                    </div>
                    <div class="col-md-2">
                        {!!Form::tel('tamanho_codigo_barras', 'Altura Barcode (mm)')
                        ->attrs(['class' => 'form-control form-control-sm', 'data-mask' => '000.00', 'data-mask-reverse' => 'true', 'placeholder' => '15'])
                        ->required()
                        !!}
                    </div>
                    <input type="hidden" name="quantidade_etiquetas" value="1">

                    {{-- Separador + Opções de exibição na mesma linha --}}
                    <div class="col-md-2 d-flex flex-column justify-content-end">
                        <label class="form-label" style="font-size:10px; text-transform:uppercase; letter-spacing:.5px;">Exibir na Etiqueta</label>
                        <div class="d-flex flex-wrap gap-2">
                            <div class="form-check form-switch mb-0">
                                <input class="form-check-input" type="checkbox" name="nome_empresa" value="1" id="chk-nome-empresa">
                                <label class="form-check-label small" for="chk-nome-empresa" style="font-size:11px;">Empresa</label>
                            </div>
                            <div class="form-check form-switch mb-0">
                                <input class="form-check-input" type="checkbox" name="nome_produto" value="1" id="chk-nome-produto" checked>
                                <label class="form-check-label small" for="chk-nome-produto" style="font-size:11px;">Produto</label>
                            </div>
                            <div class="form-check form-switch mb-0">
                                <input class="form-check-input" type="checkbox" name="valor_produto" value="1" id="chk-valor-produto" checked>
                                <label class="form-check-label small" for="chk-valor-produto" style="font-size:11px;">Valor</label>
                            </div>
                            <div class="form-check form-switch mb-0">
                                <input class="form-check-input" type="checkbox" name="codigo_produto" value="1" id="chk-codigo-produto">
                                <label class="form-check-label small" for="chk-codigo-produto" style="font-size:11px;">Código</label>
                            </div>
                            <div class="form-check form-switch mb-0">
                                <input class="form-check-input" type="checkbox" name="codigo_barras_numerico" value="1" id="chk-cod-barras" checked>
                                <label class="form-check-label small" for="chk-cod-barras" style="font-size:11px;">Nº Barras</label>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Botões --}}
                <hr class="mt-3 mb-3">
                <div class="d-flex justify-content-end gap-2">
                    <a href="{{ route('compras.index') }}" class="btn btn-sm btn-light px-4">
                        <i class="ri-arrow-left-line me-1"></i> Cancelar
                    </a>
                    <button type="submit" class="btn btn-sm btn-primary px-5 fw-semibold" id="btn-store">
                        <i class="ri-barcode-box-line me-1"></i> Gerar Etiquetas
                    </button>
                </div>

            </div>
        </div>
    </div>

</div>

<script>
$(function () {
    $('#btn-selecionar-todos').click(function () {
        $('.etiqueta-check').prop('checked', true);
    });
    $('#btn-desselecionar-todos').click(function () {
        $('.etiqueta-check').prop('checked', false);
    });
});
</script>