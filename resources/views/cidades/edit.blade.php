@extends('layouts.app', ['title' => 'Editar Cidade'])

@section('content')
<div class="mt-3 text-dark">
    <div class="row justify-content-center">
        <div class="col-lg-12">
            <div class="card border-0 shadow-sm modulo-form-card">

                <!-- ═══ CABEÇALHO PREMIUM ═══ -->
                <div class="card-header modulo-header-gradient py-3 px-4">
                    <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                        <div>
                            <h4 class="mb-1 modulo-title d-flex align-items-center gap-2">
                                <i class="ri-edit-line"></i>
                                Editar Cidade
                            </h4>
                            <p class="text-muted mb-0 modulo-subtitle fs-13">
                                Atualize os dados do município selecionado.
                            </p>
                        </div>
                        <div>
                            <a href="{{ route('cidades.index') }}" class="btn btn-light btn-sm px-3 text-dark">
                                <i class="ri-arrow-left-line align-middle me-1"></i> Voltar
                            </a>
                        </div>
                    </div>
                </div>

                <!-- ═══ CORPO DO FORMULÁRIO ═══ -->
                <div class="card-body p-4">
                    {!!Form::open()->fill($item)->put()->route('cidades.update', [$item->id])->multipart()!!}

                    @include('cidades._forms', ['edit' => true])

                    <!-- Botões de Ação -->
                    <hr class="mt-4">
                    <div class="modulo-actions">
                        <div class="d-flex gap-2 justify-content-end">
                            <a href="{{ route('cidades.index') }}" class="btn btn-outline-secondary">
                                <i class="ri-close-line align-middle me-1"></i> Cancelar
                            </a>
                            <button type="submit" class="btn btn-primary px-4" id="btn-store">
                                <i class="ri-save-line align-middle me-1"></i> Salvar Alterações
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