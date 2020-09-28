<?php 

	$nomeSite = "Projeto";

	try
	{
		$pagina = $_GET['pagina'] ?? 1;

		require('php/classes/BancoDados.php');
		require('php/classes/Product.php');

		$conexao = (new Conexao())->conectar();
        if(!empty($conexao))
        {
        	$classeProduto = new Produto($conexao);

            $resultado = $classeProduto->listar($pagina);

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
    catch(Exception $e)
	{
		$bcdErro = "Ocorreu um problema ao listar os produtos";
	}

/*
    echo '<pre>';
    echo print_r($produtos);
    echo '</pre>';
*/

 ?>

 <!DOCTYPE html>
 <html lang="pt-br">

 <head>

 	<title><?php echo $nomeSite; ?> - Lista Produtos</title>

 	<style>
 		.col-md-3
 		{
 			margin-top: 20px;
 		}
 	</style>

 	<?php require('cabecalho.php') ?>

	<div class="container">
	  
		<h1>Lista de Produtos</h1>

		<div style="width: 100%; text-align: center;">
		 	<h2 style="color: red;">
		 		<?php echo $bcdErro ?? ''; ?>
		 	</h2>
	 	</div>

	 	<div class="row">

	 	<?php if(!isset($bcdErro)): ?>
	 		<?php if(count($produtos)): ?>
			 	<?php 	for($i = 0 ; $i < count($produtos) ; $i++):	?>
			 		
			 		<?php 

			 			// PÁGINA DE CADASTRAR DE PRODUTOS POSSUI UM PREVIEW DO PRODUTO, ENTÃO CASO HAJA ALGUMA ALTERAÇÃO NESTE DESIGN, TAMBÉM DEVERÁ SER ALTERADO LÁ

			 		 ?>

			 		<div class="col-md-3">
					    <div class="card card-inverse card-primary text-center">
					    	<img style="width: 100%;height: 200px;" src="produtoImagem.php?IdProduto=<?php echo $produtos[$i]['id']; ?>">
					      	<div class="card-block">
						        <h4 class="card-title"><?php echo htmlspecialchars($produtos[$i]['nome']); ?></h4>
						        <?php foreach ($produtos[$i]['tags'] as $tag): ?>
						        	<span class="badge"><?php echo $tag['nome']; ?></span>
						        <?php endforeach; ?>
						        <p style="margin-top: 10px;" class="card-text">R$ <?php echo htmlspecialchars($produtos[$i]['preco']); ?></p>
						        <a href="detalhes-produto.php?idProduto=<?php echo $produtos[$i]['id']; ?>" class="btn btn-primary">Detalhes</a>
					    	</div>
					    </div>
					</div>
			
			 	<?php endfor; ?>
			 	</div>
			 	<div style="display: flex; justify-content: center; margin-top: 50px; font-size: 16px;">
				 	<ul class="pagination">

				 		<?php if($resultado['paginaAtual'] > 1): ?>
					 		<li class="page-item">
					 			<a href="<?php echo $_SERVER['PHP_SELF'].'?pagina='.($resultado['paginaAtual']-1); ?>">Anterior</a>
					 		</li>
				 		<?php else: ?>
					 		<li class="page-item disabled">
					 			<span>Anterior</span>
					 		</li>
				 		<?php endif; ?>

				 		<?php for($i=($resultado['paginaAtual']-3); $i<($resultado['paginaAtual']+3); $i++): ?>
				 		
					 		<?php if(($i > 0) && ($i <= $resultado['ultimaPagina'])): ?>

					 			<?php if($i == $resultado['paginaAtual']): ?>
					 				<li class="page-item disabled">
							 			<span><?php echo $i; ?></span>
							 		</li>
					 			<?php else: ?>
					 				<li class="page-item">
						 				<a href="<?php echo $_SERVER['PHP_SELF'].'?pagina='.$i; ?>"><?php echo $i; ?></a>
						 			</li>
					 			<?php endif; ?>

					 		<?php endif; ?>

				 		<?php endfor; ?>

				 		<?php if($resultado['paginaAtual'] != $resultado['ultimaPagina']): ?>
					 		<li class="page-item">
					 			<a href="<?php echo $_SERVER['PHP_SELF'].'?pagina='.($resultado['paginaAtual']+1); ?>">Próximo</a>
					 		</li>
				 		<?php else: ?>
					 		<li class="page-item disabled">
					 			<span>Próximo</span>
					 		</li>	
				 		<?php endif; ?>

				 	</ul>
			 	</div>

			<?php else: ?>
				<h2>Não há produtos cadastrados!</h2>
				</div>
			<?php endif; ?>

	 	<?php endif; ?>

	</div>

	<!-- jQuery library -->
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

	<!-- Latest compiled JavaScript -->
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>  

 </body>

 </html>