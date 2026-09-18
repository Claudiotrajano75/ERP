@php
    $docsIniciais = [];
    if (isset($item) && $item->infoDescarga && count($item->infoDescarga) > 0) {
        $qtdDocs = count($item->infoDescarga);
        $valCargaNum = floatval($item->valor_carga);
        $pesoCargaNum = floatval($item->quantidade_carga);
        $valorPorDoc = $qtdDocs > 0 ? ($valCargaNum / $qtdDocs) : 0;
        foreach ($item->infoDescarga as $info) {
            $chave = '';
            if ($info->nfe && !empty($info->nfe->chave)) {
                $chave = $info->nfe->chave;
            } elseif ($info->cte && !empty($info->cte->chave)) {
                $chave = $info->cte->chave;
            }
            $numDoc = (strlen($chave) === 44) ? (string) (int) substr($chave, 25, 9) : '';
            $serieDoc = (strlen($chave) === 44) ? (string) (int) substr($chave, 22, 3) : '1';
            
            $pesoDoc = floatval($info->quantidade_rateio);
            if ($qtdDocs === 1 && $pesoCargaNum > 0) {
                $pesoDoc = $pesoCargaNum;
            } elseif ($pesoDoc <= 0 && $qtdDocs > 0) {
                $pesoDoc = $pesoCargaNum / $qtdDocs;
            }
            
            $docsIniciais[] = [
                'chave_nfe' => $chave,
                'chave' => $chave,
                'chave_cte' => $info->cte ? $info->cte->chave : '',
                'numero' => $numDoc,
                'serie' => $serieDoc,
                'tipo_doc' => $info->nfe ? 'NFE' : ($info->cte ? 'CTE' : 'NFE'),
                'valor' => number_format($valorPorDoc, 2, ',', '.'),
                'quantidade_rateio' => number_format($pesoDoc, 2, ',', '.'),
                'municipio_descarregamento' => $info->cidade_id,
                'cidade_nome' => $info->cidade ? $info->cidade->nome : '',
                'cidade_uf' => $info->cidade ? $info->cidade->uf : '',
                'tp_und_transp' => $info->tp_unid_transp ?? 1,
                'id_und_transp' => $info->id_unid_transp ?? '',
                'lacres_transporte' => [],
                'lacres_unidade' => []
            ];
        }
    }

    $currentTpCarga = isset($item) && $item->tp_carga ? $item->tp_carga : '05';
    $tpCargasMap = [
        '01' => 'GRANEL SÓLIDO',
        '02' => 'GRANEL LÍQUIDO',
        '03' => 'FRIGORIFICADA',
        '04' => 'CONTEINERIZADA',
        '05' => 'CARGA GERAL',
        '06' => 'NEOGRANEL',
        '07' => 'PERIGOSA (GRANEL SÓLIDO)',
        '08' => 'PERIGOSA (GRANEL LÍQUIDO)',
        '09' => 'PERIGOSA (FRIGORIFICADA)',
        '10' => 'PERIGOSA (CONTEINERIZADA)',
        '11' => 'PERIGOSA (CARGA GERAL)',
    ];

    $numeroExibicao = isset($item) && $item->mdfe_numero ? $item->mdfe_numero : ($numeroMDFe ?? 'Informe');
    $serieExibicao = isset($item) && $item->serie ? $item->serie : '1';
@endphp

<style>
.dashboard-card {
    background: #fff;
    border: 1px solid #cbd5e1;
    border-radius: 10px;
    padding: 20px;
    margin-bottom: 14px;
    box-shadow: 0 1px 3px rgba(0,0,0,0.02);
}
.dashboard-card-title {
    font-size: 14.5px;
    font-weight: 700;
    color: #0f172a;
    margin-bottom: 14px;
    padding-bottom: 10px;
    border-bottom: 1px solid #e2e8f0;
}
.editable-field {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 13px;
    color: #555;
    margin-bottom: 12px;
}
.editable-field i.ri-pencil-line {
    color: #ff6b00;
    cursor: pointer;
    font-size: 15px;
}
.btn-outline-dashed {
    border: 1px dashed #ccc;
    background: transparent;
    color: #555;
    font-weight: 500;
}
.btn-outline-dashed:hover {
    border-color: #999;
    background: #f9f9f9;
}
/* ─── Card de NFes Importados (Estilo Fiel) ─── */
.nfe-main-card {
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 12px;
    padding: 22px;
    margin-bottom: 20px;
    box-shadow: 0 1px 3px rgba(0,0,0,0.02);
}
.nfe-title-wrapper {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 15px;
    font-weight: 700;
    color: #0f172a;
    margin-bottom: 14px;
}
.nfe-items-box {
    border: 1px solid #cbd5e1;
    border-radius: 10px;
    background: #ffffff;
    min-height: 200px;
    max-height: 320px;
    padding: 14px;
    overflow-y: auto;
    margin-bottom: 16px;
}
.nfe-pill-item {
    background: #ffffff;
    border: 1px solid #94a3b8;
    border-radius: 30px;
    padding: 7px 18px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 10px;
}
.nfe-pill-item:last-child {
    margin-bottom: 0;
}
.btn-import-more-nfe {
    background: #ffffff;
    border: 1px solid #cbd5e1;
    border-radius: 6px;
    padding: 7px 20px;
    font-size: 13.5px;
    font-weight: 600;
    color: #0f172a;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    transition: all 0.2s ease;
    box-shadow: 0 1px 2px rgba(0,0,0,0.03);
}
/* ─── Mini Cards Topo (Tipo Transporte, Nº Doc, Série) ─── */
.info-mini-card {
    background: #ffffff;
    border: 1px solid #cbd5e1;
    border-radius: 10px;
    overflow: hidden;
    display: flex;
    flex-direction: column;
    height: 100%;
    box-shadow: 0 1px 2px rgba(0,0,0,0.02);
}
.info-mini-card-header {
    padding: 9px 4px;
    text-align: center;
    font-size: 12px;
    font-weight: 700;
    color: #0f172a;
    border-bottom: 1px solid #e2e8f0;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}
.info-mini-card-body {
    padding: 10px 4px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 12px;
    color: #334155;
    font-weight: 500;
    flex-grow: 1;
    overflow: hidden;
}
.edit-icon-orange {
    color: #ff6b00 !important;
    font-size: 15px;
    margin-left: 5px;
    cursor: pointer;
    transition: transform 0.15s ease;
}
.edit-icon-orange:hover {
    transform: scale(1.15);
}
.mini-card-input {
    border: 1px solid #1e293b !important;
    border-radius: 4px !important;
    font-size: 13px !important;
    color: #0f172a !important;
    font-weight: 500 !important;
    padding: 3px 8px !important;
    background-color: #ffffff !important;
    box-shadow: none !important;
    width: 100% !important;
    height: 32px !important;
}
.mini-card-input:focus {
    border-color: #ff6b00 !important;
    outline: none !important;
    box-shadow: 0 0 0 1px #ff6b00 !important;
}
.mini-card-input::placeholder {
    color: #64748b !important;
    font-weight: 400 !important;
}
/* ─── Barra de Ações Inferior (Botões) ─── */
.bottom-actions-card {
    background: #ffffff;
    border: 1px solid #cbd5e1;
    border-radius: 10px;
    padding: 14px 20px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-top: 8px;
    margin-bottom: 20px;
    box-shadow: 0 1px 3px rgba(0,0,0,0.02);
}
.btn-action-outline {
    background: #ffffff;
    border: 1px solid #cbd5e1;
    border-radius: 6px;
    color: #334155;
    font-size: 13.5px;
    font-weight: 600;
    padding: 7px 22px;
    transition: all 0.2s ease;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    justify-content: center;
}
.btn-action-outline:hover {
    background: #f8fafc;
    border-color: #94a3b8;
    color: #0f172a;
}
.btn-action-view {
    background: #3f3f46;
    border: none;
    border-radius: 6px;
    color: #ffffff;
    font-size: 13.5px;
    font-weight: 700;
    padding: 7px 22px;
    transition: all 0.2s ease;
}
.btn-action-view:hover {
    background: #27272a;
    color: #ffffff;
}
.btn-action-conclude {
    background: #ff5722;
    border: none;
    border-radius: 6px;
    color: #ffffff;
    font-size: 13.5px;
    font-weight: 700;
    padding: 7px 28px;
    transition: all 0.2s ease;
    box-shadow: 0 2px 8px rgba(255, 87, 34, 0.25);
}
.btn-action-conclude:hover {
    background: #e64a19;
    color: #ffffff;
}
</style>

<div class="row">
    <!-- Coluna Esquerda -->
    <div class="col-xl-7 col-lg-7">
        
        <!-- NFes Importados -->
        <div class="nfe-main-card">
            <div class="nfe-title-wrapper">
                <i class="ri-file-line fs-17 text-dark"></i>
                <span id="lbl-nfe-count">0 NFes Importados</span>
            </div>
            
            <div class="nfe-items-box" id="container-nfe-list">
                <div class="text-center py-4 text-muted fs-13" id="empty-nfe-placeholder">
                    <i class="ri-file-upload-line fs-24 d-block mb-1 text-secondary"></i>
                    Nenhum documento importado ainda.
                </div>
            </div>

            <div class="text-center">
                <button type="button" class="btn btn-import-more-nfe" data-bs-toggle="modal" data-bs-target="#modal-importar_documentos">
                    Importar mais NFes <i class="ri-upload-2-line fs-15"></i>
                </button>
            </div>
        </div>

        <!-- Modal -->
        <div class="dashboard-card">
            <div class="dashboard-card-title">Modal</div>
            
            <div class="d-flex justify-content-center align-items-center gap-4 mb-4">
                <div class="form-check d-flex align-items-center gap-2">
                    <input class="form-check-input mt-0" type="radio" name="modal_tipo" id="modal_rodo" value="1" {{ (!isset($item) || $item->tipo_modal == 1) ? 'checked' : '' }} style="accent-color: #ff6b00; width: 17px; height: 17px;">
                    <label class="form-check-label text-dark fw-semibold fs-13" for="modal_rodo">Rodoviário</label>
                </div>
                <div class="form-check d-flex align-items-center gap-2">
                    <input class="form-check-input mt-0" type="radio" name="modal_tipo" id="modal_aqua" value="2" {{ (isset($item) && $item->tipo_modal == 2) ? 'checked' : '' }} style="accent-color: #ff6b00; width: 17px; height: 17px;">
                    <label class="form-check-label text-muted fs-13" for="modal_aqua">Aquaviário</label>
                </div>
                <div class="form-check d-flex align-items-center gap-2">
                    <input class="form-check-input mt-0" type="radio" name="modal_tipo" id="modal_aereo" value="3" {{ (isset($item) && $item->tipo_modal == 3) ? 'checked' : '' }} style="accent-color: #ff6b00; width: 17px; height: 17px;">
                    <label class="form-check-label text-muted fs-13" for="modal_aereo">Aéreo</label>
                </div>
            </div>

            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label fs-13 fw-bold text-dark mb-1">Veículo tração *</label>
                    <div class="d-flex align-items-center gap-2">
                        <select name="veiculo_tracao_id" id="inp-veiculo_tracao_id" class="form-select select2" style="border-radius: 6px; border-color: #cbd5e1;">
                            <option value="">Informe</option>
                            @isset($veiculos)
                                @foreach($veiculos as $v)
                                    <option value="{{ $v->id }}" 
                                        {{ (isset($item) && $item->veiculo_tracao_id == $v->id) ? 'selected' : '' }}
                                        data-funcionario-id="{{ $v->funcionario_id ?? '' }}"
                                        data-funcionario-nome="{{ $v->funcionario ? $v->funcionario->nome : '' }}"
                                        data-funcionario-cpf="{{ $v->funcionario ? $v->funcionario->cpf_cnpj : '' }}">
                                        {{ $v->placa }} - {{ $v->marca }}/{{ $v->modelo }}
                                    </option>
                                @endforeach
                            @endisset
                        </select>
                        <i class="ri-add-line fs-22 fw-bold text-orange cursor-pointer" title="Cadastrar veículo"></i>
                    </div>
                </div>
                <div class="col-md-6">
                    <label class="form-label fs-13 fw-bold text-dark mb-1">Motorista *</label>
                    <div class="d-flex align-items-center gap-2">
                        <select name="condutor_id" id="inp-condutor_id" class="form-select select2" style="border-radius: 6px; border-color: #cbd5e1;">
                            <option value="">Informe</option>
                            @isset($funcionarios)
                                @foreach($funcionarios as $f)
                                    <option value="{{ $f->id }}" 
                                        {{ (isset($item) && ($item->condutor_cpf == $f->cpf_cnpj || $item->condutor_nome == $f->nome || (isset($item->veiculoTracao) && $item->veiculoTracao->funcionario_id == $f->id))) ? 'selected' : '' }}
                                        data-nome="{{ $f->nome }}" data-cpf="{{ $f->cpf_cnpj }}">
                                        {{ $f->nome }}
                                    </option>
                                @endforeach
                            @endisset
                        </select>
                        <input type="hidden" name="condutor_nome" id="inp-condutor_nome" value="{{ $item->condutor_nome ?? '' }}">
                        <input type="hidden" name="condutor_cpf" id="inp-condutor_cpf" value="{{ $item->condutor_cpf ?? '' }}">
                        <i class="ri-add-line fs-22 fw-bold text-orange cursor-pointer" title="Cadastrar motorista"></i>
                    </div>
                    <a href="#" class="text-orange fs-12 text-decoration-none mt-1 d-inline-block fw-medium">Incluir mais...</a>
                </div>
                <div class="col-md-6 mt-2">
                    <label class="form-label fs-13 fw-bold text-dark mb-1">Veículo reboque</label>
                    <div class="d-flex align-items-center gap-2">
                        <select name="veiculo_reboque_id" id="inp-veiculo_reboque_id" class="form-select select2" style="border-radius: 6px; border-color: #cbd5e1;">
                            <option value="">Informe</option>
                            @isset($veiculos)
                                @foreach($veiculos as $v)
                                    <option value="{{ $v->id }}" {{ (isset($item) && $item->veiculo_reboque_id == $v->id) ? 'selected' : '' }}>{{ $v->placa }} - {{ $v->marca }}/{{ $v->modelo }}</option>
                                @endforeach
                            @endisset
                        </select>
                        <i class="ri-add-line fs-22 fw-bold text-orange cursor-pointer" title="Cadastrar reboque"></i>
                    </div>
                    <a href="#" class="text-orange fs-12 text-decoration-none mt-1 d-inline-block fw-medium">Incluir mais...</a>
                </div>
            </div>
        </div>

        <!-- Pagamento do Frete -->
        <div class="dashboard-card">
            <div class="dashboard-card-title mb-2 border-0">Informações de pagamento do frete</div>
            <div style="border: 1px solid #e0e0e0; border-radius: 6px; padding: 15px;">
                <a href="#" class="text-orange text-decoration-none fs-13 d-flex align-items-center gap-1 fw-semibold">
                    <i class="ri-add-line fw-bold"></i> Adicionar informações de pagamento
                </a>
            </div>
        </div>

    </div>

    <!-- Coluna Direita -->
    <div class="col-xl-5 col-lg-5">
        
        <!-- Top row (Mini Cards) -->
        <div class="row g-2 mb-3">
            <div class="col-4">
                <div class="info-mini-card">
                    <div class="info-mini-card-header">
                        Tipo de Transporte
                    </div>
                    <div class="info-mini-card-body">
                        <select name="tp_transp" id="inp-tp_transp" class="form-select select-clean-transport">
                            <option value="1" {{ (isset($item) && $item->tp_transp == 1) ? 'selected' : '' }}>Carga própria</option>
                            <option value="2" {{ (isset($item) && $item->tp_transp == 2) ? 'selected' : '' }}>Prestador de serviço</option>
                            <option value="3" {{ (isset($item) && $item->tp_transp == 3) ? 'selected' : '' }}>Transp. Carga Própria</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="col-4">
                <div class="info-mini-card">
                    <div class="info-mini-card-header">
                        Nº do documento
                    </div>
                    <div class="info-mini-card-body px-2" id="card-body-mdfe-numero">
                        <div class="d-flex align-items-center justify-content-center cursor-pointer w-100" id="view-mdfe-numero" onclick="ativarEdicaoNumeroDoc()">
                            <span id="txt-numero-mdfe" class="text-truncate">{{ $numeroExibicao }}</span>
                            <i class="ri-edit-2-line edit-icon-orange" title="Editar número"></i>
                        </div>
                        <div id="edit-box-mdfe-numero" class="w-100" style="display: none;">
                            <input type="text" name="mdfe_numero" id="inp-mdfe-numero" class="mini-card-input" value="{{ $numeroExibicao !== 'Informe' ? $numeroExibicao : '' }}" placeholder="Informe">
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-4">
                <div class="info-mini-card">
                    <div class="info-mini-card-header">
                        Série
                    </div>
                    <div class="info-mini-card-body px-2" id="card-body-mdfe-serie">
                        <div class="d-flex align-items-center justify-content-center cursor-pointer w-100" id="view-mdfe-serie" onclick="ativarEdicaoSerie()">
                            <span id="txt-serie">{{ $serieExibicao }}</span>
                            <i class="ri-edit-2-line edit-icon-orange" title="Editar série"></i>
                        </div>
                        <div id="edit-box-mdfe-serie" class="w-100" style="display: none;">
                            <input type="text" name="serie" id="inp-mdfe-serie" class="mini-card-input" value="{{ $serieExibicao }}" placeholder="1">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Viagem -->
        <div class="dashboard-card">
            <div class="dashboard-card-title">Viagem</div>
            
            {{-- Local de Carregamento --}}
            <div class="editable-field">
                <i class="ri-map-pin-fill text-dark fs-16"></i> 
                <span>
                    <strong class="text-dark">Local de Carregamento:</strong> 
                    <span class="text-secondary ms-1" id="disp-local-carregamento">
                        @if(isset($item) && $item->municipiosCarregamento && count($item->municipiosCarregamento) > 0)
                            {{ $item->uf_inicio }} - {{ $item->municipiosCarregamento->map(function($m) { return $m->cidade ? $m->cidade->nome : ''; })->filter()->implode(', ') }}
                        @else
                            CE - SOBRAL
                        @endif
                    </span>
                </span> 
                <i class="ri-edit-2-line edit-icon-orange" title="Alterar local de carregamento" onclick="abrirModalEditarCarregamento()"></i>
            </div>

            {{-- Local de Descarregamento --}}
            <div class="editable-field">
                <i class="ri-map-pin-line text-dark fs-16"></i> 
                <span>
                    <strong class="text-dark">Local de Descarregamento:</strong> 
                    <span class="text-secondary ms-1" id="disp-local-descarregamento">{{ $item->uf_fim ?? 'CE' }}</span>
                </span>
            </div>

            {{-- Percurso --}}
            <div class="editable-field">
                <i class="ri-map-pin-line text-dark fs-16"></i> 
                <span>
                    <strong class="text-dark">Percurso:</strong> 
                    <span class="text-secondary ms-1" id="disp-percurso">
                        @if(isset($item) && $item->percurso && count($item->percurso) > 0)
                            {{ $item->percurso->pluck('uf')->implode(' -> ') }}
                        @endif
                    </span>
                </span> 
                <i class="ri-edit-2-line edit-icon-orange" title="Alterar percurso" onclick="abrirModalEditarPercurso()"></i>
            </div>

            {{-- Data de Início de Viagem --}}
            <div class="editable-field mb-0">
                <i class="ri-calendar-line text-dark fs-16"></i> 
                <div class="d-flex align-items-center gap-1">
                    <strong class="text-dark">Data de Início de Viagem:</strong> 
                    
                    <div id="view-data-inicio" class="d-inline-flex align-items-center cursor-pointer" onclick="ativarEdicaoDataInicio()">
                        <span class="text-secondary ms-1" id="disp-data-inicio">{{ isset($item) && $item->data_inicio_viagem ? date('d/m/Y H:i', strtotime($item->data_inicio_viagem)) : date('d/m/Y H:i') }}</span>
                        <i class="ri-calendar-line ms-2" style="color: #cbd5e1 !important; font-size: 15px;" title="Alterar data"></i>
                    </div>

                    <div id="box-edit-data-inicio" style="display: none;">
                        <input type="datetime-local" id="inp-edit-data-inicio" class="mini-card-input" style="height: 28px; width: 175px; font-size: 12px;" value="{{ isset($item) && $item->data_inicio_viagem ? date('Y-m-d\TH:i', strtotime($item->data_inicio_viagem)) : date('Y-m-d\TH:i') }}">
                    </div>
                </div>
            </div>
        </div>

        <!-- Carga -->
        <div class="dashboard-card">
            <div class="dashboard-card-title">Carga</div>
            
            {{-- Valor Total da Carga --}}
            <div class="editable-field">
                <i class="ri-money-dollar-box-line text-dark fs-16"></i> 
                <div class="d-flex align-items-center gap-1 flex-wrap">
                    <strong class="text-dark">Valor Total da Carga:</strong> 
                    <div id="view-valor-carga" class="d-inline-flex align-items-center cursor-pointer" onclick="ativarEdicaoValorCarga()">
                        <span class="text-secondary ms-1" id="disp-valor-carga">R$ {{ isset($item) && $item->valor_carga ? number_format($item->valor_carga, 2, ',', '.') : '0,00' }}</span>
                        <i class="ri-edit-2-line edit-icon-orange ms-1" title="Editar valor da carga"></i>
                    </div>
                    <div id="box-edit-valor-carga" style="display: none;">
                        <input type="text" id="inp-edit-valor-carga" class="mini-card-input" style="height: 28px; width: 130px;" placeholder="R$ 0,00" value="{{ isset($item) && $item->valor_carga ? 'R$ ' . number_format($item->valor_carga, 2, ',', '.') : '' }}">
                    </div>
                </div>
            </div>

            {{-- Peso Total --}}
            <div class="editable-field">
                <i class="ri-weight-line text-dark fs-16"></i> 
                <div class="d-flex align-items-center gap-1 flex-wrap">
                    <strong class="text-dark">Peso Total:</strong> 
                    <div id="view-peso-total" class="d-inline-flex align-items-center cursor-pointer" onclick="ativarEdicaoPesoTotal()">
                        <span class="text-secondary ms-1" id="disp-peso-total">{{ isset($item) && $item->quantidade_carga ? number_format($item->quantidade_carga, 2, ',', '.') : '0.00' }} Kg</span>
                        <i class="ri-edit-2-line edit-icon-orange ms-1" title="Editar peso total"></i>
                    </div>
                    <div id="box-edit-peso-total" style="display: none;">
                        <input type="text" id="inp-edit-peso-total" class="mini-card-input" style="height: 28px; width: 120px;" placeholder="0,00" value="{{ isset($item) && $item->quantidade_carga ? number_format($item->quantidade_carga, 2, ',', '.') : '' }}">
                    </div>
                </div>
            </div>

            {{-- Produto Predominante --}}
            <div class="editable-field">
                <i class="ri-archive-line text-dark fs-16"></i> 
                <div class="d-flex align-items-center gap-1 flex-wrap">
                    <strong class="text-dark">Produto Predominante:</strong> 
                    <div id="view-prod-pred" class="d-inline-flex align-items-center cursor-pointer" onclick="ativarEdicaoProdPred()">
                        <span class="text-secondary ms-1 text-truncate" style="max-width: 170px;" id="disp-prod-predominante">{{ isset($item) && $item->produto_pred_nome ? $item->produto_pred_nome : '-' }}</span>
                        <i class="ri-edit-2-line edit-icon-orange ms-1" title="Editar produto predominante"></i>
                    </div>
                    <div id="box-edit-prod-pred" style="display: none;">
                        <input type="text" id="inp-edit-prod-pred" class="mini-card-input" style="height: 28px; width: 180px;" placeholder="Produto predominante" value="{{ $item->produto_pred_nome ?? '' }}">
                    </div>
                </div>
            </div>

            {{-- Tipo de carga --}}
            <div class="editable-field">
                <i class="ri-archive-line text-dark fs-16"></i> 
                <div class="d-flex align-items-center gap-1 flex-wrap">
                    <strong class="text-dark">Tipo de carga:</strong> 
                    <div id="view-tipo-carga" class="d-inline-flex align-items-center cursor-pointer" onclick="ativarEdicaoTipoCarga()">
                        <span class="text-secondary ms-1" id="disp-tipo-carga">{{ $tpCargasMap[$currentTpCarga] ?? 'CARGA GERAL' }}</span>
                        <i class="ri-edit-2-line edit-icon-orange ms-1" title="Editar tipo de carga"></i>
                    </div>
                    <div id="box-edit-tipo-carga" style="display: none;">
                        <select id="inp-edit-tipo-carga" class="mini-card-input" style="height: 28px; width: 155px; font-size: 12px; padding: 2px 4px;">
                            <option value="05" {{ $currentTpCarga == '05' ? 'selected' : '' }}>CARGA GERAL</option>
                            <option value="01" {{ $currentTpCarga == '01' ? 'selected' : '' }}>GRANEL SÓLIDO</option>
                            <option value="02" {{ $currentTpCarga == '02' ? 'selected' : '' }}>GRANEL LÍQUIDO</option>
                            <option value="03" {{ $currentTpCarga == '03' ? 'selected' : '' }}>FRIGORIFICADA</option>
                            <option value="04" {{ $currentTpCarga == '04' ? 'selected' : '' }}>CONTEINERIZADA</option>
                            <option value="06" {{ $currentTpCarga == '06' ? 'selected' : '' }}>NEOGRANEL</option>
                            <option value="07" {{ $currentTpCarga == '07' ? 'selected' : '' }}>PERIGOSA (GRANEL SÓLIDO)</option>
                            <option value="08" {{ $currentTpCarga == '08' ? 'selected' : '' }}>PERIGOSA (GRANEL LÍQUIDO)</option>
                            <option value="09" {{ $currentTpCarga == '09' ? 'selected' : '' }}>PERIGOSA (FRIGORIFICADA)</option>
                            <option value="10" {{ $currentTpCarga == '10' ? 'selected' : '' }}>PERIGOSA (CONTEINERIZADA)</option>
                            <option value="11" {{ $currentTpCarga == '11' ? 'selected' : '' }}>PERIGOSA (CARGA GERAL)</option>
                        </select>
                    </div>
                </div>
            </div>

            {{-- Código NCM --}}
            <div class="editable-field mb-0">
                <i class="ri-archive-line text-dark fs-16"></i> 
                <div class="d-flex align-items-center gap-1 flex-wrap">
                    <strong class="text-dark">Código NCM:</strong> 
                    <div id="view-ncm" class="d-inline-flex align-items-center cursor-pointer" onclick="ativarEdicaoNcm()">
                        <span class="text-secondary ms-1" id="disp-ncm">{{ isset($item) && $item->produto_pred_ncm ? $item->produto_pred_ncm : '-' }}</span>
                        <i class="ri-edit-2-line edit-icon-orange ms-1" title="Editar código NCM"></i>
                    </div>
                    <div id="box-edit-ncm" style="display: none;">
                        <input type="text" id="inp-edit-ncm" class="mini-card-input" style="height: 28px; width: 110px;" maxlength="8" placeholder="NCM" value="{{ $item->produto_pred_ncm ?? '' }}">
                    </div>
                </div>
            </div>
        </div>

        <!-- CIOT -->
        <div class="dashboard-card">
            <div class="dashboard-card-title">CIOT</div>
            <div class="d-flex flex-column gap-3 mt-3">
                <a href="#" class="text-orange text-decoration-none fs-13 d-flex align-items-center gap-1 fw-semibold">
                    <i class="ri-add-line fw-bold"></i> Adicionar CIOT
                </a>
                <a href="#" class="text-orange text-decoration-none fs-13 d-flex align-items-center gap-1 fw-semibold">
                    <i class="ri-file-list-3-line"></i> Emitir CIOT
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Rodapé / Barra de Ações -->
<div class="bottom-actions-card">
    <a href="{{ route('mdfe.index') }}" class="btn-action-outline">
        Cancelar
    </a>
    
    <div class="d-flex align-items-center gap-2">
        <button type="button" class="btn-action-outline">
            Mais Opções
        </button>
        <button type="button" class="btn-action-view">
            Visualizar
        </button>
        <button type="submit" class="btn-action-conclude">
            Concluir
        </button>
    </div>
</div>

{{-- Modal de Importação de XMLs --}}
@include('modals._importar_documentos')

{{-- Modal de Edição Individual de Documento --}}
@include('modals._editar_documento')

{{-- Modal de Edição de Viagem (Carregamento e Percurso) --}}
@include('modals._editar_viagem')

<!-- Container de Inputs Ocultos do Formulário -->
<div id="hidden-form-inputs">
    <input type="hidden" name="valor_carga" id="inp-valor_carga" value="{{ isset($item) && $item->valor_carga ? number_format($item->valor_carga, 2, ',', '.') : '0' }}">
    <input type="hidden" name="quantidade_carga" id="inp-quantidade_carga" value="{{ isset($item) && $item->quantidade_carga ? number_format($item->quantidade_carga, 2, ',', '.') : '0' }}">
    <input type="hidden" name="produto_pred_nome" id="inp-produto_pred_nome" value="{{ $item->produto_pred_nome ?? '' }}">
    <input type="hidden" name="produto_pred_ncm" id="inp-produto_pred_ncm" value="{{ $item->produto_pred_ncm ?? '' }}">
    <input type="hidden" name="tp_carga" id="inp-tp_carga" value="{{ $item->tp_carga ?? '05' }}">
    <input type="hidden" name="tp_emit" id="inp-tp_emit" value="{{ $item->tp_emit ?? '2' }}">
    <input type="hidden" name="tipo_modal" id="inp-tipo_modal" value="{{ $item->tipo_modal ?? '1' }}">
    <input type="hidden" name="unidade_medida" id="inp-unidade_medida" value="{{ $item->unidade_medida ?? 'KG' }}">
    <input type="hidden" name="uf_inicio" id="inp-uf_inicio" value="{{ $item->uf_inicio ?? 'CE' }}">
    <input type="hidden" name="uf_fim" id="inp-uf_fim" value="{{ $item->uf_fim ?? 'CE' }}">
    <input type="hidden" name="data_inicio_viagem" id="inp-data_inicio_viagem" value="{{ isset($item) && $item->data_inicio_viagem ? date('Y-m-d', strtotime($item->data_inicio_viagem)) : date('Y-m-d') }}">
    <input type="hidden" name="cnpj_contratante" id="inp-cnpj_contratante" value="{{ $item->cnpj_contratante ?? '' }}">
    <div id="hidden-descarregamentos-container"></div>
    <div id="hidden-municipios-carregamento-container">
        @if(isset($item) && $item->municipiosCarregamento && count($item->municipiosCarregamento) > 0)
            @foreach($item->municipiosCarregamento as $mc)
                <input type="hidden" name="municipiosCarregamento[]" value="{{ $mc->cidade_id }}">
            @endforeach
        @elseif(isset($empresa) && $empresa->cidade_id)
            <input type="hidden" name="municipiosCarregamento[]" value="{{ $empresa->cidade_id }}">
        @endif
    </div>
    <div id="hidden-percurso-container">
        @if(isset($item) && $item->percurso && count($item->percurso) > 0)
            @foreach($item->percurso as $p)
                <input type="hidden" name="uf[]" value="{{ $p->uf }}">
            @endforeach
        @endif
    </div>
</div>

<script>
window.mdfeDocumentos = {!! json_encode($docsIniciais ?? []) !!};

// Funções para Modais de Viagem
window.abrirModalEditarCarregamento = function() {
    const ufAtual = $('#inp-uf_inicio').val() || 'CE';
    $('#edit-carregamento-uf').val(ufAtual).trigger('change');
    
    const modalEl = document.getElementById('modal-editar-carregamento');
    const modal = bootstrap.Modal.getOrCreateInstance(modalEl);
    modal.show();
};

window.abrirModalEditarPercurso = function() {
    // Marca as UFs atualmente selecionadas
    $('.chk-percurso-uf').prop('checked', false);
    $('#hidden-percurso-container input[name="uf[]"]').each(function() {
        const uf = $(this).val();
        $('#percurso-uf-' + uf).prop('checked', true);
    });
    atualizarPreviewPercurso();

    const modalEl = document.getElementById('modal-editar-percurso');
    const modal = bootstrap.Modal.getOrCreateInstance(modalEl);
    modal.show();
};

function atualizarPreviewPercurso() {
    let ufs = [];
    $('.chk-percurso-uf:checked').each(function() {
        ufs.push($(this).val());
    });
    if (ufs.length > 0) {
        $('#preview-percurso-selecionado').text(ufs.join(' -> '));
    } else {
        $('#preview-percurso-selecionado').text('Nenhum estado intermediário selecionado.');
    }
}

// Funções de Edição Inline de Viagem e Carga
window.ativarEdicaoDataInicio = function() {
    $('#view-data-inicio').hide();
    $('#box-edit-data-inicio').show();
    $('#inp-edit-data-inicio').focus();
};

window.ativarEdicaoValorCarga = function() {
    $('#view-valor-carga').hide();
    $('#box-edit-valor-carga').show();
    $('#inp-edit-valor-carga').val($('#disp-valor-carga').text().trim()).focus().select();
};

window.ativarEdicaoPesoTotal = function() {
    $('#view-peso-total').hide();
    $('#box-edit-peso-total').show();
    $('#inp-edit-peso-total').val($('#disp-peso-total').text().replace(' Kg', '').trim()).focus().select();
};

window.ativarEdicaoProdPred = function() {
    $('#view-prod-pred').hide();
    $('#box-edit-prod-pred').show();
    let cur = $('#disp-prod-predominante').text().trim();
    $('#inp-edit-prod-pred').val(cur === '-' ? '' : cur).focus().select();
};

window.ativarEdicaoTipoCarga = function() {
    $('#view-tipo-carga').hide();
    $('#box-edit-tipo-carga').show();
    $('#inp-edit-tipo-carga').val($('#inp-tp_carga').val() || '05').focus();
};

window.ativarEdicaoNcm = function() {
    $('#view-ncm').hide();
    $('#box-edit-ncm').show();
    let cur = $('#disp-ncm').text().trim();
    $('#inp-edit-ncm').val(cur === '-' ? '' : cur).focus().select();
};

// Função global para abrir a modal de edição individual de um documento
window.abrirModalEditarDoc = function(index) {
    const doc = window.mdfeDocumentos[index];
    if (!doc) return;

    $('#edit-doc-index').val(index);
    $('#edit-doc-tipo').val(doc.tipo_doc || 'NFE');
    
    // UF e Município
    const uf = doc.cidade_uf || '';
    $('#edit-doc-uf').val(uf).trigger('change');
    
    if (doc.municipio_descarregamento) {
        $('#edit-doc-municipio').val(doc.municipio_descarregamento).trigger('change');
    }

    // Valor Total
    let vStr = (doc.valor || '0,00').toString();
    if (!vStr.startsWith('R$')) {
        vStr = 'R$ ' + vStr;
    }
    $('#edit-doc-valor').val(vStr);

    // Peso
    $('#edit-doc-peso').val(doc.quantidade_rateio || '0,00');

    // Chave de Acesso
    $('#edit-doc-chave').val(doc.chave_nfe || doc.chave || '');

    // Unidades de Transporte (Mais informações)
    $('#edit-doc-tp-und').val(doc.tp_und_transp || 1);
    $('#edit-doc-id-und').val(doc.id_und_transp || '');
    $('#container-mais-info').hide();
    $('#icon-mais-info').removeClass('ri-subtract-line').addClass('ri-add-line');

    const modalEl = document.getElementById('modal-editar-documento');
    const modal = bootstrap.Modal.getOrCreateInstance(modalEl);
    modal.show();
};

// Função global para remover documento
window.removerDoc = function(index) {
    window.mdfeDocumentos.splice(index, 1);
    renderizarDocumentosNfe();
};

// Renderiza a lista de documentos e recalcula os totais
window.renderizarDocumentosNfe = function() {
    const descarregamentos = window.mdfeDocumentos || [];
    const nfeCount = descarregamentos.length;
    $('#lbl-nfe-count').text(nfeCount + ' ' + (nfeCount === 1 ? 'NFe Importada' : 'NFes Importados'));

    if (descarregamentos.length === 0) {
        $('#container-nfe-list').html(`
            <div class="text-center py-4 text-muted fs-13" id="empty-nfe-placeholder">
                <i class="ri-file-upload-line fs-24 d-block mb-1 text-secondary"></i>
                Nenhum documento importado ainda.
            </div>
        `);
        $('#hidden-descarregamentos-container').html('');
        $('#disp-valor-carga').text('R$ 0,00');
        $('#inp-valor_carga').val('0,00');
        $('#disp-peso-total').text('0.00 Kg');
        $('#inp-quantidade_carga').val('0');
        return;
    }

    let nfeHtml = '';
    let hiddenDescHtml = '';
    let totalValor = 0.0;
    let totalPeso = 0.0;

    descarregamentos.forEach((item, index) => {
        let cidadeUf = '';
        if (item.cidade_nome && item.cidade_uf) {
            cidadeUf = item.cidade_nome + '/' + item.cidade_uf;
        } else if (item.cidade_info) {
            cidadeUf = item.cidade_info.replace(' (', '/').replace(')', '');
        } else if (item.destinatario) {
            cidadeUf = (item.destinatario.municipio || '') + '/' + (item.destinatario.uf || '');
        }

        let chave = item.chave_nfe || item.chave || '';
        let numDoc = item.numero || (chave.length === 44 ? chave.substring(25, 34) : '');
        let serieDoc = (chave.length === 44 ? chave.substring(22, 25) : '001');

        nfeHtml += `
            <div class="nfe-pill-item" id="nfe-row-${index}">
                <span class="fw-bold text-dark fs-13">NFe ${numDoc}/${serieDoc}</span>
                <div class="d-flex align-items-center gap-2 fs-13">
                    <span class="text-dark fw-medium">${cidadeUf || 'Descarregamento Padrão'}</span>
                    <i class="ri-edit-2-line text-secondary ms-1" style="cursor: pointer; font-size: 15px;" title="Editar documento" onclick="abrirModalEditarDoc(${index})"></i>
                    <i class="ri-close-line text-secondary ms-1" style="cursor: pointer; font-size: 17px;" title="Remover documento" onclick="removerDoc(${index})"></i>
                </div>
            </div>
        `;

        // Soma de valores
        let vNum = 0.0;
        if (typeof item.valor === 'number') {
            vNum = item.valor;
        } else if (typeof item.valor === 'string') {
            vNum = parseFloat(item.valor.replace('R$', '').replace(/\./g, '').replace(',', '.').trim()) || 0;
        }
        totalValor += vNum;

        // Soma de pesos
        let pNum = 0.0;
        if (typeof item.quantidade_rateio === 'number') {
            pNum = item.quantidade_rateio;
        } else if (typeof item.quantidade_rateio === 'string') {
            pNum = parseFloat(item.quantidade_rateio.replace(/\./g, '').replace(',', '.').trim()) || 0;
        }
        totalPeso += pNum;

        // Hidden inputs para salvar no backend
        let munId = item.municipio_descarregamento;
        hiddenDescHtml += `
            <div id="hidden-desc-row-${index}">
                <input type="hidden" name="tp_und_transp_row[]" value="${item.tp_und_transp || 1}">
                <input type="hidden" name="municipio_descarregamento_row[]" value="${munId || ''}">
                <input type="hidden" name="quantidade_rateio_row[]" value="${item.quantidade_rateio || ''}">
                <input type="hidden" name="chave_nfe_row[]" value="${chave}">
                <input type="hidden" name="chave_cte_row[]" value="${item.chave_cte || ''}">
                <input type="hidden" name="lacres_transporte_row[]" value='${JSON.stringify(item.lacres_transporte || [])}'>
                <input type="hidden" name="lacres_unidade_row[]" value='${JSON.stringify(item.lacres_unidade || [])}'>
            </div>
        `;
    });

    $('#container-nfe-list').html(nfeHtml);
    $('#hidden-descarregamentos-container').html(hiddenDescHtml);

    // Atualiza Totais Calculados no Card Carga
    const totalValorFormatado = totalValor.toLocaleString('pt-BR', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
    const totalPesoFormatado = totalPeso.toLocaleString('pt-BR', { minimumFractionDigits: 2, maximumFractionDigits: 2 });

    $('#disp-valor-carga').text('R$ ' + totalValorFormatado);
    $('#inp-valor_carga').val(totalValorFormatado);

    $('#disp-peso-total').text(totalPesoFormatado + ' Kg');
    $('#inp-quantidade_carga').val(totalPesoFormatado);
};

// Funções para ativar edição inline de Número do Documento e Série
window.ativarEdicaoNumeroDoc = function() {
    $('#view-mdfe-numero').hide();
    $('#edit-box-mdfe-numero').show();
    $('#inp-mdfe-numero').focus().select();
};

window.ativarEdicaoSerie = function() {
    $('#view-mdfe-serie').hide();
    $('#edit-box-mdfe-serie').show();
    $('#inp-mdfe-serie').focus().select();
};

document.addEventListener('DOMContentLoaded', function() {
    // 1. Inicializa Select2 para veículos, motorista e modal de edição
    $('#inp-veiculo_tracao_id, #inp-veiculo_reboque_id, #inp-condutor_id').select2({
        width: '100%',
        language: "pt-BR"
    });

    // Handlers para edição inline de Número do Documento
    $('#inp-mdfe-numero').on('blur', function() {
        let val = $(this).val().trim();
        $('#txt-numero-mdfe').text(val !== '' ? val : 'Informe');
        $('#edit-box-mdfe-numero').hide();
        $('#view-mdfe-numero').show();
    }).on('keydown', function(e) {
        if (e.key === 'Enter') {
            e.preventDefault();
            $(this).blur();
        } else if (e.key === 'Escape') {
            $(this).val($('#txt-numero-mdfe').text() === 'Informe' ? '' : $('#txt-numero-mdfe').text());
            $('#edit-box-mdfe-numero').hide();
            $('#view-mdfe-numero').show();
        }
    });

    // Handlers para edição inline de Série
    $('#inp-mdfe-serie').on('blur', function() {
        let val = $(this).val().trim();
        $('#txt-serie').text(val !== '' ? val : '1');
        $('#edit-box-mdfe-serie').hide();
        $('#view-mdfe-serie').show();
    }).on('keydown', function(e) {
        if (e.key === 'Enter') {
            e.preventDefault();
            $(this).blur();
        } else if (e.key === 'Escape') {
            $(this).val($('#txt-serie').text());
            $('#edit-box-mdfe-serie').hide();
            $('#view-mdfe-serie').show();
        }
    });

    $('#edit-doc-municipio').select2({
        dropdownParent: $('#modal-editar-documento'),
        width: '100%',
        language: "pt-BR"
    });

    $('#edit-carregamento-municipio').select2({
        dropdownParent: $('#modal-editar-carregamento'),
        width: '100%',
        language: "pt-BR"
    });

    // Filtro de municípios por UF na modal de Carregamento
    $('#edit-carregamento-uf').on('change', function() {
        const selectedUf = $(this).val();
        if (!selectedUf) {
            $('#edit-carregamento-municipio option').show();
            return;
        }
        $('#edit-carregamento-municipio option').each(function() {
            const optUf = $(this).data('uf');
            if (!optUf || optUf === selectedUf) {
                $(this).prop('disabled', false);
            } else {
                $(this).prop('disabled', true);
            }
        });
        $('#edit-carregamento-municipio').select2({
            dropdownParent: $('#modal-editar-carregamento'),
            width: '100%',
            language: "pt-BR"
        });
    });

    // Confirmar alteração do Local de Carregamento
    $('#btn-confirmar-carregamento').on('click', function() {
        const uf = $('#edit-carregamento-uf').val();
        const munId = $('#edit-carregamento-municipio').val();
        const munNome = $('#edit-carregamento-municipio option:selected').data('nome') || $('#edit-carregamento-municipio option:selected').text().split(' (')[0];

        if (uf && munId) {
            $('#disp-local-carregamento').text(uf + ' - ' + munNome);
            $('#inp-uf_inicio').val(uf);
            $('#hidden-municipios-carregamento-container').html(`<input type="hidden" name="municipiosCarregamento[]" value="${munId}">`);
        } else if (uf) {
            $('#disp-local-carregamento').text(uf);
            $('#inp-uf_inicio').val(uf);
        }

        const modalEl = document.getElementById('modal-editar-carregamento');
        const modal = bootstrap.Modal.getInstance(modalEl);
        if (modal) modal.hide();
    });

    // Eventos na modal de Percurso
    $(document).on('change', '.chk-percurso-uf', function() {
        atualizarPreviewPercurso();
    });

    // Confirmar alteração do Percurso
    $('#btn-confirmar-percurso').on('click', function() {
        let ufs = [];
        let hiddenHtml = '';
        $('.chk-percurso-uf:checked').each(function() {
            const uf = $(this).val();
            ufs.push(uf);
            hiddenHtml += `<input type="hidden" name="uf[]" value="${uf}">`;
        });

        $('#disp-percurso').text(ufs.join(' -> '));
        $('#hidden-percurso-container').html(hiddenHtml);

        const modalEl = document.getElementById('modal-editar-percurso');
        const modal = bootstrap.Modal.getInstance(modalEl);
        if (modal) modal.hide();
    });

    // Edição inline da Data de Início de Viagem
    $('#inp-edit-data-inicio').on('blur', function() {
        const rawVal = $(this).val();
        if (rawVal) {
            const d = new Date(rawVal);
            if (!isNaN(d.getTime())) {
                const dia = String(d.getDate()).padStart(2, '0');
                const mes = String(d.getMonth() + 1).padStart(2, '0');
                const ano = d.getFullYear();
                const hora = String(d.getHours()).padStart(2, '0');
                const min = String(d.getMinutes()).padStart(2, '0');
                $('#disp-data-inicio').text(`${dia}/${mes}/${ano} ${hora}:${min}`);
                $('#inp-data_inicio_viagem').val(`${ano}-${mes}-${dia}`);
            }
        }
        $('#box-edit-data-inicio').hide();
        $('#view-data-inicio').show();
    }).on('keydown', function(e) {
        if (e.key === 'Enter') {
            e.preventDefault();
            $(this).blur();
        } else if (e.key === 'Escape') {
            $('#box-edit-data-inicio').hide();
            $('#view-data-inicio').show();
        }
    });

    // Edição inline de Valor Total da Carga
    $('#inp-edit-valor-carga').on('blur', function() {
        let val = $(this).val().trim();
        if (val) {
            let numStr = val.replace('R$', '').trim();
            if (!val.startsWith('R$')) val = 'R$ ' + numStr;
            $('#disp-valor-carga').text(val);
            $('#inp-valor_carga').val(numStr);

            if (window.mdfeDocumentos && window.mdfeDocumentos.length > 0) {
                if (window.mdfeDocumentos.length === 1) {
                    window.mdfeDocumentos[0].valor = numStr;
                }
            }
        }
        $('#box-edit-valor-carga').hide();
        $('#view-valor-carga').show();
    }).on('keydown', function(e) {
        if (e.key === 'Enter') {
            e.preventDefault();
            $(this).blur();
        } else if (e.key === 'Escape') {
            $('#box-edit-valor-carga').hide();
            $('#view-valor-carga').show();
        }
    });

    // Edição inline de Peso Total
    $('#inp-edit-peso-total').on('blur', function() {
        let val = $(this).val().trim();
        if (val) {
            val = val.replace('Kg', '').replace('kg', '').trim();
            $('#disp-peso-total').text(val + ' Kg');
            $('#inp-quantidade_carga').val(val);

            // Sincroniza imediatamente com os documentos importados
            if (window.mdfeDocumentos && window.mdfeDocumentos.length > 0) {
                if (window.mdfeDocumentos.length === 1) {
                    window.mdfeDocumentos[0].quantidade_rateio = val;
                } else {
                    let pesoNum = parseFloat(val.replace(/\./g, '').replace(',', '.').trim()) || 0;
                    let pesoPorDoc = (pesoNum / window.mdfeDocumentos.length).toFixed(2).replace('.', ',');
                    window.mdfeDocumentos.forEach(doc => {
                        doc.quantidade_rateio = pesoPorDoc;
                    });
                }
                // Atualiza os inputs hidden do formulário
                window.mdfeDocumentos.forEach((doc, idx) => {
                    $(`input[name="quantidade_rateio_row[]"]`).eq(idx).val(doc.quantidade_rateio);
                });
            }
        }
        $('#box-edit-peso-total').hide();
        $('#view-peso-total').show();
    }).on('keydown', function(e) {
        if (e.key === 'Enter') {
            e.preventDefault();
            $(this).blur();
        } else if (e.key === 'Escape') {
            $('#box-edit-peso-total').hide();
            $('#view-peso-total').show();
        }
    });

    // Edição inline de Produto Predominante
    $('#inp-edit-prod-pred').on('blur', function() {
        let val = $(this).val().trim();
        $('#disp-prod-predominante').text(val !== '' ? val : '-');
        $('#inp-produto_pred_nome').val(val);
        $('#box-edit-prod-pred').hide();
        $('#view-prod-pred').show();
    }).on('keydown', function(e) {
        if (e.key === 'Enter') {
            e.preventDefault();
            $(this).blur();
        } else if (e.key === 'Escape') {
            $('#box-edit-prod-pred').hide();
            $('#view-prod-pred').show();
        }
    });

    // Edição inline de Tipo de Carga
    $('#inp-edit-tipo-carga').on('change blur', function() {
        const text = $(this).find('option:selected').text();
        const val = $(this).val();
        $('#disp-tipo-carga').text(text);
        $('#inp-tp_carga').val(val);
        $('#box-edit-tipo-carga').hide();
        $('#view-tipo-carga').show();
    });

    // Edição inline de Código NCM
    $('#inp-edit-ncm').on('blur', function() {
        let val = $(this).val().trim();
        $('#disp-ncm').text(val !== '' ? val : '-');
        $('#inp-produto_pred_ncm').val(val);
        $('#box-edit-ncm').hide();
        $('#view-ncm').show();
    }).on('keydown', function(e) {
        if (e.key === 'Enter') {
            e.preventDefault();
            $(this).blur();
        } else if (e.key === 'Escape') {
            $('#box-edit-ncm').hide();
            $('#view-ncm').show();
        }
    });

    // Toggle de Mais Informações na modal de edição
    $('#btn-toggle-mais-info').on('click', function() {
        const container = $('#container-mais-info');
        const icon = $('#icon-mais-info');
        if (container.is(':visible')) {
            container.slideUp(200);
            icon.removeClass('ri-subtract-line').addClass('ri-add-line');
        } else {
            container.slideDown(200);
            icon.removeClass('ri-add-line').addClass('ri-subtract-line');
        }
    });

    // Filtrar municípios por UF na modal de edição
    $('#edit-doc-uf').on('change', function() {
        const selectedUf = $(this).val();
        if (!selectedUf) {
            $('#edit-doc-municipio option').show();
            return;
        }
        $('#edit-doc-municipio option').each(function() {
            const optUf = $(this).data('uf');
            if (!optUf || optUf === selectedUf) {
                $(this).prop('disabled', false);
            } else {
                $(this).prop('disabled', true);
            }
        });
        $('#edit-doc-municipio').select2({
            dropdownParent: $('#modal-editar-documento'),
            width: '100%',
            language: "pt-BR"
        });
    });

    // Confirmar Edição do Documento
    $('#btn-confirmar-edicao-doc').on('click', function() {
        const index = parseInt($('#edit-doc-index').val());
        if (isNaN(index) || !window.mdfeDocumentos[index]) return;

        const doc = window.mdfeDocumentos[index];
        doc.tipo_doc = $('#edit-doc-tipo').val();
        doc.cidade_uf = $('#edit-doc-uf').val();
        doc.municipio_descarregamento = $('#edit-doc-municipio').val();
        
        const selectedMunOption = $('#edit-doc-municipio option:selected');
        if (selectedMunOption.length && selectedMunOption.val()) {
            doc.cidade_nome = selectedMunOption.data('nome') || selectedMunOption.text().split(' (')[0];
        }

        // Valor e Peso
        let rawVal = $('#edit-doc-valor').val().replace('R$', '').trim();
        doc.valor = rawVal;
        doc.quantidade_rateio = $('#edit-doc-peso').val().trim();

        // Chave e atualização de número e série
        const chave = $('#edit-doc-chave').val().trim();
        doc.chave_nfe = chave;
        if (chave.length === 44) {
            doc.serie = chave.substring(22, 25);
            doc.numero = chave.substring(25, 34);
        }

        // Unidades de Transporte
        doc.tp_und_transp = $('#edit-doc-tp-und').val();
        doc.id_und_transp = $('#edit-doc-id-und').val();

        // Re-renderiza e recalcula totais
        renderizarDocumentosNfe();

        // Fecha modal
        const modalEl = document.getElementById('modal-editar-documento');
        const modal = bootstrap.Modal.getInstance(modalEl);
        if (modal) modal.hide();
    });

    // Função para sincronizar motorista a partir do veículo selecionado
    function sincronizarMotoristaPorVeiculo() {
        const selectedOption = $('#inp-veiculo_tracao_id').find('option:selected');
        const funcId = selectedOption.data('funcionario-id');
        const funcNome = selectedOption.data('funcionario-nome');
        const funcCpf = selectedOption.data('funcionario-cpf');

        if (funcId) {
            $('#inp-condutor_id').val(funcId).trigger('change');
        }
        if (funcNome) {
            $('#inp-condutor_nome').val(funcNome);
        }
        if (funcCpf) {
            $('#inp-condutor_cpf').val(funcCpf);
        }
    }

    // Evento change no veículo tração
    $('#inp-veiculo_tracao_id').on('change', function() {
        sincronizarMotoristaPorVeiculo();
    });

    // Inicializa renderização dos documentos já existentes (modo edição)
    if (window.mdfeDocumentos && window.mdfeDocumentos.length > 0) {
        renderizarDocumentosNfe();
    }

    // Se já tiver veículo selecionado no carregamento e nenhum motorista definido
    if ($('#inp-veiculo_tracao_id').val() && !$('#inp-condutor_id').val()) {
        sincronizarMotoristaPorVeiculo();
    }

    // Atualizar hidden fields ao alterar motorista manualmente
    $('#inp-condutor_id').on('change', function() {
        const selectedOption = $(this).find('option:selected');
        const funcNome = selectedOption.data('nome') || selectedOption.text();
        const funcCpf = selectedOption.data('cpf') || '';
        
        if ($(this).val()) {
            $('#inp-condutor_nome').val(funcNome ? funcNome.trim() : '');
            $('#inp-condutor_cpf').val(funcCpf ? funcCpf.trim() : '');
        }
    });

    // 2. Processa dados importados do XML (sessionStorage)
    const rawData = sessionStorage.getItem('mdfe_imported_data');
    if (rawData) {
        try {
            const data = JSON.parse(rawData);
            console.log('Dados importados do XML recebidos:', data);

            // Carrega descarregamentos
            window.mdfeDocumentos = data.descarregamentos || [];
            
            // Se o usuário customizou descarregamento na modal de importação
            if (data.descarregamento_customizado) {
                window.mdfeDocumentos.forEach(d => {
                    d.cidade_uf = data.descarregamento_customizado.uf;
                    d.cidade_nome = data.descarregamento_customizado.cidade_nome;
                    d.municipio_descarregamento = data.descarregamento_customizado.cidade_id;
                });
            }

            // Renderiza as notas
            renderizarDocumentosNfe();

            // 2.2 Viagem
            if (data.campos) {
                let ufIni = data.campos.uf_inicio || 'CE';
                let ufFim = (data.descarregamento_customizado ? data.descarregamento_customizado.uf : data.campos.uf_fim) || 'CE';
                
                $('#inp-uf_inicio').val(ufIni);
                $('#inp-uf_fim').val(ufFim);

                if (data.municipios_carregamento_nomes && data.municipios_carregamento_nomes.length > 0) {
                    $('#disp-local-carregamento').text(ufIni + ' - ' + data.municipios_carregamento_nomes.join(', '));
                } else if (ufIni) {
                    $('#disp-local-carregamento').text(ufIni + ' - SOBRAL');
                }

                if (data.municipios_carregamento && data.municipios_carregamento.length > 0) {
                    let munCarregHtml = '';
                    data.municipios_carregamento.forEach(mId => {
                        munCarregHtml += `<input type="hidden" name="municipiosCarregamento[]" value="${mId}">`;
                    });
                    $('#hidden-municipios-carregamento-container').html(munCarregHtml);
                }

                if (data.descarregamento_customizado && data.descarregamento_customizado.cidade_nome) {
                    $('#disp-local-descarregamento').text(ufFim + ' - ' + data.descarregamento_customizado.cidade_nome);
                } else if (ufFim) {
                    $('#disp-local-descarregamento').text(ufFim);
                }

                if (data.percurso && data.percurso.length > 0) {
                    $('#disp-percurso').text(data.percurso.join(' -> '));
                    let percursoHtml = '';
                    data.percurso.forEach(ufP => {
                        percursoHtml += `<input type="hidden" name="uf[]" value="${ufP}">`;
                    });
                    $('#hidden-percurso-container').html(percursoHtml);
                }
            }

            // 2.3 Carga - Informações adicionais
            if (data.campos) {
                // Produto Predominante
                let prodNome = data.campos.produto_pred_nome || (data.produto_predominante ? data.produto_predominante.nome : '') || (data.campos.produto_predominante || '');
                if (prodNome) {
                    $('#disp-prod-predominante').text(prodNome);
                    $('#inp-produto_pred_nome').val(prodNome);
                }

                // Tipo de Carga
                if (data.campos.tp_carga) {
                    let tpCargaMap = {
                        '01': 'GRANEL SÓLIDO',
                        '02': 'GRANEL LÍQUIDO',
                        '03': 'FRIGORIFICADA',
                        '04': 'CONTEINERIZADA',
                        '05': 'CARGA GERAL',
                        '06': 'NEOGRANEL',
                        '07': 'PERIGOSA (GRANEL SÓLIDO)',
                        '08': 'PERIGOSA (GRANEL LÍQUIDO)',
                        '09': 'PERIGOSA (FRIGORIFICADA)',
                        '10': 'PERIGOSA (CONTEINERIZADA)',
                        '11': 'PERIGOSA (CARGA GERAL)'
                    };
                    $('#disp-tipo-carga').text(tpCargaMap[data.campos.tp_carga] || data.campos.tp_carga);
                    $('#inp-tp_carga').val(data.campos.tp_carga);
                }

                // NCM
                let ncm = data.campos.produto_pred_ncm || (data.produto_predominante ? data.produto_predominante.ncm : '') || (data.campos.ncm || '');
                if (ncm) {
                    $('#disp-ncm').text(ncm);
                    $('#inp-produto_pred_ncm').val(ncm);
                }
            }

            // Limpa o sessionStorage após aplicar com sucesso
            sessionStorage.removeItem('mdfe_imported_data');

        } catch (e) {
            console.error('Erro ao processar dados do XML no layout:', e);
        }
    }

    // Validação e Sincronização no submit do formulário antes de salvar
    $('#form-mdfe').on('submit', function(e) {
        const veiculoId = $('#inp-veiculo_tracao_id').val();
        const condutorId = $('#inp-condutor_id').val();
        const docs = window.mdfeDocumentos || [];

        if (!veiculoId) {
            e.preventDefault();
            if (typeof swal === 'function') {
                swal('Atenção', 'Por favor, selecione o Veículo tração antes de concluir.', 'warning');
            } else {
                alert('Por favor, selecione o Veículo tração antes de concluir.');
            }
            $('#inp-veiculo_tracao_id').select2('open');
            return false;
        }

        if (!condutorId) {
            e.preventDefault();
            if (typeof swal === 'function') {
                swal('Atenção', 'Por favor, informe o Motorista responsável.', 'warning');
            } else {
                alert('Por favor, informe o Motorista responsável.');
            }
            $('#inp-condutor_id').select2('open');
            return false;
        }

        if (docs.length === 0) {
            e.preventDefault();
            if (typeof swal === 'function') {
                swal('Atenção', 'Nenhum documento fiscal (NF-e) foi importado para esta MDF-e.', 'warning');
            } else {
                alert('Nenhum documento fiscal (NF-e) foi importado para esta MDF-e.');
            }
            return false;
        }

        // 1. Sincroniza motorista e condutor hidden
        const selectedOption = $('#inp-condutor_id').find('option:selected');
        if (selectedOption.length && selectedOption.val()) {
            $('#inp-condutor_nome').val(selectedOption.data('nome') || selectedOption.text());
            $('#inp-condutor_cpf').val(selectedOption.data('cpf') || '');
        }

        // 2. Sincroniza tipo de emitente e modal
        const tpTransp = $('#inp-tp_transp').val() || '1';
        $('#inp-tp_emit').val(tpTransp === '2' ? '1' : '2');
        
        const modalSelecionado = $('input[name="modal_tipo"]:checked').val() || '1';
        $('#inp-tipo_modal').val(modalSelecionado);

        // 3. Sincroniza Data de início de viagem
        const dtInicioRaw = $('#inp-edit-data-inicio').val();
        if (dtInicioRaw) {
            $('#inp-data_inicio_viagem').val(dtInicioRaw.substring(0, 10));
        }

        // 4. Garantir município de carregamento
        if ($('#hidden-municipios-carregamento-container input[name="municipiosCarregamento[]"]').length === 0) {
            const munPadrao = $('#edit-carregamento-municipio').val() || '{{ $empresa->cidade_id ?? 1 }}';
            $('#hidden-municipios-carregamento-container').html(`<input type="hidden" name="municipiosCarregamento[]" value="${munPadrao}">`);
        }

        // 5. Feedback no botão Concluir
        const btnConcluir = $(this).find('.btn-action-conclude');
        btnConcluir.prop('disabled', true).html('<i class="ri-loader-4-line ri-spin me-1"></i> Salvando MDF-e...');
    });
});
</script>

