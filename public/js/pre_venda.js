var DESCONTO = 0;
var VALORACRESCIMO = 0;
var PERCENTUALMAXDESCONTO = false;

function beepSucesso(){
    let alerta = $('#alerta_sonoro').val();
    if(alerta == 1 || alerta === undefined || alerta === ''){
        try {
            var audio = new Audio('/audio/beep.mp3');
            audio.play().catch(function(err){});
        } catch(e){}
    }
}

function beepErro(){
    let alerta = $('#alerta_sonoro').val();
    if(alerta == 1 || alerta === undefined || alerta === ''){
        try {
            var audio = new Audio('/audio/beep_error.mp3');
            audio.play().catch(function(err){});
        } catch(e){}
    }
}

function preVendaAtualizarCardCliente(razaoSocial) {
    if (razaoSocial && String(razaoSocial).trim() !== '') {
        $('.cliente_selecionado').text(razaoSocial).removeClass('pdv-card-value-empty').addClass('pdv-card-value');
        $('.pdv-badge-cliente').removeClass('pdv-badge-pending').addClass('pdv-badge-selected').html('✓ Selecionado');
    } else {
        $('.cliente_selecionado').html('<i class="ri-user-search-line"></i> Nenhum').removeClass('pdv-card-value').addClass('pdv-card-value-empty');
        $('.pdv-badge-cliente').removeClass('pdv-badge-selected').addClass('pdv-badge-pending').html('○ Pendente');
    }
}

function preVendaAtualizarCardFuncionario(nomeFuncionario) {
    if (nomeFuncionario && String(nomeFuncionario).trim() !== '') {
        $('.vendedor_selecionado, .funcionario_selecionado').text(nomeFuncionario).removeClass('pdv-card-value-empty').addClass('pdv-card-value');
        $('.pdv-badge-vendedor').removeClass('pdv-badge-pending').addClass('pdv-badge-selected').html('✓ Selecionado');
    } else {
        $('.vendedor_selecionado, .funcionario_selecionado').html('<i class="ri-user-search-line"></i> Nenhum').removeClass('pdv-card-value').addClass('pdv-card-value-empty');
        $('.pdv-badge-vendedor').removeClass('pdv-badge-selected').addClass('pdv-badge-pending').html('○ Pendente');
    }
}

$(function () {
    validaButtonSave()
    $("#lista_id").val('')
})

$(document).on('change', '#inp-tipo_pagamento', function() {
    pdvAtualizarCorPagamento(this);
    validaButtonSave()
})

$("#inp-produto_id").select2({
    minimumInputLength: 2,
    language: "pt-BR",
    placeholder: "Digite para buscar o produto",
    width: "100%",
    theme: "bootstrap4",
    ajax: {
        cache: true,
        url: path_url + "api/produtos",
        dataType: "json",
        data: function (params) {
            let empresa_id = $('#empresa_id').val()
            console.clear();
            var query = {
                pesquisa: params.term,
                lista_id: $('#lista_id').val(),
                empresa_id: empresa_id,
                usuario_id: $('#usuario_id').val(),
            };
            return query;
        },
        processResults: function (response) {
            var results = [];
            let compra = 0
            if($('#is_compra') && $('#is_compra').val() == 1){
                compra = 1
            }

            $.each(response, function (i, v) {
                var o = {};
                o.id = v.id;
                if(v.codigo_variacao){
                    o.codigo_variacao = v.codigo_variacao
                }

                o.text = v.nome

                if(parseFloat(v.valor_unitario) > 0){
                    o.text += ' R$ ' + convertFloatToMoeda(v.valor_unitario);
                }

                if(v.codigo_barras){
                    o.text += ' [' + v.codigo_barras  + ']';
                }
                o.value = v.id;
                // console.log(o)
                results.push(o);
            });
            return {
                results: results,
            };
        },
    },
});

// ============================================================
// CORES POR TIPO DE PAGAMENTO
// ============================================================
function pdvAtualizarCorPagamento(selectEl) {
    var valor = $(selectEl).val();
    $(selectEl).removeClass('pdv-pag-dinheiro pdv-pag-cheque pdv-pag-credito pdv-pag-debito pdv-pag-loja pdv-pag-crediario pdv-pag-vale pdv-pag-presente pdv-pag-combustivel pdv-pag-boleto pdv-pag-deposito pdv-pag-pix pdv-pag-sem');
    
    if (!valor || valor === '') return;
    
    switch(valor) {
        case '01': $(selectEl).addClass('pdv-pag-dinheiro'); break;
        case '02': $(selectEl).addClass('pdv-pag-cheque'); break;
        case '03': case '30': $(selectEl).addClass('pdv-pag-credito'); break;
        case '04': case '31': $(selectEl).addClass('pdv-pag-debito'); break;
        case '05': $(selectEl).addClass('pdv-pag-loja'); break;
        case '06': $(selectEl).addClass('pdv-pag-crediario'); break;
        case '10': case '11': case '12': $(selectEl).addClass('pdv-pag-vale'); break;
        case '13': $(selectEl).addClass('pdv-pag-combustivel'); break;
        case '14': case '15': $(selectEl).addClass('pdv-pag-boleto'); break;
        case '16': $(selectEl).addClass('pdv-pag-deposito'); break;
        case '17': case '32': $(selectEl).addClass('pdv-pag-pix'); break;
        case '90': $(selectEl).addClass('pdv-pag-sem'); break;
    }
}

function validaButtonSave() {
    $('#salvar_pre_venda').attr("disabled", 1);

    var tipo = $('#inp-tipo_pagamento').val();
    var funcionario = $('#inp-funcionario_id').val();
    var parcelasMultiplas = $(".table-payment tbody tr").length;

    if (funcionario != "" && funcionario != null) {
        if (parcelasMultiplas > 0 || (tipo != "" && tipo != null)) {
            $('#salvar_pre_venda').removeAttr("disabled");
        }
    }
}

function finalizar(id) {
    $('#finalizar_pre_venda').modal('show')
    $.get(path_url + 'api/pre-venda/finalizar/' + id)
    .done((res) => {
        $('#finalizar_pre_venda .modal-body').html(res)
        setTimeout(() => {
            calcTotalFatura()
        }, 200)
    })
    .fail((e) => {
        console.log(e)
    })
}

$('body').on('input blur keyup change', '.valor_parcela, .tipo_pagamento', function () {
    calcTotalFatura()
})

$(document).on("click", ".btn-delete-row", function () {
    var $table = $(this).closest("table");
    if ($table.find("tbody tr").length > 1) {
        $(this).closest("tr").remove();
        calcTotalFatura();
    } else {
        swal("Atenção", "A venda deve conter ao menos uma forma de pagamento!", "warning");
    }
});

function calcTotalFatura() {
    var totalFaturado = 0
    var totalDinheiro = 0
    var temDinheiro = false
    var valorTotalVenda = parseFloat($('#modal_valor_total').val()) || 0

    $(".tipo_pagamento").each(function () {
        var tipo = $(this).val()
        var valorStr = $(this).closest('tr').find('.valor_parcela').val()
        var valor = convertMoedaToFloat(valorStr) || 0
        totalFaturado += valor
        if (tipo === '01') {
            temDinheiro = true
            totalDinheiro += valor
        }
    })

    $('.total_parcelas').html("R$ " + convertFloatToMoeda(totalFaturado))

    var $painel = $('#painel-status-pagamento')
    var $botoes = $('#finalizar_pre_venda .btn-sbm')
    var diferenca = parseFloat((totalFaturado - valorTotalVenda).toFixed(2))

    if (diferenca < -0.009) {
        // Valor insuficiente
        var faltam = Math.abs(diferenca)
        $painel.removeClass('bg-success-subtle text-success border-success-subtle bg-info-subtle text-info border-info-subtle bg-warning-subtle text-warning border-warning-subtle')
               .addClass('bg-danger-subtle text-danger border border-danger-subtle')
               .html('<i class="ri-error-warning-fill fs-16"></i> Valor Insuficiente: Faltam <strong class="ms-1 fs-14">R$ ' + convertFloatToMoeda(faltam) + '</strong>')
        $botoes.prop('disabled', true)
        $('#modal_dinheiro_recebido').val(totalDinheiro)
        $('#modal_troco').val(0)
    } else if (diferenca > 0.009) {
        if (temDinheiro) {
            // Pagamento em dinheiro com troco
            var troco = diferenca
            $painel.removeClass('bg-danger-subtle text-danger border-danger-subtle bg-info-subtle text-info border-info-subtle bg-warning-subtle text-warning border-warning-subtle')
                   .addClass('bg-success-subtle text-success border border-success-subtle')
                   .html('<i class="ri-hand-coin-fill fs-16"></i> Troco a Devolver: <strong class="ms-1 fs-15 text-success">R$ ' + convertFloatToMoeda(troco) + '</strong>')
            $botoes.prop('disabled', false)
            $('#modal_dinheiro_recebido').val(totalDinheiro)
            $('#modal_troco').val(troco)
        } else {
            // Pagamento sem dinheiro ultrapassou o total
            $painel.removeClass('bg-success-subtle text-success border-success-subtle bg-info-subtle text-info border-info-subtle bg-danger-subtle text-danger border-danger-subtle')
                   .addClass('bg-warning-subtle text-warning border border-warning-subtle')
                   .html('<i class="ri-alert-line fs-16"></i> Total faturado excede a pré-venda em R$ ' + convertFloatToMoeda(diferenca) + ' sem forma Dinheiro.')
            $botoes.prop('disabled', true)
            $('#modal_dinheiro_recebido').val(0)
            $('#modal_troco').val(0)
        }
    } else {
        // Valor exato
        $painel.removeClass('bg-danger-subtle text-danger border-danger-subtle bg-warning-subtle text-warning border-warning-subtle')
               .addClass('bg-success-subtle text-success border border-success-subtle')
               .html('<i class="ri-checkbox-circle-fill fs-16"></i> Total 100% Conferido e Pago!')
        $botoes.prop('disabled', false)
        $('#modal_dinheiro_recebido').val(totalDinheiro > 0 ? totalDinheiro : valorTotalVenda)
        $('#modal_troco').val(0)
    }
}

$(document).on("keyup", "#inp-codigo_barras", function (e) {
    if (e.key === 'Enter' || e.keyCode === 13) {
        encontraItemCodigoBarras($(this).val())
    }
})

$(document).on("blur", "#inp-codigo_barras", function (e) {
    encontraItemCodigoBarras($(this).val())
})

function encontraItemCodigoBarras(codigo){
    $('.line_codigo_barras').each(function () {
        if($(this).val() == codigo){
            var $card = $(this).closest('.fin-card-item');
            if($card.length > 0){
                $(this).prev().val(1);
                $card.find('.item-name').addClass('text-success');
            }else{
                $(this).prev().val(1);
                $(this).closest('tr').find('.produto_nome').addClass('text-success');
            }
        }
    })
    setTimeout(() => {
        $('#inp-codigo_barras').val('')
    }, 50)
}

$(document).on("click", ".confirma-item", function () {
    $codigoBarra = $(this).prev()
    $status = $(this).prev().prev()
    $id = $(this).prev().prev().prev()
    $status.val(1)
    $(this).addClass('disabled')
    $nome = $(this).closest("tr").find('.produto_nome')
    $nome.addClass('text-success')
})

$(document).on("click", ".btn-add-tr", function () {
    var $table = $(this)
    .closest(".row")
    .prev()
    .find(".table-dynamic");
    var hasEmpty = false;
    $table.find("input, select").each(function () {
        if (($(this).val() == "" || $(this).val() == null) && $(this).attr("type") != "hidden" && $(this).attr("type") != "file" && !$(this).hasClass("ignore")) {
            hasEmpty = true;
        }
    });
    if (hasEmpty) {
        swal(
            "Atenção",
            "Preencha todos os campos antes de adicionar novos.",
            "warning"
            );
        return;
    }
    var $tr = $table.find(".dynamic-form").first();
    $tr.find("select.select2").select2("destroy");
    var $clone = $tr.clone();
    $clone.show();
    $clone.find("input,select").val("");

    // Preenche automaticamente a data de vencimento com a data de hoje
    let hoje = new Date().toISOString().split('T')[0];
    $clone.find('input[name="data_vencimento[]"]').val(hoje);

    // Calcula saldo restante que falta faturar e sugere no valor da nova parcela
    var totalJaFaturado = 0;
    var valorTotalVenda = parseFloat($('#modal_valor_total').val()) || 0;
    $table.find(".tipo_pagamento").each(function () {
        var valor = convertMoedaToFloat($(this).closest('tr').find('.valor_parcela').val()) || 0;
        totalJaFaturado += valor;
    });
    var saldoRestante = valorTotalVenda - totalJaFaturado;
    if (saldoRestante > 0) {
        $clone.find('.valor_parcela').val(convertFloatToMoeda(saldoRestante));
    }

    $table.append($clone);
    setTimeout(function () {
        $("tbody select.select2").select2({
            language: "pt-BR",
            width: "100%",
            theme: "bootstrap4"
        });
        calcTotalFatura();
    }, 100);
})

function validarAntesDeEnviar() {
    var totalFaturado = 0
    var valorTotalVenda = parseFloat($('#modal_valor_total').val()) || 0
    var hoje = new Date().toISOString().split('T')[0];

    $(".tipo_pagamento").each(function () {
        var $tr = $(this).closest('tr');
        var valor = convertMoedaToFloat($tr.find('.valor_parcela').val()) || 0;
        totalFaturado += valor;

        // Se a data de vencimento estiver vazia, preenche com a data de hoje
        var $vencInput = $tr.find('input[name="data_vencimento[]"]');
        if (!$vencInput.val()) {
            $vencInput.val(hoje);
        }
    })
    if (totalFaturado < (valorTotalVenda - 0.009)) {
        var faltam = valorTotalVenda - totalFaturado
        swal("Valor Insuficiente", "O total informado é menor que o valor da pré-venda! Faltam R$ " + convertFloatToMoeda(faltam) + " para completar o total de R$ " + convertFloatToMoeda(valorTotalVenda), "error")
        return false
    }
    return true
}

$(document).on("click", '#gerar_nfe', function () {
    if (!validarAntesDeEnviar()) return false;
    let fatura = getFaturas()
    gerarNFe(fatura)
})

$(document).on("click", '#gerar_nfce', function () {
    if (!validarAntesDeEnviar()) return false;
    let fatura = getFaturas()
    gerarNFCe(fatura)
})

$(document).on("click", '.finalizar_pre_venda', function () {
    if (!validarAntesDeEnviar()) return false;
    let fatura = getFaturas()
    gerarVenda(fatura)
})

function getFaturas() {
    let data = []
    let hoje = new Date().toISOString().split('T')[0];
    $('.tipo_pagamento').each(function () {
        let tipo = $(this).val()
        let vencimento = $(this).closest('tr').find('input[name="data_vencimento[]"]').val() || hoje;
        let valor = $(this).closest('tr').find('.valor_parcela').val()
        if (tipo && valor) {
            let js = {
                tipo: tipo,
                vencimento: vencimento,
                valor: valor,
            }
            data.push(js)
        }
    })
    return data
}

function gerarNFe(fatura) {
    $.post(path_url + "api/nfe/gerarNfe", {
        pre_venda_id: $('#pre_venda_id').val(),
        conta_receber: $('#inp-gerar_conta_receber').val(),
        fatura: fatura,
        valor_recebido: $('#modal_dinheiro_recebido').val(),
        troco: $('#modal_troco').val(),
        empresa_id: $('#empresa_id').val(),
        usuario_id: $('#usuario_id').val()
    })
    .done((success) => {
        transmitir(success)
    })
    .fail((err) => {
        console.log(err)
        swal("Erro", err.responseJSON || "Não foi possível gerar a NFe", "error")
    })
}

function gerarNFCe(fatura) {
    $.post(path_url + "api/nfce/gerarNfce", {
        pre_venda_id: $('#pre_venda_id').val(),
        conta_receber: $('#inp-gerar_conta_receber').val(),
        fatura: fatura,
        valor_recebido: $('#modal_dinheiro_recebido').val(),
        troco: $('#modal_troco').val(),
        empresa_id: $('#empresa_id').val(),
        usuario_id: $('#usuario_id').val()
    })
    .done((success) => {
        transmitirNfce(success)
    })
    .fail((err) => {
        console.log(err)
        swal("Erro", err.responseJSON || "Não foi possível gerar a NFCe", "error")
    })
}

function gerarVenda(fatura) {
    $.post(path_url + "api/nfce/gerarVenda", {
        pre_venda_id: $('#pre_venda_id').val(),
        conta_receber: $('#inp-gerar_conta_receber').val(),
        fatura: fatura,
        valor_recebido: $('#modal_dinheiro_recebido').val(),
        troco: $('#modal_troco').val(),
        empresa_id: $('#empresa_id').val(),
    })
    .done((success) => {
        swal({
            title: "Sucesso",
            text: "Venda finalizada com sucesso, deseja imprimir o comprovante?",
            icon: "success",
            buttons: true,
            buttons: ["Não", "Sim"],
            dangerMode: true,
        }).then((isConfirm) => {
            if (isConfirm) {
                window.open(path_url + 'frontbox/imprimir-nao-fiscal/' + success, "_blank")
                location.reload()
            } else {
                location.reload()
            }
        });
    })
    .fail((err) => {
        console.log(err)
        swal("Erro", err.responseJSON || "Não foi possível finalizar a venda", "error")
    })
}

function transmitir(id) {
    console.clear()
    $.post(path_url + "api/nfe_painel/emitir", {
        id: id,
    })
    .done((success) => {
        swal("Sucesso", "NFe emitida " + success.recibo + " - chave: [" + success.chave + "]", "success")
        .then(() => {
            window.open(path_url + 'nfe/imprimir/' + id, "_blank")
            setTimeout(() => {
                location.reload()
            }, 100)
        })
    })
    .fail((err) => {
        try {
            if (err.responseJSON.error) {
                let o = err.responseJSON.error.protNFe.infProt
                swal("Algo deu errado", o.cStat + " - " + o.xMotivo, "error")
                .then(() => {
                    location.reload()
                })
            } else {
                swal("Algo deu errado", err[0], "error")
            }
        } catch {
            try {
                swal("Algo deu errado", err.responseJSON, "error")
                .then(() => {
                    location.reload()
                })
            } catch {
                swal("Algo deu errado", err.responseJSON[0], "error")
                .then(() => {
                    location.reload()
                })
            }
        }

    })
}

function transmitirNfce(id) {
    console.clear()
    $.post(path_url + "api/nfce_painel/emitir", {
        id: id,
    })
    .done((success) => {
        swal("Sucesso", "NFCe emitida " + success.recibo + " - chave: [" + success.chave + "]", "success")
        .then(() => {
            window.open(path_url + 'nfce/imprimir/' + id, "_blank")
            setTimeout(() => {
                location.reload()
            }, 100)
        })
    })
    .fail((err) => {
        console.log(err)

        swal("Algo deu errado", err.responseJSON, "error")

    })
}


$('#codBarras').keyup((v) => {
    setTimeout(() => {
        let barcode = v.target.value
        if (barcode.length > 7) {
            $('#codBarras').val('')
            $.get(path_url + "api/produtos/findByBarcode",
            {
                barcode: barcode,
                empresa_id: $('#empresa_id').val(),
                lista_id: $('#lista_id').val(),
                usuario_id: $('#usuario_id').val()
            })
            .done((e) => {
                if (e.valor_unitario) {
                    $("#inp-produto_id").append(new Option(e.nome, e.id));
                    $("#inp-quantidade").val("1,00");
                    $("#inp-valor_unitario").val(
                        convertFloatToMoeda(e.valor_unitario)
                        );
                    $("#inp-subtotal").val(
                        convertFloatToMoeda(e.valor_unitario)
                        );
                    setTimeout(() => {
                        $('.btn-add-item').trigger('click')
                    }, 20)
                } else {
                    buscarPorReferencia(barcode)
                }
                setTimeout(() => {
                    $('#codBarras').focus()
                }, 10)
            })
            .fail((err) => {
                console.log(err);
                buscarPorReferencia(barcode)
            });
        }
    }, 500)
})

function buscarPorReferencia(barcode) {
    $.get(path_url + "api/produtos/findByBarcodeReference",
    {
        barcode: barcode,
        empresa_id: $('#empresa_id').val(),
        usuario_id: $('#usuario_id').val()
    })
    .done((e) => {
        var $newRow = $(e).addClass('pdv-item-new');
        $(".table-itens tbody").append($newRow);
        beepSucesso();
        calcTotal();
        setTimeout(function() {
            $('.total-venda').addClass('pdv-total-bounce');
            setTimeout(function() {
                $('.total-venda').removeClass('pdv-total-bounce');
            }, 500);
        }, 20);
    })
    .fail((e) => {
        console.log(e);
        beepErro();
        let msg = typeof e.responseJSON === 'string' ? e.responseJSON : (e.responseText || "Produto não localizado ou sem estoque!");
        swal("Erro", msg, "error");
    });
}


$(function () {
    setTimeout(() => {
        $('#cat_todos').first().trigger('click')
    }, 100)
})

function selectCat(id) {
    $('#cat_todos').removeClass('active')
    $('.btn_cat').removeClass('active')
    $('.btn_cat_' + id).addClass('active')
    $.get(path_url + "api/produtos/findByCategory",
    {
        lista_id: $('#lista_id').val(),
        usuario_id: $('#usuario_id').val(),
        id: id
    })
    .done((e) => {
        $('.cards-categorias').html(e)
    })
    .fail((e) => {
        console.log(e);
    });
}

function todos() {

    $('#cat_todos').addClass('active')
    $('.btn_cat').removeClass('active')

    $.get(path_url + "api/produtos/all", { 
        empresa_id: $('#empresa_id').val(),
        lista_id: $('#lista_id').val(),
        usuario_id: $('#usuario_id').val(),
    })
    .done((e) => {

        $('.cards-categorias').html(e)
    })
    .fail((e) => {
        console.log(e);
    });
}


$(function () {

    setTimeout(() => {
        $("#inp-produto_id").change(() => {

            let product_id = $("#inp-produto_id").val();

            if (product_id) {
                let codigo_variacao = $("#inp-produto_id").select2('data')[0].codigo_variacao
                $.get(path_url + "api/produtos/findWithLista",
                { 
                    produto_id: product_id,
                    lista_id: $('#lista_id').val(),
                    usuario_id: $('#usuario_id').val(),
                })
                .done((e) => {
                    if(e.variacao_modelo_id){
                        if(!codigo_variacao){
                            buscarVariacoes(product_id)
                        }else{

                            $.get(path_url + "api/variacoes/findById", {codigo_variacao: codigo_variacao})
                            .done((e) => {
                                $("#inp-variacao_id").val(codigo_variacao);
                                $("#inp-quantidade").val("1,00");
                                $("#inp-valor_unitario").val(convertFloatToMoeda(e.valor));
                                $("#inp-subtotal").val(convertFloatToMoeda(e.valor));
                            })
                            .fail((e) => {
                                console.log(e);
                            });
                        }
                    }else{
                        $("#inp-quantidade").val("1,00");
                        $("#inp-valor_unitario").val(convertFloatToMoeda(e.valor_unitario));
                        $("#inp-subtotal").val(convertFloatToMoeda(e.valor_unitario));
                    }

                    setTimeout(() => {
                        $("#inp-quantidade").focus()
                    }, 20)
                })
                .fail((e) => {
                    console.log(e);
                });
            }
        })
    }, 100)

    $("body").on("blur", ".value_unit", function () {
        let qtd = $("#inp-quantidade").val();
        let value_unit = $(this).val();
        value_unit = convertMoedaToFloat(value_unit);
        qtd = convertMoedaToFloat(qtd);
        $("#inp-subtotal").val(convertFloatToMoeda(qtd * value_unit));
    })
})

var PRODUTOID = null
function addProdutos(id) {

    $.get(path_url + "api/frenteCaixa/linhaProdutoVendaAdd", {
        id: id, 
        qtd: 0,
        lista_id: $('#lista_id').val(),
        usuario_id: $('#usuario_id').val(),
    })
    .done((e) => {
        if(!e){
            beepErro();
            swal("Alerta", "Produto sem estoque", "warning");
            return;
        }
        var $newRow = $(e).addClass('pdv-item-new');
        $(".table-itens tbody").append($newRow);
        beepSucesso();
        calcTotal();
        setTimeout(function() {
            $('.total-venda').addClass('pdv-total-bounce');
            setTimeout(function() {
                $('.total-venda').removeClass('pdv-total-bounce');
            }, 500);
        }, 20);
    })
    .fail((e) => {
        console.log(e);
        PRODUTOID = id;
        if(e.status == 402){
            buscarVariacoes(id);
        } else {
            beepErro();
            let msg = typeof e.responseJSON === 'string' ? e.responseJSON : (e.responseText || "Produto com estoque insuficiente");
            swal("Atenção", msg, "warning");
        }
    });
}

function buscarVariacoes(produto_id){
    $.get(path_url + "api/variacoes/find", { produto_id: produto_id })
    .done((res) => {
        $('#modal_variacao .modal-body').html(res)
        $('#modal_variacao').modal('show')
    })
    .fail((err) => {
        console.log(err)
        swal("Algo deu errado", "Erro ao buscar variações", "error")
    })
}

function selecionarVariacao(id, descricao, valor){
    $("#inp-quantidade").val("1,00");
    $("#inp-valor_unitario").val(convertFloatToMoeda(valor));
    $("#inp-subtotal").val(convertFloatToMoeda(valor));
    $("#inp-variacao_id").val(id);

    $('#modal_variacao').modal('hide')

    if(PRODUTOID != null){
        addItem()
    }
}

$("#inp-quantidade").on('keypress',function(e) {
    if(e.which == 13) {
        $("#inp-valor_unitario").focus()
    }
});

$("#inp-valor_unitario").on('keypress',function(e) {
    if(e.which == 13) {
        $('.btn-add-item').trigger('click')
    }
});

function addItem(){
    $.get(path_url + "api/produtos/findId/" + PRODUTOID)
    .done((res) => {
        // console.log(res)
        var newOption = new Option(res.nome, res.id, false, false);
        $('#inp-produto_id').html('')
        $('#inp-produto_id').append(newOption);
        setTimeout(() => {
            $('.btn-add-item').trigger('click')
        }, 10)
    })
    .fail((err) => {
        console.log(err)
    })
    PRODUTOID = null
}

$("#lista_precos select").each(function () {

    let id = $(this).prop("id");

    if (id == "inp-lista_preco_id") {

        $(this).select2({
            minimumInputLength: 2,
            language: "pt-BR",
            placeholder: "Digite para buscar a lista de preço",
            theme: "bootstrap4",
            dropdownParent: $(this).parent(),
            ajax: {
                cache: true,
                url: path_url + "api/lista-preco/pesquisa",
                dataType: "json",
                data: function (params) {
                    console.clear();

                    var query = {
                        pesquisa: params.term,
                        empresa_id: $("#empresa_id").val(),
                        usuario_id: $("#usuario_id").val(),
                        tipo_pagamento_lista: $("#inp-tipo_pagamento_lista").val(),
                        funcionario_lista_id: $("#inp-funcionario_lista_id").val(),
                    };
                    return query;
                },
                processResults: function (response) {
                    console.log(response)
                    var results = [];

                    $.each(response, function (i, v) {
                        var o = {};
                        o.id = v.id;

                        o.text = v.nome + " " + v.percentual_alteracao + "%";
                        o.value = v.id;
                        results.push(o);
                    });
                    return {
                        results: results,
                    };
                },
            },
        });
    }
});

function selecionaLista(){
    let tipo_pagamento_lista = $('#inp-tipo_pagamento_lista').val()
    let funcionario_lista_id = $('#inp-funcionario_lista_id').val()
    let lista_preco_id = $('#inp-lista_preco_id').val()

    if(!lista_preco_id){
        swal("Alerta", "Selecione a lista", "warning")
        return;
    }

    if(tipo_pagamento_lista){
        $('#inp-tipo_pagamento').val(tipo_pagamento_lista).change()
    }
    if(funcionario_lista_id){
        $.get(path_url + "api/funcionarios/find", {id: funcionario_lista_id})
        .done((res) => {
            console.log(res)
            var newOption = new Option(res.nome, res.id, true, false);
            $('#inp-funcionario_id').append(newOption);
            preVendaAtualizarCardFuncionario(res.nome);
        })
        .fail((err) => {
            console.log(err);
        });
    }

    $('#lista_id').val(lista_preco_id)
    setTimeout(() => {
        todos()
    }, 10)
    setTimeout(() => {
        $("#codBarras").focus();
    }, 500)
}

// ============================================================
// SUBMISSÃO AJAX (handler inline na view _forms.blade.php)
// O handler principal está inline na view para evitar cache.
// ============================================================

$(".btn-add-item").click(() => {
    let qtd = $("#inp-quantidade").val();
    let value_unit = $("#inp-valor_unitario").val();
    value_unit = convertMoedaToFloat(value_unit);
    qtd = convertMoedaToFloat(qtd);
    $("#inp-subtotal").val(convertFloatToMoeda(qtd * value_unit));
    setTimeout(() => {
        let abertura = $('#abertura').val()

        if (abertura) {
            let qtd = $("#inp-quantidade").val();
            let value_unit = $("#inp-valor_unitario").val();
            let sub_total = $("#inp-subtotal").val();
            let product_id = $("#inp-produto_id").val();
            let variacao_id = $("#inp-variacao_id").val();

            if (qtd && value_unit && product_id && sub_total) {
                let dataRequest = {
                    qtd: qtd,
                    value_unit: value_unit,
                    sub_total: sub_total,
                    product_id: product_id,
                    variacao_id: variacao_id
                };
                $.get(path_url + "api/frenteCaixa/linhaProdutoVenda", dataRequest)
                .done((e) => {
                    if (e == false) {
                        beepErro();
                        swal("Atenção", "Produto com estoque insuficiente!", "warning");
                    } else {
                        var $newRow = $(e).addClass('pdv-item-new');
                        $(".table-itens tbody").append($newRow);
                        beepSucesso();
                        calcTotal();
                        setTimeout(function() {
                            $('.total-venda').addClass('pdv-total-bounce');
                            setTimeout(function() {
                                $('.total-venda').removeClass('pdv-total-bounce');
                            }, 500);
                        }, 20);
                    }
                })
                .fail((e) => {
                    console.log(e);
                    beepErro();
                    let msg = typeof e.responseJSON === 'string' ? e.responseJSON : (e.responseText || "Produto com estoque insuficiente!");
                    swal("Atenção", msg, "warning");
                });
            } else {
                beepErro();
                swal(
                    "Atenção",
                    "Informe corretamente os campos para continuar!",
                    "warning"
                    );
            }
        } else {
            beepErro();
            swal(
                "Atenção",
                "Abra o caixa para continuar!",
                "warning"
                ).then(() => {
                    validaCaixa()
                })
            }
        }, 100);
});

function validaCaixa() {
    let abertura = $('#abertura').val()
    if (!abertura) {
        $('#modal-abrir_caixa').modal('show')
        return
    }
}


$("body").on("click", "#btn-incrementa", function () {
    let inp = $(this).closest('div.input-group-append').prev()[0] || $(this).siblings('input')[0]
    let prodRow = $(this).closest('.line-product').find('.produto_row')
    let produto_id = prodRow.length ? prodRow.val() : $(this).closest('tr').find('input[name="produto_id[]"]').val()
    if (inp && inp.value) {
        let v = convertMoedaToFloat(inp.value)
        if (produto_id) {
            $.get(path_url + "api/produtos/valida-estoque", { qtd: v + 1, product_id: produto_id })
            .done((res) => {
                v += 1
                inp.value = convertFloatToMoeda(v)
                calcSubTotal()
                beepSucesso()
            })
            .fail((err) => {
                beepErro()
                let msg = typeof err.responseJSON === 'string' ? err.responseJSON : (err.responseText || "Estoque insuficiente!");
                swal("Alerta", msg, "warning")
            });
        } else {
            v += 1
            inp.value = convertFloatToMoeda(v)
            calcSubTotal()
            beepSucesso()
        }
    }
})

$("body").on("click", "#btn-subtrai", function () {
    let inp = $(this).closest('.input-group').find('input')[0] || $(this).siblings('input')[0]
    if (inp && inp.value) {
        let v = convertMoedaToFloat(inp.value)
        v -= 1
        inp.value = convertFloatToMoeda(v)

        calcSubTotal()
        beepSucesso()
    }
})

$(".table-itens").on('click', '.btn-delete-row', function () {
    $(this).closest('tr').remove();
    swal("Sucesso", "Produto removido!", "success")
    calcTotal()
});


function calcSubTotal(e) {

    $(".line-product").each(function () {
        $qtd = $(this).find('.qtd')[0]
        $value = $(this).find('.value-unit')[0]
        $sub = $(this).find('.subtotal-item')[0]

        let qtd = convertMoedaToFloat($qtd.value)
        let value = convertMoedaToFloat($value.value)
        if (qtd <= 0) {
            $(this).remove()
        } else {
            $sub.value = convertFloatToMoeda(qtd * value)
        }
    })
    setTimeout(() => {
        calcTotal()
    }, 10)
}

function setaDesconto() {
    if (total_venda == 0) {
        swal("Erro", "Total da venda é igual a zero", "warning");
    } else {
        swal({
            title: "Valor desconto?",
            text: "Ultilize ponto(.) ao invés de virgula!",
            content: "input",
            button: {
                text: "Ok",
                closeModal: false,
                type: "error",
            },
        }).then((v) => {
            if (v) {
                let desconto = v;
                if (desconto.substring(0, 1) == "%") {
                    let perc = desconto.substring(1, desconto.length);
                    DESCONTO = TOTAL * (perc / 100);
                    if (PERCENTUALMAXDESCONTO > 0) {
                        if (perc > PERCENTUALMAXDESCONTO) {
                            swal.close();
                            setTimeout(() => {
                                swal(
                                    "Erro",
                                    "Máximo de desconto permitido é de " +
                                    PERCENTUALMAXDESCONTO +
                                    "%",
                                    "error"
                                    );
                                $("#valor_desconto").html("0,00");
                            }, 500);
                        }
                    }
                    if (DESCONTO > 0) {
                        $("#valor_item").attr("disabled", "disabled");
                        $(".btn-mini-desconto").attr(
                            "disabled",
                            "disabled"
                            );
                    } else {
                        $("#valor_item").removeAttr("disabled");
                        $(".btn-mini-desconto").removeAttr("disabled");
                    }
                } else {
                    desconto = desconto.replace(",", ".");
                    DESCONTO = parseFloat(desconto);
                    if (PERCENTUALMAXDESCONTO > 0) {
                        let tempDesc =
                        (TOTAL * PERCENTUALMAXDESCONTO) / 100;
                        if (tempDesc < DESCONTO) {
                            swal.close();

                            setTimeout(() => {
                                swal(
                                    "Erro",
                                    "Máximo de desconto permitido é de R$ " +
                                    parseFloat(tempDesc),
                                    "error"
                                    );
                                $("#valor_desconto").html("0,00");
                            }, 500);
                        }
                    }
                    if (DESCONTO > 0) {
                        $("#valor_item").attr("disabled", "disabled");
                        $(".btn-mini-desconto").attr(
                            "disabled",
                            "disabled"
                            );
                    } else {
                        $("#valor_item").removeAttr("disabled");
                        $(".btn-mini-desconto").removeAttr("disabled");
                    }
                }
                if (desconto.length == 0) DESCONTO = 0;
                $("#valor_desconto").html(convertFloatToMoeda(DESCONTO));
                $("#inp-valor_desconto").val(convertFloatToMoeda(DESCONTO));
                calcTotal();
            }
            swal.close();
            $("#codBarras").focus();
        });
    }
}

function setaAcrescimo() {
    if (total_venda == 0) {
        swal("Erro", "Total da venda é igual a zero", "warning");
    } else {
        swal({
            title: "Valor acrescimo?",
            text: "Ultilize ponto(.) ao invés de virgula!",
            content: "input",
            button: {
                text: "Ok",
                closeModal: false,
                type: "error",
            },
        }).then((v) => {
            if (v) {
                let acrescimo = v;
                if (acrescimo > 0) {
                    DESCONTO = 0;
                    $("#valor_desconto").html(convertFloatToMoeda(DESCONTO));
                }
                let total = total_venda;
                if (acrescimo.substring(0, 1) == "%") {
                    let perc = acrescimo.substring(1, acrescimo.length);
                    VALORACRESCIMO = total * (perc / 100);
                } else {
                    acrescimo = acrescimo.replace(",", ".");
                    VALORACRESCIMO = parseFloat(acrescimo);
                }
                if (acrescimo.length == 0) VALORACRESCIMO = 0;
                VALORACRESCIMO = parseFloat(VALORACRESCIMO);
                $("#valor_acrescimo").html(convertFloatToMoeda(VALORACRESCIMO));
                $("#inp-valor_acrescimo").val(convertFloatToMoeda(VALORACRESCIMO));
                calcTotal();
                $("#codBarras").focus();
            }
            swal.close();
        });
    }
}

$("#cliente select").each(function () {
    let id = $(this).prop("id");
    if (id == "inp-cliente_id") {
        $(this).select2({
            minimumInputLength: 2,
            language: "pt-BR",
            placeholder: "Digite para buscar o cliente",
            width: "100%",
            theme: "bootstrap4",
            dropdownParent: $(this).parent(),
            ajax: {
                cache: true,
                url: path_url + "api/clientes/pesquisa",
                dataType: "json",
                data: function (params) {
                    console.clear();
                    var query = {
                        pesquisa: params.term,
                        empresa_id: $("#empresa_id").val(),
                    };
                    return query;
                },
                processResults: function (response) {
                    var results = [];
                    $.each(response, function (i, v) {
                        var o = {};
                        o.id = v.id;
                        o.text = v.razao_social + " - " + v.cpf_cnpj;
                        o.value = v.id;
                        results.push(o);
                    });
                    return {
                        results: results,
                    };
                },
            },
        });
    }
});

$(document).on("change", "#inp-cliente_id", function () {
    let cliente_id = $(this).val();
    if (!cliente_id) {
        preVendaAtualizarCardCliente('');
        return;
    }
    $.get(path_url + "api/clientes/find/" + cliente_id)
    .done((cliente) => {
        if(cliente && cliente.razao_social) {
            preVendaAtualizarCardCliente(cliente.razao_social);
        }
        if(cliente && cliente.lista_preco){
            $('#lista_id').val(cliente.lista_preco.id);
            setTimeout(() => { todos(); }, 10);
            setTimeout(() => { $("#codBarras").focus(); }, 500);
        }
    })
    .fail((err) => {
        console.log(err);
    });
});

$(document).on("click", ".cliente-venda", function () {
    let cliente_id = $('#inp-cliente_id').val();
    if (cliente_id) {
        $.get(path_url + "api/clientes/find/" + cliente_id)
        .done((cliente) => {
            if (cliente && cliente.razao_social) {
                preVendaAtualizarCardCliente(cliente.razao_social);
            }
        })
        .fail((err) => {
            console.log(err);
        });
    } else {
        preVendaAtualizarCardCliente('');
    }
});

var total_venda = 0;
function calcTotal() {
    var total = 0;
    $(".subtotal-item").each(function () {
        total += convertMoedaToFloat($(this).val());
    });
    total_venda = total + parseFloat(VALORACRESCIMO || 0) - parseFloat(DESCONTO || 0);
    $(".total-venda").html(convertFloatToMoeda(total_venda));
    $('#inp-valor_total').val(convertFloatToMoeda(total_venda));
    $(".total-venda-modal").html("R$ " + convertFloatToMoeda(total_venda));
    $('#inp-valor_integral').val(convertFloatToMoeda(total_venda));

    $('#inp-quantidade').val('');
    $('#inp-valor_unitario').val('');
    $('#inp-produto_id').val('').change();

    calcTotalPayment();
}

$(function () {
    let data = new Date();
    let dataFormatada = (data.getFullYear() + "-" + adicionaZero((data.getMonth() + 1)) + "-" + adicionaZero(data.getDate()));
    // Preenche a data atual apenas em campos vazios (na edição preserva o vencimento salvo)
    $('.data_atual').each(function () {
        if (!$(this).val()) {
            $(this).val(dataFormatada);
        }
    });

    $('#pagamento_multiplo').on('show.bs.modal', function () {
        calcTotal();
        setTimeout(() => {
            calcTotalPayment();
            let restante = total_venda - total_payment;
            if (restante > 0 && (!$("#inp-valor_row").val() || convertMoedaToFloat($("#inp-valor_row").val()) <= 0)) {
                $("#inp-valor_row").val(convertFloatToMoeda(restante));
            }
        }, 100);
    });
});

function adicionaZero(numero) {
    if (numero <= 9)
        return "0" + numero;
    else
        return numero;
}


$(".btn-add-payment").click(() => {
    let tipo_pagamento_row = $("#inp-tipo_pagamento_row").val();
    let vencimento = $("#inp-data_vencimento_row").val();
    let valor_integral_row = $("#inp-valor_row").val();
    let obs_row = $("#inp-observacao_row").val();

    let v = convertMoedaToFloat(valor_integral_row);

    if (v <= 0) {
        beepErro();
        swal("Atenção", "Informe um valor válido para a parcela!", "warning");
        return;
    }

    if (v + total_payment <= total_venda + 0.05) {
        if (vencimento && valor_integral_row && tipo_pagamento_row) {
            let dataRequest = {
                data_vencimento_row: vencimento,
                valor_integral_row: valor_integral_row,
                obs_row: obs_row,
                tipo_pagamento_row: tipo_pagamento_row,
            };

            $.get(path_url + "api/frenteCaixa/linhaParcelaVenda", dataRequest)
            .done((e) => {
                $(".table-payment tbody").append(e);
                beepSucesso();
                calcTotalPayment();

                // Limpa os campos do formulário para permitir adicionar a próxima forma de pagamento
                $("#inp-tipo_pagamento_row").val("").change();
                let restante = total_venda - total_payment;
                $("#inp-valor_row").val(restante > 0 ? convertFloatToMoeda(restante) : "");
                $("#inp-observacao_row").val("");
                let data = new Date();
                let dataFormatada = (data.getFullYear() + "-" + adicionaZero((data.getMonth() + 1)) + "-" + adicionaZero(data.getDate()));
                $("#inp-data_vencimento_row").val(dataFormatada);

                validaButtonSave();
            })
            .fail((e) => {
                console.log(e);
                beepErro();
                swal("Erro", "Não foi possível adicionar a parcela de pagamento!", "error");
            });
        } else {
            beepErro();
            swal(
                "Atenção",
                "Informe o tipo de pagamento, valor e vencimento para continuar!",
                "warning"
            );
        }
    } else {
        beepErro();
        swal(
            "Atenção",
            "A soma das parcelas ultrapassa o valor total da venda!",
            "warning"
        );
    }
});

var total_payment = 0;
function calcTotalPayment() {
    var total = 0;
    $(".table-payment .valor_integral").each(function () {
        total += convertMoedaToFloat($(this).val());
    });
    total_payment = total;
    $(".sum-payment").html("R$ " + convertFloatToMoeda(total));

    let restante = Math.max(0, total_venda - total);
    $(".sum-restante").html("R$ " + convertFloatToMoeda(restante));
    $(".total-venda-modal").html("R$ " + convertFloatToMoeda(total_venda));

    let dif = total_venda - total;
    if (dif <= 0.05 && total > 0) {
        $("#btn-pag_row, .btn-modal-multiplo").removeAttr("disabled");
    }
}

$(document).on("click", ".table-payment .btn-delete-row", function (e) {
    e.preventDefault();
    e.stopPropagation();
    $(this).closest("tr").remove();
    beepSucesso();
    calcTotalPayment();
    let restante = total_venda - total_payment;
    if (restante > 0 && (!$("#inp-valor_row").val() || convertMoedaToFloat($("#inp-valor_row").val()) <= 0)) {
        $("#inp-valor_row").val(convertFloatToMoeda(restante));
    }
    validaButtonSave();
});

$(".btn-modal-multiplo").click(function () {
    calcTotalPayment();
    validaButtonSave();
});

$(document).on('change', '#inp-funcionario_id', function() {
    let funcionario_id = $(this).val();
    if (!funcionario_id) {
        preVendaAtualizarCardFuncionario('');
        validaButtonSave();
        return;
    }
    $.get(path_url + "api/funcionarios/find/", {id: funcionario_id})
    .done((e) => {
        if (e && e.nome) {
            preVendaAtualizarCardFuncionario(e.nome);
        }
        validaButtonSave();
    })
    .fail((e) => {
        console.log(e);
    });
});

$(document).on('click', '.funcionario-venda', function() {
    let funcionario_id = $('#inp-funcionario_id').val()
    if (funcionario_id) {
        $.get(path_url + "api/funcionarios/find/", {id: funcionario_id})
        .done((e) => {
            if (e && e.nome) {
                preVendaAtualizarCardFuncionario(e.nome);
            }
            validaButtonSave();
        })
        .fail((e) => {
            console.log(e);
        });
    } else {
        preVendaAtualizarCardFuncionario('');
        validaButtonSave();
    }
});

$(".modal-funcioario select").each(function () {

    let id = $(this).prop("id");

    if (id == "inp-funcionario_id") {

        $(this).select2({
            minimumInputLength: 2,
            language: "pt-BR",
            placeholder: "Digite para buscar o funcionário",
            theme: "bootstrap4",
            dropdownParent: $(this).parent(),
            ajax: {
                cache: true,
                url: path_url + "api/funcionarios/pesquisa",
                dataType: "json",
                data: function (params) {
                    console.clear();
                    var query = {
                        pesquisa: params.term,
                        empresa_id: $("#empresa_id").val(),
                    };
                    return query;
                },
                processResults: function (response) {
                    var results = [];

                    $.each(response, function (i, v) {
                        var o = {};
                        o.id = v.id;

                        o.text = v.nome;
                        o.value = v.id;
                        results.push(o);
                    });
                    return {
                        results: results,
                    };
                },
            },
        });
    }
});
