@extends('layouts.app', ['title' => 'Chamado #' . $item->id . ' - ' . $item->assunto])

@section('css')
<style type="text/css">
    /* Estilos Gerais do Card */
    .card-modulo {
        border: 1px solid rgba(0, 0, 0, 0.06) !important;
        box-shadow: 0 4px 16px rgba(0, 0, 0, 0.03) !important;
        border-radius: 16px !important;
        overflow: hidden;
        background: #fff;
        margin-bottom: 24px;
    }

    /* Cabeçalho no padrão claro moderno */
    .modulo-header-gradient {
        background: linear-gradient(135deg, #f2f3ff 0%, #e6e9ff 100%) !important;
        border-bottom: 1px solid #e0e7ff !important;
        border-radius: 16px 16px 0 0 !important;
        padding: 20px 24px !important;
    }

    .modulo-header-gradient .modulo-title {
        color: #4338ca !important;
        font-weight: 700 !important;
        letter-spacing: -0.3px !important;
        margin: 0 !important;
        display: flex !important;
        align-items: center !important;
        gap: 12px !important;
        font-size: 1.25rem !important;
    }

    .modulo-header-gradient .modulo-title i {
        background: #4f46e5 !important;
        color: #ffffff !important;
        padding: 10px !important;
        border-radius: 12px !important;
        font-size: 20px !important;
        display: inline-flex !important;
        align-items: center !important;
        justify-content: center !important;
        box-shadow: 0 4px 10px rgba(79, 70, 229, 0.25) !important;
    }

    .modulo-header-gradient .modulo-subtitle {
        color: #64748b !important;
        font-weight: 500 !important;
        font-size: 13px !important;
        margin-top: 4px !important;
        margin-bottom: 0 !important;
    }

    .btn-voltar {
        background: #ffffff !important;
        border: 1px solid #e2e8f0 !important;
        color: #334155 !important;
        font-weight: 600 !important;
        font-size: 13px !important;
        padding: 8px 16px !important;
        border-radius: 10px !important;
        box-shadow: 0 1px 3px rgba(0,0,0,0.05);
        display: inline-flex;
        align-items: center;
        gap: 6px;
        text-decoration: none !important;
        transition: all 0.2s ease;
    }

    .btn-voltar:hover {
        background: #f8fafc !important;
        color: #0f172a !important;
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.08);
    }

    /* Balões de Mensagem do Chat (Padrão Super Admin) */
    .chat-message-box {
        border-radius: 14px;
        padding: 18px 20px;
        margin-bottom: 18px;
        position: relative;
        border: 1px solid #f1f5f9;
        transition: all 0.2s ease;
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

    .avatar-icon-circle {
        width: 38px;
        height: 38px;
        border-radius: 50%;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 16px;
        color: #fff;
        flex-shrink: 0;
    }
    .avatar-icon-client {
        background-color: #10b981;
    }
    .avatar-icon-admin {
        background-color: #4f46e5;
    }

    /* Badges */
    .badge-subtle {
        padding: 4px 10px !important;
        border-radius: 9999px !important;
        font-size: 11px !important;
        font-weight: 600 !important;
        display: inline-flex;
        align-items: center;
        gap: 4px;
        border: 1px solid transparent;
    }

    .bg-success-subtle {
        background-color: #ecfdf5 !important;
        color: #047857 !important;
        border-color: #a7f3d0 !important;
    }

    .bg-primary-subtle {
        background-color: #eef2ff !important;
        color: #4338ca !important;
        border-color: #c7d2fe !important;
    }

    .bg-warning-subtle {
        background-color: #fffbeb !important;
        color: #b45309 !important;
        border-color: #fef3c7 !important;
    }

    .bg-danger-subtle {
        background-color: #fef2f2 !important;
        color: #b91c1c !important;
        border-color: #fecaca !important;
    }

    /* Anexos */
    .btn-anexo-tag {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 5px 12px;
        border-radius: 8px;
        font-size: 12px;
        font-weight: 600;
        text-decoration: none !important;
        background: #f1f5f9;
        border: 1px solid #e2e8f0;
        color: #334155;
        transition: all 0.2s ease;
        margin-right: 6px;
        margin-top: 6px;
    }
    .btn-anexo-tag:hover {
        background: #e2e8f0;
        color: #0f172a;
        transform: translateY(-1px);
    }

    /* Box Lateral de Informações */
    .tk-sidebar-card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 14px;
        padding: 22px;
    }
    .tk-info-item {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 12px 0;
        border-bottom: 1px solid #f1f5f9;
    }
    .tk-info-item:last-child {
        border-bottom: none;
        padding-bottom: 0;
    }
    .tk-info-label {
        font-size: 12.5px;
        color: #64748b;
        font-weight: 500;
    }
    .tk-info-val {
        font-size: 13px;
        color: #1e293b;
        font-weight: 600;
        text-align: right;
    }

    /* Card de Resposta */
    .reply-card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 14px;
        padding: 22px;
        margin-top: 24px;
    }
    .reply-card-title {
        font-size: 14px;
        font-weight: 700;
        color: #0f766e;
        display: flex;
        align-items: center;
        gap: 8px;
        margin-bottom: 16px;
    }

    .btn-enviar-resposta {
        background-color: #10b981 !important;
        border-color: #10b981 !important;
        color: #fff !important;
        font-weight: 600 !important;
        padding: 9px 22px !important;
        border-radius: 10px !important;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        box-shadow: 0 4px 12px rgba(16, 185, 129, 0.25) !important;
        transition: all 0.2s ease;
    }
    .btn-enviar-resposta:hover {
        background-color: #059669 !important;
        border-color: #059669 !important;
        transform: translateY(-1px);
        box-shadow: 0 6px 16px rgba(16, 185, 129, 0.35) !important;
    }

    input[type=file]::file-selector-button {
        background-color: #f1f5f9;
        color: #334155;
        border: 0;
        border-radius: 8px;
        padding: 6px 14px;
        margin-right: 12px;
        font-size: 12px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s ease;
    }
    input[type=file]::file-selector-button:hover {
        background-color: #e2e8f0;
    }
</style>
@endsection

@section('content')
<div class="mt-3 text-dark">
    <div class="row">
        <div class="col-12">
            <div class="card card-modulo">

                <!-- ═══ CABEÇALHO PADRÃO CLARO ═══ -->
                <div class="card-header modulo-header-gradient">
                    <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                        <div>
                            <h4 class="modulo-title">
                                <i class="ri-customer-service-2-line"></i>
                                Chamado #{{ $item->id }} — {{ $item->assunto }}
                            </h4>
                            <p class="modulo-subtitle">
                                Empresa: <strong>{{ $item->empresa->nome ?? 'Minha Empresa' }}</strong> &nbsp;|&nbsp; Departamento: <strong>{{ ucfirst($item->departamento) }}</strong>
                            </p>
                        </div>
                        <div>
                            <a href="{{ route('ticket.index') }}" class="btn-voltar">
                                <i class="ri-arrow-left-line"></i> Voltar
                            </a>
                        </div>
                    </div>
                </div>

                <div class="card-body p-4">
                    <div class="row g-4">

                        <!-- ═══ COLUNA DE MENSAGENS (ESQUERDA) ═══ -->
                        <div class="col-lg-8 col-12">
                            <h5 class="fw-bold mb-3 text-dark d-flex align-items-center gap-2" style="font-size: 15px;">
                                <i class="ri-discuss-line text-primary"></i> Histórico de Mensagens
                            </h5>

                            <div class="chat-thread">
                                @foreach($item->mensagens as $m)
                                <div class="chat-message-box {{ $m->resposta ? 'chat-admin' : 'chat-client' }}">
                                    <div class="d-flex align-items-center justify-content-between mb-2">
                                        <div class="d-flex align-items-center gap-3">
                                            @if($m->resposta)
                                                <div class="avatar-icon-circle avatar-icon-admin">
                                                    <i class="ri-headphone-line"></i>
                                                </div>
                                                <div>
                                                    <strong class="text-primary fs-14">{{ env("APP_NAME", "Equipe de Suporte") }}</strong>
                                                    <span class="badge-subtle bg-primary-subtle ms-2">Equipe de Suporte</span>
                                                </div>
                                            @else
                                                <div class="avatar-icon-circle avatar-icon-client">
                                                    <i class="ri-building-line"></i>
                                                </div>
                                                <div>
                                                    <strong class="text-dark fs-14">{{ $item->empresa->nome ?? 'Cliente / Solicitante' }}</strong>
                                                    <span class="badge-subtle bg-success-subtle ms-2">Cliente / Solicitante</span>
                                                </div>
                                            @endif
                                        </div>
                                        <div>
                                            <span class="text-muted fs-11">
                                                <i class="ri-time-line me-1"></i>{{ __data_pt($m->created_at, 1) }}
                                            </span>
                                        </div>
                                    </div>

                                    <div class="chat-content text-dark fs-13 mt-2" style="line-height: 1.6;">
                                        {!! $m->descricao !!}
                                    </div>

                                    @if($m->anexos && count($m->anexos) > 0)
                                    <div class="mt-3 pt-2 border-top border-light">
                                        <span class="text-muted fs-11 fw-semibold d-block mb-1">
                                            <i class="ri-attachment-2"></i> Anexos:
                                        </span>
                                        <div class="d-flex flex-wrap">
                                            @foreach($m->anexos as $key => $f)
                                            <a target="_blank" href="{{ $f->file }}" class="btn-anexo-tag">
                                                <i class="ri-file-text-line text-primary"></i> Anexo {{ $key + 1 }}
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
                            <div class="reply-card">
                                <div class="reply-card-title">
                                    <i class="ri-corner-down-right-line fs-16"></i> Responder a este Chamado
                                </div>
                                <form method="post" action="{{ route('ticket.add-mensagem', $item->id) }}" enctype="multipart/form-data">
                                    @csrf
                                    @method('put')
                                    <div class="mb-3">
                                        <textarea name="descricao" class="form-control tiny" rows="6" placeholder="Digite sua resposta ou informações adicionais..."></textarea>
                                    </div>

                                    <div class="row align-items-center g-3">
                                        <div class="col-md-7 col-12">
                                            <label class="form-label fs-12 text-muted mb-1">
                                                <i class="ri-attachment-line"></i> Anexar Arquivos (Opcional)
                                            </label>
                                            <input type="file" name="anexos[]" multiple class="form-control bg-white">
                                        </div>
                                        <div class="col-md-5 col-12 text-md-end">
                                            <button type="submit" class="btn-enviar-resposta" id="btn-store">
                                                <i class="ri-send-plane-fill"></i> Enviar Resposta
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            @else
                            <div class="alert alert-success d-flex align-items-center gap-3 mt-4 rounded-3 border-0 shadow-sm" style="background-color: #ecfdf5; color: #065f46;">
                                <i class="ri-checkbox-circle-fill fs-22 text-success"></i>
                                <div>
                                    <strong>Chamado Resolvido:</strong> Esta solicitação foi concluída e arquivada com sucesso. Caso precise de mais ajuda, você pode abrir uma nova solicitação.
                                </div>
                            </div>
                            @endif

                        </div>

                        <!-- ═══ COLUNA DE DETALHES (DIREITA) ═══ -->
                        <div class="col-lg-4 col-12">
                            <div class="tk-sidebar-card shadow-sm">
                                <h6 class="fw-bold mb-3 text-dark d-flex align-items-center gap-2" style="font-size: 14px;">
                                    <i class="ri-information-line text-primary"></i> Detalhes do Chamado
                                </h6>

                                <div class="tk-info-item">
                                    <span class="tk-info-label">Protocolo:</span>
                                    <span class="tk-info-val">#{{ $item->id }}</span>
                                </div>

                                <div class="tk-info-item">
                                    <span class="tk-info-label">Status Atual:</span>
                                    <span class="tk-info-val">
                                        @if($item->status == 'aberto')
                                            <span class="badge-subtle bg-dark-subtle" style="background:#f1f5f9; color:#475569; border-color:#cbd5e1;">
                                                <i class="ri-inbox-line"></i> Aberto
                                            </span>
                                        @elseif($item->status == 'respondida')
                                            <span class="badge-subtle bg-warning-subtle">
                                                <i class="ri-chat-check-line"></i> Respondida
                                            </span>
                                        @elseif($item->status == 'aguardando')
                                            <span class="badge-subtle bg-danger-subtle">
                                                <i class="ri-time-line"></i> Aguardando
                                            </span>
                                        @elseif($item->status == 'resolvido')
                                            <span class="badge-subtle bg-success-subtle">
                                                <i class="ri-checkbox-circle-line"></i> Resolvido
                                            </span>
                                        @endif
                                    </span>
                                </div>

                                <div class="tk-info-item">
                                    <span class="tk-info-label">Departamento:</span>
                                    <span class="tk-info-val">
                                        <span class="badge-subtle bg-primary-subtle">
                                            <i class="ri-briefcase-line"></i> {{ ucfirst($item->departamento) }}
                                        </span>
                                    </span>
                                </div>

                                <div class="tk-info-item">
                                    <span class="tk-info-label">Empresa:</span>
                                    <span class="tk-info-val">{{ $item->empresa->nome ?? 'Minha Empresa' }}</span>
                                </div>

                                <div class="tk-info-item">
                                    <span class="tk-info-label">Data de Abertura:</span>
                                    <span class="tk-info-val">{{ __data_pt($item->created_at, 1) }}</span>
                                </div>

                                <div class="tk-info-item">
                                    <span class="tk-info-label">Última Atualização:</span>
                                    <span class="tk-info-val">{{ __data_pt($item->updated_at, 1) }}</span>
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
            height: 180,
            menubar: false,
            plugins: 'lists link code',
            toolbar: 'undo redo | bold italic underline | bullist numlist | link code',
            branding: false,
            promotion: false
        });

        setTimeout(() => {
            $('.tox-promotion, .tox-statusbar__right-container').addClass('d-none');
        }, 500);
    });
</script>
@endsection
