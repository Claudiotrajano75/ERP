<div class="row g-3 text-dark">
    <!-- Seção 1: Credenciais -->
    <div class="col-12">
        <div class="card card-secao-fiscal border p-3 rounded-3 mb-3 bg-white">
            <h5 class="text-dark border-bottom pb-2 mb-3 d-flex align-items-center gap-2 fs-14 fw-bold">
                <i class="ri-key-line text-primary"></i>
                1. Autenticação e Chave do Token
            </h5>
            
            <div class="row g-3 align-items-end">
                <div class="col-md-7 col-12">
                    <label for="api_token" class="required form-label">Chave do Token (Bearer)</label>
                    <div class="input-group">
                        <input readonly required type="text" class="form-control font-monospace" id="api_token" name="token" value="{{ isset($item) ? $item->token : '' }}" placeholder="Clique no botão ao lado para gerar o token">
                        <button type="button" class="dash-btn dash-btn-primary" id="btn_token" title="Gerar Nova Chave de Token">
                            <i class="ri-refresh-line me-1"></i> Gerar Token
                        </button>
                    </div>
                </div>
                
                <div class="col-md-5 col-12">
                    {!!Form::select('status', 'Status do Token', [1 => 'Ativo (Permitir Acesso)', 0 => 'Desativado (Bloquear Acesso)'])->attrs(['class' => 'form-select'])->required()!!}
                </div>
            </div>
        </div>
    </div>

    <!-- Seção 2: Permissões -->
    <div class="col-12">
        <div class="card card-secao-fiscal border p-3 rounded-3 mb-3 bg-white">
            <div class="d-flex align-items-center justify-content-between border-bottom pb-2 mb-3 flex-wrap gap-2">
                <h5 class="text-dark mb-0 d-flex align-items-center gap-2 fs-14 fw-bold">
                    <i class="ri-shield-keyhole-line text-primary"></i>
                    2. Permissões e Escopos de Acesso por Módulo
                </h5>
                
                @if(!isset($item))
                <div class="form-check fs-13">
                    <input type="checkbox" class="form-check-input check_todos" id="checkAll">
                    <label class="form-check-label text-muted fw-semibold" for="checkAll" style="cursor: pointer;">Marcar todas as permissões</label>
                </div>
                @endif
            </div>

            <div class="row g-3">
                @foreach(\App\Models\ApiConfig::permissoes() as $key => $p)
                <div class="col-md-6 col-12">
                    <div class="permission-card">
                        <div class="card-header bg-light border-bottom">
                            <label class="mb-0 fw-bold text-dark fs-13 d-flex align-items-center">
                                <i class="ri-folder-shield-2-line text-primary me-2"></i> {{ $p }}
                            </label>
                        </div>
                        <div class="card-body p-3">
                            <div class="row g-2">
                                @foreach(\App\Models\ApiConfig::acoes() as $key2 => $acao)
                                    @if(\App\Models\ApiConfig::inArrayPermissoes($key, $key2))
                                    <div class="col-sm-6 col-12">
                                        <div class="form-check border rounded-3 px-3 py-2 bg-white d-flex align-items-center gap-2" style="transition: all 0.2s;">
                                            <input name="permissoes_acesso[]" value="{{ $key }}.{{ $key2 }}" type="checkbox" class="form-check-input m-0 check-action" id="perm_{{ $key }}_{{ $key2 }}"
                                            @isset($item) 
                                                @if(sizeof($item->permissoes_acesso) > 0 && in_array($key . "." . $key2, $item->permissoes_acesso)) 
                                                    checked="true" 
                                                @endif 
                                            @endif>
                                            <label class="form-check-label mb-0 flex-grow-1 text-dark" for="perm_{{ $key }}_{{ $key2 }}" style="cursor: pointer; font-size: 13px; font-weight: 500;">
                                                {{ $acao }}
                                            </label>
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
</div>

<div class="d-flex align-items-center justify-content-end gap-2 pt-2">
    <a href="{{ route('config-api.index') }}" class="dash-btn dash-btn-light">
        <i class="ri-close-line me-1"></i> Cancelar
    </a>
    <button type="submit" class="dash-btn dash-btn-primary px-5" id="btn-store">
        <i class="ri-save-line me-1"></i> Salvar Token de API
    </button>
</div>
