<div class="modal fade modal-pdv modal-pdv-modern" id="fechamento_caixa" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="fechamentoCaixaLegend" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header modulo-header-gradient">
                <div>
                    <h5 class="modal-title" id="fechamentoCaixaLegend">
                        <i class="ri-close-circle-line"></i> Fechar Caixa
                    </h5>
                    <p class="modulo-header-subtitle">Confira os valores e finalize o caixa</p>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                {!!Form::open()
                ->post()
                ->route('caixa.fechar')
                ->multipart()
                !!}
                <div class="row g-3">
                    <div class="col-md-12">
                        <div class="pdv-modal-stat">
                            <span class="pdv-modal-stat-label"><i class="ri-calculator-line me-1"></i>Valor Total</span>
                            <strong class="pdv-modal-stat-value text-success">R$ {{ __moeda($soma + $valor_abertura) }}</strong>
                        </div>
                    </div>
                    <input type="hidden" name="valor_fechamento" value="{{ $soma + $valor_abertura }}">
                    <input type="hidden" name="caixa_id" value="{{ $item->id }}">
                    <div class="col-md-12">
                        <label class="form-label"><i class="ri-money-dollar-circle-line me-1"></i>Total em Dinheiro</label>
                        {!! Form::tel('valor_dinheiro', '')->attrs(['class' => 'form-control moeda', 'placeholder' => '0,00']) !!}
                    </div>
                    <div class="col-md-12">
                        <label class="form-label"><i class="ri-bank-line me-1"></i>Valor em Cheque</label>
                        {!! Form::tel('valor_cheque', '')->attrs(['class' => 'form-control moeda', 'placeholder' => '0,00']) !!}
                    </div>
                    <div class="col-md-12">
                        <label class="form-label"><i class="ri-wallet-3-line me-1"></i>Valor em Outros</label>
                        {!! Form::tel('valor_outros', '')->attrs(['class' => 'form-control moeda', 'placeholder' => '0,00']) !!}
                    </div>
                    <div class="col-md-12">
                        <label class="form-label"><i class="ri-file-edit-line me-1"></i>Observação</label>
                        {!! Form::text('observacao', '')->attrs(['class' => 'form-control', 'placeholder' => 'Opcional']) !!}
                    </div>
                    <div class="col-md-12">
                        <button type="submit" class="btn btn-success px-3 w-100" data-bs-dismiss="modal">
                            <i class="ri-check-double-line me-1"></i> Salvar Fechamento
                        </button>
                    </div>
                </div>
                {!!Form::close()!!}
            </div>
        </div>
    </div>
</div>
