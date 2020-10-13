$(function(){

	/*Apresenta o Gif de Carregamento*/
	$(document).ajaxStart(function(){
		$('#carregando').show();
	}).ajaxStop(function (){
		$('#carregando').hide();
	});

	$('button#botaoEsqueciSenha').on("click", function(e){
		e.preventDefault();

		var campos = new FormData($("form#formEsqueciSenha").get(0));
		campos.append("esqueciSenha", "OK");
		campos.append("JSON", 1);


		//Validação front-end aqui antes de enviar o ajax
		$.ajax({
			url: 'php/user-recoverPassword.php',
			type: 'POST',
			contentType : false,
			processData : false,
			data: campos,

			success: function(retornoPHP){
				retornoJSON = JSON.parse(retornoPHP);

				if(retornoJSON['erro'])
				{	
					if(retornoJSON['campos'])
					{
						if(retornoJSON['campos'].email) {
							$('#erroEmail').html(retornoJSON['campos'].email);
						}else{
							$('#erroEmail').html('');
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
						$('#sucesso').html(retornoJSON['mensagem']);
					}else{
						$('#sucesso').html('');
					}
				}
			},

			error: function(){
				$('#erroBCD').html('Ocorreu um erro durante a solicitação');
			}
		});
	});

	$('button#botaoRecuperarSenha').on("click", function(e){
		e.preventDefault();

		var campos = new FormData($("form#formCadRecuperarSenha").get(0));
		campos.append("recuperarSenha", "OK");
		campos.append("JSON", 1);


		//Validação front-end aqui antes de enviar o ajax
		$.ajax({
			url: 'php/user-recoverPassword.php',
			type: 'POST',
			contentType : false,
			processData : false,
			data: campos,

			success: function(retornoPHP){
				retornoJSON = JSON.parse(retornoPHP);

				if(retornoJSON['erro'])
				{	
					if(retornoJSON['campos'])
					{
						if(retornoJSON['campos'].novaSenha) {
							$('#erroNovaSenha').html(retornoJSON['campos'].novaSenha);
						}else{
							$('#erroNovaSenha').html('');
						}

						if(retornoJSON['campos'].confirmNovaSenha) {
							$('#erroConfirmNovaSenha').html(retornoJSON['campos'].confirmNovaSenha);
						}else{
							$('#erroConfirmNovaSenha').html('');
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
						window.location = 'usuario-login.php';
					}
				}
			},

			error: function(){
				$('#erroBCD').html('Ocorreu um erro durante a solicitação');
			}
		});
	});
});