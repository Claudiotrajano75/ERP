<div class="row g-3 text-dark">

    <!-- ═══ Seção 1: Configuração de Regras de Preço ═══ -->
    <div class="col-12">
        <h5 class="modulo-section-header" style="font-weight: 700; font-size: 14px; color: #2c2c44; border-bottom: 2px solid #f0f2f8; padding-bottom: 10px; margin-bottom: 20px;">
            <i class="ri-settings-3-line text-primary me-2 align-middle fs-18"></i>
            1. Parâmetros da Lista & Reajuste em Lote
        </h5>
        <div class="row g-3">
            <div class="col-md-4 col-12">
                {!!Form::text('nome', 'Nome da Lista')->required()->placeholder('Ex: Atacado, Tabela Cartão')->attrs(['class' => 'form-control'])!!}
            </div>
            <div class="col-md-3 col-6">
                {!!Form::select('ajuste_sobre', 'Ajustar Sobre', ['' => 'Selecione', 'valor_compra' => 'Valor de compra', 'valor_venda' => 'Valor de venda'])
                ->required()->attrs(['class' => 'form-select'])!!}
            </div>
            <div class="col-md-3 col-6">
                {!!Form::select('tipo', 'Tipo de Reajuste', ['' => 'Selecione', 'incremento' => 'Incremento (+)', 'reducao' => 'Redução (-)'])
                ->required()->attrs(['class' => 'form-select'])!!}
            </div>
            <div class="col-md-2 col-12">
                {!!Form::text('percentual_alteracao', '% Alteração')->required()->attrs(['class' => 'form-control percentual text-end fw-bold text-primary'])!!}
            </div>
        </div>
    </div>

    <!-- ═══ Seção 2: Regras de Vínculo & Restrições ═══ -->
    <div class="col-12 mt-4">
        <h5 class="modulo-section-header" style="font-weight: 700; font-size: 14px; color: #2c2c44; border-bottom: 2px solid #f0f2f8; padding-bottom: 10px; margin-bottom: 20px;">
            <i class="ri-user-settings-line text-primary me-2 align-middle fs-18"></i>
            2. Regras de Vínculo & Restrições
        </h5>
        <div class="row g-3">
            <div class="col-md-4 col-12">
                {!!Form::select('tipo_pagamento', 'Restringir por Meio de Pagamento (opcional)', ['' => 'Todos'] + App\Models\ListaPreco::tiposPagamento())->attrs(['class' => 'form-select'])!!}
            </div>
            <div class="col-md-4 col-12">
                {!!Form::select('funcionario_id', 'Funcionário Vinculado (opcional)')
                ->options((isset($item) && $item->funcionario) ? [$item->funcionario_id => $item->funcionario->nome] : [])
                ->attrs(['class' => 'form-select'])!!}
            </div>
            <div class="col-md-4 col-12">
                <label class="form-label fw-semibold text-dark mb-1 required">Usuários com Acesso</label>
                <select required class="select2 form-control select2-multiple" name="usuarios[]" data-toggle="select2" multiple="multiple" id="usuarios" style="width: 100%;">
                    @foreach ($usuarios as $u)
                    <option @isset($item) @if(in_array($u->id, $item->usuarios)) selected @endif @endif value="{{$u->id}}">{{$u->name}}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>

    <!-- ═══ Botões de Ação ═══ -->
    <div class="col-12 mt-4">
        <hr class="text-muted opacity-25">
        <div class="d-flex gap-2 justify-content-end">
            <a href="{{ route('lista-preco.index') }}" class="btn btn-outline-secondary">
                <i class="ri-close-line align-middle me-1"></i> Cancelar
            </a>
            <button type="submit" class="btn btn-success px-4" id="btn-store">
                <i class="ri-save-line align-middle me-1"></i> Salvar Lista
            </button>
        </div>
    </div>

</div>
