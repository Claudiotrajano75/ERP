<div class="modal fade modal-pdv modal-pdv-modern" id="cpf_nota" tabindex="-1" role="dialog" aria-labelledby="cpfNotaLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" style="max-width: 480px;">
        <div class="modal-content text-dark" style="border-radius: 14px; border: none; overflow: hidden; box-shadow: 0 20px 45px rgba(0,0,0,0.22); background: #ffffff;">

            {{-- Cabeçalho Escuro Premium com Gradiente --}}
            <div style="background: linear-gradient(135deg, #121026 0%, #1e1b4b 55%, #292255 100%); padding: 18px 22px; border-bottom: none;" class="d-flex align-items-center justify-content-between">
                <div>
                    <h5 class="modal-title mb-0 d-flex align-items-center gap-2" id="cpfNotaLabel" style="color: #ffffff; font-size: 16.5px; font-weight: 700; letter-spacing: -0.2px;">
                        <span style="display: inline-block; width: 14px; height: 14px; border-radius: 50%; background: #383561; flex-shrink: 0;"></span>
                        CPF na Nota?
                    </h5>
                    <p class="mb-0" style="color: rgba(255,255,255,0.65); font-size: 12px; margin-top: 4px; font-weight: 400;">
                        Informe os dados para emissão da NFCe
                    </p>
                </div>
                <button type="button" class="btn-close-modal-custom" data-bs-dismiss="modal" aria-label="Close"
                    style="background: rgba(255, 255, 255, 0.12); border: none; border-radius: 8px; width: 34px; height: 34px; display: inline-flex; align-items: center; justify-content: center; color: #ffffff; cursor: pointer; transition: all 0.2s;">
                    <i class="ri-close-line" style="font-size: 20px; line-height: 1;"></i>
                </button>
            </div>

            {{-- Corpo da Modal com Inputs Estilizados --}}
            <div class="modal-body p-4" style="background: #ffffff;">
                <div class="row g-3">
                    <div class="col-12">
                        <label class="form-label" style="font-size: 11px; font-weight: 800; letter-spacing: 0.6px; text-transform: uppercase; color: #475569; margin-bottom: 8px; display: block;">
                            CPF/CNPJ
                        </label>
                        {!! Form::tel('cliente_cpf_cnpj', '')->attrs([
                            'class' => 'form-control cpf_cnpj',
                            'id' => 'inp-cliente_cpf_cnpj',
                            'placeholder' => '',
                            'style' => 'height: 48px; border-radius: 10px; border: 1px solid #e2e8f0; font-size: 14px; padding: 10px 14px; color: #1e293b; background: #ffffff; box-shadow: none;'
                        ]) !!}
                    </div>

                    <div class="col-12">
                        <label class="form-label" style="font-size: 11px; font-weight: 800; letter-spacing: 0.6px; text-transform: uppercase; color: #475569; margin-bottom: 8px; display: flex; align-items: center; gap: 6px;">
                            <i class="ri-user-3-line" style="color: #4f46e5; font-size: 15px;"></i> NOME
                        </label>
                        {!! Form::text('cliente_nome', '')->attrs([
                            'class' => 'form-control',
                            'id' => 'inp-cliente_nome',
                            'placeholder' => '',
                            'style' => 'height: 48px; border-radius: 10px; border: 1px solid #e2e8f0; font-size: 14px; padding: 10px 14px; color: #1e293b; background: #ffffff; box-shadow: none;'
                        ]) !!}
                    </div>
                </div>
            </div>

            {{-- Rodapé da Modal com Botões Modernos --}}
            <div class="modal-footer border-0 px-4 pb-4 pt-2 gap-2" style="background: #ffffff; justify-content: flex-end;">
                <button type="button" class="btn" data-bs-dismiss="modal"
                    style="background: #f1f5f9; color: #334155; border: 1px solid #e2e8f0; border-radius: 8px; padding: 9px 18px; font-weight: 600; font-size: 13.5px; display: inline-flex; align-items: center; gap: 6px; box-shadow: 0 1px 2px rgba(0,0,0,0.04); transition: all 0.2s;">
                    <i class="ri-close-line" style="font-size: 16px;"></i> Cancelar
                </button>
                <button type="button" id="btn_fiscal" class="btn"
                    style="background: #0d9488; color: #ffffff; border: none; border-radius: 8px; padding: 9px 22px; font-weight: 600; font-size: 13.5px; display: inline-flex; align-items: center; gap: 6px; box-shadow: 0 2px 6px rgba(13,148,136,0.25); transition: all 0.2s;">
                    <i class="ri-check-double-line" style="font-size: 16px;"></i> Emitir NFCe
                </button>
            </div>

        </div>
    </div>
</div>

<style>
#cpf_nota .form-control:focus {
    border-color: #4f46e5 !important;
    box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.12) !important;
    background-color: #ffffff !important;
}
#cpf_nota .btn-close-modal-custom:hover {
    background: rgba(255, 255, 255, 0.22) !important;
}
#cpf_nota #btn_fiscal:hover {
    background: #0f766e !important;
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(13, 148, 136, 0.35) !important;
}
#cpf_nota button[data-bs-dismiss="modal"]:hover {
    background: #e2e8f0 !important;
    color: #1e293b !important;
}
</style>
