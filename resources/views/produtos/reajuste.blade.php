@extends('layouts.app', ['title' => 'Reajuste de Produtos'])

@section('css')
<style type="text/css">
    /* ─── Header Gradiente ─── */
    .modulo-header-gradient {
        background: linear-gradient(135deg, #0f0c29 0%, #302b63 50%, #24243e 100%);
        border-radius: 12px 12px 0 0 !important;
        border-bottom: none !important;
    }
    .modulo-header-gradient .modulo-title {
        color: #fff;
        font-weight: 700;
        letter-spacing: -0.3px;
    }
    .modulo-header-gradient .modulo-title i {
        background: rgba(255,255,255,0.12);
        padding: 8px;
        border-radius: 10px;
        color: #a8b5ff;
    }
    .modulo-header-gradient .modulo-subtitle {
        color: rgba(255,255,255,0.6) !important;
        font-weight: 400;
    }
    .modulo-header-gradient .btn {
        border-radius: 8px;
        font-weight: 600;
        transition: all 0.2s ease;
    }
    .modulo-header-gradient .btn:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 14px rgba(0,0,0,0.25);
    }

    /* ─── Filtros Glass ─── */
    .modulo-glass-filter {
        background: rgba(255,255,255,0.7);
        backdrop-filter: blur(12px);
        -webkit-backdrop-filter: blur(12px);
        border: 1px solid rgba(255,255,255,0.8) !important;
        border-radius: 12px;
        box-shadow: 0 2px 20px rgba(0,0,0,0.04);
    }
    .modulo-glass-filter label {
        font-weight: 600;
        font-size: 11px;
        text-transform: uppercase;
        letter-spacing: 0.4px;
        color: #5a5a7a;
        margin-bottom: 2px;
    }
    .modulo-glass-filter .form-control, .modulo-glass-filter .form-select, .modulo-glass-filter .select2-container .select2-selection--single {
        height: 38px !important;
    }
    .modulo-glass-filter .select2-container .select2-selection--single {
        display: flex;
        align-items: center;
    }
    .modulo-glass-filter .btn {
        border-radius: 8px;
        font-weight: 600;
        font-size: 13px;
        height: 38px;
        padding-top: 0;
        padding-bottom: 0;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        transition: all 0.2s;
    }
    .modulo-glass-filter .btn:hover {
        transform: translateY(-1px);
    }

    /* ─── Tabela Premium ─── */
    .modulo-table-wrap {
        border-radius: 12px;
        border: 1px solid #eef0f5;
        overflow: hidden;
    }
    .modulo-table-wrap table {
        margin-bottom: 0;
    }
    .modulo-table-wrap thead th {
        background: #f8f9fc;
        color: #5a5a7a;
        font-weight: 700;
        font-size: 11px;
        text-transform: uppercase;
        letter-spacing: 0.4px;
        padding: 12px 14px;
        border-bottom: 2px solid #e8eaf6;
    }
    .modulo-table-wrap tbody td {
        padding: 12px 14px;
        vertical-align: middle;
        border-bottom: 1px solid #f0f2f8;
        transition: background 0.15s ease;
        font-size: 13px;
    }
    .modulo-table-wrap tbody tr {
        transition: all 0.15s ease;
    }
    .modulo-table-wrap tbody tr:hover {
        background: #f5f6fe;
    }
    .modulo-table-wrap tbody tr:last-child td {
        border-bottom: none;
    }

    /* Link ações em lote */
    .define-batch-link {
        font-size: 11px;
        color: #5c6bc0;
        font-weight: 600;
        display: block;
        margin-top: 4px;
        transition: color 0.15s;
    }
    .define-batch-link:hover {
        color: #3f51b5;
        text-decoration: underline !important;
    }
</style>
@endsection

@section('content')
<div class="mt-3 text-dark">
    <div class="row">
        <div class="card border-0 shadow-sm text-dark">

            {{-- ═══ CABEÇALHO PREMIUM ═══ --}}
            <div class="card-header modulo-header-gradient py-3 px-4">
                <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                    <div>
                        <h4 class="mb-1 modulo-title d-flex align-items-center gap-2">
                            <i class="ri-file-edit-line"></i>
                            Reajuste de Produtos
                        </h4>
                        <p class="text-muted mb-0 modulo-subtitle fs-13">
                            Ajuste de preços e dados tributários de múltiplos produtos simultaneamente.
                        </p>
                    </div>
                    <div>
                        <a href="{{ route('produtos.index') }}" class="btn btn-light btn-sm px-3 text-dark">
                            <i class="ri-arrow-left-line align-middle me-1"></i> Voltar
                        </a>
                    </div>
                </div>
            </div>

            <div class="card-body p-4">

                {{-- ═══ KPI CARDS: Estatísticas de Reajuste ═══ --}}
                <div class="row g-3 mb-4">
                    <div class="col-md-3 col-6">
                        <div class="card widget-icon-box text-bg-info mb-0">
                            <div class="card-body">
                                <div class="d-flex justify-content-between">
                                    <div class="flex-grow-1 overflow-hidden">
                                        <h4 class="text-uppercase fs-12 mt-0 text-white-50">Total de Produtos</h4>
                                        <h3 class="my-2 text-white fs-18">{{ $stats['total'] }}</h3>
                                        <p class="mb-0 text-white-50 fs-11">Cadastrados no sistema</p>
                                    </div>
                                    <div class="avatar-sm flex-shrink-0">
                                        <span class="avatar-title bg-white bg-opacity-25 text-white rounded rounded-3 fs-3 widget-icon-box-avatar shadow">
                                            <i class="ri-box-3-line"></i>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 col-6">
                        <div class="card widget-icon-box text-bg-primary mb-0">
                            <div class="card-body">
                                <div class="d-flex justify-content-between">
                                    <div class="flex-grow-1 overflow-hidden">
                                        <h4 class="text-uppercase fs-12 mt-0 text-white-50">Filtrados / Buscados</h4>
                                        <h3 class="my-2 text-white fs-18">{{ $stats['filtrados'] }}</h3>
                                        <p class="mb-0 text-white-50 fs-11">Produtos na busca atual</p>
                                    </div>
                                    <div class="avatar-sm flex-shrink-0">
                                        <span class="avatar-title bg-white bg-opacity-25 text-white rounded rounded-3 fs-3 widget-icon-box-avatar shadow">
                                            <i class="ri-search-eye-line"></i>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 col-6">
                        <div class="card widget-icon-box text-bg-success mb-0">
                            <div class="card-body">
                                <div class="d-flex justify-content-between">
                                    <div class="flex-grow-1 overflow-hidden">
                                        <h4 class="text-uppercase fs-12 mt-0 text-white-50">Categorias</h4>
                                        <h3 class="my-2 text-white fs-18">{{ $stats['categorias'] }}</h3>
                                        <p class="mb-0 text-white-50 fs-11">Categorias disponíveis</p>
                                    </div>
                                    <div class="avatar-sm flex-shrink-0">
                                        <span class="avatar-title bg-white bg-opacity-25 text-white rounded rounded-3 fs-3 widget-icon-box-avatar shadow">
                                            <i class="ri-folders-line"></i>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 col-6">
                        <div class="card widget-icon-box text-bg-warning mb-0">
                            <div class="card-body">
                                <div class="d-flex justify-content-between">
                                    <div class="flex-grow-1 overflow-hidden">
                                        <h4 class="text-uppercase fs-12 mt-0 text-white-50">Marcas Ativas</h4>
                                        <h3 class="my-2 text-white fs-18">{{ $stats['marcas'] }}</h3>
                                        <p class="mb-0 text-white-50 fs-11">Marcas cadastradas</p>
                                    </div>
                                    <div class="avatar-sm flex-shrink-0">
                                        <span class="avatar-title bg-white bg-opacity-25 text-white rounded rounded-3 fs-3 widget-icon-box-avatar shadow">
                                            <i class="ri-bookmark-3-line"></i>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- ═══ FILTROS GLASS ═══ --}}
                <div class="modulo-glass-filter p-3 mb-4">
                    {!!Form::open()->fill(request()->all())->get()!!}
                    <div class="row g-2 align-items-end">
                        <div class="col-md-3 col-12">
                            {!!Form::text('nome', 'Pesquisar por nome')->attrs(['placeholder' => 'Nome do produto...'])!!}
                        </div>
                        
                        <div class="col-md-2 col-6">
                            {!!Form::select('categoria_id', 'Categoria', ['' => 'Selecione'] + $categorias->pluck('nome', 'id')->all())->attrs(['class' => 'form-select'])!!}
                        </div>

                        <div class="col-md-2 col-6">
                            {!!Form::select('marca_id', 'Marca', ['' => 'Selecione'] + $marcas->pluck('nome', 'id')->all())->attrs(['class' => 'form-select'])!!}
                        </div>

                        <div class="col-md-3 col-12">
                            <label class="form-label">CST/CSOSN</label>
                            {!!Form::select('cst_csosn', '', ['' => 'Selecione'] + App\Models\Produto::listaCSTCSOSN())->attrs(['class' => 'select2 form-control'])!!}
                        </div>

                        <div class="col-md-2 col-6">
                            {!!Form::select('pendentes', 'Dados pendentes', ['' => 'Selecione', 1 => 'Sim', 0 => 'Não'])->attrs(['class' => 'form-select'])!!}
                        </div>
                        
                        <div class="col-md-3 col-12 ms-auto mt-2">
                            <div class="d-flex gap-2">
                                <button class="btn btn-primary btn-sm flex-grow-1" type="submit">
                                    <i class="ri-search-line me-1"></i> Pesquisar
                                </button>
                                <a id="clear-filter" class="btn btn-danger btn-sm px-3" href="{{ route('produtos.reajuste') }}">
                                    <i class="ri-eraser-line me-1"></i> Limpar
                                </a>
                            </div>
                        </div>
                    </div>
                    {!!Form::close()!!}
                </div>

                {{-- ═══ CARD REAJUSTE DE VALOR EM LOTE ═══ --}}
                @if(sizeof($data) > 0)
                <div class="card border-0 bg-light p-3 mb-4 rounded-3">
                    <div class="row align-items-end g-2">
                        <div class="col-md-3 col-12">
                            <label class="form-label fw-medium text-muted fs-12 mb-1">REAJUSTAR PERCENTUAL DE VENDA EM LOTE (%)</label>
                            {!!Form::tel('percentual_valor_venda', '')->attrs(['class' => 'form-control', 'placeholder' => 'Ex: 10.00 ou -5.00'])!!}
                        </div>
                        <div class="col-md-9 col-12">
                            <span class="fs-12 text-muted">Digite um percentual positivo ou negativo. Ao perder o foco do campo, o sistema aplicará o reajuste dinâmico nos inputs abaixo.</span>
                        </div>
                    </div>
                </div>
                @endif

                {{-- ═══ TABELA DE ITENS ═══ --}}
                <form method="post" action="{{ route('produtos-reajuste.update') }}">
                    @csrf
                    
                    @if(sizeof($data) > 0)
                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <h6 class="m-0 text-muted">Total de registros encontrados: <strong class="text-primary">{{ sizeof($data) }}</strong></h6>
                        </div>
                    @endif

                    <div class="modulo-table-wrap mb-3">
                        <div class="table-responsive">
                            <table class="table table-centered align-middle mb-0 text-dark">
                                <thead>
                                    <tr>
                                        <th>Produto</th> 
                                        <th>Categoria</th> 
                                        <th>Valor de venda</th> 
                                        <th>Valor de compra</th> 
                                        <th>CST/CSOSN</th> 
                                        <th>CST PIS</th> 
                                        <th>CST COFINS</th> 
                                        <th>CST IPI</th> 
                                        <th>% ICMS</th> 
                                        <th>% PIS</th> 
                                        <th>% COFINS</th> 
                                        <th>% IPI</th> 
                                        <th>% RED. BC</th> 
                                        <th>CFOP Saída Est.</th> 
                                        <th>CFOP Saída Ext.</th>
                                        <th>CFOP Ent. Est.</th> 
                                        <th>CFOP Ent. Ext.</th>  
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($data as $item)
                                    <tr>
                                        <td>
                                            <span class="fw-semibold text-dark d-block" style="width: 250px;">{{ $item->nome }}</span>
                                        </td>
                                        <td>
                                            <span class="text-muted" style="width: 150px; display: inline-block;">{{ $item->categoria ? $item->categoria->nome : '--' }}</span>
                                        </td>
                                        <td>
                                            <input type="hidden" name="produto_id[]" value="{{ $item->id }}">
                                            <input type="hidden" class="valor_venda" value="{{ $item->valor_unitario }}">
                                            <input required style="width: 120px" type="tel" class="form-control form-control-sm moeda valor_venda" name="valor_unitario[]" value="{{ __moeda($item->valor_unitario) }}">
                                            @if($loop->first)
                                            <a onclick="setValorVenda()" class="define-batch-link" href="#!">Definir p/ todos</a>
                                            @endif
                                        </td>
                                        <td>
                                            <input required style="width: 120px" type="tel" class="form-control form-control-sm moeda valor_compra" name="valor_compra[]" value="{{ __moeda($item->valor_compra) }}">
                                            @if($loop->first)
                                            <a onclick="setValorCompra()" class="define-batch-link" href="#!">Definir p/ todos</a>
                                            @endif
                                        </td>
                                        <td>
                                            <select required class="select2 cst_csosn form-select form-select-sm" name="cst_csosn[]" style="width: 320px">
                                                @foreach(App\Models\Produto::listaCSTCSOSN() as $key => $v)
                                                <option @if($key == $item->cst_csosn) selected @endif value="{{ $key }}">{{ $v }}</option>
                                                @endforeach
                                            </select>
                                            @if($loop->first)
                                            <a onclick="setCstCsosn()" class="define-batch-link" href="#!">Definir p/ todos</a>
                                            @endif
                                        </td>
                                        <td>
                                            <select required class="select2 cst_pis form-select form-select-sm" name="cst_pis[]" style="width: 320px">
                                                @foreach(App\Models\Produto::listaCST_PIS_COFINS() as $key => $v)
                                                <option @if($key == $item->cst_pis) selected @endif value="{{ $key }}">{{ $v }}</option>
                                                @endforeach
                                            </select>
                                            @if($loop->first)
                                            <a onclick="setCstPis()" class="define-batch-link" href="#!">Definir p/ todos</a>
                                            @endif
                                        </td>
                                        <td>
                                            <select required class="select2 cst_cofins form-select form-select-sm" name="cst_cofins[]" style="width: 320px">
                                                @foreach(App\Models\Produto::listaCST_PIS_COFINS() as $key => $v)
                                                <option @if($key == $item->cst_cofins) selected @endif value="{{ $key }}">{{ $v }}</option>
                                                @endforeach
                                            </select>
                                            @if($loop->first)
                                            <a onclick="setCstCofins()" class="define-batch-link" href="#!">Definir p/ todos</a>
                                            @endif
                                        </td>
                                        <td>
                                            <select required class="select2 cst_ipi form-select form-select-sm" name="cst_ipi[]" style="width: 320px">
                                                @foreach(App\Models\Produto::listaCST_IPI() as $key => $v)
                                                <option @if($key == $item->cst_ipi) selected @endif value="{{ $key }}">{{ $v }}</option>
                                                @endforeach
                                            </select>
                                            @if($loop->first)
                                            <a onclick="setCstIpi()" class="define-batch-link" href="#!">Definir p/ todos</a>
                                            @endif
                                        </td>
                                        <td>
                                            <input required style="width: 100px" type="tel" class="form-control form-control-sm percentual perc_icms" name="perc_icms[]" value="{{ $item->perc_icms }}">
                                            @if($loop->first)
                                            <a onclick="setPercIcms()" class="define-batch-link" href="#!">Definir p/ todos</a>
                                            @endif
                                        </td>
                                        <td>
                                            <input required style="width: 100px" type="tel" class="form-control form-control-sm percentual perc_pis" name="perc_pis[]" value="{{ $item->perc_pis }}">
                                            @if($loop->first)
                                            <a onclick="setPercPis()" class="define-batch-link" href="#!">Definir p/ todos</a>
                                            @endif
                                        </td>
                                        <td>
                                            <input required style="width: 100px" type="tel" class="form-control form-control-sm percentual perc_cofins" name="perc_cofins[]" value="{{ $item->perc_cofins }}">
                                            @if($loop->first)
                                            <a onclick="setPercCofins()" class="define-batch-link" href="#!">Definir p/ todos</a>
                                            @endif
                                        </td>
                                        <td>
                                            <input required style="width: 100px" type="tel" class="form-control form-control-sm percentual perc_ipi" name="perc_ipi[]" value="{{ $item->perc_ipi }}">
                                            @if($loop->first)
                                            <a onclick="setPercIpi()" class="define-batch-link" href="#!">Definir p/ todos</a>
                                            @endif
                                        </td>
                                        <td>
                                            <input required style="width: 100px" type="tel" class="form-control form-control-sm percentual perc_red_bc" name="perc_red_bc[]" value="{{ $item->perc_red_bc }}">
                                            @if($loop->first)
                                            <a onclick="setPercRedBc()" class="define-batch-link" href="#!">Definir p/ todos</a>
                                            @endif
                                        </td>
                                        <td>
                                            <input required style="width: 110px" type="tel" class="form-control form-control-sm cfop cfop_saida_estadual" name="cfop_estadual[]" value="{{ $item->cfop_estadual }}">
                                            @if($loop->first)
                                            <a onclick="setCfopSaidaEstadual()" class="define-batch-link" href="#!">Definir p/ todos</a>
                                            @endif
                                        </td>
                                        <td>
                                            <input required style="width: 110px" type="tel" class="form-control form-control-sm cfop cfop_saida_outro_estado" name="cfop_outro_estado[]" value="{{ $item->cfop_outro_estado }}">
                                            @if($loop->first)
                                            <a onclick="setCfopSaidaOutroEstado()" class="define-batch-link" href="#!">Definir p/ todos</a>
                                            @endif
                                        </td>
                                        <td>
                                            <input required style="width: 110px" type="tel" class="form-control form-control-sm cfop cfop_entrada_estadual" name="cfop_entrada_estadual[]" value="{{ $item->cfop_entrada_estadual }}">
                                            @if($loop->first)
                                            <a onclick="setCfopEntradaEstadual()" class="define-batch-link" href="#!">Definir p/ todos</a>
                                            @endif
                                        </td>
                                        <td>
                                            <input required style="width: 110px" type="tel" class="form-control form-control-sm cfop cfop_entrada_outro_estado" name="cfop_entrada_outro_estado[]" value="{{ $item->cfop_entrada_outro_estado }}">
                                            @if($loop->first)
                                            <a onclick="setCfopEntradaOutroEstado()" class="define-batch-link" href="#!">Definir p/ todos</a>
                                            @endif
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="17" class="text-center py-4 text-muted">
                                            <i class="ri-information-line align-middle fs-18 me-1"></i>
                                            Filtre os dados acima para carregar a lista de produtos.
                                        </td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                    @if(sizeof($data) > 0)
                        <div class="text-end mb-3">
                            <button type="submit" class="btn btn-success px-4">
                                <i class="ri-save-line align-middle me-1"></i> Salvar Alterações
                            </button>
                        </div>
                    @endif
                </form>

            </div>
        </div>
    </div>
</div>
@endsection

@section('js')
<script type="text/javascript">
    $('body').on('blur', '#inp-percentual_valor_venda', function () {
        let percentual = parseFloat($(this).val());
        if (isNaN(percentual)) return;

        $('.valor_venda').each(function () {
            // Pega o input hidden de valor base
            let baseInput = $(this).siblings('.valor_venda[type="hidden"]');
            if (baseInput.length === 0) {
                baseInput = $(this).closest('td').find('input[type="hidden"].valor_venda');
            }
            
            let v = parseFloat(baseInput.val());
            if (!isNaN(v)) {
                let nv = v + (v * (percentual / 100));
                $(this).val(convertFloatToMoeda(nv));
            }
        });
    });

    $("#inp-percentual_valor_venda").mask("Z999.00", {
        translation: {
            '0': {pattern: /\d/},
            '9': {pattern: /\d/, optional: true},
            'Z': {pattern: /[\-\+]/, optional: true}
        }
    });

    function setValorVenda(){
        let v = $('input.valor_venda[type="tel"]').first().val();
        $('input.valor_venda[type="tel"]').val(v);
    }
    function setValorCompra(){
        let v = $('.valor_compra').first().val();
        $('.valor_compra').val(v);
    }
    function setPercIcms(){
        let v = $('.perc_icms').first().val();
        $('.perc_icms').val(v);
    }
    function setPercPis(){
        let v = $('.perc_pis').first().val();
        $('.perc_pis').val(v);
    }
    function setPercCofins(){
        let v = $('.perc_cofins').first().val();
        $('.perc_cofins').val(v);
    }
    function setPercIpi(){
        let v = $('.perc_ipi').first().val();
        $('.perc_ipi').val(v);
    }
    function setPercRedBc(){
        let v = $('.perc_red_bc').first().val();
        $('.perc_red_bc').val(v);
    }
    function setCfopSaidaEstadual(){
        let v = $('.cfop_saida_estadual').first().val();
        $('.cfop_saida_estadual').val(v);
    }
    function setCfopSaidaOutroEstado(){
        let v = $('.cfop_saida_outro_estado').first().val();
        $('.cfop_saida_outro_estado').val(v);
    }
    function setCfopEntradaEstadual(){
        let v = $('.cfop_entrada_estadual').first().val();
        $('.cfop_entrada_estadual').val(v);
    }
    function setCfopEntradaOutroEstado(){
        let v = $('.cfop_entrada_outro_estado').first().val();
        $('.cfop_entrada_outro_estado').val(v);
    }
    function setCstCsosn(){
        let v = $('.cst_csosn').first().val();
        $('.cst_csosn').val(v).change();
    }
    function setCstPis(){
        let v = $('.cst_pis').first().val();
        $('.cst_pis').val(v).change();
    }
    function setCstCofins(){
        let v = $('.cst_cofins').first().val();
        $('.cst_cofins').val(v).change();
    }
    function setCstIpi(){
        let v = $('.cst_ipi').first().val();
        $('.cst_ipi').val(v).change();
    }
</script>
@endsection
