/**
 * Emissão automática da MDF-e: envia o XML da NF-e e pré-preenche o formulário.
 */

$(function () {
    if (!$('#inp-mdfe-xml').length) {
        return
    }

    iniciarImportacaoXml()
})

function iniciarImportacaoXml() {
    let $drop = $('#mdfe-xml-drop')

    $('#inp-mdfe-xml').on('change', function () {
        enviarXmlsMdfe(this.files)
        $(this).val('')
    })

    $drop.on('dragenter dragover', function (e) {
        e.preventDefault()
        $(this).addClass('is-dragover')
    })

    $drop.on('dragleave dragend drop', function (e) {
        e.preventDefault()
        $(this).removeClass('is-dragover')
    })

    $drop.on('drop', function (e) {
        enviarXmlsMdfe(e.originalEvent.dataTransfer.files)
    })
}

function enviarXmlsMdfe(arquivos) {
    if (!arquivos || arquivos.length == 0) {
        return
    }

    let dados = new FormData()
    for (let i = 0; i < arquivos.length; i++) {
        dados.append('xml[]', arquivos[i])
    }

    $.ajax({
        url: path_url + 'mdfe/importar-xml',
        type: 'POST',
        data: dados,
        processData: false,
        contentType: false,
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
            'Accept': 'application/json'
        },
        beforeSend: function () {
            $('#mdfe-xml-drop').addClass('is-loading')
            $('#mdfe-xml-resumo').html(resumoXml('ri-loader-4-line ri-spin', 'Lendo o XML da NF-e...'))
        }
    })
        .done(function (res) {
            preencherMdfePeloXml(res)
        })
        .fail(function (err) {
            let mensagem = 'Não foi possível ler o XML enviado.'

            if (err.responseJSON) {
                mensagem = err.responseJSON.erro || err.responseJSON.message || mensagem
            }

            $('#mdfe-xml-resumo').html(resumoXml('ri-error-warning-line', 'Não foi possível ler o XML'))
            swal('Atenção', mensagem, 'warning')
        })
        .always(function () {
            $('#mdfe-xml-drop').removeClass('is-loading')
        })
}

function preencherMdfePeloXml(res) {
    $.each(res.campos || {}, function (campo, valor) {
        definirCampoMdfe(campo, valor)
    })

    preencherLinhasSelect('select[name="municipiosCarregamento[]"]', res.municipios_carregamento)
    preencherLinhasSelect('select[name="uf[]"]', res.percurso)

    adicionarLinhasDescarregamento(res.descarregamentos || [])

    mostrarResumoXml(res)
    mostrarAvisosXml(res.avisos || [])

    if (typeof validateButtonSave === 'function') {
        validateButtonSave()
    }
}

function definirCampoMdfe(campo, valor) {
    let $campo = $('#inp-' + campo)

    if (!$campo.length) {
        return
    }

    if ($campo.is('select')) {
        $campo.val(valor).trigger('change')
    } else {
        $campo.val(valor)
    }
}

/**
 * Preenche os selects que funcionam como tabela dinâmica (municípios e percurso),
 * clonando a primeira linha quando houver mais de um valor.
 */
function preencherLinhasSelect(seletor, valores) {
    valores = valores || []

    if (valores.length == 0) {
        return
    }

    let $selects = $(seletor)

    for (let i = 0; i < valores.length; i++) {
        if (i == 0 && $selects.length > 0) {
            $selects.eq(0).val(valores[i]).trigger('change')
            continue
        }

        adicionarLinhaSelect(seletor, valores[i])
    }
}

function adicionarLinhaSelect(seletor, valor) {
    let $origem = $(seletor).first().closest('tr')

    if (!$origem.length) {
        return
    }

    // Mesmo procedimento do btn-add-tr global: destrói o select2 antes de clonar a linha
    $origem.find('select.select2').select2('destroy')

    let $nova = $origem.clone()
    $nova.find('input, select').val('')
    $origem.parent().append($nova)

    let $select = $nova.find('select')

    if ($select.hasClass('select2')) {
        $select.select2({ language: 'pt-BR', width: '100%' })
    }

    $select.val(valor).trigger('change')

    let $selectOrigem = $origem.find('select.select2')

    if ($selectOrigem.length && !$selectOrigem.hasClass('select2-hidden-accessible')) {
        $selectOrigem.select2({ language: 'pt-BR', width: '100%' })
    }
}

/**
 * Cria uma linha em "Dados do Descarregamento" para cada NF-e lida,
 * reaproveitando o endpoint que o formulário já usa.
 */
function adicionarLinhasDescarregamento(linhas) {
    let $tbody = $('.table-descarregamento tbody')

    if (!$tbody.length || linhas.length == 0) {
        return
    }

    $tbody.html('')

    let fila = $.Deferred().resolve().promise()

    linhas.forEach(function (linha) {
        fila = fila.then(function () {
            return $.get(path_url + 'api/mdfe/linhaInfoDescarregamento', {
                tp_und_transp: linha.tp_und_transp,
                id_und_transp: linha.id_und_transp || '',
                quantidade_rateio: linha.quantidade_rateio,
                quantidade_rateio_carga: linha.quantidade_rateio_carga || '',
                chave_nfe: linha.chave_nfe,
                chave_cte: linha.chave_cte || '',
                municipio_descarregamento: linha.municipio_descarregamento,
                'lacres_transporte[]': [''],
                'lacres_unidade[]': ['']
            }).done(function (html) {
                $('.table-descarregamento tbody').append(html)
            }).fail(function () {
                swal('Atenção', 'Não foi possível montar a linha de descarregamento da NF-e ' + linha.numero + '.', 'warning')
            })
        })
    })

    return fila
}

function mostrarResumoXml(res) {
    let campos = res.campos || {}
    let html = ''

    html += '<div class="d-flex align-items-center justify-content-between mb-2">'
    html += '<div class="d-flex align-items-center gap-2"><i class="ri-checkbox-circle-fill text-success fs-18"></i>'
    html += '<span class="fw-bold" style="font-size:13px;color:#1f2937;">' + res.notas + ' NF-e lida(s)</span></div>'
    html += '<span class="fs-12 text-muted">' + (res.descarregamentos || []).length + ' descarregamento(s)</span>'
    html += '</div>'

    html += '<div class="xml-total">Carga: R$ ' + (campos.valor_carga || '0,00')
    html += ' &middot; ' + (campos.quantidade_carga || '0,000') + ' ' + (campos.unidade_medida || 'KG') + '</div>'

    $.each(res.chaves || [], function (i, chave) {
        html += '<div class="xml-chave"><i class="ri-file-text-line me-1"></i>' + formatarChaveXml(chave) + '</div>'
    })

    $('#mdfe-xml-resumo').html(html)
}

function mostrarAvisosXml(avisos) {
    let $avisos = $('.mdfe-xml-avisos')

    if (avisos.length == 0) {
        $avisos.html('')
        return
    }

    let html = ''

    $.each(avisos, function (i, aviso) {
        html += '<div class="alert alert-warning border-0 py-2 px-3 mb-2 fs-12 d-flex align-items-start gap-2">'
        html += '<i class="ri-alert-line mt-1"></i><span>' + aviso + '</span></div>'
    })

    $avisos.html(html)
}

function resumoXml(icone, texto) {
    return '<div class="d-flex align-items-center gap-2 text-muted">' +
        '<i class="' + icone + ' fs-18"></i><span class="fs-12">' + texto + '</span></div>'
}

function formatarChaveXml(chave) {
    return (chave || '').replace(/(\d{4})(?=\d)/g, '$1 ')
}
