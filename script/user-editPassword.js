$(function(){
	$('button#botaoAlterar').on("click", function(e){
		e.preventDefault();

		var campos = new FormData($("form#formAlterarSenha").get(0));
		campos.append("alterar", "OK");
		campos.append("JSON", 1);

		//Validação front-end aqui antes de enviar o ajax
		$.ajax({
			url: 'php/user-editPassword.php',
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
						if(retornoJSON['campos'].senhaAtual) {
							$('#erroSenhaAtual').html(retornoJSON['campos'].senhaAtual);
						}else{
							$('#erroSenhaAtual').html('');
						}

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