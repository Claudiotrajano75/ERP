@extends('layouts.app', ['title' => 'Central de Faturamento (NF-e)'])

@section('css')
<style>
/* =====================================================
   CENTRAL DE FATURAMENTO (NF-e) - PREMIUM DESIGN
   ===================================================== */
.cf-wrap {
  font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
  color: #1e293b;
  min-height: calc(100vh - 170px);
  display: flex;
  flex-direction: column;
}

/* CABEÇALHO */
.cf-page-header {
  display: flex;
  align-items: center;
  justify-content: space-between;
  flex-wrap: wrap;
  gap: 10px;
  margin-bottom: 14px;
}
.cf-page-title {
  font-size: 18px;
  font-weight: 800;
  color: #0f172a;
  margin: 0 0 2px;
  letter-spacing: -0.3px;
}
.cf-page-subtitle {
  font-size: 11.5px;
  color: #64748b;
  margin: 0;
}
.cf-header-actions {
  display: flex;
  gap: 8px;
  align-items: center;
}
.btn-cf-xml {
  background: #ffffff;
  border: 1px solid #c7d2fe;
  color: #4338ca;
  font-weight: 600;
  font-size: 12px;
  border-radius: 7px;
  padding: 6px 13px;
  display: inline-flex;
  align-items: center;
  gap: 6px;
  transition: all 0.15s ease;
  cursor: pointer;
  text-decoration: none;
}
.btn-cf-xml:hover {
  background: #f5f7ff;
  border-color: #3b82f6;
  color: #2563eb;
}
.btn-cf-lote {
  background: #10b981;
  border: none;
  color: #ffffff;
  font-weight: 700;
  font-size: 12px;
  border-radius: 7px;
  padding: 6px 14px;
  display: inline-flex;
  align-items: center;
  gap: 6px;
  transition: all 0.15s ease;
  cursor: pointer;
  box-shadow: 0 1px 3px rgba(16, 185, 129, 0.25);
  text-decoration: none;
}
.btn-cf-lote:hover {
  background: #059669;
  color: #ffffff;
  box-shadow: 0 2px 6px rgba(16, 185, 129, 0.35);
}

/* KPI CARDS (6 colunas compactas) */
.cf-kpi-grid {
  display: grid;
  grid-template-columns: repeat(6, 1fr);
  gap: 10px;
  margin-bottom: 14px;
}
@media (max-width: 1200px) {
  .cf-kpi-grid { grid-template-columns: repeat(3, 1fr); }
}
@media (max-width: 768px) {
  .cf-kpi-grid { grid-template-columns: repeat(2, 1fr); }
}
@media (max-width: 480px) {
  .cf-kpi-grid { grid-template-columns: 1fr; }
}
.cf-kpi-card {
  background: #ffffff;
  border: 1px solid #f1f5f9;
  border-radius: 10px;
  padding: 10px 12px;
  display: flex;
  align-items: center;
  gap: 10px;
  box-shadow: 0 1px 3px rgba(0, 0, 0, 0.03);
  transition: transform 0.15s ease, box-shadow 0.15s ease;
}
.cf-kpi-card:hover {
  transform: translateY(-1px);
  box-shadow: 0 4px 10px rgba(0, 0, 0, 0.05);
}
.cf-kpi-icon {
  width: 36px;
  height: 36px;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 16px;
  flex-shrink: 0;
  color: #ffffff;
}
.kpi-bg-blue   { background: #2563eb; }
.kpi-bg-green  { background: #10b981; }
.kpi-bg-purple { background: #8b5cf6; }
.kpi-bg-red    { background: #ef4444; }
.kpi-bg-cyan   { background: #06b6d4; }
.kpi-bg-orange { background: #f59e0b; }

.cf-kpi-body {
  flex: 1;
  min-width: 0;
}
.cf-kpi-count {
  font-size: 19px;
  font-weight: 800;
  line-height: 1.1;
  letter-spacing: -0.3px;
  display: flex;
  align-items: center;
  gap: 5px;
}
.cf-kpi-count.c-blue   { color: #2563eb; }
.cf-kpi-count.c-green  { color: #10b981; }
.cf-kpi-count.c-purple { color: #8b5cf6; }
.cf-kpi-count.c-red    { color: #ef4444; }
.cf-kpi-count.c-cyan   { color: #0891b2; font-size: 15px; }
.cf-kpi-count.c-orange { color: #d97706; }

.cf-kpi-badge-novo {
  background: #fef08a;
  color: #854d0e;
  font-size: 8px;
  font-weight: 800;
  border-radius: 3px;
  padding: 1px 4px;
  letter-spacing: 0.3px;
}
.cf-kpi-label {
  font-size: 10.5px;
  font-weight: 600;
  color: #64748b;
  margin: 1px 0;
  white-space: nowrap;
  overflow: hidden;
  text-overflow: ellipsis;
}
.cf-kpi-value {
  font-size: 10.5px;
  font-weight: 700;
  color: #2563eb;
  line-height: 1;
}

/* PAINEL DE FILTROS */
.cf-filter-panel {
  background: #ffffff;
  border: 1px solid #f1f5f9;
  border-radius: 10px;
  padding: 12px 14px;
  margin-bottom: 14px;
  box-shadow: 0 1px 3px rgba(0, 0, 0, 0.03);
}
.cf-filter-grid {
  display: flex;
  flex-wrap: wrap;
  align-items: flex-end;
  gap: 8px;
}
.cf-filter-item {
  display: flex;
  flex-direction: column;
}
.cf-filter-item.item-cliente { flex: 2 1 180px; }
.cf-filter-item.item-data    { flex: 1 1 115px; }
.cf-filter-item.item-select  { flex: 1 1 125px; }
.cf-filter-item.item-actions {
  flex: 0 0 auto;
  display: flex;
  flex-direction: row;
  gap: 6px;
}

.cf-filter-item label {
  font-size: 10.5px;
  font-weight: 700;
  color: #475569;
  margin-bottom: 4px;
  display: flex;
  align-items: center;
  gap: 4px;
}
.cf-filter-item label i {
  font-size: 12px;
  color: #94a3b8;
}
.cf-filter-panel .form-control,
.cf-filter-panel .form-select {
  height: 33px;
  border-radius: 7px;
  border: 1px solid #cbd5e1;
  font-size: 11.5px;
  color: #334155;
  background-color: #ffffff;
  padding: 4px 8px;
}
.cf-filter-panel .form-control:focus,
.cf-filter-panel .form-select:focus {
  border-color: #3b82f6;
  box-shadow: 0 0 0 2px rgba(59, 130, 246, 0.15);
}
.btn-cf-pesquisar {
  background: #3b82f6;
  border: none;
  color: #ffffff;
  font-weight: 700;
  height: 33px;
  border-radius: 7px;
  font-size: 11.5px;
  display: inline-flex;
  align-items: center;
  justify-content: center;
  gap: 5px;
  padding: 0 14px;
  cursor: pointer;
  transition: all 0.15s ease;
  white-space: nowrap;
}
.btn-cf-pesquisar:hover {
  background: #2563eb;
  color: #ffffff;
}
.btn-cf-limpar {
  background: #ffffff;
  border: 1px solid #cbd5e1;
  color: #64748b;
  font-weight: 600;
  height: 33px;
  border-radius: 7px;
  font-size: 11.5px;
  display: inline-flex;
  align-items: center;
  justify-content: center;
  gap: 4px;
  padding: 0 10px;
  text-decoration: none;
  cursor: pointer;
  transition: all 0.15s ease;
  white-space: nowrap;
}
.btn-cf-limpar:hover {
  background: #f8fafc;
  color: #334155;
  border-color: #94a3b8;
}

/* SUB-ABAS DE STATUS */
.cf-tabs {
  display: flex;
  gap: 2px;
  border-bottom: 2px solid #e2e8f0;
  margin-bottom: 0;
  overflow-x: auto;
}
.cf-tab-btn {
  background: transparent;
  border: none;
  border-bottom: 2px solid transparent;
  margin-bottom: -2px;
  padding: 8px 12px;
  font-size: 11.5px;
  font-weight: 600;
  color: #64748b;
  display: inline-flex;
  align-items: center;
  gap: 5px;
  white-space: nowrap;
  cursor: pointer;
  text-decoration: none;
  transition: all 0.15s ease;
}
.cf-tab-btn:hover {
  color: #2563eb;
  background: #f8fafc;
}
.cf-tab-btn.active {
  color: #2563eb;
  border-bottom-color: #2563eb;
  font-weight: 700;
}
.cf-tab-badge {
  background: #e0e7ff;
  color: #4338ca;
  font-size: 9.5px;
  font-weight: 800;
  border-radius: 12px;
  padding: 1px 6px;
  min-width: 18px;
  text-align: center;
}
.cf-tab-btn.active .cf-tab-badge {
  background: #2563eb;
  color: #ffffff;
}

/* BARRA FLUTUANTE DE AÇÃO EM LOTE */
.cf-bulk-action-bar {
  background: #0f172a;
  color: #ffffff;
  padding: 8px 16px;
  border-radius: 8px;
  margin-bottom: 10px;
  display: flex;
  align-items: center;
  justify-content: space-between;
  box-shadow: 0 4px 12px rgba(15, 23, 42, 0.15);
}

/* CONTAINER EXPANDIDO DA TABELA ATÉ O RODAPÉ */
.cf-table-wrap {
  border: 1px solid #f1f5f9;
  border-top: none;
  border-radius: 0 0 10px 10px;
  background: #ffffff;
  min-height: 520px;
  box-shadow: 0 1px 3px rgba(0, 0, 0, 0.02);
  display: flex;
  flex-direction: column;
  justify-content: space-between;
  margin-bottom: 20px;
  flex: 1;
}
.cf-table-responsive {
  min-height: 460px;
  padding-bottom: 140px;
  overflow-x: auto;
  overflow-y: visible !important;
}
.cf-table-wrap thead th {
  background: #ffffff;
  color: #64748b;
  font-weight: 700;
  font-size: 10px;
  text-transform: uppercase;
  letter-spacing: 0.3px;
  padding: 10px 12px;
  border-bottom: 1px solid #e2e8f0;
  white-space: nowrap;
}
.cf-table-wrap tbody td {
  padding: 10px 12px;
  vertical-align: middle;
  border-bottom: 1px solid #f1f5f9;
  font-size: 11.5px;
}
.cf-table-wrap tbody tr:hover {
  background: #f8fafc;
}
.cf-table-wrap tbody tr:last-child td {
  border-bottom: 1px solid #f1f5f9;
}

.cf-badge-tag-novo {
  border: 1px solid #ef4444;
  color: #ef4444;
  background: #ffffff;
  font-size: 8.5px;
  font-weight: 800;
  border-radius: 3px;
  padding: 1px 4px;
  letter-spacing: 0.3px;
  display: inline-block;
}
.cf-dot-done {
  width: 14px;
  height: 14px;
  border-radius: 50%;
  background: #10b981;
  color: #ffffff;
  display: inline-flex;
  align-items: center;
  justify-content: center;
  font-size: 8px;
}
.cf-pedido-id {
  font-weight: 600;
  color: #334155;
  font-size: 11.5px;
}
.cf-cliente-title {
  font-weight: 700;
  color: #0f172a;
  font-size: 11.5px;
  line-height: 1.25;
}
.cf-cliente-doc {
  font-size: 10px;
  color: #94a3b8;
  margin-top: 1px;
}
.cf-valor-txt {
  font-weight: 700;
  color: #059669;
  font-size: 12px;
  white-space: nowrap;
}
.cf-data-txt {
  font-size: 11px;
  font-weight: 600;
  color: #334155;
  line-height: 1.2;
}
.cf-hora-txt {
  font-size: 10px;
  color: #2563eb;
  font-weight: 600;
}

/* STEPPER COMPACTO */
.cf-stepper-box {
  display: flex;
  align-items: center;
  min-width: 250px;
}
.cf-step-item {
  display: flex;
  flex-direction: column;
  align-items: center;
  gap: 2px;
  flex: 1;
}
.cf-step-circle {
  width: 22px;
  height: 22px;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 10px;
  color: #ffffff;
}
.step-c-blue   { background: #2563eb; }
.step-c-purple { background: #8b5cf6; }
.step-c-green  { background: #10b981; }
.step-c-gray   { background: #ffffff; border: 1.5px solid #cbd5e1; color: #94a3b8; }

.cf-step-name {
  font-size: 8.5px;
  font-weight: 700;
  color: #475569;
  text-transform: capitalize;
  line-height: 1;
}
.cf-step-time {
  font-size: 8px;
  color: #94a3b8;
  white-space: nowrap;
  line-height: 1;
}
.cf-step-line {
  flex: 1;
  height: 1.5px;
  background: #e2e8f0;
  margin: 0 2px;
  margin-bottom: 14px;
  min-width: 12px;
}
.cf-step-line.line-ok {
  background: #86efac;
}

/* BADGES FISCAIS */
.cf-fiscal-col {
  display: flex;
  flex-direction: column;
  align-items: flex-start;
  gap: 2px;
}
.cf-badge-fiscal {
  display: inline-block;
  padding: 2px 7px;
  border-radius: 4px;
  font-size: 10px;
  font-weight: 700;
  letter-spacing: 0.2px;
}
.badge-st-pendente   { background: #fef3c7; color: #d97706; }
.badge-st-autorizada { background: #d1fae5; color: #059669; }
.badge-st-rejeitada  { background: #fee2e2; color: #dc2626; }
.badge-st-cancelada  { background: #f1f5f9; color: #64748b; }
.cf-fiscal-detail    { font-size: 9.5px; color: #64748b; padding-left: 1px; }

/* BOTÕES DE AÇÃO */
.btn-act-faturar {
  background: #059669;
  color: #ffffff !important;
  border: none;
  border-radius: 5px;
  font-size: 11px;
  font-weight: 700;
  padding: 4px 10px;
  display: inline-flex;
  align-items: center;
  gap: 4px;
  white-space: nowrap;
  cursor: pointer;
  transition: all 0.15s ease;
  text-decoration: none;
}
.btn-act-faturar:hover {
  background: #047857;
  color: #ffffff !important;
  box-shadow: 0 2px 6px rgba(5, 150, 105, 0.3);
}
.btn-act-danfe {
  background: #ffffff;
  color: #4338ca !important;
  border: 1px solid #cbd5e1;
  border-radius: 5px;
  font-size: 11px;
  font-weight: 600;
  padding: 3px 9px;
  display: inline-flex;
  align-items: center;
  gap: 4px;
  white-space: nowrap;
  cursor: pointer;
  transition: all 0.15s ease;
  text-decoration: none;
}
.btn-act-danfe:hover {
  background: #f8fafc;
  border-color: #3b82f6;
  color: #2563eb !important;
}
.btn-act-more {
  width: 26px;
  height: 26px;
  border-radius: 50%;
  border: 1px solid #e2e8f0;
  background: #ffffff;
  color: #64748b;
  display: inline-flex;
  align-items: center;
  justify-content: center;
  font-size: 14px;
  cursor: pointer;
  transition: all 0.15s ease;
}
.btn-act-more:hover {
  background: #f1f5f9;
  color: #1e293b;
}

/* DROPDOWN MENU */
.cf-dropdown-menu {
  z-index: 1060 !important;
  min-width: 195px;
  border-radius: 8px;
  padding: 5px;
  font-size: 12px;
  border: 1px solid #e2e8f0;
  box-shadow: 0 10px 25px rgba(0, 0, 0, 0.12) !important;
}

/* ESTILOS DOS MODAIS FIDEDIGNOS */
.modal-nfe-card-dados {
  background: #ffffff;
  border: 1px solid #eef2f6;
  border-radius: 10px;
  padding: 14px 16px;
  margin-bottom: 14px;
}
.modal-nfe-card-title {
  font-size: 11px;
  font-weight: 700;
  color: #475569;
  margin-bottom: 10px;
  display: block;
}
.modal-field-label {
  font-size: 10.5px;
  color: #94a3b8;
  margin-bottom: 2px;
  font-weight: 500;
}
.modal-field-val {
  font-size: 12px;
  font-weight: 700;
  color: #0f172a;
  word-break: break-all;
}

.alert-cce-info {
  background: #eef2ff;
  border: 1px solid #e0e7ff;
  border-radius: 10px;
  padding: 12px 14px;
  margin-bottom: 14px;
  display: flex;
  align-items: flex-start;
  gap: 10px;
}
.alert-cce-info i {
  color: #4f46e5;
  font-size: 18px;
  margin-top: 1px;
  flex-shrink: 0;
}
.alert-cce-info-title {
  font-size: 11.5px;
  font-weight: 700;
  color: #312e81;
  margin-bottom: 3px;
}
.alert-cce-info-sub {
  font-size: 10.5px;
  color: #6366f1;
  margin: 0;
}

.alert-cce-warning {
  background: #fefce8;
  border: 1px solid #fef08a;
  border-radius: 10px;
  padding: 12px 14px;
  margin-top: 14px;
}
.alert-cce-warning-title {
  font-size: 11.5px;
  font-weight: 700;
  color: #854d0e;
  margin-bottom: 6px;
  display: flex;
  align-items: center;
  gap: 5px;
}
.alert-cce-warning ul {
  list-style: none;
  padding-left: 0;
  margin-bottom: 0;
}
.alert-cce-warning ul li {
  font-size: 11px;
  color: #a16207;
  margin-bottom: 3px;
  display: flex;
  align-items: center;
  gap: 6px;
}
.alert-cce-warning ul li::before {
  content: "•";
  color: #eab308;
  font-size: 14px;
  font-weight: bold;
}

/* MODAL CANCELAMENTO ESTILOS */
.modal-header-cancelar {
  background: #ef4444;
  color: #ffffff;
  padding: 14px 18px;
}
.modal-header-cancelar .modal-title {
  color: #ffffff;
  font-size: 15px;
  font-weight: 700;
}
.modal-header-cancelar .modal-subtitle {
  color: rgba(255, 255, 255, 0.9);
  font-size: 11px;
  margin: 0;
}
.modal-header-cancelar .btn-close {
  filter: brightness(0) invert(1);
}
.alert-cancela-danger {
  background: #fef2f2;
  border: 1px solid #fee2e2;
  border-radius: 10px;
  padding: 12px 14px;
  margin-bottom: 14px;
  display: flex;
  align-items: flex-start;
  gap: 10px;
}
.alert-cancela-danger i {
  color: #dc2626;
  font-size: 18px;
  margin-top: 1px;
  flex-shrink: 0;
}
.alert-cancela-danger-title {
  font-size: 11.5px;
  font-weight: 700;
  color: #991b1b;
  margin-bottom: 3px;
}
.alert-cancela-danger-sub {
  font-size: 10.5px;
  color: #b91c1c;
  margin: 0;
}

.cf-empty-state {
  padding: 60px 15px;
  text-align: center;
}
.cf-empty-state i {
  font-size: 44px;
  color: #cbd5e1;
  margin-bottom: 8px;
  display: block;
}
.cf-empty-state p {
  color: #94a3b8;
  font-size: 13px;
  margin: 0;
}

.cf-pagination-wrap {
  padding: 12px 14px;
  background: #ffffff;
  border-top: 1px solid #f1f5f9;
  display: flex;
  align-items: center;
  justify-content: space-between;
  flex-wrap: wrap;
  gap: 10px;
  margin-top: auto;
}
</style>
@endsection

@section('content')
<div class="mt-3 text-dark">
  <div class="row">
    <div class="card border-0 shadow-sm text-dark">

      <!-- ═══ Cabeçalho Premium ═══ -->
      <div class="card-header modulo-header-gradient py-3 px-4">
        <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
          <div>
            <h4 class="mb-1 modulo-title d-flex align-items-center gap-2">
              <i class="ri-file-list-3-line"></i>
              Central de Faturamento (NF-e)
            </h4>
            <p class="text-muted mb-0 modulo-subtitle fs-13">Pedidos aprovados financeiramente, prontos para faturamento.</p>
          </div>
          <div class="d-inline-flex gap-2">
            <button type="button" class="dash-btn dash-btn-light" data-bs-toggle="modal" data-bs-target="#modal-xmls-mes">
              <i class="ri-download-line align-middle me-1"></i> XMLs do mês
            </button>
            <button type="button" class="dash-btn dash-btn-primary" data-bs-toggle="modal" data-bs-target="#modal-faturamento-lote">
              <i class="ri-add-line"></i> Novo Faturamento em Lote
            </button>
          </div>
        </div>
      </div>

      <div class="card-body p-4">
        <div class="cf-wrap">

  {{-- 6 CARDS DE KPI DINÂMICOS (TEMPO REAL) --}}
  <div class="cf-kpi-grid">
    {{-- Card 1: Pedidos Pendentes --}}
    <div class="cf-kpi-card">
      <div class="cf-kpi-icon kpi-bg-blue"><i class="ri-file-text-line"></i></div>
      <div class="cf-kpi-body">
        <div class="cf-kpi-count c-blue">
          {{ $kpis['pedidos_pendentes']['qtd'] }}
          @if($kpis['pedidos_pendentes']['qtd'] > 0)
            <span class="cf-kpi-badge-novo">NOVO</span>
          @endif
        </div>
        <div class="cf-kpi-label">Pedidos Pendentes</div>
        <div class="cf-kpi-value">R$ {{ number_format($kpis['pedidos_pendentes']['valor'], 2, ',', '.') }}</div>
      </div>
    </div>

    {{-- Card 2: Prontos para Faturar --}}
    <div class="cf-kpi-card">
      <div class="cf-kpi-icon kpi-bg-green"><i class="ri-check-line"></i></div>
      <div class="cf-kpi-body">
        <div class="cf-kpi-count c-green">{{ $kpis['prontos_faturar']['qtd'] }}</div>
        <div class="cf-kpi-label">Prontos para Faturar</div>
        <div class="cf-kpi-value">R$ {{ number_format($kpis['prontos_faturar']['valor'], 2, ',', '.') }}</div>
      </div>
    </div>

    {{-- Card 3: NFes Emitidas Hoje --}}
    <div class="cf-kpi-card">
      <div class="cf-kpi-icon kpi-bg-purple"><i class="ri-receipt-line"></i></div>
      <div class="cf-kpi-body">
        <div class="cf-kpi-count c-purple">{{ $kpis['nfes_emitidas_hoje']['qtd'] }}</div>
        <div class="cf-kpi-label">NFes Emitidas Hoje</div>
        <div class="cf-kpi-value">R$ {{ number_format($kpis['nfes_emitidas_hoje']['valor'], 2, ',', '.') }}</div>
      </div>
    </div>

    {{-- Card 4: NFes Canceladas --}}
    <div class="cf-kpi-card">
      <div class="cf-kpi-icon kpi-bg-red"><i class="ri-close-line"></i></div>
      <div class="cf-kpi-body">
        <div class="cf-kpi-count c-red">{{ $kpis['nfes_canceladas']['qtd'] }}</div>
        <div class="cf-kpi-label">NFes Canceladas</div>
        <div class="cf-kpi-value">R$ {{ number_format($kpis['nfes_canceladas']['valor'], 2, ',', '.') }}</div>
      </div>
    </div>

    {{-- Card 5: Valor Faturado Hoje --}}
    <div class="cf-kpi-card">
      <div class="cf-kpi-icon kpi-bg-cyan"><i class="ri-money-dollar-circle-line"></i></div>
      <div class="cf-kpi-body">
        <div class="cf-kpi-count c-cyan">R$ {{ number_format($kpis['valor_faturado_hoje']['valor'], 2, ',', '.') }}</div>
        <div class="cf-kpi-label">Valor Faturado Hoje</div>
      </div>
    </div>

    {{-- Card 6: Aguardando SEFAZ --}}
    <div class="cf-kpi-card">
      <div class="cf-kpi-icon kpi-bg-orange"><i class="ri-hourglass-line"></i></div>
      <div class="cf-kpi-body">
        <div class="cf-kpi-count c-orange">{{ $kpis['aguardando_sefaz']['qtd'] }}</div>
        <div class="cf-kpi-label">Aguardando SEFAZ</div>
        <div class="cf-kpi-value">R$ {{ number_format($kpis['aguardando_sefaz']['valor'], 2, ',', '.') }}</div>
      </div>
    </div>
  </div>

  {{-- PAINEL DE FILTROS TOTALMENTE CONTIDO --}}
  <div class="cf-filter-panel">
    <form method="get" action="{{ route('faturamento-nfe.index') }}">
      <input type="hidden" name="tab" value="{{ $activeTab }}">
      <div class="cf-filter-grid">
        <div class="cf-filter-item item-cliente">
          <label><i class="ri-user-line"></i> Cliente</label>
          <input type="text" name="cliente" class="form-control" placeholder="Digite para buscar o cliente" value="{{ request('cliente') }}">
        </div>
        <div class="cf-filter-item item-data">
          <label><i class="ri-calendar-line"></i> Data inicial</label>
          <input type="date" name="start_date" class="form-control" value="{{ request('start_date') }}">
        </div>
        <div class="cf-filter-item item-data">
          <label><i class="ri-calendar-check-line"></i> Data final</label>
          <input type="date" name="end_date" class="form-control" value="{{ request('end_date') }}">
        </div>
        <div class="cf-filter-item item-select">
          <label><i class="ri-equalizer-line"></i> Estado Faturamento</label>
          <select name="estado_faturamento" class="form-select">
            <option value="">Todos</option>
            <option value="pendente" @selected(request('estado_faturamento')=='pendente')>Pendente</option>
            <option value="faturado" @selected(request('estado_faturamento')=='faturado')>Faturado</option>
          </select>
        </div>
        <div class="cf-filter-item item-select">
          <label><i class="ri-shield-check-line"></i> Situação Fiscal</label>
          <select name="situacao_fiscal" class="form-select">
            <option value="">Todos</option>
            <option value="pendente" @selected(request('situacao_fiscal')=='pendente')>Pendente</option>
            <option value="autorizada" @selected(request('situacao_fiscal')=='autorizada')>Autorizada</option>
            <option value="rejeitada" @selected(request('situacao_fiscal')=='rejeitada')>Rejeitada</option>
            <option value="cancelada" @selected(request('situacao_fiscal')=='cancelada')>Cancelada</option>
          </select>
        </div>
        <div class="cf-filter-item item-actions">
          <button type="submit" class="btn-cf-pesquisar"><i class="ri-search-line"></i> Pesquisar</button>
          <a href="{{ route('faturamento-nfe.index', ['tab' => $activeTab]) }}" class="btn-cf-limpar"><i class="ri-filter-off-line"></i> Limpar</a>
        </div>
      </div>
    </form>
  </div>

  {{-- SUB-ABAS DE STATUS --}}
  <div>
    <div class="cf-tabs">
      <a href="{{ route('faturamento-nfe.index', array_merge(request()->query(),['tab'=>'pendentes'])) }}" class="cf-tab-btn {{ $activeTab == 'pendentes' ? 'active' : '' }}">
        <i class="ri-lock-line"></i> Pendentes de Faturamento <span class="cf-tab-badge">{{ $tabs['pendentes'] }}</span>
      </a>
      <a href="{{ route('faturamento-nfe.index', array_merge(request()->query(),['tab'=>'emitidas'])) }}" class="cf-tab-btn {{ $activeTab == 'emitidas' ? 'active' : '' }}">
        <i class="ri-file-text-line"></i> NFes Emitidas <span class="cf-tab-badge">{{ $tabs['emitidas'] }}</span>
      </a>
      <a href="{{ route('faturamento-nfe.index', array_merge(request()->query(),['tab'=>'rejeicoes'])) }}" class="cf-tab-btn {{ $activeTab == 'rejeicoes' ? 'active' : '' }}">
        <i class="ri-alert-line"></i> Rejeições SEFAZ <span class="cf-tab-badge">{{ $tabs['rejeicoes'] }}</span>
      </a>
      <a href="{{ route('faturamento-nfe.index', array_merge(request()->query(),['tab'=>'canceladas'])) }}" class="cf-tab-btn {{ $activeTab == 'canceladas' ? 'active' : '' }}">
        <i class="ri-checkbox-circle-line"></i> Canceladas <span class="cf-tab-badge">{{ $tabs['canceladas'] }}</span>
      </a>
      <a href="{{ route('faturamento-nfe.index', array_merge(request()->query(),['tab'=>'correcao'])) }}" class="cf-tab-btn {{ $activeTab == 'correcao' ? 'active' : '' }}">
        <i class="ri-file-edit-line"></i> Carta de Correção <span class="cf-tab-badge">{{ $tabs['correcao'] }}</span>
      </a>
    </div>

    {{-- BARRA FLUTUANTE DE AÇÃO EM LOTE --}}
    <div id="bulk-action-bar" class="cf-bulk-action-bar d-none">
      <div class="d-flex align-items-center gap-2">
        <i class="ri-checkbox-multiple-line text-success fs-16"></i>
        <span><strong id="selected-count">0</strong> pedido(s) selecionado(s)</span>
      </div>
      <div class="d-flex align-items-center gap-2">
        <button type="button" class="btn btn-sm btn-outline-light py-1" onclick="desmarcarTodosLote()">Cancelar</button>
        <button type="button" class="btn btn-sm btn-success py-1 fw-bold" onclick="executarFaturamentoLoteSelecionados()">
          <i class="ri-send-plane-fill me-1"></i> Faturar Selecionados em Lote
        </button>
      </div>
    </div>

    {{-- TABELA EXPANDIDA ATÉ O RODAPÉ --}}
    <div class="cf-table-wrap">
      <div class="cf-table-responsive">
        <table class="table align-middle mb-0">
          <thead>
            <tr>
              <th style="width:46px;">
                @if($activeTab == 'pendentes' || $activeTab == 'rejeicoes')
                  <input type="checkbox" id="check-select-all" title="Selecionar Todos para Faturamento em Lote" onchange="toggleSelectAllLote(this)">
                @else
                  #
                @endif
              </th>
              <th style="width:70px;">PEDIDO</th>
              <th>CLIENTE</th>
              <th style="width:95px;">VALOR</th>
              <th style="width:110px;">DATA PEDIDO</th>
              <th style="min-width:255px;">FLUXO DO FATURAMENTO</th>
              <th style="width:135px;">SITUAÇÃO FISCAL</th>
              <th style="width:135px;">PRÓXIMA AÇÃO</th>
              <th style="width:42px; text-align:center;">AÇÕES</th>
            </tr>
          </thead>
          <tbody>
            @forelse($pedidos as $pedido)
            @php
              $isNovo = ($pedido->estado == 'novo');
              $isAprovado = ($pedido->estado == 'aprovado');
              $isRejeitado = ($pedido->estado == 'rejeitado');
              $isCancelado = ($pedido->estado == 'cancelado');
              
              $clienteNome = $pedido->cliente ? $pedido->cliente->razao_social : ($pedido->emissor_nome ?? 'Consumidor Final');
              $clienteDoc = $pedido->cliente ? $pedido->cliente->cpf_cnpj : ($pedido->emissor_cpf_cnpj ?? '--');
              $dataEmissaoFormatada = $pedido->created_at ? $pedido->created_at->format('d/m/Y H:i') : '--';
              $chaveNota = $pedido->chave ?? '--';
              $serieNota = $pedido->numero_serie ?? '1';
              $numeroNota = $pedido->numero ?? $pedido->id;
            @endphp
            <tr>
              <td>
                @if($isNovo || $isRejeitado)
                  <div class="d-flex align-items-center gap-1">
                    <input type="checkbox" class="check-lote-item form-check-input" value="{{ $pedido->id }}" onchange="updateLoteCounter()">
                    @if($isNovo)
                      <span class="cf-badge-tag-novo">NOVO</span>
                    @endif
                  </div>
                @else
                  <span class="cf-dot-done"><i class="ri-check-line"></i></span>
                @endif
              </td>
              <td><span class="cf-pedido-id">{{ $numeroNota }}</span></td>
              <td>
                <div class="cf-cliente-title">{{ Str::limit($clienteNome, 40) }}</div>
                <div class="cf-cliente-doc">{{ $clienteDoc }}</div>
              </td>
              <td><span class="cf-valor-txt">R$ {{ number_format($pedido->total, 2, ',', '.') }}</span></td>
              <td>
                <div class="cf-data-txt">{{ $pedido->created_at ? $pedido->created_at->format('d/m/Y') : '--' }}</div>
                <div class="cf-hora-txt">{{ $pedido->created_at ? $pedido->created_at->format('H:i') : '--' }}</div>
              </td>
              <td>
                <div class="cf-stepper-box">
                  {{-- Etapa 1: Pedido --}}
                  <div class="cf-step-item">
                    <div class="cf-step-circle step-c-blue">
                      <i class="ri-file-text-line"></i>
                    </div>
                    <div class="cf-step-name">Pedido</div>
                    <div class="cf-step-time">{{ $pedido->created_at ? $pedido->created_at->format('d/m H:i') : '--' }}</div>
                  </div>
                  <div class="cf-step-line line-ok"></div>

                  {{-- Etapa 2: Fiscal --}}
                  <div class="cf-step-item">
                    <div class="cf-step-circle step-c-purple">
                      <i class="ri-file-shield-line"></i>
                    </div>
                    <div class="cf-step-name">Fiscal</div>
                    <div class="cf-step-time">{{ $pedido->created_at ? $pedido->created_at->format('d/m H:i') : '--' }}</div>
                  </div>
                  <div class="cf-step-line {{ $isAprovado ? 'line-ok' : '' }}"></div>

                  {{-- Etapa 3: NF-e --}}
                  <div class="cf-step-item">
                    <div class="cf-step-circle {{ $isAprovado ? 'step-c-green' : ($isRejeitado ? 'kpi-bg-red' : ($isCancelado ? 'kpi-bg-red' : 'step-c-gray')) }}">
                      <i class="{{ $isAprovado ? 'ri-check-line' : 'ri-file-list-3-line' }}"></i>
                    </div>
                    <div class="cf-step-name">NF-e</div>
                    <div class="cf-step-time">
                      @if($isAprovado)
                        Autorizada
                      @elseif($isRejeitado)
                        Rejeitada
                      @elseif($isCancelado)
                        Cancelada
                      @else
                        --
                      @endif
                    </div>
                  </div>
                  <div class="cf-step-line {{ $isAprovado ? 'line-ok' : '' }}"></div>

                  {{-- Etapa 4: Enviado --}}
                  <div class="cf-step-item">
                    <div class="cf-step-circle {{ $isAprovado ? 'step-c-green' : 'step-c-gray' }}">
                      <i class="{{ $isAprovado ? 'ri-send-plane-fill' : 'ri-send-plane-line' }}"></i>
                    </div>
                    <div class="cf-step-name">Enviado</div>
                    <div class="cf-step-time">{{ $isAprovado ? 'SEFAZ OK' : '--' }}</div>
                  </div>
                </div>
              </td>
              <td>
                <div class="cf-fiscal-col">
                  @if($isNovo)
                    <span class="cf-badge-fiscal badge-st-pendente">Pendente</span>
                    <span class="cf-fiscal-detail">Financeiro Aprovado</span>
                  @elseif($isAprovado)
                    <span class="cf-badge-fiscal badge-st-autorizada">Autorizada</span>
                    <span class="cf-fiscal-detail">NF-e: {{ $numeroNota }}</span>
                  @elseif($isRejeitado)
                    <span class="cf-badge-fiscal badge-st-rejeitada" style="cursor:pointer;" onclick="info('{{ addslashes($pedido->motivo_rejeicao) }}', '{{ $pedido->chave }}', 'rejeitado', '{{ $pedido->recibo }}')">Rejeitada <i class="ri-information-line"></i></span>
                    <span class="cf-fiscal-detail" title="{{ $pedido->motivo_rejeicao }}">{{ Str::limit($pedido->motivo_rejeicao ?? 'Erro SEFAZ', 18) }}</span>
                  @elseif($isCancelado)
                    <span class="cf-badge-fiscal badge-st-cancelada">Cancelada</span>
                    <span class="cf-fiscal-detail">NF-e Cancelada</span>
                  @else
                    <span class="cf-badge-fiscal badge-st-pendente">{{ ucfirst($pedido->estado) }}</span>
                  @endif
                </div>
              </td>
              <td>
                @if($isNovo || $isRejeitado)
                  <button type="button" class="btn-act-faturar" onclick="transmitir('{{ $pedido->id }}')" title="Transmitir NF-e para a SEFAZ">
                    <i class="ri-send-plane-fill"></i> Faturar Pedido
                  </button>
                @elseif($isAprovado)
                  <button type="button" class="btn-act-danfe" onclick="imprimir('{{ $pedido->id }}', '{{ $numeroNota }}')" title="Escolher modelo para impressão">
                    <i class="ri-printer-line"></i> Imprimir DANFE
                  </button>
                @else
                  <a target="_blank" href="{{ route('nfe.imprimirVenda', $pedido->id) }}" class="btn-act-danfe">
                    <i class="ri-file-text-line"></i> Ver Pedido
                  </a>
                @endif
              </td>
              <td style="text-align: center; position: relative;">
                <div class="dropdown d-inline-block">
                  <button type="button" class="btn-act-more" data-bs-toggle="dropdown" data-bs-display="static" aria-expanded="false">
                    <i class="ri-more-2-fill"></i>
                  </button>
                  <ul class="dropdown-menu dropdown-menu-end cf-dropdown-menu">
                    {{-- Ver Detalhes --}}
                    <li>
                      <a class="dropdown-item d-flex align-items-center gap-2 py-1" href="{{ route('nfe.show', $pedido->id) }}">
                        <i class="ri-eye-line text-primary"></i> Ver Detalhes
                      </a>
                    </li>

                    {{-- Imprimir DANFE (se autorizada) --}}
                    @if($isAprovado)
                      <li>
                        <a class="dropdown-item d-flex align-items-center gap-2 py-1" href="javascript:void(0)" onclick="imprimir('{{ $pedido->id }}', '{{ $numeroNota }}')">
                          <i class="ri-printer-line text-success"></i> Imprimir DANFE
                        </a>
                      </li>
                      <li>
                        <a class="dropdown-item d-flex align-items-center gap-2 py-1" href="{{ route('nfe.download-xml', $pedido->id) }}">
                          <i class="ri-download-line text-success"></i> Baixar XML
                        </a>
                      </li>
                      <li>
                        <a class="dropdown-item d-flex align-items-center gap-2 py-1" href="javascript:void(0)" onclick="enviarEmail('{{ $pedido->id }}', '{{ $numeroNota }}')">
                          <i class="ri-mail-send-line text-info"></i> Enviar por E-mail
                        </a>
                      </li>
                      <li>
                        <a class="dropdown-item d-flex align-items-center gap-2 py-1" href="javascript:void(0)" onclick="consultar('{{ $pedido->id }}', '{{ $numeroNota }}')">
                          <i class="ri-search-eye-line text-secondary"></i> Consultar SEFAZ
                        </a>
                      </li>
                      <li>
                        <a class="dropdown-item d-flex align-items-center gap-2 py-1" href="javascript:void(0)" onclick="abrirModalCorrecao('{{ $pedido->id }}', '{{ $numeroNota }}', '{{ $serieNota }}', '{{ $dataEmissaoFormatada }}', '{{ addslashes($clienteNome) }}', '{{ $chaveNota }}')">
                          <i class="ri-file-warning-line text-warning"></i> Carta de Correção
                        </a>
                      </li>
                      <li><hr class="dropdown-divider my-1"></li>
                      <li>
                        <a class="dropdown-item d-flex align-items-center gap-2 py-1 text-danger" href="javascript:void(0)" onclick="abrirModalCancelamento('{{ $pedido->id }}', '{{ $numeroNota }}', '{{ $serieNota }}', '{{ $dataEmissaoFormatada }}', '{{ addslashes($clienteNome) }}', '{{ $chaveNota }}')">
                          <i class="ri-close-circle-line"></i> Cancelar NF-e
                        </a>
                      </li>
                    @endif

                    {{-- Pedido Pendente / Rejeitado --}}
                    @if($isNovo || $isRejeitado)
                      <li>
                        <a class="dropdown-item d-flex align-items-center gap-2 py-1 text-success" href="javascript:void(0)" onclick="transmitir('{{ $pedido->id }}')">
                          <i class="ri-send-plane-fill"></i> Transmitir SEFAZ
                        </a>
                      </li>
                      <li>
                        <a class="dropdown-item d-flex align-items-center gap-2 py-1 text-warning" href="{{ route('nfe.edit', $pedido->id) }}">
                          <i class="ri-pencil-line"></i> Editar Venda
                        </a>
                      </li>
                    @endif

                    {{-- Imprimir Pedido de Venda --}}
                    <li>
                      <a class="dropdown-item d-flex align-items-center gap-2 py-1" target="_blank" href="{{ route('nfe.imprimirVenda', $pedido->id) }}">
                        <i class="ri-file-text-line text-muted"></i> Imprimir Pedido
                      </a>
                    </li>
                  </ul>
                </div>
              </td>
            </tr>
            @empty
            <tr>
              <td colspan="9">
                <div class="cf-empty-state">
                  <i class="ri-file-list-3-line"></i>
                  <p>Nenhum faturamento/pedido encontrado nesta aba ou com os filtros selecionados.</p>
                </div>
              </td>
            </tr>
            @endforelse
          </tbody>
        </table>
      </div>

      {{-- PAGINAÇÃO DO LARAVEL --}}
      @if($pedidos->hasPages())
      <div class="cf-pagination-wrap">
        <div style="font-size:11.5px;color:#64748b;">
          Exibindo {{ $pedidos->firstItem() ?? 0 }} até {{ $pedidos->lastItem() ?? 0 }} de {{ $pedidos->total() }} registros
        </div>
        <div>
          {{ $pedidos->links() }}
        </div>
      </div>
      @endif
        </div>
      </div>
    </div>
  </div>
</div>

{{-- ====================================================================== --}}
{{-- MODAL EMITIR CARTA DE CORREÇÃO (CC-e) - MODELO NOVO IDÊNTICO --}}
{{-- ====================================================================== --}}
<div class="modal fade" id="modal-corrigir" tabindex="-1" aria-labelledby="modalCorrigirLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content text-dark" style="border-radius:12px; border:none; box-shadow:0 20px 40px rgba(0,0,0,0.15);">
      
      {{-- Header --}}
      <div class="modal-header border-0 pb-0 pt-3 px-3 px-md-4">
        <div>
          <h5 class="modal-title fs-16 fw-bold text-dark mb-0" id="modalCorrigirLabel">
            Emitir Carta de Correção (CC-e)
          </h5>
          <p class="fs-12 text-muted mb-0">Corrija informações permitidas da NF-e</p>
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>

      <div class="modal-body p-3 p-md-4">
        
        {{-- Alert Azul de Informação --}}
        <div class="alert-cce-info">
          <i class="ri-information-line"></i>
          <div>
            <div class="alert-cce-info-title">A Carta de Correção serve para corrigir informações incorretas que não alterem dados fiscais da NF-e.</div>
            <p class="alert-cce-info-sub">Exemplos: descrição do produto, CFOP, transportadora, endereço, informações complementares, entre outros.</p>
          </div>
        </div>

        {{-- Card Dados da NF-e --}}
        <div class="modal-nfe-card-dados">
          <span class="modal-nfe-card-title">Dados da NF-e</span>
          <div class="row g-3">
            <div class="col-6 col-md-3">
              <div class="modal-field-label">Número NF-e</div>
              <div class="modal-field-val" id="cce-card-numero">--</div>
            </div>
            <div class="col-6 col-md-2">
              <div class="modal-field-label">Série</div>
              <div class="modal-field-val" id="cce-card-serie">1</div>
            </div>
            <div class="col-6 col-md-3">
              <div class="modal-field-label">Data de Emissão</div>
              <div class="modal-field-val" id="cce-card-data">--</div>
            </div>
            <div class="col-6 col-md-4">
              <div class="modal-field-label">Cliente</div>
              <div class="modal-field-val" id="cce-card-cliente">--</div>
            </div>
            <div class="col-12 mt-2 pt-2 border-top">
              <div class="modal-field-label">Chave de Acesso</div>
              <div class="modal-field-val font-monospace fs-11 text-secondary" id="cce-card-chave">--</div>
            </div>
          </div>
        </div>

        {{-- Campo Texto da Correção --}}
        <div class="mb-2">
          <label class="form-label fs-12 fw-bold text-dark mb-1">
            Texto da Correção <span class="text-danger">*</span>
          </label>
          <textarea 
            id="inp-motivo-corrigir" 
            class="form-control fs-12 text-dark" 
            rows="4" 
            maxlength="1000"
            style="border-radius:8px; border-color:#cbd5e1;"
            placeholder="Descreva aqui a correção a ser considerada.&#10;&#10;    Exemplo: Onde se lê &quot;CFOP 5102&quot;, leia-se &quot;CFOP 5405&quot;." 
            required 
            minlength="15"
            oninput="atualizarContadorCharCce(this)"></textarea>
          <div class="d-flex justify-content-between align-items-center mt-1">
            <span class="fs-11 text-muted"><span id="cce-char-count">0</span> de 1000 caracteres utilizados</span>
          </div>
        </div>

        {{-- Alerta Amarelo de Atenção --}}
        <div class="alert-cce-warning">
          <div class="alert-cce-warning-title">
            <i class="ri-alert-line text-warning"></i> Atenção
          </div>
          <ul>
            <li>Alterar valores fiscais, impostos ou alíquotas;</li>
            <li>Alterar dados do destinatário/remetente;</li>
            <li>Alterar data de emissão da NF-e.</li>
          </ul>
        </div>

      </div>

      {{-- Footer --}}
      <div class="modal-footer border-0 pt-0 px-3 px-md-4 pb-3 d-flex justify-content-end gap-2">
        <button type="button" class="btn btn-light btn-sm px-3" data-bs-dismiss="modal">Cancelar</button>
        <button type="button" id="btn-corrigir" class="btn btn-sm px-4 fw-bold text-white" style="background:#0d9488;">
          <i class="ri-send-plane-fill me-1"></i> Transmitir CC-e
        </button>
      </div>

    </div>
  </div>
</div>

{{-- ====================================================================== --}}
{{-- MODAL CANCELAR NF-e - MODELO NOVO IDÊNTICO AO DESIGN --}}
{{-- ====================================================================== --}}
<div class="modal fade" id="modal-cancelar" tabindex="-1" aria-labelledby="modalCancelarLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content text-dark" style="border-radius:12px; border:none; overflow:hidden; box-shadow:0 20px 45px rgba(0,0,0,0.18);">
      
      {{-- Header Vermelho --}}
      <div class="modal-header-cancelar d-flex align-items-center justify-content-between">
        <div>
          <h5 class="modal-title mb-0" id="modalCancelarLabel" style="font-weight:700; font-size:16px;">Cancelar NF-e</h5>
          <p class="modal-subtitle" style="font-size:11.5px; opacity:0.95; margin-top:2px;">O cancelamento da NF-e será transmitido para a SEFAZ</p>
        </div>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>

      <div class="modal-body p-3 p-md-4" style="background:#ffffff;">
        
        {{-- Alert Vermelho de Atenção --}}
        <div class="alert-cancela-danger" style="background:#fef2f2; border:1px solid #fee2e2; border-radius:10px; padding:12px 14px; margin-bottom:16px; display:flex; align-items:flex-start; gap:10px;">
          <i class="ri-alert-line" style="color:#ef4444; font-size:18px; margin-top:1px;"></i>
          <div>
            <div style="font-size:12px; font-weight:700; color:#dc2626; margin-bottom:2px;">Atenção ao cancelar esta NF-e</div>
            <p style="font-size:11px; color:#ef4444; margin:0; line-height:1.35;">Após o cancelamento autorizado pela SEFAZ, a nota fiscal ficará sem validade fiscal e não poderá ser utilizada novamente.</p>
          </div>
        </div>

        {{-- Card Dados da NF-e --}}
        <div class="modal-nfe-card-dados" style="background:#ffffff; border:1px solid #e2e8f0; border-radius:10px; padding:16px; margin-bottom:16px;">
          <span style="font-size:11.5px; font-weight:700; color:#334155; margin-bottom:12px; display:block;">Dados da NF-e</span>
          <div class="row g-3">
            <div class="col-6 col-md-3">
              <div style="font-size:10.5px; color:#94a3b8; margin-bottom:2px; font-weight:500;">Número NF-e</div>
              <div style="font-size:12.5px; font-weight:700; color:#0f172a;" id="cancela-card-numero">--</div>
            </div>
            <div class="col-6 col-md-2">
              <div style="font-size:10.5px; color:#94a3b8; margin-bottom:2px; font-weight:500;">Série</div>
              <div style="font-size:12.5px; font-weight:700; color:#0f172a;" id="cancela-card-serie">1</div>
            </div>
            <div class="col-6 col-md-3">
              <div style="font-size:10.5px; color:#94a3b8; margin-bottom:2px; font-weight:500;">Data de Emissão</div>
              <div style="font-size:12.5px; font-weight:700; color:#0f172a;" id="cancela-card-data">--</div>
            </div>
            <div class="col-6 col-md-4">
              <div style="font-size:10.5px; color:#94a3b8; margin-bottom:2px; font-weight:500;">Cliente</div>
              <div style="font-size:12.5px; font-weight:700; color:#0f172a;" id="cancela-card-cliente">--</div>
            </div>
            <div class="col-12 mt-3 pt-2">
              <div style="font-size:10.5px; color:#94a3b8; margin-bottom:2px; font-weight:500;">Chave de Acesso</div>
              <div style="font-size:12px; font-weight:700; color:#334155; letter-spacing:0.3px; word-break:break-all;" id="cancela-card-chave">--</div>
            </div>
          </div>
        </div>

        {{-- Campo Motivo do Cancelamento --}}
        <div class="mb-3">
          <label class="form-label fs-12 fw-bold text-dark mb-1">
            Motivo do Cancelamento <span class="text-danger">*</span>
          </label>
          <textarea 
            id="inp-motivo-cancela" 
            class="form-control fs-12 text-dark" 
            rows="3" 
            maxlength="255"
            style="border-radius:8px; border-color:#cbd5e1; resize:vertical;"
            placeholder="Descreva o motivo do cancelamento da NF-e" 
            required 
            minlength="15"
            oninput="atualizarContadorCharCancela(this)"></textarea>
          <div class="mt-1">
            <span class="fs-11 text-muted"><span id="cancela-char-count">0</span> de 255 caracteres utilizados</span>
          </div>
        </div>

        {{-- Alerta Amarelo de Importante --}}
        <div class="alert-cce-warning" style="background:#fefce8; border:1px solid #fef08a; border-radius:10px; padding:14px 16px;">
          <div style="font-size:12px; font-weight:700; color:#854d0e; margin-bottom:6px; display:flex; align-items:center; gap:6px;">
            <i class="ri-information-line" style="font-size:15px; color:#ca8a04;"></i> Importante
          </div>
          <ul style="list-style:none; padding-left:0; margin-bottom:0;">
            <li style="font-size:11.5px; color:#a16207; margin-bottom:3px; display:flex; align-items:center; gap:6px;">
              <span style="color:#eab308; font-weight:bold;">•</span> O cancelamento deve respeitar o prazo permitido pela SEFAZ;
            </li>
            <li style="font-size:11.5px; color:#a16207; margin-bottom:3px; display:flex; align-items:center; gap:6px;">
              <span style="color:#eab308; font-weight:bold;">•</span> Após autorizado, o cancelamento não poderá ser revertido;
            </li>
            <li style="font-size:11.5px; color:#a16207; margin-bottom:0; display:flex; align-items:center; gap:6px;">
              <span style="color:#eab308; font-weight:bold;">•</span> Informe um motivo claro e objetivo.
            </li>
          </ul>
        </div>

      </div>

      {{-- Footer --}}
      <div class="modal-footer border-0 pt-0 px-3 px-md-4 pb-3 d-flex justify-content-end gap-2" style="background:#ffffff;">
        <button type="button" class="btn btn-light btn-sm px-4 fw-medium text-dark" data-bs-dismiss="modal" style="background:#f1f5f9; border:none; border-radius:6px;">Fechar</button>
        <button type="button" id="btn-cancelar" class="btn btn-danger btn-sm px-4 fw-bold" style="background:#ef4444; border:none; border-radius:6px; display:inline-flex; align-items:center; gap:6px;">
          <i class="ri-record-circle-line"></i> Transmitir Cancelamento
        </button>
      </div>

    </div>
  </div>
</div>

{{-- MODAL XMLS DO MÊS --}}
<div class="modal fade" id="modal-xmls-mes" tabindex="-1" aria-labelledby="modalXmlsMesLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content text-dark" style="border-radius:12px;overflow:hidden;">
      <div class="modal-header bg-light">
        <h5 class="modal-title d-flex align-items-center gap-2 fs-14 text-primary" id="modalXmlsMesLabel">
          <i class="ri-download-2-line"></i> Download de XMLs do Mês (ZIP)
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form action="{{ route('nfe-xml.download') }}" method="get" target="_blank">
        <div class="modal-body p-3">
          <p class="fs-12 text-muted mb-3">Selecione o período para compactar e baixar todos os arquivos XML autorizados:</p>
          <div class="row g-2">
            <div class="col-6">
              <label class="form-label fs-11 fw-bold text-secondary">Mês:</label>
              <select id="xml_mes_select" class="form-select form-select-sm" onchange="atualizarPeriodoXmlMes()">
                @for($m=1; $m<=12; $m++)
                  @php $mesNome = Carbon\Carbon::create(null, $m, 1)->locale('pt_BR')->translatedFormat('F'); @endphp
                  <option value="{{ sprintf('%02d', $m) }}" @selected(date('m') == $m)>{{ ucfirst($mesNome) }}</option>
                @endfor
              </select>
            </div>
            <div class="col-6">
              <label class="form-label fs-11 fw-bold text-secondary">Ano:</label>
              <select id="xml_ano_select" class="form-select form-select-sm" onchange="atualizarPeriodoXmlMes()">
                @for($y=date('Y'); $y>=date('Y')-3; $y--)
                  <option value="{{ $y }}" @selected(date('Y') == $y)>{{ $y }}</option>
                @endfor
              </select>
            </div>
            <div class="col-12 mt-2">
              <label class="form-label fs-11 fw-bold text-secondary">Estado da Nota:</label>
              <select name="estado" class="form-select form-select-sm">
                <option value="aprovado">Apenas Autorizadas (Aprovadas)</option>
                <option value="cancelado">Apenas Canceladas</option>
                <option value="">Todas</option>
              </select>
            </div>
          </div>
          {{-- Inputs Ocultos de Datas Calculadas --}}
          <input type="hidden" name="start_date" id="xml_start_date">
          <input type="hidden" name="end_date" id="xml_end_date">
        </div>
        <div class="modal-footer bg-light p-2">
          <button type="button" class="btn btn-light btn-sm" data-bs-dismiss="modal">Fechar</button>
          <button type="submit" class="btn btn-primary btn-sm" onclick="$('#modal-xmls-mes').modal('hide');">
            <i class="ri-file-zip-line me-1"></i> Baixar Arquivo ZIP
          </button>
        </div>
      </form>
    </div>
  </div>
</div>

{{-- MODAL FATURAMENTO EM LOTE --}}
<div class="modal fade" id="modal-faturamento-lote" tabindex="-1" aria-labelledby="modalFaturamentoLoteLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content text-dark" style="border-radius:12px;overflow:hidden;">
      <div class="modal-header bg-light">
        <h5 class="modal-title d-flex align-items-center gap-2 fs-14 text-success" id="modalFaturamentoLoteLabel">
          <i class="ri-stack-line"></i> Novo Faturamento em Lote (NF-e)
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body p-3">
        <div class="alert alert-info py-2 fs-12 mb-3">
          <i class="ri-information-line me-1"></i> Selecione os pedidos pendentes abaixo para transmitir e faturar sequencialmente na SEFAZ.
        </div>
        <div class="table-responsive" style="max-height: 320px; overflow-y: auto;">
          <table class="table table-sm table-hover align-middle mb-0 fs-12">
            <thead class="table-light sticky-top">
              <tr>
                <th style="width:36px;"><input type="checkbox" id="modal-check-all" onchange="toggleModalCheckAll(this)"></th>
                <th>Pedido</th>
                <th>Cliente</th>
                <th>Valor</th>
                <th>Data</th>
                <th>Situação</th>
              </tr>
            </thead>
            <tbody>
              @php
                $pendentesLote = $pedidos->filter(fn($p) => $p->estado == 'novo' || $p->estado == 'rejeitado');
              @endphp
              @forelse($pendentesLote as $p)
              <tr>
                <td><input type="checkbox" class="modal-lote-check form-check-input" value="{{ $p->id }}" onchange="updateModalLoteCounter()"></td>
                <td><strong>#{{ $p->numero ?? $p->id }}</strong></td>
                <td>{{ $p->cliente ? $p->cliente->razao_social : ($p->emissor_nome ?? 'Consumidor') }}</td>
                <td class="text-success fw-bold">R$ {{ number_format($p->total, 2, ',', '.') }}</td>
                <td>{{ $p->created_at ? $p->created_at->format('d/m/Y') : '--' }}</td>
                <td><span class="badge bg-warning text-dark">{{ ucfirst($p->estado) }}</span></td>
              </tr>
              @empty
              <tr>
                <td colspan="6" class="text-center py-4 text-muted">
                  Nenhum pedido pendente nesta página para faturamento em lote.
                </td>
              </tr>
              @endforelse
            </tbody>
          </table>
        </div>
      </div>
      <div class="modal-footer bg-light p-2 d-flex justify-content-between">
        <div class="fs-12 text-muted">
          Selecionados: <strong id="modal-lote-count">0</strong> pedido(s)
        </div>
        <div>
          <button type="button" class="btn btn-light btn-sm" data-bs-dismiss="modal">Cancelar</button>
          <button type="button" class="btn btn-success btn-sm fw-bold" onclick="executarFaturamentoLoteModal()">
            <i class="ri-send-plane-fill me-1"></i> Iniciar Emissão em Lote
          </button>
        </div>
      </div>
    </div>
  </div>
</div>

{{-- MODAL DE ESCOLHA DE DANFE --}}
<div class="modal fade" id="modal-print" tabindex="-1" aria-labelledby="modalPrintLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content text-dark" style="border-radius:12px;overflow:hidden;">
      <div class="modal-header bg-light">
        <h5 class="modal-title d-flex align-items-center gap-2 fs-14" id="modalPrintLabel">
          <i class="ri-printer-line text-primary"></i> Imprimir NF-e <strong class="ref-numero text-primary"></strong>
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body p-3">
        <div class="row g-2">
          <div class="col-12 col-md-4">
            <button type="button" class="btn btn-success w-100 btn-sm py-2" onclick="gerarDanfe('danfe')">
              <i class="ri-printer-line me-1"></i> DANFE Padrão
            </button>
          </div>
          <div class="col-12 col-md-4">
            <button type="button" class="btn btn-primary w-100 btn-sm py-2" onclick="gerarDanfe('simples')">
              <i class="ri-printer-line me-1"></i> DANFE Simples
            </button>
          </div>
          <div class="col-12 col-md-4">
            <button type="button" class="btn btn-dark w-100 btn-sm py-2" onclick="gerarDanfe('etiqueta')">
              <i class="ri-printer-line me-1"></i> DANFE Etiqueta
            </button>
          </div>
        </div>
      </div>
      <div class="modal-footer bg-light p-2">
        <button type="button" class="btn btn-light btn-sm" data-bs-dismiss="modal">Fechar</button>
      </div>
    </div>
  </div>
</div>

{{-- MODAL DE ENVIO POR EMAIL --}}
<div class="modal fade" id="modal-email" tabindex="-1" aria-labelledby="modalEmailLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content text-dark" style="border-radius:12px;overflow:hidden;">
      <div class="modal-header bg-light">
        <h5 class="modal-title d-flex align-items-center gap-2 fs-14 text-primary" id="modalEmailLabel">
          <i class="ri-mail-send-line"></i> Enviar NF-e por E-mail <strong class="ref-numero text-primary"></strong>
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body p-3">
        <div class="mb-3">
          <label class="form-label fs-12 fw-bold text-dark">Endereço de E-mail do Destinatário:</label>
          <input type="email" id="inp-email" class="form-control form-control-sm" placeholder="cliente@email.com" required>
        </div>
        <div class="d-flex gap-3">
          <div class="form-check">
            <input class="form-check-input" type="checkbox" id="inp-danfe" checked>
            <label class="form-check-label fs-12" for="inp-danfe">Incluir DANFE (PDF)</label>
          </div>
          <div class="form-check">
            <input class="form-check-input" type="checkbox" id="inp-xml" checked>
            <label class="form-check-label fs-12" for="inp-xml">Incluir XML</label>
          </div>
        </div>
      </div>
      <div class="modal-footer bg-light p-2">
        <button type="button" class="btn btn-light btn-sm" data-bs-dismiss="modal">Fechar</button>
        <button type="button" id="btn-enviar-email" class="btn btn-success btn-sm">Enviar E-mail</button>
      </div>
    </div>
  </div>
</div>

@include('modals._processing_overlay')

@endsection

@section('js')
<script type="text/javascript">
  // ==========================================
  // MODAL CARTA DE CORREÇÃO (CC-e)
  // ==========================================
  function abrirModalCorrecao(id, numero, serie, data, cliente, chave) {
    IDNFE = id;
    $('.ref-numero').text(numero);
    $('#cce-card-numero').text(numero || '--');
    $('#cce-card-serie').text(serie || '1');
    $('#cce-card-data').text(data || '--');
    $('#cce-card-cliente').text(cliente || '--');
    $('#cce-card-chave').text(chave || '--');
    $('#inp-motivo-corrigir').val('');
    $('#cce-char-count').text('0');
    $('#modal-corrigir').modal('show');
  }

  function atualizarContadorCharCce(el) {
    $('#cce-char-count').text(el.value.length);
  }

  // ==========================================
  // MODAL CANCELAR NF-e
  // ==========================================
  function abrirModalCancelamento(id, numero, serie, data, cliente, chave) {
    IDNFE = id;
    $('.ref-numero').text(numero);
    $('#cancela-card-numero').text(numero || '--');
    $('#cancela-card-serie').text(serie || '1');
    $('#cancela-card-data').text(data || '--');
    $('#cancela-card-cliente').text(cliente || '--');
    $('#cancela-card-chave').text(chave || '--');
    $('#inp-motivo-cancela').val('');
    $('#cancela-char-count').text('0');
    $('#modal-cancelar').modal('show');
  }

  function atualizarContadorCharCancela(el) {
    $('#cancela-char-count').text(el.value.length);
  }

  // Compatibilidade com funções legadas
  function cancelar(id, numero) {
    abrirModalCancelamento(id, numero, '1', '--', '--', '--');
  }
  function corrigir(id, numero) {
    abrirModalCorrecao(id, numero, '1', '--', '--', '--');
  }

  // Atualiza datas inicial e final para o download de XMLs do Mês
  function atualizarPeriodoXmlMes() {
    let mes = $('#xml_mes_select').val();
    let ano = $('#xml_ano_select').val();
    let ultimoDia = new Date(ano, parseInt(mes), 0).getDate();
    
    $('#xml_start_date').val(ano + '-' + mes + '-01');
    $('#xml_end_date').val(ano + '-' + mes + '-' + (ultimoDia < 10 ? '0' + ultimoDia : ultimoDia));
  }
  $(document).ready(function() {
    atualizarPeriodoXmlMes();
  });

  // Seleção e Faturamento em Lote na Grid
  function toggleSelectAllLote(masterCheck) {
    $('.check-lote-item').prop('checked', masterCheck.checked);
    updateLoteCounter();
  }

  function updateLoteCounter() {
    let count = $('.check-lote-item:checked').length;
    $('#selected-count').text(count);
    if (count > 0) {
      $('#bulk-action-bar').removeClass('d-none');
    } else {
      $('#bulk-action-bar').addClass('d-none');
      $('#check-select-all').prop('checked', false);
    }
  }

  function desmarcarTodosLote() {
    $('.check-lote-item').prop('checked', false);
    $('#check-select-all').prop('checked', false);
    updateLoteCounter();
  }

  // Faturamento em Lote no Modal
  function toggleModalCheckAll(masterCheck) {
    $('.modal-lote-check').prop('checked', masterCheck.checked);
    updateModalLoteCounter();
  }

  function updateModalLoteCounter() {
    let count = $('.modal-lote-check:checked').length;
    $('#modal-lote-count').text(count);
  }

  // Execução de Faturamento em Lote Sequencial com Feedback na SEFAZ
  async function processarEmissaoLote(ids) {
    if (!ids || ids.length === 0) {
      swal("Aviso", "Nenhum pedido selecionado para faturamento em lote!", "warning");
      return;
    }

    let total = ids.length;
    let autorizadas = 0;
    let rejeitadas = 0;

    nfeMostrarProcessingOverlay('Faturamento em Lote SEFAZ', `Iniciando emissão de 0 de ${total} pedidos...`, 'ri-stack-line');

    for (let i = 0; i < total; i++) {
      let currentId = ids[i];
      let numAtual = i + 1;
      
      nfeAtualizarProcessingOverlay(
        `Faturando Pedido ${numAtual} de ${total}`,
        `Comunicando com a SEFAZ para autorização da nota...`,
        'ri-loader-4-line'
      );

      try {
        await new Promise((resolve) => {
          $.post(path_url + "api/nfe_painel/emitir", { id: currentId })
            .done((res) => {
              autorizadas++;
              resolve(res);
            })
            .fail((err) => {
              rejeitadas++;
              resolve(null);
            });
        });
      } catch (e) {
        rejeitadas++;
      }
    }

    nfeEsconderProcessingOverlay();
    swal("Lote Concluído!", `Resultado do faturamento em lote:\n\n✔ Autorizadas com sucesso: ${autorizadas}\n✖ Rejeitadas/Erros: ${rejeitadas}`, "success")
      .then(() => {
        location.reload();
      });
  }

  function executarFaturamentoLoteSelecionados() {
    let ids = [];
    $('.check-lote-item:checked').each(function() {
      ids.push($(this).val());
    });
    processarEmissaoLote(ids);
  }

  function executarFaturamentoLoteModal() {
    let ids = [];
    $('.modal-lote-check:checked').each(function() {
      ids.push($(this).val());
    });
    $('#modal-faturamento-lote').modal('hide');
    processarEmissaoLote(ids);
  }

  function info(motivo_rejeicao, chave, estado, recibo) {
    if (estado == 'rejeitado') {
      let text = "Motivo: " + motivo_rejeicao + "\n\n";
      text += "Chave: " + chave + "\n";
      swal("Nota Rejeitada", text, "warning");
    } else {
      let text = "Chave: " + chave + "\n";
      text += "Recibo: " + recibo + "\n";
      swal("Nota Autorizada", text, "success");
    }
  }
</script>
<script type="text/javascript" src="/js/nfe_transmitir.js"></script>
@endsection
