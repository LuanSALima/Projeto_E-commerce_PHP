<?php 

	$nomeSite = "Projeto";

	require('../bcd/bcd_connect.php');

    if($conexao)
    {
    	$comandoSQL = 'SELECT id, nome, preco, imagem FROM produto';

		$resultado = mysqli_query($conexao, $comandoSQL);

		$produtos = mysqli_fetch_all($resultado, MYSQLI_ASSOC);

		mysqli_free_result($resultado);

		mysqli_close($conexao);
    }
    else
    {
    	$bcdErro = "Houve um problema no banco de dados";
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

 	<?php require('cabecalho.php') ?>

	<div class="container">
	  
		<h1>Lista de Produtos</h1>

		<div style="width: 100%; text-align: center;">
		 	<h2 style="color: red;">
		 		<?php
		 			echo $bcdErro ?? ''; 
		 			echo $erroBCD ?? '';
		 		?>
		 	</h2>
	 	</div>

	 	<div class="row">

	 	<?php if(!isset($bcdErro)): ?>
	 		<?php if(count($produtos)): ?>
			 	<?php 	for($i = 0; $i < count($produtos) ; $i++):	?>
			 		
			 		<?php 

			 			// PÁGINA DE CADASTRAR DE PRODUTOS POSSUI UM PREVIEW DO PRODUTO, ENTÃO CASO HAJA ALGUMA ALTERAÇÃO NESTE DESIGN, TAMBÉM DEVERÁ SER ALTERADO LÁ

			 		 ?>

			 		<div class="col-md-3">
					    <div class="card card-inverse card-primary text-center">
					    	<img style="width: 100%;height: 200px;" src="produtoImagem.php?IdProduto=<?php echo $produtos[$i]['id']; ?>">
					      	<div class="card-block">
						        <h4 class="card-title"><?php echo htmlspecialchars($produtos[$i]['nome']); ?></h4>
						        <p class="card-text">R$ <?php echo htmlspecialchars(str_replace('.', ',', $produtos[$i]['preco'])); ?></p>
						        <a href="detalhes-produto.php?idProduto=<?php echo $produtos[$i]['id']; ?>" class="btn btn-primary">Comprar</a>
					    	</div>
					    </div>
					</div>
			
			 	<?php endfor; ?>
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