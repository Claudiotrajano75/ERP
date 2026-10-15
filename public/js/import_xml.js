$("tbody .produto_id").select2({
    minimumInputLength: 2,
    language: "pt-BR",
    placeholder: "Digite para buscar o produto",
    width: "100%",
    ajax: {
        cache: true,
        url: path_url + "api/produtos",
        dataType: "json",
        data: function (params) {
            let empresa_id = $('#empresa_id').val()
            console.clear();
            var query = {
                pesquisa: params.term,
                empresa_id: empresa_id
            };
            return query;
        },
        processResults: function (response) {
            var results = [];
            
            $.each(response, function (i, v) {
                var o = {};
                o.id = v.id;
                if(v.codigo_variacao){
                    o.codigo_variacao = v.codigo_variacao
                }

                o.text = v.nome;
                o.text += ' R$ ' + convertFloatToMoeda(v.valor_compra);
                
                if(v.codigo_barras){
                    o.text += ' [' + v.codigo_barras  + ']';
                }
                o.value = v.id;
                results.push(o);
            });
            return {
                results: results,
            };
        },
    },
});

$(document).on("change", ".produto_id", function () {
    let produto_id = $(this).val()
    $cadProd = $(this).prev().prev().prev().prev().prev();
    $label = $(this).next().next();
    console.log($label)
    $cadProd.val('0')
    $label.text('')
});

// Comportamento inteligente para Conversão de Estoque e Unidade
$(document).on("input change", ".inp-conversao-estoque", function () {
    let $input = $(this);
    let conversao = parseInt($input.val()) || 1;
    let $tr = $input.closest('tr');
    let $unidade = $tr.find('.unidade_item');
    let unidadeOriginal = ($input.data('unidade-original') || '').toString().toUpperCase().trim();
    let valorBruto = parseFloat($input.data('valor-unitario')) || 0;
    let lucro = parseFloat($input.data('lucro')) || 0;

    // Se a conversão for maior que 1, sugere unidade UN caso esteja em Dz, Cx, etc.
    if (conversao > 1) {
        let unidadesEmbalagem = ['DZ', 'DUZIA', 'DÚZIA', 'DUZIAS', 'CX', 'CAIXA', 'FD', 'FARDO', 'PCT', 'PACOTE'];
        let unidadeAtual = ($unidade.val() || '').toUpperCase().trim();
        
        if (unidadeAtual === '' || unidadeAtual === unidadeOriginal || unidadesEmbalagem.includes(unidadeAtual)) {
            $unidade.val('UN');
        }

        // Recalcula o valor de venda sugerido proporcional à unidade fracionada
        if (valorBruto > 0) {
            let custoUnitario = valorBruto / conversao;
            let vendaSugerida = custoUnitario + (custoUnitario * lucro / 100);
            if (typeof convertFloatToMoeda === "function") {
                $tr.find('.inp-valor-venda').val(convertFloatToMoeda(vendaSugerida));
            }
        }
    } else if (conversao === 1) {
        // Se voltou para 1 e a unidade original era de atacado, restaura ou mantém
        if (unidadeOriginal && $unidade.val() === 'UN') {
            $unidade.val(unidadeOriginal);
        }
        if (valorBruto > 0) {
            let vendaSugerida = valorBruto + (valorBruto * lucro / 100);
            if (typeof convertFloatToMoeda === "function") {
                $tr.find('.inp-valor-venda').val(convertFloatToMoeda(vendaSugerida));
            }
        }
    }
});

// Forçar letras maiúsculas na unidade
$(document).on("input", ".unidade_item", function () {
    $(this).val($(this).val().toUpperCase());
});


