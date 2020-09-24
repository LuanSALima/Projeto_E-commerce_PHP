function PreviewImagem() {
    var oFReader = new FileReader();
    oFReader.readAsDataURL(document.getElementById("inputImagem").files[0]);

    oFReader.onload = function (oFREvent) {
        document.getElementById("imagemPreview").src = oFREvent.target.result;
    };
};

function PreviewNome() {
    document.getElementById("nomePreview").innerHTML = document.getElementById("inputNome").value;
};

function PreviewPreco() {
    document.getElementById("precoPreview").innerHTML = document.getElementById("inputPreco").value;
};

$(function(){
	$('button#botaoEditar').on("click", function(e){
		e.preventDefault();

		var campos = new FormData($("form#formEditarProduto").get(0));
		campos.append("editar", "OK");
		campos.append("JSON", 1);


		//Validação front-end aqui antes de enviar o ajax
		$.ajax({
			url: 'php/product-edit.php',
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
						if(retornoJSON['campos'].nome) {
							$('#erroNome').html(retornoJSON['campos'].nome);
						}else{
							$('#erroNome').html('');
						}

						if(retornoJSON['campos'].preco) {
							$('#erroPreco').html(retornoJSON['campos'].preco);
						}else{
							$('#erroPreco').html('');
						}

						if(retornoJSON['campos'].imagem) {
							$('#erroImagem').html(retornoJSON['campos'].imagem);
						}else{
							$('#erroImagem').html('');
						}

						if(retornoJSON['campos'].tags) {
							$('#erroTag').html(retornoJSON['campos'].tags);
						}else{
							$('#erroTag').html('');
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
						window.location = 'meus-produtos.php';
					}
				}
			},

			error: function(){
				$('#erroBCD').html('Ocorreu um erro durante a solicitação');
			}
		});
	});
});