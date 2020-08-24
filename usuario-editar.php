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
		include('php/user-edit.php');
	}
	else
	{
		try
    	{
			require('php/classes/BancoDados.php');
			require('php/classes/User.php');

			$conexao = (new Conexao())->conectar();
	        if(!empty($conexao))
	        {
	        	$classeUsuario = new Usuario($conexao);

                $resultado = $classeUsuario->buscaUsuario($usuarioLogado['id']);

                if(gettype($resultado) == 'string')
                {
                	$bcdErro = $resultado;
                }
                else
                {
                	$usuario = $resultado;
                }
	        }
	        else
	        {
	        	$bcdErro = "Ocorreu um problema ao conectar ao Banco de Dados";
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

 	<title><?php echo $nomeSite; ?> - Editar Perfil</title>

 	<style type="text/css">
 		
 		.error
 		{
 			color: red;
 			font-size: 16px;
 		}

 	</style>

 	<?php require('cabecalho.php') ?>

	<div class="container">

		<h1>Editar Perfil	</h1>

		<div style="width: 100%; text-align: center;">
		 	<h2 id="erroBCD" style="color: red;">
		 		<?php
		 			echo $bcdErro ?? '';
		 		?>
		 	</h2>
	 	</div>
	  
	  	<?php if(!isset($bcdErro)): ?>
		<form class="col-md-9" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST" id="formEditarUsuario" enctype="multipart/form-data">
			<div class="form-group">
				<label class="control-label" for="inputLogin">Login</label>
				<input class="form-control" type="text" name="login" id="inputLogin" value="<?php echo $login ?? $usuario['login']; ?>">
				<span id="erroLogin" class="error">
					<?php echo $erros['login'] ?? ''; ?>
				</span>
			</div>
			<div class="form-group">
				<label class="control-label" for="inputEmail">E-mail</label>
				<input class="form-control" type="email" name="email" id="inputEmail" value="<?php echo $email ?? $usuario['email']; ?>">
				<span id="erroEmail" class="error">
					<?php echo $erros['email'] ?? ''; ?>						
				</span>
			</div>
			<div class="form-group">
				<label class="control-label" for="inputSenha">Senha Atual</label>
				<input class="form-control" type="password" name="senhaAtual" id="inputSenha" >
				<span id="erroSenha" class="error">
					<?php echo $erros['senhaAtual'] ?? ''; ?>	
				</span>
			</div>
			<div class="form-group">
				<label class="control-label" for="inputImagem">Foto</label>
				<br>
				<img id="imagemPreview" style="width: 400px;height: 200px;" src="perfilImagem.php?idUsuario=<?php echo $usuarioLogado['id']; ?>">
				<img id="iconePreview" style="width: 75px;height: 75px;border-radius: 100%;" src="perfilImagem.php?idUsuario=<?php echo $usuarioLogado['id']; ?>">
				<br><br>
				<input type="file" name="imagem" id="inputImagem" onchange="PreviewImagem();">
				<span>Formatos aceitos: .jpg ; .jpeg ; .png ;</span>
				<h4>Se nenhuma imagem for selecionada, será mantida a imagem anterior</h4>
				<span id="erroImagem" class="error"><?php echo $erros['imagem'] ?? ''; ?></span>
			</div>
			 <button id="botaoEditar" type="submit" class="btn btn-default" name="editar">Editar</button>
		</form>
		<?php else: ?>
	 		<a href="index.php">Voltar</a>
	 	<?php endif; ?>

	</div>

	
	<!-- jQuery library -->
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

	<!-- jQuery Validation library -->
	<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.2/jquery.validate.min.js"></script>

	<!-- Latest compiled JavaScript -->
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>  
	
	<script src="script/user-edit.js"></script>

	<script>
		
		$(function(){
			$("#formEditarUsuario").validate();
		});

		function PreviewImagem() {
	        var oFReader = new FileReader();
	        oFReader.readAsDataURL(document.getElementById("inputImagem").files[0]);

	        oFReader.onload = function (oFREvent) {
	            document.getElementById("imagemPreview").src = oFREvent.target.result;
	            document.getElementById("iconePreview").src = oFREvent.target.result;
	        };
	    };

	</script>

 </body>

 </html>