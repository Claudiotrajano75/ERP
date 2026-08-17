<div class="row g-3">
    <div class="col-md-4 col-12">
        <label class="form-label required"><i class="ri-pages-line me-1"></i> Nome da Página / Rota</label>
        {!!Form::text('pagina', '')
        ->required()
        ->attrs(['class' => 'form-control', 'placeholder' => 'Ex: produtos, nfe, pdv, clientes...'])
        !!}
        <small class="text-muted fs-11">Identificador exato da tela onde o botão de ajuda será exibido.</small>
    </div>

    <div class="col-md-4 col-12">
        <label class="form-label required"><i class="ri-server-line me-1"></i> URL do Servidor</label>
        {!!Form::text('url_servidor', '')
        ->required()
        ->attrs(['class' => 'form-control', 'placeholder' => 'Ex: https://meusistema.com.br'])
        !!}
        <small class="text-muted fs-11">Endereço base do servidor do sistema.</small>
    </div>

    <div class="col-md-4 col-12">
        <label class="form-label required"><i class="ri-youtube-line me-1"></i> URL do Vídeo (YouTube/Vimeo)</label>
        {!!Form::text('url_video', '')
        ->required()
        ->attrs(['class' => 'form-control', 'placeholder' => 'Ex: https://www.youtube.com/watch?v=...'])
        !!}
        <small class="text-muted fs-11">Link direto do tutorial para os usuários assistirem.</small>
    </div>
</div>

<div class="modulo-actions mt-4">
    <div class="d-flex gap-2 justify-content-end align-items-center">
        <a href="{{ route('video-suporte.index') }}" class="btn btn-outline-secondary">
            <i class="ri-close-line me-1"></i> Cancelar
        </a>
        <button type="submit" class="btn btn-success px-4" id="btn-store">
            <i class="ri-save-3-line me-1"></i> Salvar Vídeo
        </button>
    </div>
</div>