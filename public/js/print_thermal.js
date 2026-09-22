/**
 * ═══════════════════════════════════════════════════════════════
 * print_thermal.js - Utilitário de impressão em impressora térmica
 * 
 * Se a impressora estiver configurada: envia direto para a térmica.
 * Se não estiver configurada: abre o PDF no navegador (comportamento antigo).
 * ═══════════════════════════════════════════════════════════════
 */

var PrintThermal = {
    
    /**
     * Imprime um cupom na impressora térmica ou abre PDF como fallback
     * 
     * @param {string} tipo - Tipo do cupom: 'cupom', 'troca', 'prevenda'
     * @param {int} id - ID do registro a imprimir
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
            // Tipo desconhecido, abre PDF direto
            window.open(pdfUrl, '_blank');
            return;
        }

        // Mostra feedback visual
        var btnOriginal = null;
        try {
            if (typeof window !== 'undefined' && window.event && window.event.target) {
                btnOriginal = window.event.target.closest('button, a');
            }
        } catch (e) {}

        if (btnOriginal) {
            btnOriginal.disabled = true;
            var originalHtml = btnOriginal.innerHTML;
            btnOriginal.innerHTML = '<i class="ri-loader-4-line spin me-1"></i> Imprimindo...';
        }

        // Verifica se a impressora está configurada
        $.ajax({
            url: '/print/configuracao',
            type: 'GET',
            success: function(config) {
                if (config.printer_configured) {
                    // Impressora configurada - envia direto para a térmica
                    PrintThermal.enviarParaImpressora(route, pdfUrl, function() {
                        if (btnOriginal) {
                            btnOriginal.disabled = false;
                            btnOriginal.innerHTML = originalHtml;
                        }
                    });
                } else {
                    // Sem impressora configurada - abre PDF (comportamento antigo)
                    window.open(pdfUrl, '_blank');
                    if (btnOriginal) {
                        btnOriginal.disabled = false;
                        btnOriginal.innerHTML = originalHtml;
                    }
                }
            },
            error: function() {
                // Erro ao verificar config - abre PDF como fallback
                window.open(pdfUrl, '_blank');
                if (btnOriginal) {
                    btnOriginal.disabled = false;
                    btnOriginal.innerHTML = originalHtml;
                }
            }
        });
    },

    /**
     * Envia o cupom para a impressora térmica via AJAX.
     * Se o servidor retornar use_pdf=true (impressora desabilitada pelo admin),
     * abre automaticamente o PDF como fallback.
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
                    // Servidor na nuvem (Hostinger) -> envia para o Agente Local na máquina do usuário
                    PrintThermal.enviarParaAgenteLocal(res.printer_ip, res.printer_porta, res.payload_base64, pdfUrl, onComplete);
                } else if (res.success) {
                    PrintThermal.mostrarFeedback('success', res.message || 'Impresso com sucesso!');
                    if (onComplete) onComplete();
                } else if (res.use_pdf) {
                    // Impressora desabilitada ou sem config - abre o PDF automaticamente
                    if (pdfUrl) window.open(pdfUrl, '_blank');
                    if (onComplete) onComplete();
                } else {
                    PrintThermal.mostrarFeedback('error', res.message || 'Erro ao imprimir');
                    if (onComplete) onComplete();
                }
            },
            error: function(xhr) {
                // Erro de rede/servidor - abre PDF como fallback
                if (pdfUrl) window.open(pdfUrl, '_blank');
                var msg = xhr.responseJSON ? xhr.responseJSON.message : 'Erro de conexão';
                PrintThermal.mostrarFeedback('error', msg);
                if (onComplete) onComplete();
            }
        });
    },

    /**
     * Envia o payload ESC/POS para o Agente Local de Impressão (127.0.0.1:9187)
     */
    enviarParaAgenteLocal: function(ip, porta, dataBase64, pdfUrl, onComplete) {
        $.ajax({
            url: 'http://127.0.0.1:9187/print',
            type: 'POST',
            contentType: 'application/json',
            data: JSON.stringify({
                ip: ip,
                porta: porta || 9100,
                data: dataBase64
            }),
            timeout: 5000,
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
                PrintThermal.mostrarFeedback('warning', 'Inicie o Agente de Impressão local para envio direto.');
            },
            complete: function() {
                if (onComplete) onComplete();
            }
        });
    },

    /**
     * Mostra feedback visual (toast simples)
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
        toast.style.cssText = 'position:fixed;top:20px;right:20px;z-index:99999;background:' + 
            cores[tipo] + ';color:#fff;padding:12px 20px;border-radius:10px;font-size:13px;font-weight:600;' +
            'box-shadow:0 4px 14px rgba(0,0,0,0.2);display:flex;align-items:center;gap:8px;' +
            'animation:slideIn 0.3s ease;font-family:inherit;max-width:400px;';
        toast.innerHTML = '<i class="' + icones[tipo] + '"></i> ' + mensagem;
        document.body.appendChild(toast);

        // Animação de entrada
        var style = document.createElement('style');
        style.textContent = '@keyframes slideIn{from{transform:translateX(100%);opacity:0}to{transform:translateX(0);opacity:1}}';
        document.head.appendChild(style);

        // Remove após 4 segundos
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
