<div class="row g-3 text-dark">
    <!-- Linha 1 -->
    <div class="col-md-6 col-12">
        {!!Form::text('nome', 'Nome da Conta')
        ->attrs(['placeholder' => 'Ex: Banco do Brasil - Principal'])
        ->required()
        !!}
    </div>
    
    <div class="col-md-3 col-6">
        {!!Form::text('banco', 'Banco')
        ->attrs(['placeholder' => 'Ex: Banco do Brasil'])
        !!}
    </div>

    <div class="col-md-3 col-6">
        {!!Form::select('status', 'Status', [1 => 'Ativa', 0 => 'Desativada'])
        ->attrs(['class' => 'form-select'])
        ->required()
        !!}
    </div>

    <!-- Linha 2 -->
    <div class="col-md-4 col-6">
        {!!Form::text('agencia', 'Agência')
        ->attrs(['placeholder' => 'Ex: 1234-5'])
        !!}
    </div>
    
    <div class="col-md-4 col-6">
        {!!Form::text('conta', 'Conta Corrente')
        ->attrs(['placeholder' => 'Ex: 123456-7'])
        !!}
    </div>

    @if(!isset($item))
    <div class="col-md-4 col-12">
        {!!Form::tel('saldo_inicial', 'Saldo Inicial')
        ->attrs(['class' => 'moeda form-control'])
        ->required()
        !!}
    </div>
    @endif

    @if(__isAdmin() && isset($item))
    <div class="col-md-4 col-12">
        {!!Form::tel('saldo', 'Saldo Atual')
        ->attrs(['class' => 'moeda form-control'])
        ->value(__moeda($item->saldo))
        ->required()
        !!}
    </div>
    @endif

    <!-- Linha 3 -->
    @php
        $planoCol = __countLocalAtivo() > 1 ? 'col-md-8' : 'col-md-12';
    @endphp
    
    <div class="{{ $planoCol }} col-12">
        {!!Form::select('plano_conta_id', 'Plano de Conta')
        ->attrs(['class' => 'form-select'])
        ->required()
        ->options(isset($item) ? [$item->plano_conta_id => $item->plano->descricao] : [])
        !!}
    </div>

    @if(__countLocalAtivo() > 1)
    <div class="col-md-4 col-12">
        <label for="inp-local_id" class="form-label fw-semibold">Local da Empresa</label>
        <select id="inp-local_id" required class="select2 form-select class-required" data-toggle="select2" name="local_id">
            <option value="">Selecione</option>
            @foreach(__getLocaisAtivoUsuario() as $local)
            <option @isset($item) @if($item->local_id == $local->id) selected @endif @endif value="{{ $local->id }}">{{ $local->descricao }}</option>
            @endforeach
        </select>
    </div>
    @else
    <input id="inp-local_id" type="hidden" value="{{ __getLocalAtivo() ? __getLocalAtivo()->id : '' }}" name="local_id">
    @endif

    <!-- Rodapé -->
    <div class="col-12 mt-4">
        <hr class="text-muted opacity-25">
        <div class="d-flex align-items-center justify-content-end gap-2">
            <a href="{{ route('contas-empresa.index') }}" class="btn btn-light px-4">Cancelar</a>
            <button type="submit" class="btn btn-success px-4" id="btn-store">
                <i class="ri-save-line align-middle me-1"></i> Salvar Conta
            </button>
        </div>
    </div>
</div>