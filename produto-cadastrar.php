<?php 

	$nomeSite = "Projeto";

	session_start();

	$usuarioLogado = $_SESSION['usuario'] ?? '';

    session_write_close();

    if(!$usuarioLogado)
	{
	    header('location: index.php');
	}

    if(isset($_POST['cadastrar']))
	{
		include('php/product-register.php');
	}
	else
	{
		include('php/classes/BancoDados.php');
		require('php/classes/Product.php');
	}
	
	try
	{
		$conexao = (new Conexao())->conectar();
        if(!empty($conexao))
        {
        	$classeProduto = new Produto($conexao);

            $resultado = $classeProduto->listaTags();

            if(gettype($resultado) == 'string')
            {
            	$bcdErro = $resultado;
            }
            else
            {
            	$tags = $resultado;
            }
        }
        else
        {
        	$bcdErro = "Ocorreu um problema ao conectar ao Banco de Dados";
        }
	}
	catch(Exception $e)
	{
		$bcdErro = "Ocorreu um problema ao carregar as tags";
	}
	finally
	{
		mysqli_close($conexao);
	}

 ?>

 <!DOCTYPE html>
 <html lang="pt-br">

 <head>

 	<title><?php echo $nomeSite; ?> - Cadastrar Produto</title>

 	<style type="text/css">
 		
 		.error
 		{
 			color: red;
 			font-size: 16px;
 		}

 	</style>

 	<?php require('cabecalho.php') ?>

	<div class="container">

		<h1>Cadastrar Produto</h1>

	 	<div style="width: 100%; text-align: center;">
		 	<h2 id="erroBCD" style="color: red;">
		 		<?php
		 			echo $bcdErro ?? ''; 
		 		?>
		 	</h2>
	 	</div>
		<form class="col-md-9" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST" id="formCadProduto" enctype="multipart/form-data">
			<div class="form-group">
				<label class="control-label" for="inputNome">Nome</label>
				<input class="form-control" type="text" name="nome" id="inputNome" required maxlength="100" value="<?php echo $nome ?? ''; ?>" onchange="PreviewNome();">
				<span id="erroNome" class="error"><?php echo $erros['nome'] ?? ''; ?></span>
			</div>
			<div class="form-group">
				<label class="control-label" for="inputPreco">Preço</label>
				<div class="input-group">
					<span class="input-group-addon">R$</span>
					<input class="form-control" type="numeric" name="preco" required id="inputPreco" value="<?php echo $preco ?? ''; ?>" onchange="PreviewPreco();">
				</div>
				<label id="inputPreco-error" class="error" for="inputPreco"></label>
				<span id="erroPreco" class="error"><?php echo $erros['preco'] ?? ''; ?></span>
			</div>
			<div class="form-group">
				<label class="control-label" for="inputImagem">Imagem</label>
				<input type="file" name="imagem" id="inputImagem" onchange="PreviewImagem();">
				<span>Formatos aceitos: .jpg ; .jpeg ; .png ;</span>
				<span id="erroImagem" class="error"><?php echo $erros['imagem'] ?? ''; ?></span>
			</div>
			<div class="form-group">
				<label class="control-label">Tags</label>
				<?php foreach ($tags as $tag): ?>
					<?php 
						if(isset($tagsPost))
						{
							$check = in_array($tag['id'], $tagsPost) ? 'checked' : '';
						}
					?>
				<div class="custom-control custom-checkbox">
				    <input type="checkbox" name="tags[]" class="custom-control-input" id="<?php echo 'tag'.$tag['id']; ?>" value="<?php echo $tag['id']; ?>" <?php echo $check ?? ''; ?>>
				    <label class="custom-control-label" for="<?php echo 'tag'.$tag['id']; ?>"><?php echo $tag['nome']; ?></label>
				</div>

				<?php endforeach; ?>
				<span id="erroTag" class="error"><?php echo $erros['tags'] ?? ''; ?></span>
			</div>
			<button id="botaoCadastrar" type="submit" class="btn btn-default" name="cadastrar">Cadastrar</button>
		</form>

		<h1>Preview</h1>

		<div class="col-md-3">
		    <div class="card card-inverse card-primary text-center">
		    	<img id="imagemPreview" style="width: 100%;height: 200px;">
		      	<div class="card-block">
			        <h4 class="card-title"><span id="nomePreview"><?php echo $nome ?? ''; ?></span></h4>
			        <p class="card-text">R$ <span id="precoPreview"><?php echo $preco ?? ''; ?></span></p>
		    	</div>
		    </div>
		</div>

	</div>

	
	<!-- jQuery library -->
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

	<!-- jQuery Validation library -->
	<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.2/jquery.validate.min.js"></script>

	<!-- Latest compiled JavaScript -->
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>  

	<!--
<script src="script/product-register.js"></script>
	-->
	

	<script>
		
		$(function(){
			$("#formCadProduto").validate();
		});

	</script>

 </body>

 </html>