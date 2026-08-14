@extends('layouts.app', ['title' => 'Categorias de Ecommerce'])

@section('css')
<style>
    .modulo-header-gradient { background: linear-gradient(135deg, #0f0c29 0%, #302b63 50%, #24243e 100%); border-radius: 12px 12px 0 0 !important; border-bottom: none !important; }
    .modulo-header-gradient .modulo-title { color: #fff; font-weight: 700; letter-spacing: -0.3px; }
    .modulo-header-gradient .modulo-title i { background: rgba(255,255,255,0.12); padding: 8px; border-radius: 10px; color: #a8b5ff; }
    .modulo-header-gradient .modulo-subtitle { color: rgba(255,255,255,0.6) !important; font-weight: 400; }
    
    .modulo-form-card { border: 1px solid #eef0f5; border-radius: 12px; overflow: hidden; }
    
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
    
    .modulo-table-wrap table { margin-bottom: 0; }
    .modulo-table-wrap thead th { background: #f8f9fc; color: #5a5a7a; font-weight: 700; font-size: 11px; text-transform: uppercase; letter-spacing: 0.4px; padding: 12px 16px; border-bottom: 2px solid #e8eaf6; }
    .modulo-table-wrap tbody td { padding: 12px 16px; vertical-align: middle; border-bottom: 1px solid #f0f2f8; font-size: 13px; color: #374151; }
    .modulo-table-wrap tbody tr:hover td { background: #fafbff; }
    .modulo-table-wrap tbody tr:last-child td { border-bottom: none; }
    
    .modulo-empty { padding: 60px 20px; text-align: center; }
    .modulo-empty i { font-size: 48px; color: #c5cae9; margin-bottom: 12px; display: block; }
    .modulo-empty p { color: #9e9eb8; font-size: 14px; margin: 0; }
    
    /* Toggle Switch Styles */
    .switch-premium { position: relative; display: inline-block; width: 44px; height: 24px; vertical-align: middle; margin-left: 10px; }
    .switch-premium input { opacity: 0; width: 0; height: 0; }
    .switch-premium .slider { position: absolute; cursor: pointer; top: 0; left: 0; right: 0; bottom: 0; background-color: #cbd5e1; transition: .4s; border-radius: 34px; }
    .switch-premium .slider:before { position: absolute; content: ""; height: 18px; width: 18px; left: 3px; bottom: 3px; background-color: white; transition: .4s; border-radius: 50%; }
    .switch-premium input:checked + .slider { background-color: #10b981; }
    .switch-premium input:checked + .slider:before { transform: translateX(20px); }
</style>
@endsection

@section('content')
<div class="mt-3 text-dark">
    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm modulo-form-card">

                {{-- CABEÇALHO PREMIUM --}}
                <div class="card-header modulo-header-gradient py-3 px-4">
                    <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                        <div>
                            <h4 class="mb-1 modulo-title d-flex align-items-center gap-2">
                                <i class="ri-list-check-2"></i>
                                Categorias no E-commerce
                            </h4>
                            <p class="text-muted mb-0 modulo-subtitle fs-13">
                                Gerencie quais categorias de produtos devem ser exibidas na loja virtual.
                            </p>
                        </div>
                    </div>
                </div>

                <div class="card-body p-4">
                    {{-- ═══ Filtros de Busca Premium ═══ --}}
                    <div class="modulo-glass-filter-premium">
                        <div class="filtro-premium-header">
                            <h5 class="filtro-premium-title">
                                <i class="ri-search-line"></i> Filtrar Categorias
                            </h5>
                        </div>

                        {!!Form::open()->fill(request()->all())->get()!!}
                        <div class="row g-3">
                            <div class="col-md-5 col-12">
                                <label class="form-label"><i class="ri-folder-line"></i> Nome da Categoria</label>
                                {!!Form::text('nome', '')->attrs(['class' => 'form-control', 'placeholder' => 'Digite o nome da categoria...'])!!}
                            </div>
                            <div class="col-md-3 col-12 ms-auto d-flex align-items-end">
                                <div class="d-flex gap-2 w-100">
                                    <button class="btn btn-pesquisar flex-grow-1" type="submit">
                                        <i class="ri-search-line"></i> Buscar
                                    </button>
                                    <a id="clear-filter" class="btn btn-limpar px-3" href="{{ route('produtos-ecommerce.categorias') }}" title="Limpar Filtros">
                                        <i class="ri-eraser-line"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                        {!!Form::close()!!}
                    </div>

                {{-- TABELA PREMIUM --}}
                <div class="modulo-table-wrap">
                    <div class="table-responsive">
                        <table class="table align-middle">
                            <thead>
                                <tr>
                                    <th>Nome da Categoria</th>
                                    <th class="text-end">Ações / Visibilidade na Loja</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($data as $item)
                                <tr>
                                    <td class="fw-semibold text-dark">{{ $item->nome }}</td>
                                    
                                    <td class="text-end">
                                        <div class="d-inline-flex align-items-center gap-2">
                                            <a class="btn btn-warning btn-sm text-white rounded-2 px-2" href="{{ route('categoria-produtos.edit', [$item->id]) }}" title="Editar Categoria">
                                                <i class="ri-pencil-fill"></i>
                                            </a>
                                            
                                            <div class="d-inline-flex align-items-center bg-light px-2 py-1 rounded-2 border">
                                                <span class="fs-12 text-muted fw-bold me-1">Ativo:</span>
                                                <label class="switch-premium mb-0">
                                                    <input @if($item->ecommerce) checked @endif type="checkbox" value="{{ $item->id }}" class="switch-check">
                                                    <span class="slider"></span>
                                                </label>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="2">
                                        <div class="modulo-empty">
                                            <i class="ri-list-check-2"></i>
                                            <p>Nenhuma categoria encontrada.</p>
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                
                @if($data->hasPages())
                <div class="px-4 py-3 border-top bg-white">
                    {!! $data->appends(request()->all())->links() !!}
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@section('js')
<script type="text/javascript">
    $('.switch-check').on("change", function () {
        let id = $(this).val()
        $.get(path_url + "api/produtos-ecommerce/switch-categoria", {id: id})
        .done((success) => {
            // Opcional: mostrar um alerta de sucesso do Toastr aqui
        })
        .fail((err) => {
            console.log(err)
        })
    })
</script>
@endsection