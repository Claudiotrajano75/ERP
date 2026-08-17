$(function(){
	// alert('oi')
	intervalVar = setInterval(() => {
		notificacoesEcommerce()
	}, 5000)
})

function notificacoesEcommerce(){

	$('.control-loading').remove("div");
	if($('#modal-notificacao-ecommerce').is(':visible')){
	}else{

		$.ajax({
			url: path_url + "api/notificacoes-ecommerce",
			type: "GET",
			data: {empresa_id: $('#empresa_id').val()},
			global: false,
			success: (success) => {
				if(success.length > 1){
					var audio = new Audio('/audio/song3.wav');
					audio.addEventListener('canplaythrough', function() {
						audio.play();
					});
					$('#modal-notificacao-ecommerce').modal('show')
					$('#modal-notificacao-ecommerce .modal-body').html(success)
				}
			},
			error: (err) => {
				if(err.status != 401){
					// swal("erro", "erro ao buscar notificações", "error")
				}
			}
		});
	}
	setTimeout(() => {
		$('.control-loading').add('<div class="modal-loading loading-class"></div>')
	}, 100)
}

$('body').on('click', '.btn-set-status', function () {
	let id = $(this).prev().val()

	$.post(path_url + "api/notificacoes-set-status", {id: id})
	.done((success) => {
		$('#modal-notificacao').modal('hide')
		if(success.tipo == 'fechar_mesa'){
			if(success.pedido){
				location.href = '/pedidos-cardapio/'+success.pedido.id
			}
		}

	})
	.fail((err) => {
		console.log(err)
	})
});