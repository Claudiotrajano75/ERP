/**
 * ═══════════════════════════════════════════════════════════════
 * print_thermal.js - Utilitário de impressão em impressora térmica
 * 
 * Se a impressora estiver configurada: envia via Agente Local WebSocket (ws://127.0.0.1:9187).
 * Se não estiver configurada ou agente offline: abre o comprovante/PDF no navegador (fallback seguro).
 * ═══════════════════════════════════════════════════════════════
 */

var PrintThermal = {
    
    /**
     * Imprime um cupom na impressora térmica ou abre PDF/Comprovante como fallback
     * 
     * @param {string} tipo - Tipo do cupom: 'cupom', 'nfce', 'troca', 'prevenda', 'sangria', 'suprimento'
     * @param {int|string} id - ID do registro a imprimir
     * @param {string} pdfUrl - URL de fallback para o PDF/Comprovante
     * @param {Function} callback - Callback opcional executado após a impressão ou abertura do documento
     */
    imprimir: function(tipo, id, pdfUrl, callback) {
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
            PrintThermal.abrirDocumento(pdfUrl, callback);
            return;
        }

        // Mostra feedback visual no botão clicado (se houver)
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

        var finalizar = function() {
            if (btnOriginal) {
                btnOriginal.disabled = false;
                btnOriginal.innerHTML = originalHtml;
            }
            if (typeof callback === 'function') {
                setTimeout(callback, 400);
            }
        };

        // Envia diretamente para a rota do documento (que já valida a configuração no backend)
        PrintThermal.enviarParaImpressora(route, pdfUrl, finalizar);
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
                if (res.is_usb && res.url) {
                    // Modo USB -> abre o cupom térmico padronizado com disparo automático de impressão
                    PrintThermal.abrirDocumento(res.url, onComplete);
                } else if (res.queued) {
                    // Fila de Impressão na Nuvem -> Agente local consome em segundo plano
                    PrintThermal.mostrarFeedback('success', res.message || 'Enviado para a impressora térmica com sucesso!');
                    if (onComplete) onComplete(true);
                } else if (res.via_agent && res.payload_base64) {
                    // Sistema em nuvem (Hostinger) -> envia bytes ESC/POS ao Agente Local (127.0.0.1:9187)
                    PrintThermal.enviarParaAgenteLocal(res.printer_ip, res.printer_porta, res.payload_base64, pdfUrl, onComplete);
                } else if (res.success) {
                    PrintThermal.mostrarFeedback('success', res.message || 'Impresso com sucesso!');
                    if (onComplete) onComplete();
                } else if (res.use_pdf) {
                    // Impressora desabilitada -> abre PDF/Comprovante
                    PrintThermal.abrirDocumento(pdfUrl, onComplete);
                } else {
                    PrintThermal.mostrarFeedback('error', res.message || 'Erro ao imprimir. Abrindo comprovante...');
                    PrintThermal.abrirDocumento(pdfUrl, onComplete);
                }
            },
            error: function(xhr) {
                var msg = xhr.responseJSON ? xhr.responseJSON.message : 'Erro de conexão com o servidor';
                PrintThermal.mostrarFeedback('warning', msg + '. Abrindo comprovante...');
                PrintThermal.abrirDocumento(pdfUrl, onComplete);
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
                        if (onComplete) onComplete();
                    } else {
                        PrintThermal.mostrarFeedback('warning', 'Agente local não conectou na impressora. Abrindo documento...');
                        PrintThermal.abrirDocumento(pdfUrl, onComplete);
                    }
                },
                error: function(err) {
                    console.warn('Agente local (127.0.0.1:9187) offline ou bloqueado pelo navegador em HTTPS.', err);
                    if (window.location.protocol === 'https:') {
                        PrintThermal.mostrarFeedback('warning', 'Agente local bloqueado em HTTPS. Abrindo comprovante...');
                        PrintThermal.mostrarAvisoHttps();
                    } else {
                        PrintThermal.mostrarFeedback('warning', 'Agente de impressão offline. Abrindo comprovante...');
                    }
                    PrintThermal.abrirDocumento(pdfUrl, onComplete);
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
                    if (onComplete) onComplete();
                } else {
                    PrintThermal.mostrarFeedback('warning', (res.message || 'Falha na impressora.') + ' Abrindo documento...');
                    PrintThermal.abrirDocumento(pdfUrl, onComplete);
                }
            } catch(ex) {
                PrintThermal.mostrarFeedback('success', 'Comando de impressão enviado com sucesso!');
                if (onComplete) onComplete();
            }
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
     * Abre o comprovante/PDF no navegador de forma segura e garantida contra bloqueador de popups
     */
    abrirDocumento: function(url, onDone) {
        if (!url) {
            if (onDone) onDone();
            return;
        }

        var win = window.open(url, '_blank');
        if (!win || win.closed || typeof win.closed === 'undefined') {
            // Popup bloqueado pelo navegador -> aciona clique em link dinâmico
            var link = document.createElement('a');
            link.href = url;
            link.target = '_blank';
            link.rel = 'noopener noreferrer';
            document.body.appendChild(link);
            link.click();
            setTimeout(function() {
                if (link.parentNode) link.parentNode.removeChild(link);
                if (onDone) onDone();
            }, 800);
        } else {
            if (onDone) setTimeout(onDone, 800);
        }
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
    },

    /**
     * Modal explicativo quando o Google Chrome em HTTPS bloqueia o acesso à impressora local
     */
    mostrarAvisoHttps: function() {
        if (document.getElementById('modal-aviso-https-print')) return;

        var modal = document.createElement('div');
        modal.id = 'modal-aviso-https-print';
        modal.style.cssText = 'position:fixed;inset:0;background:rgba(15,23,42,0.6);backdrop-filter:blur(3px);z-index:9999999;display:flex;align-items:center;justify-content:center;padding:16px;font-family:inherit;animation:fadeIn 0.2s ease;';

        modal.innerHTML = 
            '<div style="background:#fff;border-radius:16px;max-width:520px;width:100%;box-shadow:0 20px 25px -5px rgba(0,0,0,0.1), 0 8px 10px -6px rgba(0,0,0,0.1);overflow:hidden;border:1px solid #e2e8f0;">' +
                '<div style="background:linear-gradient(135deg, #4f46e5 0%, #3730a3 100%);padding:18px 24px;color:#fff;display:flex;align-items:center;justify-content:between;gap:12px;">' +
                    '<div style="display:flex;align-items:center;gap:10px;">' +
                        '<div style="background:rgba(255,255,255,0.2);width:38px;height:38px;border-radius:10px;display:flex;align-items:center;justify-content:center;font-size:20px;">' +
                            '<i class="ri-printer-line"></i>' +
                        '</div>' +
                        '<div>' +
                            '<h5 style="margin:0;font-size:16px;font-weight:700;color:#fff;">Liberar Impressora Térmica no Chrome</h5>' +
                            '<p style="margin:2px 0 0;font-size:12px;opacity:0.85;">Acesso à rede local bloqueado pelo navegador em HTTPS</p>' +
                        '</div>' +
                    '</div>' +
                    '<button type="button" onclick="document.getElementById(\'modal-aviso-https-print\').remove()" style="background:transparent;border:none;color:#fff;font-size:20px;cursor:pointer;margin-left:auto;opacity:0.8;">&times;</button>' +
                '</div>' +
                '<div style="padding:22px 24px;color:#334155;font-size:13.5px;line-height:1.6;">' +
                    '<p style="margin:0 0 14px;color:#475569;">Por padrão de segurança, o Google Chrome bloqueia sites na internet (como o ERP na nuvem) de enviarem dados diretamente para impressoras na sua rede interna (192.168.x.x) a menos que você autorize.</p>' +
                    '<div style="background:#f8fafc;border:1px solid #e2e8f0;border-radius:12px;padding:14px 16px;margin-bottom:16px;">' +
                        '<div style="font-weight:700;color:#1e293b;margin-bottom:8px;font-size:13px;"><i class="ri-checkbox-circle-line text-primary me-1"></i> Como autorizar em 2 passos rápidos:</div>' +
                        '<ol style="margin:0;padding-left:18px;font-size:13px;color:#334155;">' +
                            '<li style="margin-bottom:6px;">Clique no ícone de configurações / cadeado à esquerda do link <strong>erp.code360.dev.br</strong> na barra de endereços do Chrome.</li>' +
                            '<li style="margin-bottom:6px;">Clique em <strong>"Configurações do site"</strong>.</li>' +
                            '<li style="margin-bottom:6px;">Na linha <strong>"Conteúdo não seguro"</strong>, mude de <em>Bloquear (padrão)</em> para <strong>Permitir</strong>.</li>' +
                            '<li>Volte ao ERP e aperte <strong>F5</strong> (Recarregar).</li>' +
                        '</ol>' +
                    '</div>' +
                    '<p style="margin:0;font-size:12px;color:#64748b;"><i class="ri-information-line me-1"></i> Certifique-se também de que o arquivo <strong>iniciar_oculto</strong> do Agente de Impressão foi iniciado no computador.</p>' +
                '</div>' +
                '<div style="background:#f8fafc;border-top:1px solid #e2e8f0;padding:12px 24px;display:flex;justify-content:flex-end;">' +
                    '<button type="button" onclick="document.getElementById(\'modal-aviso-https-print\').remove()" style="background:#4f46e5;color:#fff;border:none;padding:8px 20px;border-radius:8px;font-weight:600;font-size:13px;cursor:pointer;">Entendi, vou configurar</button>' +
                '</div>' +
            '</div>';

        document.body.appendChild(modal);
    }
};
