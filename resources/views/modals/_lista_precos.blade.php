<div class="modal fade modal-pdv modal-pdv-modern" id="lista_precos" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="listaPrecosLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header modulo-header-gradient">
                <div>
                    <h5 class="modal-title" id="listaPrecosLabel">
                        <i class="ri-price-tag-3-line"></i> Lista de Preços
                    </h5>
                    <p class="modulo-header-subtitle">Selecione a lista de preços para esta venda</p>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row g-3">
                    <div class="col-md-4 col-12">
                        <label class="form-label"><i class="ri-money-dollar-circle-line me-1"></i>Tipo de Pagamento</label>
                        {!!Form::select('tipo_pagamento_lista', '', ['' => 'Selecione'] + App\Models\Nfce::tiposPagamento())
                        ->attrs(['class' => 'form-select'])
                        !!}
                        <div class="form-text">A lista pode alterar a forma de pagamento da venda.</div>
                    </div>

                    <div class="col-md-4 col-12">
                        <label class="form-label"><i class="ri-user-2-line me-1"></i>Funcionário</label>
                        {!! Form::select('funcionario_lista_id', '', ['' => 'Selecione'] + $funcionarios->pluck('nome', 'id')->all())
                        ->attrs(['class' => 'form-select'])
                        !!}
                        <div class="form-text">Vincula o vendedor à lista escolhida.</div>
                    </div>

                    <div class="col-md-4 col-12">
                        <label class="form-label"><i class="ri-price-tag-2-line me-1"></i>Lista de Preços</label>
                        {!! Form::select('lista_preco_id', '', ['' => 'Selecione'])
                        ->attrs(['class' => 'form-select'])
                        !!}
                        <div class="form-text">Digite para buscar a lista desejada.</div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">
                    <i class="ri-close-line me-1"></i> Cancelar
                </button>
                <button type="button" class="btn btn-primary btn-store" onclick="selecionaLista()" data-bs-dismiss="modal">
                    <i class="ri-check-double-line me-1"></i> Escolher Lista
                </button>
            </div>
        </div>
    </div>
</div>
