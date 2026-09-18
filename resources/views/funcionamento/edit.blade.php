@extends('layouts.app', ['title' => 'Editar Horário de Funcionamento'])

@section('content')
<div class="mt-3">
    <div class="row">
        <div class="card border-0 shadow-sm">

            <div class="card-header modulo-header-gradient py-3 px-4">
                <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                    <div>
                        <h4 class="mb-1 modulo-title d-flex align-items-center gap-2">
                            <i class="ri-edit-circle-line"></i>
                            Editar Horário de Funcionamento
                        </h4>
                        <p class="text-muted mb-0 modulo-subtitle fs-13">Altere a grade horária de expediente do colaborador selecionado.</p>
                    </div>
                    <div class="d-flex gap-2 flex-wrap">
                        <a href="{{ route('funcionamentos.index') }}" class="dash-btn dash-btn-light">
                            <i class="ri-arrow-left-line"></i> Voltar
                        </a>
                    </div>
                </div>
            </div>

            <div class="card-body p-4">
                {!!Form::open()->fill($item)
                ->put()
                ->route('funcionamentos.update', [$item->id])
                !!}
                @include('funcionamento._forms', ['formType' => 'edit'])
                {!!Form::close()!!}
            </div>

        </div>
    </div>
</div>
@endsection

@section('js')
<script type="text/javascript">
    $(function() {
        // Busca dinâmica de dias vinculados
        $(document).on("change", "#inp-funcionario_id", function () {
            let val = $(this).val();
            if(val) {
                $.get(path_url + "api/funcionamentos/diasDoFuncionario", { funcionario_id: val })
                .done((success) => {
                    $('#table-horarios tbody').html(success);
                })
                .fail((err) => {
                    console.log(err);
                    swal("Erro", "Erro ao buscar os dias de expediente deste funcionário.", "error");
                });
            } else {
                $('#table-horarios tbody').html('');
            }
        });
    });
</script>
@endsection

