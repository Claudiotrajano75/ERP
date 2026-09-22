/**
 * ═══════════════════════════════════════════════════════════════
 * print_thermal.js - Utilitário de impressão em impressora térmica
 * 
 * Se a impressora estiver configurada: envia via Agente Local WebSocket (ws://127.0.0.1:9187).
 * Se não estiver configurada ou agente offline: abre o PDF no navegador (fallback seguro).
 * ═══════════════════════════════════════════════════════════════
 */

var PrintThermal = {
    
    /**
     * Imprime um cupom na impressora térmica ou abre PDF como fallback
     * 
     * @param {string} tipo - Tipo do cupom: 'cupom', 'nfce', 'troca', 'prevenda', 'sangria', 'suprimento'
     * @param {int|string} id - ID do registro a imprimir
     * @param {string} pdfUrl - URL de fallback para o PDF
     */
    imprimir: function(tipo, id, pdfUrl) {
        var routes = {
            'cupom': '/print/cupom/' + id,
            'nfce': '/print/nfce/' + id,
            'troca': '/print/troca/' + id,
            'prevenda': '/print/prevenda/' + id,
            'sangria': '/print/sangria/' + id,
            'suprimento': '/print/suprimento/' + id
        };

        var route = routes[tipo];
        if (!route) {
            if (pdfUrl) window.open(pdfUrl, '_blank');
            return;
        }

        // Mostra feedback visual no botão clicado
        var btnOriginal = null;
        var originalHtml = '';
        try {
            if (typeof window !== 'undefined' && window.event && window.event.target) {
                btnOriginal = window.event.target.closest('button, a');
            }
        } catch (e) {}

        if (btnOriginal) {
            btnOriginal.disabled = true;
            originalHtml = btnOriginal.innerHTML;
            btnOriginal.innerHTML = '<i class="ri-loader-4-line spin me-1"></i> Imprimindo...';
        }

        var restaurarBtn = function() {
            if (btnOriginal) {
                btnOriginal.disabled = false;
                btnOriginal.innerHTML = originalHtml;
            }
        };

        // Verifica status da impressora nas configurações
        $.ajax({
            url: '/print/configuracao',
            type: 'GET',
            success: function(config) {
                if (config.printer_configured) {
                    PrintThermal.enviarParaImpressora(route, pdfUrl, restaurarBtn);
                } else {
                    // Sem impressora configurada -> abre PDF
                    if (pdfUrl) window.open(pdfUrl, '_blank');
                    restaurarBtn();
                }
            },
            error: function() {
                // Erro de rede -> abre PDF
                if (pdfUrl) window.open(pdfUrl, '_blank');
                restaurarBtn();
            }
        });
    },

    /**
     * Envia o cupom para a impressora térmica via AJAX / WebSocket
     *
     * @param {string} route - Rota POST da impressora térmica
     * @param {string} pdfUrl - URL de fallback para o PDF/DANFE
     * @param {Function} onComplete - Callback ao finalizar
     */
    enviarParaImpressora: function(route, pdfUrl, onComplete) {
        $.ajax({
            url: route,
            type: 'POST',
            data: { _token: $('meta[name="csrf-token"]').attr('content') },
            success: function(res) {
                if (res.via_agent && res.payload_base64) {
                    // Sistema em nuvem (Hostinger) -> envia bytes ESC/POS ao Agente Local (127.0.0.1:9187)
                    PrintThermal.enviarParaAgenteLocal(res.printer_ip, res.printer_porta, res.payload_base64, pdfUrl, onComplete);
                } else if (res.success) {
                    PrintThermal.mostrarFeedback('success', res.message || 'Impresso com sucesso!');
                    if (onComplete) onComplete();
                } else if (res.use_pdf) {
                    // Impressora desabilitada -> abre PDF
                    if (pdfUrl) window.open(pdfUrl, '_blank');
                    if (onComplete) onComplete();
                } else {
                    PrintThermal.mostrarFeedback('error', res.message || 'Erro ao imprimir');
                    if (onComplete) onComplete();
                }
            },
            error: function(xhr) {
                if (pdfUrl) window.open(pdfUrl, '_blank');
                var msg = xhr.responseJSON ? xhr.responseJSON.message : 'Erro de conexão com o servidor';
                PrintThermal.mostrarFeedback('error', msg);
                if (onComplete) onComplete();
            }
        });
    },

    /**
     * Envia o payload ESC/POS para o Agente Local de Impressão via WebSocket (ws://127.0.0.1:9187)
     */
    enviarParaAgenteLocal: function(ip, porta, dataBase64, pdfUrl, onComplete) {
        var wsConcluido = false;
        var wsTimeout = null;
        var ws = null;

        function fallbackHttp() {
            $.ajax({
                url: 'http://127.0.0.1:9187/print',
                type: 'POST',
                contentType: 'application/json',
                data: JSON.stringify({
                    ip: ip,
                    porta: porta || 9100,
                    data: dataBase64
                }),
                timeout: 3000,
                success: function(agentRes) {
                    if (agentRes && agentRes.success) {
                        PrintThermal.mostrarFeedback('success', 'Impresso com sucesso na impressora térmica!');
                    } else {
                        PrintThermal.mostrarFeedback('warning', 'Agente local não conectou na impressora. Abrindo PDF...');
                        if (pdfUrl) window.open(pdfUrl, '_blank');
                    }
                },
                error: function(err) {
                    console.warn('Agente local (127.0.0.1:9187) offline. Abrindo PDF como fallback.', err);
                    if (pdfUrl) window.open(pdfUrl, '_blank');
                    PrintThermal.mostrarFeedback('warning', 'Inicie o Agente de Impressão na máquina local.');
                },
                complete: function() {
                    if (onComplete) onComplete();
                }
            });
        }

        try {
            ws = new WebSocket('ws://127.0.0.1:9187');
        } catch(e) {
            fallbackHttp();
            return;
        }

        wsTimeout = setTimeout(function() {
            if (!wsConcluido) {
                wsConcluido = true;
                try { ws.close(); } catch(e){}
                fallbackHttp();
            }
        }, 3500);

        ws.onopen = function() {
            ws.send(JSON.stringify({
                action: 'print',
                ip: ip,
                porta: porta || 9100,
                data: dataBase64
            }));
        };

        ws.onmessage = function(evt) {
            if (wsConcluido) return;
            wsConcluido = true;
            clearTimeout(wsTimeout);
            try { ws.close(); } catch(e){}

            try {
                var res = JSON.parse(evt.data);
                if (res.success) {
                    PrintThermal.mostrarFeedback('success', 'Impresso com sucesso na impressora térmica!');
                } else {
                    PrintThermal.mostrarFeedback('warning', (res.message || 'Falha na impressora.') + ' Abrindo PDF...');
                    if (pdfUrl) window.open(pdfUrl, '_blank');
                }
            } catch(ex) {
                PrintThermal.mostrarFeedback('success', 'Comando de impressão enviado com sucesso!');
            }

            if (onComplete) onComplete();
        };

        ws.onerror = function(err) {
            if (wsConcluido) return;
            wsConcluido = true;
            clearTimeout(wsTimeout);
            try { ws.close(); } catch(e){}
            fallbackHttp();
        };
    },

    /**
     * Mostra feedback visual (toast flutuante moderno)
     */
    mostrarFeedback: function(tipo, mensagem) {
        var cores = {
            'success': '#10b981',
            'error': '#ef4444',
            'warning': '#f59e0b'
        };
        var icones = {
            'success': 'ri-check-line',
            'error': 'ri-error-warning-line',
            'warning': 'ri-information-line'
        };

        var toast = document.createElement('div');
        toast.style.cssText = 'position:fixed;top:20px;right:20px;z-index:999999;background:' + 
            (cores[tipo] || '#10b981') + ';color:#fff;padding:12px 20px;border-radius:10px;font-size:13px;font-weight:600;' +
            'box-shadow:0 4px 14px rgba(0,0,0,0.2);display:flex;align-items:center;gap:8px;' +
            'animation:slideIn 0.3s ease;font-family:inherit;max-width:400px;';
        toast.innerHTML = '<i class="' + (icones[tipo] || 'ri-check-line') + '"></i> ' + mensagem;
        document.body.appendChild(toast);

        setTimeout(function() {
            toast.style.transition = 'all 0.3s ease';
            toast.style.opacity = '0';
            toast.style.transform = 'translateX(100%)';
            setTimeout(function() {
                if (toast.parentNode) toast.parentNode.removeChild(toast);
            }, 300);
        }, 4000);
    }
};
