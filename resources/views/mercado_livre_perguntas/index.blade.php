@extends('layouts.app', ['title' => 'Perguntas Mercado Livre'])

@section('css')
<style>
    .modulo-header-gradient { background: linear-gradient(135deg, #ffe600 0%, #f4d000 50%, #e6b800 100%); border-radius: 12px 12px 0 0 !important; border-bottom: none !important; }
    .modulo-header-gradient .modulo-title { color: #333; font-weight: 700; letter-spacing: -0.3px; }
    .modulo-header-gradient .modulo-title i { background: rgba(0,0,0,0.05); padding: 8px; border-radius: 10px; color: #333; }
    .modulo-header-gradient .modulo-subtitle { color: rgba(51,51,51,0.7) !important; font-weight: 400; }
    
    .modulo-form-card { border: 1px solid #eef0f5; border-radius: 12px; overflow: hidden; }
    
    .modulo-filter-bar { background: #fff; border-bottom: 1px solid #eef0f5; padding: 16px 24px; }
    .modulo-filter-bar label { font-size: 12px; font-weight: 600; color: #5a5a7a; }
    
    .modulo-table-wrap table { margin-bottom: 0; }
    .modulo-table-wrap thead th { background: #f8f9fc; color: #5a5a7a; font-weight: 700; font-size: 11px; text-transform: uppercase; letter-spacing: 0.4px; padding: 12px 16px; border-bottom: 2px solid #e8eaf6; }
    .modulo-table-wrap tbody td { padding: 12px 16px; vertical-align: middle; border-bottom: 1px solid #f0f2f8; font-size: 13px; color: #374151; }
    .modulo-table-wrap tbody tr:hover td { background: #fafbff; }
    .modulo-table-wrap tbody tr:last-child td { border-bottom: none; }
    
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
                                <i class="ri-question-answer-fill"></i>
                                Perguntas do Mercado Livre
                            </h4>
                            <p class="text-muted mb-0 modulo-subtitle fs-13">
                                Gerencie e responda as dúvidas dos clientes nos seus anúncios.
                            </p>
                        </div>
                    </div>
                </div>

                {{-- BARRA DE FILTRO --}}
                <div class="modulo-filter-bar">
                    {!!Form::open()->fill(request()->all())->get()!!}
                    <div class="row g-2 align-items-end">
                        <div class="col-md-3">
                            {!!Form::select('status', 'Status da Pergunta', ['UNANSWERED' => 'AGUARDANDO RESPOSTA', 'ANSWERED' => 'RESPONDIDA'])
                            ->attrs(['class' => 'form-select'])
                            !!}
                        </div>
                        <div class="col-md-auto">
                            <button class="btn btn-primary btn-sm px-3 d-flex align-items-center gap-1" type="submit">
                                <i class="ri-search-line"></i> Pesquisar
                            </button>
                        </div>
                        <div class="col-md-auto">
                            <a id="clear-filter" class="btn btn-outline-secondary btn-sm px-3 d-flex align-items-center gap-1" href="{{ route('mercado-livre-perguntas.index') }}">
                                <i class="ri-eraser-fill"></i> Limpar
                            </a>
                        </div>
                    </div>
                    {!!Form::close()!!}
                </div>
                
                {{-- TABELA DE PERGUNTAS --}}
                <div class="modulo-table-wrap">
                    <div class="table-responsive">
                        <table class="table align-middle">
                            <thead>
                                <tr>
                                    <th width="20%">Anúncio</th>
                                    <th>Pergunta</th>
                                    <th width="15%">Data</th>
                                    <th width="15%">Status</th>
                                    <th class="text-end" width="10%">Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($data as $item)
                                <tr>
                                    <td class="fw-semibold text-primary">
                                        <div class="text-truncate" style="max-width: 250px;" title="{{ $item->anuncio ? $item->anuncio->nome : '#'.$item->item_id }}">
                                            {{ $item->anuncio ? $item->anuncio->nome : '#'.$item->item_id }}
                                        </div>
                                    </td>
                                    <td>
                                        <div class="text-wrap" style="max-width: 400px;">
                                            <i class="ri-chat-1-line text-muted me-1"></i> {{ $item->texto }}
                                        </div>
                                    </td>
                                    <td class="text-muted">{{ __data_pt($item->data) }}</td>
                                    <td>
                                        @if($item->status == 'UNANSWERED')
                                            <span class="badge bg-warning text-dark border border-warning px-2 py-1"><i class="ri-time-line"></i> Aguardando Resposta</span>
                                        @elseif($item->status == 'ANSWERED')
                                            <span class="badge bg-success bg-opacity-10 text-success border border-success px-2 py-1"><i class="ri-check-double-line"></i> Respondida</span>
                                        @else
                                            <span class="badge bg-light text-secondary">{{ $item->status }}</span>
                                        @endif
                                    </td>
                                    <td class="text-end">
                                        <form action="{{ route('mercado-livre-perguntas.destroy', $item->id) }}" method="post" id="form-{{$item->id}}" class="d-inline-flex gap-1 m-0">
                                            @method('delete')
                                            @csrf

                                            @if($item->status == 'UNANSWERED')
                                            <a title="Responder pergunta" class="btn btn-primary btn-sm px-2 rounded-2" href="{{ route('mercado-livre-perguntas.show', [$item->id]) }}">
                                                <i class="ri-reply-fill"></i> Responder
                                            </a>
                                            @else
                                            <a title="Ver resposta" class="btn btn-light border btn-sm px-2 rounded-2" href="{{ route('mercado-livre-perguntas.show', [$item->id]) }}">
                                                <i class="ri-eye-line"></i> Ver
                                            </a>
                                            @endif
                                            
                                            <button type="button" class="btn btn-delete btn-sm btn-danger px-2 rounded-2" title="Remover Pergunta">
                                                <i class="ri-delete-bin-line"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5">
                                        <div class="modulo-empty">
                                            <i class="ri-chat-smile-3-line"></i>
                                            <p>Nenhuma pergunta encontrada no momento.</p>
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                @if($data->hasPages())
                <div class="px-4 py-3 border-top bg-white">
                    {!! $data->appends(request()->all())->links() !!}
                </div>
                @endif
                
            </div>
        </div>
    </div>
</div>
@endsection

