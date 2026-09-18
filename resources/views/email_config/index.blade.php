@extends('layouts.app', ['title' => 'Configuração de Email'])

@section('css')
<style>
/* ─── Painéis de Seção Interna ─── */
.card-secao-fiscal {
    border: 1px solid #eef2f6 !important;
    border-radius: 14px !important;
    box-shadow: 0 2px 8px rgba(0,0,0,0.02) !important;
    margin-bottom: 24px !important;
    background: #ffffff;
    overflow: hidden;
}
.card-secao-fiscal .card-header {
    background: #f8fafc;
    border-bottom: 1px solid #edf2f7;
    padding: 14px 20px;
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
.card-secao-fiscal .card-body { padding: 24px !important; }

/* ─── Rodapé de status ─── */
.status-footer {
    background: #f8fafc;
    border-top: 1px solid #e2e8f0;
    border-radius: 0 0 16px 16px;
    padding: 16px 24px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 12px;
}
.status-footer p { margin: 0; font-size: 13px; color: #64748b; }
</style>
@endsection

@section('content')
<div class="mt-3 text-dark">
    <div class="row justify-content-center">
        <div class="col-lg-11">
            <div class="card border-0 shadow-sm text-dark modulo-form-card">

                {{-- ═══ CABEÇALHO ═══ --}}
                <div class="card-header modulo-header-gradient py-3 px-4">
                    <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                        <div>
                            <h4 class="mb-1 modulo-title d-flex align-items-center gap-2">
                                <i class="ri-mail-send-line"></i>
                                Configuração de Email (SMTP)
                            </h4>
                            <p class="text-muted mb-0 modulo-subtitle fs-13">
                                Configure os dados do servidor SMTP para envio de emails e notificações pelo sistema.
                            </p>
                        </div>
                        <div>
                            <a href="{{ route('home') }}" class="dash-btn dash-btn-light">
                                <i class="ri-arrow-left-line"></i> Voltar
                            </a>
                        </div>
                    </div>
                </div>

                {{-- ═══ CORPO ═══ --}}
                <div class="card-body p-4">
                    {!!Form::open()->fill($item)
                    ->post()
                    ->route('email-config.store')
                    !!}

                    @include('email_config._forms')

                    {!!Form::close()!!}
                </div>

                {{-- ═══ RODAPÉ DE STATUS ═══ --}}
                <div class="status-footer">
                    <div>
                        @if(isset($item) && $item->status)
                        <p><i class="ri-mail-check-line text-success me-1"></i>
                            Utilizando o email configurado: <strong class="text-dark">{{ $item->email }}</strong>
                        </p>
                        @else
                        <p><i class="ri-error-warning-line text-warning me-1"></i>
                            Utilizando o email padrão do sistema: <strong class="text-dark">{{ env('MAIL_USERNAME') }}</strong>
                        </p>
                        @endif
                    </div>
                    <div>
                        <a class="dash-btn dash-btn-light" href="{{ route('teste-email') }}">
                            <i class="ri-send-plane-fill me-1"></i> Enviar Email de Teste
                        </a>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
@endsection
