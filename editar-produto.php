<?php 

	$nomeSite = "Projeto";

	session_start();

	$usuario = $_SESSION['usuario'] ?? '';

    session_write_close();

    require('../bcd/bcd_connect.php');

    $bcdErro = '';
    $erroBCD = '';

    $erros = ['nome' => '', 'preco' => '', 'imagem' => ''];

    if($conexao)
    {
	    if($usuario)
	    {
	    	if(isset($_POST['editar']))
	    	{
				$nome = trim(htmlspecialchars($_POST['nome']));
				$preco = trim(htmlspecialchars($_POST['preco']));
				$imagem = $_FILES['imagem'];
				$idProduto = $_POST['idProduto'];

				$precoReais = str_replace(',', '.', $preco);

				if( empty($idProduto) )
				{
					header('location: meus-produtos.php');
				}

				if( empty($nome) )
		        {
		            $erros['nome'] = "Preencha o Nome";          
		        }
		        else if( strlen($nome) > 100)
		        {
		        	 $erros['nome'] = "Nome deve possuir no máximo 100 caracteres";
		        }

		        if( empty($precoReais) )
		        {
		            $erros['preco'] = "Preencha o Preço";
		        }
		        else if( !is_numeric($precoReais))
		        {
		        	 $erros['preco'] = "Preço deve ser um número";
		        }

				if( $imagem['error'] != 4) 
				{
					$tiposImagemValidas = ["image/jpeg", "image/png"];

					if(!in_array($imagem['type'], $tiposImagemValidas))
					{
						$erros['imagem'] = "O arquivo deve ser uma imagem";
					}
					else if($imagem['size'] > 102400)
					{
						$erros['imagem'] = "A imagem deve ter no máximo 100 KB";
					}
				}
				
				if(!array_filter($erros))
				{
					try
					{
						$nome = mysqli_real_escape_string($conexao, $nome);
						$precoReais = mysqli_real_escape_string($conexao, $precoReais);
						$idProduto = mysqli_real_escape_string($conexao, $idProduto);

						$idUsuario = $usuario['id'];

						//Se não tiver imagem, não será alterada
						if($imagem['error'] == 4)
						{
							$comandoSQL = "UPDATE produto SET nome = '$nome', preco = '$precoReais'WHERE id = $idProduto;";
						}
						else
						{
							$nomeFinal = time().'.jpg';
							if (move_uploaded_file($imagem['tmp_name'], $nomeFinal)) {
								$tamanhoImg = filesize($nomeFinal);

								$mysqlImg = addslashes(fread(fopen($nomeFinal, "r"), $tamanhoImg));

								$comandoSQL = "UPDATE produto SET nome = '$nome', preco = '$precoReais', imagem = '$mysqlImg' WHERE id = $idProduto;";
							}
							unlink($nomeFinal);
						}

						if(!mysqli_query($conexao, $comandoSQL))
		                {
		                    echo "Erro de comando: " . mysqli_error($conexao);
		                }

						header('location: meus-produtos.php');
						
					}
					catch(Exception $e)
			        {
			        	$bcdErro = "Houve um problema no banco de dados ". $e;
			        }
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

						$produto['preco'] = str_replace('.', ',', $produto['preco']);

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

 	<title><?php echo $nomeSite; ?> - Editar Produto</title>

 	<style type="text/css">
 		
 		.error
 		{
 			color: red;
 			font-size: 16px;
 		}

 	</style>

 	<?php require('cabecalho.php') ?>

 	<h1>Editar Produto</h1>

 	<div style="width: 100%; text-align: center;">
	 	<h2 style="color: red;">
	 		<?php
	 			echo $bcdErro ?? ''; 
	 			echo $erroBCD ?? '';
	 		?>
	 	</h2>
 	</div>

	<div class="container">
	  
	  	<?php if(!$erroBCD && !$bcdErro): ?>
		<form class="col-md-9" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST" id="formEditarProduto" enctype="multipart/form-data">
			<input type="hidden" name="idProduto" value="<?php echo $produto['id'] ?>">
			<div class="form-group">
				<label class="control-label" for="inputNome">Nome</label>
				<input class="form-control" type="text" name="nome" id="inputNome" required maxlength="100" value="<?php echo $produto['nome']; ?>" onchange="PreviewNome();">
				<span class="error"><?php echo $erros['nome']; ?></span>
			</div>
			<div class="form-group">
				<label class="control-label" for="inputPreco">Preço</label>
				<div class="input-group">
					<span class="input-group-addon">R$</span>
					<input class="form-control" type="numeric" name="preco" required id="inputPreco" value="<?php echo $produto['preco']; ?>" onchange="PreviewPreco();">
				</div>
				<label id="inputPreco-error" class="error" for="inputPreco"></label>
				<span class="error"><?php echo $erros['preco']; ?></span>
			</div>
			<div class="form-group">
				<label class="control-label" for="inputImagem">Imagem</label>
				<input type="file" name="imagem" id="inputImagem" onchange="PreviewImagem();">
				<span>Formatos aceitos: .jpg ; .jpeg ; .png ;</span>
				<h4>Se nenhuma imagem for selecionada, será mantida a imagem anterior</h4>
				<span class="error"><?php echo $erros['imagem']; ?></span>
			</div>
			 <input type="submit" class="btn btn-default" name="editar" value="Editar"></input>
		</form>

		<h1>Preview</h1>

		<div class="col-md-3">
		    <div class="card card-inverse card-primary text-center">
		    	<img id="imagemPreview" style="width: 100%;height: 200px;" src="produtoImagem.php?IdProduto=<?php echo $produto['id']; ?>">
		      	<div class="card-block">
			        <h4 class="card-title"><span id="nomePreview"><?php echo $produto['nome']; ?><?php echo $nome ?? ''; ?></span></h4>
			        <p class="card-text">R$ <span id="precoPreview"><?php echo $produto['preco']; ?><?php echo $preco ?? ''; ?></span></p>
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

	<script>
		
		$(function(){
			$("#formEditarProduto").validate();
		});

	    function PreviewImagem() {
	        var oFReader = new FileReader();
	        oFReader.readAsDataURL(document.getElementById("inputImagem").files[0]);

	        oFReader.onload = function (oFREvent) {
	            document.getElementById("imagemPreview").src = oFREvent.target.result;
	        };
	    };

	    function PreviewNome() {
	        document.getElementById("nomePreview").innerHTML = document.getElementById("inputNome").value;
	    };

	    function PreviewPreco() {
	        document.getElementById("precoPreview").innerHTML = document.getElementById("inputPreco").value;
	    };

	</script>

 </body>

 </html>