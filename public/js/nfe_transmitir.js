// --- Funções de Controle do Overlay de Processamento SEFAZ ---
function nfeMostrarProcessingOverlay(titulo, msg, icone) {
	var $overlay = $('#pdv_processing_overlay');
	if ($overlay.length === 0) return;

	$('#pdv_processing_title').text(titulo || 'Transmitindo NFe (Modelo 55)');
	$('#pdv_processing_msg').text(msg || 'Comunicando com a SEFAZ... Por favor, aguarde.');
	
	var $icon = $('#pdv_processing_icon');
	$icon.attr('class', icone || 'ri-file-shield-2-line');
	
	var $iconBox = $('#pdv_processing_icon_box');
	$iconBox.removeClass('success');
	
	$overlay.removeClass('d-none');
}

function nfeAtualizarProcessingOverlay(titulo, msg, icone, isSuccess) {
	if (titulo) $('#pdv_processing_title').text(titulo);
	if (msg) $('#pdv_processing_msg').text(msg);
	if (icone) $('#pdv_processing_icon').attr('class', icone);
	if (isSuccess) {
		$('#pdv_processing_icon_box').addClass('success');
	}
}

function nfeEsconderProcessingOverlay() {
	$('#pdv_processing_overlay').addClass('d-none');
}

function transmitir(id){
	console.clear()
	nfeMostrarProcessingOverlay('Transmitindo NFe', 'Comunicando com os servidores da SEFAZ... Por favor, aguarde.', 'ri-file-shield-2-line');
	
	$.post(path_url + "api/nfe_painel/emitir", {
		id: id,
	})
	.done((success) => {
		nfeAtualizarProcessingOverlay('NFe Autorizada!', 'Nota Fiscal emitida com sucesso pela SEFAZ.', 'ri-checkbox-circle-fill', true);
		setTimeout(() => {
			nfeEsconderProcessingOverlay();
			swal("Sucesso", "NFe emitida " + success.recibo + " - chave: [" + success.chave + "]", "success")
			.then(() => {
				window.open(path_url + 'nfe/imprimir/' + id, "_blank")
				setTimeout(() => {
					location.reload()
				}, 100)
			})
		}, 600);
	})
	.fail((err) => {
		nfeEsconderProcessingOverlay();
		console.log(err)
		try{
			if(err.responseJSON.error){
				let o = err.responseJSON.error.protNFe.infProt
				swal("Algo deu errado", o.cStat + " - " + o.xMotivo, "error")
				.then(() => {
					location.reload()
				})
			}else{
				swal("Algo deu errado", err[0], "error")
			}
		}catch{
			if(err.responseJSON.message){
				swal("Algo deu errado", err.responseJSON.message, "error")
				.then(() => {
					location.reload()
				})
			}else{
				try{
					if(err.responseJSON.xMotivo){
						swal("Algo deu errado", err.responseJSON.xMotivo, "error")
						.then(() => {
							location.reload()
						})
					}else{
						if(err.responseJSON.error){
							swal("Algo deu errado", err.responseJSON.error, "error")
							.then(() => {
								location.reload()
							})
						}else{
							swal("Algo deu errado", err.responseJSON, "error")
							.then(() => {
								location.reload()
							})
						}
					}
				}catch{
					swal("Algo deu errado", err.responseJSON[0], "error")
					.then(() => {
						location.reload()
					})
				}
			}
		}
	})
}

var IDNFE = null
function cancelar(id, numero){
	IDNFE = id
	$('.ref-numero').text(numero)
	$('#modal-cancelar').modal('show')
}

function imprimir(id, numero){
	IDNFE = id
	$('.ref-numero').text(numero)
	$('#modal-print').modal('show')
}

function corrigir(id, numero){
	IDNFE = id
	$('.ref-numero').text(numero)
	$('#modal-corrigir').modal('show')
}

function gerarDanfe(tipo){
	if(tipo == 'danfe'){
		window.open('/nfe/imprimir/'+IDNFE)
	}else if(tipo == 'simples'){
		window.open('/nfe/danfe-simples/'+IDNFE)
	}else{
		window.open('/nfe/danfe-etiqueta/'+IDNFE)
	}
	$('#modal-print').modal('hide')
}

$('#btn-cancelar').click(() => {
	if(IDNFE != null){
		$('#modal-cancelar').modal('hide');
		nfeMostrarProcessingOverlay('Cancelando NFe', 'Enviando evento de cancelamento para a SEFAZ...', 'ri-close-circle-line');
		
		$.post(path_url + "api/nfe_painel/cancelar", {
			id: IDNFE,
			motivo: $('#inp-motivo-cancela').val()
		})
		.done((success) => {
			nfeEsconderProcessingOverlay();
			swal("Sucesso", "NFe cancelada " + success, "success")
			.then(() => {
				window.open(path_url + 'nfe/imprimir-cancela/' + IDNFE, "_blank")
				setTimeout(() => {
					location.reload()
				}, 100)
			})
		})
		.fail((err) => {
			nfeEsconderProcessingOverlay();
			console.log(err)
			swal("Algo deu errado", err.responseJSON, "error")
		})
	}else{
		swal("Erro", "Nota não selecionada", "error")
	}
})

$('#btn-corrigir').click(() => {
	if(IDNFE != null){
		$('#modal-corrigir').modal('hide');
		nfeMostrarProcessingOverlay('Carta de Correção (CC-e)', 'Transmitindo evento CC-e para a SEFAZ...', 'ri-file-warning-line');
		
		$.post(path_url + "api/nfe_painel/corrigir", {
			id: IDNFE,
			motivo: $('#inp-motivo-corrigir').val()
		})
		.done((success) => {
			nfeEsconderProcessingOverlay();
			swal("Sucesso", "NFe corrigida " + success, "success")
			.then(() => {
				window.open(path_url + 'nfe/imprimir-correcao/' + IDNFE, "_blank")
				setTimeout(() => {
					location.reload()
				}, 100)
			})
		})
		.fail((err) => {
			nfeEsconderProcessingOverlay();
			console.log(err)
			swal("Algo deu errado", err.responseJSON, "error")
		})
	}else{
		swal("Erro", "Nota não selecionada", "error")
	}
})

function consultar(id, numero){
	nfeMostrarProcessingOverlay('Consultando NFe', 'Verificando status da NFe na SEFAZ...', 'ri-search-eye-line');
	
	$.post(path_url + "api/nfe_painel/consultar", {
		id: id,
	})
	.done((success) => {
		nfeEsconderProcessingOverlay();
		console.log(success)
		swal("Sucesso", success, "success")
		.then(() => {
			location.reload()
		})
	})
	.fail((err) => {
		nfeEsconderProcessingOverlay();
		console.log(err)
		swal("Algo deu errado", err.responseJSON, "error")
	})
}

function enviarEmail(id, numero){
	$('.ref-numero').text(numero)
	$('#modal-email').modal('show')
	$('#inp-danfe').prop('checked', 1)
	$('#inp-xml').prop('checked', 1)
	IDNFE = id

	$.get(path_url + "api/nfe_painel/find", {
		id: id,
	})
	.done((success) => {
		$('#inp-email').val(success.cliente.email)
	})
	.fail((err) => {
		// console.log(err)
	})
}

$('#btn-enviar-email').click(() => {
	let email = $('#inp-email').val()
	let danfe = $('#inp-danfe').is(':checked') ? 1 : 0
	let xml = $('#inp-xml').is(':checked') ? 1 : 0
	let data = {
		email: email,
		id: IDNFE,
		danfe: danfe,
		xml: xml,
	}

	$('#modal-email').modal('hide');
	nfeMostrarProcessingOverlay('Enviando E-mail', 'Preparando anexos (DANFE/XML) e enviando...', 'ri-mail-send-line');

	$.post(path_url + "api/nfe_painel/send-mail", data)
	.done((success) => {
		nfeEsconderProcessingOverlay();
		swal("Sucesso", "Email enviado com sucesso!", "success")
	})
	.fail((err) => {
		nfeEsconderProcessingOverlay();
		console.log(err)
		swal("Erro", err.responseJSON, "error")
	})
})

