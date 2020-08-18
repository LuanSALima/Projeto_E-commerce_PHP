<?php

	$nomeSite = "Projeto";

	if(isset($_POST['alterar']))
	{
		include('php/user-editPassword.php');
	}
	
 ?>

 <!DOCTYPE html>
 <html lang="pt-br">

 <head>

 	<title><?php echo $nomeSite; ?> - Alterar Senha</title>

 	<style type="text/css">
 		
 		.error
 		{
 			color: red;
 			font-size: 16px;
 		}

 	</style>

 	<?php require('cabecalho.php') ?>

	<div class="container">

		<h1>Editar Perfil	</h1>

		<div style="width: 100%; text-align: center;">
		 	<h2 id="erroBCD" style="color: red;">
		 		<?php echo $bcdErro ?? ''; ?>
		 	</h2>
	 	</div>
	  
		<form class="col-md-9" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST" id="formAlterarSenha">
			<div class="form-group">
				<label class="control-label" for="inputSenhaAtual">Senha Atual</label>
				<input class="form-control" type="password" name="senhaAtual" required maxlength="40" id="inputSenhaAtual" >
				<span id="erroSenhaAtual" class="error">
					<?php echo $erros['senhaAtual'] ?? ''; ?>	
				</span>
			</div>
			<div class="form-group">
				<label class="control-label" for="inputNovaSenha">Nova Senha</label>
				<input class="form-control" type="password" name="novaSenha" required maxlength="40" id="inputNovaSenha" >
				<span id="erroNovaSenha" class="error">
					<?php echo $erros['novaSenha'] ?? ''; ?>	
				</span>
			</div>
			<div class="form-group">
				<label class="control-label" for="inputConfirmNovaSenha">Confirmar Nova Senha</label>
				<input class="form-control" type="password" name="confirmNovaSenha" required maxlength="40" id="inputConfirmNovaSenha" >
				<span id="erroConfirmNovaSenha" class="error">
					<?php echo $erros['confirmNovaSenha'] ?? ''; ?>	
				</span>
			</div>
			
			 <button id="botaoAlterar" type="submit" class="btn btn-default" name="alterar">Alterar</button>
		</form>

	</div>

	
	<!-- jQuery library -->
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

	<!-- jQuery Validation library -->
	<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.2/jquery.validate.min.js"></script>

	<!-- Latest compiled JavaScript -->
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script> 

	<script src="script/user-editPassword.js"></script>

	<script>
		
		$(function(){
			$("#formAlterarSenha").validate();
		});

	</script>

 </body>

 </html>