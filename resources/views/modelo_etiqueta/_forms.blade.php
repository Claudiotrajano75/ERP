<style>
    .modulo-section-header { display:flex; align-items:center; gap:8px; font-size:14px; font-weight:700; color:#1f2937 !important; border-bottom:1px solid #eef0f6 !important; padding-bottom:10px; margin-bottom:16px; }
    .modulo-section-header i { color:#4f46e5 !important; }
    .form-label, label { font-size:11px; font-weight:700; text-transform:uppercase; letter-spacing:.04em; color:#64748b; }
    .modulo-form-card .form-control, .modulo-form-card .form-select, .form-control, .form-select, select { border-radius:10px; border:1px solid #dcdce9 !important; font-size:13.5px; background:#fcfdfe; }
    .form-control:focus, .form-select:focus { border-color:#4f46e5 !important; box-shadow:0 0 0 3px rgba(79,70,229,.12) !important; background:#fff; }
</style>

<div class="row g-3 text-dark">

    <!-- ═══ Seção 1: Dimensões & Layout ═══ -->
    <div class="col-12">
        <h5 class="modulo-section-header" style="font-weight: 700; font-size: 14px; color: #2c2c44; border-bottom: 2px solid #f0f2f8; padding-bottom: 10px; margin-bottom: 20px;">
            <i class="ri-ruler-line text-primary me-2 align-middle fs-18"></i>
            1. Dimensões & Layout da Etiqueta
        </h5>
        <div class="row g-3">
            <div class="col-md-3 col-12">
                {!!Form::text('nome', 'Nome do Modelo')->placeholder('Ex: Etiqueta Gondola 40x30')->required()->attrs(['class' => 'form-control'])!!}
            </div>
            <div class="col-md-3 col-6">
                {!!Form::select('tipo', 'Tipo de Etiqueta', ['simples' => 'Simples', 'gondola' => 'Gôndola'])->required()->attrs(['class' => 'form-select'])!!}
            </div>
            <div class="col-md-6 col-12">
                {!!Form::text('observacao', 'Observação/Anotação')->placeholder('Ex: Utilizado na impressora Zebra L42')->attrs(['class' => 'form-control'])!!}
            </div>

            <div class="col-md-2 col-6">
                {!!Form::tel('altura', 'Altura (mm)')->attrs(['class' => 'form-control', 'data-mask' => '000.00', 'data-mask-reverse' => 'true'])->required()!!}
            </div>
            <div class="col-md-2 col-6">
                {!!Form::tel('largura', 'Largura (mm)')->attrs(['class' => 'form-control', 'data-mask' => '000.00', 'data-mask-reverse' => 'true'])->required()!!}
            </div>
            <div class="col-md-2 col-6">
                {!!Form::tel('quantidade_etiquetas', 'Qtd. Total Etiquetas')->attrs(['class' => 'form-control', 'data-mask' => '000'])->required()!!}
            </div>
            <div class="col-md-2 col-6">
                {!!Form::tel('etiquestas_por_linha', 'Etiquetas por Linha')->attrs(['class' => 'form-control', 'data-mask' => '00'])->required()!!}
            </div>
            <div class="col-md-2 col-6">
                {!!Form::tel('distancia_etiquetas_lateral', 'Dist. Lateral (mm)')->attrs(['class' => 'form-control', 'data-mask' => '000.00', 'data-mask-reverse' => 'true'])->required()!!}
            </div>
            <div class="col-md-2 col-6">
                {!!Form::tel('distancia_etiquetas_topo', 'Dist. Topo (mm)')->attrs(['class' => 'form-control', 'data-mask' => '000.00', 'data-mask-reverse' => 'true'])->required()!!}
            </div>

            <div class="col-md-3 col-6">
                {!!Form::tel('tamanho_fonte', 'Tamanho da Fonte (pt)')->attrs(['class' => 'form-control', 'data-mask' => '000.00', 'data-mask-reverse' => 'true'])->required()!!}
            </div>
            <div class="col-md-3 col-6">
                {!!Form::tel('tamanho_codigo_barras', 'Tamanho Cód. Barras')->attrs(['class' => 'form-control', 'data-mask' => '000.00', 'data-mask-reverse' => 'true'])->required()!!}
            </div>
        </div>
    </div>

    <!-- ═══ Seção 2: Campos Visíveis na Impressão ═══ -->
    <div class="col-12 mt-4">
        <h5 class="modulo-section-header" style="font-weight: 700; font-size: 14px; color: #2c2c44; border-bottom: 2px solid #f0f2f8; padding-bottom: 10px; margin-bottom: 20px;">
            <i class="ri-checkbox-multiple-line text-primary me-2 align-middle fs-18"></i>
            2. Campos Visíveis na Impressão
        </h5>
        <div class="row g-3">
            <div class="col-md-3 col-6">
                <div class="modulo-switch-card d-flex align-items-center justify-content-between">
                    <label class="form-check-label fw-medium text-dark" style="cursor: pointer;" for="nome_empresa">Nome da Empresa</label>
                    <div class="form-check form-switch mb-0">
                        <input type="checkbox" name="nome_empresa" class="form-check-input" value="1" id="nome_empresa" @if(isset($item) && $item->nome_empresa) checked @endif>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-6">
                <div class="modulo-switch-card d-flex align-items-center justify-content-between">
                    <label class="form-check-label fw-medium text-dark" style="cursor: pointer;" for="nome_produto">Nome do Produto</label>
                    <div class="form-check form-switch mb-0">
                        <input type="checkbox" name="nome_produto" class="form-check-input" value="1" id="nome_produto" @if(isset($item) && $item->nome_produto) checked @endif>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-6">
                <div class="modulo-switch-card d-flex align-items-center justify-content-between">
                    <label class="form-check-label fw-medium text-dark" style="cursor: pointer;" for="valor_produto">Valor do Produto</label>
                    <div class="form-check form-switch mb-0">
                        <input type="checkbox" name="valor_produto" class="form-check-input" value="1" id="valor_produto" @if(isset($item) && $item->valor_produto) checked @endif>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-6">
                <div class="modulo-switch-card d-flex align-items-center justify-content-between">
                    <label class="form-check-label fw-medium text-dark" style="cursor: pointer;" for="codigo_produto">Código do Produto</label>
                    <div class="form-check form-switch mb-0">
                        <input type="checkbox" name="codigo_produto" class="form-check-input" value="1" id="codigo_produto" @if(isset($item) && $item->codigo_produto) checked @endif>
                    </div>
                </div>
            </div>
            <div class="col-md-4 col-12">
                <div class="modulo-switch-card d-flex align-items-center justify-content-between">
                    <label class="form-check-label fw-medium text-dark" style="cursor: pointer;" for="codigo_barras_numerico">Exibir Código de Barras Numérico</label>
                    <div class="form-check form-switch mb-0">
                        <input type="checkbox" name="codigo_barras_numerico" class="form-check-input" value="1" id="codigo_barras_numerico" @if(isset($item) && $item->codigo_barras_numerico) checked @endif>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- ═══ Botões de Ação ═══ -->
    <div class="col-12 mt-4">
        <hr class="text-muted opacity-25">
        <div class="d-flex gap-2 justify-content-end">
            <a href="{{ route('modelo-etiquetas.index') }}" class="dash-btn dash-btn-light px-4">
                <i class="ri-close-line align-middle me-1"></i> Cancelar
            </a>
            <button type="submit" class="dash-btn dash-btn-primary px-4" id="btn-store">
                <i class="ri-save-line align-middle me-1"></i> Salvar Modelo
            </button>
        </div>
    </div>

</div>
