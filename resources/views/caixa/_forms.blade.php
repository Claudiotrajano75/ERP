@if($item != null)
<div class="row">
    <div class="col-12 text-center py-3">
        <div class="avatar-md bg-success-subtle text-success mx-auto rounded-circle mb-2 d-flex align-items-center justify-content-center" style="width: 50px; height: 50px; font-size: 24px;">
            <i class="ri-checkbox-circle-line"></i>
        </div>
        <h4 class="text-success mb-1">Caixa Aberto!</h4>
        @if($item->contaEmpresa)
        <p class="text-muted mb-0">Este operador já possui um caixa ativo associado à conta: <strong>{{ $item->contaEmpresa->nome }}</strong></p>
        @endif
    </div>
</div>
@else
<div class="row g-3">
    @if(__countLocalAtivo() > 1)
        <!-- Caso exista mais de um local ativo -->
        <div class="col-md-4">
            {!!Form::text('valor_abertura', 'Valor de abertura')
            ->attrs(['class' => 'moeda form-control'])
            ->required()
            !!}
        </div>

        <div class="col-md-4">
            <div class="form-group div-conta-empresa">
                {!!Form::select('conta_empresa_id', 'Conta empresa')
                ->required()
                !!}
            </div>
        </div>

        <div class="col-md-4">
            <div class="form-group">
                <label class="form-label">Local</label>
                <select id="inp-local_id" required class="form-select select2 class-required" data-toggle="select2" name="local_id">
                    <option value="">Selecione</option>
                    @foreach(__getLocaisAtivoUsuario() as $local)
                    <option @isset($item) @if($item->local_id == $local->id) selected @endif @endif value="{{ $local->id }}">{{ $local->descricao }}</option>
                    @endforeach
                </select>
            </div>
        </div>
    @else
        <!-- Caso exista apenas um local ativo (envia oculto) -->
        <input id="inp-local_id" type="hidden" value="{{ __getLocalAtivo() ? __getLocalAtivo()->id : '' }}" name="local_id">
        
        <div class="col-md-4">
            {!!Form::text('valor_abertura', 'Valor de abertura')
            ->attrs(['class' => 'moeda form-control'])
            ->required()
            !!}
        </div>

        <div class="col-md-8">
            <div class="form-group div-conta-empresa">
                {!!Form::select('conta_empresa_id', 'Conta empresa')
                ->required()
                !!}
            </div>
        </div>
    @endif

    <div class="col-md-12">
        {!!Form::text('observacao', 'Observação')
        ->attrs(['class' => 'form-control'])
        !!}
    </div>
    
    <div class="col-12 mt-4">
        <hr class="text-muted opacity-25">
        <div class="d-flex align-items-center justify-content-end gap-2">
            <a href="{{ route('caixa.index') }}" class="btn btn-light px-4">Cancelar</a>
            <button type="submit" class="btn btn-success px-4" id="btn-store">
                <i class="ri-check-line align-middle me-1"></i> Abrir Caixa
            </button>
        </div>
    </div>
</div>
@endif
