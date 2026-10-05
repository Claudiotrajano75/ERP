<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Página de Teste - Impressora Térmica</title>
    <style>
        * {
            box-sizing: border-box;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }
        @page {
            size: {{ ($largura ?? '80') == '58' ? '58mm' : '80mm' }} auto;
            margin: 0;
        }
        html, body {
            margin: 0;
            padding: 0;
            background: #fff;
            color: #000;
            font-family: 'Courier New', Courier, monospace;
            font-size: {{ ($largura ?? '80') == '58' ? '9px' : '11px' }};
            line-height: 1.3;
            font-weight: 600;
        }
        .cupom-container {
            width: {{ ($largura ?? '80') == '58' ? '54mm' : '74mm' }};
            margin: 0 auto;
            padding: 10px 4px 20px 4px;
        }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .text-left { text-align: left; }
        .bold { font-weight: 900; }
        .uppercase { text-transform: uppercase; }

        .line {
            border-top: 1px dashed #000;
            margin: 5px 0;
            width: 100%;
        }

        .barra-acoes {
            position: fixed;
            top: 10px;
            right: 10px;
            background: rgba(0,0,0,0.85);
            padding: 8px 12px;
            border-radius: 8px;
            z-index: 99999;
            display: flex;
            gap: 8px;
        }
        .barra-acoes button {
            background: #059669;
            color: #fff;
            border: none;
            padding: 6px 14px;
            border-radius: 6px;
            cursor: pointer;
            font-size: 13px;
            font-weight: bold;
        }
        .barra-acoes button:hover {
            background: #047857;
        }
        .barra-acoes button.btn-fechar {
            background: #4b5563;
        }

        @media print {
            .barra-acoes {
                display: none !important;
            }
            .cupom-container {
                width: 100% !important;
                padding: 0 !important;
                margin: 0 !important;
            }
        }
    </style>
</head>
<body>

    <div class="barra-acoes no-print">
        <button onclick="window.print()">
            🖨️ Imprimir Teste
        </button>
        <button class="btn-fechar" onclick="window.close()">
            ✕ Fechar
        </button>
    </div>

    <div class="cupom-container">
        <div class="text-center bold uppercase" style="font-size: 14px;">
            TESTE DE IMPRESSÃO
        </div>
        <div class="text-center uppercase bold">
            IMPRESSORA TÉRMICA USB / LOCAL
        </div>
        <div class="text-center" style="font-size: 10px;">
            {{ date('d/m/Y H:i:s') }}
        </div>

        <div class="line"></div>

        <div><strong>EMPRESA:</strong> {{ $empresa->nome_fantasia ?: $empresa->nome }}</div>
        <div><strong>LARGURA:</strong> {{ ($largura ?? '80') }}mm</div>
        <div><strong>STATUS:</strong> Configurada e Ativa</div>

        <div class="line"></div>

        <div class="text-center bold">REGUÁ DE TESTE DE ALINHAMENTO</div>
        <div style="font-size: 9px; text-align: center; letter-spacing: -0.5px;">
            123456789012345678901234567890123456789012345678
        </div>
        <div class="line"></div>

        <div class="text-center" style="margin-top: 10px;">
            Se você consegue ler este teste claramente, sua impressora térmica USB está perfeitamente configurada para emitir Cupons Fiscais (NFC-e) e Cupons Não Fiscais no mesmo padrão visual da rede!
        </div>

        <div class="line"></div>

        <div class="text-center bold" style="margin-top: 12px;">
            * SISTEMA ERP ATUALIZADO *
        </div>
    </div>

    <script>
        window.addEventListener('load', function() {
            setTimeout(function() {
                window.print();
            }, 300);
        });
    </script>
</body>
</html>
