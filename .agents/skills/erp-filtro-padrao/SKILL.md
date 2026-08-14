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
3. **Labels com Ícones**: Fontes de 10px em negrito, caixa alta, com ícone Remix Icon em tom neutro e espaçamento correto.
4. **Inputs Compactos e Uniformes**: Altura de 38px, borda suave `#dcdce9`, foco com anel suave de destaque.
5. **Botões Alinhados na Base**: Botão **Buscar** com gradiente azul/índigo e botão **Limpar** com ícone compacto `#f1f3f9`.
6. **Grid Responsivo em 1 ou 2 Linhas**:
   - **Campos Largos** (ex: Cliente, Nome, Produto): `col-md-4` ou `col-md-3`.
   - **Campos Curtos** (ex: Datas, Estado/Status, Tipo): `col-md-2` ou `col-md-3`.
   - **Ações (Botões)**: `col-md-2` ou `col-md-3` com `ms-auto d-flex align-items-end`.

---

## 📦 1. CSS Obrigatório (Adicionar no `@section('css')` da View)

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

/* Customização dos Inputs dentro do Filtro */
.modulo-glass-filter-premium label {
    font-size: 10px !important;
    font-weight: 700 !important;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    color: #8c8ca6 !important;
    margin-bottom: 6px !important;
    display: flex;
    align-items: center;
    gap: 4px;
}
.modulo-glass-filter-premium label i {
    font-size: 12px;
    color: #a8a8c0;
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

/* Botões do Filtro */
.modulo-glass-filter-premium .btn-pesquisar {
    background: linear-gradient(135deg, #5572f5 0%, #3d56d4 100%) !important;
    border: none !important;
    color: #fff !important;
    font-weight: 600 !important;
    height: 38px;
    border-radius: 8px !important;
    font-size: 13px !important;
    transition: all 0.2s ease !important;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 6px;
}
.modulo-glass-filter-premium .btn-pesquisar:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(85, 114, 245, 0.25) !important;
}

.modulo-glass-filter-premium .btn-limpar {
    background: #f1f3f9 !important;
    border: 1px solid #e2e5ec !important;
    color: #5a5a7a !important;
    font-weight: 600 !important;
    height: 38px;
    border-radius: 8px !important;
    font-size: 13px !important;
    transition: all 0.2s ease !important;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 6px;
}
.modulo-glass-filter-premium .btn-limpar:hover {
    background: #e8ebf3 !important;
    color: #302b63 !important;
}
```

---

## 📄 2. Template HTML Padrão (Exemplo Geral)

```blade
<!-- ═══ Filtros de Busca Premium ═══ -->
<div class="modulo-glass-filter-premium">
    <div class="filtro-premium-header">
        <h5 class="filtro-premium-title">
            <i class="ri-search-line"></i> Filtrar Registros
        </h5>
    </div>

    {!!Form::open()->fill(request()->all())->get()!!}
    <div class="row g-3">
        <!-- Campo Principal (Nome / Cliente) -->
        <div class="col-md-4 col-12">
            <label class="form-label"><i class="ri-user-line"></i> Nome / Cliente</label>
            {!!Form::text('nome', '')->attrs(['class' => 'form-control', 'placeholder' => 'Digite para pesquisar...'])!!}
        </div>

        <!-- Data Inicial -->
        <div class="col-md-2 col-6">
            <label class="form-label"><i class="ri-calendar-line"></i> Data Inicial</label>
            {!!Form::date('start_date', '')->attrs(['class' => 'form-control'])!!}
        </div>

        <!-- Data Final -->
        <div class="col-md-2 col-6">
            <label class="form-label"><i class="ri-calendar-line"></i> Data Final</label>
            {!!Form::date('end_date', '')->attrs(['class' => 'form-control'])!!}
        </div>

        <!-- Status / Estado -->
        <div class="col-md-2 col-6">
            <label class="form-label"><i class="ri-equalizer-line"></i> Status</label>
            {!!Form::select('status', '', [
                '' => 'Todos',
                '1' => 'Ativo / Aprovado',
                '0' => 'Inativo / Pendente'
            ])->attrs(['class' => 'form-select'])!!}
        </div>

        <!-- Localização (Se empresa tiver múltiplos locais) -->
        @if(__countLocalAtivo() > 1)
        <div class="col-md-2 col-6">
            <label class="form-label"><i class="ri-store-2-line"></i> Local</label>
            {!!Form::select('local_id', '')->options(['' => 'Selecione'] + __getLocaisAtivoUsuario()->pluck('descricao', 'id')->all())->attrs(['class' => 'select2 form-select'])!!}
        </div>
        @endif

        <!-- Botões de Ação (Buscar / Limpar) -->
        <div class="col-md-2 col-12 ms-auto d-flex align-items-end">
            <div class="d-flex gap-2 w-100">
                <button class="btn btn-pesquisar flex-grow-1" type="submit">
                    <i class="ri-search-line"></i> Buscar
                </button>
                <a class="btn btn-limpar px-3" href="{{ route('modulo.index') }}" title="Limpar Filtros">
                    <i class="ri-eraser-line"></i>
                </a>
            </div>
        </div>
    </div>
    {!!Form::close()!!}
</div>
```

---

## ⚠️ Regras Importantes ao Aplicar
1. **Evitar variáveis não enviadas pelo Controller**: Use `{!!Form::select('cliente_id', '')->attrs(...)!!}` em vez de tentar acessar variáveis como `$cliente` que podem não ter sido passadas no `compact()`.
2. **Posição na Tela**: O bloco do filtro deve ficar sempre **abaixo dos KPI Cards** (se houver) e **acima da tabela (`.modulo-table-wrap`)**.
3. **Manter dentro de `card-body`**: Certifique-se de que a div do filtro está devidamente aninhada dentro de `<div class="card-body p-4">`.
