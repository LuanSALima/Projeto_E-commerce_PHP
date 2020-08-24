<?php 

	$nomeSite = "Projeto";

	if(isset($_POST['cadastrar']))
	{
		include('php/user-register.php');
	}

 ?>

 <!DOCTYPE html>
 <html lang="pt-br">

 <head>

 	<title><?php echo $nomeSite; ?> - Registrar-se</title>
 	
 	<style type="text/css">
 		
 		.error
 		{
 			color: red;
 			font-size: 16px;
 		}

 	</style>

 	<?php require('cabecalho.php') ?>

	<div class="container">
		<h1>Cadastrar-se</h1>

	 	<div style="width: 100%; text-align: center;">
		 	<h2 id="erroBCD" style="color: red;">
		 		<?php echo $bcdErro ?? ''; ?>
		 	</h2>
	 	</div>
	  
		<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST" id="formCadUsuario">
			<div class="form-group">
				<label class="control-label" for="inputLogin">Login</label>
				<input class="form-control" type="text" name="login" id="inputLogin" required maxlength="40" value="<?php echo $login ?? ''; ?>">
				<span id="erroLogin" class="error">
					<?php echo $erros['login'] ?? ''; ?>
				</span>
			</div>
			<div class="form-group">
				<label class="control-label" for="inputEmail">E-mail</label>
				<input class="form-control" type="email" name="email" id="inputEmail" required maxlength="50" value="<?php echo $email ?? ''; ?>">
				<span id="erroEmail" class="error">
					<?php echo $erros['email'] ?? ''; ?>						
				</span>
			</div>
			<div class="form-group">
				<label class="control-label" for="inputSenha">Senha</label>
				<input class="form-control" type="password" name="senha" id="inputSenha" required maxlength="40" value="<?php echo $senha ?? ''; ?>">
				<span id="erroSenha" class="error">
					<?php echo $erros['senha'] ?? ''; ?>	
				</span>
			</div>
			<div class="form-group">
				<label class="control-label" for="inputConfirmSenha">Confirmar Senha</label>
				<input class="form-control" type="password" name="confirmSenha" id="inputConfirmSenha" required maxlength="40" >
				<span id="erroConfirmSenha"class="error">
					<?php echo $erros['confirmarSenha'] ?? ''; ?>
				</span>
			</div>
			 <button type="submit" id="botaoCadastrar" class="btn btn-default" name="cadastrar">Cadastrar</button>
		</form>

	</div>

	<!-- jQuery library -->
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

	<!-- jQuery Validation library -->
	<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.2/jquery.validate.min.js"></script>

	<!-- Latest compiled JavaScript -->
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script> 

	<script src="script/user-register.js"></script>

	<script>
		//Não esta funcionando
		$(function(){
			$("#formCadUsuario").validate();
		});
	</script>

 </body>

 </html>