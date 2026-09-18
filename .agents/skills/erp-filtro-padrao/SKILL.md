---
name: erp-filtro-padrao
description: Padrão oficial de filtros e barras de pesquisa para o ERP. Fornece o CSS completo, HTML pronto com grid balanceado, ícones nas labels, botões padronizados e regras de compatibilidade.
---

# 🔍 Padrão de Filtros de Pesquisa — ERP

Este guia define o padrão oficial para a criação e modernização de **Filtros e Barras de Pesquisa** em todas as telas de listagem (`index.blade.php`) do ERP.

---

## 🎨 Características do Padrão
1. **Container Limpo (`.modulo-glass-filter-premium`)**: Fundo branco, cantos arredondados (`border-radius: 12px`), borda suave `#eef0f6` e sombra sutil.
2. **Cabeçalho Interno (`.filtro-premium-header`)**: Título em caixa alta discreta com ícone colorido `#5572f5` e separador.
3. **Labels Nítidas com Distância Suave**: 
   - **Tamanho da Fonte**: `13px` com peso `font-weight: 600` e cor `#374151` (legível e confortável, **nunca** usar fontes minúsculas).
   - **Distância para o Input**: Apenas `margin-bottom: 4px;` para ficar próximo e harmonioso com o campo.
   - **Ícone**: Remix Icon com tom neutro e espaçamento de `gap: 5px`.
4. **Inputs Compactos e Uniformes**: Altura de 38px a 40px, borda suave `#dcdce9`, foco com anel suave de destaque.
5. **Botões Alinhados na Base**: Botões `.dash-btn.dash-btn-primary` (Buscar) e `.dash-btn.dash-btn-light` (Limpar) ou botões equivalentes com `align-items-end`.
6. **Alinhamento do Grid (`align-items-end`)**:
   - `row g-3 align-items-end` para que todos os campos fiquem perfeitamente alinhados na base, inclusive botões e selects.
   - **Campos Largos** (ex: Cliente, Nome, Produto): `col-md-4` ou `col-md-3`.
   - **Campos Curtos** (ex: Datas, Estado/Status, Tipo): `col-md-2` ou `col-md-3` ou `col-6`.
   - **Ações (Botões)**: `col-md-2` ou `col-md-3` com `d-flex gap-2`.

---

## 📦 1. CSS Obrigatório (Já em `dashboard-skin.css` e replicável localmente)

```css
/* --- Novo Filtro de Pesquisa Premium --- */
.modulo-glass-filter-premium {
    background: #ffffff;
    border: 1px solid #eef0f6 !important;
    border-radius: 12px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.03);
    padding: 20px !important;
    margin-bottom: 24px;
}

/* Título e Header do Filtro */
.filtro-premium-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    border-bottom: 1px solid #f1f3f9;
    padding-bottom: 12px;
    margin-bottom: 16px;
}
.filtro-premium-title {
    font-size: 13px;
    font-weight: 700;
    color: #3f3e6a;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    margin-bottom: 0;
}
.filtro-premium-title i {
    color: #5572f5;
    margin-right: 6px;
}

/* Customização e Espaçamento Perfeito dos Labels */
.modulo-glass-filter-premium label,
.form-label,
label:not(.form-check-label):not(.btn) {
    font-size: 13px !important;
    font-weight: 600 !important;
    color: #374151 !important;
    margin-bottom: 4px !important;
    padding-bottom: 0 !important;
    display: inline-flex !important;
    align-items: center !important;
    gap: 5px !important;
}

.modulo-glass-filter-premium label i {
    font-size: 13px;
    color: #64748b;
}

.form-group {
    margin-bottom: 0 !important;
    margin-top: 0 !important;
}

.modulo-glass-filter-premium .form-control,
.modulo-glass-filter-premium .form-select {
    height: 38px !important;
    border-radius: 8px !important;
    border: 1px solid #dcdce9 !important;
    font-size: 13px !important;
    padding: 6px 12px !important;
    color: #374151 !important;
    background-color: #fcfdfe !important;
    transition: all 0.2s ease;
}

.modulo-glass-filter-premium .form-control:focus,
.modulo-glass-filter-premium .form-select:focus {
    border-color: #5572f5 !important;
    background-color: #fff !important;
    box-shadow: 0 0 0 3px rgba(85, 114, 245, 0.12) !important;
}
```

---

## 📄 2. Template HTML Padrão (Exemplo Geral Limpo)

```blade
<!-- ═══ Filtros de Busca Premium ═══ -->
<div class="modulo-glass-filter-premium mb-4">
    <div class="filtro-premium-header">
        <h5 class="filtro-premium-title">
            <i class="ri-search-line"></i> Filtrar Registros
        </h5>
    </div>

    <form method="get" action="{{ route('modulo.index') }}">
        <div class="row g-3 align-items-end">
            <!-- Campo Principal (Nome / Descrição) -->
            <div class="col-md-4 col-12">
                <label class="form-label"><i class="ri-search-line"></i> Nome / Descrição</label>
                <input type="text" name="nome" value="{{ request('nome') }}" class="form-control" placeholder="Digite para pesquisar...">
            </div>

            <!-- Data Inicial -->
            <div class="col-md-2 col-6">
                <label class="form-label"><i class="ri-calendar-line"></i> Data Inicial</label>
                <input type="date" name="start_date" value="{{ request('start_date') }}" class="form-control">
            </div>

            <!-- Data Final -->
            <div class="col-md-2 col-6">
                <label class="form-label"><i class="ri-calendar-line"></i> Data Final</label>
                <input type="date" name="end_date" value="{{ request('end_date') }}" class="form-control">
            </div>

            <!-- Status / Estado -->
            <div class="col-md-2 col-6">
                <label class="form-label"><i class="ri-equalizer-line"></i> Status</label>
                <select name="status" class="form-select">
                    <option value="" @selected(request('status') == '')>Todos</option>
                    <option value="1" @selected(request('status') === '1')>Ativo / Aprovado</option>
                    <option value="0" @selected(request('status') === '0')>Inativo / Pendente</option>
                </select>
            </div>

            <!-- Botões de Ação (Buscar / Limpar) -->
            <div class="col-md-2 col-12 d-flex gap-2">
                <button class="dash-btn dash-btn-primary flex-grow-1" type="submit">
                    <i class="ri-search-line"></i> Buscar
                </button>
                <a class="dash-btn dash-btn-light px-3" href="{{ route('modulo.index') }}" title="Limpar Filtros">
                    <i class="ri-eraser-line"></i>
                </a>
            </div>
        </div>
    </form>
</div>
```

---

## ⚠️ Regras Importantes ao Aplicar
1. **Evitar Espaços Extras / FormBuilder dentro do Grid**: Utilizar inputs e selects limpos dentro do grid com `align-items-end` para evitar que divs wrappers geradas por bibliotecas criem vãos verticais entre a label e o input.
2. **Distância Label $\rightarrow$ Input**: Manter sempre `margin-bottom: 4px` para garantir proximidade e clareza visual.
3. **Tamanho da Fonte das Labels**: Manter `13px` com peso `600`, preservando a legibilidade.
4. **Posição na Tela**: O bloco do filtro deve ficar sempre **abaixo dos KPI Cards** e **acima da tabela (`.tb-wrap`)**.
