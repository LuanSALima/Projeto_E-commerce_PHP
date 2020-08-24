$(function(){
	$('button#botaoCadastrar').on("click", function(e){
		e.preventDefault();

		/*
		var campoLogin = $('form#formCadUsuario #inputLogin').val();
		var campoEmail = $('form#formCadUsuario #inputEmail').val();
		var campoSenha = $('form#formCadUsuario #inputSenha').val();
		var campoConfirmarSenha = $('form#formCadUsuario #inputConfirmSenha').val();
		*/
		
		var campos = new FormData($("form#formCadUsuario").get(0));
		campos.append("cadastrar", "OK");
		campos.append("JSON", 1);


		//Validação front-end aqui antes de enviar o ajax
		$.ajax({
			url: 'php/user-register.php',
			type: 'POST',
			/*data: {
				login: campoLogin,
				email: campoEmail,
				senha: campoSenha,
				confirmSenha: campoConfirmarSenha,
				cadastrar: 'OK',
				JSON: '1'
			},*/
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

						if(retornoJSON['campos'].senha) {
							$('#erroSenha').html(retornoJSON['campos'].senha);
						}else{
							$('#erroSenha').html('');
						}

						if(retornoJSON['campos'].confirmarSenha) {
							$('#erroConfirmSenha').html(retornoJSON['campos'].confirmarSenha);
						}else{
							$('#erroConfirmSenha').html('');
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
						window.location = 'login-usuario.php';
					}
				}
			},

			error: function(){
				$('#erroBCD').html('Ocorreu um erro durante a solicitação');
			}
		});
	});
});