<div class="row g-3">
    <div class="col-md-4 col-12">
        <label class="form-label required"><i class="ri-heading me-1"></i> Título da Notificação</label>
        {!!Form::text('titulo', '')
        ->required()
        ->attrs(['class' => 'form-control', 'placeholder' => 'Ex: Manutenção Programada'])
        !!}
    </div>

    <div class="col-md-2 col-6">
        <label class="form-label required"><i class="ri-toggle-line me-1"></i> Status</label>
        {!!Form::select('status', '', ['1' => 'Ativo', '0' => 'Desativado'])
        ->attrs(['class' => 'form-select'])->required()
        !!}
    </div>

    <div class="col-md-2 col-6">
        <label class="form-label required"><i class="ri-flag-line me-1"></i> Prioridade</label>
        {!!Form::select('prioridade', '', ['baixa' => 'Baixa', 'media' => 'Média', 'alta' => 'Alta'])
        ->attrs(['class' => 'form-select'])->required()
        !!}
    </div>

    <div class="col-md-4 col-12">
        <label class="form-label required"><i class="ri-text-snippet me-1"></i> Descrição Curta (Resumo)</label>
        {!!Form::text('descricao_curta', '')
        ->required()
        ->attrs(['class' => 'form-control', 'placeholder' => 'Ex: O sistema passará por instabilidade breve às 22h.'])
        !!}
    </div>

    @if(!isset($item))
    <div class="col-md-6 col-12">
        <label class="form-label"><i class="ri-building-line me-1"></i> Empresa Destino (Opcional)</label>
        {!!Form::select('empresa', '', ['' => 'Todas as Empresas (Transmissão Global)'] + ($empresas ?? []))
        ->attrs(['class' => 'form-select select2'])
        !!}
        <small class="text-muted fs-11">Deixe em branco caso queira enviar esta notificação para todas as empresas do ERP.</small>
    </div>
    @else
    <div class="col-md-6 col-12">
        <label class="form-label"><i class="ri-building-line me-1"></i> Empresa</label>
        {!!Form::text('emp', '')
        ->value($item->empresa ? $item->empresa->nome : 'Todas as Empresas')
        ->readonly(true)
        ->attrs(['class' => 'form-control bg-light'])
        !!}
    </div>
    @endif

    <div class="col-md-12 col-12">
        <label class="form-label required"><i class="ri-file-text-line me-1"></i> Conteúdo Completo da Notificação</label>
        {!!Form::textarea('descricao', '')
        ->attrs(['rows' => '8', 'class' => 'tiny form-control'])
        !!}
    </div>
</div>

<div class="modulo-actions mt-4">
    <div class="d-flex gap-2 justify-content-end align-items-center">
        <a href="{{ route('notificacao-super.index') }}" class="btn btn-outline-secondary">
            <i class="ri-close-line me-1"></i> Cancelar
        </a>
        <button type="submit" class="btn btn-success px-4" id="btn-store">
            <i class="ri-save-3-line me-1"></i> Salvar Notificação
        </button>
    </div>
</div>