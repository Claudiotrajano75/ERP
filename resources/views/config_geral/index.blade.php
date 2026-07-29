@extends('layouts.app', ['title' => 'Configurações Gerais'])

@section('css')
<style>
    .modulo-header-gradient { background: linear-gradient(135deg, #0d2b40 0%, #1a4a6e 100%); border-radius: 12px 12px 0 0 !important; border-bottom: none !important; }
    .modulo-header-gradient .modulo-title { color: #fff; font-weight: 700; letter-spacing: -0.3px; }
    .modulo-header-gradient .modulo-title i { background: rgba(255,255,255,0.15); padding: 8px; border-radius: 10px; color: #fff; }
    .modulo-header-gradient .modulo-subtitle { color: rgba(255,255,255,0.85) !important; font-weight: 400; }

    .modulo-form-card { border: 1px solid #eef0f5; border-radius: 12px; overflow: hidden; background: #fff; }

    .modulo-section-card {
        background: #fdfdfd;
        border: 1px solid #e9ecef;
        border-radius: 10px;
        margin-bottom: 20px;
    }
    .modulo-section-card .card-header {
        background: #f8f9fa;
        border-bottom: 1px solid #e9ecef;
        border-radius: 10px 10px 0 0;
        padding: 12px 20px;
    }
    .modulo-section-card .card-header h4 {
        margin: 0;
        font-size: 15px;
        font-weight: 600;
        color: #343a40;
        display: flex;
        align-items: center;
    }

    .check-module-label {
        display: flex;
        align-items: center;
        gap: 8px;
        padding: 6px 10px;
        border-radius: 8px;
        border: 1px solid #e9ecef;
        background: #fff;
        cursor: pointer;
        margin-bottom: 8px;
        transition: all .2s;
    }
    .check-module-label:hover { border-color: #0d2b40; background: #f0f4f8; }
    .check-module-label input[type=checkbox] { width: 18px; height: 18px; flex-shrink: 0; cursor: pointer; }
</style>
@endsection

@section('content')
<div class="mt-3">
    <div class="card modulo-form-card shadow-sm">
        <div class="card-header modulo-header-gradient py-3 px-4">
            <h4 class="mb-1 modulo-title d-flex align-items-center gap-2">
                <i class="ri-settings-3-fill"></i>
                Configurações Gerais
            </h4>
            <p class="mb-0 modulo-subtitle fs-13">
                Defina os parâmetros globais do sistema para PDV, vendas e produtos.
            </p>
        </div>
        <div class="card-body p-4">
            {!!Form::open()->fill($item)
            ->post()
            ->route('config-geral.store')
            ->multipart()
            !!}

            {{-- ─── PDV ─── --}}
            <div class="modulo-section-card">
                <div class="card-header">
                    <h4><i class="ri-store-2-line me-2"></i>PDV — Ponto de Venda</h4>
                </div>
                <div class="card-body">
                    <div class="row g-2">
                        <div class="col-md-3 mb-2">
                            {!!Form::text('balanca_digito_verificador', 'Referência produto balança (dígitos)')
                            ->value(isset($item) ? $item->balanca_digito_verificador : '')
                            !!}
                        </div>
                        <div class="col-md-2 mb-2">
                            {!!Form::select('balanca_valor_peso', 'Tipo unidade balança', ['valor' => 'Valor', 'peso' => 'Peso'])
                            ->attrs(['class' => 'form-select'])
                            !!}
                        </div>
                        <div class="col-md-2 mb-2">
                            {!!Form::select('abrir_modal_cartao', 'Modal dados do cartão', ['1' => 'Sim', '0' => 'Não'])
                            ->attrs(['class' => 'form-select'])
                            !!}
                        </div>
                        <div class="col-md-3 mb-2">
                            {!!Form::text('senha_manipula_valor', 'Senha desconto/acréscimo')
                            ->attrs(['class' => 'form-control'])
                            !!}
                        </div>
                        <div class="col-md-2 mb-2">
                            {!!Form::select('agrupar_itens', 'Agrupar itens', ['0' => 'Não', '1' => 'Sim'])
                            ->attrs(['class' => 'form-select'])
                            !!}
                        </div>
                        <div class="col-md-3 mb-3">
                            {!!Form::select('tipo_comissao', 'Tipo de comissão', ['percentual_vendedor' => '% Vendedor', 'percentual_margem' => '% Margem'])
                            ->attrs(['class' => 'form-select tooltipp'])
                            !!}
                            <div class="text-tooltip d-none">Marcar como sim se for usar esta categoria no cardápio</div>
                        </div>
                        <div class="col-md-3 mb-3">
                            {!!Form::select('modelo', 'Modelo', ['light' => 'Light', 'compact' => 'Compact'])
                            ->attrs(['class' => 'form-select'])
                            !!}
                        </div>
                        <div class="col-md-3 mb-3">
                            {!!Form::select('alerta_sonoro', 'Alerta sonoro', ['1' => 'Sim', '0' => 'Não'])
                            ->attrs(['class' => 'form-select'])
                            !!}
                        </div>
                        <div class="col-md-3 mb-3">
                            {!!Form::select('cabecalho_pdv', 'Cabeçalho no PDV', ['1' => 'Sim', '0' => 'Não'])
                            ->attrs(['class' => 'form-select'])
                            !!}
                        </div>

                        <div class="col-12 mt-2">
                            <label class="fw-semibold mb-2 d-block" style="font-size:13px;">Tipos de Pagamento Habilitados</label>
                            <div class="row g-1">
                                @foreach(\App\Models\Nfce::tiposPagamento() as $key => $t)
                                <div class="col-lg-3 col-6">
                                    <label class="check-module-label">
                                        <input name="tipos_pagamento_pdv[]" value="{{$t}}" type="checkbox" class="form-check-input check-module"
                                            @isset($item) @if(sizeof($item->tipos_pagamento_pdv) > 0 && in_array($t, $item->tipos_pagamento_pdv)) checked="true" @endif @endif>
                                        <span style="font-size:13px;">{{$t}}</span>
                                    </label>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ─── Pré Venda ─── --}}
            <div class="modulo-section-card">
                <div class="card-header">
                    <h4><i class="ri-shopping-cart-line me-2"></i>Pré Venda</h4>
                </div>
                <div class="card-body">
                    <div class="row g-2">
                        <div class="col-md-3 mb-2">
                            {!!Form::select('confirmar_itens_prevenda', 'Confirmar itens pré venda', ['0' => 'Não', '1' => 'Sim'])
                            ->attrs(['class' => 'form-select'])
                            !!}
                        </div>
                    </div>
                </div>
            </div>

            {{-- ─── Orçamento ─── --}}
            <div class="modulo-section-card">
                <div class="card-header">
                    <h4><i class="ri-file-list-3-line me-2"></i>Orçamento</h4>
                </div>
                <div class="card-body">
                    <div class="row g-2">
                        <div class="col-md-3 mb-2">
                            {!!Form::tel('percentual_desconto_orcamento', '% Máximo de desconto sobre lucro')
                            ->attrs(['class' => 'percentual'])
                            !!}
                        </div>
                    </div>
                </div>
            </div>

            {{-- ─── Produto ─── --}}
            <div class="modulo-section-card">
                <div class="card-header">
                    <h4><i class="ri-box-3-line me-2"></i>Produto</h4>
                </div>
                <div class="card-body">
                    <div class="row g-2">
                        <div class="col-md-3 mb-3">
                            {!!Form::tel('percentual_lucro_produto', '% Lucro padrão')
                            ->attrs(['class' => 'percentual'])
                            !!}
                        </div>
                        <div class="col-md-3 mb-3">
                            {!!Form::tel('margem_combo', 'Margem % combo')
                            ->attrs(['class' => 'percentual'])
                            !!}
                        </div>
                        <div class="col-md-3 mb-3">
                            {!!Form::select('gerenciar_estoque', 'Gerenciar estoque', ['0' => 'Não', '1' => 'Sim'])
                            ->attrs(['class' => 'form-select'])
                            !!}
                        </div>
                    </div>
                </div>
            </div>

            {{-- ─── Alertas ─── --}}
            <div class="modulo-section-card">
                <div class="card-header">
                    <h4><i class="ri-notification-3-line me-2"></i>Alertas e Notificações</h4>
                </div>
                <div class="card-body">
                    <div class="row g-1">
                        @foreach(App\Models\ConfigGeral::getNotificacoes() as $n)
                        <div class="col-lg-3 col-6">
                            <label class="check-module-label">
                                <input name="notificacoes[]" value="{{$n}}" type="checkbox" class="form-check-input"
                                    @isset($item) @if(sizeof($item->notificacoes) > 0 && in_array($n, $item->notificacoes)) checked="true" @endif @endif>
                                <span style="font-size:13px;">{{$n}}</span>
                            </label>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <div class="d-flex justify-content-end mt-2">
                <button type="submit" class="btn btn-success px-5" id="btn-store">
                    <i class="ri-save-line me-1"></i> Salvar
                </button>
            </div>

            {!!Form::close()!!}
        </div>
    </div>
</div>
@endsection
