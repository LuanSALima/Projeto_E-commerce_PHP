<?php 

	$nomeSite = "Projeto";

	session_start();

	$usuarioLogado = $_SESSION['usuario'] ?? '';

    session_write_close();

    require('../bcd/bcd_connect.php');

    $bcdErro = '';
    $erroBCD = '';

    $erros = ['login' => '', 'email' => '', 'senhaAtual' => '', 'confirmarSenha' => '', 'imagem' => ''];

    if($conexao)
    {
	    if($usuarioLogado)
	    {
	    	if(isset($_POST['editar']))
	    	{
				$login = trim(htmlspecialchars($_POST['login']));
        		$email = trim(htmlspecialchars($_POST['email']));
        		$senha = trim(htmlspecialchars($_POST['senhaAtual']));
				$imagem = $_FILES['imagem'];

				if( empty($login) )
		        {
		            $erros['login'] = "Login não foi preenchido";          
		        }
		        else if( strlen($login) > 40)
		        {
		        	 $erros['login'] = "Login deve possuir no máximo 40 caracteres";
		        }

		        if( empty($email) )
		        {
		            $erros['email'] = "E-mail não foi preenchido";
		        }
		        else if( strlen($email) > 50)
		        {
		        	 $erros['email'] = "E-mail deve possuir no máximo 50 caracteres";
		        }
		        else if(!filter_var($email, FILTER_VALIDATE_EMAIL))
		        {
		            $erros['email'] = "Digite um e-mail válido";
		        }

		        if( empty($senha) )
		        {
		            $erros['senha'] = "Senha Atual não foi preenchida";
		        }
		        else if( strlen($senha) > 40)
		        {
		        	 $erros['senha'] = "Senha deve possuir no máximo 40 caracteres";
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
						$errosBCD = ['login' => '', 'email' => '', 'senha' => ''];

			            $login = trim(mysqli_real_escape_string($conexao, $_POST['login']));
			            $email = trim(mysqli_real_escape_string($conexao, $_POST['email']));
			            $senha = trim(mysqli_real_escape_string($conexao, $_POST['senhaAtual']));

			            $loginBanco = mysqli_query($conexao, "SELECT * FROM usuarios WHERE login = '$login' AND id != ".$usuarioLogado['id']);
			            $emailBanco = mysqli_query($conexao, "SELECT * FROM usuarios WHERE email = '$email' AND id != ".$usuarioLogado['id']);

			            $resultadoSenhaBanco = mysqli_query($conexao, "SELECT senha FROM usuarios WHERE id = ".$usuarioLogado['id']);

			            $senhaBanco = mysqli_fetch_all($resultadoSenhaBanco, MYSQLI_ASSOC)[0];

			            $resultadoLogin = $loginBanco -> num_rows;
			            $resultadoEmail = $emailBanco -> num_rows;

			            if($resultadoLogin > 0)
			            {
			                $errosBCD['login'] = "Login já cadastrado";
			            }
			            if($resultadoEmail > 0)
			            {
			                $errosBCD['email'] = "E-mail já cadastrado";
			            }
			            if($senhaBanco['senha'] != $senha)
			            {
			            	$errosBCD['senha'] = "Senha incorreta";
			            }

			            if(!array_filter($errosBCD))
			            {
							$idUsuario = $usuarioLogado['id'];

							//Se não tiver imagem, não será alterada
							if($imagem['error'] == 4)
							{
								$comandoSQL = "UPDATE usuarios SET login = '$login', email = '$email' WHERE id = $idUsuario;";
							}
							else
							{
								$nomeFinal = time().'.jpg';
								if (move_uploaded_file($imagem['tmp_name'], $nomeFinal)) {
									$tamanhoImg = filesize($nomeFinal);

									$mysqlImg = addslashes(fread(fopen($nomeFinal, "r"), $tamanhoImg));

									$comandoSQL = "UPDATE usuarios SET login = '$login', email = '$email', foto = '$mysqlImg' WHERE id = $idUsuario;";
								}
								unlink($nomeFinal);
							}

							if(!mysqli_query($conexao, $comandoSQL))
			                {
			                    echo "Erro de comando: " . mysqli_error($conexao);
			                }
			                else
			                {
			                	echo "sucesso";
			                }

			                $comandoSQL = "SELECT id, login, senha FROM usuarios WHERE login = '$login' OR email = '$login';";

		        			$resultado = mysqli_query($conexao, $comandoSQL);

		        			$usuarioAlterado = mysqli_fetch_assoc($resultado);

			                session_start();

		                	$_SESSION['usuario'] = $usuarioAlterado;

							header('location: index.php');
						}
						
					}
					catch(Exception $e)
			        {
			        	$bcdErro = "Houve um problema no banco de dados ". $e;
			        }
				}
	    	}
		
	    	try
	    	{
		    	$idUsuario = mysqli_real_escape_string($conexao, $usuarioLogado['id']);

		    	$comandoSQL = "SELECT id, login, email, foto FROM usuarios WHERE id = $idUsuario";

				$resultado = mysqli_query($conexao, $comandoSQL);

				$usuario = mysqli_fetch_all($resultado, MYSQLI_ASSOC)[0];

				mysqli_free_result($resultado);

				mysqli_close($conexao);
			}
			catch(Exception $e)
			{
				$erroBCD = "Não foi possível localizar o usuário";
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
		 	<h2 style="color: red;">
		 		<?php
		 			echo $bcdErro ?? ''; 
		 			echo $erroBCD ?? '';
		 		?>
		 	</h2>
	 	</div>
	  
	  	<?php if(!$erroBCD && !$bcdErro): ?>
		<form class="col-md-9" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST" id="formEditarUsuario" enctype="multipart/form-data">
			<div class="form-group">
				<label class="control-label" for="inputLogin">Login</label>
				<input class="form-control" type="text" name="login" id="inputLogin" required maxlength="40" value="<?php echo $login ?? $usuario['login']; ?>">
				<span class="error">
					<?php 
						echo $erros['login'];
						echo $errosBCD['login'] ?? '';
					?>
				</span>
			</div>
			<div class="form-group">
				<label class="control-label" for="inputEmail">E-mail</label>
				<input class="form-control" type="email" name="email" id="inputEmail" required maxlength="50" value="<?php echo $email ?? $usuario['email']; ?>">
				<span class="error">
					<?php
					 	echo $erros['email']; 
					 	echo $errosBCD['email'] ?? '';
					?>						
				</span>
			</div>
			<div class="form-group">
				<label class="control-label" for="inputSenha">Senha Atual</label>
				<input class="form-control" type="password" name="senhaAtual" required maxlength="40" id="inputSenha" >
				<span class="error">
					<?php 
						echo $erros['senhaAtual']; 
						echo $errosBCD['senha'] ?? '';
					?>	
				</span>
			</div>
			<div class="form-group">
				<label class="control-label" for="inputImagem">Foto</label>
				<br>
				<img id="imagemPreview" style="width: 400px;height: 200px;" src="perfilImagem.php?idUsuario=<?php echo $usuario['id']; ?>">
				<img id="iconePreview" style="width: 75px;height: 75px;border-radius: 100%;" src="perfilImagem.php?idUsuario=<?php echo $logado['id']; ?>">
				<br><br>
				<input type="file" name="imagem" id="inputImagem" onchange="PreviewImagem();">
				<span>Formatos aceitos: .jpg ; .jpeg ; .png ;</span>
				<h4>Se nenhuma imagem for selecionada, será mantida a imagem anterior</h4>
				<span class="error"><?php echo $erros['imagem']; ?></span>
			</div>
			 <input type="submit" class="btn btn-default" name="editar" value="Editar"></input>
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