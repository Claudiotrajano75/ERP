@extends('layouts.app', ['title' => 'Empresas do Contador'])

@section('css')
<style>
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
    .act-del { background: #fee2e2; color: #dc2626; }
    .act-del:hover { box-shadow: 0 4px 12px rgba(220,38,38,.3); }

    /* ─── Painel adicionar empresa ─── */
    .add-empresa-wrap { background: #fff; border: 1px solid #e9ecf3; border-radius: 14px; padding: 18px 20px; margin-bottom: 18px; }
    .add-empresa-wrap .form-control, .add-empresa-wrap .form-select {
        height: 40px; border-radius: 10px; border: 1px solid #dcdce9; font-size: 13.5px; background: #fcfdfe;
    }
    .add-empresa-wrap .form-control:focus, .add-empresa-wrap .form-select:focus {
        border-color: #4f46e5; box-shadow: 0 0 0 3px rgba(79,70,229,.12); background: #fff;
    }
    .add-empresa-wrap label { font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: .4px; color: #8c8ca6; }

    /* ─── Estado vazio ─── */
    .empty-state { padding: 52px 20px; text-align: center; }
    .empty-state i { font-size: 52px; color: #c5cae9; display: block; margin-bottom: 12px; }
    .empty-state p { color: #9e9eb8; font-size: 14px; margin: 0; }
</style>
@endsection

@section('content')
<div class="mt-3">
    <div class="row">
        <div class="card border-0 shadow-sm">

            {{-- ═══ CABEÇALHO ═══ --}}
            <div class="card-header modulo-header-gradient py-3 px-4">
                <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                    <div>
                        <h4 class="mb-1 modulo-title d-flex align-items-center gap-2">
                            <i class="ri-building-2-line"></i> Empresas do Contador
                        </h4>
                        <p class="text-muted mb-0 modulo-subtitle fs-13">
                            Gerencie as empresas atribuídas ao contador <strong>{{ $item->nome }}</strong>.
                        </p>
                    </div>
                    <div class="d-flex gap-2 flex-wrap">
                        <a href="{{ route('contadores.index') }}" class="dash-btn dash-btn-light"><i class="ri-arrow-left-line"></i> Voltar</a>
                    </div>
                </div>
            </div>

            <div class="card-body p-4">

                {{-- ═══ Painel: Adicionar Empresa ═══ --}}
                <div class="add-empresa-wrap">
                    <h5 style="font-size:13px;font-weight:700;color:#3f3e6a;text-transform:uppercase;letter-spacing:.5px;" class="mb-3">
                        <i class="ri-add-circle-line" style="color:#4f46e5;margin-right:6px;"></i> Atribuir Nova Empresa
                    </h5>
                    <form class="row g-3 align-items-end" method="post" action="{{ route('contadores.add-business', [$item->id]) }}">
                        @csrf
                        @method('put')
                        <div class="col-md-7 col-12">
                            <label class="form-label"><i class="ri-building-2-line"></i> Empresa</label>
                            {!!Form::select('empresa_contador_id', 'Empresa')
                            ->attrs(['class' => 'form-control'])
                            ->required()
                            !!}
                        </div>
                        <div class="col-md-3 col-12">
                            <button class="dash-btn dash-btn-primary w-100" type="submit">
                                <i class="ri-add-circle-line"></i> Adicionar Empresa
                            </button>
                        </div>
                    </form>
                </div>

                {{-- ═══ Tabela de Empresas Atribuídas ═══ --}}
                <div class="tb-wrap">
                    <div class="table-responsive">
                        <table class="table table-centered table-hover align-middle mb-0">
                            <thead>
                                <tr>
                                    <th>Razão Social</th>
                                    <th>CPF / CNPJ</th>
                                    <th>Data de Cadastro</th>
                                    <th class="text-end" style="width: 80px;">Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($item->empresasAtribuidas as $e)
                                <tr>
                                    <td>
                                        <div class="fw-semibold" style="color:#1f2937;">{{ $e->empresa->nome }}</div>
                                    </td>
                                    <td>{{ $e->empresa->cpf_cnpj }}</td>
                                    <td>{{ __data_pt($e->empresa->created_at) }}</td>
                                    <td class="text-end">
                                        <form action="{{ route('contadores.destroy-business', $e->id) }}" method="post" id="form-{{$e->id}}" class="m-0">
                                            @method('delete')
                                            @csrf
                                            <div class="act-group">
                                                <button type="button" class="act-btn act-del btn-delete" title="Remover empresa">
                                                    <i class="ri-delete-bin-line"></i>
                                                </button>
                                            </div>
                                        </form>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4">
                                        <div class="empty-state">
                                            <i class="ri-building-2-line"></i>
                                            <p>Nenhuma empresa atribuída a este contador.</p>
                                        </div>
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
