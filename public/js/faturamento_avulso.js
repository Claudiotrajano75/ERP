// Faturamento Avulso JS Engine
var itensNfe = [];
var editingIndex = null;
var currentStep = 1;

$(document).ready(function () {
    // Inicialização do Select2 no modal
    $("#modal-produto_id").select2({
        minimumInputLength: 2,
        language: "pt-BR",
        placeholder: "Digite para buscar o produto...",
        width: "100%",
        dropdownParent: $('#modal_lancamento_produto'),
        ajax: {
            cache: true,
            url: path_url + "api/produtos",
            dataType: "json",
            data: function (params) {
                return {
                    pesquisa: params.term,
                    empresa_id: $('#empresa_id').val()
                };
            },
            processResults: function (response) {
                var results = [];
                $.each(response, function (i, v) {
                    var o = {};
                    o.id = v.id;
                    o.text = v.nome;
                    if(v.codigo_barras){
                        o.text += ' [' + v.codigo_barras  + ']';
                    }
                    if(parseFloat(v.valor_unitario) > 0){
                        o.text += ' - R$ ' + convertFloatToMoeda(v.valor_unitario);
                    }
                    results.push(o);
                });
                return { results: results };
            }
        }
    });

    // Evento de seleção de produto no modal (carregar dados e impostos)
    $("#modal-produto_id").on("change", function () {
        let product_id = $(this).val();
        if (product_id) {
            $.get(path_url + "api/produtos/find", {
                produto_id: product_id,
                usuario_id: $('#usuario_id').val(),
                cliente_id: $('#inp-cliente_id').val(),
                entrada: 0
            })
            .done((e) => {
                // Preencher campos básicos
                $("#modal-cfop").val(e.cfop_atual || "5102");
                $("#modal-ncm").val(e.ncm || "");
                $("#modal-valor_unitario").val(convertFloatToMoeda(e.valor_unitario || 0));
                
                // Preencher tributações
                $("#modal-cst_csosn").val(e.cst_csosn).change();
                $("#modal-perc_icms").val(convertFloatToMoeda(e.perc_icms || 0));
                $("#modal-perc_red_bc").val(convertFloatToMoeda(e.perc_red_bc || 0));
                
                $("#modal-cst_pis").val(e.cst_pis).change();
                $("#modal-cst_cofins").val(e.cst_cofins).change();
                $("#modal-perc_pis").val(convertFloatToMoeda(e.perc_pis || 0));
                $("#modal-perc_cofins").val(convertFloatToMoeda(e.perc_cofins || 0));
                $("#modal-perc_ibs").val(convertFloatToMoeda(e.perc_ibs || 0));
                $("#modal-perc_cbs").val(convertFloatToMoeda(e.perc_cbs || 0));
                
                $("#modal-cst_ipi").val(e.cst_ipi || "99").change();
                $("#modal-perc_ipi").val(convertFloatToMoeda(e.perc_ipi || 0));
                $("#modal-cEnq").val(e.cEnq || "999");
                $("#modal-codigo_beneficio_fiscal").val(e.codigo_beneficio_fiscal || "");
                
                atualizarSubtotalModal();
            })
            .fail((err) => {
                console.error(err);
            });
        }
    });

    // Calcular subtotal do modal ao alterar quantidade ou preço
    $("#modal-quantidade, #modal-valor_unitario").on("input", function() {
        atualizarSubtotalModal();
    });

    // Salvar produto do modal
    $("#btn-gravar-produto-modal").on("click", function () {
        let product_id = $("#modal-produto_id").val();
        let product_nome = $("#modal-produto_id select2-modal option:selected").text() || $("#modal-produto_id").text();
        let quantidade = convertMoedaToFloat($("#modal-quantidade").val());
        let valor_unitario = convertMoedaToFloat($("#modal-valor_unitario").val());
        let cfop = $("#modal-cfop").val();
        let ncm = $("#modal-ncm").val();

        if (!product_id || quantidade <= 0 || valor_unitario < 0 || !cfop || !ncm) {
            swal("Atenção", "Preencha todos os campos obrigatórios (*)", "warning");
            return;
        }

        // Criar objeto do item
        let item = {
            produto_id: product_id,
            nome: product_nome.split(" - R$")[0], // Remove preço do nome
            quantidade: quantidade,
            valor_unitario: valor_unitario,
            sub_total: (quantidade * valor_unitario),
            cfop: cfop,
            ncm: ncm,
            codigo_beneficio_fiscal: $("#modal-codigo_beneficio_fiscal").val(),
            cst_csosn: $("#modal-cst_csosn").val(),
            perc_icms: convertMoedaToFloat($("#modal-perc_icms").val()),
            perc_red_bc: convertMoedaToFloat($("#modal-perc_red_bc").val()),
            cst_pis: $("#modal-cst_pis").val(),
            cst_cofins: $("#modal-cst_cofins").val(),
            perc_pis: convertMoedaToFloat($("#modal-perc_pis").val()),
            perc_cofins: convertMoedaToFloat($("#modal-perc_cofins").val()),
            perc_ibs: convertMoedaToFloat($("#modal-perc_ibs").val()),
            perc_cbs: convertMoedaToFloat($("#modal-perc_cbs").val()),
            cst_ipi: $("#modal-cst_ipi").val(),
            perc_ipi: convertMoedaToFloat($("#modal-perc_ipi").val()),
            cEnq: $("#modal-cEnq").val(),
            infAdProd: $("#modal-infAdProd").val()
        };

        if (editingIndex !== null) {
            itensNfe[editingIndex] = item;
            editingIndex = null;
        } else {
            itensNfe.push(item);
        }

        // Limpar modal e fechar
        limparModalItem();
        $("#modal_lancamento_produto").modal("hide");
        
        // Renderizar tabela de itens
        renderTableProdutos();
    });

    // Restaurar tributos padrão
    $("#btn-carregar-tributos-padrao").on("click", function() {
        $("#modal-produto_id").trigger("change");
    });

    // Tratar exibição do painel financeiro
    $("#switch-gerar-financeiro").on("change", function() {
        if ($(this).is(":checked")) {
            $("#panel-financeiro").slideDown();
        } else {
            $("#panel-financeiro").slideUp();
        }
    });

    // Gerar Parcelas no Financeiro
    $("#btn-gerar-faturas-avulso").on("click", function() {
        let total = parseFloat($("#inp-valor_total").val());
        let parcelas = parseInt($("#fin-parcelas").val()) || 1;
        let intervalo = parseInt($("#fin-intervalo").val()) || 30;
        let primVenc = $("#fin-primeiro_vencimento").val();

        if (total <= 0) {
            swal("Atenção", "O valor total da nota deve ser maior que R$ 0,00 para gerar financeiro.", "warning");
            return;
        }

        if (!primVenc) {
            swal("Atenção", "Selecione o primeiro vencimento.", "warning");
            return;
        }

        let valorParcela = (total / parcelas).toFixed(2);
        let resto = (total - (valorParcela * parcelas)).toFixed(2); // Diferença de dízima
        
        let html = '';
        let date = new Date(primVenc + 'T12:00:00');

        for (let i = 1; i <= parcelas; i++) {
            let valor = parseFloat(valorParcela);
            if (i === parcelas) {
                valor = parseFloat(valorParcela) + parseFloat(resto);
            }
            
            // Format data
            let y = date.getFullYear();
            let m = String(date.getMonth() + 1).padStart(2, '0');
            let d = String(date.getDate()).padStart(2, '0');
            let dateStr = `${y}-${m}-${d}`;

            html += `
                <tr>
                    <td class="fw-bold text-secondary">Parcela ${i} / ${parcelas}</td>
                    <td>
                        <select name="fatura_tipo[]" class="form-select form-select-sm select2">
                            <option value="01">Dinheiro</option>
                            <option value="02">Cheque</option>
                            <option value="03">Cartão de Crédito</option>
                            <option value="04">Cartão de Débito</option>
                            <option value="05">Crédito Loja</option>
                            <option value="10">Vale Alimentação</option>
                            <option value="11">Vale Refeição</option>
                            <option value="15">Boleto Bancário</option>
                            <option value="17">PIX</option>
                            <option value="90">Sem Pagamento</option>
                        </select>
                    </td>
                    <td>
                        <input type="date" name="fatura_vencimento[]" class="form-control form-control-sm" value="${dateStr}">
                    </td>
                    <td>
                        <input type="text" name="fatura_valor[]" class="form-control form-control-sm text-end money" value="${convertFloatToMoeda(valor)}">
                    </td>
                </tr>
            `;

            // Adiciona intervalo de dias para a próxima parcela
            date.setDate(date.getDate() + intervalo);
        }

        $("#table-faturas-avulso tbody").html(html);
        
        // Re-inicializa máscaras nas parcelas geradas
        $(".money").mask("000.000.000.000.000,00", { reverse: true });
    });

    // Desconto e Acréscimo alterando total
    $("#inp-desconto, #inp-acrescimo, #inp-valor_frete").on("input", function() {
        calcularTotalGeral();
    });

    // Mudança de Cliente (buscar dados da API)
    $('body').on('change', '#inp-cliente_id', function () {
        let cliente = $(this).val();
        if (cliente != '') {
            $.get(path_url + "api/clientes/find/" + cliente)
            .done((res) => {
                $('#cli-razao_social').val(res.razao_social);
                $('#cli-cpf_cnpj').val(res.cpf_cnpj);
                $('#cli-ie').val(res.ie || 'ISENTO');
                let cidade = res.cidade ? (res.cidade.nome + ' / ' + res.cidade.uf) : '--';
                $('#cli-cidade').val(cidade);
                $('.d-cliente-info').fadeIn();
            })
            .fail((err) => {
                console.error(err);
            });
        } else {
            $('.d-cliente-info').fadeOut();
        }
    });

    // Submissão do formulário: validação de itens obrigatórios
    $("#form-faturamento-avulso").on("submit", function(e) {
        if (itensNfe.length === 0) {
            swal("Atenção", "Lance pelo menos um produto na nota antes de salvar.", "warning");
            e.preventDefault();
            return false;
        }
    });
});

// Atualizar subtotal no modal
function atualizarSubtotalModal() {
    let qtd = convertMoedaToFloat($("#modal-quantidade").val()) || 0;
    let preco = convertMoedaToFloat($("#modal-valor_unitario").val()) || 0;
    $("#modal-sub_total").val(convertFloatToMoeda(qtd * preco));
}

// Limpar modal
function limparModalItem() {
    $("#modal-produto_id").val("").change();
    $("#modal-quantidade").val("1,00");
    $("#modal-valor_unitario").val("0,00");
    $("#modal-sub_total").val("0,00");
    $("#modal-cfop").val("");
    $("#modal-ncm").val("");
    $("#modal-codigo_beneficio_fiscal").val("");
    $("#modal-perc_icms").val("0,00");
    $("#modal-perc_red_bc").val("0,00");
    $("#modal-perc_pis").val("0,00");
    $("#modal-perc_cofins").val("0,00");
    $("#modal-perc_ibs").val("0,00");
    $("#modal-perc_cbs").val("0,00");
    $("#modal-perc_ipi").val("0,00");
    $("#modal-cEnq").val("999");
    $("#modal-infAdProd").val("");
}

// Renderizar itens na tabela principal
function renderTableProdutos() {
    let html = '';
    let totalProdutos = 0;

    if (itensNfe.length === 0) {
        html = `
            <tr id="tr-sem-produtos">
                <td colspan="9" class="text-center text-muted py-4">Nenhum produto lançado ainda.</td>
            </tr>
        `;
    } else {
        $.each(itensNfe, function(index, item) {
            totalProdutos += item.sub_total;
            html += `
                <tr>
                    <td class="fw-bold text-secondary">${item.produto_id}</td>
                    <td>
                        ${item.nome}
                        <!-- Inputs ocultos para submissão POST -->
                        <input type="hidden" name="item_produto_id[]" value="${item.produto_id}">
                        <input type="hidden" name="item_quantidade[]" value="${item.quantidade}">
                        <input type="hidden" name="item_valor_unitario[]" value="${item.valor_unitario}">
                        <input type="hidden" name="item_sub_total[]" value="${item.sub_total}">
                        <input type="hidden" name="item_cfop[]" value="${item.cfop}">
                        <input type="hidden" name="item_ncm[]" value="${item.ncm}">
                        <input type="hidden" name="item_codigo_beneficio_fiscal[]" value="${item.codigo_beneficio_fiscal || ''}">
                        <input type="hidden" name="item_cst_csosn[]" value="${item.cst_csosn}">
                        <input type="hidden" name="item_perc_icms[]" value="${item.perc_icms}">
                        <input type="hidden" name="item_perc_red_bc[]" value="${item.perc_red_bc}">
                        <input type="hidden" name="item_cst_pis[]" value="${item.cst_pis}">
                        <input type="hidden" name="item_cst_cofins[]" value="${item.cst_cofins}">
                        <input type="hidden" name="item_perc_pis[]" value="${item.perc_pis}">
                        <input type="hidden" name="item_perc_cofins[]" value="${item.perc_cofins}">
                        <input type="hidden" name="item_perc_ibs[]" value="${item.perc_ibs}">
                        <input type="hidden" name="item_perc_cbs[]" value="${item.perc_cbs}">
                        <input type="hidden" name="item_cst_ipi[]" value="${item.cst_ipi}">
                        <input type="hidden" name="item_perc_ipi[]" value="${item.perc_ipi}">
                        <input type="hidden" name="item_cEnq[]" value="${item.cEnq}">
                        <input type="hidden" name="item_infAdProd[]" value="${item.infAdProd || ''}">
                    </td>
                    <td class="text-end fw-bold">${convertFloatToMoeda(item.quantidade)}</td>
                    <td class="text-end">${convertFloatToMoeda(item.valor_unitario)}</td>
                    <td class="text-end fw-bold text-success">${convertFloatToMoeda(item.sub_total)}</td>
                    <td><span class="badge bg-light text-dark">${item.cfop}</span></td>
                    <td><span class="badge bg-light text-dark">${item.ncm}</span></td>
                    <td><span class="badge bg-primary">${item.cst_csosn}</span></td>
                    <td class="text-center">
                        <div class="btn-group btn-group-sm">
                            <button type="button" class="btn btn-outline-primary" onclick="editarItem(${index})" title="Editar Item">
                                <i class="ri-edit-line"></i>
                            </button>
                            <button type="button" class="btn btn-outline-danger" onclick="excluirItem(${index})" title="Excluir Item">
                                <i class="ri-delete-bin-line"></i>
                            </button>
                        </div>
                    </td>
                </tr>
            `;
        });
    }

    $("#table-produtos-avulso tbody").html(html);
    
    // Atualizar labels de valor total de produtos
    $("#label-total-produtos").text(convertFloatToMoeda(totalProdutos));
    $("#inp-valor_produtos").val(totalProdutos.toFixed(2));
    
    calcularTotalGeral();
}

// Calcular total geral (total produtos - desc + acresc + frete)
function calcularTotalGeral() {
    let totalProd = parseFloat($("#inp-valor_produtos").val()) || 0;
    let desconto = convertMoedaToFloat($("#inp-desconto").val()) || 0;
    let acrescimo = convertMoedaToFloat($("#inp-acrescimo").val()) || 0;
    let frete = convertMoedaToFloat($("#inp-valor_frete").val()) || 0;
    
    let totalGeral = totalProd - desconto + acrescimo + frete;
    if (totalGeral < 0) totalGeral = 0;
    
    $("#label-total-geral").text(convertFloatToMoeda(totalGeral));
    $("#inp-valor_total").val(totalGeral.toFixed(2));
}

// Editar item da lista
function editarItem(index) {
    let item = itensNfe[index];
    editingIndex = index;
    
    // Repopular modal
    // Criamos uma opção temporária para o select2 carregar o produto
    let newOption = new Option(item.nome, item.produto_id, true, true);
    $("#modal-produto_id").append(newOption).trigger('change.select2');
    
    $("#modal-quantidade").val(convertFloatToMoeda(item.quantidade));
    $("#modal-valor_unitario").val(convertFloatToMoeda(item.valor_unitario));
    $("#modal-sub_total").val(convertFloatToMoeda(item.sub_total));
    $("#modal-cfop").val(item.cfop);
    $("#modal-ncm").val(item.ncm);
    $("#modal-codigo_beneficio_fiscal").val(item.codigo_beneficio_fiscal || "");
    
    $("#modal-cst_csosn").val(item.cst_csosn).change();
    $("#modal-perc_icms").val(convertFloatToMoeda(item.perc_icms));
    $("#modal-perc_red_bc").val(convertFloatToMoeda(item.perc_red_bc));
    
    $("#modal-cst_pis").val(item.cst_pis).change();
    $("#modal-cst_cofins").val(item.cst_cofins).change();
    $("#modal-perc_pis").val(convertFloatToMoeda(item.perc_pis));
    $("#modal-perc_cofins").val(convertFloatToMoeda(item.perc_cofins));
    $("#modal-perc_ibs").val(convertFloatToMoeda(item.perc_ibs));
    $("#modal-perc_cbs").val(convertFloatToMoeda(item.perc_cbs));
    
    $("#modal-cst_ipi").val(item.cst_ipi).change();
    $("#modal-perc_ipi").val(convertFloatToMoeda(item.perc_ipi));
    $("#modal-cEnq").val(item.cEnq);
    $("#modal-infAdProd").val(item.infAdProd || "");
    
    // Abrir o modal
    $("#modal_lancamento_produto").modal("show");
}

// Excluir item da lista
function excluirItem(index) {
    swal({
        title: "Tem certeza?",
        text: "Deseja remover este produto lançado?",
        icon: "warning",
        buttons: ["Cancelar", "Confirmar"],
        dangerMode: true,
    })
    .then((willDelete) => {
        if (willDelete) {
            itensNfe.splice(index, 1);
            renderTableProdutos();
        }
    });
}

// Wizard Navigation Engine
function nextStep(step) {
    // Validação passo 1
    if (currentStep === 1) {
        if (!$("#inp-cliente_id").val()) {
            swal("Atenção", "Selecione o Cliente (Destinatário) antes de prosseguir.", "warning");
            return;
        }
        if (!$("#inp-natureza_id").val()) {
            swal("Atenção", "Selecione a Natureza de Operação antes de prosseguir.", "warning");
            return;
        }
    }
    
    // Validação passo 2
    if (currentStep === 2) {
        if (itensNfe.length === 0) {
            swal("Atenção", "Lance pelo menos um produto antes de avançar.", "warning");
            return;
        }
    }

    $(`#step-content-${currentStep}`).hide();
    $(`#step-btn-${currentStep}`).removeClass("active").addClass("completed");
    
    currentStep = step;
    
    $(`#step-content-${currentStep}`).fadeIn();
    $(`#step-btn-${currentStep}`).addClass("active");
}

function prevStep(step) {
    $(`#step-content-${currentStep}`).hide();
    $(`#step-btn-${currentStep}`).removeClass("active");
    
    currentStep = step;
    
    $(`#step-content-${currentStep}`).fadeIn();
    $(`#step-btn-${currentStep}`).addClass("active").removeClass("completed");
}
