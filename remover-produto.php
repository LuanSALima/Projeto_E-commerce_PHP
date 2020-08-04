<?php 

	$nomeSite = "Projeto";

	session_start();

	$usuario = $_SESSION['usuario'] ?? '';

    session_write_close();

    $bcdErro = '';
    $erroBCD = '';

    require('../bcd/bcd_connect.php');			

    if($conexao)
    {

	    if($usuario)
	    {

	    	if(isset($_POST['removerProduto']))
	    	{
	    		$idProduto = mysqli_real_escape_string($conexao, $_POST['idProduto']);

				$comandoSQL = "DELETE FROM produto WHERE id = $idProduto;";

				if(mysqli_query($conexao, $comandoSQL))
				{
					//deletou
					header('location: meus-produtos.php');
				}
				else
				{
					//não deletou
					$erroBCD = "Não Foi possível deletar o Produto, tente novamente.";
				}
	    	}
	    	else
	    	{
		    	$idProduto = $_GET['idProduto'] ?? '';

		    	if($idProduto)
				{				
			    	try
			    	{
				    	$idProduto = mysqli_real_escape_string($conexao, $idProduto);

				    	$comandoSQL = "SELECT id, id_usuario, nome, preco, imagem FROM produto WHERE id = $idProduto";

						$resultado = mysqli_query($conexao, $comandoSQL);

						$produto = mysqli_fetch_all($resultado, MYSQLI_ASSOC)[0];

						mysqli_free_result($resultado);

						mysqli_close($conexao);

						if($produto['id_usuario'] != $usuario['id'])
						{
							header('location: meus-produtos.php');
						}
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
	    }
	    else
	    {
	    	header('location: index.php');
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

 	<title><?php echo $nomeSite; ?> - Remover</title>

 	<?php require('cabecalho.php') ?>

	<div class="container">
	  	<h1>Remover Produto</h1> 

	  	<div style="width: 100%; text-align: center;">
		 	<h2 style="color: red;">
		 		<?php
		 			echo $bcdErro ?? ''; 
		 			echo $erroBCD ?? '';
		 		?>
		 	</h2>
	 	</div>


	 	<?php if(!$erroBCD && !$bcdErro): ?>
	 	<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
	 		<input type="hidden" name="idProduto" value="<?php echo $produto['id']; ?>">
	 		<div class="form-group">
				<label class="control-label">Nome</label>
				<input type="text" readonly class="form-control" value="<?php echo htmlspecialchars($produto['nome']); ?>">
			</div>
			<div class="form-group">
				<label class="control-label">Preço</label>
				<div class="input-group">
					<span class="input-group-addon">R$</span>
					<input type="text" readonly class="form-control" value="<?php echo htmlspecialchars(str_replace('.', ',', $produto['preco'])); ?>">
				</div>
			</div>
			<div class="form-group">
				<label class="control-label">Imagem</label>
				<br>
				<img style="width: 400px;height: 280px;" src="produtoImagem.php?IdProduto=<?php echo $produto['id']; ?>">
			</div>
			<div class="form-group">
				<label class="control-label">Deseja Realmente Remover o Produto?</label>
				<br>
				<input class="btn btn-danger" type="submit" name="removerProduto" value="Sim">
				<a class="btn btn-primary" href="meus-produtos.php">Não</a>
			</div>
	 	</form>
	 	<?php else: ?>
	 		<a href="meus-produtos.php">Voltar</a>
	 	<?php endif; ?>
	</div>

	<!-- jQuery library -->
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

	<!-- Latest compiled JavaScript -->
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>  

 </body>

 </html>