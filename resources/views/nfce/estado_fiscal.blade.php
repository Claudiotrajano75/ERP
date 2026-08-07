@extends('layouts.app', ['title' => 'Alterar Estado Fiscal NFCe'])

@section('css')
<style>
/* ─── Estilos Personalizados para a Página ─── */
.card {
    border: 1px solid rgba(0, 0, 0, 0.06) !important;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.02) !important;
    border-radius: 16px !important;
    overflow: hidden;
    background: #fff;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1) !important;
    margin-bottom: 24px;
}

.card:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 24px rgba(0, 0, 0, 0.05) !important;
}

.card-body {
    padding: 24px !important;
}

/* ─── Cabeçalho de Gradiente Premium ─── */
.modulo-header-gradient {
    background: linear-gradient(135deg, #0f0c29 0%, #302b63 50%, #24243e 100%) !important;
    border-radius: 12px 12px 0 0 !important;
    border-bottom: none !important;
    padding: 20px 24px !important;
}

.modulo-header-gradient .modulo-title {
    color: #fff !important;
    font-weight: 700 !important;
    letter-spacing: -0.3px !important;
    margin: 0 !important;
    display: flex !important;
    align-items: center !important;
    gap: 12px !important;
}

.modulo-header-gradient .modulo-title i {
    background: rgba(255, 255, 255, 0.1) !important;
    padding: 8px !important;
    border-radius: 10px !important;
    color: #a8b5ff !important;
    font-size: 20px !important;
    display: inline-flex !important;
    align-items: center !important;
    justify-content: center !important;
}

.modulo-header-gradient .modulo-subtitle {
    color: rgba(255, 255, 255, 0.6) !important;
    font-weight: 400 !important;
    font-size: 13px !important;
    margin-top: 4px !important;
    margin-bottom: 0 !important;
}

.modulo-header-gradient .btn {
    border-radius: 8px;
    font-weight: 600;
    transition: all 0.2s ease;
}

.modulo-header-gradient .btn:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 14px rgba(0, 0, 0, 0.25);
}

/* ─── Formulários ─── */
.form-control, .form-select, select {
    border: 1px solid #e2e8f0 !important;
    border-radius: 10px !important;
    padding: 10px 14px !important;
    font-size: 13px !important;
    color: #334155 !important;
    transition: all 0.2s ease !important;
    box-shadow: none !important;
}

.form-control:focus, .form-select:focus, select:focus {
    border-color: #4f46e5 !important;
    box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.1) !important;
}

.form-label, label {
    font-weight: 600 !important;
    color: #475569 !important;
    font-size: 13px !important;
    margin-bottom: 6px !important;
}

/* ─── Botões ─── */
.btn {
    border-radius: 10px !important;
    font-weight: 500 !important;
    font-size: 13px !important;
    padding: 10px 20px !important;
    transition: all 0.2s ease !important;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 6px;
}

.btn-sm {
    padding: 6px 12px !important;
    font-size: 12px !important;
    border-radius: 8px !important;
}

.btn-warning {
    background-color: #f59e0b !important;
    border-color: #f59e0b !important;
    color: #fff !important;
}

.btn-warning:hover {
    background-color: #d97706 !important;
    border-color: #d97706 !important;
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(245, 158, 11, 0.25) !important;
}

.btn-outline-secondary:hover {
    transform: translateY(-1px);
}

/* ─── Seções do Formulário ─── */
.section-title {
    font-weight: 700 !important;
    font-size: 14px !important;
    color: #0f0c29 !important;
    text-transform: uppercase;
    letter-spacing: 0.4px;
    display: flex;
    align-items: center;
    gap: 8px;
    margin: 0 0 14px 0;
}

.section-title i {
    color: #4f46e5;
}

/* ─── Cartão de Resumo da Nota ─── */
.resumo-nota {
    background: linear-gradient(135deg, #f8f9fc 0%, #f1f3ff 100%) !important;
    border: 1px solid rgba(79, 70, 229, 0.12) !important;
    border-radius: 12px !important;
}

.resumo-nota .info-item {
    padding: 10px 14px;
    background: #fff;
    border: 1px solid #eef0f5;
    border-radius: 10px;
    height: 100%;
}

.resumo-nota .info-item .info-label {
    font-size: 10px !important;
    font-weight: 700 !important;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    color: #9e9eb8 !important;
    margin-bottom: 2px;
}

.resumo-nota .info-item .info-value {
    font-size: 13px !important;
    font-weight: 600 !important;
    color: #1e1e3f !important;
    word-break: break-word;
}

/* ─── Badges Modernizados (Pills) ─── */
.badge {
    padding: 6px 12px !important;
    border-radius: 9999px !important;
    font-size: 11px !important;
    font-weight: 600 !important;
    display: inline-flex;
    align-items: center;
    gap: 4px;
    box-shadow: none !important;
    border: 1px solid transparent;
}

.bg-success-subtle {
    background-color: #ecfdf5 !important;
    color: #047857 !important;
    border-color: #a7f3d0 !important;
}

.bg-danger-subtle {
    background-color: #fef2f2 !important;
    color: #b91c1c !important;
    border-color: #fecaca !important;
}

.bg-warning-subtle {
    background-color: #fffbeb !important;
    color: #b45309 !important;
    border-color: #fde68a !important;
}

.bg-info-subtle {
    background-color: #eff6ff !important;
    color: #1d4ed8 !important;
    border-color: #bfdbfe !important;
}

/* ─── Responsivo ─── */
@media (max-width: 768px) {
    .modulo-header-gradient .modulo-title { font-size: 18px; }
}
</style>
@endsection

@section('content')
<div class="mt-3 text-dark">
    <div class="row justify-content-center">
        <div class="col-lg-8 col-12">
            <div class="card border-0 shadow-sm modulo-form-card">

                <!-- ═══ CABEÇALHO PREMIUM ═══ -->
                <div class="card-header modulo-header-gradient py-3 px-4">
                    <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                        <div>
                            <h4 class="mb-1 modulo-title">
                                <i class="ri-arrow-up-down-line"></i>
                                Alterar Estado Fiscal NFCe
                            </h4>
                            <p class="modulo-subtitle">
                                Altere manualmente o estado fiscal da NFCe (aprovado, cancelado, rejeitado ou novo).
                            </p>
                        </div>
                        <div>
                            <a href="{{ route('nfce.index') }}" class="btn btn-light btn-sm px-3 text-dark">
                                <i class="ri-arrow-left-line align-middle me-1"></i> Voltar
                            </a>
                        </div>
                    </div>
                </div>

                <!-- ═══ CORPO DO FORMULÁRIO ═══ -->
                <div class="card-body p-4">

                    {!!Form::open()
                    ->put()
                    ->route('nfce.storeEstado', [$item->id])
                    ->multipart()
                    !!}

                    {{-- Aviso de cautela --}}
                    <div class="alert alert-warning border-warning-subtle bg-warning-subtle text-warning d-flex align-items-start p-3 mb-4">
                        <i class="ri-error-warning-line me-2 fs-20"></i>
                        <div class="fs-13">
                            <strong>Atenção:</strong> a alteração manual do estado não transmite nenhuma informação ao SEFAZ.
                            Utilize apenas para regularizar divergências entre o sistema e o fisco.
                        </div>
                    </div>

                    {{-- 1. Dados da NFCe --}}
                    <h5 class="section-title"><i class="ri-information-line"></i> 1. Dados da NFCe</h5>

                    <div class="resumo-nota p-3 mb-4">
                        <div class="row g-2">
                            <div class="col-md-4 col-12">
                                <div class="info-item">
                                    <div class="info-label">Cliente</div>
                                    <div class="info-value">{{ $item->cliente ? $item->cliente->razao_social : ($item->cliente_nome ?: 'Consumidor Final') }}</div>
                                </div>
                            </div>
                            <div class="col-md-3 col-6">
                                <div class="info-item">
                                    <div class="info-label">CNPJ / CPF</div>
                                    <div class="info-value">{{ $item->cliente ? $item->cliente->cpf_cnpj : ($item->cliente_cpf_cnpj ?: '--') }}</div>
                                </div>
                            </div>
                            <div class="col-md-2 col-6">
                                <div class="info-item">
                                    <div class="info-label">Data</div>
                                    <div class="info-value">{{ __data_pt($item->data_registro, 0) }}</div>
                                </div>
                            </div>
                            <div class="col-md-3 col-6">
                                <div class="info-item">
                                    <div class="info-label">Valor Total</div>
                                    <div class="info-value text-success">R$ {{ __moeda($item->total) }}</div>
                                </div>
                            </div>

                            @if($item->cliente)
                            <div class="col-md-4 col-12">
                                <div class="info-item">
                                    <div class="info-label">Cidade</div>
                                    <div class="info-value">{{ $item->cliente->cidade->nome }} ({{ $item->cliente->cidade->uf }})</div>
                                </div>
                            </div>
                            @endif
                            <div class="col-md-8 col-12">
                                <div class="info-item">
                                    <div class="info-label">Chave NFCe</div>
                                    <div class="info-value font-monospace fs-12">{{ $item->chave ?: '--' }}</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- 2. Alteração de Estado --}}
                    <h5 class="section-title"><i class="ri-settings-4-line"></i> 2. Novo Estado da NFCe</h5>

                    <div class="row g-3">
                        <div class="col-md-3 col-12">
                            <label class="form-label fw-semibold text-dark fs-13 d-block">
                                Estado Atual
                            </label>
                            @if($item->estado == 'aprovado')
                            <span class="badge bg-success-subtle text-success border border-success-subtle px-2 py-1">
                                <i class="ri-checkbox-circle-line me-1"></i>Aprovado
                            </span>
                            @elseif($item->estado == 'cancelado')
                            <span class="badge bg-danger-subtle text-danger border border-danger-subtle px-2 py-1">
                                <i class="ri-close-circle-line me-1"></i>Cancelado
                            </span>
                            @elseif($item->estado == 'rejeitado')
                            <span class="badge bg-warning-subtle text-warning border border-warning-subtle px-2 py-1">
                                <i class="ri-error-warning-line me-1"></i>Rejeitado
                            </span>
                            @else
                            <span class="badge bg-info-subtle text-info border border-info-subtle px-2 py-1">
                                <i class="ri-time-line me-1"></i>Novo
                            </span>
                            @endif
                        </div>

                        <div class="col-md-4 col-12">
                            {!!Form::select('estado_emissao', 'Novo Estado', [
                                'novo' => 'Novo',
                                'rejeitado' => 'Rejeitado',
                                'cancelado' => 'Cancelado',
                                'aprovado' => 'Aprovado'
                            ])->attrs(['class' => 'form-select'])->value(isset($item) ? $item->estado : '')!!}
                        </div>

                        <div class="col-md-5 col-12">
                            <label class="form-label fw-semibold text-dark fs-13">Arquivo XML (opcional)</label>
                            <input type="file" name="file" id="inp-file-estado" accept=".xml" class="form-control">
                            <span class="text-muted fs-12 mt-1 d-block" id="filename"></span>
                            <span class="text-muted fs-11 d-block mt-1">
                                <i class="ri-information-line me-1 align-middle"></i>
                                Ao anexar o XML, a chave e o número da nota são atualizados automaticamente.
                            </span>
                        </div>
                    </div>

                    {{-- Botões de Ação --}}
                    <div class="modulo-actions mt-4">
                        <hr class="text-muted opacity-25">
                        <div class="d-flex gap-2 justify-content-end">
                            <a href="{{ route('nfce.index') }}" class="btn btn-outline-secondary">
                                <i class="ri-close-line align-middle me-1"></i> Cancelar
                            </a>
                            <button type="submit" class="btn btn-warning px-4 fw-bold">
                                <i class="ri-save-line me-1 align-middle"></i> Salvar Alteração
                            </button>
                        </div>
                    </div>

                    {!!Form::close()!!}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('js')
<script type="text/javascript">
    $('#inp-file-estado').on('change', function () {
        var nome = this.files.length ? this.files[0].name : '';
        $('#filename').text(nome ? 'Arquivo selecionado: ' + nome : '').removeClass('text-danger');
    });
</script>
@endsection
