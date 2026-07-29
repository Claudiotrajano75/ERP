<div class="row g-3 text-dark">
    <!-- Seção 1 -->
    <div class="col-12">
        <h5 class="text-dark border-bottom pb-2 mb-3">
            <i class="ri-key-line text-primary me-2 align-middle fs-18"></i>
            1. Dados de Autenticação
        </h5>
        
        <div class="row g-3 align-items-end">
            <div class="col-md-5 col-12">
                <label for="api_token" class="required form-label">Token</label>
                <div class="input-group">
                    <input readonly required type="text" class="form-control" id="api_token" name="token" value="{{ isset($item) ? $item->token : '' }}">
                    <button type="button" class="btn btn-primary px-3" id="btn_token" title="Gerar Novo Token">
                        <i class="ri-refresh-line"></i>
                    </button>
                </div>
            </div>
            
            <div class="col-md-3 col-12">
                {!!Form::select('status', 'Status', [1 => 'Ativo', 0 => 'Desativado'])->attrs(['class' => 'form-select'])->required()!!}
            </div>
        </div>
    </div>

    <!-- Seção 2 -->
    <div class="col-12 mt-4">
        <h5 class="text-dark border-bottom pb-2 mb-3 d-flex align-items-center justify-content-between">
            <div>
                <i class="ri-shield-keyhole-line text-primary me-2 align-middle fs-18"></i>
                2. Permissões de Acesso
            </div>
            
            @if(!isset($item))
            <div class="form-check fs-14 fw-normal">
                <input type="checkbox" class="form-check-input check_todos" id="checkAll">
                <label class="form-check-label text-muted" for="checkAll" style="cursor: pointer;">Marcar todos</label>
            </div>
            @endif
        </h5>

        <div class="row g-3">
            @foreach(\App\Models\ApiConfig::permissoes() as $key => $p)
            <div class="col-md-6 col-12">
                <div class="permission-card">
                    <div class="card-header bg-light border-bottom">
                        <label class="mb-0 fw-semibold text-dark fs-13 d-flex align-items-center">
                            <i class="ri-folder-shield-2-line text-muted me-2"></i> {{ $p }}
                        </label>
                    </div>
                    <div class="card-body p-3">
                        <div class="row g-2">
                            @foreach(\App\Models\ApiConfig::acoes() as $key2 => $acao)
                                @if(\App\Models\ApiConfig::inArrayPermissoes($key, $key2))
                                <div class="col-sm-6 col-12">
                                    <div class="form-check border rounded px-3 py-2 bg-white d-flex align-items-center gap-2" style="transition: all 0.2s;">
                                        <input name="permissoes_acesso[]" value="{{ $key }}.{{ $key2 }}" type="checkbox" class="form-check-input m-0 check-action" id="perm_{{ $key }}_{{ $key2 }}"
                                        @isset($item) 
                                            @if(sizeof($item->permissoes_acesso) > 0 && in_array($key . "." . $key2, $item->permissoes_acesso)) 
                                                checked="true" 
                                            @endif 
                                        @endif>
                                        <label class="form-check-label mb-0 flex-grow-1" for="perm_{{ $key }}_{{ $key2 }}" style="cursor: pointer; font-size: 13px;">{{ $acao }}</label>
                                    </div>
                                </div>
                                @endif
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>

<div class="modulo-actions">
    <div class="d-flex gap-2 justify-content-end">
        <a href="{{ route('config-api.index') }}" class="btn btn-outline-secondary">
            <i class="ri-close-line align-middle me-1"></i> Cancelar
        </a>
        <button type="submit" class="btn btn-success px-4" id="btn-store">
            <i class="ri-save-line align-middle me-1"></i> Salvar
        </button>
    </div>
</div>
