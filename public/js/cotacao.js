function getPathUrl() {
    if (typeof path_url !== "undefined" && path_url) {
        return path_url;
    }
    var prot = window.location.protocol;
    var host = window.location.host;
    return prot + "//" + host + "/";
}

function initSelect2Produto($elements) {
    var baseUrl = getPathUrl();

    $elements.each(function () {
        var $select = $(this);

        // Se já foi inicializado estaticamente pelo template, destrói para reaplicar com AJAX
        if ($select.hasClass("select2-hidden-accessible") || $select.data('select2')) {
            try {
                $select.select2('destroy');
            } catch (e) {}
        }

        $select.select2({
            minimumInputLength: 2,
            language: "pt-BR",
            placeholder: "Digite para buscar o produto",
            width: "100%",
            theme: "bootstrap4",
            ajax: {
                cache: true,
                url: baseUrl + "api/produtos",
                dataType: "json",
                delay: 250,
                data: function (params) {
                    var empId = $('#empresa_id').val();
                    var usuId = $('#usuario_id').val();
                    return {
                        pesquisa: params.term,
                        empresa_id: empId,
                        usuario_id: usuId
                    };
                },
                processResults: function (response) {
                    var results = [];
                    $.each(response, function (i, v) {
                        var o = {};
                        o.id = v.id;
                        o.text = v.nome;
                        if (v.codigo_barras) {
                            o.text += " [" + v.codigo_barras + "]";
                        }
                        results.push(o);
                    });
                    return {
                        results: results
                    };
                }
            }
        });
    });
}

function initSelect2Fornecedor() {
    var baseUrl = getPathUrl();

    $(".fornecedor_id").each(function () {
        var $select = $(this);

        if ($select.hasClass("select2-hidden-accessible") || $select.data('select2')) {
            try {
                $select.select2('destroy');
            } catch (e) {}
        }

        $select.select2({
            minimumInputLength: 2,
            language: "pt-BR",
            placeholder: "Digite para buscar o fornecedor",
            theme: "bootstrap4",
            ajax: {
                cache: true,
                url: baseUrl + "api/fornecedores/pesquisa",
                dataType: "json",
                delay: 250,
                data: function (params) {
                    return {
                        pesquisa: params.term,
                        empresa_id: $("#empresa_id").val()
                    };
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
                        results: results
                    };
                }
            }
        });
    });
}

$(document).ready(function () {
    // Pequeno timeout para garantir execução após qualquer inicializador genérico do template
    setTimeout(function () {
        initSelect2Produto($("select.produto_id"));
        initSelect2Fornecedor();
    }, 50);
});

// Adição de nova linha na grade de produtos da cotação
$(document).on("click", ".btn-add-tr-item", function (e) {
    e.preventDefault();

    var $table = $(".table-produtos");
    if (!$table.length) {
        $table = $(".table-dynamic");
    }

    var hasEmpty = false;
    $table.find("tbody tr.dynamic-form").each(function () {
        var prod = $(this).find("select.produto_id").val();
        var qtd = $(this).find("input.qtd").val();
        if (!prod || !qtd || $.trim(qtd) === "") {
            hasEmpty = true;
        }
    });

    if (hasEmpty) {
        if (typeof swal === "function") {
            swal(
                "Atenção",
                "Preencha o produto e a quantidade antes de adicionar novos itens.",
                "warning"
            );
        } else {
            alert("Preencha o produto e a quantidade antes de adicionar novos itens.");
        }
        return;
    }

    var newTrHtml = `
        <tr class="dynamic-form">
            <td>
                <select required class="form-control produto_id form-select" name="produto_id[]">
                </select>
            </td>
            <td>
                <input required class="form-control qtd text-center" type="tel" name="quantidade[]" placeholder="0,00">
            </td>
            <td class="text-center">
                <button type="button" class="act-btn act-del btn-remove-tr" title="Remover Produto">
                    <i class="ri-delete-bin-line"></i>
                </button>
            </td>
        </tr>
    `;

    var $newTr = $(newTrHtml);
    $table.find("tbody").append($newTr);
    initSelect2Produto($newTr.find("select.produto_id"));
});

$('form#form-cotacao, form').on("submit", function () {
    if ($(this).find('#btn-save-cotacao').length) {
        $('.btn-salvar').attr('disabled', true);
    }
});
