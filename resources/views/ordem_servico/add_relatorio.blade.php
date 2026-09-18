@extends('layouts.app', ['title' => 'Adicionar Relatório'])

@section('content')
<div class="mt-3">
    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm">

                <!-- ═══ CABEÇALHO PREMIUM ═══ -->
                <div class="card-header modulo-header-gradient py-3 px-4">
                    <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                        <div>
                            <h4 class="mb-1 modulo-title d-flex align-items-center gap-2">
                                <i class="ri-file-add-line"></i>
                                Adicionar Novo Relatório de Atendimento (OS #{{ $ordem->codigo_sequencial }})
                            </h4>
                            <p class="text-muted mb-0 modulo-subtitle fs-13">
                                Adicione observações diárias ou relatórios de evolução técnica na ordem de serviço.
                            </p>
                        </div>
                        <div class="d-flex gap-2 flex-wrap">
                            <a href="{{ route('ordem-servico.show', $ordem->id) }}" class="dash-btn dash-btn-light">
                                <i class="ri-arrow-left-line"></i> Voltar
                            </a>
                        </div>
                    </div>
                </div>

                <!-- ═══ CORPO DO FORMULÁRIO ═══ -->
                <div class="card-body p-4">
                    {!!Form::open()
                    ->post()
                    ->route('ordem-servico.store-relatorio', [$ordem->id])
                    !!}
                    
                    @include('ordem_servico._forms_relatorio')
                    
                    {!!Form::close()!!}
                </div>

            </div>
        </div>
    </div>
</div>
@endsection

