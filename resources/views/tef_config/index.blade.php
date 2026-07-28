@extends('layouts.app', ['title' => 'Configuração de TEF'])
@section('content')
<div class="mt-3 text-dark">
    <div class="row">
        <div class="card border-0 shadow-sm text-dark">

            <!-- Cabeçalho Principal -->
            <div class="card-header bg-transparent border-bottom py-3">
                <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                    <div>
                        <h4 class="mb-1 text-dark d-flex align-items-center">
                            <i class="ri-wifi-line me-2 text-primary fs-22"></i>
                            Configuração de TEF
                        </h4>
                        <p class="text-muted mb-0 fs-13">Gerencie as configurações de TEF (Transferência Eletrônica de Fundos) para integração com máquinas de cartão.</p>
                    </div>
                    <div class="d-inline-flex gap-1">
                        <a href="{{ route('tef-config.create') }}" class="btn btn-success btn-sm px-3">
                            <i class="ri-add-circle-line align-middle me-1"></i> Nova Configuração
                        </a>
                    </div>
                </div>
            </div>

            <div class="card-body p-4">

                <div class="table-responsive">
                    <table class="table table-centered table-hover align-middle mb-0 text-dark">
                        <thead class="table-light">
                            <tr>
                                <th>Usuário</th>
                                <th>CNPJ</th>
                                <th>PDV</th>
                                <th>Status</th>
                                <th class="text-end">Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($data as $item)
                            <tr>
                                <td class="fw-semibold">{{ $item->usuario->name }}</td>
                                <td>{{ $item->cnpj }}</td>
                                <td class="fw-bold">{{ $item->pdv }}</td>
                                <td>
                                    @if($item->status)
                                    <span class="badge bg-success-subtle text-success border border-success-subtle px-2 py-1 fs-11">
                                        <i class="ri-checkbox-circle-fill me-1"></i>Ativo
                                    </span>
                                    @else
                                    <span class="badge bg-danger-subtle text-danger border border-danger-subtle px-2 py-1 fs-11">
                                        <i class="ri-close-circle-fill me-1"></i>Inativo
                                    </span>
                                    @endif
                                </td>
                                <td class="text-end">
                                    <form action="{{ route('tef-config.destroy', $item->id) }}" method="post" id="form-{{$item->id}}" class="m-0">
                                        @method('delete')
                                        @csrf
                                        <div class="d-inline-flex gap-1">
                                            <a class="btn btn-warning btn-sm text-white" href="{{ route('tef-config.edit', [$item->id]) }}" title="Editar">
                                                <i class="ri-pencil-line"></i>
                                            </a>
                                            <button type="button" class="btn btn-danger btn-sm btn-delete" title="Excluir">
                                                <i class="ri-delete-bin-line"></i>
                                            </button>
                                            @if($item->status)
                                            <button id="btn-status" title="Consultar Status" type="button" class="btn btn-dark btn-sm text-white">
                                                <i class="ri-checkbox-circle-line"></i>
                                            </button>
                                            @endif
                                        </div>
                                    </form>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted py-4">Nenhuma configuração de TEF encontrada.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </div>
</div>
@endsection
@section('js')
<script type="text/javascript">
    $('body').on('click', '#btn-status', function () {
        $.get(path_url + "api/tef/verifica-ativo",
        {
            empresa_id: $('#empresa_id').val(),
            usuario_id: $('#usuario_id').val(),
        })
        .done((data) => {
            console.log(data);
            swal("Sucesso", "TEF Ativo", "success")
        })
        .fail((e) => {
            console.log(e);
            swal("Erro", e.responseJSON, "error")
        });
    })
</script>
@endsection
