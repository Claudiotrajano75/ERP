<div class="row g-3 text-dark">
    
    <div class="col-12">
        <div class="card card-secao-fiscal border p-3 rounded-3 mb-3 bg-white">
            <h5 class="text-dark border-bottom pb-2 mb-3 d-flex align-items-center gap-2 fs-14 fw-bold">
                <i class="ri-calendar-event-line text-primary"></i> 1. Definição e Parâmetros do Evento
            </h5>
            <div class="row g-3">
                <div class="col-md-6 col-12">
                    <label class="form-label required fw-semibold">Nome do Evento</label>
                    {!!Form::text('nome', '')->placeholder('Ex: Adicional de Insalubridade, Vale Transporte, Bônus')->required()->attrs(['class' => 'form-control'])!!}
                </div>
                
                <div class="col-md-3 col-6">
                    <label class="form-label required fw-semibold">Tipo de Recorrência</label>
                    {!!Form::select('tipo', '', ['mensal' => 'Mensal', 'anual' => 'Anual', 'semanal' => 'Semanal'])->attrs(['class' => 'form-select'])->required()!!}
                </div>
                
                <div class="col-md-3 col-6">
                    <label class="form-label required fw-semibold">Método de Entrada</label>
                    {!!Form::select('metodo', '', ['fixo' => 'Fixo', 'informado' => 'Informado no Lançamento'])->attrs(['class' => 'form-select'])->required()!!}
                </div>
                
                <div class="col-md-4 col-6">
                    <label class="form-label required fw-semibold">Condição (Operação)</label>
                    {!!Form::select('condicao', '', ['soma' => 'Soma (Provento / Acréscimo)', 'diminui' => 'Diminui (Desconto)'])->attrs(['class' => 'form-select'])->required()!!}
                </div>
                
                <div class="col-md-4 col-6">
                    <label class="form-label required fw-semibold">Tipo de Valor</label>
                    {!!Form::select('tipo_valor', '', ['fixo' => 'Valor Fixo em Reais (R$)', 'percentual' => 'Percentual (%)'])->attrs(['class' => 'form-select'])->required()!!}
                </div>
                
                <div class="col-md-4 col-12">
                    <label class="form-label required fw-semibold">Status de Ativação</label>
                    {!!Form::select('ativo', '', ['1' => 'Ativo', '0' => 'Inativo'])->attrs(['class' => 'form-select'])->required()!!}
                </div>
            </div>
        </div>
    </div>

    <!-- Rodapé de Envio -->
    <div class="col-12 d-flex align-items-center justify-content-end gap-2 pt-2">
        <a href="{{ route('evento-funcionarios.index') }}" class="dash-btn dash-btn-light">
            <i class="ri-close-line me-1"></i> Cancelar
        </a>
        <button type="submit" class="dash-btn dash-btn-primary px-5" id="btn-store">
            <i class="ri-save-line me-1"></i> {{ isset($item) ? 'Salvar Alterações' : 'Salvar Evento' }}
        </button>
    </div>

</div>