@extends('layouts.app', ['title' => 'Atualização Concluída'])

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

    /* Terminal Console de Logs */
    .terminal-console {
        background-color: #0f172a;
        color: #f1f5f9;
        font-family: 'Consolas', 'Monaco', 'Courier New', monospace;
        font-size: 13px;
        padding: 20px;
        border-radius: 12px;
        border: 1px solid #1e293b;
        max-height: 500px;
        overflow-y: auto;
        box-shadow: inset 0 2px 8px rgba(0, 0, 0, 0.4);
    }

    .terminal-console .log-entry {
        padding: 6px 0;
        border-bottom: 1px dashed rgba(255, 255, 255, 0.08);
        display: flex;
        align-items: flex-start;
        gap: 8px;
    }

    .terminal-console .log-entry:last-child {
        border-bottom: none;
    }

    /* Botões */
    .btn {
        border-radius: 10px !important;
        font-weight: 600 !important;
        font-size: 13px !important;
        padding: 10px 20px !important;
        transition: all 0.2s ease !important;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
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
</style>
@endsection

@section('content')
<div class="mt-3 text-dark">
    <div class="row">
        <div class="card">

            <!-- ═══ CABEÇALHO COM GRADIENTE PREMIUM ═══ -->
            <div class="card-header modulo-header-gradient">
                <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                    <div>
                        <h4 class="modulo-title text-white">
                            <i class="ri-checkbox-circle-line"></i> Atualização Concluída com Sucesso!
                        </h4>
                        <p class="modulo-subtitle">
                            Relatório das etapas executadas durante o processamento do pacote de arquivos.
                        </p>
                    </div>
                    <div>
                        <a href="{{ route('update-file.index') }}" class="btn btn-light btn-sm text-dark">
                            <i class="ri-arrow-left-line me-1"></i> Voltar
                        </a>
                    </div>
                </div>
            </div>

            <div class="card-body">

                <div class="d-flex align-items-center justify-content-between mb-3">
                    <span class="fw-bold text-dark fs-14">
                        <i class="ri-terminal-line text-primary me-1"></i> Log de Processamento:
                    </span>
                    <span class="badge bg-success fs-12">
                        Total de Etapas: {{ count($logMessage ?? []) }}
                    </span>
                </div>

                <!-- ═══ TERMINAL DE SAÍDA ═══ -->
                <div class="terminal-console">
                    @forelse($logMessage as $log)
                    <div class="log-entry">
                        <span class="text-success">✔</span>
                        <div>{!! $log !!}</div>
                    </div>
                    @empty
                    <div class="text-muted text-center py-3">
                        Nenhuma mensagem de log registrada.
                    </div>
                    @endforelse
                </div>

                <div class="mt-4 text-end">
                    <a href="{{ route('update-file.index') }}" class="btn btn-primary px-4">
                        <i class="ri-check-line me-1"></i> Concluir
                    </a>
                </div>

            </div>
        </div>
    </div>
</div>
@endsection