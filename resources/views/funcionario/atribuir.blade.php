@extends('layouts.app', ['title' => 'Atribuir Serviços'])

@section('css')
<style>
/* ─── Header Gradiente ─── */
.modulo-header-gradient { background: linear-gradient(135deg, #0f0c29 0%, #302b63 50%, #24243e 100%); border-radius: 12px 12px 0 0 !important; border-bottom: none !important; }
.modulo-header-gradient .modulo-title { color: #fff; font-weight: 700; letter-spacing: -0.3px; }
.modulo-header-gradient .modulo-title i { background: rgba(255,255,255,0.12); padding: 8px; border-radius: 10px; color: #a8b5ff; }
.modulo-header-gradient .modulo-subtitle { color: rgba(255,255,255,0.6) !important; font-weight: 400; }
.modulo-header-gradient .btn { border-radius: 8px; font-weight: 600; transition: all 0.2s ease; }
.modulo-header-gradient .btn:hover { transform: translateY(-1px); box-shadow: 0 4px 14px rgba(0,0,0,0.25); }

/* ─── Form Card (Create/Edit) ─── */
.modulo-form-card { border: 1px solid #eef0f5; border-radius: 12px; overflow: hidden; }
.modulo-form-card .card-body { background: #fff; }
.modulo-form-card .form-label,
.modulo-form-card label:not(.form-check-label) { font-weight: 600; font-size: 12px; color: #5a5a7a; margin-bottom: 4px; }
.modulo-form-card .form-control,
.modulo-form-card .form-select { border-radius: 8px; border-color: #e0e3eb; font-size: 13px; padding: 8px 12px; transition: all 0.15s ease; }
.modulo-form-card .form-control:focus,
.modulo-form-card .form-select:focus { border-color: #302b63; box-shadow: 0 0 0 3px rgba(48,43,99,0.08); }

/* ─── Premium Table ─── */
.modulo-table-wrap { border-radius: 12px; border: 1px solid #eef0f5; overflow: hidden; }
.modulo-table-wrap table { margin-bottom: 0; }
.modulo-table-wrap thead th { background: #f8f9fc; color: #5a5a7a; font-weight: 700; font-size: 11px; text-transform: uppercase; letter-spacing: 0.4px; padding: 12px 14px; border-bottom: 2px solid #e8eaf6; }
.modulo-table-wrap tbody td { padding: 12px 14px; vertical-align: middle; border-bottom: 1px solid #f0f2f8; transition: background 0.15s ease; font-size: 13px; }
.modulo-table-wrap tbody tr { transition: all 0.15s ease; }
.modulo-table-wrap tbody tr:hover { background: #f5f6fe; }
.modulo-table-wrap tbody tr:last-child td { border-bottom: none; }

/* ─── Empty State ─── */
.modulo-empty { padding: 48px 20px; text-align: center; }
.modulo-empty i { font-size: 48px; color: #c5cae9; margin-bottom: 12px; display: block; }
.modulo-empty p { color: #9e9eb8; font-size: 14px; margin: 0; }
</style>
@endsection

@section('content')
<div class="mt-3 text-dark">
    <div class="row justify-content-center">
        <div class="col-lg-12">
            <div class="card border-0 shadow-sm modulo-form-card">
                
                <!-- CABEÇALHO PREMIUM -->
                <div class="card-header modulo-header-gradient py-3 px-4">
                    <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                        <div>
                            <h4 class="mb-1 modulo-title d-flex align-items-center gap-2">
                                <i class="ri-user-settings-line"></i>
                                Atribuir Serviços ao Funcionário
                            </h4>
                            <p class="text-muted mb-0 modulo-subtitle fs-13">Defina quais serviços específicos este profissional está capacitado e autorizado a realizar.</p>
                        </div>
                        <div>
                            <a href="{{ route('funcionarios.index') }}" class="btn btn-light btn-sm px-3 text-dark">
                                <i class="ri-arrow-left-line align-middle me-1"></i> Voltar
                            </a>
                        </div>
                    </div>
                </div>

                <!-- CORPO DO FORMULÁRIO -->
                <div class="card-body p-4">
                    
                    <div class="mb-4 p-3 bg-light border rounded shadow-sm d-flex align-items-center">
                        <i class="ri-user-star-line text-success fs-24 me-2"></i>
                        <div>
                            <span class="fs-12 text-muted text-uppercase fw-semibold d-block">Colaborador Selecionado</span>
                            <strong class="text-dark fs-16">{{ $item->nome }}</strong>
                        </div>
                    </div>

                    {!!Form::open()
                    ->post()
                    ->route('funcionarios.atribuir-servico')
                    !!}
                    @csrf
                    
                    <input type="hidden" name="funcionario_id" value="{{ $item->id }}">
                    
                    <div class="row g-2 align-items-end mb-4">
                        <div class="col-md-8 col-12">
                            {!!Form::select('servico_id', 'Serviços Disponíveis', $servicos->pluck('nome', 'id')->all())
                            ->attrs(['class' => 'select2 form-select'])!!}
                        </div>
                        <div class="col-md-4 col-12">
                            <button type="submit" class="btn btn-success w-100 py-2" style="border-radius: 8px; font-weight: 600;">
                                <i class="ri-add-line align-middle me-1"></i> Atribuir Serviço
                            </button>
                        </div>
                    </div>

                    {!!Form::close()!!}

                    <!-- Serviços Atribuídos -->
                    <div class="border-top pt-4">
                        <h5 class="fs-14 fw-bold text-dark mb-3">Serviços Atribuídos Atualmente</h5>
                        <div class="modulo-table-wrap">
                            <div class="table-responsive">
                                <table class="table table-centered table-hover align-middle mb-0 text-dark">
                                    <thead>
                                        <tr>
                                            <th>Descrição do Serviço</th>
                                            <th class="text-end" style="width: 100px;">Ações</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($data as $att)
                                        <tr>
                                            <td class="fw-semibold text-dark">{{ $att->servico->nome }}</td>
                                            <td class="text-end">
                                                <form action="{{ route('funcionarios.deletarAtribuicao', $att->id) }}" method="post" id="form-{{$att->id}}" class="m-0">
                                                    @method('delete')
                                                    @csrf
                                                    <button type="button" class="btn btn-danger btn-sm btn-delete" title="Remover Atribuição" style="border-radius: 8px;">
                                                        <i class="ri-delete-bin-line"></i>
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="2">
                                                <div class="modulo-empty">
                                                    <i class="ri-inbox-2-line"></i>
                                                    <p>Este colaborador ainda não possui serviços atribuídos.</p>
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
    </div>
</div>
@endsection
