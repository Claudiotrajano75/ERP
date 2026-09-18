<div class="modal fade" id="info_vencimento" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="infoVencimentoLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content text-dark" style="border-radius: 14px; border: none; overflow: hidden; box-shadow: 0 20px 45px rgba(0,0,0,0.2); background: #ffffff;">

            {{-- Cabeçalho Indigo/Escuro com Gradiente --}}
            <div style="background: linear-gradient(135deg, #121026 0%, #1e1b4b 55%, #292255 100%); padding: 18px 22px; border-bottom: none;" class="d-flex align-items-center justify-content-between">
                <div>
                    <h5 class="modal-title mb-0 d-flex align-items-center gap-2" id="infoVencimentoLabel" style="color: #ffffff; font-size: 16.5px; font-weight: 700; letter-spacing: -0.2px;">
                        <i class="ri-calendar-check-line" style="background: rgba(255,255,255,0.14); padding: 6px; border-radius: 8px; font-size: 18px; color: #a5b4fc;"></i>
                        Informação de Lote e Vencimento
                    </h5>
                    <p class="mb-0" style="color: rgba(255,255,255,0.65); font-size: 12px; margin-top: 4px; font-weight: 400;">
                        Histórico de lotes, prazos de validade e quantidades adquiridas na compra
                    </p>
                </div>
                <button type="button" class="btn-close-modal-custom" data-bs-dismiss="modal" aria-label="Close"
                    style="background: rgba(255, 255, 255, 0.12); border: none; border-radius: 8px; width: 34px; height: 34px; display: inline-flex; align-items: center; justify-content: center; color: #ffffff; cursor: pointer; transition: all 0.2s;">
                    <i class="ri-close-line" style="font-size: 20px; line-height: 1;"></i>
                </button>
            </div>

            {{-- Corpo da Modal --}}
            <div class="modal-body p-4" style="background: #ffffff;">
                <div class="table-responsive" style="border-radius: 10px; border: 1px solid #e2e8f0; overflow: hidden; background: #ffffff;">
                    <table class="table table-hover align-middle mb-0 table-infoValidade">
                        <thead>
                            <tr style="background: #f8fafc;">
                                <th style="font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px; color: #475569; padding: 12px 16px; border-bottom: 2px solid #e2e8f0;">
                                    <i class="ri-barcode-line me-1" style="color: #4f46e5;"></i> Lote
                                </th>
                                <th style="font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px; color: #475569; padding: 12px 16px; border-bottom: 2px solid #e2e8f0;">
                                    <i class="ri-calendar-event-line me-1" style="color: #4f46e5;"></i> Vencimento
                                </th>
                                <th style="font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px; color: #475569; padding: 12px 16px; border-bottom: 2px solid #e2e8f0;">
                                    <i class="ri-shopping-cart-line me-1" style="color: #4f46e5;"></i> Quantidade de Compra
                                </th>
                            </tr>
                        </thead>
                        <tbody style="font-size: 13px;">
                           
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- Rodapé --}}
            <div class="modal-footer border-0 px-4 pb-4 pt-0 gap-2" style="background: #ffffff; justify-content: flex-end;">
                <button type="button" class="btn" data-bs-dismiss="modal"
                    style="background: #f1f5f9; color: #334155; border: 1px solid #e2e8f0; border-radius: 8px; padding: 9px 20px; font-weight: 600; font-size: 13px; display: inline-flex; align-items: center; gap: 6px; transition: all 0.2s;">
                    <i class="ri-close-line" style="font-size: 16px;"></i> Fechar
                </button>
            </div>

        </div>
    </div>
</div>

<style>
#info_vencimento .btn-close-modal-custom:hover {
    background: rgba(255, 255, 255, 0.22) !important;
}
#info_vencimento .table-infoValidade tbody tr:hover {
    background-color: #f8fafc;
}
#info_vencimento .table-infoValidade td {
    padding: 12px 16px;
    border-bottom: 1px solid #f1f5f9;
}
#info_vencimento .table-infoValidade tr:last-child td {
    border-bottom: none;
}
</style>
