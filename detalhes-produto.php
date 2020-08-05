<?php 

	$nomeSite = "Projeto";


	$idProduto = $_GET['idProduto'] ?? '';

	require('../bcd/bcd_connect.php');

    $bcdErro = '';
    $erroBCD = '';

    if($conexao)
    {
		if($idProduto)
		{				
	    	try
	    	{
		    	$idProduto = mysqli_real_escape_string($conexao, $idProduto);

		    	$comandoSQL = "SELECT produto.id, id_usuario, usuarios.login, nome, preco, imagem FROM produto INNER JOIN usuarios ON produto.id_usuario = usuarios.id WHERE produto.id = $idProduto";

				$resultado = mysqli_query($conexao, $comandoSQL);

				$produto = mysqli_fetch_all($resultado, MYSQLI_ASSOC)[0];

				$produto['preco'] = str_replace('.', ',', $produto['preco']);	

				mysqli_free_result($resultado);

				mysqli_close($conexao);

			}
			catch(Exception $e)
			{
				$erroBCD = "Não foi possível localizar o produto ";
			}
		}
		else
		{
			$erroBCD = "Não foi possível localizar o produto";
		}
	}
	else
    {
    	$bcdErro = "Houve um problema no banco de dados";
    }

 ?>

 <!DOCTYPE html>
 <html lang="pt-br">

 <head>

 	<title><?php echo $nomeSite; ?> - Home</title>

 	<?php require('cabecalho.php') ?>

	<div class="container">
	  <h1>Detalhes do Produto</h1>

		<div style="width: 100%; text-align: center;">
		 	<h2 style="color: red;">
		 		<?php
		 			echo $bcdErro ?? ''; 
		 			echo $erroBCD ?? '';
		 		?>
		 	</h2>
	 	</div>

	 	<?php if(!$erroBCD && !$bcdErro): ?>

	  		<h4>Nome:</h4>
	  		<p><?php echo $produto['nome']; ?></p>

	  		<h4>Preço:</h4>
	  		<p>R$ <?php echo $produto['preco']; ?></p>

	  		<h4>Vendedor:</h4>
	  		<p><?php echo $produto['login'] ?></p>

	  		<h4>Imagem:</h4>
	  		<img id="imagemPreview" style="width: 400px;height: 400px;" src="produtoImagem.php?IdProduto=<?php echo $produto['id']; ?>"> 		

	  	<?php else: ?>
	 		<a href="lista-produtos.php">Voltar</a>
	 	<?php endif; ?>
	</div>

	<!-- jQuery library -->
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

	<!-- Latest compiled JavaScript -->
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>  

 </body>

 </html>