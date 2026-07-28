<div class="row g-3 text-dark">

    <!-- ═══ Seção 1: Identificação do Modelo ═══ -->
    <div class="col-12">
        <h5 class="modulo-section-header">
            <i class="ri-information-line"></i>
            1. Identificação do Modelo
        </h5>
        <div class="row g-3">
            <div class="col-md-6 col-12">
                {!!Form::text('descricao', 'Descrição (Ex: Grade de Cores, Tamanho de Calçados)')->required()->attrs(['class' => 'form-control', 'placeholder' => 'Ex: Grade de Cores, Tamanho de Calçados'])!!}
            </div>
            <div class="col-md-3 col-12">
                {!!Form::select('status', 'Ativo', ['1' => 'Sim', '0' => 'Não'])
                ->attrs(['class' => 'form-select'])!!}
            </div>
        </div>
    </div>

    <!-- ═══ Seção 2: Valores / Opções do Atributo ═══ -->
    <div class="col-12 mt-4">
        <h5 class="modulo-section-header">
            <i class="ri-list-check-2"></i>
            2. Valores / Opções do Atributo
        </h5>

        <div class="modulo-dynamic-table">
            <div class="table-responsive mb-0">
                <table class="table table-centered align-middle mb-0 table-dynamic">
                    <thead>
                        <tr>
                            <th style="width: 50px;" class="text-center">#</th>
                            <th>Nome do Valor (Ex: P, M, G ou Azul, Verde)</th>
                        </tr>
                    </thead>
                    <tbody>
                        @isset($item)
                            @foreach($item->itens as $l)
                            <tr class="dynamic-form">
                                <td class="text-center">
                                    <button type="button" class="btn btn-danger btn-remove-tr btn-sm" title="Remover linha">
                                        <i class="ri-delete-bin-line"></i>
                                    </button>
                                </td>
                                <td>
                                    {!!Form::text('nome[]', '')->required()->attrs(['class' => 'form-control'])->value($l->nome)!!}
                                </td>
                            </tr>
                            @endforeach
                        @else
                            <tr class="dynamic-form">
                                <td class="text-center">
                                    <button type="button" class="btn btn-danger btn-remove-tr btn-sm" title="Remover linha">
                                        <i class="ri-delete-bin-line"></i>
                                    </button>
                                </td>
                                <td>
                                    {!!Form::text('nome[]', '')->required()->attrs(['class' => 'form-control', 'placeholder' => 'Digite um valor (ex: P)'])!!}
                                </td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Importante: div.row mantida para compatibilidade com JS (.closest(".row").prev()) -->
        <div class="row mt-3">
            <div class="col-12 col-lg-3">
                <button type="button" class="btn btn-dark btn-add-tr btn-sm w-100">
                    <i class="ri-add-line align-middle me-1"></i> Adicionar linha
                </button>
            </div>
        </div>
    </div>

    <!-- ═══ Botões de Ação ═══ -->
    <div class="col-12 mt-4">
        <hr class="text-muted opacity-25">
        <div class="d-flex gap-2 justify-content-end">
            <a href="{{ route('variacoes.index') }}" class="btn btn-outline-secondary">
                <i class="ri-close-line align-middle me-1"></i> Cancelar
            </a>
            <button type="submit" class="btn btn-success px-4" id="btn-store">
                <i class="ri-save-line align-middle me-1"></i> Salvar Variações
            </button>
        </div>
    </div>

</div>
