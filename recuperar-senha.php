<?php 

	$nomeSite = "Projeto";

	try
	{
		if(isset($_POST['esqueciSenha']))
		{
			include('php/user-recoverPassword.php');
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
		else if(isset($_POST['recuperarSenha']))
		{
            include('php/user-recoverPassword.php');
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

 		.carregando {
		    display:    none;
		    position:   fixed;
		    z-index:    1000;
		    top:        0;
		    left:       0;
		    height:     100%;
		    width:      100%;
		    background: rgba( 255, 255, 255, .8 ) 
		                url('img/ajaxLoading.gif') 
		                50% 50% 
		                no-repeat;
		}

		body.carregando {
		    overflow: hidden;   
		}
		body.carregando {
		    display: block;
		}

 	</style>

 	<?php require('cabecalho.php') ?>

 	<div id="carregando" class="carregando"></div>

 	<div class="container">
		<div style="width: 100%; text-align: center;">
		 	<h2 id="erroBCD" style="color: red;">
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
				<div style="width: 100%; text-align: center;">
				 	<h3 id="sucesso" style="color: green;">
				 		<?php
				 			echo $emailEnviado ?? '';
				 		?>
				 	</h3>
				</div>
				<h3>Enviaremos um e-mail para que você digite uma nova senha</h3>
				<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST" id="formEsqueciSenha">
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
						<input class="form-control" type="password" name="novaSenha" id="inputNovaSenha">
						<span id="erroNovaSenha" class="error">
							<?php echo $erros['novaSenha'] ?? ''; ?>
						</span>
					</div>
					<div class="form-group">
						<label class="control-label" for="inputConfirmNovaSenha">Confirme a Nova Senha</label>
						<input class="form-control" type="password" name="confirmNovaSenha" id="inputConfirmNovaSenha">
						<span id="erroConfirmNovaSenha" class="error">
							<?php echo $erros['confirmNovaSenha'] ?? ''; ?>
						</span>
					</div>
					<button id="botaoRecuperarSenha" type="submit" class="btn btn-default" name="recuperarSenha">Alterar</button>
				</form>
			<?php endif; ?>
		</div>

	<?php endif; ?>

	<!-- jQuery library -->
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

	<!-- Latest compiled JavaScript -->
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>

	<!--
	
	-->
	<script src="script/user-recoverPassword.js"></script>

 </body>

 </html>