@extends('layouts.app', ['title' => 'Atribuir Serviços'])

@section('css')
<style>
/* ─── Botões de Ação Squircle ─── */
.act-group { display: inline-flex; gap: 6px; align-items: center; justify-content: flex-end; }
.act-btn { 
    width: 34px; 
    height: 34px; 
    border-radius: 10px; 
    border: 1px solid transparent; 
    display: inline-flex; 
    align-items: center; 
    justify-content: center; 
    font-size: 15px; 
    text-decoration: none; 
    cursor: pointer; 
    transition: all .2s ease; 
    padding: 0;
}
.act-btn:hover { transform: translateY(-2px); text-decoration: none; }
.act-del  { background: #fef2f2; color: #dc2626; border-color: #fecaca; }
.act-del:hover  { background: #fee2e2; color: #b91c1c; box-shadow: 0 4px 12px rgba(220,38,38,.2); }

/* ─── Tabela ─── */
.tb-wrap { border-radius: 14px; border: 1px solid #eef0f5; overflow: hidden; background: #fff; }
.tb-wrap table { margin-bottom: 0; }
.tb-wrap thead th { background: #f8f9fc; color: #5a5a7a; font-weight: 700; font-size: 11px; text-transform: uppercase; letter-spacing: .4px; padding: 13px 16px; border-bottom: 1px solid #e8eaf6; white-space: nowrap; }
.tb-wrap tbody td { padding: 13px 16px; vertical-align: middle; border-bottom: 1px solid #f0f2f8; font-size: 13.5px; color: #374151; }
.tb-wrap tbody tr:hover { background: #f5f6fe; }
.tb-wrap tbody tr:last-child td { border-bottom: none; }

/* ─── Empty State ─── */
.modulo-empty { padding: 48px 20px; text-align: center; }
.modulo-empty i { font-size: 44px; color: #cbd5e1; margin-bottom: 10px; display: block; }
.modulo-empty p { color: #94a3b8; font-size: 14px; margin: 0; }
</style>
@endsection

@section('content')
<div class="mt-3 text-dark">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="card border-0 shadow-sm text-dark modulo-form-card">
                
                <!-- CABEÇALHO -->
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
                            <a href="{{ route('funcionarios.index') }}" class="dash-btn dash-btn-light">
                                <i class="ri-arrow-left-line"></i> Voltar
                            </a>
                        </div>
                    </div>
                </div>

                <!-- CORPO DO FORMULÁRIO -->
                <div class="card-body p-4">
                    
                    <div class="mb-4 p-3 bg-light border rounded-3 shadow-sm d-flex align-items-center gap-3">
                        <div style="width: 44px; height: 44px; border-radius: 12px; background: #eef2ff; color: #4f46e5; display: flex; align-items: center; justify-content: center; font-size: 22px;">
                            <i class="ri-user-star-line"></i>
                        </div>
                        <div>
                            <span class="fs-11 text-muted text-uppercase fw-bold d-block">Colaborador Selecionado</span>
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
                            <label class="form-label fw-semibold">Selecione o Serviço Disponível</label>
                            {!!Form::select('servico_id', '', ['' => 'Selecione o serviço'] + $servicos->pluck('nome', 'id')->all())
                            ->attrs(['class' => 'select2 form-select'])!!}
                        </div>
                        <div class="col-md-4 col-12">
                            <button type="submit" class="dash-btn dash-btn-primary w-100 py-2 justify-content-center">
                                <i class="ri-add-line me-1"></i> Atribuir Serviço
                            </button>
                        </div>
                    </div>

                    {!!Form::close()!!}

                    <!-- Serviços Atribuídos -->
                    <div class="border-top pt-4">
                        <h5 class="fs-14 fw-bold text-dark mb-3 d-flex align-items-center gap-2">
                            <i class="ri-list-check-2 text-primary"></i> Serviços Atribuídos Atualmente
                        </h5>
                        <div class="tb-wrap">
                            <div class="table-responsive">
                                <table class="table table-hover align-middle mb-0 text-dark">
                                    <thead>
                                        <tr>
                                            <th>Descrição do Serviço</th>
                                            <th class="text-end" style="width: 100px;">Ações</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($data as $att)
                                        <tr>
                                            <td class="fw-semibold text-dark">
                                                <i class="ri-check-line text-success me-1"></i> {{ $att->servico->nome }}
                                            </td>
                                            <td class="text-end">
                                                <form action="{{ route('funcionarios.deletarAtribuicao', $att->id) }}" method="post" id="form-{{$att->id}}" class="m-0">
                                                    @method('delete')
                                                    @csrf
                                                    <button type="button" class="act-btn act-del btn-delete" title="Remover Atribuição">
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
