$(function(){
	$('button#botaoLogar').on("click", function(e){
		e.preventDefault();

		var campos = new FormData($("form#formLoginUsuario").get(0));
		campos.append("logar", "OK");
		campos.append("JSON", 1);

		//Validação front-end aqui antes de enviar o ajax
		$.ajax({
			url: 'php/user-login.php',
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