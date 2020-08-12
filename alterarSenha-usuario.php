<?php 

	$nomeSite = "Projeto";

	session_start();

	$usuarioLogado = $_SESSION['usuario'] ?? '';

    session_write_close();

    require('../bcd/bcd_connect.php');

    $bcdErro = '';
    $erroBCD = '';

    $erros = [];

    if($conexao)
    {
	    if($usuarioLogado)
	    {
	    	if(isset($_POST['alterar']))
	    	{
				$senhaAtual = trim(htmlspecialchars($_POST['senhaAtual']));
        		$novaSenha = trim(htmlspecialchars($_POST['novaSenha']));
        		$confirmNovaSenha = trim(htmlspecialchars($_POST['confirmNovaSenha']));

		        if(empty($senhaAtual))
		        {
		            $erros['senhaAtual'] = "Senha Atual não foi preenchida";
		        }

		        if(empty($novaSenha))
		        {
		            $erros['novaSenha'] = "Nova Senha não foi preenchida";
		        }
		        else if(strlen($novaSenha) > 40)
		        {
		        	 $erros['novaSenha'] = "Nova Senha deve possuir no máximo 40 caracteres";
		        }

		        if(empty($confirmNovaSenha))
		        {
		            $erros['confirmNovaSenha'] = "Confirmar Nova Senha não foi preenchida";
		        }
		        else if($novaSenha != $confirmNovaSenha)
		        {
		            $erros['confirmNovaSenha'] = "As novas senhas não coincidem";
		        }
				
				if(!array_filter($erros))
				{
					try
					{
						$errosBCD = [];

			            $senhaAtual = trim(mysqli_real_escape_string($conexao, $_POST['senhaAtual']));

			            $novaSenha = trim(mysqli_real_escape_string($conexao, $_POST['novaSenha']));

			            $resultadoSenhaBanco = mysqli_query($conexao, "SELECT senha FROM usuarios WHERE id = ".$usuarioLogado['id']);

			            $senhaBanco = mysqli_fetch_all($resultadoSenhaBanco, MYSQLI_ASSOC)[0];

			            if($senhaBanco['senha'] != $senhaAtual)
			            {
			            	$errosBCD['senha'] = "Senha Atual incorreta";
			            }

			            if(!array_filter($errosBCD))
			            {
							$idUsuario = $usuarioLogado['id'];

							$comandoSQL = "UPDATE usuarios SET senha = '$novaSenha' WHERE id = $idUsuario;";

							if(!mysqli_query($conexao, $comandoSQL))
			                {
			                    echo "Erro de comando: " . mysqli_error($conexao);
			                }
			                else
			                {
			                	echo "sucesso";
			                }

							header('location: index.php');
						}
						
					}
					catch(Exception $e)
			        {
			        	$bcdErro = "Houve um problema no banco de dados ". $e;
			        }
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

 	<title><?php echo $nomeSite; ?> - Alterar Senha</title>

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
	  
		<form class="col-md-9" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST" id="formAlterarSenha">
			<div class="form-group">
				<label class="control-label" for="inputSenhaAtual">Senha Atual</label>
				<input class="form-control" type="password" name="senhaAtual" required maxlength="40" id="inputSenhaAtual" >
				<span class="error">
					<?php 
						echo $erros['senhaAtual'] ?? ''; 
						echo $errosBCD['senha'] ?? '';
					?>	
				</span>
			</div>
			<div class="form-group">
				<label class="control-label" for="inputNovaSenha">Nova Senha</label>
				<input class="form-control" type="password" name="novaSenha" required maxlength="40" id="inputNovaSenha" >
				<span class="error">
					<?php 
						echo $erros['novaSenha'] ?? ''; 
					?>	
				</span>
			</div>
			<div class="form-group">
				<label class="control-label" for="inputConfirmNovaSenha">Confirmar Nova Senha</label>
				<input class="form-control" type="password" name="confirmNovaSenha" required maxlength="40" id="inputConfirmNovaSenha" >
				<span class="error">
					<?php 
						echo $erros['confirmNovaSenha'] ?? ''; 
					?>	
				</span>
			</div>
			
			 <input type="submit" class="btn btn-default" name="alterar" value="Alterar"></input>
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
			$("#formAlterarSenha").validate();
		});

	</script>

 </body>

 </html>