<?php 

	$nomeSite = "Projeto";

	try
	{
		session_start();

		$usuarioLogado = $_SESSION['usuario'] ?? '';

	    session_write_close();

	    if(!empty($usuarioLogado))
	    {
			require('php/classes/BancoDados.php');
			require('php/classes/Analytics.php');

			$conexao = (new Conexao())->conectar();
	        if(!empty($conexao))
	        {
	        	$classeProduto = new Access($conexao);

	            $resultado = $classeProduto->searchAcessUserProducts($usuarioLogado['id']);

	            if(gettype($resultado) == 'string')
	            {
	            	$bcdErro = $resultado;
	            }
	            else if(gettype($resultado) == 'array')
	            {
	            	$acessos = $resultado;
	            	/*
	            	echo '<pre>';
	            	print_r($acessos);
	            	echo '</pre>';
	            	*/
	            }
	            else
	            {
	            	$bcdErro = 'Resultado inesperado do Banco de Dados';
	            }
	        }
	        else
	        {
	        	$bcdErro = "Ocorreu um problema ao conectar ao Banco de Dados";
	        }
	    }
	    else
	    {
	    	header('location: index.php');
	    }
    }
    catch(Exception $e)
	{
		$bcdErro = "Ocorreu um problema ao listar as estatísticas";
	}

 ?>

 <!DOCTYPE html>
 <html lang="pt-br">

 <head>

 	<title><?php echo $nomeSite; ?> - Estatísticas Produtos</title>

 	<style type="text/css">
 		.table th, td
 		{
 			text-align: center;
 		}
 	</style>

 	<?php require('cabecalho.php') ?>

	<div class="container">

	  	<h1>Detalhes dos Produtos</h1>

		<div style="width: 100%; text-align: center;">
		 	<h2 style="color: red;">
		 		<?php
		 			echo $bcdErro ?? '';
		 		?>
		 	</h2>
	 	</div>

	 	<?php if(!isset($bcdErro)): ?>
	 		<h3 align="center">Acessos</h3>
	 		<table class="table">
	 			<tr>
	 				<th>Produto</th>
	 				<th>Hoje</th>
	 				<th>Última Semana</th>
	 				<th>Último Mês</th>
	 				<th>Total Acessos</th>
	 			</tr>
	 			<?php foreach($acessos as $acesso): ?>
	 				<tr>
	 					<td>
	 						<img style="width: 100px;height: 100px;" src="produtoImagem.php?IdProduto=<?php echo $acesso['id']; ?>">
	 						<span><?php echo $acesso['nome']; ?></span>
	 					</td>
	 					<td>
	 						<span><?php echo $acesso['ultimo_dia']; ?></span>
	 					</td>
	 					<td>
	 						<span><?php echo $acesso['ultima_semana']; ?></span>
	 					</td>
	 					<td>
	 						<span><?php echo $acesso['ultimo_mes']; ?></span>
	 					</td>
	 					<td>
	 						<span><?php echo $acesso['total_acessos']; ?></span>
	 					</td>
	 				</tr>
	 			<?php endforeach; ?>
	 		</table>
	 	<?php else: ?>

	 	<?php endif; ?>

	</div>

	<!-- jQuery library -->
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

	<!-- Latest compiled JavaScript -->
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>  

 </body>

 </html>