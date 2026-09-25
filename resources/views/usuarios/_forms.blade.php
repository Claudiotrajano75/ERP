<style>
    /* ─── Formulário premium de Usuário ─── */
    .uf-section {
        margin-top: 6px;
    }
    .uf-section-title {
        display: flex; align-items: center; gap: 10px;
        font-size: 14px; font-weight: 700; color: #1f2937;
        border-bottom: 1px solid #eef0f6; padding-bottom: 10px; margin-bottom: 16px;
    }
    .uf-section-title .uf-ico {
        width: 30px; height: 30px; border-radius: 9px;
        background: #eef0ff; color: #4f46e5;
        display: inline-flex; align-items: center; justify-content: center; font-size: 15px;
    }
    .uf-section-title small { font-weight: 500; color: #94a3b8; font-size: 12px; margin-left: auto; }

    .uf-field label,
    .uf-field .form-label {
        display: block;
        font-size: 11px; font-weight: 700; letter-spacing: .04em; text-transform: uppercase;
        color: #64748b !important; margin-bottom: 6px;
    }
    .uf-field .form-label i, .uf-field label i { color: #a8a8c0; font-size: 12px; }
    .uf-field .form-control,
    .uf-field .form-select,
    .uf-field .input-group .form-control {
        height: 40px; border-radius: 10px; border: 1px solid #dcdce9;
        font-size: 13.5px; color: #1f2937; background: #fcfdfe;
        transition: all .15s ease;
    }
    .uf-field .form-control:focus,
    .uf-field .form-select:focus {
        border-color: #4f46e5; box-shadow: 0 0 0 3px rgba(79,70,229,.12); background: #fff;
    }
    .uf-field .input-group-text { border-radius: 0 10px 10px 0; background: #fff; border-color: #dcdce9; color: #64748b; cursor: pointer; }
    .uf-field .form-text { font-size: 11.5px !important; color: #94a3b8 !important; }
    .uf-required::after { content: ' *'; color: #dc2626; font-weight: 700; }

    /* Botões do rodapé */
    .uf-actions {
        display: flex; align-items: center; justify-content: flex-end; gap: 10px;
        border-top: 1px solid #eef0f6; padding-top: 16px; margin-top: 20px;
    }
</style>

<div class="row g-4">

    <!-- ═══ SEÇÃO 1: IDENTIFICAÇÃO & CREDENCIAIS ═══ -->
    <div class="col-12 uf-section">
        <div class="uf-section-title">
            <span class="uf-ico"><i class="ri-user-3-line"></i></span>
            1. Identificação &amp; Credenciais
            <small>Dados de acesso do colaborador</small>
        </div>
        <div class="row g-3">
            <div class="col-md-6 col-12 uf-field">
                {!!Form::text('name', 'Nome Completo')->placeholder('Ex: João Silva')->required()->attrs(['class' => 'form-control'])!!}
            </div>
            <div class="col-md-6 col-12 uf-field">
                {!!Form::text('email', 'E-mail (Login)')->placeholder('joao@empresa.com')->required()->attrs(['class' => 'form-control'])!!}
            </div>
        </div>
        <div class="row g-3 mt-1">
            <div class="col-md-4 col-12 uf-field">
                {!!Form::select('admin', 'Administrador', [0 => 'Não', 1 => 'Sim'])->attrs(['class' => 'form-select'])->required()!!}
            </div>
            <div class="col-md-4 col-12 uf-field">
                <label class="form-label required uf-required"><i class="ri-lock-line"></i> Senha</label>
                <div class="input-group" id="show_hide_password">
                    <input required type="password" class="form-control" id="senha" name="password" autocomplete="off"
                           @if(isset($senhaCookie)) value="{{$senhaCookie}}" @endif placeholder="Digite a senha">
                    <button type="button" class="btn btn-outline-secondary input-group-text"><i class="ri-eye-line"></i></button>
                </div>
            </div>
            <div class="col-md-4 col-12 d-flex align-items-end">
                <div class="w-100 p-3 rounded-2" style="background:#f8fafc;border:1px solid #eef0f6;">
                    <div class="d-flex align-items-center gap-2">
                        <i class="ri-information-line" style="color:#4f46e5;font-size:18px;"></i>
                        <div>
                            <div class="fw-semibold" style="font-size:12.5px;color:#1f2937;">Administrador</div>
                            <div style="font-size:11.5px;color:#94a3b8;">Define se o usuário tem acesso total ao sistema.</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- ═══ SEÇÃO 2: NÍVEIS DE ACESSO & ALERTAS ═══ -->
    <div class="col-12 uf-section mt-2">
        <div class="uf-section-title">
            <span class="uf-ico"><i class="ri-shield-user-line"></i></span>
            2. Níveis de Acesso &amp; Alertas
            <small>Permissões, locais e notificações</small>
        </div>
        <div class="row g-3">
            <div class="col-md-4 col-12 uf-field">
                {!!Form::select('role_id', 'Grupo / Controle de Acesso', ['' => 'Selecione'] + $roles->pluck('description', 'id')->all())
                ->attrs(['class' => 'select2 form-select'])
                ->value(isset($item) && $item->roles ? optional($item->roles->first())->id : null)
                ->required()!!}
            </div>

            @if(__countLocalAtivo() > 1)
            <div class="col-md-4 col-12 uf-field">
                <label class="form-label required uf-required"><i class="ri-map-pin-2-line"></i> Locais de Acesso Permitidos</label>
                <select required class="select2 form-select select2-multiple" data-toggle="select2" name="locais[]" multiple="multiple">
                    @foreach(__getLocaisAtivos() as $local)
                    <option @if(in_array($local->id, (isset($item) ? $item->locais->pluck('localizacao_id')->toArray() : []))) selected @endif value="{{ $local->id }}">{{ $local->descricao }}</option>
                    @endforeach
                </select>
                <div class="form-text mt-1">Deixe em branco para permitir todos os locais.</div>
            </div>
            @else
            <input type="hidden" value="{{ __getLocalAtivo() ? __getLocalAtivo()->id : '' }}" name="local_id">
            @endif

            <div class="col-md-4 col-12 uf-field">
                {!!Form::select('escolher_localidade_venda', 'Definir Localidade ao Vender/Comprar', [0 => 'Não', 1 => 'Sim'])->attrs(['class' => 'form-select'])!!}
                <div class="form-text mt-1">Se "Sim", o usuário poderá alternar a filial nas vendas e compras.</div>
            </div>

            @if(__isNotificacao(Auth::user()->empresa) || __isNotificacaoMarketPlace(Auth::user()->empresa) || __isNotificacaoEcommerce(Auth::user()->empresa))
            <div class="col-12 mt-2">
                <div class="row g-3">
                    @if(__isNotificacao(Auth::user()->empresa))
                    <div class="col-md-3 col-6 uf-field">
                        {!!Form::select('notificacao_cardapio', 'Notificação de Cardápio', [0 => 'Não', 1 => 'Sim'])->attrs(['class' => 'form-select'])->required()!!}
                    </div>
                    @endif
                    @if(__isNotificacaoMarketPlace(Auth::user()->empresa))
                    <div class="col-md-3 col-6 uf-field">
                        {!!Form::select('notificacao_marketplace', 'Notificação de Delivery', [0 => 'Não', 1 => 'Sim'])->attrs(['class' => 'form-select'])->required()!!}
                    </div>
                    @endif
                    @if(__isNotificacaoEcommerce(Auth::user()->empresa))
                    <div class="col-md-3 col-6 uf-field">
                        {!!Form::select('notificacao_ecommerce', 'Notificação de E-commerce', [0 => 'Não', 1 => 'Sim'])->attrs(['class' => 'form-select'])->required()!!}
                    </div>
                    @endif
                </div>
            </div>
            @endif
        </div>
    </div>

    <!-- ═══ SEÇÃO 3: FOTO ═══ -->
    <div class="col-12 uf-section mt-2">
        <div class="uf-section-title">
            <span class="uf-ico"><i class="ri-image-line"></i></span>
            3. Foto de Perfil
            <small>Imagem exibida para o usuário</small>
        </div>
        <div class="row g-3">
            <div class="col-md-3 col-12 uf-field">
                <div class="card border shadow-sm" style="border-radius:14px;">
                    <div class="card-body p-2 text-center">
                        <div class="preview mb-2 bg-light rounded d-flex align-items-center justify-content-center border"
                             style="height: 140px; position: relative; overflow: hidden;">
                            <button type="button" id="btn-remove-imagem"
                                    class="btn btn-danger btn-sm p-1 rounded-circle"
                                    style="position: absolute; top: 5px; right: 5px; z-index: 10; line-height: 1; width: 22px; height: 22px;">×</button>
                            @isset($item)
                            <img id="file-ip-1-preview" src="{{ $item->img }}" style="width: 100%; height: 100%; object-fit: cover;">
                            @else
                            <img id="file-ip-1-preview" src="/imgs/no-image.png" style="width: 100%; height: 100%; object-fit: cover;">
                            @endif
                        </div>
                        <label for="file-ip-1" class="btn btn-primary btn-sm w-100 mb-0" style="border-radius:9px;"><i class="ri-upload-cloud-line me-1"></i> Selecionar Foto</label>
                        <input type="file" class="d-none" id="file-ip-1" name="image" accept="image/*" onchange="showPreview(event);">
                    </div>
                </div>
            </div>
            <div class="col-md-8 col-12 d-flex align-items-end">
                <div class="text-muted" style="font-size:12.5px;">
                    <i class="ri-image-add-line me-1" style="color:#4f46e5;"></i>
                    Envie uma foto (JPG ou PNG). Ela aparecerá como avatar do usuário no sistema.
                </div>
            </div>
        </div>
    </div>

    <!-- ═══ RODAPÉ COM BOTÕES ═══ -->
    <div class="col-12">
        <div class="uf-actions">
            <a href="{{ route('usuarios.index') }}" class="dash-btn dash-btn-light px-4">
                <i class="ri-close-line"></i> Cancelar
            </a>
            <button type="submit" class="dash-btn dash-btn-primary px-4" id="btn-store">
                <i class="ri-save-line"></i>
                {{ $formType === 'edit' ? 'Salvar Alterações' : 'Salvar Usuário' }}
            </button>
        </div>
    </div>

</div>

@section('js')
<script type="text/javascript">
    $(document).ready(function() {
        $("#show_hide_password button").on('click', function(event) {
            event.preventDefault();
            let input = $('#show_hide_password input');
            let icon = $('#show_hide_password i');
            if (input.attr("type") === "text") {
                input.attr('type', 'password');
                icon.addClass("ri-eye-line").removeClass("ri-eye-off-line");
            } else {
                input.attr('type', 'text');
                icon.removeClass("ri-eye-line").addClass("ri-eye-off-line");
            }
        });
    });
</script>
<script type="text/javascript" src="/js/uploadImagem.js"></script>
@endsection
