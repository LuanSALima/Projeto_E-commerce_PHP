<?php 

	$nomeSite = "Projeto";

	try
	{
		session_start();

		$usuario = $_SESSION['usuario'] ?? '';

	    session_write_close();

	    if(!empty($usuario))
	    {
	    	$pagina = $_GET['pagina'] ?? 1;

			require('php/classes/BancoDados.php');
			require('php/classes/Product.php');

			$conexao = (new Conexao())->conectar();
	        if(!empty($conexao))
	        {
	        	$classeProduto = new Produto($conexao);

	            $resultado = $classeProduto->listaProdutosUsuario($usuario['id'], $pagina);

	            if(gettype($resultado) == 'string')
	            {
	            	$bcdErro = $resultado;
	            }
	            else if(gettype($resultado) == 'array')
	            {
	            	$produtos = $resultado['produtos'];
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
		$bcdErro = "Ocorreu um problema ao listar os produtos";
	}


 ?>

 <!DOCTYPE html>
 <html lang="pt-br">

 <head>

 	<title><?php echo $nomeSite; ?> - Meus Produtos</title>

 	<?php require('cabecalho.php') ?>

	<div class="container">

	<?php if(!isset($bcdErro)): ?>
		<a class="btn btn-primary pull-right" href="estatisticas-produtos.php">Estatísticas</a>
	<?php endif; ?>

	  <h1>Meus Produtos</h1>

	  	<div style="width: 100%; text-align: center;">
		 	<h2 style="color: red;">
		 		<?php
		 			echo $bcdErro ?? ''; 
		 			echo $erroBCD ?? '';
		 		?>
		 	</h2>
	 	</div>

	  	<?php if(!isset($bcdErro)): ?>

	 		<?php if(count($produtos)): ?>
			 	<?php 	foreach ($produtos as $produto):	?>

			 		<div class="col-md-3">
					    <div class="card card-inverse card-primary text-center">
					    	<img style="width: 100%;height: 200px;" src="produtoImagem.php?IdProduto=<?php echo $produto['id']; ?>">
					      	<div class="card-block">
						        <h4 class="card-title"><?php echo htmlspecialchars($produto['nome']); ?></h4>
						        <?php foreach ($produto['tags'] as $tag): ?>
						        	<span class="badge"><?php echo $tag['nome']; ?></span>
						        <?php endforeach; ?>
						        <p style="margin-top: 10px;" class="card-text">R$ <?php echo htmlspecialchars($produto['preco']); ?></p>
						        <a href="produto-editar.php?idProduto=<?php echo $produto['id'] ?>" class="btn btn-primary">Editar</a>
						        <a href="produto-remover.php?idProduto=<?php echo $produto['id'] ?>" class="btn btn-danger">Remover</a>
					    	</div>
					    </div>
					</div>
			
			 	<?php endforeach; ?>
			<?php else: ?>
				<h2>Não há produtos cadastrados!</h2>
			<?php endif; ?>
	 	<?php endif; ?>

		</div>
	</div>

	<!-- jQuery library -->
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

	<!-- Latest compiled JavaScript -->
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>  

 </body>

 </html>