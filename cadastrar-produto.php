<?php 

	$nomeSite = "Projeto";

	session_start();

	$usuario = $_SESSION['usuario'] ?? '';

    session_write_close();

    if(!$usuario)
    {
    	header('location: index.php');
    }

	$erros = ['nome' => '', 'preco' => '', 'imagem' => ''];

	if(isset($_POST['cadastrar']))
	{
		$nome = trim(htmlspecialchars($_POST['nome']));
		$preco = trim(htmlspecialchars($_POST['preco']));
		$imagem = $_FILES['imagem'];

		$precoReais = str_replace(',', '.', $preco);

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

		if( $imagem['error'] == 4) 
		{
			$erros['imagem'] = "É necessário colocar uma imagem do seu produto";
		}
		else
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

			require('../bcd/bcd_connect.php');

			if($conexao)
			{

				$nomeFinal = time().'.jpg';
				if (move_uploaded_file($imagem['tmp_name'], $nomeFinal)) {
					$tamanhoImg = filesize($nomeFinal);

					$mysqlImg = addslashes(fread(fopen($nomeFinal, "r"), $tamanhoImg));

					$idUsuario = $usuario['id'];

					$comandoSQL = "INSERT INTO produto (id_usuario, nome, preco, imagem) VALUES ('$idUsuario', '$nome', '$precoReais', '$mysqlImg');";

					if(!mysqli_query($conexao, $comandoSQL))
	                {
	                    echo "Erro de comando: " . mysqli_error($conexao);
	                }

					unlink($nomeFinal);

					header('location: index.php');
				}
			}
			else
	        {
	        	$bcdErro = "Houve um problema no banco de dados";
	        }
		}
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

 	<h1>Cadastrar Produto</h1>

 	<div style="width: 100%; text-align: center;">
	 	<h2 style="color: red;">
	 		<?php
	 			echo $bcdErro ?? ''; 
	 			echo $erroBCD ?? '';
	 		?>
	 	</h2>
 	</div>

	<div class="container">
	  
		<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST" id="formCadProduto" enctype="multipart/form-data">
			<div class="form-group">
				<label class="control-label" for="inputNome">Nome</label>
				<input class="form-control" type="text" name="nome" id="inputNome" required maxlength="100" value="<?php echo $nome ?? ''; ?>">
				<span class="error"><?php echo $erros['nome']; ?></span>
			</div>
			<div class="form-group">
				<label class="control-label" for="inputPreco">Preço</label>
				<input class="form-control" type="numeric" name="preco" required id="inputPreco" value="<?php echo $preco ?? ''; ?>">
				<span class="error"><?php echo $erros['preco']; ?></span>
			</div>
			<div class="form-group">
				<label class="control-label" for="inputImagem">Imagem</label>
				<input type="file" name="imagem" id="inputImagem">
				<span>Formatos aceitos: .jpg ; .jpeg ; .png ;</span>
				<span class="error"><?php echo $erros['imagem']; ?></span>
			</div>
			 <input type="submit" class="btn btn-default" name="cadastrar"></input>
		</form>

	</div>

	
	<!-- jQuery library -->
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

	<!-- jQuery Validation library -->
	<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.2/jquery.validate.min.js"></script>

	<!-- Latest compiled JavaScript -->
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>  

	<script>
		
		$(function(){
			$("#formCadProduto").validate();
		});

	</script>

 </body>

 </html>