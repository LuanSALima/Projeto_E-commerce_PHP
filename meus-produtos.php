<?php 

	$nomeSite = "Projeto";

	session_start();

	$usuario = $_SESSION['usuario'] ?? '';

    session_write_close();


	if($usuario)
	{
		require('../bcd/bcd_connect.php');

	    if($conexao)
	    {
	    	$idUsuario = $usuario['id'];

	    	$comandoSQL = "SELECT id, nome, preco, imagem FROM produto WHERE id_usuario = $idUsuario";

			$resultado = mysqli_query($conexao, $comandoSQL);

			$produtos = mysqli_fetch_all($resultado, MYSQLI_ASSOC);

			mysqli_free_result($resultado);

			mysqli_close($conexao);
	    }
	    else
	    {
	    	$bcdErro = "Houve um problema no banco de dados";
	    }
	}
	else
	{
		header('location: index.php');
	}


 ?>

 <!DOCTYPE html>
 <html lang="pt-br">

 <head>

 	<title><?php echo $nomeSite; ?> - Meus Produtos</title>

 	<?php require('cabecalho.php') ?>

	<div class="container">
	  <h1>Meus Produtos</h1>

	  	<?php if(!isset($bcdErro)): ?>
	 		<?php if(count($produtos)): ?>
			 	<?php 	foreach ($produtos as $produto):	?>

			 		<div class="col-md-3">
					    <div class="card card-inverse card-primary text-center">
					    	<img style="width: 100%;height: 200px;" src="produtoImagem.php?IdProduto=<?php echo $produto['id']; ?>">
					      	<div class="card-block">
						        <h4 class="card-title"><?php echo htmlspecialchars($produto['nome']); ?></h4>
						        <p class="card-text"><?php echo htmlspecialchars($produto['preco']); ?></p>
						        <a href="editar.php" class="btn btn-primary">Editar</a>
						        <a href="remover.php" class="btn btn-danger">Remover</a>
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