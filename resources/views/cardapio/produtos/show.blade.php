@extends('layouts.app', ['title' => 'Adicionais para ' . $item->nome])

@section('css')
<style>
.modulo-header-gradient { background: linear-gradient(135deg, #0f0c29 0%, #302b63 50%, #24243e 100%); border-radius: 12px 12px 0 0 !important; border-bottom: none !important; }
.modulo-header-gradient .modulo-title { color: #fff; font-weight: 700; letter-spacing: -0.3px; }
.modulo-header-gradient .modulo-title i { background: rgba(255,255,255,0.12); padding: 8px; border-radius: 10px; color: #a8b5ff; }
.modulo-header-gradient .modulo-subtitle { color: rgba(255,255,255,0.6) !important; font-weight: 400; }
.modulo-header-gradient .btn { border-radius: 8px; font-weight: 600; transition: all 0.2s ease; }
.modulo-header-gradient .btn:hover { transform: translateY(-1px); box-shadow: 0 4px 14px rgba(0,0,0,0.25); }
.modulo-glass-filter { background: rgba(255,255,255,0.7); backdrop-filter: blur(12px); -webkit-backdrop-filter: blur(12px); border: 1px solid rgba(255,255,255,0.8) !important; border-radius: 12px; box-shadow: 0 2px 20px rgba(0,0,0,0.04); }
.modulo-glass-filter label { font-weight: 600; font-size: 11px; text-transform: uppercase; letter-spacing: 0.4px; color: #5a5a7a; margin-bottom: 2px; }
.modulo-glass-filter .form-control, .modulo-glass-filter .form-select { height: 38px; }
.modulo-glass-filter .btn { border-radius: 8px; font-weight: 600; font-size: 13px; height: 38px; padding-top: 0; padding-bottom: 0; display: inline-flex; align-items: center; justify-content: center; transition: all 0.2s; }
.modulo-glass-filter .btn:hover { transform: translateY(-1px); }
.modulo-form-card { border: 1px solid #eef0f5; border-radius: 12px; overflow: hidden; }
.modulo-form-card .card-body { background: #fff; }
.modulo-form-card .form-label, .modulo-form-card label:not(.form-check-label) { font-weight: 600; font-size: 12px; color: #5a5a7a; margin-bottom: 4px; }
.modulo-form-card .form-control, .modulo-form-card .form-select { border-radius: 8px; border-color: #e0e3eb; font-size: 13px; padding: 8px 12px; transition: all 0.15s ease; }
.modulo-form-card .form-control:focus, .modulo-form-card .form-select:focus { border-color: #302b63; box-shadow: 0 0 0 3px rgba(48,43,99,0.08); }
.modulo-table-wrap { border-radius: 12px; border: 1px solid #eef0f5; overflow: hidden; margin-top: 24px; }
.modulo-table-wrap table { margin-bottom: 0; }
.modulo-table-wrap thead th { background: #f8f9fc; color: #5a5a7a; font-weight: 700; font-size: 11px; text-transform: uppercase; letter-spacing: 0.4px; padding: 12px 14px; border-bottom: 2px solid #e8eaf6; }
.modulo-table-wrap tbody td { padding: 12px 14px; vertical-align: middle; border-bottom: 1px solid #f0f2f8; transition: background 0.15s ease; font-size: 13px; }
.modulo-table-wrap tbody tr { transition: all 0.15s ease; }
.modulo-table-wrap tbody tr:hover { background: #f5f6fe; }
.modulo-table-wrap tbody tr:last-child td { border-bottom: none; }
.modulo-action-group { display: inline-flex; gap: 4px; flex-wrap: nowrap; align-items: center; }
.modulo-action-group .btn { border-radius: 8px; padding: 4px 10px; font-size: 13px; transition: all 0.15s ease; }
.modulo-action-group .btn:hover { transform: translateY(-1px); }
.modulo-empty { padding: 48px 20px; text-align: center; }
.modulo-empty i { font-size: 48px; color: #c5cae9; margin-bottom: 12px; display: block; }
.modulo-empty p { color: #9e9eb8; font-size: 14px; margin: 0; }
@media (max-width: 768px) { .modulo-header-gradient .modulo-title { font-size: 18px; } }
</style>
@endsection

@section('content')
<div class="mt-3 text-dark">
    <div class="row justify-content-center">
        <div class="col-lg-12">
            <div class="card border-0 shadow-sm modulo-form-card">

                <!-- Cabeçalho Premium -->
                <div class="card-header modulo-header-gradient py-3 px-4">
                    <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                        <div>
                            <h4 class="mb-1 modulo-title d-flex align-items-center gap-2">
                                <i class="ri-play-list-add-line"></i>
                                Adicionais para {{ $item->nome }}
                            </h4>
                            <p class="text-muted mb-0 modulo-subtitle fs-13">
                                Vincule adicionais opcionais a este produto do cardápio.
                            </p>
                        </div>
                        <div class="d-inline-flex gap-2">
                            <a href="{{ route('produtos-cardapio.index') }}" class="btn btn-light btn-sm px-3 text-dark">
                                <i class="ri-arrow-left-line align-middle me-1"></i> Voltar
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Corpo -->
                <div class="card-body p-4">

                    <!-- Formulário para adicionar adicional -->
                    <div class="modulo-glass-filter p-3 mb-4">
                        {!!Form::open()->post()->route('produtos-cardapio.store-adicional')!!}
                        <div class="row g-2 align-items-end">
                            <div class="col-md-6 col-12">
                                {!!Form::select('adicional_id', 'Adicional', ['' => 'Selecione'] + $adicionais->pluck('nome', 'id')->all())->required()
                                ->attrs(['class' => 'select2 form-control'])
                                !!}
                            </div>
                            <input type="hidden" name="produto_id" value="{{ $item->id }}">
                            <div class="col-md-3 col-6">
                                <button class="btn btn-success btn-sm w-100" type="submit">
                                    <i class="ri-add-circle-line me-1"></i> Adicionar
                                </button>
                            </div>
                        </div>
                        {!!Form::close()!!}
                    </div>

                    <!-- Tabela de Adicionais Vinculados -->
                    <h5 class="text-dark border-bottom pb-2 mb-3">
                        <i class="ri-attachment-line text-primary me-2 align-middle fs-18"></i>
                        Adicionais Vinculados
                    </h5>
                    <div class="modulo-table-wrap">
                        <div class="table-responsive">
                            <table class="table table-centered table-hover align-middle mb-0 text-dark">
                                <thead>
                                    <tr>
                                        <th>Adicional</th>
                                        <th>Valor</th>
                                        <th class="text-end" style="width: 100px;">Ações</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($item->adicionais as $a)
                                    <tr>
                                        <td class="fw-semibold text-dark">{{ $a->adicional->nome }}</td>
                                        <td>{{ __moeda($a->adicional->valor) }}</td>
                                        <td class="text-end">
                                            <form action="{{ route('produtos-cardapio.destroy-adicional', $a->id) }}" method="post" id="form-{{$a->id}}" class="m-0">
                                                @method('delete')
                                                @csrf
                                                <div class="modulo-action-group">
                                                    <button type="button" class="btn btn-danger btn-sm btn-delete" title="Remover">
                                                        <i class="ri-delete-bin-line"></i>
                                                    </button>
                                                </div>
                                            </form>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="3">
                                            <div class="modulo-empty">
                                                <i class="ri-attachment-line"></i>
                                                <p>Nenhum adicional vinculado a este produto.</p>
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
@endsection
