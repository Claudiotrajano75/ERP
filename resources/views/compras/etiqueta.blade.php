@extends('layouts.app', ['title' => 'Gerar Etiqueta'])

@section('css')
<style>
/* ─── Padrão Oficial ERP Layout Modernization ─── */
.card {
    border: 1px solid rgba(0, 0, 0, 0.06) !important;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.02) !important;
    border-radius: 16px !important;
    overflow: hidden;
    background: #fff;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1) !important;
    margin-bottom: 24px;
}

.card-body {
    padding: 24px !important;
}

/* ─── Cabeçalho de Gradiente Premium ─── */
.modulo-header-gradient {
    background: linear-gradient(135deg, #0f0c29 0%, #302b63 50%, #24243e 100%) !important;
    border-radius: 12px 12px 0 0 !important;
    border-bottom: none !important;
    padding: 20px 24px !important;
}

.modulo-header-gradient .modulo-title {
    color: #fff !important;
    font-weight: 700 !important;
    letter-spacing: -0.3px !important;
    margin: 0 !important;
    display: flex !important;
    align-items: center !important;
    gap: 12px !important;
}

.modulo-header-gradient .modulo-title i {
    background: rgba(255, 255, 255, 0.1) !important;
    padding: 8px !important;
    border-radius: 10px !important;
    color: #a8b5ff !important;
    font-size: 20px !important;
    display: inline-flex !important;
    align-items: center !important;
    justify-content: center !important;
}

.modulo-header-gradient .modulo-subtitle {
    color: rgba(255, 255, 255, 0.6) !important;
    font-weight: 400 !important;
    font-size: 13px !important;
    margin-top: 4px !important;
    margin-bottom: 0 !important;
}

/* ─── Formulários e Inputs ─── */
.form-control,
.form-select,
select,
input[type="text"],
input[type="tel"],
input[type="password"] {
    border: 1px solid #e2e8f0 !important;
    border-radius: 10px !important;
    padding: 10px 14px !important;
    font-size: 13px !important;
    color: #334155 !important;
    transition: all 0.2s ease !important;
    box-shadow: none !important;
    background-color: #ffffff !important;
}

.form-control:focus,
.form-select:focus,
select:focus {
    border-color: #4f46e5 !important;
    box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.1) !important;
}

.form-label,
label {
    font-weight: 600 !important;
    color: #475569 !important;
    font-size: 13px !important;
    margin-bottom: 6px !important;
}

/* ─── Botões Padrão ─── */
.btn {
    border-radius: 10px !important;
    font-weight: 500 !important;
    font-size: 13px !important;
    padding: 10px 20px !important;
    transition: all 0.2s ease !important;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 6px;
}

.btn-sm {
    padding: 6px 12px !important;
    font-size: 12px !important;
    border-radius: 8px !important;
}

.btn-primary {
    background-color: #4f46e5 !important;
    border-color: #4f46e5 !important;
    color: #fff !important;
}

.btn-primary:hover {
    background-color: #4338ca !important;
    border-color: #4338ca !important;
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(79, 70, 229, 0.2) !important;
}

.btn-success {
    background-color: #10b981 !important;
    border-color: #10b981 !important;
    color: #fff !important;
}

.btn-success:hover {
    background-color: #059669 !important;
    border-color: #059669 !important;
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(16, 185, 129, 0.2) !important;
}

/* ─── Cards de Seção Interna ─── */
.card-secao-fiscal {
    border: 1px solid #eef2f6 !important;
    border-radius: 12px !important;
    box-shadow: 0 2px 6px rgba(0,0,0,0.02) !important;
    margin-bottom: 20px !important;
    background: #ffffff;
}

.card-secao-fiscal .card-header {
    background: #f8fafc;
    border-bottom: 1px solid #edf2f7;
    padding: 12px 20px;
    border-radius: 12px 12px 0 0 !important;
}

.card-secao-fiscal .card-header h5 {
    margin: 0;
    font-size: 14px;
    font-weight: 700;
    color: #1e293b;
    display: flex;
    align-items: center;
    gap: 8px;
}

.card-secao-fiscal .card-body {
    padding: 20px !important;
}

/* ─── Form Check Customizado ─── */
.form-check-input:checked {
    background-color: #4f46e5;
    border-color: #4f46e5;
}

.form-check-label {
    font-size: 12px !important;
    font-weight: 500;
}

/* ─── Responsivo ─── */
@media (max-width: 768px) {
    .modulo-header-gradient .modulo-title { font-size: 18px; }
}
</style>
@endsection

@section('content')
<div class="mt-3 text-dark">
    <div class="row justify-content-center">
        <div class="col-lg-12">
            <div class="card border-0 shadow-sm">
                
                <!-- CABEÇALHO PREMIUM -->
                <div class="card-header modulo-header-gradient py-3 px-4">
                    <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                        <div>
                            <h4 class="mb-1 modulo-title d-flex align-items-center gap-2">
                                <i class="ri-barcode-box-line"></i>
                                Gerar Etiquetas de Compra - <strong class="text-white">#{{ $item->numero_sequencial }}</strong>
                            </h4>
                            <p class="text-muted mb-0 modulo-subtitle fs-13">Configure o layout das etiquetas de código de barras ou preços para os produtos contidos na nota de compra.</p>
                        </div>
                        <div>
                            <a href="{{ route('compras.index') }}" class="btn btn-light btn-sm px-3 text-dark">
                                <i class="ri-arrow-left-line align-middle me-1"></i> Voltar
                            </a>
                        </div>
                    </div>
                </div>

                <!-- CORPO DO FORMULÁRIO -->
                <div class="card-body p-4">
                    {!!Form::open()
                    ->post()
                    ->route('compras.etiqueta-store', [$item->id])
                    !!}
                    
                    <div class="pl-lg-2">
                        @include('compras._forms_etiqueta')
                    </div>
                    
                    {!!Form::close()!!}
                </div>

            </div>
        </div>
    </div>
</div>
@endsection

@section('js')
<script type="text/javascript">
    $(function(){
        $('#inp-modelo_id').val('').change()
    })

    $('body').on('change', '#inp-modelo_id', function () {
        if($(this).val()){
            $.get(path_url + 'api/etiqueta', {modelo_id: $(this).val()})
            .done((res) => {
                $('#inp-tipo').val(res.tipo).change()
                $('#inp-altura').val(res.altura)
                $('#inp-largura').val(res.largura)
                $('#inp-etiquestas_por_linha').val(res.etiquestas_por_linha)
                $('#inp-distancia_etiquetas_lateral').val(res.distancia_etiquetas_lateral)
                $('#inp-distancia_etiquetas_topo').val(res.distancia_etiquetas_topo)
                $('#inp-quantidade_etiquetas').val(res.quantidade_etiquetas)
                $('#inp-tamanho_fonte').val(res.tamanho_fonte)
                $('#inp-tamanho_codigo_barras').val(res.tamanho_codigo_barras)

                $('#inp-nome_empresa').prop('checked', res.nome_empresa)
                $('#inp-nome_produto').prop('checked', res.nome_produto)
                $('#inp-valor_produto').prop('checked', res.valor_produto)
                $('#inp-codigo_produto').prop('checked', res.codigo_produto)
                $('#inp-codigo_barras_numerico').prop('checked', res.codigo_barras_numerico)
            })
            .fail((err) => {
                console.log(err)
            })
        }
    })
</script>
@endsection