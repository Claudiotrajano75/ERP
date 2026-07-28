<div class="row g-3 text-dark">

    <!-- ═══ SEÇÃO 1: CREDENCIAIS BÁSICAS ═══ -->
    <div class="col-12">
        <h5 class="text-dark border-bottom pb-2 mb-3">
            <i class="ri-user-line text-primary me-2 align-middle fs-18"></i>
            1. Identificação & Credenciais
        </h5>
        <div class="row g-3">
            <div class="col-md-4 col-12">
                {!!Form::text('name', 'Nome Completo')->placeholder('Ex: João Silva')->required()->attrs(['class' => 'form-control'])!!}
            </div>

            <div class="col-md-4 col-12">
                {!!Form::text('email', 'E-mail (Login)')->placeholder('joao@empresa.com')->required()->attrs(['class' => 'form-control'])!!}
            </div>

            <div class="col-md-2 col-6">
                {!!Form::select('admin', 'Administrador', [0 => 'Não', 1 => 'Sim'])->attrs(['class' => 'form-select'])->required()!!}
            </div>

            <div class="col-md-2 col-6">
                <label class="form-label fw-semibold text-dark mb-1 required">Senha</label>
                <div class="input-group" id="show_hide_password">
                    <input required type="password" class="form-control" id="senha" name="password" autocomplete="off"
                           @if(isset($senhaCookie)) value="{{$senhaCookie}}" @endif placeholder="Digite a senha">
                    <button type="button" class="btn btn-outline-secondary input-group-text"><i class='ri-eye-line'></i></button>
                </div>
            </div>
        </div>
    </div>

    <!-- ═══ SEÇÃO 2: NÍVEIS DE ACESSO ═══ -->
    <div class="col-12 mt-4">
        <h5 class="text-dark border-bottom pb-2 mb-3">
            <i class="ri-shield-user-line text-primary me-2 align-middle fs-18"></i>
            2. Níveis de Acesso & Alertas
        </h5>
        <div class="row g-3">
            <div class="col-md-4 col-12">
                {!!Form::select('role_id', 'Grupo / Controle de Acesso', ['' => 'Selecione'] + $roles->pluck('description', 'id')->all())
                ->attrs(['class' => 'select2 form-select'])
                ->value(isset($item) && $item->roles ? $item->roles->first()->id : null)
                ->required()!!}
            </div>

            @if(__countLocalAtivo() > 1)
            <div class="col-md-4 col-12">
                <label class="form-label fw-semibold text-dark mb-1 required">Locais de Acesso Permitidos</label>
                <select required class="select2 form-select select2-multiple" data-toggle="select2" name="locais[]" multiple="multiple">
                    @foreach(__getLocaisAtivos() as $local)
                    <option @if(in_array($local->id, (isset($item) ? $item->locais->pluck('localizacao_id')->toArray() : []))) selected @endif value="{{ $local->id }}">{{ $local->descricao }}</option>
                    @endforeach
                </select>
            </div>
            @else
            <input type="hidden" value="{{ __getLocalAtivo() ? __getLocalAtivo()->id : '' }}" name="local_id">
            @endif

            <div class="col-md-4 col-12">
                {!!Form::select('escolher_localidade_venda', 'Definir Localidade ao Vender/Comprar', [0 => 'Não', 1 => 'Sim'])->attrs(['class' => 'form-select'])!!}
                <div class="form-text text-muted fs-11 mt-1">Se "Sim", o usuário poderá alternar a filial nas vendas e compras.</div>
            </div>

            @if(__isNotificacao(Auth::user()->empresa) || __isNotificacaoMarketPlace(Auth::user()->empresa) || __isNotificacaoEcommerce(Auth::user()->empresa))
            <div class="col-12 mt-2">
                <div class="row g-2">
                    @if(__isNotificacao(Auth::user()->empresa))
                    <div class="col-md-3 col-6">
                        {!!Form::select('notificacao_cardapio', 'Notificação de Cardápio', [0 => 'Não', 1 => 'Sim'])->attrs(['class' => 'form-select'])->required()!!}
                    </div>
                    @endif
                    @if(__isNotificacaoMarketPlace(Auth::user()->empresa))
                    <div class="col-md-3 col-6">
                        {!!Form::select('notificacao_marketplace', 'Notificação de Delivery', [0 => 'Não', 1 => 'Sim'])->attrs(['class' => 'form-select'])->required()!!}
                    </div>
                    @endif
                    @if(__isNotificacaoEcommerce(Auth::user()->empresa))
                    <div class="col-md-3 col-6">
                        {!!Form::select('notificacao_ecommerce', 'Notificação de E-commerce', [0 => 'Não', 1 => 'Sim'])->attrs(['class' => 'form-select'])->required()!!}
                    </div>
                    @endif
                </div>
            </div>
            @endif
        </div>
    </div>

    <!-- ═══ SEÇÃO 3: FOTO ═══ -->
    <div class="col-12 mt-4">
        <h5 class="text-dark border-bottom pb-2 mb-3">
            <i class="ri-image-line text-primary me-2 align-middle fs-18"></i>
            3. Foto de Perfil
        </h5>
        <div class="row g-3">
            <div class="col-md-3 col-12">
                <div class="card border shadow-sm">
                    <div class="card-body p-2 text-center">
                        <div class="preview mb-2 bg-light rounded d-flex align-items-center justify-content-center border"
                             style="height: 120px; position: relative; overflow: hidden;">
                            <button type="button" id="btn-remove-imagem"
                                    class="btn btn-danger btn-sm p-1 rounded-circle"
                                    style="position: absolute; top: 5px; right: 5px; z-index: 10; line-height: 1; width: 22px; height: 22px;">×</button>
                            @isset($item)
                            <img id="file-ip-1-preview" src="{{ $item->img }}" style="width: 100%; height: 100%; object-fit: cover;">
                            @else
                            <img id="file-ip-1-preview" src="/imgs/no-image.png" style="width: 100%; height: 100%; object-fit: cover;">
                            @endif
                        </div>
                        <label for="file-ip-1" class="btn btn-primary btn-sm w-100 mb-0"><i class="ri-upload-cloud-line me-1"></i> Selecionar Foto</label>
                        <input type="file" class="d-none" id="file-ip-1" name="image" accept="image/*" onchange="showPreview(event);">
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- ═══ RODAPÉ COM BOTÕES ═══ -->
    <div class="col-12 mt-4">
        <hr class="text-muted opacity-25">
        <div class="d-flex align-items-center justify-content-end gap-2">
            <a href="{{ route('usuarios.index') }}" class="btn btn-outline-secondary px-4">
                <i class="ri-close-line align-middle me-1"></i> Cancelar
            </a>
            <button type="submit" class="btn {{ $formType === 'edit' ? 'btn-primary' : 'btn-success' }} px-4" id="btn-store">
                <i class="ri-save-line align-middle me-1"></i>
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
