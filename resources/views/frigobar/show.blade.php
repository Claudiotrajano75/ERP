@extends('layouts.app', ['title' => 'Padrão de Produtos - Frigobar'])

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
                                <i class="ri-product-hunt-fill"></i>
                                Produtos Padrão
                            </h4>
                            <p class="mb-0 modulo-subtitle fs-13">
                                Frigobar: <strong class="text-white fw-bold">{{ $item->modelo }}</strong> | Acomodação: <strong class="text-white fw-bold">{{ $item->acomodacao->info }}</strong>
                            </p>
                        </div>
                        <a href="{{ route('frigobar.index') }}" class="btn btn-light text-dark fw-semibold px-4 py-2">
                            <i class="ri-arrow-left-double-fill me-1"></i> Voltar
                        </a>
                    </div>
                </div>

                <div class="card-body bg-white p-4">
                    {!!Form::open()
                    ->post()
                    ->route('frigobar.store-default', [$item->id])
                    !!}
                    <div class="pl-lg-4">
                        <div class="row g-2">
                            <div class="table-responsive-sm">
                                <table class="table table-custom mb-0">
                                    <thead>
                                        <tr>
                                            <th>Produto</th>
                                            <th width="150px">Quantidade</th>
                                            <th width="80px">Ações</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if(sizeof($item->padraoProdutos) > 0)
                                        @foreach($item->padraoProdutos as $p)
                                        <tr class="dynamic-form">
                                            <td>
                                                <select required class="form-control select2 produto_id" name="produto_id[]" id="inp-produto_id">
                                                    <option value="{{ $p->produto_id }}">{{ $p->produto->nome }} | R$: {{ __moeda($p->produto->valor_unitario) }}</option>
                                                </select>
                                            </td>
                                            <td>
                                                <input type="tel" value="{{ $p->quantidade }}" class="form-control" data-mask-reverse="true" data-mask="0000.00" name="quantidade[]" required>
                                            </td>
                                            <td>
                                                <button type="button" class="btn btn-danger btn-sm btn-remove-tr" data-bs-toggle="tooltip" title="Remover Produto">
                                                    <i class="ri-delete-bin-fill"></i>
                                                </button>
                                            </td>
                                        </tr>
                                        @endforeach
                                        @else
                                        <tr class="dynamic-form">
                                            <td>
                                                <select required class="form-control select2 produto_id" name="produto_id[]" id="inp-produto_id">
                                                </select>
                                            </td>
                                            <td>
                                                <input type="tel" class="form-control quantidade" name="quantidade[]" required data-mask-reverse="true" data-mask="0000.00">
                                            </td>
                                            <td>
                                                <button type="button" class="btn btn-danger btn-sm btn-remove-tr" data-bs-toggle="tooltip" title="Remover Produto">
                                                    <i class="ri-delete-bin-fill"></i>
                                                </button>
                                            </td>
                                        </tr>
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                            
                            <div class="row col-12 mt-3">
                                <div>
                                    <button type="button" class="btn btn-dark fw-semibold px-3 py-2 btn-add-tr-prod">
                                        <i class="ri-add-fill me-1 align-middle"></i> Adicionar Produto
                                    </button>
                                </div>
                            </div>
                            
                            <div class="col-12 mt-4">
                                <hr class="text-muted opacity-25">
                                <div class="d-flex align-items-center justify-content-end">
                                    <button type="submit" class="btn btn-success px-5 py-2 fw-semibold shadow-sm" id="btn-store">
                                        <i class="ri-save-line align-middle me-1"></i> Salvar Padrão
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    {!!Form::close()!!}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('js')
<script type="text/javascript" src="/js/frigobar.js"></script>
@endsection
