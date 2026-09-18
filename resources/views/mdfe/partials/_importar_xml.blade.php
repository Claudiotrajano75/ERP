<style>
    /* ─── Seções (padrão do ERP) ─── */
    .uf-section-title {
        display: flex;
        align-items: center;
        gap: 10px;
        font-size: 14px;
        font-weight: 700;
        color: #1f2937;
        border-bottom: 1px solid #eef0f6;
        padding-bottom: 10px;
        margin-bottom: 16px;
    }
    .uf-section-title .uf-ico {
        width: 30px;
        height: 30px;
        border-radius: 9px;
        background: #eef0ff;
        color: #4f46e5;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 15px;
    }
    .uf-section-title small {
        font-weight: 500;
        color: #94a3b8;
        font-size: 12px;
        margin-left: auto;
    }

    /* ─── Importação automática do XML da NF-e ─── */
    .xml-import-box {
        background: #fff;
        border: 1px solid #e9ecf3;
        border-radius: 14px;
        box-shadow: 0 1px 2px rgba(16, 24, 40, .04);
        padding: 18px 20px;
        margin-bottom: 18px;
    }
    .xml-drop {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        gap: 4px;
        height: 100%;
        min-height: 132px;
        border: 2px dashed #c7cbf5;
        border-radius: 14px;
        background: #f7f8ff;
        padding: 20px 16px;
        cursor: pointer;
        text-align: center;
        transition: all .2s ease;
        margin-bottom: 0;
    }
    .xml-drop:hover,
    .xml-drop.is-dragover {
        border-color: #4f46e5;
        background: #eef0ff;
        transform: translateY(-1px);
    }
    .xml-drop i {
        font-size: 34px;
        color: #4f46e5;
    }
    .xml-drop .xml-drop-title {
        font-weight: 700;
        font-size: 13.5px;
        color: #1f2937;
    }
    .xml-drop .xml-drop-hint {
        font-size: 11.5px;
        color: #94a3b8;
    }
    .xml-drop.is-loading {
        opacity: .55;
        pointer-events: none;
    }
    #inp-mdfe-xml {
        display: none;
    }
    .xml-resumo {
        height: 100%;
        min-height: 132px;
        border: 1px solid #e9ecf3;
        border-radius: 14px;
        background: #fbfcff;
        padding: 14px 16px;
    }
    .xml-resumo .xml-chave {
        font-size: 11.5px;
        color: #5a5a7a;
        margin-top: 5px;
        word-break: break-all;
    }
    .xml-resumo .xml-total {
        font-size: 12.5px;
        font-weight: 600;
        color: #1f2937;
    }
    .mdfe-xml-avisos .alert {
        border-radius: 10px;
    }
</style>

<div class="xml-import-box">
    <div class="uf-section-title">
        <span class="uf-ico"><i class="ri-flashlight-line"></i></span>
        Emissão automática pelo XML da NF-e
        <small>Menos digitação: o manifesto é pré-preenchido pela nota</small>
    </div>

    <div class="row g-3">
        <div class="col-lg-7 col-12">
            <label class="xml-drop" id="mdfe-xml-drop" for="inp-mdfe-xml">
                <i class="ri-file-upload-line"></i>
                <span class="xml-drop-title">Arraste o XML da NF-e aqui</span>
                <span class="xml-drop-hint">ou clique para selecionar — pode enviar mais de uma nota</span>
            </label>
            <input type="file" id="inp-mdfe-xml" accept=".xml,application/xml,text/xml" multiple>
        </div>

        <div class="col-lg-5 col-12">
            <div class="xml-resumo" id="mdfe-xml-resumo">
                <div class="d-flex align-items-start gap-2 text-muted">
                    <i class="ri-information-line fs-18"></i>
                    <span class="fs-12">
                        O valor da carga, os municípios de carregamento e descarregamento, o produto
                        predominante e as chaves das notas são preenchidos automaticamente.
                    </span>
                </div>
            </div>
        </div>
    </div>

    <div class="mdfe-xml-avisos mt-3"></div>
</div>
