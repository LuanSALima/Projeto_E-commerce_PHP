<?php 

	$nomeSite = "Projeto";

	session_start();

	$usuarioLogado = $_SESSION['usuario'] ?? '';

    session_write_close();

    if(!$usuarioLogado)
	{
	    header('location: index.php');
	}

    if(isset($_POST['remover']))
	{
		include('php/product-delete.php');
	}
	else
	{
		try
    	{
    		$idProduto = $_GET['idProduto'] ?? '';

    		if($idProduto)
			{
				$idProduto = htmlspecialchars($idProduto);

				require('php/classes/BancoDados.php');
				require('php/classes/Product.php');

				$conexao = (new Conexao())->conectar();
		        if(!empty($conexao))
		        {
		        	$classeProduto = new Produto($conexao);

	                $resultado = $classeProduto->buscaProduto($idProduto);

	                if(gettype($resultado) == 'string')
	                {
	                	$bcdErro = $resultado;
	                }
	                else
	                {
	                	if($resultado['id_usuario'] != $usuarioLogado['id'])
	                	{
	                		$bcdErro = "Não é possível remover o produto de outro usuário";
	                	}
	                	else
	                	{
	                		$produto = $resultado;
	                	}
	                }
		        }
		        else
		        {
		        	$bcdErro = "Ocorreu um problema ao conectar ao Banco de Dados";
		        }
		    }
	        else
			{
				$bcdErro = "Não foi possível localizar o produto";
			}
	    }
	    catch(Exception $e)
    	{
    		$bcdErro = "Ocorreu um problema ao buscar o usuário";
    	}
	}
 ?>

 <!DOCTYPE html>
 <html lang="pt-br">

 <head>

 	<title><?php echo $nomeSite; ?> - Remover Produto</title>

 	<?php require('cabecalho.php') ?>

	<div class="container">
	  	<h1>Remover Produto</h1> 

	  	<div style="width: 100%; text-align: center;">
		 	<h2 style="color: red;">
		 		<?php echo $bcdErro ?? ''; ?>
		 	</h2>
	 	</div>


	 	<?php if(!isset($bcdErro)): ?>
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
				<input class="btn btn-danger" type="submit" name="remover" value="Sim">
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