function transmitir(id){
	console.clear()
	$.post(path_url + "api/nfce_painel/emitir", {
		id: id,
	})
	.done((success) => {
		console.log(success)
		var msg = (success.recibo == '' && success.contigencia) 
			? "NFCe emitida em contingência - chave: [" + success.chave + "]" 
			: "NFCe emitida " + success.recibo + " - chave: [" + success.chave + "]";

		swal("Sucesso", msg, "success")
		.then(() => {
			var pdfUrl = path_url + 'nfce/imprimir/' + id;
			var redirectFn = function() {
				location.reload();
			};

			if (typeof PrintThermal !== 'undefined') {
				PrintThermal.imprimir('nfce', id, pdfUrl, redirectFn);
			} else {
				window.open(pdfUrl, "_blank");
				setTimeout(redirectFn, 600);
			}
		})
	})
	.fail((err) => {
		console.log(err)
		if(err.responseJSON && err.responseJSON.message){
			swal("Algo deu errado", err.responseJSON.message, "error")
			.then(() => {
				location.reload()
			})
		}else{
			swal("Algo deu errado", err.responseJSON || "Erro ao comunicar com SEFAZ", "error")
		}
	})
}

function transmitirContigencia(id){
	console.clear()
	$.post(path_url + "api/nfce_painel/transmitir-contigencia", {
		id: id,
	})
	.done((success) => {
		console.log(success)
		var msg = (success.recibo == '' && success.contigencia) 
			? "NFCe emitida em contingência - chave: [" + success.chave + "]" 
			: "NFCe emitida " + success.recibo + " - chave: [" + success.chave + "]";

		swal("Sucesso", msg, "success")
		.then(() => {
			var pdfUrl = path_url + 'nfce/imprimir/' + id;
			var redirectFn = function() {
				location.reload();
			};

			if (typeof PrintThermal !== 'undefined') {
				PrintThermal.imprimir('nfce', id, pdfUrl, redirectFn);
			} else {
				window.open(pdfUrl, "_blank");
				setTimeout(redirectFn, 600);
			}
		})
	})
	.fail((err) => {
		console.log(err)
		if(err.responseJSON.message){
			swal("Algo deu errado", err.responseJSON.message, "error")
			.then(() => {
				location.reload()
			})
		}else{
			swal("Algo deu errado", err.responseJSON, "error")
		}
	})
}

var IDNFE = null
function cancelar(id, numero){
	IDNFE = id
	$('.ref-numero').text(numero)
	$('#modal-cancelar').modal('show')
}

function corrigir(id, numero){
	IDNFE = id
	$('.ref-numero').text(numero)
	$('#modal-corrigir').modal('show')
}

$('#btn-cancelar').click(() => {
	if(IDNFE != null){
		$.post(path_url + "api/nfce_painel/cancelar", {
			id: IDNFE,
			motivo: $('#inp-motivo-cancela').val()
		})
		.done((success) => {
			swal("Sucesso", "NFe cancelada " + success, "success")
			.then(() => {
				location.reload()
			})
		})
		.fail((err) => {
			console.log(err)

			swal("Algo deu errado", err.responseJSON, "error")

		})
	}else{
		swal("Erro", "Nota não selecionada", "error")
	}
})


function consultar(id, numero){
	$.post(path_url + "api/nfce_painel/consultar", {
		id: id,
	})
	.done((success) => {
		swal("Sucesso", success, "success")
	})
	.fail((err) => {
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

	$.post(path_url + "api/nfce_painel/send-mail", data)
	.done((success) => {
		// console.log(success)
		swal("Sucesso", "Email enviado!", "success")
		$('#modal-email').modal('hide')
	})
	.fail((err) => {
		// console.log(err)
		swal("Erro", err.responseJSON, "error")
	})
})