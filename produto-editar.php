<?php 

	$nomeSite = "Projeto";

	session_start();

	$usuarioLogado = $_SESSION['usuario'] ?? '';

    session_write_close();

    if(!$usuarioLogado)
	{
	    header('location: index.php');
	}

    if(isset($_POST['editar']))
	{
		include('php/product-edit.php');
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

		        	$tagsProduto = $classeProduto->buscaTagsProduto($idProduto);

        			if(is_string($tagsProduto))
            		{
            			$bcdErro = $tagsProduto;
            		}

	                $resultado = $classeProduto->buscaProduto($idProduto);

	                if(gettype($resultado) == 'string')
	                {
	                	$bcdErro = $resultado;
	                }
	                else
	                {
	                	if($resultado['id_usuario'] != $usuarioLogado['id'])
	                	{
	                		$bcdErro = "Não é possível editar o produto de outro usuário";
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
	$conexaoTags = (new Conexao())->conectar();
    if(!empty($conexaoTags))
    {
    	$classeProdutoTags = new Produto($conexaoTags);

		$tags = $classeProdutoTags->listaTags();

		if(is_string($tags))
		{
			$bcdErro = $tags;
		}
	}
	else
	{
		$bcdErro = "Ocorreu um problema ao carregar as tags";
	}
 ?>

 <!DOCTYPE html>
 <html lang="pt-br">

 <head>

 	<title><?php echo $nomeSite; ?> - Editar Produto</title>

 	<style type="text/css">
 		
 		.error
 		{
 			color: red;
 			font-size: 16px;
 		}

 	</style>

 	<?php require('cabecalho.php') ?>

	<div class="container">

		<h1>Editar Produto</h1>

		<div style="width: 100%; text-align: center;">
		 	<h2 style="color: red;">
		 		<?php echo $bcdErro ?? ''; ?>
		 	</h2>
	 	</div>
	  
	  	<?php if(!isset($bcdErro)): ?>
		<form class="col-md-9" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST" id="formEditarProduto" enctype="multipart/form-data">
			<input type="hidden" name="idProduto" value="<?php echo $idProduto ?? $produto['id'] ?>">
			<div class="form-group">
				<label class="control-label" for="inputNome">Nome</label>
				<input class="form-control" type="text" name="nome" id="inputNome" required maxlength="100" value="<?php echo $nome ?? $produto['nome']; ?>" onchange="PreviewNome();">
				<span id="erroNome" class="error"><?php echo $erros['nome'] ?? ''; ?></span>
			</div>
			<div class="form-group">
				<label class="control-label" for="inputPreco">Preço</label>
				<div class="input-group">
					<span class="input-group-addon">R$</span>
					<input class="form-control" type="numeric" name="preco" required id="inputPreco" value="<?php echo $preco ?? $produto['preco']; ?>" onchange="PreviewPreco();">
				</div>
				<label id="inputPreco-error" class="error" for="inputPreco"></label>
				<span id="erroPreco" class="error"><?php echo $erros['preco'] ?? ''; ?></span>
			</div>
			<div class="form-group">
				<label class="control-label" for="inputImagem">Imagem</label>
				<input type="file" name="imagem" id="inputImagem" onchange="PreviewImagem();">
				<span>Formatos aceitos: .jpg ; .jpeg ; .png ;</span>
				<h4>Se nenhuma imagem for selecionada, será mantida a imagem anterior</h4>
				<span id="erroImagem" class="error"><?php echo $erros['imagem'] ?? ''; ?></span>
			</div>
			<div class="form-group">
				<label class="control-label">Tags</label>
				<?php foreach ($tags as $tag): ?>
					<?php
						if(isset($tagsProduto))
						{
							foreach ($tagsProduto as $tagProd)
							{
								if(in_array($tag['id'], $tagProd))
								{
									$check = 'checked';
									break;
								}
								else
								{
									$check = '';
								}
							}
						}
						else if(isset($tagsPost))
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
			 <button id="botaoEditar" type="submit" class="btn btn-default" name="editar">Editar</button>
		</form>

		<h1>Preview</h1>

		<div class="col-md-3">
		    <div class="card card-inverse card-primary text-center">
		    	<img id="imagemPreview" style="width: 100%;height: 200px;" src="produtoImagem.php?IdProduto=<?php echo $idProduto ?? $produto['id']; ?>">
		      	<div class="card-block">
			        <h4 class="card-title"><span id="nomePreview"><?php echo $nome ?? $produto['nome']; ?></span></h4>
			        <p class="card-text">R$ <span id="precoPreview"><?php echo $preco ?? $produto['preco']; ?></span></p>
		    	</div>
		    </div>
		</div>
		<?php else: ?>
	 		<a href="meus-produtos.php">Voltar</a>
	 	<?php endif; ?>

	</div>

	
	<!-- jQuery library -->
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

	<!-- jQuery Validation library -->
	<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.2/jquery.validate.min.js"></script>

	<!-- Latest compiled JavaScript -->
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>  
	
	<!--
	
	-->
	<script src="script/product-edit.js"></script>

	<script>
		
		$(function(){
			$("#formEditarProduto").validate();
		});

	</script>

 </body>

 </html>