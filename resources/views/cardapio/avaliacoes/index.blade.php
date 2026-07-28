@extends('layouts.app', ['title' => 'Avaliações do Cardápio'])

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
.modulo-glass-filter .form-control, .modulo-glass-filter .form-select { height: 38px; } .modulo-glass-filter .btn { border-radius: 8px; font-weight: 600; font-size: 13px; height: 38px; padding-top: 0; padding-bottom: 0; display: inline-flex; align-items: center; justify-content: center; transition: all 0.2s; }
.modulo-glass-filter .btn:hover { transform: translateY(-1px); }
.modulo-table-wrap { border-radius: 12px; border: 1px solid #eef0f5; overflow: hidden; }
.modulo-table-wrap table { margin-bottom: 0; }
.modulo-table-wrap thead th { background: #f8f9fc; color: #5a5a7a; font-weight: 700; font-size: 11px; text-transform: uppercase; letter-spacing: 0.4px; padding: 12px 14px; border-bottom: 2px solid #e8eaf6; }
.modulo-table-wrap tbody td { padding: 12px 14px; vertical-align: middle; border-bottom: 1px solid #f0f2f8; transition: background 0.15s ease; font-size: 13px; }
.modulo-table-wrap tbody tr { transition: all 0.15s ease; }
.modulo-table-wrap tbody tr:hover { background: #f5f6fe; }
.modulo-table-wrap tbody tr:last-child td { border-bottom: none; }
.modulo-empty { padding: 48px 20px; text-align: center; }
.modulo-empty i { font-size: 48px; color: #c5cae9; margin-bottom: 12px; display: block; }
.modulo-empty p { color: #9e9eb8; font-size: 14px; margin: 0; }
.modulo-footer { padding: 16px 0 0; display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 12px; }
.star-rating i { font-size: 18px; margin-right: 2px; }
@media (max-width: 768px) { .modulo-header-gradient .modulo-title { font-size: 18px; } }
</style>
@endsection

@section('content')
<div class="mt-3 text-dark">
    <div class="row">
        <div class="card border-0 shadow-sm text-dark">

            <!-- Cabeçalho Premium -->
            <div class="card-header modulo-header-gradient py-3 px-4">
                <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                    <div>
                        <h4 class="mb-1 modulo-title d-flex align-items-center gap-2">
                            <i class="ri-star-s-line"></i>
                            Avaliações do Cardápio
                        </h4>
                        <p class="text-muted mb-0 modulo-subtitle fs-13">
                            Acompanhe as avaliações e feedbacks dos clientes sobre o cardápio digital.
                        </p>
                    </div>
                </div>
            </div>

            <div class="card-body p-4">

                <!-- Filtros Glass -->
                <div class="modulo-glass-filter p-3 mb-4">
                    {!!Form::open()->fill(request()->all())->get()!!}
                    <div class="row g-2 align-items-end">
                        <div class="col-md-6 col-12">
                            {!!Form::text('nome', 'Pesquisar por cliente')!!}
                        </div>
                        <div class="col-md-3 col-6">
                            <button class="btn btn-primary btn-sm w-100" type="submit">
                                <i class="ri-search-line me-1"></i> Pesquisar
                            </button>
                        </div>
                        <div class="col-md-3 col-6">
                            <a class="btn btn-danger btn-sm w-100" href="{{ route('avaliacao-cardapio.index') }}">
                                <i class="ri-eraser-line me-1"></i> Limpar
                            </a>
                        </div>
                    </div>
                    {!!Form::close()!!}
                </div>

                <!-- Tabela Premium -->
                <div class="modulo-table-wrap">
                    <div class="table-responsive">
                        <table class="table table-centered table-hover align-middle mb-0 text-dark">
                            <thead>
                                <tr>
                                    <th>Cliente</th>
                                    <th>Telefone</th>
                                    <th>Avaliação</th>
                                    <th>Observação</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($data as $item)
                                <tr>
                                    <td class="fw-semibold text-dark">{{ $item->pedido ? $item->pedido->cliente_nome : '--' }}</td>
                                    <td>
                                        @if($item->pedido && $item->pedido->cliente_telefone)
                                        <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-2 py-1 fs-11">
                                            <i class="ri-phone-line me-1"></i>{{ $item->pedido->cliente_telefone }}
                                        </span>
                                        @else
                                        <span class="text-muted">--</span>
                                        @endif
                                    </td>
                                    <td class="star-rating">
                                        @for($i=1;$i<=5;$i++)
                                        @if($item->avaliacao >= $i)
                                        <i class="ri-star-fill text-warning"></i>
                                        @else
                                        <i class="ri-star-line text-muted"></i>
                                        @endif
                                        @endfor
                                    </td>
                                    <td>
                                        @if($item->observacao)
                                        <span class="text-muted">{{ $item->observacao }}</span>
                                        @else
                                        <span class="text-muted">--</span>
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4">
                                        <div class="modulo-empty">
                                            <i class="ri-star-s-line"></i>
                                            <p>Nenhuma avaliação encontrada.</p>
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Footer -->
                <div class="modulo-footer">
                    <div class="text-muted fs-13">
                        Total: <strong>{{ $data->total() }}</strong> {{ $data->total() == 1 ? 'avaliação' : 'avaliações' }}
                    </div>
                    <div>
                        {!! $data->appends(request()->all())->links() !!}
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
@endsection
