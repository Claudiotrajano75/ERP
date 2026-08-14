---
name: erp-layout-modernization
description: Guia completo de padronização de layouts do ERP. Define o padrão visual premium para todas as telas (Index, Create, Edit, Show), com templates prontos para copiar e regras de compatibilidade técnica.
---

# 🎨 Padrão de Layout Premium — ERP

> **Objetivo**: Qualquer IA que ler este arquivo deve ser capaz de criar ou atualizar qualquer tela do ERP seguindo exatamente o mesmo padrão visual, sem precisar ver outras telas como referência.

---

## 📋 REGRAS GERAIS (LEIA ANTES DE QUALQUER COISA)

| Regra | Detalhe |
|-------|---------|
| **Botão Limpar** | SEMPRE com texto: `<i class="ri-eraser-line me-1"></i> Limpar` |
| **Paginação** | Limite de **10 linhas** por página (chave `.env`: `PAGINACAO=10`) |
| **CPF/CNPJ** | Nunca exibir em coluna separada — colocar em `<span class="text-muted fs-11">` abaixo do nome |
| **Botões de ação** | SEMPRE lado a lado, nunca empilhados (`flex-wrap: nowrap`) |
| **Ícones** | Usar exclusivamente a biblioteca **Remix Icon** (`ri-*`) |
| **IDs de inputs** | NUNCA alterar IDs gerados pelo FormBuilder (ver seção de compatibilidade) |
| **Form de exclusão** | `<form>` com `@method('delete')` + `@csrf` + classe `btn-delete` no botão |
| **⚠️ Botões duplicados** | Antes de adicionar `modulo-actions` no `create`/`edit`, **SEMPRE ler o `_forms.blade.php`** para verificar se ele já tem botões Salvar/Cancelar. Se tiver, NÃO adicionar os botões na view pai — apenas fazer `@include('modulo._forms')` |
| **📊 KPI Cards no Index** | SEMPRE verificar se há dados agregáveis no controller (count por estado, somas de valores). Se houver, **obrigatoriamente** incluir o bloco de KPI cards entre os filtros e a tabela, usando `widget-icon-box`. Ver seção "📊 PADRÃO DE KPI CARDS" |

---

## 🗂️ QUAL PADRÃO USAR EM CADA TELA?

```
┌─────────────────────────────────────────────────────────────┐
│  TELA DE LISTAGEM (Index) — ordem obrigatória:              │
│  1. Cabeçalho: GRADIENTE ESCURO (.modulo-header-gradient)   │
│  2. KPI Cards: widget-icon-box  ← ACIMA dos filtros         │
│  3. Filtros: .modulo-glass-filter                           │
│  4. Tabela: .modulo-table-wrap                              │
│  5. Footer: paginação (.modulo-footer)                      │
├─────────────────────────────────────────────────────────────┤
│  TELAS DE FORMULÁRIO (Create / Edit / Show)                 │
│  → Cabeçalho: GRADIENTE ESCURO (.modulo-header-gradient)   │
│  → Botão Voltar: btn-light btn-sm text-dark                 │
│  → Botão Salvar: btn-success (create) / btn-primary (edit) │
└─────────────────────────────────────────────────────────────┘
```

> **Nota**: O padrão de header gradiente escuro é usado em TODAS as telas (Index, Create, Edit e Show). Os KPI cards ficam **SEMPRE acima dos filtros** — nunca entre filtros e tabela.

---

## 🏗️ ESTRUTURA BASE (toda tela começa assim)

```blade
@extends('layouts.app', ['title' => 'Nome da Tela'])

@section('css')
<style>
/* Cole aqui o CSS Completo do bloco "📦 CSS Completo" abaixo */
</style>
@endsection

@section('content')
<div class="mt-3 text-dark">
    <div class="row">
        <div class="card border-0 shadow-sm text-dark">
            <!-- CABEÇALHO -->
            <!-- CORPO -->
        </div>
    </div>
</div>
@endsection

@section('js')
<script src="/js/meu_modulo.js"></script>
@endsection
```

---

## 📄 TEMPLATE COMPLETO — TELA INDEX (Listagem)

---

## 📊 PADRÃO DE KPI CARDS (obrigatório no Index quando há dados agregáveis)

### Quando incluir?

Ao modernizar qualquer tela Index, **SEMPRE verificar o controller** para identificar campos que permitem agregações úteis:
- Campos de **status/estado** → `count()` por valor (`aprovado`, `cancelado`, `rejeitado`, `novo`, `ativo`, `inativo`...)
- Campos de **valor monetário** → `sum('valor_xxx')` filtrado por estado aprovado
- Campos de **quantidade** → `sum('quantidade_xxx')`

Se houver ao menos **2 métricas interessantes**, inclua o bloco de KPI cards.

### Padrão no Controller (`index` method)

```php
// 1. Crie uma $statsQuery com os mesmos filtros da listagem, MAS SEM filtro de estado
//    (para os cards sempre mostrarem totais por categoria, independente do filtro)
$statsQuery = Modelo::where('empresa_id', request()->empresa_id)
->when(...filtros de data, local, etc...);

// 2. Monte o array $stats com clone para não contaminar a query
$stats = [
    'total'      => (clone $statsQuery)->count(),
    'aprovadas'  => (clone $statsQuery)->where('estado_emissao', 'aprovado')->count(),
    'canceladas' => (clone $statsQuery)->where('estado_emissao', 'cancelado')->count(),
    'valor'      => (clone $statsQuery)->where('estado_emissao', 'aprovado')->sum('valor_campo'),
];

// 3. Envie $stats junto com $data
return view('modulo.index', compact('data', 'stats'));
```

### Padrão na View (entre `modulo-glass-filter` e `modulo-table-wrap`)

```blade
{{-- ═══ KPI CARDS ═══ --}}
<div class="row g-3 mb-4">
    <div class="col-md-3 col-6">
        <div class="card widget-icon-box text-bg-info mb-0">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div class="flex-grow-1 overflow-hidden">
                        <h4 class="text-uppercase fs-12 mt-0 text-white-50">Total</h4>
                        <h3 class="my-2 text-white fs-18">{{ $stats['total'] }}</h3>
                        <p class="mb-0 text-white-50 fs-11">Registros no período</p>
                    </div>
                    <div class="avatar-sm flex-shrink-0">
                        <span class="avatar-title bg-white bg-opacity-25 text-white rounded rounded-3 fs-3 widget-icon-box-avatar shadow">
                            <i class="ri-file-list-3-line"></i>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    {{-- Repetir para cada métrica: text-bg-success (aprovadas), text-bg-danger (canceladas), text-bg-warning (valor) --}}
</div>
```

**Cores recomendadas por tipo de métrica:**

| Métrica | Cor Bootstrap | Ícone sugerido |
|---------|---------------|----------------|
| Total geral | `text-bg-info` | `ri-file-list-3-line` |
| Aprovados/Ativos | `text-bg-success` | `ri-checkbox-circle-line` |
| Cancelados/Inativos | `text-bg-danger` | `ri-close-circle-line` |
| Valor monetário | `text-bg-warning` | `ri-money-dollar-circle-line` |
| Pendentes/Novos | `text-bg-primary` | `ri-time-line` |
| Quantidade/Volume | `text-bg-dark` | `ri-stack-line` |

---


```blade
@extends('layouts.app', ['title' => 'Nome do Módulo'])

@section('css')
<style>
/* Cole aqui o CSS Completo (ver seção 📦 CSS Completo) */
</style>
@endsection

@section('content')
<div class="mt-3 text-dark">
    <div class="row">
        <div class="card">

            <!-- ═══ CABEÇALHO PREMIUM ═══ -->
            <div class="card-header modulo-header-gradient">
                <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                    <div>
                        <h4 class="modulo-title text-white">
                            <i class="ri-ICONE-DA-TELA-line"></i>
                            Nome do Módulo
                        </h4>
                        <p class="modulo-subtitle">
                            Breve descrição do propósito desta tela de listagem.
                        </p>
                    </div>
                    <div>
                        @can('modulo_create')
                        <a href="{{ route('modulo.create') }}" class="btn btn-success">
                            <i class="ri-add-circle-fill"></i> Novo Registro
                        </a>
                        @endcan
                    </div>
                </div>
            </div>

            <div class="card-body">

                <!-- ═══ FILTROS PREMIUM ═══ -->
                <div class="modulo-glass-filter-premium">
                    <div class="filtro-premium-header">
                        <h5 class="filtro-premium-title">
                            <i class="ri-search-line"></i> Filtrar Registros
                        </h5>
                    </div>

                    {!!Form::open()->fill(request()->all())->get()!!}
                    <div class="row g-3">
                        <div class="col-md-4 col-12">
                            <label class="form-label"><i class="ri-user-line"></i> Nome / Descrição</label>
                            {!!Form::text('nome', '')->attrs(['class' => 'form-control', 'placeholder' => 'Digite para pesquisar...'])!!}
                        </div>
                        <div class="col-md-2 col-6">
                            <label class="form-label"><i class="ri-calendar-line"></i> Data Inicial</label>
                            {!!Form::date('start_date', '')->attrs(['class' => 'form-control'])!!}
                        </div>
                        <div class="col-md-2 col-6">
                            <label class="form-label"><i class="ri-calendar-line"></i> Data Final</label>
                            {!!Form::date('end_date', '')->attrs(['class' => 'form-control'])!!}
                        </div>
                        <div class="col-md-2 col-6">
                            <label class="form-label"><i class="ri-equalizer-line"></i> Status</label>
                            {!!Form::select('status', '', ['' => 'Todos', '1' => 'Ativo', '0' => 'Inativo'])->attrs(['class' => 'form-select'])!!}
                        </div>
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

                <!-- ═══ TABELA PREMIUM ═══ -->
                <div class="col-12">
                    <div class="table-responsive-sm">
                        <table class="table table-centered">
                            <thead>
                                <tr>
                                    @can('modulo_delete')
                                    <th style="width: 40px;">
                                        <div class="form-check mb-0">
                                            <input class="form-check-input" type="checkbox" id="select-all-checkbox">
                                        </div>
                                    </th>
                                    @endcan
                                    <th>Nome</th>
                                    <th>Status</th>
                                    <th width="10%">Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($data as $item)
                                <tr>
                                    @can('modulo_delete')
                                    <td>
                                        <div class="form-check mb-0">
                                            <input class="form-check-input check-delete" type="checkbox"
                                                   name="item_delete[]" value="{{ $item->id }}">
                                        </div>
                                    </td>
                                    @endcan
                                    <td>
                                        <strong>{{ $item->nome }}</strong>
                                        {{-- CPF/CNPJ ou dado secundário SEMPRE em span abaixo, nunca em coluna separada --}}
                                        <span class="text-muted d-block fs-11">{{ $item->cpf_cnpj ?? '' }}</span>
                                    </td>
                                    <td>
                                        @if($item->ativo)
                                        <span class="badge bg-success-subtle"><i class="ri-checkbox-circle-line"></i> Ativo</span>
                                        @else
                                        <span class="badge bg-danger-subtle"><i class="ri-close-circle-line"></i> Inativo</span>
                                        @endif
                                    </td>
                                    <td>
                                        <form action="{{ route('modulo.destroy', $item->id) }}" method="post"
                                              id="form-{{$item->id}}" class="d-flex align-items-center gap-1" style="width: auto;">
                                            @method('delete')
                                            @csrf
                                            @can('modulo_edit')
                                            <a class="btn btn-warning btn-sm text-white"
                                               href="{{ route('modulo.edit', $item->id) }}" title="Editar">
                                                <i class="ri-pencil-fill"></i>
                                            </a>
                                            @endcan
                                            @can('modulo_delete')
                                            <button type="button" class="btn btn-delete btn-sm btn-danger" title="Excluir">
                                                <i class="ri-delete-bin-line"></i>
                                            </button>
                                            @endcan
                                        </form>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="text-center text-muted py-4">Nenhum registro encontrado.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- ═══ FOOTER (Lote + Paginação) ═══ -->
                <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mt-3">
                    <div>
                        @can('modulo_delete')
                        <form action="{{ route('modulo.destroy-select') }}" method="post" id="form-delete-select" class="m-0">
                            @method('delete')
                            @csrf
                            <button type="button" class="btn btn-outline-danger btn-sm btn-delete-all" disabled>
                                <i class="ri-delete-bin-line align-middle me-1"></i> Remover Selecionados
                            </button>
                        </form>
                        @endcan
                    </div>
                    <div>
                        {!! $data->appends(request()->all())->links() !!}
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
@endsection

@section('js')
<script type="text/javascript" src="/js/delete_selecionados.js"></script>
@endsection
```

---

## 📝 TEMPLATE COMPLETO — TELA CREATE (Cadastro)

```blade
@extends('layouts.app', ['title' => 'Novo Registro'])

@section('css')
<style>
/* Cole aqui o CSS Completo (ver seção 📦 CSS Completo) */
</style>
@endsection

@section('content')
<div class="mt-3 text-dark">
    <div class="row justify-content-center">
        <div class="col-lg-12">
            <div class="card border-0 shadow-sm modulo-form-card">

                <!-- ═══ CABEÇALHO PREMIUM ═══ -->
                <div class="card-header modulo-header-gradient py-3 px-4">
                    <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                        <div>
                            <h4 class="mb-1 modulo-title d-flex align-items-center gap-2">
                                <i class="ri-add-circle-line"></i>
                                Novo Registro
                            </h4>
                            <p class="text-muted mb-0 modulo-subtitle fs-13">
                                Preencha os campos abaixo para cadastrar um novo registro.
                            </p>
                        </div>
                        <div>
                            <a href="{{ route('modulo.index') }}" class="btn btn-light btn-sm px-3 text-dark">
                                <i class="ri-arrow-left-line align-middle me-1"></i> Voltar
                            </a>
                        </div>
                    </div>
                </div>

                <!-- ═══ CORPO DO FORMULÁRIO ═══ -->
                <div class="card-body p-4">
                    {!!Form::open()->post()->route('modulo.store')->multipart()!!}

                    @include('modulo._forms')

                    {{--
                        ⚠️ ATENÇÃO — BOTÕES DUPLICADOS:
                        Antes de adicionar o bloco modulo-actions abaixo,
                        SEMPRE verifique se o _forms.blade.php já tem botões Salvar/Cancelar.
                        Se o _forms já tiver, NÃO inclua o bloco abaixo.
                        Se o _forms NÃO tiver botões, use o bloco abaixo:
                    --}}

                    {{-- Só adicionar este bloco se o _forms.blade.php NÃO tiver botões --}}
                    <div class="modulo-actions">
                        <div class="d-flex gap-2 justify-content-end">
                            <a href="{{ route('modulo.index') }}" class="btn btn-outline-secondary">
                                <i class="ri-close-line align-middle me-1"></i> Cancelar
                            </a>
                            <button type="submit" class="btn btn-success px-4" id="btn-store">
                                <i class="ri-save-line align-middle me-1"></i> Salvar
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
```

---

## ✏️ TEMPLATE COMPLETO — TELA EDIT (Edição)

```blade
@extends('layouts.app', ['title' => 'Editar Registro'])

@section('css')
<style>
/* Cole aqui o CSS Completo (ver seção 📦 CSS Completo) */
</style>
@endsection

@section('content')
<div class="mt-3 text-dark">
    <div class="row justify-content-center">
        <div class="col-lg-12">
            <div class="card border-0 shadow-sm modulo-form-card">

                <!-- ═══ CABEÇALHO PREMIUM ═══ -->
                <div class="card-header modulo-header-gradient py-3 px-4">
                    <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                        <div>
                            <h4 class="mb-1 modulo-title d-flex align-items-center gap-2">
                                <i class="ri-edit-line"></i>
                                Editar Registro
                            </h4>
                            <p class="text-muted mb-0 modulo-subtitle fs-13">
                                Atualize os dados do registro selecionado.
                            </p>
                        </div>
                        <div>
                            <a href="{{ route('modulo.index') }}" class="btn btn-light btn-sm px-3 text-dark">
                                <i class="ri-arrow-left-line align-middle me-1"></i> Voltar
                            </a>
                        </div>
                    </div>
                </div>

                <!-- ═══ CORPO DO FORMULÁRIO ═══ -->
                <div class="card-body p-4">
                    {!!Form::open()->fill($item)->put()->route('modulo.update', [$item->id])->multipart()!!}

                    @include('modulo._forms')

                    <!-- Botões de Ação -->
                    <div class="modulo-actions">
                        <div class="d-flex gap-2 justify-content-end">
                            <a href="{{ route('modulo.index') }}" class="btn btn-outline-secondary">
                                <i class="ri-close-line align-middle me-1"></i> Cancelar
                            </a>
                            <button type="submit" class="btn btn-primary px-4" id="btn-store">
                                <i class="ri-save-line align-middle me-1"></i> Salvar Alterações
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
```

---

## 👁️ TEMPLATE COMPLETO — TELA SHOW (Visualização/Detalhes)

```blade
@extends('layouts.app', ['title' => 'Detalhes do Registro'])

@section('css')
<style>
/* Cole aqui o CSS Completo (ver seção 📦 CSS Completo) */
</style>
@endsection

@section('content')
<div class="mt-3 text-dark">
    <div class="row justify-content-center">
        <div class="col-lg-12">
            <div class="card border-0 shadow-sm modulo-form-card">

                <!-- ═══ CABEÇALHO PREMIUM ═══ -->
                <div class="card-header modulo-header-gradient py-3 px-4">
                    <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                        <div>
                            <h4 class="mb-1 modulo-title d-flex align-items-center gap-2">
                                <i class="ri-eye-line"></i>
                                Detalhes do Registro #{{ $item->id }}
                            </h4>
                            <p class="text-muted mb-0 modulo-subtitle fs-13">
                                Visualização completa dos dados do registro.
                            </p>
                        </div>
                        <div class="d-inline-flex gap-2">
                            @can('modulo_edit')
                            <a href="{{ route('modulo.edit', $item->id) }}" class="btn btn-warning btn-sm text-white">
                                <i class="ri-pencil-line align-middle me-1"></i> Editar
                            </a>
                            @endcan
                            <a href="{{ route('modulo.index') }}" class="btn btn-light btn-sm px-3 text-dark">
                                <i class="ri-arrow-left-line align-middle me-1"></i> Voltar
                            </a>
                        </div>
                    </div>
                </div>

                <!-- ═══ CORPO ═══ -->
                <div class="card-body p-4">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="fw-bold text-muted fs-12 text-uppercase">Nome</label>
                            <p class="mb-0 text-dark fw-semibold">{{ $item->nome }}</p>
                        </div>
                        <div class="col-md-3">
                            <label class="fw-bold text-muted fs-12 text-uppercase">Status</label>
                            <p class="mb-0">
                                @if($item->ativo)
                                <span class="badge bg-success-subtle text-success border border-success-subtle px-2 py-1">Ativo</span>
                                @else
                                <span class="badge bg-danger-subtle text-danger border border-danger-subtle px-2 py-1">Inativo</span>
                                @endif
                            </p>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
@endsection
```

---

## 🧩 COMPONENTES REUTILIZÁVEIS

### Badges de Status (Sim/Não — para campos booleanos)

```html
<!-- SIM -->
<span class="badge bg-success-subtle text-success border border-success-subtle px-2 py-1 fs-11">Sim</span>

<!-- NÃO -->
<span class="badge bg-danger-subtle text-danger border border-danger-subtle px-2 py-1 fs-11">Não</span>

<!-- ATIVO -->
<span class="badge bg-success-subtle text-success border border-success-subtle px-2 py-1 fs-11">
    <i class="ri-check-line me-1"></i>Ativo
</span>

<!-- INATIVO -->
<span class="badge bg-danger-subtle text-danger border border-danger-subtle px-2 py-1 fs-11">
    <i class="ri-close-line me-1"></i>Inativo
</span>
```

### Grupo de Botões de Ação (na tabela)

> **⚠️ Regra crítica**: `flex-wrap: nowrap` para NUNCA empilhar botões verticalmente.

```html
<form action="{{ route('modulo.destroy', $item->id) }}" method="post" id="form-{{$item->id}}" class="m-0">
    @method('delete')
    @csrf
    <div class="modulo-action-group">
        @can('modulo_show')
        <a class="btn btn-info btn-sm text-white" href="{{ route('modulo.show', $item->id) }}" title="Visualizar">
            <i class="ri-eye-line"></i>
        </a>
        @endcan
        @can('modulo_edit')
        <a class="btn btn-warning btn-sm text-white" href="{{ route('modulo.edit', $item->id) }}" title="Editar">
            <i class="ri-pencil-line"></i>
        </a>
        @endcan
        @can('modulo_delete')
        <button type="button" class="btn btn-danger btn-sm btn-delete" title="Excluir">
            <i class="ri-delete-bin-line"></i>
        </button>
        @endcan
    </div>
</form>
```

### KPI Cards (Cards de Resumo no topo da listagem)

O ERP utiliza os cards de resumo no padrão de widgets da página principal (`widget-icon-box`). Esse estilo possui fundos coloridos, cantos arredondados, texto com boa legibilidade e ícones destacados de forma moderna.

```html
<div class="row g-3 mb-4">
    <div class="col-md-3 col-6">
        <div class="card widget-icon-box text-bg-info mb-0">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div class="flex-grow-1 overflow-hidden">
                        <h4 class="text-uppercase fs-12 mt-0 text-white-50">Total de Contas</h4>
                        <h3 class="my-2 text-white fs-18">10</h3>
                    </div>
                    <div class="avatar-sm flex-shrink-0">
                        <span class="avatar-title bg-white bg-opacity-25 text-white rounded rounded-3 fs-3 widget-icon-box-avatar shadow">
                            <i class="ri-file-text-line"></i>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
```

**Cores de fundo disponíveis (Bootstrap)**: `text-bg-info` (azul) | `text-bg-success` (verde) | `text-bg-danger` (vermelho) | `text-bg-warning` (amarelo) | `text-bg-primary` (índigo) | `text-bg-dark` (cinza escuro)

### Seções em Formulários Longos (`_forms.blade.php`)

```html
<div class="row g-3 text-dark">
    <!-- Seção 1 -->
    <div class="col-12 mt-4">
        <h5 class="section-title"><i class="ri-information-line"></i> 1. Informações Básicas</h5>
        <div class="row g-3">
            <div class="col-md-6 col-12">
                {!!Form::text('nome', 'Nome')->attrs(['class' => 'form-control'])->required()!!}
            </div>
            <div class="col-md-3 col-6">
                {!!Form::select('status', 'Status', [1 => 'Ativo', 0 => 'Inativo'])->attrs(['class' => 'form-select'])!!}
            </div>
        </div>
    </div>

    <!-- Seção 2: Inputs Complexos -->
    <div class="col-12 mt-4">
        <h5 class="section-title"><i class="ri-lock-password-line"></i> 2. Segurança e API</h5>
        <div class="row g-3">
            <div class="col-md-4">
                <label class="form-label required">Senha</label>
                <div class="input-group" id="show_hide_password">
                    <input required type="password" class="form-control" name="password" autocomplete="off">
                    <a class="input-group-text" style="cursor: pointer;"><i class='ri-eye-line'></i></a>
                </div>
            </div>
            <div class="col-md-4">
                <label class="form-label">Token API</label>
                <div class="input-group">
                    <input readonly type="text" class="form-control" id="api_token" name="token">
                    <button type="button" class="btn btn-info" id="btn_token"><i class="ri-refresh-line text-white"></i></button>
                </div>
            </div>
        </div>
    </div>

    <!-- Seção 3 -->
    <div class="col-12 mt-4">
        <h5 class="section-title"><i class="ri-map-pin-line"></i> 3. Endereço</h5>
        <div class="row g-3">
            <!-- campos aqui -->
        </div>
    </div>
</div>
```

---

## 📦 CSS COMPLETO (copie inteiro para o `@section('css')`)

```css
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

/* ─── Formulários de Filtro ─── */
.form-control, select {
    border: 1px solid #e2e8f0 !important;
    border-radius: 10px !important;
    padding: 10px 14px !important;
    font-size: 13px !important;
    color: #334155 !important;
    transition: all 0.2s ease !important;
    box-shadow: none !important;
}

.form-control:focus, select:focus {
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

.btn-success {
    background-color: #10b981 !important;
    border-color: #10b981 !important;
    color: #fff !important;
}

.btn-success:hover {
    background-color: #059669 !important;
    border-color: #059669 !important;
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(16, 185, 129, 0.2) !important;
}

.btn-primary {
    background-color: #4f46e5 !important;
    border-color: #4f46e5 !important;
    color: #fff !important;
}

.btn-primary:hover {
    background-color: #4338ca !important;
    border-color: #4338ca !important;
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(79, 70, 229, 0.2) !important;
}

.btn-danger {
    background-color: #ef4444 !important;
    border-color: #ef4444 !important;
    color: #fff !important;
}

.btn-danger:hover {
    background-color: #dc2626 !important;
    border-color: #dc2626 !important;
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(239, 68, 68, 0.2) !important;
}

.btn-info {
    background-color: #0ea5e9 !important;
    border-color: #0ea5e9 !important;
    color: #fff !important;
    border-radius: 10px !important;
}

.btn-info:hover {
    background-color: #0284c7 !important;
    border-color: #0284c7 !important;
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(14, 165, 233, 0.2) !important;
}

/* ─── Input Groups (Senha / Tokens) ─── */
.input-group .form-control {
    border-top-right-radius: 0 !important;
    border-bottom-right-radius: 0 !important;
}

.input-group-text {
    border: 1px solid #e2e8f0 !important;
    border-left: none !important;
    border-top-right-radius: 10px !important;
    border-bottom-right-radius: 10px !important;
    background-color: #ffffff !important;
    color: #475569 !important;
    display: flex;
    align-items: center;
    padding: 0 14px !important;
}

/* ─── Upload Customizado ─── */
.file-certificado label {
    padding: 10px 16px;
    width: 100%;
    background-color: #8833ff;
    color: #fff;
    text-transform: uppercase;
    text-align: center;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    margin-top: 20px;
    cursor: pointer;
    border-radius: 10px;
    font-weight: 600;
    font-size: 13px;
    transition: all 0.2s ease;
}

.file-certificado label:hover {
    background-color: #7026db;
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(136, 51, 255, 0.2);
}

.file-certificado input[type="file"] {
    display: none;
}

/* ─── Tabelas ─── */
.table-responsive, .table-responsive-sm {
    border-radius: 12px;
    overflow-x: auto !important;
    border: 1px solid rgba(0, 0, 0, 0.05);
}

.table {
    margin-bottom: 0 !important;
    width: 100%;
    border-collapse: collapse;
}

.table thead th {
    background-color: #f8fafc !important;
    color: #475569 !important;
    font-size: 11px !important;
    font-weight: 600 !important;
    text-transform: uppercase !important;
    letter-spacing: 0.06em !important;
    padding: 14px 20px !important;
    border-bottom: 1px solid rgba(0, 0, 0, 0.06) !important;
    border-top: none !important;
}

.table tbody tr {
    transition: background-color 0.2s ease;
}

.table tbody tr:hover {
    background-color: #f8fafc !important;
}

.table tbody td {
    padding: 14px 20px !important;
    vertical-align: middle !important;
    font-size: 13px !important;
    color: #334155 !important;
    border-bottom: 1px solid rgba(0, 0, 0, 0.04) !important;
}

.table tbody tr:last-child td {
    border-bottom: none !important;
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

/* ─── Responsivo ─── */
@media (max-width: 768px) {
    .modulo-header-gradient .modulo-title { font-size: 18px; }
    .modulo-kpi-card .kpi-value { font-size: 18px; }
}
```

---

## ⚡ REGRAS DE COMPATIBILIDADE TÉCNICA (NUNCA QUEBRAR)

> ⚠️ **Crítico**: O ERP possui scripts JS globais que dependem de IDs, classes e estruturas específicas. Violar estas regras causa falhas silenciosas.

### IDs de Inputs — NUNCA ALTERAR

O FormBuilder gera IDs automáticos (`inp-nome_do_campo`). Estes IDs são usados por scripts AJAX, máscaras e autocomplete. **Nunca renomeie:**

- `inp-plano_conta_id` → Autocomplete do plano de contas
- `inp-cidade_id`, `inp-novo_cidade_id` → Autocomplete de cidades
- `inp-fornecedor_id`, `inp-cliente_id` → Pesquisa rápida
- `inp-carteira`, `inp-convenio`, `inp-tipo` → Cálculo de taxas de boleto
- `inp-local_id` → Vínculo de estabelecimentos

### Classes JS — NUNCA REMOVER

| Classe | Função |
|--------|--------|
| `moeda` | Máscara monetária R$ |
| `cpf_cnpj` | Máscara de documento |
| `cep` | Máscara de CEP |
| `check-delete` | Checkbox de seleção em lote |
| `btn-delete` | Gatilho de confirmação de exclusão |
| `btn-delete-all` | Gatilho de exclusão em lote |

### Select2 com Bootstrap 5

**NÃO use** `theme: "bootstrap4"` na inicialização do Select2 — o tema quebra o layout no Bootstrap 5:

```js
// ✅ CORRETO
$("#inp-campo_id").select2({
    minimumInputLength: 2,
    language: "pt-BR",
    width: "100%",
    ajax: { /* ... */ }
});

// ❌ ERRADO — não usar theme
$("#inp-campo_id").select2({
    theme: "bootstrap4", // REMOVER
    // ...
});
```

Para Select2 em formulários, adicionar as classes corretas:

```html
{{-- ✅ CORRETO --}}
<select class="select2 form-control" style="width: 100%">
```

### Blade — Evitar Diretivas Inline em Loops

Nunca escrever `@if` e `@endif` na mesma linha dentro de `@foreach`:

```blade
{{-- ❌ ERRADO — causa erro 500 de compilação --}}
@foreach($items as $item) @if($item->ativo) <span>Ativo</span> @endif @endforeach

{{-- ✅ CORRETO — sempre multilinha --}}
@foreach($items as $item)
    @if($item->ativo)
        <span>Ativo</span>
    @endif
@endforeach
```

### Scripts JS no Final

```blade
@section('js')
<script type="text/javascript" src="/js/meu_modulo.js"></script>
<script type="text/javascript" src="/js/delete_selecionados.js"></script>
@endsection
```

---

## 🔍 CHECKLIST RÁPIDO ANTES DE ENTREGAR UMA TELA

- [ ] Header usa `.modulo-header-gradient` com título branco e ícone com fundo translúcido
- [ ] Botão de ação principal "Novo Registro" usa a classe `btn btn-success` no header, alinhado à direita
- [ ] Filtros em colunas Bootstrap dentro do `.card-body`, sem o wrapper `.modulo-glass-filter`
- [ ] Botão Limpar tem texto e ícone visível: `<i class="ri-eraser-fill"></i> Limpar`
- [ ] Tabela usa `.table-responsive-sm` e as classes de tabela nativas do Bootstrap
- [ ] CPF/CNPJ ou dado secundário em `<span class="text-muted d-block fs-11">` abaixo do nome, não em coluna separada
- [ ] Ações na tabela alinhadas lado a lado (`d-flex align-items-center gap-1`)
- [ ] Form de exclusão com `@method('delete')` + `@csrf` + `btn-delete` no botão
- [ ] `@section('css')` com todo o CSS da seção "📦 CSS Completo"
- [ ] Paginação: 10 itens por página
