---
name: erp-layout-modernization
description: Guia completo de padronização de layouts do ERP. Define o padrão visual premium para todas as telas (Index, Create, Edit, Show), com templates prontos para copiar e regras de compatibilidade técnica.
---

# 🎨 Padrão de Layout Premium — ERP (v2)

> **Objetivo**: Qualquer IA que ler este arquivo deve ser capaz de criar ou atualizar qualquer tela do ERP seguindo exatamente o mesmo padrão visual, sem precisar ver outras telas como referência.

---

## 🧭 IMPORTANTE — DUAS CAMADAS DE ESTILO

O sistema tem **2 camadas** de estilo. Você deve entender as duas antes de mexer em qualquer view:

### 1. Camada Global (NÃO copiar para as views)

O arquivo **`public/css/dashboard-skin.css`** é carregado no `layouts/app.blade.php` e aplica o tema premium a **TODAS** as telas. Ele já cuida de:

- **Header das telas** (`.modulo-header-gradient`) → fundo índigo claro discreto + título escuro (via `!important`). **NÃO redefine o gradiente escuro na view.**
- **Topbar** (busca, chip de plano, botões Upgrade/Tour/PDV, ícones).
- **Sidebar** (item ativo em pílula roxa, card do usuário).
- **Cards** (borda, raio 16px, sombra suave) e **botões** `.dash-btn`, `.seg-control`, `.page-title`.

> Como o skin sobrescreve `.modulo-header-gradient` globalmente, basta a view usar `class="card-header modulo-header-gradient"` e os títulos `.modulo-title` / `.modulo-subtitle` — a aparência (fundo claro + texto escuro) já vem do skin.

### 2. Camada Local (por view)

Cada view adiciona seu próprio CSS no `@section('css')` para os componentes **específicos** do módulo: cards de estatística, tabela, filtro, action buttons, seções de formulário. **Estes CSS são os que você deve copiar de exemplo.**

---

## 🎨 DESIGN TOKENS (use estes valores)

| Token | Valor | Uso |
|-------|-------|-----|
| `--skin-primary` | `#4f46e5` | Cor principal (índigo) — botões, ativo, destaque |
| `--skin-primary-hover` | `#4338ca` | Hover do primário |
| `--skin-primary-soft` | `#eef0ff` | Fundo suave índigo (filtros, chips) |
| `--skin-bg` | `#f3f5fa` | Fundo da página |
| `--skin-surface` | `#ffffff` | Fundo de cards |
| `--skin-border` | `#e9ecf3` | Bordas |
| `--skin-text` | `#1f2937` | Texto principal |
| `--skin-muted` | `#64748b` | Texto secundário |
| `--skin-soft` | `#94a3b8` | Texto discreto / placeholder |
| `--skin-primary-soft` | `#eef0ff` | Hover suave |

Cores dos cards de estatística (gradientes):
- índigo `linear-gradient(135deg,#6366f1,#4f46e5)`
- verde `linear-gradient(135deg,#24c98a,#109f61)`
- vermelho `linear-gradient(135deg,#fb7185,#dc2626)`
- azul `linear-gradient(135deg,#4d94ff,#1d4ed8)`
- âmbar `linear-gradient(135deg,#fbbf24,#d97706)`

---

## 🗂️ QUAL PADRÃO USAR EM CADA TELA?

```
┌─────────────────────────────────────────────────────────────┐
│  TELA DE LISTAGEM (Index) — ordem obrigatória:              │
│  1. Cabeçalho: .modulo-header-gradient (via skin, claro)    │
│  2. Cards de estatística: .stat-card (coloridos)            │
│  3. Filtros: .filter-wrap                                   │
│  4. Tabela: .tb-wrap (coluna com avatar + action grid)      │
│  5. Footer: contagem + paginação                            │
├─────────────────────────────────────────────────────────────┤
│  TELAS DE FORMULÁRIO (Create / Edit)                        │
│  → Cabeçalho: .modulo-header-gradient                       │
│  → Botão Voltar: .dash-btn.dash-btn-light                   │
│  → Form: abrir no pai, campos no `_forms`, botões no _forms │
└─────────────────────────────────────────────────────────────┘
```

---

## 🧭 FLUXO DE TRABALHO (como modernizar uma tela — passo a passo)

> **Use SEMPRE este roteiro.** Quando o usuário passar uma rota, modernize **TODAS as telas vinculadas** (index, create, edit, show, `_forms` e parciais usados). Faça **um módulo por vez**, para não quebrar regras/calculos.

### Passo 1 — Mapear
- Liste as views em `resources/views/<modulo>/` e leia o controller (`index`, `create`, `edit`) para saber o que é passado (`compact(...)`).
- Identifique rotas, JS, IDs de inputs e máscaras que **não podem mudar**.

### Passo 2 — Controller (SÓ adicionar `$stats`)
- No `index`, crie uma query base e **conte as métricas SEMPRE no controller** (nunca `->where()` em cima do paginador).
- Padrão:
  ```php
  $base = Modelo::where('empresa_id', request()->empresa_id);
  $data = (clone $base)
      ->when(!empty($request->nome), fn($q) => $q->where('nome','like',"%$request->nome%"))
      ->paginate(env("PAGINACAO"));
  $stats = [
      'total'  => (clone $base)->count(),
      'ativos' => (clone $base)->where('status',1)->count(),   // apenas campos que existem
  ];
  return view('modulo.index', compact('data','stats'));
  ```
- ⚠️ Se não houver 2+ métricas seguras, use `total` + `$data->count()` (nesta página).
- ⚠️ **Nunca** use `whereHas` em relação quebrada/suspeita (ex.: um `hasMany` apontando para o modelo errado).
- ⚠️ Se usar `Auth::user()->empresa` no controller, **adicione `use Illuminate\Support\Facades\Auth;`** no topo (senão vira `App\Http\Controllers\Auth` → 500).

### Passo 3 — Index
- Header: `card-header modulo-header-gradient` + botões `dash-btn` (ação principal `dash-btn-primary`; demais `dash-btn-light`).
- KPI: `.stat-card` coloridos. Filtro: `.filter-wrap` (label + input + Buscar `btn-primary` + Limpar).
- Tabela: `.tb-wrap`; badges → `.pill`; ações → `.act-group`/`.act-btn` **ou** dropdown (veja "Posição da Coluna de Ações").
- Footer: contagem "Exibindo X de Y" + `{!! $data->appends(request()->all())->links() !!}`.

### Passo 4 — Create / Edit
- Cabeçalho + **Voltar** `dash-btn dash-btn-light`; focus dos inputs índigo (`#4f46e5`).
- `@include('modulo._forms')`. Botões Salvar/Cancelar ficam **no `_forms`** (`.uf-actions`).

### Passo 5 — `_forms`
- Seções `.uf-section-title`, campos `.uf-field`, rodapé `.uf-actions` (Cancelar `dash-btn-light` + Salvar `dash-btn-primary`).
- **NÃO alterar** IDs `inp-*`, classes JS, máscaras nem `@section('js')`.

### Passo 6 — Validar (obrigatório antes de entregar)
```
php artisan view:cache   # compila o Blade — pega erro `??`/sintaxe
php -l app/Http/Controllers/<Modulo>Controller.php
```
Confirme que não sobrou `{{-- ??? }}` nem CSS "solto" fora de `<style>`, e que a TELA não dá 500.

---

## 🏗️ ESTRUTURA BASE (toda tela começa assim)

```blade
@extends('layouts.app', ['title' => 'Nome da Tela'])

@section('css')
<style>
/* CSS local do módulo (copiar dos exemplos abaixo) */
</style>
@endsection

@section('content')
<div class="mt-3">
    <div class="row">
        <div class="card border-0 shadow-sm">
            <!-- CABEÇALHO -->
            <!-- CORPO -->
        </div>
    </div>
</div>
@endsection
```

---

## 📄 TEMPLATE COMPLETO — TELA INDEX (Listagem)

> Baseado no padrão da tela **Usuários**. Copie e adapte.

```blade
@extends('layouts.app', ['title' => 'Nome do Módulo'])

@section('css')
<style>
    /* 📌 Cole o bloco "CSS COMPLETO DA LISTAGEM" (final do arquivo) */
</style>
@endsection

@section('content')
<div class="mt-3">
    <div class="row">
        <div class="card border-0 shadow-sm">

            <!-- ═══ CABEÇALHO ═══ -->
            <div class="card-header modulo-header-gradient py-3 px-4">
                <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                    <div>
                        <h4 class="mb-1 modulo-title d-flex align-items-center gap-2">
                            <i class="ri-ICONE-DA-TELA-line"></i>
                            Nome do Módulo
                        </h4>
                        <p class="text-muted mb-0 modulo-subtitle fs-13">Descrição do propósito desta tela.</p>
                    </div>
                    <div class="d-flex gap-2 flex-wrap">
                        <a href="{{ route('modulo.index') }}" class="dash-btn dash-btn-light"><i class="ri-refresh-line"></i> Atualizar</a>
                        @can('modulo_create')
                        <a href="{{ route('modulo.create') }}" class="dash-btn dash-btn-primary"><i class="ri-add-line"></i> Novo Registro</a>
                        @endcan
                    </div>
                </div>
            </div>

            <div class="card-body p-4">

                <!-- ═══ CARDS DE ESTATÍSTICA ═══ -->
                <div class="row g-3 mb-3">
                    <div class="col-6 col-xl-3">
                        <div class="stat-card stat-indigo">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <div class="st-label">Total</div>
                                    <div class="st-value">{{ $stats['total'] }}</div>
                                    <div class="st-sub">descrição curta</div>
                                </div>
                                <div class="st-icon"><i class="ri-file-list-3-line"></i></div>
                            </div>
                        </div>
                    </div>
                    {{-- repita com stat-green / stat-blue / stat-amber conforme as métricas --}}
                </div>

                <!-- ═══ FILTROS ═══ -->
                <div class="filter-wrap">
                    <h5 class="filter-title mb-0"><i class="ri-search-line"></i> Filtrar Registros</h5>
                    <div class="mt-3">
                        {!!Form::open()->fill(request()->all())->get()!!}
                        <div class="row g-3 align-items-end">
                            <div class="col-md-8 col-12">
                                <label class="form-label"><i class="ri-user-line"></i> Nome / Descrição</label>
                                {!!Form::text('nome', '')->attrs(['class' => 'form-control', 'placeholder' => 'Digite para pesquisar...'])!!}
                            </div>
                            <div class="col-md-3 col-12 ms-auto">
                                <div class="d-flex gap-2 w-100">
                                    <button class="btn btn-primary flex-grow-1" type="submit" style="border-radius:10px;">
                                        <i class="ri-search-line"></i> Buscar
                                    </button>
                                    <a class="btn btn-light border px-3" href="{{ route('modulo.index') }}" title="Limpar Filtros" style="border-radius:10px;">
                                        <i class="ri-eraser-line"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                        {!!Form::close()!!}
                    </div>
                </div>

                <!-- ═══ TABELA ═══ -->
                <div class="tb-wrap">
                    <div class="table-responsive">
                        <table class="table table-centered table-hover align-middle mb-0">
                            <thead>
                                <tr>
                                    <th>Nome</th>
                                    <th>Status</th>
                                    @if(existe coluna extra) <th>Extra</th> @endif
                                    <th class="text-end" style="width: 170px;">Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($data as $item)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <img class="rounded-circle border bg-light me-2 shadow-sm" src="{{ $item->img ?? '/imgs/no-image.png' }}"
                                                 alt="" style="width: 36px; height: 36px; object-fit: cover;">
                                            <div>
                                                <div class="fw-semibold" style="color:#1f2937;">{{ $item->nome }}</div>
                                                {{-- Dado secundário SEMPRE abaixo do nome --}}
                                                <div class="fs-12" style="color:#94a3b8;">{{ $item->cpf_cnpj ?? '' }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>@include badge @endtd</td>
                                    <td class="text-end">
                                        @if(__isAdmin())
                                        <form action="{{ route('modulo.destroy', $item->id) }}" method="post" id="form-{{$item->id}}" class="m-0">
                                            @method('delete')
                                            @csrf
                                            <div class="act-group">
                                                @can('modulo_edit')
                                                <a class="act-btn act-edit" href="{{ route('modulo.edit', $item->id) }}" title="Editar"><i class="ri-pencil-line"></i></a>
                                                @endcan
                                                @can('modulo_show')
                                                <a class="act-btn act-view" href="{{ route('modulo.show', $item->id) }}" title="Visualizar"><i class="ri-eye-line"></i></a>
                                                @endcan
                                                @can('modulo_delete')
                                                <button type="button" class="act-btn act-del btn-delete" title="Excluir"><i class="ri-delete-bin-line"></i></button>
                                                @endcan
                                            </div>
                                        </form>
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="99">
                                        <div class="empty-state">
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

                <!-- ═══ FOOTER ═══ -->
                <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mt-3">
                    <div class="fs-12" style="color:#94a3b8;">
                        Exibindo <strong>{{ $data->count() }}</strong> de <strong>{{ $data->total() }}</strong> registros
                    </div>
                    <div>{!! $data->appends(request()->all())->links() !!}</div>
                </div>

            </div>
        </div>
    </div>
</div>
@endsection
```

---

## 📊 CARDS DE ESTATÍSTICA — PADRÃO NO CONTROLLER

No método `index` do controller, monte um `$stats` com as agregações (usando `clone` para não contaminar a query de listagem):

```php
public function index(Request $request)
{
    $base = Modelo::where('empresa_id', request()->empresa_id)
        ->when(!empty($request->nome), fn($q) => $q->where('nome', 'like', "%$request->nome%"));

    $data = (clone $base)->paginate(env("PAGINACAO"));

    $stats = [
        'total'      => (clone $base)->count(),
        'ativos'     => (clone $base)->where('ativo', 1)->count(),
        'aprovados'  => (clone $base)->where('estado', 'aprovado')->count(),
        'valor'      => (clone $base)->sum('total'),
    ];

    return view('modulo.index', compact('data', 'stats'));
}
```

**Dica (usuario_empresas):** quando a tabela usa `belongsToMany`/join com a empresa, use `$base = Modelo::where('usuario_empresas.empresa_id', request()->empresa_id)->join('usuario_empresas', 'modelos.id', '=', 'usuario_empresas.modelo_id')->select('modelos.*');` e `->whereHas('roles')` / `->whereDoesntHave('locais')` conforme a métrica.

---

## 📝 TEMPLATE COMPLETO — TELA CREATE / EDIT

> As views `create`/`edit` quase só abrem o `Form` + `@include('modulo._forms')`. Toda a responsabilidade visual dos campos fica no `_forms`.

**create.blade.php:**
```blade
@extends('layouts.app', ['title' => 'Novo Registro'])

@section('content')
<div class="mt-3">
    <div class="row">
        <div class="card border-0 shadow-sm">

            <div class="card-header modulo-header-gradient py-3 px-4">
                <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                    <div>
                        <h4 class="mb-1 modulo-title d-flex align-items-center gap-2"><i class="ri-add-circle-line"></i> Novo Registro</h4>
                        <p class="text-muted mb-0 modulo-subtitle fs-13">Preencha os campos abaixo para cadastrar.</p>
                    </div>
                    <div class="d-flex gap-2 flex-wrap">
                        <a href="{{ route('modulo.index') }}" class="dash-btn dash-btn-light"><i class="ri-arrow-left-line"></i> Voltar</a>
                    </div>
                </div>
            </div>

            <div class="card-body p-4">
                {!!Form::open()->post()->route('modulo.store')->multipart()!!}
                @include('modulo._forms')
                {!!Form::close()!!}
            </div>

        </div>
    </div>
</div>
@endsection
```

**edit.blade.php:** igual, mudando `->put()->route('modulo.update', [$item->id])` e `title => 'Editar Registro'`, botão Voltar igual.

> ⚠️ **Regra**: Os botões Salvar/Cancelar vivem no **`_forms`** (`.uf-actions`). **NÃO** duplicar botões na view pai. Apenas `@include('modulo._forms')`.

---

## 🧩 PADRÃO DO `_forms.blade.php` (campos alinhados)

> Sections com título + ícone, grade equilibrada (`col-md-6` para campos grandes, `col-md-4` para médios, `col-md-3` para pequenos), labels em caixa alta.

```blade
<style>
    /* 📌 Cole o bloco "CSS DO FORMULÁRIO" (final do arquivo) */
</style>

<div class="row g-4">

    <!-- SEÇÃO 1 -->
    <div class="col-12 uf-section">
        <div class="uf-section-title">
            <span class="uf-ico"><i class="ri-ICONE-line"></i></span>
            1. Nome da Seção
            <small>descrição curta</small>
        </div>
        <div class="row g-3">
            <div class="col-md-6 col-12 uf-field">
                {!!Form::text('nome', 'Nome')->placeholder('Ex: ...')->required()->attrs(['class' => 'form-control'])!!}
            </div>
            <div class="col-md-6 col-12 uf-field">
                {!!Form::text('email', 'E-mail')->placeholder('...')->required()->attrs(['class' => 'form-control'])!!}
            </div>
        </div>
        <div class="row g-3 mt-1">
            <div class="col-md-4 col-12 uf-field">
                {!!Form::select('status', 'Status', [1 => 'Ativo', 0 => 'Inativo'])->attrs(['class' => 'form-select'])!!}
            </div>
            <div class="col-md-4 col-12 uf-field">
                <label class="form-label required uf-required"><i class="ri-lock-line"></i> Senha</label>
                <div class="input-group" id="show_hide_password">
                    <input required type="password" class="form-control" name="password" autocomplete="off" placeholder="Digite a senha">
                    <button type="button" class="btn btn-outline-secondary input-group-text"><i class="ri-eye-line"></i></button>
                </div>
            </div>
            <div class="col-md-4 col-12 d-flex align-items-end">
                <div class="w-100 p-3 rounded-2" style="background:#f8fafc;border:1px solid #eef0f6;">
                    <div class="d-flex align-items-center gap-2">
                        <i class="ri-information-line" style="color:#4f46e5;font-size:18px;"></i>
                        <div>
                            <div class="fw-semibold" style="font-size:12.5px;color:#1f2937;">Título do aviso</div>
                            <div style="font-size:11.5px;color:#94a3b8;">Texto curto explicativo.</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- mais seções --}}

    <!-- RODAPÉ COM BOTÕES -->
    <div class="col-12">
        <div class="uf-actions">
            <a href="{{ route('modulo.index') }}" class="dash-btn dash-btn-light px-4"><i class="ri-close-line"></i> Cancelar</a>
            <button type="submit" class="dash-btn dash-btn-primary px-4" id="btn-store">
                <i class="ri-save-line"></i> {{ $formType === 'edit' ? 'Salvar Alterações' : 'Salvar' }}
            </button>
        </div>
    </div>

</div>

@section('js')
{{-- scripts específicos do formulário --}}
@endsection
```

---

## 📦 CSS COMPLETO DA LISTAGEM (copiar para `@section('css')` do Index)

```css
/* ─── Cards de Estatísticas ─── */
.stat-card { border: 0; border-radius: 16px; padding: 18px 20px; height: 100%; color: #fff; position: relative; overflow: hidden; transition: transform .18s ease, box-shadow .18s ease; }
.stat-card:hover { transform: translateY(-3px); }
.stat-card::after { content: ''; position: absolute; top: -44px; right: -44px; width: 130px; height: 130px; border-radius: 50%; background: rgba(255,255,255,.12); }
.stat-indigo { background: linear-gradient(135deg,#6366f1,#4f46e5); box-shadow: 0 6px 18px rgba(79,70,229,.32); }
.stat-green  { background: linear-gradient(135deg,#24c98a,#109f61); box-shadow: 0 6px 18px rgba(16,185,129,.32); }
.stat-blue   { background: linear-gradient(135deg,#4d94ff,#1d4ed8); box-shadow: 0 6px 18px rgba(37,99,235,.32); }
.stat-amber  { background: linear-gradient(135deg,#fbbf24,#d97706); box-shadow: 0 6px 18px rgba(245,158,11,.32); }
.stat-card .st-label { font-size: 11px; font-weight: 700; letter-spacing: .05em; text-transform: uppercase; color: rgba(255,255,255,.85); }
.stat-card .st-value { font-size: 26px; font-weight: 800; color: #fff; margin-top: 4px; line-height: 1.1; }
.stat-card .st-sub { font-size: 11.5px; color: rgba(255,255,255,.75); margin-top: 4px; }
.stat-card .st-icon { width: 46px; height: 46px; border-radius: 13px; background: rgba(255,255,255,.22); color: #fff; display: flex; align-items: center; justify-content: center; font-size: 20px; }

/* ─── Filtro ─── */
.filter-wrap { background: #fff; border: 1px solid #e9ecf3; border-radius: 14px; box-shadow: 0 1px 2px rgba(16,24,40,.04); padding: 18px 20px; margin-bottom: 18px; }
.filter-title { font-size: 13px; font-weight: 700; color: #3f3e6a; text-transform: uppercase; letter-spacing: .5px; }
.filter-title i { color: #4f46e5; margin-right: 6px; }
.filter-wrap label { font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: .4px; color: #8c8ca6; }
.filter-wrap .form-control { height: 40px; border-radius: 10px; border: 1px solid #dcdce9; font-size: 13.5px; background: #fcfdfe; }
.filter-wrap .form-control:focus { border-color: #4f46e5; box-shadow: 0 0 0 3px rgba(79,70,229,.12); background: #fff; }

/* ─── Tabela ─── */
.tb-wrap { border-radius: 14px; border: 1px solid #eef0f5; overflow: hidden; background: #fff; }
.tb-wrap table { margin-bottom: 0; }
.tb-wrap thead th { background: #f8f9fc; color: #5a5a7a; font-weight: 700; font-size: 11px; text-transform: uppercase; letter-spacing: .4px; padding: 13px 16px; border-bottom: 1px solid #e8eaf6; white-space: nowrap; }
.tb-wrap tbody td { padding: 13px 16px; vertical-align: middle; border-bottom: 1px solid #f0f2f8; font-size: 13.5px; color: #374151; }
.tb-wrap tbody tr:hover { background: #f5f6fe; }
.tb-wrap tbody tr:last-child td { border-bottom: none; }

/* ─── Grade de botões de ação ─── */
.act-group { display: inline-flex; gap: 6px; align-items: center; }
.act-btn { width: 34px; height: 34px; border-radius: 10px; border: 0; display: inline-flex; align-items: center; justify-content: center; font-size: 15px; text-decoration: none; cursor: pointer; transition: transform .15s ease, box-shadow .15s ease; }
.act-btn:hover { transform: translateY(-2px); text-decoration: none; }
.act-edit { background: #eef0ff; color: #4f46e5; }
.act-edit:hover { box-shadow: 0 4px 12px rgba(79,70,229,.3); }
.act-view { background: #e0f2fe; color: #0284c7; }
.act-view:hover { box-shadow: 0 4px 12px rgba(2,132,199,.3); }
.act-profile { background: #dcfce7; color: #16a34a; }
.act-profile:hover { box-shadow: 0 4px 12px rgba(22,163,74,.3); }
.act-del { background: #fee2e2; color: #dc2626; }
.act-del:hover { box-shadow: 0 4px 12px rgba(220,38,38,.3); }

/* ─── Badges (pills) ─── */
.pill { display: inline-flex; align-items: center; gap: 5px; border-radius: 8px; padding: 4px 10px; font-size: 11.5px; font-weight: 700; }
.pill-ok { background: #dcfce7; color: #15803d; }
.pill-no { background: #f1f5f9; color: #64748b; }
.pill-role { background: #eef0ff; color: #4f46e5; }
.pill-amber { background: #fef3c7; color: #b45309; }
.pill-red { background: #fee2e2; color: #b91c1c; }

/* ─── Estado vazio ─── */
.empty-state { padding: 52px 20px; text-align: center; }
.empty-state i { font-size: 52px; color: #c5cae9; display: block; margin-bottom: 12px; }
.empty-state p { color: #9e9eb8; font-size: 14px; margin: 0; }
```

---

## 📦 CSS DO FORMULÁRIO (copiar para o topo do `_forms.blade.php` ou no `@section('css')`)

```css
/* ─── Seções do formulário ─── */
.uf-section-title { display: flex; align-items: center; gap: 10px; font-size: 14px; font-weight: 700; color: #1f2937; border-bottom: 1px solid #eef0f6; padding-bottom: 10px; margin-bottom: 16px; }
.uf-section-title .uf-ico { width: 30px; height: 30px; border-radius: 9px; background: #eef0ff; color: #4f46e5; display: inline-flex; align-items: center; justify-content: center; font-size: 15px; }
.uf-section-title small { font-weight: 500; color: #94a3b8; font-size: 12px; margin-left: auto; }

/* ─── Campos ─── */
.uf-field label, .uf-field .form-label { display: block; font-size: 11px; font-weight: 700; letter-spacing: .04em; text-transform: uppercase; color: #64748b; margin-bottom: 6px; }
.uf-field .form-label i, .uf-field label i { color: #a8a8c0; font-size: 12px; }
.uf-field .form-control, .uf-field .form-select, .uf-field .input-group .form-control { height: 40px; border-radius: 10px; border: 1px solid #dcdce9; font-size: 13.5px; color: #1f2937; background: #fcfdfe; transition: all .15s ease; }
.uf-field .form-control:focus, .uf-field .form-select:focus { border-color: #4f46e5; box-shadow: 0 0 0 3px rgba(79,70,229,.12); background: #fff; }
.uf-field .input-group-text { border-radius: 0 10px 10px 0; background: #fff; border-color: #dcdce9; color: #64748b; cursor: pointer; }
.uf-field .form-text { font-size: 11.5px; color: #94a3b8; }
.uf-required::after { content: ' *'; color: #dc2626; font-weight: 700; }

/* ─── Rodapé de ações ─── */
.uf-actions { display: flex; align-items: center; justify-content: flex-end; gap: 10px; border-top: 1px solid #eef0f6; padding-top: 16px; margin-top: 20px; }
```

---

## 🎛️ COMPONENTES REUTILIZÁVEIS (templates prontos)

### Botões `.dash-btn` (globais — SEM CSS na view)
```blade
<a href="{{ route('modulo.index') }}" class="dash-btn dash-btn-light"><i class="ri-refresh-line"></i> Atualizar</a>
<a href="{{ route('modulo.create') }}" class="dash-btn dash-btn-primary"><i class="ri-add-line"></i> Novo</a>
```
> `.dash-btn` / `.dash-btn-light` / `.dash-btn-primary` já vêm do `dashboard-skin.css`. Use direto. `dash-btn-light` = branco/borda; `dash-btn-primary` = índigo.

### Badges de Status
```blade
@if($item->ativo)
<span class="pill pill-ok"><i class="ri-checkbox-circle-line"></i> Ativo</span>
@else
<span class="pill pill-no"><i class="ri-close-circle-line"></i> Inativo</span>
@endif
```

### Grade de Ações (substitui o dropdown de 3 pontinhos)
```blade
<div class="act-group">
    <a class="act-btn act-edit" href="{{ route('modulo.edit', $item->id) }}" title="Editar"><i class="ri-pencil-line"></i></a>
    <a class="act-btn act-view" href="{{ route('modulo.show', $item->id) }}" title="Visualizar"><i class="ri-eye-line"></i></a>
    <a class="act-btn act-profile" href="{{ route('modulo.profile', $item->id) }}" title="Perfil"><i class="ri-user-3-line"></i></a>
    <button type="button" class="act-btn act-del btn-delete" title="Excluir"><i class="ri-delete-bin-line"></i></button>
</div>
```

### 📍 Coluna de Ações: posição e largura

Por padrão a coluna de ações é a **última**. Mas quando o pedido for que ela fique **antes do nome** (ex.: compras, devolução, PDV), mova **no cabeçalho** e **em cada linha**. Depois **estreite** a coluna (ex.: `width: 70px`) e alinhe à esquerda (`th`/`td` **sem** `text-end`), para não sobrar vão no início.

```blade
<th style="width: 70px;">Ações</th>
...
<td style="white-space: nowrap;">
    <form action="{{ route('modulo.destroy', $item->id) }}" method="post" id="form-{{$item->id}}" class="m-0">
        @method('delete') @csrf
        <div class="act-group"> ...botões... </div>
    </form>
</td>
```

**Quando usar DROPDOWN em vez da grade de botões:** se o módulo tem **muitas ações (6+)**, use o menu dropdown de submenus (`btn-action-trigger` + `action-dropdown-card` + `action-menu-item` + `action-item-icon`), que já é o padrão premium existente — ex.: produtos, clientes, PDV, compras, devolução. Para módulos com **poucas ações (2–3)**, use a **grade `act-group`/`act-btn`**.

> ⚠️ Se houver um botão **fora** do dropdown (ex.: "Transmitir" em destaque) e o mesmo item já existir **dentro** do menu, **remova o de fora** para não duplicar e não desalinhar.

### Upload de imagem (foto/perfil)
```blade
<div class="col-md-3 col-12 uf-field">
    <div class="card border shadow-sm" style="border-radius:14px;">
        <div class="card-body p-2 text-center">
            <div class="preview mb-2 bg-light rounded d-flex align-items-center justify-content-center border"
                 style="height: 140px; position: relative; overflow: hidden;">
                <img id="file-ip-1-preview" src="{{ isset($item) ? $item->img : '/imgs/no-image.png' }}" style="width: 100%; height: 100%; object-fit: cover;">
            </div>
            <label for="file-ip-1" class="btn btn-primary btn-sm w-100 mb-0" style="border-radius:9px;"><i class="ri-upload-cloud-line me-1"></i> Selecionar Foto</label>
            <input type="file" class="d-none" id="file-ip-1" name="image" accept="image/*" onchange="showPreview(event);">
        </div>
    </div>
</div>
```
> Requer `/js/uploadImagem.js` no `@section('js')`.

### Seletor de período (quando a tela usa filtro por período)
```blade
<div class="seg-control">
    <button type="button" class="seg-btn" data-periodo="1">Hoje</button>
    <button type="button" class="seg-btn" data-periodo="7">Semana</button>
    <button type="button" class="seg-btn active" data-periodo="30">Mês</button>
    <button type="button" class="seg-btn" data-periodo="365">Ano</button>
</div>
```
> Estilos em `dashboard-skin.css`. Complementar com `#inp-periodo` hidden e o JS que chama o endpoint.

---

## 🛑 ERROS COMUNS QUE CAUSAM 500 (EVITAR!)

| Erro | Sintoma | Causa | Correção |
|------|---------|-------|----------|
| `Class "App\Http\Controllers\Auth" not found` | 500 no index | Usou `Auth::user()` no controller sem importar | `use Illuminate\Support\Facades\Auth;` |
| `syntax error, unexpected token "??"` | 500 em várias telas | Comentário Blade malformado `{{-- ??? }}` (sem `--` de fechamento) | Use comentário válido `{{-- texto --}}` ou remova |
| `Call to a member function count() on array` | 500 | `$data->count()` onde `$data` é **array** (a view recebe array, não coleção) | Use `count($data)` |
| Layout diferente após modernizar | tela "estranha"/CSS quebrado | Substituiu **todo** o `@section('css')` e perdeu classes específicas (`.status-badge`, `.unidade-nome`, `.unicode-badge`, `.transacao-badge`, `.div-overflow`, `.codigo-unico-badge`) | **ADICIONE** o CSS premium **antes de `</style>`** — NUNCA substitua o bloco inteiro |
| Título do header invisível | texto "transparente" | A view redefiniu `.modulo-header-gradient` escuro (white text) mesmo com o skin claro | Não redefina o gradient escuro. Se precisar vencer, use prefixo `body .modulo-header-gradient` (mais específico) |
| KPI `where` no paginador | 500 / erro | `$data->where('status',1)->count()` (paginator não tem `where`) | Calcule no **controller** (`$stats`) e use `$stats['...']` |

**Regra de ouro:**
- NUNCA altere IDs `inp-*`, classes JS (`moeda`, `cpf_cnpj`, `cep`, `check-delete`, `btn-delete`, `btn-delete-all`, `select2`, `show_hide_password`), rotas, nomes de campos, nem `@section('js')`.
- NUNCA reescreva a lógica de negócio dos controllers — apenas **adicione contagens** (`$stats`).
- Para inserir CSS de premium: **sempre** `s.replace("</style>", NEW_CSS + "</style>")` (incrementar), preservando o CSS antigo do módulo.

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
- `inp-periodo` → Período dos dashboard/relatórios

### Classes JS — NUNCA REMOVER

| Classe | Função |
|--------|--------|
| `moeda` | Máscara monetária R$ |
| `cpf_cnpj` | Máscara de documento |
| `cep` | Máscara de CEP |
| `check-delete` | Checkbox de seleção em lote |
| `btn-delete` | Gatilho de confirmação de exclusão (SweetAlert) |
| `btn-delete-all` | Gatilho de exclusão em lote |
| `show_hide_password` | Mostrar/ocultar senha |
| `select2` / `.select2-multiple` | Selects com busca |

### Form de exclusão — SEMPRE assim
```blade
<form action="{{ route('modulo.destroy', $item->id) }}" method="post" id="form-{{$item->id}}" class="m-0">
    @method('delete')
    @csrf
    <button type="button" class="act-btn act-del btn-delete" title="Excluir"><i class="ri-delete-bin-line"></i></button>
</form>
```

### Select2 com Bootstrap 5 — NÃO usar `theme: "bootstrap4"`
```js
// ✅ CORRETO (sem theme)
$("#inp-campo_id").select2({ width: "100%", language: "pt-BR", ajax: {} });
// ❌ ERRADO
$("#inp-campo_id").select2({ theme: "bootstrap4" });
```

### Select2 no filtro (alinhar à altura dos outros campos)
Inputs Select2 têm altura própria e ficam desalinhados dos campos comuns. Adicione no CSS da view:

```css
.filter-wrap .select2-container .select2-selection--single { height: 40px !important; line-height: 40px !important; border: 1px solid #dcdce9 !important; border-radius: 10px !important; background: #fcfdfe !important; font-size: 13.5px; }
.filter-wrap .select2-container .select2-selection--single .select2-selection__rendered { line-height: 38px !important; color: #1f2937; padding-left: 12px; }
.filter-wrap .select2-container .select2-selection--single .select2-selection__arrow { height: 38px !important; }
.filter-wrap .select2-container .select2-selection--single .select2-selection__arrow b { display: none; }
```

### Blade — Evitar diretivas inline em loops (causa erro 500)
```blade
{{-- ❌ ERRADO --}}
@foreach($items as $item) @if($item->ativo) <span>Ativo</span> @endif @endforeach

{{-- ✅ CORRETO --}}
@foreach($items as $item)
    @if($item->ativo)
        <span>Ativo</span>
    @endif
@endforeach
```

### Paginação
- Limite de **`env("PAGINACAO")`** (ex.: 10/20) por página.
- Sempre `{!! $data->appends(request()->all())->links() !!}` para manter o filtro na paginação.

### Ícones — Somente Remix Icon (`ri-*`)

### Scripts no final
```blade
@section('js')
<script type="text/javascript" src="/js/uploadImagem.js"></script>
<script type="text/javascript" src="/js/delete_selecionados.js"></script>
@endsection
```

### JS de mostrar/ocultar senha (necessário no `_forms` com campo senha)
```js
$("#show_hide_password button").on('click', function (e) {
    e.preventDefault();
    let input = $('#show_hide_password input'), icon = $('#show_hide_password i');
    if (input.attr("type") === "text") { input.attr('type', 'password'); icon.addClass("ri-eye-line").removeClass("ri-eye-off-line"); }
    else { input.attr('type', 'text'); icon.removeClass("ri-eye-line").addClass("ri-eye-off-line"); }
});
```

---

## 🔍 CHECKLIST RÁPIDO ANTES DE ENTREGAR UMA TELA

- [ ] Header usa `card-header modulo-header-gradient` (claro via skin) — **não** definir gradiente escuro na view
- [ ] Botão de ação principal "Novo Registro" = `.dash-btn.dash-btn-primary` (índigo), à direita
- [ ] Botão "Atualizar"/"Voltar" = `.dash-btn.dash-btn-light`
- [ ] **Index**: Cards de estatística `.stat-card` (coloridos) **acima** do filtro
- [ ] Filtro em `.filter-wrap` com label + input + Buscar (btn-primary) + Limpar
- [ ] Tabela em `.tb-wrap` com thead estilizado; 1ª coluna = avatar + nome + dado secundário
- [ ] **Grade de botões** `.act-group` / `.act-btn` (poucas ações) OU dropdown de submenus (muitas ações) — escolha conforme o nº de ações
- [ ] Se o pedido for a coluna de ações **antes do nome**: mova no `<thead>` e no `<tbody>`, estreite (≈70px) e alinhe à esquerda (sem `text-end`)
- [ ] Badges de status em `.pill` (ok/no/role/amber/red)
- [ ] Form de exclusão com `@method('delete')` + `@csrf` + `btn-delete`
- [ ] **Create/Edit**: `@include('modulo._forms')`; botões Salvar/Cancelar **no `_forms`** (`.uf-actions`), nunca duplicados na view pai
- [ ] Campos do `_forms` alinhados: `.uf-section-title` com ícone + grade `col-md-6`/`col-md-4`/`col-md-3` + `.uf-field`
- [ ] `$formType` usado para alternar "Salvar" / "Salvar Alterações"
- [ ] Controller: `$stats` calculado no controller e passado via `compact()` — nunca `->where()` no paginador
- [ ] If usou `Auth::` no controller → `use Illuminate\Support\Facades\Auth;`
- [ ] Não alterar IDs `inp-*` nem classes JS (`moeda`, `cpf_cnpj`, `btn-delete`, `btn-delete-all`, `select2`, `check-delete`, `show_hide_password`)
- [ ] Select2 do filtro alinhado (altura 40px) — ver seção "Select2 no filtro"
- [ ] Paginação: `env("PAGINACAO")` e `appends(request()->all())`
- [ ] Rodapé do Index: contagem "Exibindo X de Y" + paginação à direita
- [ ] ⚠️ Validar ANTES de entregar: `php artisan view:cache` e `php -l <Controller>`; conferir que não há `{{-- ??? }}` e que a tela NÃO retorna 500
