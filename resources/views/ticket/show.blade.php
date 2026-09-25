@extends('layouts.app', ['title' => 'Solicitação #'.$item->id])

@section('css')
<style>
.modulo-header-gradient { background: linear-gradient(135deg, #0f0c29 0%, #302b63 50%, #24243e 100%); border-radius: 12px 12px 0 0 !important; border-bottom: none !important; }
.modulo-header-gradient .modulo-title { color: #fff; font-weight: 700; letter-spacing: -.3px; }
.modulo-header-gradient .modulo-title i { background: rgba(255,255,255,.12); padding: 8px; border-radius: 10px; color: #a8b5ff; }
.modulo-header-gradient .modulo-subtitle { color: rgba(255,255,255,.6) !important; }
.modulo-form-card { border: 1px solid #eef0f5; border-radius: 12px; overflow: hidden; }

/* Chat */
.chat-wrap { display: flex; flex-direction: column; gap: 20px; }
.chat-msg { display: flex; gap: 14px; }
.chat-msg.is-reply { flex-direction: row-reverse; }
.chat-avatar { width: 44px; height: 44px; border-radius: 50%; object-fit: cover; flex-shrink: 0; border: 2px solid #e8eaf6; box-shadow: 0 2px 8px rgba(0,0,0,.08); }
.chat-bubble { background: #f8f9fc; border: 1px solid #eef0f5; border-radius: 0 14px 14px 14px; padding: 14px 18px; max-width: 75%; position: relative; }
.chat-msg.is-reply .chat-bubble { background: linear-gradient(135deg, #ede9fe, #dbeafe); border: 1px solid #c7d2fe; border-radius: 14px 0 14px 14px; }
.chat-sender { font-size: 12px; font-weight: 700; color: #312e81; margin-bottom: 4px; }
.chat-msg.is-reply .chat-sender { color: #4338ca; text-align: right; }
.chat-time { font-size: 11px; color: #94a3b8; margin-top: 6px; }
.chat-msg.is-reply .chat-time { text-align: right; }
.chat-body { font-size: 14px; color: #1e293b; line-height: 1.6; }
.chat-body p { margin-bottom: 0; }
.chat-anexo-link { display: inline-flex; align-items: center; gap: 5px; font-size: 12px; color: #4f46e5; text-decoration: none; background: #ede9fe; border-radius: 6px; padding: 4px 10px; margin-top: 8px; }
.chat-anexo-link:hover { background: #ddd6fe; color: #3730a3; }
.chat-divider { border-color: #f1f5f9; margin: 4px 0; }

/* Sidebar info */
.tk-info-box { background: #f8faff; border: 1px solid #eef0f5; border-radius: 12px; padding: 20px; }
.tk-info-row { display: flex; flex-direction: column; gap: 2px; padding: 10px 0; border-bottom: 1px solid #f1f5f9; }
.tk-info-row:last-child { border-bottom: none; padding-bottom: 0; }
.tk-info-label { font-size: 10px; font-weight: 700; text-transform: uppercase; letter-spacing: .5px; color: #94a3b8; }
.tk-info-value { font-size: 13px; font-weight: 600; color: #1e293b; }

/* Badges status */
.tk-badge { display:inline-flex; align-items:center; gap:5px; padding:4px 12px; border-radius:20px; font-size:11px; font-weight:700; }
.tk-badge-aberto    { background:#f1f5f9; color:#475569; border:1px solid #e2e8f0; }
.tk-badge-respondida{ background:#fef3c7; color:#92400e; border:1px solid #fde68a; }
.tk-badge-aguardando{ background:#fee2e2; color:#991b1b; border:1px solid #fecaca; }
.tk-badge-resolvido { background:#dcfce7; color:#166534; border:1px solid #bbf7d0; }
.tk-depto { display:inline-flex; align-items:center; gap:4px; padding:3px 10px; border-radius:6px; font-size:11px; font-weight:600; background:#ede9fe; color:#5b21b6; }

/* Resposta form */
.reply-box { background: #fff; border: 1px solid #eef0f5; border-radius: 12px; padding: 20px; }
.reply-box-title { font-size: 13px; font-weight: 700; color: #312e81; margin-bottom: 14px; display: flex; align-items: center; gap: 6px; }
.reply-box-title i { font-size: 16px; color: #a5b4fc; }

/* Btn */
.dash-btn { display:inline-flex; align-items:center; gap:6px; padding:8px 18px; border-radius:8px; font-size:13px; font-weight:600; text-decoration:none; transition:all .2s ease; border:none; cursor:pointer; }
.dash-btn-success { background:#16a34a; color:#fff; }
.dash-btn-success:hover { background:#15803d; color:#fff; transform:translateY(-1px); }
.dash-btn-light { background:rgba(255,255,255,.15); border:1px solid rgba(255,255,255,.25); color:#fff; backdrop-filter:blur(6px); }
.dash-btn-light:hover { background:rgba(255,255,255,.28); color:#fff; }

input[type=file]::file-selector-button { background:#f8f9fc; color:#475569; border:0; border-right:1px solid #eef0f5; padding:9px 14px; margin-right:14px; transition:.3s; font-size:12px; font-weight:600; }
input[type=file]::file-selector-button:hover { background:#ede9fe; color:#4f46e5; }
</style>
@endsection

@section('content')
<div class="mt-3 text-dark">
    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm modulo-form-card">

                {{-- HEADER --}}
                <div class="card-header modulo-header-gradient py-3 px-4">
                    <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                        <div>
                            <h4 class="mb-1 modulo-title d-flex align-items-center gap-2">
                                <i class="ri-customer-service-2-line"></i>
                                Solicitação <span style="color:#a5b4fc;">#{{ str_pad($item->id, 4, '0', STR_PAD_LEFT) }}</span>
                            </h4>
                            <p class="mb-0 modulo-subtitle fs-13">{{ $item->assunto }}</p>
                        </div>
                        <div>
                            <a href="{{ route('ticket.index') }}" class="dash-btn dash-btn-light">
                                <i class="ri-arrow-left-line"></i> Voltar
                            </a>
                        </div>
                    </div>
                </div>

                <div class="card-body p-4">
                    <div class="row g-4">

                        {{-- COLUNA CHAT --}}
                        <div class="col-lg-8 col-12">
                            <div class="chat-wrap">
                                @foreach($item->mensagens as $m)
                                <div class="chat-msg {{ $m->resposta ? 'is-reply' : '' }}">
                                    @if($m->resposta)
                                    <img class="chat-avatar" src="/logo.png" alt="Suporte">
                                    @else
                                    <img class="chat-avatar" src="{{ $item->empresa->img ?? '/imgs/no-image.png' }}" alt="{{ $item->empresa->nome ?? 'Empresa' }}">
                                    @endif

                                    <div class="chat-bubble">
                                        <div class="chat-sender">
                                            {{ $m->resposta ? env('APP_NAME') : ($item->empresa->nome ?? 'Empresa') }}
                                        </div>
                                        <div class="chat-body">
                                            {!! $m->descricao !!}
                                        </div>
                                        @foreach($m->anexos as $key => $f)
                                        <a target="_blank" href="{{ $f->file }}" class="chat-anexo-link">
                                            <i class="ri-attachment-2"></i> Anexo {{ $key + 1 }}
                                        </a>
                                        @endforeach
                                        <div class="chat-time">
                                            <i class="ri-time-line me-1"></i>{{ __data_pt($m->created_at) }}
                                        </div>
                                    </div>
                                </div>
                                <hr class="chat-divider">
                                @endforeach
                            </div>

                            {{-- Formulário de resposta --}}
                            @if($item->status != 'resolvido')
                            <div class="reply-box mt-4">
                                <div class="reply-box-title">
                                    <i class="ri-reply-line"></i> Adicionar Resposta
                                </div>
                                <form method="post" action="{{ route('ticket.add-mensagem', $item->id) }}" enctype="multipart/form-data">
                                    @csrf
                                    @method('put')
                                    <div class="mb-3">
                                        {!! Form::textarea('descricao', 'Mensagem')->attrs(['rows' => '8', 'class' => 'tiny']) !!}
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label fw-semibold fs-13">
                                            <i class="ri-attachment-2 me-1 text-muted"></i> Anexos
                                        </label>
                                        <input type="file" name="anexos[]" multiple class="form-control">
                                    </div>
                                    <div class="text-end">
                                        <button type="submit" class="dash-btn dash-btn-success px-5" id="btn-store">
                                            <i class="ri-send-plane-line"></i> Enviar Resposta
                                        </button>
                                    </div>
                                </form>
                            </div>
                            @else
                            <div class="alert alert-success d-flex align-items-center gap-2 mt-4 rounded-3">
                                <i class="ri-checkbox-circle-line fs-18"></i>
                                <span>Esta solicitação foi <strong>resolvida</strong>. Não é mais possível adicionar respostas.</span>
                            </div>
                            @endif
                        </div>

                        {{-- SIDEBAR INFO --}}
                        <div class="col-lg-4 col-12">
                            <div class="tk-info-box">
                                <div class="fw-bold fs-13 text-uppercase text-muted mb-3" style="letter-spacing:.5px;">
                                    <i class="ri-information-line me-1 text-indigo-400"></i> Detalhes do Chamado
                                </div>

                                <div class="tk-info-row">
                                    <div class="tk-info-label">Número</div>
                                    <div class="tk-info-value">#{{ str_pad($item->id, 4, '0', STR_PAD_LEFT) }}</div>
                                </div>

                                <div class="tk-info-row">
                                    <div class="tk-info-label">Status</div>
                                    <div class="mt-1">
                                        @php
                                            $statusMap = [
                                                'aberto'     => ['class' => 'tk-badge-aberto',     'icon' => 'ri-inbox-line',           'label' => 'Aberto'],
                                                'respondida' => ['class' => 'tk-badge-respondida', 'icon' => 'ri-chat-check-line',      'label' => 'Respondida'],
                                                'aguardando' => ['class' => 'tk-badge-aguardando', 'icon' => 'ri-time-line',            'label' => 'Aguardando'],
                                                'resolvido'  => ['class' => 'tk-badge-resolvido',  'icon' => 'ri-checkbox-circle-line', 'label' => 'Resolvido'],
                                            ];
                                            $s = $statusMap[$item->status] ?? ['class' => 'tk-badge-aberto', 'icon' => 'ri-question-line', 'label' => $item->status];
                                        @endphp
                                        <span class="tk-badge {{ $s['class'] }}">
                                            <i class="{{ $s['icon'] }}"></i> {{ $s['label'] }}
                                        </span>
                                    </div>
                                </div>

                                <div class="tk-info-row">
                                    <div class="tk-info-label">Departamento</div>
                                    <div class="mt-1">
                                        <span class="tk-depto">
                                            <i class="ri-briefcase-line"></i> {{ ucfirst($item->departamento) }}
                                        </span>
                                    </div>
                                </div>

                                <div class="tk-info-row">
                                    <div class="tk-info-label">Criado em</div>
                                    <div class="tk-info-value">{{ __data_pt($item->created_at) }}</div>
                                </div>

                                <div class="tk-info-row">
                                    <div class="tk-info-label">Última atividade</div>
                                    <div class="tk-info-value">{{ __data_pt($item->updated_at) }}</div>
                                </div>

                                <div class="tk-info-row">
                                    <div class="tk-info-label">Total de mensagens</div>
                                    <div class="tk-info-value">{{ $item->mensagens->count() }}</div>
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
<script>
    $(function(){
        tinymce.init({ selector: 'textarea.tiny', language: 'pt_BR' })
        setTimeout(() => {
            $('.tox-promotion, .tox-statusbar__right-container').addClass('d-none')
        }, 500)
    })
</script>
@endsection
