$(function(){
	$('button#botaoEditar').on("click", function(e){
		e.preventDefault();

		var campos = new FormData($("form#formEditarUsuario").get(0));
		campos.append("editar", "OK");
		campos.append("JSON", 1);

		//Validação front-end aqui antes de enviar o ajax
		$.ajax({
			url: 'php/user-edit.php',
			type: 'POST',
			enctype: 'multipart/form-data',
			contentType : false,
			processData : false,
			data: campos,

			success: function(retornoPHP){
				retornoJSON = JSON.parse(retornoPHP);

				if(retornoJSON['erro'])
				{	
					if(retornoJSON['campos'])
					{
						if(retornoJSON['campos'].login) {
							$('#erroLogin').html(retornoJSON['campos'].login);
						}else{
							$('#erroLogin').html('');
						}

						if(retornoJSON['campos'].email) {
							$('#erroEmail').html(retornoJSON['campos'].email);
						}else{
							$('#erroEmail').html('');
						}

						if(retornoJSON['campos'].senhaAtual) {
							$('#erroSenha').html(retornoJSON['campos'].senhaAtual);
						}else{
							$('#erroSenha').html('');
						}

						if(retornoJSON['campos'].imagem) {
							$('#erroImagem').html(retornoJSON['campos'].imagem);
						}else{
							$('#erroImagem').html('');
						}
					}
					else
					{
						if(retornoJSON['mensagem']){
							$('#erroBCD').html(retornoJSON['mensagem']);
						}else{
							$('#erroBCD').html('');
						}
					}					
				}
				else
				{
					if(retornoJSON['sucesso']){
						window.location = 'index.php';
					}
				}
			},

			error: function(){
				$('#erroBCD').html('Ocorreu um erro durante a solicitação');
			}
		});
	});
});