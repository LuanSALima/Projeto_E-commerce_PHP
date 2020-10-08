<?php 

	$nomeSite = "Projeto";

	if(isset($_GET['token']))
	{
		require('php/classes/BancoDados.php');
		require('php/classes/User.php');

		$conexao = (new Conexao())->conectar();
        if(!empty($conexao))
        {
        	$classeUsuario = new Usuario($conexao);

        	if(is_string($resultado = $classeUsuario->confirmarToken($_GET['token'])))
        	{
        		$bcdErro = $resultado;
        	}
        	
        }
        else
        {
        	$bcdErro = "Ocorreu um problema ao conectar ao Banco de Dados";
        }
	}
	else
	{
		$erro = "Ocorreu um erro ao encontrar o token de confirmação, acesse novamente o link que foi enviado por email";
	}

 ?>

 <!DOCTYPE html>
 <html lang="pt-br">

 <head>

 	<title><?php echo $nomeSite; ?> - Confirmar E-mail</title>

 	<?php require('cabecalho.php') ?>

	<div class="container">
	  <?php if(!isset($erro)): ?>

	  	<h1>E-mail Confirmado!</h1>
	  	<h2>Agora pode realizar compras e cadastrar produtos no nosso site!</h2>

	  <?php else: ?>
	  	<div style="width: 100%; text-align: center;">
		 	<h2 style="color: red;">
		 		<?php
		 			echo $erro;
		 		?>
		 	</h2>
	 	</div>
	  	<a href="index.php">Voltar</a>
	  <?php endif; ?>
	</div>

	<!-- jQuery library -->
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

	<!-- Latest compiled JavaScript -->
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>  

 </body>

 </html>