@extends('layouts.app', ['title' => 'Visualizar Produto'])

@section('content')
<div class="mt-3">
    <div class="row justify-content-center">
        <div class="col-12">
            <div class="card border-0 shadow-sm modulo-form-card">

                {{-- ═══ CABEÇALHO ═══ --}}
                <div class="card-header modulo-header-gradient py-3 px-4">
                    <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                        <div>
                            <h4 class="mb-1 modulo-title d-flex align-items-center gap-2">
                                <i class="ri-box-3-line"></i>
                                Visualizar Produto: <strong>{{ $item->nome }}</strong>
                            </h4>
                            <p class="text-muted mb-0 modulo-subtitle fs-13">
                                Consulta de parâmetros fiscais, tributação, variações e estoque cadastrado.
                            </p>
                        </div>
                        <div>
                            <a href="{{ route('contador-empresa.produtos') }}" class="dash-btn dash-btn-light">
                                <i class="ri-arrow-left-line"></i> Voltar
                            </a>
                        </div>
                    </div>
                </div>

                {{-- ═══ CORPO DO FORMULÁRIO (SOMENTE LEITURA) ═══ --}}
                <div class="card-body p-4">
                    {!!Form::open()->fill($item)
                    ->post()
                    ->route('produtos.store')
                    ->multipart()
                    !!}

                    <div class="pl-lg-4">
                        @include('produtos._forms', ['not_submit' => 1])
                    </div>

                    {!!Form::close()!!}
                </div>

            </div>
        </div>
    </div>
</div>
@endsection

@section('js')
<script type="text/javascript" src="/js/produto.js"></script>
<script type="text/javascript">
    $(function(){
        $('input, select, textarea').each(function(){
            $(this).attr('disabled', 1)
        })
    })
</script>
<script src="/assets/vendor/twitter-bootstrap-wizard/jquery.bootstrap.wizard.min.js"></script>
<script src="/assets/js/pages/demo.form-wizard.js"></script>
@endsection
