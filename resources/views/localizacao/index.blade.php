@extends('layouts.app', ['title' => 'Localizações'])

@section('css')
<style>
    .modulo-header-gradient { background: linear-gradient(135deg, #0d2b40 0%, #1a4a6e 100%); border-radius: 12px 12px 0 0 !important; border-bottom: none !important; }
    .modulo-header-gradient .modulo-title { color: #fff; font-weight: 700; letter-spacing: -0.3px; }
    .modulo-header-gradient .modulo-title i { background: rgba(255,255,255,0.15); padding: 8px; border-radius: 10px; color: #fff; }
    .modulo-header-gradient .modulo-subtitle { color: rgba(255,255,255,0.85) !important; font-weight: 400; }
    
    .modulo-form-card { border: 1px solid #eef0f5; border-radius: 12px; overflow: hidden; background: #fff; }
    
    /* Tabela */
    .table-custom thead th { background-color: #f8fafc; color: #475569; font-weight: 600; text-transform: uppercase; font-size: 12px; letter-spacing: 0.5px; border-bottom: 2px solid #e2e8f0; border-top: none; padding: 14px 16px; }
    .table-custom tbody tr { transition: all 0.2s; border-bottom: 1px solid #eef0f5; }
    .table-custom tbody tr:hover { background-color: #f8fafc; }
    .table-custom tbody td { padding: 14px 16px; vertical-align: middle; color: #1e293b; font-size: 14px; }
</style>
@endsection

@section('content')
<div class="mt-3 text-dark">
    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm modulo-form-card">
                
                <div class="card-header modulo-header-gradient py-3 px-4">
                    <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                        <div>
                            <h4 class="mb-1 modulo-title d-flex align-items-center gap-2">
                                <i class="ri-map-pin-user-fill"></i>
                                Localizações (Filiais)
                            </h4>
                            <p class="mb-0 modulo-subtitle fs-13">
                                Gerencie as localizações da sua empresa.
                            </p>
                        </div>
                        @can('localizacao_create')
                        <a href="{{ route('localizacao.create') }}" class="btn btn-success fw-semibold px-4 py-2">
                            <i class="ri-add-circle-fill me-1"></i> Nova Localização
                        </a>
                        @endcan
                    </div>
                </div>

                <div class="card-body bg-white p-4">
                    
                    <div class="table-responsive-sm">
                        <table class="table table-custom mb-0">
                            <thead>
                                <tr>
                                    <th>Descrição</th>
                                    <th>Razão Social/Nome</th>
                                    <th>CPF/CNPJ</th>
                                    <th>Cidade</th>
                                    <th>Endereço</th>
                                    <th>CEP</th>
                                    <th>Status</th>
                                    <th width="10%">Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($data as $item)
                                <tr>
                                    <td class="fw-bold">{{ $item->descricao }}</td>
                                    <td>{{ $item->nome }}</td>
                                    <td>{{ $item->cpf_cnpj }}</td>
                                    <td>{{ $item->cidade ? $item->cidade->info : '' }}</td>
                                    <td><span class="text-truncate d-inline-block" style="max-width: 200px;" title="{{ $item->rua ? $item->endereco : '--' }}">{{ $item->rua ? $item->endereco : '--' }}</span></td>
                                    <td>{{ $item->cep }}</td>
                                    <td>
                                        @if($item->status)
                                        <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 px-2 py-1"><i class="ri-check-line"></i> Ativo</span>
                                        @else
                                        <span class="badge bg-danger bg-opacity-10 text-danger border border-danger border-opacity-25 px-2 py-1"><i class="ri-close-line"></i> Inativo</span>
                                        @endif
                                    </td>
                                    <td>
                                        <form action="{{ route('localizacao.destroy', $item->id) }}" method="post" id="form-{{$item->id}}" class="d-flex gap-1 flex-wrap">
                                            @method('delete')
                                            @csrf

                                            @can('localizacao_edit')
                                            <a class="btn btn-warning btn-sm text-white" href="{{ route('localizacao.edit', [$item->id]) }}" data-bs-toggle="tooltip" title="Editar">
                                                <i class="ri-pencil-fill"></i>
                                            </a>
                                            @endcan

                                            @if(!$loop->first)
                                            @can('localizacao_delete')
                                            <button type="button" class="btn btn-delete btn-sm btn-danger" data-bs-toggle="tooltip" title="Excluir">
                                                <i class="ri-delete-bin-fill"></i>
                                            </button>
                                            @endcan
                                            @endif
                                        </form>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="8" class="text-center py-5">
                                        <i class="ri-map-pin-line fs-1 text-muted opacity-50 mb-3 d-block"></i>
                                        <h5 class="text-muted">Nenhuma localização encontrada</h5>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
@endsection
