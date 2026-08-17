@extends('layouts.app', ['title' => 'Chamado #' . $item->id . ' - ' . $item->assunto])

@section('css')
<style type="text/css">
    /* Estilos Personalizados para a Página */
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

    /* Cabeçalho de Gradiente Premium */
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

    /* Balões de Mensagem do Chat */
    .chat-message-box {
        border-radius: 14px;
        padding: 16px 20px;
        margin-bottom: 18px;
        position: relative;
        border: 1px solid #f1f5f9;
    }

    .chat-admin {
        background-color: #f8fafc;
        border-left: 4px solid #4f46e5 !important;
    }

    .chat-client {
        background-color: #ffffff;
        border-left: 4px solid #10b981 !important;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
    }

    .avatar-chat {
        width: 44px;
        height: 44px;
        border-radius: 12px;
        object-fit: cover;
    }

    /* Badges */
    .badge {
        padding: 6px 12px !important;
        border-radius: 9999px !important;
        font-size: 11px !important;
        font-weight: 600 !important;
        display: inline-flex;
        align-items: center;
        gap: 4px;
        box-shadow: none !important;
        border: 1px solid transparent;
    }

    .bg-success-subtle {
        background-color: #ecfdf5 !important;
        color: #047857 !important;
        border-color: #a7f3d0 !important;
    }

    .bg-danger-subtle {
        background-color: #fef2f2 !important;
        color: #b91c1c !important;
        border-color: #fecaca !important;
    }

    .bg-warning-subtle {
        background-color: #fffbeb !important;
        color: #b45309 !important;
        border-color: #fef3c7 !important;
    }

    .bg-primary-subtle {
        background-color: #eef2ff !important;
        color: #4338ca !important;
        border-color: #c7d2fe !important;
    }

    .bg-dark-subtle {
        background-color: #f1f5f9 !important;
        color: #334155 !important;
        border-color: #cbd5e1 !important;
    }

    /* Formulários e Botões */
    .form-control, select {
        border: 1px solid #e2e8f0 !important;
        border-radius: 10px !important;
        padding: 10px 14px !important;
        font-size: 13px !important;
        color: #334155 !important;
    }

    .btn {
        border-radius: 10px !important;
        font-weight: 500 !important;
        font-size: 13px !important;
        padding: 10px 20px !important;
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

    .btn-success {
        background-color: #10b981 !important;
        border-color: #10b981 !important;
        color: #fff !important;
    }

    .info-item {
        padding: 10px 0;
        border-bottom: 1px solid #f1f5f9;
    }

    .info-item:last-child {
        border-bottom: none;
    }
</style>
@endsection

@section('content')
<div class="mt-3 text-dark">
    <div class="row">
        <div class="card border-0 shadow-sm">

            <!-- ═══ CABEÇALHO PREMIUM ═══ -->
            <div class="card-header modulo-header-gradient">
                <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                    <div>
                        <h4 class="modulo-title text-white">
                            <i class="ri-customer-service-2-line"></i>
                            Chamado #{{ $item->id }} — {{ $item->assunto }}
                        </h4>
                        <p class="modulo-subtitle">
                            Empresa: <strong>{{ $item->empresa->nome }}</strong> &nbsp;|&nbsp; Departamento: <strong>{{ ucfirst($item->departamento) }}</strong>
                        </p>
                    </div>
                    <div>
                        <a href="{{ route('ticket-super.index') }}" class="btn btn-light btn-sm text-dark">
                            <i class="ri-arrow-left-line me-1"></i> Voltar
                        </a>
                    </div>
                </div>
            </div>

            <div class="card-body">
                <div class="row g-4">

                    <!-- ═══ COLUNA DE MENSAGENS (ESQUERDA) ═══ -->
                    <div class="col-lg-8 col-12">
                        <h5 class="fw-bold mb-3 text-dark">
                            <i class="ri-discuss-line me-1 text-primary"></i> Histórico de Mensagens
                        </h5>

                        <div class="chat-thread">
                            @foreach($item->mensagens as $m)
                            <div class="chat-message-box {{ $m->resposta ? 'chat-admin' : 'chat-client' }}">
                                <div class="d-flex align-items-center justify-content-between mb-2">
                                    <div class="d-flex align-items-center gap-3">
                                        @if($m->resposta)
                                            <div class="avatar-xs">
                                                <span class="avatar-title bg-primary text-white rounded-circle fs-14 fw-bold">
                                                    <i class="ri-headphone-line"></i>
                                                </span>
                                            </div>
                                            <div>
                                                <strong class="text-primary fs-14">{{ env("APP_NAME", "Suporte do Sistema") }}</strong>
                                                <span class="badge bg-primary-subtle ms-2">Equipe de Suporte</span>
                                            </div>
                                        @else
                                            <div class="avatar-xs">
                                                <span class="avatar-title bg-success text-white rounded-circle fs-14 fw-bold">
                                                    <i class="ri-building-line"></i>
                                                </span>
                                            </div>
                                            <div>
                                                <strong class="text-dark fs-14">{{ $item->empresa->nome }}</strong>
                                                <span class="badge bg-success-subtle ms-2">Cliente / Solicitante</span>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="d-flex align-items-center gap-2">
                                        <span class="text-muted fs-11">
                                            <i class="ri-time-line me-1"></i>{{ __data_pt($m->created_at, 1) }}
                                        </span>
                                        @if($item->status != 'resolvido')
                                        <form action="{{ route('ticket-super.destroy-mensagem', $m->id) }}" method="post" id="form-{{$m->id}}" class="m-0">
                                            @method('delete')
                                            @csrf
                                            <button type="button" class="btn btn-outline-danger btn-sm py-0 px-1 btn-delete" title="Excluir Mensagem">
                                                <i class="ri-delete-bin-line"></i>
                                            </button>
                                        </form>
                                        @endif
                                    </div>
                                </div>

                                <div class="chat-content text-dark fs-13 mt-2">
                                    {!! $m->descricao !!}
                                </div>

                                @if($m->anexos && count($m->anexos) > 0)
                                <div class="mt-3 pt-2 border-top">
                                    <span class="text-muted fs-11 d-block mb-1"><i class="ri-attachment-line me-1"></i> Anexos:</span>
                                    <div class="d-flex flex-wrap gap-2">
                                        @foreach($m->anexos as $key => $f)
                                        <a target="_blank" href="{{ $f->file }}" class="btn btn-light btn-sm text-primary border">
                                            <i class="ri-file-download-line me-1"></i> Anexo {{ $key + 1 }}
                                        </a>
                                        @endforeach
                                    </div>
                                </div>
                                @endif
                            </div>
                            @endforeach
                        </div>

                        <!-- ═══ FORMULÁRIO DE RESPOSTA ═══ -->
                        @if($item->status != 'resolvido')
                        <div class="card border bg-light mt-4 shadow-none">
                            <div class="card-body p-3">
                                <h6 class="fw-bold mb-3 text-dark">
                                    <i class="ri-reply-line me-1 text-success"></i> Responder a este Chamado
                                </h6>
                                <form method="post" action="{{ route('ticket-super.add-mensagem', [$item->id]) }}" enctype="multipart/form-data">
                                    @csrf
                                    @method('put')

                                    <div class="mb-3">
                                        {!!Form::textarea('descricao', '')
                                        ->attrs(['rows' => '6', 'class' => 'tiny form-control'])
                                        !!}
                                    </div>

                                    <div class="row align-items-center">
                                        <div class="col-md-7 col-12 mb-2 mb-md-0">
                                            <label class="form-label fs-12 mb-1"><i class="ri-attachment-line me-1"></i> Anexar Arquivos (Opcional)</label>
                                            {!!Form::file('anexos[]', '')->attrs(['multiple' => 'true', 'class' => 'form-control form-control-sm'])!!}
                                        </div>
                                        <div class="col-md-5 col-12 text-end">
                                            <button type="submit" class="btn btn-success px-4" id="btn-reply">
                                                <i class="ri-send-plane-2-line me-1"></i> Enviar Resposta
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                        @else
                        <div class="alert alert-success mt-4 d-flex align-items-center gap-2" role="alert">
                            <i class="ri-checkbox-circle-line fs-20"></i>
                            <div>Este chamado está marcado como <strong>Resolvido</strong>. Para enviar novas respostas, altere o status para "Aberto" ou "Aguardando".</div>
                        </div>
                        @endif
                    </div>

                    <!-- ═══ COLUNA DE INFORMAÇÕES DO TICKET (DIREITA) ═══ -->
                    <div class="col-lg-4 col-12">
                        <div class="card border shadow-none bg-light mb-3">
                            <div class="card-body p-3">
                                <h6 class="fw-bold text-dark mb-3 border-bottom pb-2">
                                    <i class="ri-information-line me-1 text-primary"></i> Detalhes do Chamado
                                </h6>

                                <div class="info-item d-flex justify-content-between align-items-center">
                                    <span class="text-muted fs-12">Protocolo:</span>
                                    <span class="fw-bold fs-13 font-monospace">#{{ $item->id }}</span>
                                </div>

                                <div class="info-item d-flex justify-content-between align-items-center">
                                    <span class="text-muted fs-12">Status Atual:</span>
                                    <div>
                                        @if($item->status == 'aberto')
                                            <span class="badge bg-dark-subtle">
                                                <i class="ri-record-circle-line me-1"></i> Aberto
                                            </span>
                                        @elseif($item->status == 'respondida')
                                            <span class="badge bg-warning-subtle">
                                                <i class="ri-reply-line me-1"></i> Respondida
                                            </span>
                                        @elseif($item->status == 'aguardando')
                                            <span class="badge bg-danger-subtle">
                                                <i class="ri-time-line me-1"></i> Aguardando
                                            </span>
                                        @else
                                            <span class="badge bg-success-subtle">
                                                <i class="ri-checkbox-circle-line me-1"></i> Resolvido
                                            </span>
                                        @endif
                                    </div>
                                </div>

                                <div class="info-item d-flex justify-content-between align-items-center">
                                    <span class="text-muted fs-12">Departamento:</span>
                                    <span class="badge bg-primary-subtle">{{ ucfirst($item->departamento) }}</span>
                                </div>

                                <div class="info-item d-flex justify-content-between align-items-center">
                                    <span class="text-muted fs-12">Empresa:</span>
                                    <strong class="text-dark fs-12">{{ $item->empresa->nome }}</strong>
                                </div>

                                <div class="info-item d-flex justify-content-between align-items-center">
                                    <span class="text-muted fs-12">Data de Abertura:</span>
                                    <span class="text-dark fs-12">{{ __data_pt($item->created_at, 1) }}</span>
                                </div>

                                <div class="info-item d-flex justify-content-between align-items-center">
                                    <span class="text-muted fs-12">Última Atualização:</span>
                                    <span class="text-dark fs-12">{{ __data_pt($item->updated_at, 1) }}</span>
                                </div>

                                <!-- ═══ FORMULÁRIO DE ALTERAÇÃO DE STATUS ═══ -->
                                <div class="mt-4 pt-3 border-top">
                                    <label class="form-label fw-bold text-dark fs-12 mb-2">
                                        <i class="ri-refresh-line me-1"></i> Alterar Status do Chamado
                                    </label>
                                    {!!Form::open()->fill($item)
                                    ->put()
                                    ->route('ticket-super.update-status', [$item->id])
                                    !!}
                                    <div class="row g-2">
                                        <div class="col-7">
                                            {!!Form::select('status', '', ['aberto' => 'Aberto', 'respondida' => 'Respondida', 'aguardando' => 'Aguardando', 'resolvido' => 'Resolvido'])
                                            ->attrs(['class' => 'form-select form-select-sm'])->required()
                                            !!}
                                        </div>
                                        <div class="col-5">
                                            <button type="submit" class="btn btn-primary btn-sm w-100">
                                                Salvar Status
                                            </button>
                                        </div>
                                    </div>
                                    {!!Form::close()!!}
                                </div>

                            </div>
                        </div>
                    </div>

                </div>
            </div>

        </div>
    </div>
</div>
@endsection

@section('js')
<script src="/tinymce/tinymce.min.js"></script>
<script type="text/javascript">
    $(function(){
        tinymce.init({ 
            selector: 'textarea.tiny', 
            language: 'pt_BR',
            height: 200,
            menubar: false,
            plugins: 'lists link code',
            toolbar: 'undo redo | bold italic underline | bullist numlist | link code'
        });

        setTimeout(() => {
            $('.tox-promotion, .tox-statusbar__right-container').addClass('d-none');
        }, 500);
    });
</script>
@endsection
