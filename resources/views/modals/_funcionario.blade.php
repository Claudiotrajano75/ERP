{{-- Classe modal-funcioario preservada: o JS (frente_caixa.js) usa o seletor $(".modal-funcioario select") para inicializar o Select2 com busca AJAX --}}
<div class="modal fade modal-pdv modal-pdv-modern modal-funcioario" id="funcionario" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="funcionarioLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header modulo-header-gradient">
                <div>
                    <h5 class="modal-title" id="funcionarioLabel">
                        <i class="ri-user-2-line"></i> Selecionar Vendedor
                    </h5>
                    <p class="modulo-header-subtitle">Escolha o responsável pela venda</p>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row g-2">
                    <div class="col-12">
                        <label class="form-label"><i class="ri-user-star-line me-1"></i>Vendedor</label>
                        {!! Form::select('funcionario_id', '')
                        ->options(isset($funcionario) ? [$funcionario->id => $funcionario->nome] : [])
                        ->attrs(['id' => 'inp-funcionario_id', 'class' => 'form-control'])
                        !!}
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">
                    <i class="ri-close-line me-1"></i> Cancelar
                </button>
                <button type="button" class="btn btn-primary funcionario-venda" data-bs-dismiss="modal">
                    <i class="ri-check-double-line me-1"></i> Salvar
                </button>
            </div>
        </div>
    </div>
</div>
