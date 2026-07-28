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
        <div class="card border-0 shadow-sm text-dark">

            <!-- ═══ CABEÇALHO PREMIUM ═══ -->
            <div class="card-header modulo-header-gradient py-3 px-4">
                <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                    <div>
                        <h4 class="mb-1 modulo-title d-flex align-items-center gap-2">
                            <i class="ri-ICONE-DA-TELA-line"></i>
                            Nome do Módulo
                        </h4>
                        <p class="text-muted mb-0 modulo-subtitle fs-13">
                            Breve descrição do propósito desta tela.
                        </p>
                    </div>
                    <div class="d-inline-flex gap-2">
                        @can('modulo_create')
                        <a href="{{ route('modulo.create') }}" class="btn btn-light btn-sm px-3 text-dark">
                            <i class="ri-add-circle-line align-middle me-1"></i> Novo Registro
                        </a>
                        @endcan
                    </div>
                </div>
            </div>

            <div class="card-body p-4">

                <!-- ═══ FILTROS GLASS ═══ -->
                <div class="modulo-glass-filter p-3 mb-4">
                    {!!Form::open()->fill(request()->all())->get()!!}
                    <div class="row g-2 align-items-end">
                        <div class="col-md-4 col-12">
                            {!!Form::text('nome', 'Pesquisar por nome')!!}
                        </div>
                        <div class="col-md-2 col-6">
                            {!!Form::date('data_inicio', 'Data Início')!!}
                        </div>
                        <div class="col-md-2 col-6">
                            {!!Form::date('data_fim', 'Data Fim')!!}
                        </div>
                        <div class="col-md-2 col-6">
                            <button class="btn btn-primary btn-sm w-100" type="submit">
                                <i class="ri-search-line me-1"></i> Pesquisar
                            </button>
                        </div>
                        <div class="col-md-2 col-6">
                            <a class="btn btn-danger btn-sm w-100" href="{{ route('modulo.index') }}">
                                <i class="ri-eraser-line me-1"></i> Limpar
                            </a>
                        </div>
                    </div>
                    {!!Form::close()!!}
                </div>

                <!-- ═══ TABELA PREMIUM ═══ -->
                <div class="modulo-table-wrap">
                    <div class="table-responsive">
                        <table class="table table-centered table-hover align-middle mb-0 text-dark">
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
                                    <th class="text-end" style="width: 120px;">Ações</th>
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
                                        <span class="fw-semibold text-dark d-block">{{ $item->nome }}</span>
                                        {{-- CPF/CNPJ ou dado secundário SEMPRE em span abaixo, nunca em coluna separada --}}
                                        <span class="text-muted fs-11">{{ $item->cpf_cnpj ?? '' }}</span>
                                    </td>
                                    <td>
                                        @if($item->ativo)
                                        <span class="badge bg-success-subtle text-success border border-success-subtle px-2 py-1 fs-11">
                                            <i class="ri-check-line me-1"></i>Ativo
                                        </span>
                                        @else
                                        <span class="badge bg-danger-subtle text-danger border border-danger-subtle px-2 py-1 fs-11">
                                            <i class="ri-close-line me-1"></i>Inativo
                                        </span>
                                        @endif
                                    </td>
                                    <td class="text-end">
                                        {{-- IMPORTANTE: form de exclusão envolve todos os botões de ação --}}
                                        <form action="{{ route('modulo.destroy', $item->id) }}" method="post"
                                              id="form-{{$item->id}}" class="m-0">
                                            @method('delete')
                                            @csrf
                                            <div class="modulo-action-group">
                                                @can('modulo_show')
                                                <a class="btn btn-info btn-sm text-white"
                                                   href="{{ route('modulo.show', $item->id) }}" title="Visualizar">
                                                    <i class="ri-eye-line"></i>
                                                </a>
                                                @endcan
                                                @can('modulo_edit')
                                                <a class="btn btn-warning btn-sm text-white"
                                                   href="{{ route('modulo.edit', $item->id) }}" title="Editar">
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
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4">
                                        <div class="modulo-empty">
                                            <i class="ri-inbox-2-line"></i>
                                            <p>Nenhum registro encontrado.</p>
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- ═══ FOOTER (Lote + Paginação) ═══ -->
                <div class="modulo-footer">
                    <div>
                        @can('modulo_delete')
                        <form action="{{ route('modulo.destroy-select') }}" method="post" id="form-delete-select" class="m-0">
                            @method('delete')
                            @csrf
                            <button type="button" class="btn btn-outline-danger btn-sm btn-delete-all" disabled>
                                <i class="ri-delete-bin-line align-middle me-1"></i> Remover Selecionadas
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
    <div class="col-12">
        <h5 class="text-dark border-bottom pb-2 mb-3">
            <i class="ri-information-line text-primary me-2 align-middle fs-18"></i>
            1. Informações Básicas
        </h5>
        <div class="row g-3">
            <div class="col-md-6 col-12">
                {!!Form::text('nome', 'Nome')->required()!!}
            </div>
            <div class="col-md-3 col-6">
                {!!Form::select('status', 'Status', [1 => 'Ativo', 0 => 'Inativo'])->attrs(['class' => 'form-select'])!!}
            </div>
        </div>
    </div>

    <!-- Seção 2 -->
    <div class="col-12 mt-4">
        <h5 class="text-dark border-bottom pb-2 mb-3">
            <i class="ri-map-pin-line text-primary me-2 align-middle fs-18"></i>
            2. Endereço
        </h5>
        <div class="row g-3">
            <!-- campos aqui -->
        </div>
    </div>
</div>
```

---

## 📦 CSS COMPLETO (copie inteiro para o `@section('css')`)

```css
/* ─── Header Gradiente ─── */
.modulo-header-gradient { background: linear-gradient(135deg, #0f0c29 0%, #302b63 50%, #24243e 100%); border-radius: 12px 12px 0 0 !important; border-bottom: none !important; }
.modulo-header-gradient .modulo-title { color: #fff; font-weight: 700; letter-spacing: -0.3px; }
.modulo-header-gradient .modulo-title i { background: rgba(255,255,255,0.12); padding: 8px; border-radius: 10px; color: #a8b5ff; }
.modulo-header-gradient .modulo-subtitle { color: rgba(255,255,255,0.6) !important; font-weight: 400; }
.modulo-header-gradient .btn { border-radius: 8px; font-weight: 600; transition: all 0.2s ease; }
.modulo-header-gradient .btn:hover { transform: translateY(-1px); box-shadow: 0 4px 14px rgba(0,0,0,0.25); }

/* ─── Glass Filters ─── */
.modulo-glass-filter { background: rgba(255,255,255,0.7); backdrop-filter: blur(12px); -webkit-backdrop-filter: blur(12px); border: 1px solid rgba(255,255,255,0.8) !important; border-radius: 12px; box-shadow: 0 2px 20px rgba(0,0,0,0.04); }
.modulo-glass-filter label { font-weight: 600; font-size: 11px; text-transform: uppercase; letter-spacing: 0.4px; color: #5a5a7a; margin-bottom: 2px; }
.modulo-glass-filter .form-control,
.modulo-glass-filter .form-select { height: 38px; }
.modulo-glass-filter .btn { border-radius: 8px; font-weight: 600; font-size: 13px; height: 38px; padding-top: 0; padding-bottom: 0; display: inline-flex; align-items: center; justify-content: center; transition: all 0.2s; }
.modulo-glass-filter .btn:hover { transform: translateY(-1px); }

/* ─── Premium Table ─── */
.modulo-table-wrap { border-radius: 12px; border: 1px solid #eef0f5; overflow: hidden; }
.modulo-table-wrap table { margin-bottom: 0; }
.modulo-table-wrap thead th { background: #f8f9fc; color: #5a5a7a; font-weight: 700; font-size: 11px; text-transform: uppercase; letter-spacing: 0.4px; padding: 12px 14px; border-bottom: 2px solid #e8eaf6; }
.modulo-table-wrap tbody td { padding: 12px 14px; vertical-align: middle; border-bottom: 1px solid #f0f2f8; transition: background 0.15s ease; font-size: 13px; }
.modulo-table-wrap tbody tr { transition: all 0.15s ease; }
.modulo-table-wrap tbody tr:hover { background: #f5f6fe; }
.modulo-table-wrap tbody tr:last-child td { border-bottom: none; }
.modulo-table-wrap tbody tr.clickable { cursor: pointer; }

/* ─── Action Buttons — SEMPRE lado a lado (flex-wrap: nowrap é obrigatório) ─── */
.modulo-action-group { display: inline-flex; gap: 4px; flex-wrap: nowrap; align-items: center; }
.modulo-action-group .btn { border-radius: 8px; padding: 4px 10px; font-size: 13px; transition: all 0.15s ease; }
.modulo-action-group .btn:hover { transform: translateY(-1px); }

/* ─── KPI Cards Premium (Widget Icon Box) ─── */
/* Os estilos .widget-icon-box e .widget-icon-box-avatar são disponibilizados globalmente pelo ERP. */

/* ─── Form Card (Create/Edit) ─── */
.modulo-form-card { border: 1px solid #eef0f5; border-radius: 12px; overflow: hidden; }
.modulo-form-card .card-body { background: #fff; }
.modulo-form-card .form-label,
.modulo-form-card label:not(.form-check-label) { font-weight: 600; font-size: 12px; color: #5a5a7a; margin-bottom: 4px; }
.modulo-form-card .form-control,
.modulo-form-card .form-select { border-radius: 8px; border-color: #e0e3eb; font-size: 13px; padding: 8px 12px; transition: all 0.15s ease; }
.modulo-form-card .form-control:focus,
.modulo-form-card .form-select:focus { border-color: #302b63; box-shadow: 0 0 0 3px rgba(48,43,99,0.08); }

/* ─── Botões de Ação do Formulário ─── */
.modulo-actions { padding: 16px 0 0; border-top: 1px solid #f0f2f8; margin-top: 24px; }
.modulo-actions .btn { border-radius: 8px; font-weight: 600; font-size: 13px; padding: 8px 20px; transition: all 0.2s ease; }
.modulo-actions .btn:hover { transform: translateY(-1px); }

/* ─── Empty State ─── */
.modulo-empty { padding: 48px 20px; text-align: center; }
.modulo-empty i { font-size: 48px; color: #c5cae9; margin-bottom: 12px; display: block; }
.modulo-empty p { color: #9e9eb8; font-size: 14px; margin: 0; }

/* ─── Footer da Tabela ─── */
.modulo-footer { padding: 16px 0 0; display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 12px; }
.modulo-footer .modulo-total-label { font-size: 13px; color: #5a5a7a; font-weight: 600; }
.modulo-footer .modulo-total-value { font-size: 18px; font-weight: 800; color: #2e7d32; letter-spacing: -0.3px; }

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
- [ ] Botão de ação principal usa `btn-light btn-sm text-dark` no header
- [ ] Filtros dentro de `.modulo-glass-filter`
- [ ] Botão Limpar tem texto visível: `<i class="ri-eraser-line me-1"></i> Limpar`
- [ ] Tabela dentro de `.modulo-table-wrap`
- [ ] Botões de ação usam `.modulo-action-group` com `flex-wrap: nowrap`
- [ ] Form de exclusão com `@method('delete')` + `@csrf` + `btn-delete` no botão
- [ ] CPF/CNPJ em `<span class="text-muted fs-11">` abaixo do nome, não em coluna separada
- [ ] Estado vazio com `.modulo-empty` quando não há dados
- [ ] Footer com paginação usando `.modulo-footer`
- [ ] `@section('css')` com todo o CSS do bloco "📦 CSS Completo"
- [ ] Paginação: 10 itens por página
