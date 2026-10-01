<div class="modal fade" id="modal_fatura_venda" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="modalFaturaVendaLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow-lg modal-fatura-card">
            
            {{-- Header Moderno com Gradiente Escuro --}}
            <div class="modal-header modal-fatura-header border-0 py-3 px-4">
                <div class="d-flex align-items-center gap-3">
                    <div class="modal-fatura-icon">
                        <i class="ri-calculator-line fs-20 text-white"></i>
                    </div>
                    <div>
                        <h5 class="modal-title text-white fw-bold mb-0 fs-16" id="modalFaturaVendaLabel">
                            Gerar Fatura Automática
                        </h5>
                        <p class="text-white-50 mb-0 fs-12">
                            Configure o parcelamento e distribua as faturas da venda automaticamente
                        </p>
                    </div>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Fechar"></button>
            </div>

            <div class="modal-body p-4 bg-white">
                
                {{-- Card de Destaque: Valor Total a Parcelar --}}
                <div class="fatura-total-card mb-4 p-3 d-flex align-items-center justify-content-between flex-wrap gap-3">
                    <div class="d-flex align-items-center gap-3">
                        <div class="fatura-wallet-icon">
                            <i class="ri-wallet-3-line fs-24 text-primary"></i>
                        </div>
                        <div>
                            <span class="text-muted text-uppercase fw-bold fs-11 letter-spacing-1 d-block mb-1">
                                Valor Total a Parcelar
                            </span>
                            <div class="fs-22 fw-black text-dark lh-1">
                                R$ <span class="lbl-total_fatura text-primary fw-bolder">0,00</span>
                            </div>
                        </div>
                    </div>
                    <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-3 py-1.5 rounded-pill fs-12 fw-semibold d-inline-flex align-items-center gap-1.5">
                        <i class="ri-sparkling-fill text-primary"></i> Cálculo Inteligente
                    </span>
                </div>

                {{-- Grid de Campos --}}
                <div class="row g-3">
                    
                    {{-- Valor de Entrada --}}
                    <div class="col-md-3 col-sm-6">
                        <label class="form-label fatura-label" for="inp-entrada_fatura">
                            <i class="ri-hand-coin-line text-primary"></i> Valor de Entrada
                        </label>
                        <div class="input-group">
                            <span class="input-group-text fatura-addon">R$</span>
                            <input type="tel" class="form-control fatura-input moeda" id="inp-entrada_fatura" placeholder="0,00">
                        </div>
                        <span class="text-muted fs-11 mt-1 d-block">Opcional</span>
                    </div>

                    {{-- Quantidade de Parcelas --}}
                    <div class="col-md-3 col-sm-6">
                        <label class="form-label fatura-label" for="inp-parcelas_fatura">
                            <i class="ri-hashtag text-primary"></i> Qtd. Parcelas <span class="text-danger">*</span>
                        </label>
                        <input type="tel" class="form-control fatura-input text-center" data-mask="000" id="inp-parcelas_fatura" placeholder="Ex: 3">
                        <span class="text-muted fs-11 mt-1 d-block">Número de vezes</span>
                    </div>

                    {{-- Intervalo de Vencimento --}}
                    <div class="col-md-3 col-sm-6">
                        <label class="form-label fatura-label" for="inp-intervalo_fatura">
                            <i class="ri-timer-line text-primary"></i> Intervalo <span class="text-danger">*</span>
                        </label>
                        <div class="input-group">
                            <input type="tel" class="form-control fatura-input text-center" value="30" data-mask="000" id="inp-intervalo_fatura">
                            <span class="input-group-text fatura-addon">dias</span>
                        </div>
                        <span class="text-muted fs-11 mt-1 d-block">Dias entre parcelas</span>
                    </div>

                    {{-- Primeiro Vencimento --}}
                    <div class="col-md-3 col-sm-6">
                        <label class="form-label fatura-label" for="inp-primeiro_vencimento_fatura">
                            <i class="ri-calendar-event-line text-primary"></i> 1º Vencimento
                        </label>
                        <input type="date" class="form-control fatura-input" id="inp-primeiro_vencimento_fatura" value="{{ date('Y-m-d') }}">
                        <span class="text-muted fs-11 mt-1 d-block">Data inicial</span>
                    </div>

                    {{-- Tipo de Pagamento --}}
                    <div class="col-md-12 mt-2">
                        <div class="p-3 rounded-3 border bg-light-subtle">
                            <label class="form-label fatura-label mb-2" for="inp-tipo_pagamento_fatura">
                                <i class="ri-bank-card-line text-primary"></i> Forma / Tipo de Pagamento
                            </label>
                            <select class="form-control tipo_pagamento select2 w-100" id="inp-tipo_pagamento_fatura">
                                @foreach(App\Models\Nfe::tiposPagamento() as $key => $c)
                                <option value="{{$key}}">{{$c}}</option>
                                @endforeach
                            </select>
                            <span class="text-muted fs-12 mt-1.5 d-block">
                                <i class="ri-information-line me-1"></i> O meio selecionado será aplicado em todas as parcelas geradas.
                            </span>
                        </div>
                    </div>

                </div>
            </div>

            {{-- Footer com Ações --}}
            <div class="modal-footer modal-fatura-footer border-0 px-4 py-3 bg-light d-flex justify-content-between align-items-center">
                <button type="button" class="btn btn-outline-secondary px-3 py-2 fw-semibold fs-13 rounded-3" data-bs-dismiss="modal">
                    <i class="ri-close-line me-1"></i> Cancelar
                </button>
                <button type="button" class="btn btn-fatura-generate btn-store-fatura px-4 py-2 fw-bold fs-13 rounded-3 text-white shadow-sm d-inline-flex align-items-center gap-2">
                    <i class="ri-magic-line fs-16"></i> Gerar Parcelas
                </button>
            </div>

        </div>
    </div>
</div>

{{-- Estilos dedicados da Modal de Fatura --}}
<style>
    .modal-fatura-card {
        border-radius: 16px !important;
        overflow: hidden;
        border: 1px solid rgba(0, 0, 0, 0.08) !important;
        box-shadow: 0 25px 50px -12px rgba(15, 23, 42, 0.25) !important;
    }

    .modal-fatura-header {
        background: linear-gradient(135deg, #0f172a 0%, #1e1b4b 60%, #312e81 100%) !important;
        border-bottom: 1px solid rgba(255, 255, 255, 0.08) !important;
    }

    .modal-fatura-icon {
        width: 42px;
        height: 42px;
        border-radius: 12px;
        background: rgba(255, 255, 255, 0.12);
        border: 1px solid rgba(255, 255, 255, 0.2);
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }

    .fatura-total-card {
        background: linear-gradient(135deg, #f8faff 0%, #eef2ff 100%);
        border: 1px solid #e0e7ff;
        border-radius: 12px;
    }

    .fatura-wallet-icon {
        width: 44px;
        height: 44px;
        border-radius: 12px;
        background: #ffffff;
        border: 1px solid #e2e8f0;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 2px 6px rgba(99, 102, 241, 0.08);
    }

    .letter-spacing-1 {
        letter-spacing: 0.6px;
    }

    .fatura-label {
        font-size: 11px !important;
        font-weight: 700 !important;
        text-transform: uppercase !important;
        letter-spacing: 0.4px !important;
        color: #475569 !important;
        margin-bottom: 6px !important;
        display: flex;
        align-items: center;
        gap: 5px;
    }

    .fatura-input {
        height: 40px !important;
        border-radius: 9px !important;
        border: 1px solid #cbd5e1 !important;
        font-size: 13.5px !important;
        font-weight: 500 !important;
        color: #1e293b !important;
        background-color: #ffffff !important;
        transition: all 0.2s ease !important;
    }

    .fatura-input:focus {
        border-color: #6366f1 !important;
        box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.15) !important;
        background-color: #ffffff !important;
    }

    .fatura-addon {
        background-color: #f1f5f9 !important;
        border: 1px solid #cbd5e1 !important;
        color: #64748b !important;
        font-size: 12px !important;
        font-weight: 600 !important;
        border-radius: 9px !important;
    }

    .modal-fatura-footer {
        background-color: #f8fafc !important;
        border-top: 1px solid #e2e8f0 !important;
    }

    .btn-fatura-generate {
        background: linear-gradient(135deg, #059669 0%, #047857 100%) !important;
        border: none !important;
        transition: all 0.2s ease !important;
    }

    .btn-fatura-generate:hover {
        background: linear-gradient(135deg, #047857 0%, #065f46 100%) !important;
        transform: translateY(-1px);
        box-shadow: 0 6px 16px rgba(5, 150, 105, 0.3) !important;
    }

    .btn-fatura-generate:active {
        transform: translateY(0);
    }
</style>