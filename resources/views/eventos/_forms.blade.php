<div class="row g-3 text-dark">
    
    <div class="col-md-6 col-12">
        {!!Form::text('nome', 'Nome do Evento')->placeholder('Ex: Adicional Insalubridade, Vale Transporte')->required()->attrs(['class' => 'form-control'])!!}
    </div>
    
    <div class="col-md-2 col-6">
        {!!Form::select('tipo', 'Tipo de Recorrência', ['mensal' => 'Mensal', 'anual' => 'Anual', 'semanal' => 'Semanal'])->attrs(['class' => 'form-select'])->required()!!}
    </div>
    
    <div class="col-md-2 col-6">
        {!!Form::select('metodo', 'Método de Entrada', ['fixo' => 'Fixo', 'informado' => 'Informado'])->attrs(['class' => 'form-select'])->required()!!}
    </div>
    
    <div class="col-md-2 col-6">
        {!!Form::select('condicao', 'Condição (Operação)', ['soma' => 'Soma (Provento)', 'diminui' => 'Diminui (Desconto)'])->attrs(['class' => 'form-select'])->required()!!}
    </div>
    
    <div class="col-md-2 col-6">
        {!!Form::select('tipo_valor', 'Tipo Valor', ['fixo' => 'Valor Fixo', 'percentual' => 'Percentual'])->attrs(['class' => 'form-select'])->required()!!}
    </div>
    
    <div class="col-md-2 col-6">
        {!!Form::select('ativo', 'Status (Ativo)', ['1' => 'Sim', '0' => 'Não'])->attrs(['class' => 'form-select'])->required()!!}
    </div>

    <!-- Rodapé de Envio -->
    <div class="col-12">
        <div class="modulo-actions">
            <div class="d-flex align-items-center justify-content-end gap-2">
                <a href="{{ route('evento-funcionarios.index') }}" class="btn btn-outline-secondary">
                    <i class="ri-close-line align-middle me-1"></i> Cancelar
                </a>
                <button type="submit" class="btn btn-success px-4" id="btn-store">
                    <i class="ri-save-line align-middle me-1"></i> Salvar Evento
                </button>
            </div>
        </div>
    </div>

</div>