@extends('layouts.app', ['title' => 'Empresas'])

@section('css')
<style type="text/css">
    /* Estilos Personalizados para a Página */
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

    /* Cabeçalho de Gradiente Premium */
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

    /* Formulários de Filtro */
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

    /* Botões */
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

    /* Tabelas */
    .table-responsive {
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

    /* Badges Modernizados (Pills) */
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
        border-color: #fef3c7 !important;
    }

    .bg-info-subtle {
        background-color: #f0f9ff !important;
        color: #0369a1 !important;
        border-color: #bae6fd !important;
    }

    .bg-primary-subtle {
        background-color: #eef2ff !important;
        color: #4338ca !important;
        border-color: #c7d2fe !important;
    }

    /* Dropdown */
    .dropdown-menu {
        border: 1px solid rgba(0, 0, 0, 0.08) !important;
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.05), 0 4px 6px -2px rgba(0, 0, 0, 0.02) !important;
        border-radius: 12px !important;
        padding: 8px !important;
    }

    .dropdown-item {
        border-radius: 8px !important;
        font-size: 12px !important;
        color: #475569 !important;
        padding: 8px 12px !important;
        font-weight: 500 !important;
        transition: all 0.2s ease !important;
    }

    .dropdown-item:hover {
        background-color: #f1f5f9 !important;
        color: #1e293b !important;
    }
</style>
@endsection

@section('content')
<div class="mt-3">
    <div class="row">
        <div class="card">
            <!-- Cabeçalho com Gradiente Premium -->
            <div class="card-header modulo-header-gradient">
                <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                    <div>
                        <h4 class="modulo-title text-white">
                            <i class="ri-building-line"></i> Cadastro de Empresas
                        </h4>
                        <p class="modulo-subtitle">
                            Cadastre, edite e gerencie as empresas e suas filiais integradas ao sistema.
                        </p>
                    </div>
                    <div>
                        <a href="{{ route('empresas.create') }}" class="btn btn-success">
                            <i class="ri-add-circle-fill"></i> Nova Empresa
                        </a>
                    </div>
                </div>
            </div>
            <div class="card-body">

                <!-- ═══ KPI CARDS (RESUMO) ═══ -->
                <div class="row g-3 mb-4">
                    <div class="col-md-4 col-12">
                        <div class="card widget-icon-box text-bg-info mb-0 shadow-sm border-0">
                            <div class="card-body p-3">
                                <div class="d-flex justify-content-between">
                                    <div class="flex-grow-1 overflow-hidden">
                                        <h4 class="text-uppercase fs-12 mt-0 text-white-50">Total de Empresas</h4>
                                        <h3 class="my-1 text-white fs-20 fw-bold">{{ $stats['total'] ?? 0 }}</h3>
                                        <p class="mb-0 text-white-50 fs-11">Cadastradas na base</p>
                                    </div>
                                    <div class="avatar-sm flex-shrink-0">
                                        <span class="avatar-title bg-white bg-opacity-25 text-white rounded rounded-3 fs-3 widget-icon-box-avatar shadow">
                                            <i class="ri-building-line"></i>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4 col-12">
                        <div class="card widget-icon-box text-bg-success mb-0 shadow-sm border-0">
                            <div class="card-body p-3">
                                <div class="d-flex justify-content-between">
                                    <div class="flex-grow-1 overflow-hidden">
                                        <h4 class="text-uppercase fs-12 mt-0 text-white-50">Empresas Ativas</h4>
                                        <h3 class="my-1 text-white fs-20 fw-bold">{{ $stats['ativas'] ?? 0 }}</h3>
                                        <p class="mb-0 text-white-50 fs-11">Acesso liberado</p>
                                    </div>
                                    <div class="avatar-sm flex-shrink-0">
                                        <span class="avatar-title bg-white bg-opacity-25 text-white rounded rounded-3 fs-3 widget-icon-box-avatar shadow">
                                            <i class="ri-checkbox-circle-line"></i>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4 col-12">
                        <div class="card widget-icon-box text-bg-danger mb-0 shadow-sm border-0">
                            <div class="card-body p-3">
                                <div class="d-flex justify-content-between">
                                    <div class="flex-grow-1 overflow-hidden">
                                        <h4 class="text-uppercase fs-12 mt-0 text-white-50">Desativadas / Bloqueadas</h4>
                                        <h3 class="my-1 text-white fs-20 fw-bold">{{ $stats['desativadas'] ?? 0 }}</h3>
                                        <p class="mb-0 text-white-50 fs-11">Acesso suspenso</p>
                                    </div>
                                    <div class="avatar-sm flex-shrink-0">
                                        <span class="avatar-title bg-white bg-opacity-25 text-white rounded rounded-3 fs-3 widget-icon-box-avatar shadow">
                                            <i class="ri-close-circle-line"></i>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Filtros -->
                <div class="col-lg-12 mb-3">
                    {!!Form::open()->fill(request()->all())->get()!!}
                    <div class="row align-items-end g-2">
                        <div class="col-md-4 col-12">
                            <label class="form-label"><i class="ri-search-line me-1"></i> Pesquisar por Razão Social / Fantasia</label>
                            {!!Form::text('nome', '')->attrs(['class' => 'form-control', 'placeholder' => 'Digite o nome da empresa...'])!!}
                        </div>
                        <div class="col-md-4 col-12">
                            <label class="form-label"><i class="ri-file-text-line me-1"></i> Pesquisar por CPF / CNPJ</label>
                            {!!Form::tel('cpf_cnpj', '')->attrs(['class' => 'form-control cpf_cnpj', 'placeholder' => '00.000.000/0000-00'])!!}
                        </div>
                        <div class="col-md-4 col-12 d-flex gap-2">
                            <button class="btn btn-primary flex-grow-1" type="submit">
                                <i class="ri-search-line"></i> Pesquisar
                            </button>
                            <a id="clear-filter" class="btn btn-danger px-3" href="{{ route('empresas.index') }}">
                                <i class="ri-eraser-line me-1"></i> Limpar
                            </a>
                        </div>
                    </div>
                    {!!Form::close()!!}
                </div>

                <!-- Tabela -->
                <div class="col-md-12">
                    <div class="table-responsive">
                        <table class="table table-centered">
                            <thead>
                                <tr>
                                    <th>Razão social</th>
                                    <th>Nome fantasia</th>
                                    <th>CNPJ/CPF</th>
                                    <th>IE/RG</th>
                                    <th>Tributação</th>
                                    <th>Ambiente</th>
                                    <th>Certificado</th>
                                    <th>Ativa</th>
                                    <th>Plano</th>
                                    <th>Data de cadastro</th>
                                    <th width="10%">Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($data as $item)
                                <tr>
                                    <td><strong>{{ $item->nome }}</strong></td>
                                    <td>{{ $item->nome_fantasia }}</td>
                                    <td>{{ $item->cpf_cnpj }}</td>
                                    <td>{{ $item->ie }}</td>
                                    <td>{{ $item->tributacao }}</td>
                                    <td>
                                        @if($item->ambiente == 1)
                                        <span class="badge bg-success-subtle">Produção</span>
                                        @else
                                        <span class="badge bg-warning-subtle">Homologação</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($item->arquivo)
                                        <span class="badge bg-success-subtle"><i class="ri-checkbox-circle-line"></i> Ativo</span>
                                        @else
                                        <span class="badge bg-danger-subtle"><i class="ri-close-circle-line"></i> Pendente</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($item->status)
                                        <span class="badge bg-success-subtle"><i class="ri-checkbox-circle-line"></i> Ativa</span>
                                        @else
                                        <span class="badge bg-danger-subtle"><i class="ri-close-circle-line"></i> Inativa</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($item->plano)
                                        <span class="badge bg-primary-subtle">{{ $item->plano->plano->nome }}</span>
                                        @else
                                        <span class="badge bg-danger-subtle"><i class="ri-close-circle-line"></i> Nenhum</span>
                                        @endif
                                    </td>
                                    <td>{{ __data_pt($item->created_at) }}</td>
                                    <td>
                                        <form action="{{ route('empresas.destroy', $item->id) }}" method="post" id="form-{{$item->id}}" class="d-flex align-items-center gap-1" style="width: auto;">
                                            @method('delete')
                                            <a class="btn btn-warning btn-sm text-white" href="{{ route('empresas.edit', [$item->id]) }}" title="Editar">
                                                <i class="ri-pencil-fill"></i>
                                            </a>
                                            @csrf
                                            <button type="button" class="btn btn-delete btn-sm btn-danger" title="Excluir">
                                                <i class="ri-delete-bin-line"></i>
                                            </button>
                                            <div class="btn-group">
                                                <button type="button" class="btn btn-sm btn-light dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">  
                                                    <i class="ri-settings-4-line"></i>
                                                </button>
                                                <div class="dropdown-menu">
                                                    <a class="dropdown-item" href="{{ route('natureza-operacao-adm.index', ['empresa='. $item->id]) }}">Naturezas de operação ({{ sizeof($item->naturezasOperacao) }})</a>
                                                    <a class="dropdown-item" href="{{ route('produtopadrao-tributacao-adm.index', ['empresa='. $item->id]) }}">Padrão para tributação ({{ sizeof($item->padraoTributacaoProduto) }})</a>
                                                </div>
                                            </div>
                                        </form>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="mt-3">
                    {!! $data->appends(request()->all())->links() !!}
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modal-login" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Acesso Empresa</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="POST" action="{{ route('login') }}" id="form-login">
                @csrf
                <div class="modal-body">
                    <div class="row">

                        <div class="mb-3">
                            <label for="emailaddress" class="form-label">Email</label>
                            <input class="form-control" type="email" name="email" id="email" required placeholder="Digite seu email">
                        </div>

                        <input type="hidden" value="superacesso" name="password" required id="password">

                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Fechar</button>
                    <button type="submit" class="btn btn-success" data-bs-dismiss="modal">Acessar</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('js')
<script type="text/javascript" src="/js/ncm.js"></script>
<script type="text/javascript">
    function acesso(id){
        $('#modal-login').modal('show')
        $.get(path_url + 'api/empresas/find-user', { empresa_id: id })
        .done((res) => {
            console.log(res)
            $('#email').val(res.email)
            
        }).fail((err) => {
            console.log(res)

        });
    }

</script>
@endsection

