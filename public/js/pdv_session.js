$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});

const PdvSession = {
    timer: null,
    isRestoring: false,
    isInitialLoaded: false,
    
    save: function() {
        if (this.isRestoring) {
            return;
        }
        if ($('#venda_id').val() != '') {
            return; // Se for edição de venda existente, não salva rascunho
        }
        
        clearTimeout(this.timer);
        this.timer = setTimeout(() => {
            this._executeSave();
        }, 600);
    },
    
    _executeSave: function() {
        if (this.isRestoring) return;

        let prods = [];
        let qtds = [];
        let valUnits = [];
        let subTotals = [];
        let variacoes = [];

        $('.table-itens tbody tr.line-product').each(function() {
            let pid = $(this).find('.produto_row').val();
            if (pid) {
                prods.push(pid);
                let q = $(this).find('.qtd_row').val() || '1';
                qtds.push(q);
                let vu = $(this).find('.value-unit').val() || '0,00';
                valUnits.push(vu);
                let st = $(this).find('.subtotal-item').val() || vu;
                subTotals.push(st);
                let vid = $(this).find('.variacao_id').val() || '';
                variacoes.push(vid);
            }
        });

        let cliente_id = $('#inp-cliente_id').val() || '';
        let funcionario_id = $('#inp-funcionario_id').val() || '';
        let desconto = typeof convertMoedaToFloat === 'function' ? convertMoedaToFloat($('#inp-valor_desconto').val() || $('#inp-desconto').val() || '0') : 0;
        let tipo_desconto = $('#inp-tipo_desconto').val() || 'R$';
        let acrescimo = typeof convertMoedaToFloat === 'function' ? convertMoedaToFloat($('#inp-valor_acrescimo').val() || $('#inp-acrescimo').val() || '0') : 0;
        let tipo_acrescimo = $('#inp-tipo_acrescimo').val() || 'R$';
        let observacao = $('#inp-observacao_row').val() || '';

        const userId = $('#usuario_id').val() || '1';
        const terminalId = this._getTerminalId();

        // Se o carrinho estiver vazio e não houver cliente nem desconto, limpa rascunho
        if (prods.length === 0 && !cliente_id && !funcionario_id && desconto <= 0 && acrescimo <= 0) {
            if (this.isInitialLoaded) {
                this.clear();
            }
            return;
        }

        let json = {
            'produto_id[]': prods,
            'quantidade[]': qtds,
            'valor_unitario[]': valUnits,
            'subtotal_item[]': subTotals,
            'variacao_id[]': variacoes,
            cliente_id: cliente_id,
            funcionario_id: funcionario_id,
            desconto: desconto,
            tipo_desconto: tipo_desconto,
            acrescimo: acrescimo,
            tipo_acrescimo: tipo_acrescimo,
            observacao: observacao,
            empresa_id: $('#empresa_id').val(),
            usuario_id: userId,
            terminal_id: terminalId,
            data_hora: new Date().toISOString()
        };

        // Salvar no localStorage
        const key = 'pdv_draft_' + userId + '_' + terminalId;
        try {
            localStorage.setItem(key, JSON.stringify(json));
        } catch(e) {}

        // Enviar para backend
        $.post(path_url + 'pdv/draft/save', json)
            .done((res) => {})
            .fail((err) => {});
    },
    
    load: function() {
        if ($('#venda_id').val() != '') {
            this.isInitialLoaded = true;
            return; // Se tem ID, já é edição
        }
        
        const userId = $('#usuario_id').val() || '1';
        const terminalId = this._getTerminalId();
        const key = 'pdv_draft_' + userId + '_' + terminalId;
        
        let localDraftStr = null;
        try {
            localDraftStr = localStorage.getItem(key);
        } catch(e) {}
        let localDraft = localDraftStr ? JSON.parse(localDraftStr) : null;
        
        // Verifica no backend
        $.get(path_url + 'pdv/draft/current', { terminal_id: terminalId })
            .done((res) => {
                let remoteDraft = res.has_draft ? res.draft : null;
                let draftToUse = null;
                
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
                } else {
                    this.isInitialLoaded = true;
                }
            })
            .fail((err) => {
                if (localDraft && this._hasItems(localDraft)) {
                    this._promptRecovery(localDraft);
                } else {
                    this.isInitialLoaded = true;
                }
            });
    },
    
    clear: function() {
        const userId = $('#usuario_id').val() || '1';
        const terminalId = this._getTerminalId();
        const key = 'pdv_draft_' + userId + '_' + terminalId;
        
        try {
            localStorage.removeItem(key);
        } catch(e) {}

        $.ajax({
            url: path_url + 'pdv/draft/clear',
            type: 'DELETE',
            data: { terminal_id: terminalId },
            success: function(res) {},
            error: function(err) {}
        });
    },
    
    _hasItems: function(draft) {
        if (!draft) return false;
        let prods = draft['produto_id[]'] || draft['produto_id'];
        if (Array.isArray(prods) && prods.length > 0) return true;
        if (typeof prods === 'string' && prods.trim() !== '') return true;
        if (draft.cliente_id && String(draft.cliente_id).trim() !== '') return true;
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
                this.isInitialLoaded = true;
            }
        });
    },
    
    _restoreDraft: function(draft) {
        this.isRestoring = true;
        
        // Limpar tabela existente para evitar duplicações
        $('.table-itens tbody').empty();

        // Extrair arrays de produtos
        let prods = draft['produto_id[]'] || draft['produto_id'] || [];
        if (!Array.isArray(prods)) prods = [prods];
        
        let qtds = draft['quantidade[]'] || draft['quantidade'] || [];
        if (!Array.isArray(qtds)) qtds = [qtds];

        let valUnits = draft['valor_unitario[]'] || draft['valor_unitario'] || [];
        if (!Array.isArray(valUnits)) valUnits = [valUnits];

        let subTotals = draft['subtotal_item[]'] || draft['subtotal_item'] || [];
        if (!Array.isArray(subTotals)) subTotals = [subTotals];

        let variacoes = draft['variacao_id[]'] || draft['variacao_id'] || [];
        if (!Array.isArray(variacoes)) variacoes = [variacoes];

        // Restauração de itens sequencial
        this._restoreItemsSequentially(draft, prods, qtds, valUnits, subTotals, variacoes, 0, () => {
            
            // Restaurar Cliente
            if (draft.cliente_id && String(draft.cliente_id).trim() !== '') {
                $.get(path_url + "api/clientes/find/" + draft.cliente_id).done((cliente) => {
                    if (cliente) {
                        var newOption = new Option(cliente.razao_social + (cliente.cpf_cnpj ? " - " + cliente.cpf_cnpj : ""), cliente.id, true, true);
                        $('#inp-cliente_id').empty().append(newOption);
                        if (typeof pdvAtualizarCardCliente === 'function') {
                            pdvAtualizarCardCliente(cliente.razao_social);
                        }
                    }
                });
            } else {
                if (typeof pdvAtualizarCardCliente === 'function') {
                    pdvAtualizarCardCliente('');
                }
            }

            // Restaurar Vendedor / Funcionário
            if (draft.funcionario_id && String(draft.funcionario_id).trim() !== '') {
                $.get(path_url + "api/funcionarios/find/", { id: draft.funcionario_id }).done((func) => {
                    if (func) {
                        var newOption = new Option(func.nome, func.id, true, true);
                        $('#inp-funcionario_id').empty().append(newOption);
                        if (typeof pdvAtualizarCardFuncionario === 'function') {
                            pdvAtualizarCardFuncionario(func.nome);
                        }
                    }
                });
            } else {
                if (typeof pdvAtualizarCardFuncionario === 'function') {
                    pdvAtualizarCardFuncionario('');
                }
            }

            // Restaurar Descontos
            if (draft.desconto && parseFloat(draft.desconto) > 0) {
                let descFormat = typeof convertFloatToMoeda === 'function' ? convertFloatToMoeda(draft.desconto) : draft.desconto;
                $('#inp-valor_desconto').val(descFormat);
                $('#inp-desconto').val(descFormat);
            }
            if (draft.tipo_desconto) {
                $('#inp-tipo_desconto').val(draft.tipo_desconto);
            }

            // Restaurar Acréscimos
            if (draft.acrescimo && parseFloat(draft.acrescimo) > 0) {
                let acrescFormat = typeof convertFloatToMoeda === 'function' ? convertFloatToMoeda(draft.acrescimo) : draft.acrescimo;
                $('#inp-valor_acrescimo').val(acrescFormat);
                $('#inp-acrescimo').val(acrescFormat);
            }
            if (draft.tipo_acrescimo) {
                $('#inp-tipo_acrescimo').val(draft.tipo_acrescimo);
            }

            // Finalização do restore
            setTimeout(() => {
                if (typeof calcTotal === 'function') {
                    calcTotal();
                } else if (typeof calculaTotal === 'function') {
                    calculaTotal();
                }

                if (typeof pdvAtualizarContagemCarrinho === 'function') {
                    pdvAtualizarContagemCarrinho();
                }

                if (typeof validateButtonSave === 'function') {
                    validateButtonSave();
                }

                this.isRestoring = false;
                this.isInitialLoaded = true;

                swal("Restaurado", "Venda restaurada com sucesso!", "success");
            }, 500);
        });
    },
    
    _restoreItemsSequentially: function(draft, prods, qtds, valUnits, subTotals, variacoes, index, onComplete) {
        if (index >= prods.length) {
            if (typeof onComplete === 'function') onComplete();
            return;
        }
        
        let prodId = prods[index];
        if (!prodId) {
            this._restoreItemsSequentially(draft, prods, qtds, valUnits, subTotals, variacoes, index + 1, onComplete);
            return;
        }

        let qtdStr = qtds[index] ? String(qtds[index]) : "1";
        let valUnitStr = valUnits[index] ? String(valUnits[index]) : "";
        let subTotalStr = subTotals[index] ? String(subTotals[index]) : "";
        let variacaoId = variacoes[index] ? String(variacoes[index]) : "";
        
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
                let $row = $(res);
                $('.table-itens tbody').append($row);
                if (typeof checkLowStock === 'function') {
                    checkLowStock($row);
                }
            }
            this._restoreItemsSequentially(draft, prods, qtds, valUnits, subTotals, variacoes, index + 1, onComplete);
        })
        .fail((err) => {
            console.error("Erro ao restaurar item " + prodId, err);
            this._restoreItemsSequentially(draft, prods, qtds, valUnits, subTotals, variacoes, index + 1, onComplete);
        });
    },

    _getTerminalId: function() {
        let term = null;
        try {
            term = localStorage.getItem('terminal_identificador');
        } catch(e) {}

        if (!term) {
            term = 'T-' + Math.floor(Math.random() * 1000000);
            try {
                localStorage.setItem('terminal_identificador', term);
            } catch(e) {}
        }
        return term;
    }
};

$(function() {
    // Carregar rascunho se existir
    setTimeout(() => {
        PdvSession.load();
    }, 200);
    
    // Hooks para salvar rascunho
    $(document).on('click', '.pdv-qty-btn, #btn-subtrai, #btn-incrementa, .btn-delete-row, .btn-add-item', function() {
        setTimeout(() => { PdvSession.save(); }, 300);
    });
    
    $(document).on('change', '#inp-cliente_id, #inp-funcionario_id, #inp-tipo_desconto, #inp-tipo_acrescimo, .qtd_row, .value-unit', function() {
        PdvSession.save();
    });
    
    $(document).on('keyup', '#inp-desconto, #inp-acrescimo, #inp-valor_desconto, #inp-valor_acrescimo', function() {
        PdvSession.save();
    });

    $(document).on('click', '.cliente-venda, .funcionario-venda, .btn-store-cliente', function() {
        setTimeout(() => { PdvSession.save(); }, 500);
    });
    
    // MutationObserver para observar alterações no carrinho
    const cartTbody = document.querySelector('.table-itens tbody');
    if (cartTbody) {
        const observer = new MutationObserver((mutations) => {
            PdvSession.save();
        });
        observer.observe(cartTbody, { childList: true, subtree: true });
    }
});
