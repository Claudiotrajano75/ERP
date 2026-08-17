<div class="row g-3">
    <div class="col-md-3 col-12">
        <label class="form-label required"><i class="ri-map-pin-line me-1"></i> Estado (UF)</label>
        {!!Form::select('uf', '', ['' => 'Selecione o Estado'] + \App\Models\Cidade::estados())
        ->attrs(['class' => 'form-select select2', 'id' => 'inp-uf'])
        ->required()
        !!}
    </div>

    <div class="col-md-3 col-12">
        <label class="form-label required"><i class="ri-git-branch-line me-1"></i> Versão da Tabela</label>
        {!!Form::text('versao', '')
        ->required()
        ->attrs(['class' => 'form-control', 'id' => 'inp-versao', 'placeholder' => 'Ex: 24.1.A'])
        !!}
    </div>

    <div class="col-md-6 col-12">
        <label class="form-label required"><i class="ri-file-excel-2-line me-1"></i> Arquivo CSV (Tabela IBPT)</label>
        {!!Form::file('file', '')
        ->required()
        ->attrs(['accept' => '.csv', 'class' => 'form-control', 'id' => 'inp-file'])
        !!}
        <small class="text-muted fs-11">Selecione o arquivo .CSV oficial baixado do portal IBPT/De Olho No Imposto.</small>
    </div>
</div>

<div class="modulo-actions mt-4">
    <div class="d-flex gap-2 justify-content-end align-items-center">
        <a href="{{ route('ibpt.index') }}" class="btn btn-outline-secondary">
            <i class="ri-close-line me-1"></i> Cancelar
        </a>
        <button type="submit" class="btn btn-success px-4" id="btn-store">
            <span class="spinner-grow spinner-grow-sm d-none me-1" role="status" aria-hidden="true"></span>
            <i class="ri-upload-cloud-2-line me-1"></i> Iniciar Importação
        </button>
    </div>
</div>

@section('js')
<script type="text/javascript">
    $('#btn-store').click(() => {
        setTimeout(() => {
            if($('#inp-versao').val() && $('#inp-uf').val() && $('#inp-file').val()){
                $('.spinner-grow').removeClass('d-none')
                $('#btn-store').attr('disabled', true).text('Processando importação...')
                $('form').submit()
            }
        }, 100)
    })
</script>
@endsection
