@extends('layouts.app', ['title' => 'Fechamento Fiscal Mensal'])

@section('css')
<style type="text/css">
    .modulo-header-gradient { background: linear-gradient(135deg, #0d2b40 0%, #1a4a6e 100%); border-radius: 12px 12px 0 0 !important; border-bottom: none !important; }
    .modulo-header-gradient .modulo-title { color: #fff; font-weight: 700; letter-spacing: -0.3px; }
    .modulo-header-gradient .modulo-title i { background: rgba(255,255,255,0.15); padding: 8px; border-radius: 10px; color: #fff; }
    .modulo-header-gradient .modulo-subtitle { color: rgba(255,255,255,0.85) !important; font-weight: 400; }
    
    .modulo-form-card { border: 1px solid #eef0f5; border-radius: 12px; overflow: hidden; background: #fff; }
    
    .stat-card {
        border-radius: 12px;
        transition: transform 0.2s ease, box-shadow 0.2s ease;
        border: 1px solid #eef0f5;
        background: #fff;
    }
    .stat-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 18px rgba(16,24,40,.06);
    }
    .stat-icon {
        width: 46px;
        height: 46px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.4rem;
    }
    .company-pill {
        background: rgba(255,255,255,0.15);
        color: #fff;
        padding: 5px 12px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }
    .package-item {
        padding: 12px 16px;
        border-radius: 10px;
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        display: flex;
        align-items: center;
        gap: 12px;
        margin-bottom: 8px;
    }
    .package-icon {
        width: 36px;
        height: 36px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.2rem;
        flex-shrink: 0;
    }
</style>
@endsection

@section('content')
<div class="mt-3 text-dark">
    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm modulo-form-card">
                <div class="card-header modulo-header-gradient py-3 px-4 d-flex justify-content-between align-items-center flex-wrap gap-2">
                    <div>
                        <h4 class="mb-1 modulo-title d-flex align-items-center gap-2">
                            <i class="ri-archive-line"></i>
                            Fechamento Fiscal Mensal (.ZIP)
                        </h4>
                        <p class="mb-0 modulo-subtitle fs-13">
                            Pacote consolidado com todos os XMLs de Saídas, Entradas, Cupons e SPED em lote.
                        </p>
                    </div>
                    <div>
                        <span class="company-pill">
                            <i class="ri-building-line"></i> {{ $empresa->nome }} ({{ $empresa->cpf_cnpj }})
                        </span>
                    </div>
                </div>
                
                <div class="card-body bg-white p-4">

                    {{-- Filtro de Mês e Ano --}}
                    <form action="{{ route('contador-empresa.fechamento') }}" method="GET" class="mb-4">
                        <div class="row g-3 align-items-end">
                            <div class="col-md-3 col-sm-6">
                                <label class="form-label fw-semibold text-secondary fs-12 text-uppercase">Mês de Referência</label>
                                <select name="mes" class="form-select">
                                    @for($m = 1; $m <= 12; $m++)
                                        <option value="{{ $m }}" {{ $mes == $m ? 'selected' : '' }}>
                                            {{ str_pad($m, 2, '0', STR_PAD_LEFT) }} - {{ [1=>'Janeiro', 2=>'Fevereiro', 3=>'Março', 4=>'Abril', 5=>'Maio', 6=>'Junho', 7=>'Julho', 8=>'Agosto', 9=>'Setembro', 10=>'Outubro', 11=>'Novembro', 12=>'Dezembro'][$m] }}
                                        </option>
                                    @endfor
                                </select>
                            </div>
                            <div class="col-md-2 col-sm-6">
                                <label class="form-label fw-semibold text-secondary fs-12 text-uppercase">Ano</label>
                                <select name="ano" class="form-select">
                                    @for($a = date('Y') + 1; $a >= 2023; $a--)
                                        <option value="{{ $a }}" {{ $ano == $a ? 'selected' : '' }}>{{ $a }}</option>
                                    @endfor
                                </select>
                            </div>
                            <div class="col-md-3 col-sm-12">
                                <button type="submit" class="btn btn-primary px-4 shadow-sm fw-medium">
                                    <i class="ri-filter-3-line me-1"></i> Filtrar Período
                                </button>
                            </div>
                        </div>
                    </form>

                    {{-- Cards de Resumo do Período --}}
                    <div class="row g-3 mb-4">
                        <div class="col-lg-3 col-sm-6">
                            <div class="stat-card p-3 shadow-sm h-100">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="stat-icon bg-primary bg-opacity-10 text-primary">
                                        <i class="ri-file-text-line"></i>
                                    </div>
                                    <div>
                                        <small class="text-muted d-block fw-semibold text-uppercase fs-11">NF-e Saídas</small>
                                        <span class="fs-4 fw-bold text-dark">{{ $stats['nfe_saidas_qtd'] }}</span>
                                        <small class="d-block text-success fw-medium">R$ {{ __moeda($stats['nfe_saidas_valor']) }}</small>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-3 col-sm-6">
                            <div class="stat-card p-3 shadow-sm h-100">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="stat-icon bg-info bg-opacity-10 text-info">
                                        <i class="ri-download-cloud-2-line"></i>
                                    </div>
                                    <div>
                                        <small class="text-muted d-block fw-semibold text-uppercase fs-11">NF-e Entradas (Compras)</small>
                                        <span class="fs-4 fw-bold text-dark">{{ $stats['nfe_entradas_qtd'] }}</span>
                                        <small class="d-block text-primary fw-medium">R$ {{ __moeda($stats['nfe_entradas_valor']) }}</small>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-3 col-sm-6">
                            <div class="stat-card p-3 shadow-sm h-100">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="stat-icon bg-success bg-opacity-10 text-success">
                                        <i class="ri-shopping-bag-3-line"></i>
                                    </div>
                                    <div>
                                        <small class="text-muted d-block fw-semibold text-uppercase fs-11">NFC-e (PDV / Cupons)</small>
                                        <span class="fs-4 fw-bold text-dark">{{ $stats['nfce_qtd'] }}</span>
                                        <small class="d-block text-success fw-medium">R$ {{ __moeda($stats['nfce_valor']) }}</small>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-3 col-sm-6">
                            <div class="stat-card p-3 shadow-sm h-100">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="stat-icon bg-secondary bg-opacity-10 text-secondary">
                                        <i class="ri-truck-line"></i>
                                    </div>
                                    <div>
                                        <small class="text-muted d-block fw-semibold text-uppercase fs-11">CT-e & MDF-e</small>
                                        <span class="fs-4 fw-bold text-dark">{{ $stats['cte_qtd'] + $stats['mdfe_qtd'] }}</span>
                                        <small class="d-block text-muted">{{ $stats['cte_qtd'] }} CT-e / {{ $stats['mdfe_qtd'] }} MDF-e</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Card Central do Pacote Completo --}}
                    <div class="card border border-2 border-primary border-opacity-25 rounded-3 mb-4 bg-light bg-opacity-50">
                        <div class="card-body p-4">
                            <div class="row align-items-center">
                                <div class="col-lg-7 mb-3 mb-lg-0">
                                    <h5 class="fw-bold text-dark d-flex align-items-center gap-2 mb-2">
                                        <i class="ri-file-zip-fill text-primary fs-3"></i>
                                        Pacotão de Fechamento Fiscal ({{ str_pad($mes, 2, '0', STR_PAD_LEFT) }}/{{ $ano }})
                                    </h5>
                                    <p class="text-muted fs-13 mb-3">
                                        Este pacote reúne todos os documentos fiscais em um único arquivo compactado organizado em subpastas:
                                    </p>
                                    
                                    <div class="row g-2">
                                        <div class="col-sm-6">
                                            <div class="package-item">
                                                <div class="package-icon bg-primary bg-opacity-10 text-primary">
                                                    <i class="ri-folder-zip-line"></i>
                                                </div>
                                                <div class="fs-12">
                                                    <strong class="d-block text-dark">01_NFe_Saidas/</strong>
                                                    <span class="text-muted">Autorizadas, Canceladas e CCe</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="package-item">
                                                <div class="package-icon bg-info bg-opacity-10 text-info">
                                                    <i class="ri-folder-received-line"></i>
                                                </div>
                                                <div class="fs-12">
                                                    <strong class="d-block text-dark">02_NFe_Entradas_Compras/</strong>
                                                    <span class="text-muted">XMLs de fornecedores importados</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="package-item">
                                                <div class="package-icon bg-success bg-opacity-10 text-success">
                                                    <i class="ri-shopping-cart-2-line"></i>
                                                </div>
                                                <div class="fs-12">
                                                    <strong class="d-block text-dark">03_NFCe_PDV/</strong>
                                                    <span class="text-muted">Cupons fiscais do mês</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="package-item">
                                                <div class="package-icon bg-warning bg-opacity-10 text-dark">
                                                    <i class="ri-file-code-line"></i>
                                                </div>
                                                <div class="fs-12">
                                                    <strong class="d-block text-dark">SPED_Fiscal_EFD.txt</strong>
                                                    <span class="text-muted">Arquivo SPED mensal pronto</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-lg-5 text-lg-end text-center">
                                    <div class="d-flex flex-column gap-2 justify-content-center">
                                        <a href="{{ route('contador-empresa.fechamento-zip', ['mes' => $mes, 'ano' => $ano]) }}" class="btn btn-primary btn-lg shadow-sm fw-bold py-3 px-4">
                                            <i class="ri-file-download-fill me-1"></i> Baixar Pacotão Completo (.ZIP)
                                        </a>

                                        @if($stats['escritorio'] && !empty($stats['escritorio']->email))
                                        <form action="{{ route('contador-empresa.fechamento-email') }}" method="POST" onsubmit="return confirm('Deseja enviar o pacote fiscal completo para o e-mail {{ $stats['escritorio']->email }}?');">
                                            @csrf
                                            <input type="hidden" name="mes" value="{{ $mes }}">
                                            <input type="hidden" name="ano" value="{{ $ano }}">
                                            <button type="submit" class="btn btn-outline-success w-100 py-2 fw-medium shadow-sm">
                                                <i class="ri-mail-send-line me-1"></i> Enviar por E-mail para {{ $stats['escritorio']->email }}
                                            </button>
                                        </form>
                                        @else
                                        <div class="alert alert-warning p-2 fs-12 mb-0 text-start">
                                            <i class="ri-information-line me-1"></i> Cadastre o e-mail do escritório contábil para habilitar o envio automático por e-mail.
                                        </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12 text-end">
                            <a href="{{ route('contador-empresa.sped') }}" class="btn btn-outline-secondary px-4 me-2">
                                <i class="ri-settings-line me-1"></i> Acessar SPED Fiscal EFD
                            </a>
                            <a href="{{ route('home') }}" class="btn btn-light px-4">
                                <i class="ri-arrow-left-line me-1"></i> Voltar ao Painel
                            </a>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection
