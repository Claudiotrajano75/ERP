<div class="row g-3">
    <div class="col-md-4 col-12">
        <label class="form-label required">
            <i class="ri-folder-user-line text-primary"></i> Departamento
        </label>
        {!! Form::select('departamento', '', ['' => 'Selecione o Departamento', 'financeiro' => 'Financeiro', 'suporte' => 'Suporte Técnico'])
            ->attrs(['class' => 'form-select'])
            ->required()
        !!}
    </div>

    <div class="col-md-8 col-12">
        <label class="form-label required">
            <i class="ri-chat-voice-line text-primary"></i> Assunto do Chamado
        </label>
        {!! Form::text('assunto', '')
            ->attrs(['class' => 'form-control', 'placeholder' => 'Ex: Dúvida na emissão de NFC-e ou erro no fechamento de caixa'])
            ->required()
        !!}
    </div>

    <div class="col-12">
        <label class="form-label required">
            <i class="ri-file-text-line text-primary"></i> Mensagem / Descrição do Problema
        </label>
        {!! Form::textarea('descricao', '')
            ->attrs(['rows' => '8', 'class' => 'tiny form-control'])
        !!}
    </div>

    <div class="col-12">
        <label class="form-label">
            <i class="ri-attachment-line text-primary"></i> Anexo (Opcional)
        </label>
        <div class="upload-custom-box">
            <input type="file" name="anexo" class="form-control bg-white" id="inp-anexo" accept=".png,.jpg,.jpeg,.pdf,.xml,.txt,.doc,.docx">
            <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mt-2">
                <small class="text-muted fs-12">
                    <i class="ri-file-info-line me-1"></i> Formatos aceitos: imagens (.png, .jpg), documentos (.pdf, .txt, .docx) ou arquivos fiscais (.xml).
                </small>
                <small class="text-muted fs-11">Tamanho máximo recomendado: 10MB</small>
            </div>
        </div>
    </div>
</div>

<hr class="my-4" style="border-color: #e2e8f0;">

<div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
    <a href="{{ route('ticket.index') }}" class="btn-acao btn-cancelar">
        <i class="ri-close-line"></i> Cancelar
    </a>
    <button type="submit" class="btn-acao btn-salvar" id="btn-store">
        <i class="ri-send-plane-fill"></i> Enviar Solicitação
    </button>
</div>
