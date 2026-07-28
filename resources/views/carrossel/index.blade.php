@extends('layouts.app', ['title' => 'Carrossel Destaque'])

@section('css')
<style>
    .modulo-header-gradient { background: linear-gradient(135deg, #0f0c29 0%, #302b63 50%, #24243e 100%); border-radius: 12px 12px 0 0 !important; border-bottom: none !important; }
    .modulo-header-gradient .modulo-title { color: #fff; font-weight: 700; letter-spacing: -0.3px; }
    .modulo-header-gradient .modulo-title i { background: rgba(255,255,255,0.12); padding: 8px; border-radius: 10px; color: #a8b5ff; }
    .modulo-header-gradient .modulo-subtitle { color: rgba(255,255,255,0.6) !important; font-weight: 400; }
    .modulo-header-gradient .btn { border-radius: 8px; font-weight: 600; transition: all 0.2s ease; }
    .modulo-header-gradient .btn:hover { transform: translateY(-1px); box-shadow: 0 4px 14px rgba(0,0,0,0.25); }
    .modulo-form-card { border: 1px solid #eef0f5; border-radius: 12px; overflow: hidden; }
    .modulo-filter-bar { background: #fff; border-bottom: 1px solid #eef0f5; padding: 16px 24px; }
    .modulo-filter-bar label { font-size: 12px; font-weight: 600; color: #5a5a7a; }
    .modulo-table-wrap table { margin-bottom: 0; }
    .modulo-table-wrap thead th { background: #f8f9fc; color: #5a5a7a; font-weight: 700; font-size: 11px; text-transform: uppercase; letter-spacing: 0.4px; padding: 12px 16px; border-bottom: 2px solid #e8eaf6; }
    .modulo-table-wrap tbody td { padding: 12px 16px; vertical-align: middle; border-bottom: 1px solid #f0f2f8; font-size: 13px; color: #374151; }
    .modulo-table-wrap tbody tr:hover td { background: #fafbff; }
    .modulo-table-wrap tbody tr:last-child td { border-bottom: none; }
    .img-thumb { width: 56px; height: 56px; object-fit: cover; border-radius: 8px; border: 2px solid #eef0f5; }
    .status-pill { display: inline-flex; align-items: center; gap: 4px; padding: 3px 10px; border-radius: 20px; font-size: 11px; font-weight: 700; }
    .status-pill.ativo { background: #dcfce7; color: #16a34a; }
    .status-pill.inativo { background: #fee2e2; color: #dc2626; }
    .modulo-empty { padding: 60px 20px; text-align: center; }
    .modulo-empty i { font-size: 48px; color: #c5cae9; margin-bottom: 12px; display: block; }
    .modulo-empty p { color: #9e9eb8; font-size: 14px; margin: 0; }
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
                                <i class="ri-image-2-line"></i>
                                Carrossel Destaque
                            </h4>
                            <p class="text-muted mb-0 modulo-subtitle fs-13">
                                Gerencie os banners e destaques exibidos no cardápio digital.
                            </p>
                        </div>
                        <div>
                            <a href="{{ route('carrossel.create') }}" class="btn btn-success btn-sm px-3 d-flex align-items-center gap-1 fw-semibold">
                                <i class="ri-add-circle-line fs-18"></i> Novo Carrossel
                            </a>
                        </div>
                    </div>
                </div>

                {{-- BARRA DE FILTRO --}}
                <div class="modulo-filter-bar">
                    {!!Form::open()->fill(request()->all())->get()!!}
                    <div class="row g-2 align-items-end">
                        <div class="col-md-4">
                            {!!Form::select('produto_id', 'Filtrar por produto')!!}
                        </div>
                        <div class="col-md-auto">
                            <button class="btn btn-primary btn-sm px-3 d-flex align-items-center gap-1" type="submit">
                                <i class="ri-search-line"></i> Pesquisar
                            </button>
                        </div>
                        <div class="col-md-auto">
                            <a id="clear-filter" class="btn btn-outline-secondary btn-sm px-3 d-flex align-items-center gap-1" href="{{ route('carrossel.index') }}">
                                <i class="ri-eraser-fill"></i> Limpar
                            </a>
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
                                    <th style="width: 72px;">Imagem</th>
                                    <th>Produto</th>
                                    <th>Descrição</th>
                                    <th>Valor</th>
                                    <th class="text-center">Status</th>
                                    <th class="text-end" style="width: 110px;">Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($data as $item)
                                <tr>
                                    <td><img class="img-thumb" src="{{ $item->img }}" alt="Banner"></td>
                                    <td class="fw-semibold">{{ $item->produto ? $item->produto->nome : '--' }}</td>
                                    <td class="text-muted">{{ $item->descricao }}</td>
                                    <td class="fw-semibold">R$ {{ __moeda($item->valor) }}</td>
                                    <td class="text-center">
                                        @if($item->status)
                                        <span class="status-pill ativo"><i class="ri-checkbox-circle-fill"></i> Ativo</span>
                                        @else
                                        <span class="status-pill inativo"><i class="ri-close-circle-fill"></i> Inativo</span>
                                        @endif
                                    </td>
                                    <td class="text-end">
                                        <form action="{{ route('carrossel.destroy', $item->id) }}" method="post" id="form-{{$item->id}}" class="d-inline-flex gap-1">
                                            @method('delete')
                                            @csrf
                                            <a class="btn btn-warning btn-sm" href="{{ route('carrossel.edit', [$item->id]) }}" title="Editar">
                                                <i class="ri-edit-line"></i>
                                            </a>
                                            <button type="button" class="btn btn-delete btn-sm btn-danger" title="Excluir">
                                                <i class="ri-delete-bin-line"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6">
                                        <div class="modulo-empty">
                                            <i class="ri-image-2-line"></i>
                                            <p>Nenhum item de carrossel cadastrado.</p>
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                @if($data->total() > 0)
                <div class="px-4 py-3 border-top bg-white">
                    {!! $data->appends(request()->all())->links() !!}
                </div>
                @endif

            </div>
        </div>
    </div>
</div>
@endsection
