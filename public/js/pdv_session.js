$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});

const PdvSession = {
    timer: null,
    
    save: function() {
        if ($('#venda_id').val() != '') {
            return; // se for edição ou venda carregada, não salva rascunho
        }
        
        clearTimeout(this.timer);
        this.timer = setTimeout(() => {
            this._executeSave();
        }, 500);
    },
    
    _executeSave: function() {
        let json = $("#form-pdv").serializeFormJSON();
        json.empresa_id = $('#empresa_id').val();
        json.usuario_id = $('#usuario_id').val();
        json.desconto = convertMoedaToFloat($('#inp-valor_desconto').val());
        json.acrescimo = convertMoedaToFloat($('#inp-valor_acrescimo').val());
        json.terminal_id = this._getTerminalId();
        json.data_hora = new Date().toISOString();

        // Salvar no localStorage
        const userId = $('#usuario_id').val();
        const key = 'pdv_draft_' + userId + '_' + json.terminal_id;
        localStorage.setItem(key, JSON.stringify(json));

        // Enviar para backend
        $.post(path_url + 'pdv/draft/save', json)
            .done((res) => {})
            .fail((err) => {
                console.warn("Falha ao salvar rascunho no servidor, rascunho salvo localmente.");
            });
    },
    
    load: function() {
        if ($('#venda_id').val() != '') {
            return; // Se tem ID, já é edição, não carrega draft
        }
        
        // Verifica primeiro se tem local
        const userId = $('#usuario_id').val();
        const terminalId = this._getTerminalId();
        const key = 'pdv_draft_' + userId + '_' + terminalId;
        
        let localDraftStr = localStorage.getItem(key);
        let localDraft = localDraftStr ? JSON.parse(localDraftStr) : null;
        
        // Verifica no backend
        $.get(path_url + 'pdv/draft/current', { terminal_id: terminalId })
            .done((res) => {
                let remoteDraft = res.has_draft ? res.draft : null;
                
                let draftToUse = null;
                
                // Comparar datas para usar o mais recente (se tiver ambos)
                if (localDraft && remoteDraft) {
                    if (new Date(localDraft.data_hora) > new Date(remoteDraft.data_hora)) {
                        draftToUse = localDraft;
                    } else {
                        draftToUse = remoteDraft;
                    }
                } else if (localDraft) {
                    draftToUse = localDraft;
                } else if (remoteDraft) {
                    draftToUse = remoteDraft;
                }
                
                if (draftToUse && this._hasItems(draftToUse)) {
                    this._promptRecovery(draftToUse);
                }
            })
            .fail((err) => {
                // Se falhar a rede, confia no localDraft
                if (localDraft && this._hasItems(localDraft)) {
                    this._promptRecovery(localDraft);
                }
            });
    },
    
    clear: function() {
        const userId = $('#usuario_id').val();
        const terminalId = this._getTerminalId();
        const key = 'pdv_draft_' + userId + '_' + terminalId;
        
        localStorage.removeItem(key);
        $.ajax({
            url: path_url + 'pdv/draft/clear',
            type: 'DELETE',
            data: { terminal_id: terminalId },
            success: function(res) {},
            error: function(err) {}
        });
    },
    
    _hasItems: function(draft) {
        if (draft['produto_id[]']) return true;
        if (draft['produto_id']) return true;
        return false;
    },
    
    _promptRecovery: function(draftToUse) {
        swal({
            title: "Venda Pendente Encontrada",
            text: "Existe uma venda não finalizada neste caixa. Deseja continuar de onde parou ou iniciar uma nova venda?",
            icon: "info",
            buttons: {
                cancel: {
                    text: "Abandonar Venda",
                    value: "cancelar",
                    visible: true,
                    className: "btn-danger"
                },
                confirm: {
                    text: "Continuar Venda",
                    value: "continuar",
                    className: "btn-success"
                }
            },
            closeOnClickOutside: false,
        }).then((value) => {
            if (value === "continuar") {
                this._restoreDraft(draftToUse);
            } else if (value === "cancelar") {
                this.clear();
            }
        });
    },
    
    _restoreDraft: function(draft) {
        // Restaurar Produtos
        let prods = draft['produto_id[]'];
        if (prods && !Array.isArray(prods)) { prods = [prods]; }
        
        let qtds = draft['quantidade[]'];
        if (qtds && !Array.isArray(qtds)) { qtds = [qtds]; }
        
        if (prods && prods.length > 0) {
            this._restoreItemsSequentially(draft, prods, qtds, 0);
        }
        
        // Restaurar Cliente
        if (draft.cliente_id) {
            $.get(path_url + "api/clientes/find/" + draft.cliente_id).done((cliente) => {
                if(cliente){
                    var newOption = new Option(cliente.razao_social, cliente.id, true, true);
                    $('#inp-cliente_id').append(newOption).trigger('change');
                }
            });
        }
        
        // Restaurar Desconto e Tipo
        if (draft.desconto > 0) {
            $('#inp-valor_desconto').val(convertFloatToMoeda(draft.desconto));
            $('#inp-desconto').val(convertFloatToMoeda(draft.desconto));
        }
        if (draft.tipo_desconto) {
            $('#inp-tipo_desconto').val(draft.tipo_desconto);
        }
        
        // Restaurar Acréscimo e Tipo
        if (draft.acrescimo > 0) {
            $('#inp-valor_acrescimo').val(convertFloatToMoeda(draft.acrescimo));
            $('#inp-acrescimo').val(convertFloatToMoeda(draft.acrescimo));
        }
        if (draft.tipo_acrescimo) {
            $('#inp-tipo_acrescimo').val(draft.tipo_acrescimo);
        }
        
        setTimeout(() => {
            if (typeof calculaTotal === 'function') {
                calculaTotal();
            } else if (typeof calcTotal === 'function') {
                calcTotal();
            }
            swal("Restaurado", "Venda restaurada com sucesso!", "success");
        }, 1500);
    },
    
    _restoreItemsSequentially: function(draft, prods, qtds, index) {
        if (index >= prods.length) {
            if (typeof calculaTotal === 'function') {
                calculaTotal();
            } else if (typeof calcTotal === 'function') {
                calcTotal();
            }
            return;
        }
        
        let prodId = prods[index];
        let qtdStr = qtds[index] ? String(qtds[index]) : "1";
        let qtdFloat = parseFloat(qtdStr.replace(',', '.'));
        
        let valUnits = draft['valor_unitario[]'];
        if (valUnits && !Array.isArray(valUnits)) valUnits = [valUnits];
        let valUnitStr = valUnits && valUnits[index] ? String(valUnits[index]) : "0,00";
        
        let subTotals = draft['subtotal_item[]'];
        if (subTotals && !Array.isArray(subTotals)) subTotals = [subTotals];
        let subTotalStr = subTotals && subTotals[index] ? String(subTotals[index]) : valUnitStr;
        
        let variacoes = draft['variacao_id[]'];
        if (variacoes && !Array.isArray(variacoes)) variacoes = [variacoes];
        let variacaoId = variacoes && variacoes[index] ? variacoes[index] : "";
        
        if ($('.table-itens tbody').length > 0 && $('.itens-cart').length === 0) {
            // Usa endpoint da visualização clássica/default
            let dataRequest = {
                qtd: qtdStr,
                value_unit: valUnitStr,
                sub_total: subTotalStr,
                product_id: prodId,
                variacao_id: variacaoId,
            };
            
            $.get(path_url + "api/frenteCaixa/linhaProdutoVenda", dataRequest)
            .done((res) => {
                if (res && res !== false) {
                    $('.table-itens tbody').append(res);
                } else {
                    console.error("linhaProdutoVenda returned false for product ", prodId);
                }
                setTimeout(() => {
                    this._restoreItemsSequentially(draft, prods, qtds, index + 1);
                }, 100);
            })
            .fail((err) => {
                console.error("linhaProdutoVenda erro:", err);
                setTimeout(() => {
                    this._restoreItemsSequentially(draft, prods, qtds, index + 1);
                }, 100);
            });
        } else {
            // Usa endpoint da visualização compacta (cards)
            $.post(path_url + 'api/frenteCaixa/add-produto', { 
                produto_id: prodId, 
                lista_id: $('#lista_id').val(), 
                qtd: 0 
            }).done((res) => {
                $('.itens-cart').append(res);
                
                setTimeout(() => {
                    let lastChild = $('.itens-cart .products').last();
                    
                    if (lastChild && lastChild.length > 0) {
                        lastChild.find('.quantidade').val(qtdFloat);
                        lastChild.find('.qtd-row').val(qtdFloat);
                        lastChild.find('.qtd_row').val(qtdFloat);
                        
                        let foundUnitStr = lastChild.find('.valor_unitario').val() || lastChild.find('.value-unit').val();
                        if (foundUnitStr) {
                            let vf = convertMoedaToFloat(foundUnitStr);
                            lastChild.find('.subtotal_item').val(convertFloatToMoeda(vf*qtdFloat));
                            lastChild.find('.subtotal-item').val(convertFloatToMoeda(vf*qtdFloat));
                            lastChild.find('.price').text("R$ " + convertFloatToMoeda(vf*qtdFloat));
                        }
                    }
                    this._restoreItemsSequentially(draft, prods, qtds, index + 1);
                }, 100);
            });
        }
    },

    _getTerminalId: function() {
        // Poderiamos pegar um caixa aberto, do LocalStorage ou 'default'
        let term = localStorage.getItem('terminal_identificador');
        if (!term) {
            term = 'T-' + Math.floor(Math.random() * 1000000);
            localStorage.setItem('terminal_identificador', term);
        }
        return term;
    }
};

$(function() {
    // Inicializa carregando o rascunho
    PdvSession.load();
    
    // Hooks nas ações do PDV para salvar
    $(document).on('click', '.increment-decrement', function() {
        PdvSession.save();
    });
    
    $(document).on('change', '#inp-cliente_id, #inp-tipo_desconto, #inp-tipo_acrescimo', function() {
        PdvSession.save();
    });
    
    $(document).on('keyup', '#inp-desconto, #inp-acrescimo', function() {
        PdvSession.save();
    });
    
    // In vez de hooks em functions, vamos usar um observer para detectar novos itens e mudancas
    const cartContainers = document.querySelectorAll('.itens-cart, .table-itens tbody');
    if (cartContainers.length > 0) {
        const observer = new MutationObserver((mutations) => {
            PdvSession.save();
        });
        cartContainers.forEach(container => {
            observer.observe(container, { childList: true, subtree: true, attributes: true, attributeFilter: ['value'] });
        });
    }
    
    // Fallback para input change caso observer n pegar algo especifico
    $(document).on('change', '.qtd-row, .quantidade', function() {
        PdvSession.save();
    });
});
