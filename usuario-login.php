<?php 

	$nomeSite = "Projeto";

	if(isset($_POST['logar']))
	{
		include('php/user-login.php');
	}
	
 ?>

 <!DOCTYPE html>
 <html lang="pt-br">

 <head>

 	<title><?php echo $nomeSite; ?> - Login</title>

 	<style type="text/css">
 		
 		.error
 		{
 			color: red;
 			font-size: 16px;
 		}

 	</style>

 	<?php require('cabecalho.php') ?>

 	<h1>Login</h1>

 	<div style="width: 100%; text-align: center;">
	 	<h2 id="erroBCD" style="color: red;">
	 		<?php echo $bcdErro ?? ''; ?>
	 	</h2>
 	</div>

	<div class="container">
	  
		<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST" id="formLoginUsuario">
			<div class="form-group">
				<label class="control-label" for="inputLogin">Login</label>
				<input class="form-control" type="text" name="login_email" id="inputLoginEmail"  value="<?php echo $login_email ?? ''; ?>">
				<span id="erroLoginEmail" class="error">
					<?php echo $erros['login_email'] ?? ''; ?>
				</span>
			</div>
			<div class="form-group">
				<label class="control-label" for="inputSenha">Senha</label>
				<input class="form-control" type="password" name="senha" id="inputSenha" >
				<span id="erroSenha" class="error">
					<?php echo $erros['senha'] ?? ''; ?>
				</span>
			</div>
			 <button id="botaoLogar" type="submit" class="btn btn-default" name="logar">Logar</button>
		</form>

	</div>

	<!-- jQuery library -->
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

	<!-- jQuery Validation library -->
	<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.2/jquery.validate.min.js"></script>

	<!-- Latest compiled JavaScript -->
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script> 

	<script src="script/user-login.js"></script>

	<script>
		
		$(function(){
			$("#formLoginUsuario").validate();
		});

	</script>

 </body>

 </html>