<div class="modal fade modal-pdv modal-pdv-modern modal-action-pos" id="sangria_caixa" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="sangriaLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header modulo-header-gradient">
                <div>
                    <h5 class="modal-title" id="sangriaLabel">
                        <i class="ri-subtract-line"></i> Sangria de Caixa
                    </h5>
                    <p class="modulo-header-subtitle">Registre uma retirada de valores do caixa</p>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                {!!Form::open()
                ->post()
                ->route('sangria.store')
                !!}
                <div class="row g-3">
                    <input type="hidden" name="caixa_id" value="{{ $abertura->id }}">
                    <div class="col-md-5">
                        <label class="form-label"><i class="ri-money-dollar-circle-line me-1"></i>Valor</label>
                        {!! Form::tel('valor', '')->attrs(['class' => 'form-control moeda'])->required() !!}
                    </div>
                    <div class="col-md-7 div-conta-empresa">
                        <label class="form-label"><i class="ri-bank-line me-1"></i>Conta empresa</label>
                        {!!Form::select('conta_empresa_sangria_id', '')
                        ->attrs(['class' => 'form-control conta_empresa'])
                        ->required()
                        !!}
                    </div>
                    <div class="col-md-12">
                        <label class="form-label"><i class="ri-file-edit-line me-1"></i>Observação</label>
                        {!! Form::text('observacao', '')->attrs(['class' => 'form-control', 'placeholder' => 'Opcional']) !!}
                    </div>
                    <div class="d-flex justify-content-end gap-2 mt-2">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">
                            <i class="ri-close-line me-1"></i> Cancelar
                        </button>
                        <button type="submit" class="btn btn-danger px-4">
                            <i class="ri-check-double-line me-1"></i> Salvar Sangria
                        </button>
                    </div>
                </div>
                {!!Form::close()!!}
            </div>
        </div>
    </div>
</div>
