$(function(){
	$('button#botaoLogar').on("click", function(e){
		e.preventDefault();

		var campoLoginEmail = $('form#formLoginUsuario #inputLoginEmail').val();
		var campoSenha = $('form#formLoginUsuario #inputSenha').val();

		//Validação front-end aqui antes de enviar o ajax
		$.ajax({
			url: 'php/user-login.php',
			type: 'POST',
			data: {
				login_email: campoLoginEmail,
				senha: campoSenha,
				logar: 'OK',
				JSON: '1'
			},

			success: function(retornoPHP){
				retornoJSON = JSON.parse(retornoPHP);

				if(retornoJSON['erro'])
				{	
					if(retornoJSON['campos'])
					{
						if(retornoJSON['campos'].login_email) {
							$('#erroLoginEmail').html(retornoJSON['campos'].login_email);
						}else{
							$('#erroLoginEmail').html('');
						}

						if(retornoJSON['campos'].senha) {
							$('#erroSenha').html(retornoJSON['campos'].senha);
						}else{
							$('#erroSenha').html('');
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