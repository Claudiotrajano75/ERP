<div class="row g-3">
    <div class="col-md-4 col-12">
        <label class="form-label required"><i class="ri-building-line me-1"></i> Empresa Solicitante</label>
        {!!Form::select('empresa', '', ['' => 'Selecione a Empresa'] + ($empresas ?? []))
        ->attrs(['class' => 'form-select select2', 'id' => 'inp-empresa'])
        ->required()
        ->value(isset($item) ? $item->empresa_id : null)
        !!}
    </div>

    <div class="col-md-3 col-12">
        <label class="form-label required"><i class="ri-folder-user-line me-1"></i> Departamento</label>
        {!!Form::select('departamento', '', ['' => 'Selecione', 'financeiro' => 'Financeiro', 'suporte' => 'Suporte Técnico'])
        ->attrs(['class' => 'form-select'])
        ->required()
        !!}
    </div>

    <div class="col-md-5 col-12">
        <label class="form-label required"><i class="ri-chat-voice-line me-1"></i> Assunto do Chamado</label>
        {!!Form::text('assunto', '')
        ->required()
        ->attrs(['class' => 'form-control', 'placeholder' => 'Ex: Dúvida sobre emissão de NF-e'])
        !!}
    </div>

    @if(!isset($item))
    <div class="col-md-12 col-12">
        <label class="form-label required"><i class="ri-file-text-line me-1"></i> Mensagem / Descrição Inicial</label>
        {!!Form::textarea('descricao', '')
        ->attrs(['rows' => '8', 'class' => 'tiny form-control'])
        !!}
    </div>

    <div class="col-md-6 col-12">
        <label class="form-label"><i class="ri-attachment-line me-1"></i> Anexo (Opcional)</label>
        {!!Form::file('anexo', '')->attrs(['class' => 'form-control'])!!}
        <small class="text-muted fs-11">Permitido imagens ou documentos complementares (.png, .jpg, .pdf).</small>
    </div>
    @endif
</div>

<div class="modulo-actions mt-4">
    <div class="d-flex gap-2 justify-content-end align-items-center">
        <a href="{{ route('ticket-super.index') }}" class="btn btn-outline-secondary">
            <i class="ri-close-line me-1"></i> Cancelar
        </a>
        <button type="submit" class="btn btn-success px-4" id="btn-store">
            <i class="ri-save-3-line me-1"></i> Salvar Solicitação
        </button>
    </div>
</div>
