var IDNFE = null;

function transmitir(id) {
    console.clear()
    $("#btn-consulta-cnpj span").removeClass("d-none");
    let empresa_id = $("#empresa_id").val();

    $.post(path_url + "api/mdfe_painel/emitir", {
        id: id,
        empresa_id: empresa_id,
    })
        .done((success) => {
            swal("Sucesso", "MDFe emitida " + success, "success")
                .then(() => {
                    window.open(path_url + 'mdfe/imprimir/' + id, "_blank")
                    setTimeout(() => {
                        location.reload()
                    }, 100)
                })
        })
        .fail((err) => {
            console.log(err)
            if (err.status == 403) {
                swal("Algo deu errado", err.responseJSON, "error")
            } else {
                try {
                    swal("Algo deu errado", err.responseJSON, "error")
                } catch {
                    swal("Algo deu errado", err.responseText, "error")
                }
            }
        })
}

function consultar(id, numero) {
    if (id) {
        let empresa_id = $("#empresa_id").val() || "";

        swal({
            title: "Consultando SEFAZ...",
            text: "Aguarde a resposta dos servidores da SEFAZ.",
            icon: "info",
            buttons: false,
            closeOnClickOutside: false,
            closeOnEsc: false
        });

        $.post(path_url + "api/mdfe_painel/consultar", {
            id: id,
            empresa_id: empresa_id,
        })
            .done((success) => {
                let motivo = "MDF-e consultada com sucesso!";
                let chave = "";
                let status = "";
                let protocolo = "";
                let eventoInfo = "";

                if (success.protMDFe && success.protMDFe.infProt) {
                    let infProt = success.protMDFe.infProt;
                    motivo = infProt.xMotivo || motivo;
                    chave = infProt.chMDFe || "";
                    status = infProt.cStat || "";
                    protocolo = infProt.nProt || "";
                } else if (success.xMotivo) {
                    motivo = success.xMotivo;
                    status = success.cStat || "";
                }

                if (success.procEventoMDFe && success.procEventoMDFe.retEventoMDFe) {
                    let infEvento = success.procEventoMDFe.retEventoMDFe.infEvento;
                    if (infEvento && infEvento.xEvento) {
                        eventoInfo = "\nÚltimo Evento: " + infEvento.xEvento + " (" + (infEvento.xMotivo || "") + ")";
                    }
                }

                let mensagemFinal = "Status SEFAZ: [" + status + "] " + motivo;
                if (protocolo) {
                    mensagemFinal += "\nProtocolo: " + protocolo;
                }
                if (eventoInfo) {
                    mensagemFinal += eventoInfo;
                }

                swal("Consulta SEFAZ", mensagemFinal, "success");
            })
            .fail((err) => {
                console.log(err);
                let msg = "Não foi possível consultar na SEFAZ.";
                try {
                    if (err.responseJSON) {
                        msg = typeof err.responseJSON === 'string' ? err.responseJSON : (err.responseJSON.mensagem || JSON.stringify(err.responseJSON));
                    }
                } catch (e) { }
                swal("Erro na Consulta", msg, "error");
            });
    } else {
        swal("Alerta", "Selecione um MDF-e para consultar!", "warning");
    }
}

function cancelar(id, numero) {
    IDNFE = id
    $('.ref-numero').text(numero)
    $('#modal-cancelar').modal('show')
}


$('#btn-cancelar').click(() => {
    let empresa_id = $("#empresa_id").val();
    let motivo = $('#inp-motivo-cancela').val()
    if (motivo.length >= 15) {
        $.post(path_url + "api/mdfe/cancelar", {
            id: IDNFE,
            empresa_id: empresa_id,
            motivo: motivo
        })
            .done((success) => {
                let infEvento = success.infEvento
                swal("Sucesso", "[" + infEvento.cStat + "] " + infEvento.xMotivo, "success")
                    .then(() => {
                        // window.open(path_url + 'mdfe/imprimir-cancela/' + id, "_blank")
                        setTimeout(() => {
                            location.reload()
                        }, 100)
                    })

            })
            .fail((err) => {
                console.log(err)
                try {
                    swal("Algo deu errado", err.responseJSON.infEvento.xMotivo, "error")
                } catch {
                    swal("Algo deu errado", err.responseJSON, "error")
                }
            })
    } else {
        swal("Alerta", "Informe no mínimo 15 caracteres", "warning")
    }
})

var CHAVE_ENCERRAR = null;
var PROTOCOLO_ENCERRAR = null;

function encerrar(chave, protocolo) {
    CHAVE_ENCERRAR = chave;
    PROTOCOLO_ENCERRAR = protocolo;
    if ($('#modal-encerrar').length) {
        $('#modal-encerrar').modal('show');
    }
}

$(document).on('shown.bs.modal', '#modal-encerrar', function () {
    if ($('#municipio_encerramento').hasClass('select2-hidden-accessible')) {
        $('#municipio_encerramento').select2('destroy');
    }
    $('#municipio_encerramento').select2({
        dropdownParent: $('#modal-encerrar'),
        width: '100%',
        placeholder: 'Selecione o município de encerramento (opcional)'
    });
});

$(document).on('click', '#btn-encerrar', function () {
    let empresa_id = $("#empresa_id").val();
    let municipio = $('#municipio_encerramento').val();

    if (!CHAVE_ENCERRAR || !PROTOCOLO_ENCERRAR) {
        swal("Alerta", "Chave ou protocolo não identificados!", "warning");
        return;
    }

    let $btn = $(this);
    $btn.prop('disabled', true).html('<i class="ri-loader-4-line ri-spin"></i> Encerrando na SEFAZ...');

    $.ajax({
        url: path_url + 'mdfe/encerrar',
        type: 'POST',
        data: {
            _token: $('meta[name="csrf-token"]').attr('content'),
            chave: CHAVE_ENCERRAR,
            protocolo: PROTOCOLO_ENCERRAR,
            municipio_encerramento: municipio,
            empresa_id: empresa_id
        },
        success: function (res) {
            swal("Sucesso", res.mensagem || "MDF-e encerrada com sucesso na SEFAZ!", "success")
                .then(() => {
                    location.reload();
                });
        },
        error: function (xhr) {
            $btn.prop('disabled', false).html('<i class="ri-stop-circle-line"></i> Confirmar Encerramento');
            let msg = "Erro ao encerrar MDF-e na SEFAZ.";
            try {
                if (xhr.responseJSON && xhr.responseJSON.mensagem) {
                    msg = xhr.responseJSON.mensagem;
                }
            } catch (e) { }
            swal("Erro", msg, "error");
        }
    });
});
