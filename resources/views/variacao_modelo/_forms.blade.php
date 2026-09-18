<style>
    .modulo-section-header { display: flex; align-items: center; gap: 8px; font-size: 14px; font-weight: 700; color: #1f2937; border-bottom: 1px solid #eef0f6; padding-bottom: 10px; margin-bottom: 16px; }
    .modulo-section-header i { color: #4f46e5; }
    .form-label, label { font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: .04em; color: #64748b; }
    .form-control, .form-select, select { border-radius: 10px; border: 1px solid #dcdce9 !important; font-size: 13.5px; background: #fcfdfe; }
    .form-control:focus, .form-select:focus { border-color: #4f46e5 !important; box-shadow: 0 0 0 3px rgba(79,70,229,.12) !important; background: #fff; }
    .modulo-dynamic-table { border-radius: 12px; overflow: hidden; border: 1px solid #eef0f5; }
    .modulo-dynamic-table thead th { background: #f8f9fc; color: #5a5a7a; font-weight: 700; font-size: 11px; text-transform: uppercase; letter-spacing: .4px; }
</style>

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
            <a href="{{ route('variacoes.index') }}" class="dash-btn dash-btn-light px-4">
                <i class="ri-close-line align-middle me-1"></i> Cancelar
            </a>
            <button type="submit" class="dash-btn dash-btn-primary px-4" id="btn-store">
                <i class="ri-save-line align-middle me-1"></i> Salvar Variações
            </button>
        </div>
    </div>

</div>
