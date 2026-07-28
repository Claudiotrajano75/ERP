@extends('layouts.app', ['title' => 'TEF Registros'])

@section('css')
<style>
/* ─── Header Gradiente ─── */
.modulo-header-gradient { background: linear-gradient(135deg, #0f0c29 0%, #302b63 50%, #24243e 100%); border-radius: 12px 12px 0 0 !important; border-bottom: none !important; }
.modulo-header-gradient .modulo-title { color: #fff; font-weight: 700; letter-spacing: -0.3px; }
.modulo-header-gradient .modulo-title i { background: rgba(255,255,255,0.12); padding: 8px; border-radius: 10px; color: #a8b5ff; }
.modulo-header-gradient .modulo-subtitle { color: rgba(255,255,255,0.6) !important; font-weight: 400; }
.modulo-header-gradient .btn { border-radius: 8px; font-weight: 600; transition: all 0.2s ease; }
.modulo-header-gradient .btn:hover { transform: translateY(-1px); box-shadow: 0 4px 14px rgba(0,0,0,0.25); }

/* ─── Glass Filters ─── */
.modulo-glass-filter { background: rgba(255,255,255,0.7); backdrop-filter: blur(12px); -webkit-backdrop-filter: blur(12px); border: 1px solid rgba(255,255,255,0.8) !important; border-radius: 12px; box-shadow: 0 2px 20px rgba(0,0,0,0.04); }
.modulo-glass-filter label { font-weight: 600; font-size: 11px; text-transform: uppercase; letter-spacing: 0.4px; color: #5a5a7a; margin-bottom: 2px; }
.modulo-glass-filter .form-control, .modulo-glass-filter .form-select { height: 38px; } .modulo-glass-filter .btn { border-radius: 8px; font-weight: 600; font-size: 13px; height: 38px; padding-top: 0; padding-bottom: 0; display: inline-flex; align-items: center; justify-content: center; transition: all 0.2s; }
.modulo-glass-filter .btn:hover { transform: translateY(-1px); }

/* ─── Premium Table ─── */
.modulo-table-wrap { border-radius: 12px; border: 1px solid #eef0f5; overflow: hidden; }
.modulo-table-wrap table { margin-bottom: 0; }
.modulo-table-wrap thead th { background: #f8f9fc; color: #5a5a7a; font-weight: 700; font-size: 11px; text-transform: uppercase; letter-spacing: 0.4px; padding: 12px 14px; border-bottom: 2px solid #e8eaf6; }
.modulo-table-wrap tbody td { padding: 12px 14px; vertical-align: middle; border-bottom: 1px solid #f0f2f8; transition: background 0.15s ease; font-size: 13px; }
.modulo-table-wrap tbody tr { transition: all 0.15s ease; }
.modulo-table-wrap tbody tr:hover { background: #f5f6fe; }
.modulo-table-wrap tbody tr:last-child td { border-bottom: none; }

/* ─── Empty State ─── */
.modulo-empty { padding: 48px 20px; text-align: center; }
.modulo-empty i { font-size: 48px; color: #c5cae9; margin-bottom: 12px; display: block; }
.modulo-empty p { color: #9e9eb8; font-size: 14px; margin: 0; }

/* ─── Botões de Ação do Formulário / Grid ─── */
.modulo-action-group { display: flex; align-items: center; justify-content: flex-end; gap: 4px; flex-wrap: nowrap !important; }
.modulo-action-group .btn { padding: 5px 8px; font-size: 12px; border-radius: 6px; }
</style>
@endsection

@section('content')
<div class="mt-3 text-dark">
    <div class="row">
        <div class="card border-0 shadow-sm text-dark modulo-form-card">

            <!-- CABEÇALHO PREMIUM -->
            <div class="card-header modulo-header-gradient py-3 px-4">
                <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                    <div>
                        <h4 class="mb-1 modulo-title d-flex align-items-center gap-2">
                            <i class="ri-file-list-3-line"></i>
                            Registros de TEF
                        </h4>
                        <p class="text-muted mb-0 modulo-subtitle fs-13">Visualize e gerencie as transações realizadas via TEF (Transferência Eletrônica de Fundos).</p>
                    </div>
                </div>
            </div>

            <div class="card-body p-4">

                <!-- FILTROS GLASS -->
                <div class="modulo-glass-filter p-3 mb-4">
                    {!!Form::open()->fill(request()->all())->get()!!}
                    <div class="row g-2 align-items-end">
                        <div class="col-md-4 col-12">
                            {!!Form::select('usuario_id', 'Pesquisar por usuário', ['' => 'Selecione'] + $usuarios->pluck('name', 'id')->all())
                            ->attrs(['class' => 'select2 form-select'])
                            !!}
                        </div>
                        <div class="col-md-2 col-12 ms-auto">
                            <div class="d-flex gap-2">
                                <button class="btn btn-primary btn-sm flex-grow-1" type="submit">
                                    <i class="ri-search-line me-1"></i> Pesquisar
                                </button>
                                <a class="btn btn-danger btn-sm px-3" href="{{ route('tef-registros.index') }}">
                                    <i class="ri-eraser-line me-1"></i> Limpar
                                </a>
                            </div>
                        </div>
                    </div>
                    {!!Form::close()!!}
                </div>

                <!-- TABELA PREMIUM -->
                <div class="modulo-table-wrap mb-4">
                    <div class="table-responsive">
                        <table class="table table-centered table-hover align-middle mb-0 text-dark">
                            <thead>
                                <tr>
                                    <th>Data</th>
                                    <th>Hora</th>
                                    <th>Venda #</th>
                                    <th>Valor (R$)</th>
                                    <th>NSU</th>
                                    <th class="text-end">Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($data as $item)
                                <tr>
                                    <td class="fs-12">{{ $item->getDataTransacao() }}</td>
                                    <td class="fs-12">{{ $item->getHoraTransacao() }}</td>
                                    <td class="fw-bold">{{ $item->nfce_id }}</td>
                                    <td class="fw-bold text-success">R$ {{ __moeda($item->valor_total/100) }}</td>
                                    <td class="text-muted fs-12">{{ $item->nsu }}</td>
                                    <td class="text-end">
                                        <div class="modulo-action-group">
                                            <button type="button" class="btn btn-danger btn-sm" onclick="cancelar('{{ $item->id }}')" title="Cancelar/Estornar">
                                                <i class="ri-close-line"></i>
                                            </button>
                                            <button type="button" class="btn btn-dark btn-sm text-white" onclick="imprimir('{{ $item->id }}')" title="Imprimir">
                                                <i class="ri-printer-line"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6">
                                        <div class="modulo-empty">
                                            <i class="ri-inbox-2-line"></i>
                                            <p>Nenhum registro de TEF encontrado.</p>
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="d-flex justify-content-end mt-4">
                    {!! $data->appends(request()->all())->links() !!}
                </div>

            </div>
        </div>
    </div>
</div>
@include('modals._tef_consulta')

@endsection

@section('js')
<script type="text/javascript">
    function imprimir(id){
        $.post(path_url + "api/tef/imprimir",
        {
            id: id,
        })
        .done((data) => {
            console.log(data)
            if(data != 'Pendente'){
                window.open('/tef_comprovante/comprovante.pdf')
            }else if(data != 'Pendente'){
                swal("Alerta", "Comprovante pendente aguarde!", "warning")
            }else{
                swal("Erro", "Algo deu errado!", "error")
            }
        })
        .fail((e) => {
            console.log(e)
            swal("Erro", "Algo deu errado!", "error")
        });
    }

    function cancelar(id){

        swal({
            title: "Você está certo?",
            text: "Você está prestes a estornar o valor da venda!",
            icon: "warning",
            buttons: true,
            buttons: ["Cancelar", "Estornar"],
            dangerMode: true,
        }).then((isConfirm) => {
            if (isConfirm) {

                $.post(path_url + "api/tef/cancelar",
                {
                    id: id,
                })
                .done((data) => {
                    console.log(data)
                    consultaCancelamento(data)
                })
                .fail((e) => {
                    console.log(e)
                });
            } else {
                swal("", "Este item está salvo!", "info");
            }
        });
    }

    function consultaCancelamento(hash){
        $('#modal_tef_consulta').modal('show')
        $('.status-tef').text('Processando')
        $('.loading-tef').removeClass('d-none')

        let data = {
            hash: hash,
            usuario_id: $('#usuario_id').val(),
            empresa_id: $('#empresa_id').val()
        }
        $('.modal-loading').remove()
        let intervalo = null;
        intervalo = setInterval(() => {
            $.post(path_url + 'api/tef/consulta-cancelamento', data)
            .done((success) => {
                console.log(success)
                
            })
            .fail((err) => {
                console.log(err)
                clearInterval(intervalo)
                $('.status-tef').text(err.responseJSON)
                setTimeout(() => {
                    $('#modal_tef_consulta').modal('hide')
                }, 2000)
            })
        }, 3000)
    }
</script>
@endsection
