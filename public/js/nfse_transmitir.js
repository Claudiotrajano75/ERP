function transmitir(id){
	console.clear()
	$.post(path_url + "api/nfse/transmitir", {
		id: id,
	})
	.done((success) => {

		swal("Sucesso", success.mensagem, "success").then(() => {
			location.reload()
		})

	}).fail((err) => {

		if(err.status == 404){
			let json = err.responseJSON

			let motivo = json.mensagem
			let erros = json.erros

			if(erros){
				erros.map((e) => {
					motivo += e.erro
				})
			}

			let icon = "error"
			let title = "Algo deu errado"

			swal({
				title: title,
				text: motivo,
				icon: icon,
				buttons: ["Fechar"],
				dangerMode: true,
			})
		}else{
			swal("Algo deu errado", err.responseJSON, "error")
		}
	})
}

function consultar(id){
	console.clear()
	$.post(path_url + "api/nfse/consultar", {
		id: id,
	})
	.done((success) => {
		swal("Sucesso", success.mensagem, "success").then(() => {
			window.open(success.link_pdf)
		})
	}).fail((err) => {
		try{
			swal("Erro", err.responseJSON.mensagem, "error").then(() => {
				location.reload()
			})
		}catch{
			swal("Erro", "Erro consulte o console", "error")
		}
	})
}

$('#btn-cancelar').click(() => {
	console.clear()

	let motivo = $('#inp-motivo-cancela').val()
	if (!motivo || motivo.trim() === '') {
		swal("Aviso", "Por favor, preencha o motivo do cancelamento.", "warning")
		return
	}

	$.post(path_url + "api/nfse/cancelar", {
		id: IDNFSE,
		motivo: motivo
	})
	.done((res) => {
		$('#modal-cancelar').modal('hide')
		swal("Sucesso", res.mensagem || "NFS-e cancelada com sucesso!", "success").then(() => {
			location.reload()
		})
	}).fail((err) => {
		let msg = "Erro ao cancelar NFS-e"
		if (err.responseJSON && err.responseJSON.mensagem) {
			msg = err.responseJSON.mensagem
		}
		swal("Erro", msg, "error")
	})
})

var IDNFSE = null
function cancelar(id, numero){
	IDNFSE = id
	$('.ref-numero').text(numero)
	$('#modal-cancelar').modal('show')
}
