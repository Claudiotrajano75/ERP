@extends('layouts.app', ['title' => 'Alterar Estado do Pedido'])

@section('content')
<div class="mt-3 text-dark">
    <div class="row justify-content-center">
        <div class="col-lg-12">
            <div class="card border-0 shadow-sm text-dark modulo-form-card">

                <!-- CABEÇALHO -->
                <div class="card-header modulo-header-gradient py-3 px-4">
                    <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                        <div>
                            <h4 class="mb-1 modulo-title d-flex align-items-center gap-2">
                                <i class="ri-refresh-line"></i>
                                Alterar Estado &mdash; Pedido #{{ $item->hash_pedido }}
                            </h4>
                            <p class="text-muted mb-0 modulo-subtitle fs-13">Atualize o status de processamento, rastreamento e pagamento deste pedido.</p>
                        </div>
                        <div>
                            <a href="{{ route('pedidos-ecommerce.index') }}" class="dash-btn dash-btn-light">
                                <i class="ri-arrow-left-line"></i> Voltar
                            </a>
                        </div>
                    </div>
                </div>

                <div class="card-body p-4">
                    {!!Form::open()->fill($item)
                    ->put()
                    ->route('pedidos-ecommerce.update', [$item->id])
                    !!}
                    <div class="row g-3">
                        <div class="col-md-3 col-12">
                            {!!Form::select('estado', 'Estado do Pedido', [
                            'novo' => 'Novo', 'preparando' => 'Preparando', 'em_trasporte' => 'Em transporte', 
                            'finalizado' => 'Finalizado', 'recusado' => 'Recusado'
                            ])->required()->attrs(['class' => 'form-select'])
                            !!}
                        </div>

                        <div class="col-md-2 col-6">
                            {!!Form::tel('valor_frete', 'Valor do Frete (R$)')->required()
                            ->attrs(['class' => 'form-control moeda'])
                            ->value(__moeda($item->valor_frete))
                            !!}
                        </div>

                        <div class="col-md-2 col-6">
                            {!!Form::date('data_entrega', 'Data Prevista Entrega')
                            ->attrs(['class' => 'form-control'])
                            !!}
                        </div>

                        <div class="col-md-3 col-12">
                            {!!Form::text('codigo_rastreamento', 'Código de Rastreamento')
                            ->attrs(['class' => 'form-control', 'placeholder' => 'Ex: BR123456789BR'])
                            !!}
                        </div>

                        <div class="col-md-2 col-12">
                            {!!Form::select('status_pagamento', 'Status do Pagamento', [
                            'approved' => 'Aprovado', 'pending' => 'Pendente'
                            ])->required()->attrs(['class' => 'form-select'])
                            !!}
                        </div>

                        <div class="col-12 d-flex align-items-center justify-content-end gap-2 mt-4 pt-3 border-top">
                            <a href="{{ route('pedidos-ecommerce.index') }}" class="dash-btn dash-btn-light">
                                <i class="ri-close-line"></i> Cancelar
                            </a>
                            <button type="submit" class="dash-btn dash-btn-primary px-4" id="btn-store">
                                <i class="ri-save-line"></i> Salvar Alterações
                            </button>
                        </div>
                    </div>
                    {!!Form::close()!!}
                </div>

            </div>
        </div>
    </div>
</div>
@endsection