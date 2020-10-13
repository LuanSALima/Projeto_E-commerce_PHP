<?php 

	$nomeSite = "Projeto";

	try
	{
		if(isset($_POST['esqueciSenha']))
		{
			require('php/classes/BancoDados.php');
			require('php/classes/User.php');

			$conexao = (new Conexao())->conectar();
	        if(!empty($conexao))
	        {
	        	$classeUsuario = new Usuario($conexao);

	        	$email = trim(htmlspecialchars($_POST['email']));

	        	$resultado = $classeUsuario->gerarTokenEmail($email);

	        	if($resultado === 1)
	        	{
	        		require('php/classes/Email.php');

	        		$classeEmail = new Email();

	        		$resultadoEmail = $classeEmail->sendPasswordRecover($email, $classeUsuario->getTokenGenerated());

	        		if($resultadoEmail == 1)
	        		{
	        			$emailEnviado = "O e-mail foi enviado !";
	        		}
	        		else if (is_string($resultadoEmail))
	        		{
	        			$erro = "Ocorreu um erro ao enviar o email para recuperar senha";
	        			//$erro = $resultadoEmail;
	        		}

	        	}
	        	else if(is_string($resultado))
	        	{
	        		$erro = $resultado;
	        	}
	        	else if(is_array($resultado))
	        	{
	        		$erros = $resultado;
	        	}
	        	else
	        	{
	        		$erro = "Resultado inesperado do servidor";
	        	}
	        }
	        else
	        {
	        	$erro = "Ocorreu um problema ao conectar ao Banco de Dados";
	        }
		}
		else if(isset($_GET['token']))
		{
			require('php/classes/BancoDados.php');
			require('php/classes/User.php');

			$conexao = (new Conexao())->conectar();
	        if(!empty($conexao))
	        {
	        	$classeUsuario = new Usuario($conexao);

	        	$existeToken = $classeUsuario->existeToken($_GET['token']);

				if(is_string($existeToken))
	        	{
	        		$erro = $existeToken;
	        	}
	        }
	        else
	        {
	        	$erro = "Ocorreu um problema ao conectar ao Banco de Dados";
	        }
		}
		else if(isset($_POST['alterarSenha']))
		{
            $novaSenha = trim(htmlspecialchars($_POST['novaSenha']));
            $confirmNovaSenha = trim(htmlspecialchars($_POST['confirmNovaSenha']));
            $token = trim(htmlspecialchars($_POST['token']));

            $_GET['token'] = $token;

            require('php/classes/BancoDados.php');
			require('php/classes/User.php');

			$conexao = (new Conexao())->conectar();
	        if(!empty($conexao))
	        {
	        	$classeUsuario = new Usuario($conexao);

	        	$resultado = $classeUsuario->recuperarSenha($token, $novaSenha, $confirmNovaSenha);

				if($resultado === 1)
				{
					header('location: usuario-login.php');
				}
				else if(is_array($resultado))
				{
					$erros = $resultado;
				}
				else
				{
					$erro = $resultado;
				}
	        }
	        else
	        {
	        	$erro = "Ocorreu um problema ao conectar ao Banco de Dados";
	        }
		}
	}
	catch(Exception $e)
	{
		$erro = "Ocorreu um erro interno".$e;
	}

 ?>

 <!DOCTYPE html>
 <html lang="pt-br">

 <head>

 	<title><?php echo $nomeSite; ?> - Recuperar a Senha</title>

 	<style type="text/css">
 		
 		.error
 		{
 			color: red;
 			font-size: 16px;
 		}

 	</style>

 	<?php require('cabecalho.php') ?>

 	<div class="container">
		<div style="width: 100%; text-align: center;">
		 	<h2 style="color: red;">
		 		<?php
		 			echo $erro ?? '';
		 		?>
		 	</h2>
		</div>
	</div>

 	<?php if(!isset($erro)): ?>


		<div class="container">
			<h1>Recuperar a Senha</h1>
			<?php if(!isset($_GET['token'])): ?>
				<?php if(isset($emailEnviado)): ?>
					<span style="font-size: 18px; color: green;"><?php echo $emailEnviado; ?></span>
				<?php endif; ?>
				<h3>Enviaremos um e-mail para que você digite uma nova senha</h3>
				<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST" id="formRecuperarSenha">
					<div class="form-group">
						<label class="control-label" for="inputLogin">Digite seu e-mail</label>
						<input class="form-control" type="text" name="email" id="inputEmail"  value="<?php echo $email ?? ''; ?>">
						<span id="erroEmail" class="error">
							<?php echo $erros['email'] ?? ''; ?>
						</span>
					</div>
					<button id="botaoEsqueciSenha" type="submit" class="btn btn-default" name="esqueciSenha">Enviar</button>
				</form>
			<?php else: ?>
				<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST" id="formCadRecuperarSenha">
					<input type="hidden" name="token" value="<?php echo htmlspecialchars($_GET['token']) ?? $token; ?>">
					<div class="form-group">
						<label class="control-label" for="inputNovaSenha">Digite a Nova Senha</label>
						<input class="form-control" type="text" name="novaSenha" id="inputNovaSenha">
						<span id="erroNovaSenha" class="error">
							<?php echo $erros['novaSenha'] ?? ''; ?>
						</span>
					</div>
					<div class="form-group">
						<label class="control-label" for="inputConfirmNovaSenha">Confirme a Nova Senha</label>
						<input class="form-control" type="text" name="confirmNovaSenha" id="inputConfirmNovaSenha">
						<span id="erroConfirmNovaSenha" class="error">
							<?php echo $erros['confirmNovaSenha'] ?? ''; ?>
						</span>
					</div>
					<button id="botaoEsqueciSenha" type="submit" class="btn btn-default" name="alterarSenha">Alterar</button>
				</form>
			<?php endif; ?>
		</div>

	<?php endif; ?>

	<!-- jQuery library -->
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

	<!-- Latest compiled JavaScript -->
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>  

 </body>

 </html>