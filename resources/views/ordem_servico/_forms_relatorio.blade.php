<input type="hidden" value="{{$ordem->id}}" name="ordem_servico_id">
<div class="row g-3 text-dark">
    <div class="col-12">
        {!! Form::textarea('texto', 'Descrição Detalhada do Relatório')->required()->attrs(['class' => 'form-control', 'rows' => '6', 'placeholder' => 'Informe as ações tomadas, testes efetuados ou laudo deste período...']) !!}
    </div>
    
    <div class="col-12 mt-4">
        <hr class="text-muted opacity-25">
        <div class="d-flex align-items-center justify-content-end gap-2">
            <a href="{{ route('ordem-servico.show', $ordem->id) }}" class="btn btn-light px-4">Cancelar</a>
            <button type="submit" class="btn btn-success px-4">
                <i class="ri-save-line align-middle me-1"></i> Salvar Relatório
            </button>
        </div>
    </div>
</div>
