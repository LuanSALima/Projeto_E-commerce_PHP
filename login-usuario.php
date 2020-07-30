<?php 

	$nomeSite = "Projeto";


    $erros = ['login_email' => '', 'senha' => ''];

    if(isset($_POST['logar']))
    {
        $login_email = htmlspecialchars($_POST['login-email']);
        $senha = htmlspecialchars($_POST['senha']);

        if( empty($login_email) )
        {
            $erros['login_email'] = "Preencha o Login/E-mail";
        }
        else if( strlen($login_email) > 50)
        {
        	 $erros['login_email'] = "Login/Email deve possuir no máximo 50 caracteres";
        }

        if( empty($senha) )
        {
            $erros['senha'] = "Preencha a senha";
        }
        else if( strlen($senha) > 40)
        {
        	 $erros['senha'] = "Senha deve possuir no máximo 40 caracteres";
        }

        if(!array_filter($erros))
        {

            require('../bcd/bcd_connect.php');

            if($conexao)
            {
            	$login = mysqli_real_escape_string($conexao, $_POST['login-email']);
		        $senha = mysqli_real_escape_string($conexao, $_POST['senha']);

		        $comandoSQL = "SELECT id, login, senha FROM usuarios WHERE login = '$login' OR email = '$login';";

		        $resultado = mysqli_query($conexao, $comandoSQL);

		        $usuario = mysqli_fetch_assoc($resultado);

		        mysqli_free_result($resultado);

		        mysqli_close($conexao);

		        if( empty($usuario) )
		        {
		            $erroBCD = "Login e E-mail não encontrado";
		        }
		        else
		        {
		            
		            if($senha != $usuario['senha'])
		            {
		                $erroBCD = "Senha incorreta";
		            }
		            else
		            {
		                session_start();

		                $_SESSION['usuario'] = $usuario;

		                header('Location: index.php');
		            }
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

 	<title><?php echo $nomeSite; ?> - Login</title>

 	<style type="text/css">
 		
 		.error
 		{
 			color: red;
 			font-size: 16px;
 		}

 	</style>

 	<?php require('cabecalho.php') ?>

 	<h1>Login</h1>

 	<div style="width: 100%; text-align: center;">
	 	<h2 style="color: red;">
	 		<?php
	 			echo $bcdErro ?? ''; 
	 			echo $erroBCD ?? '';
	 		?>
	 	</h2>
 	</div>

	<div class="container">
	  
		<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST" id="formLoginUsuario">
			<div class="form-group">
				<label class="control-label" for="inputLogin">Login</label>
				<input class="form-control" type="text" name="login-email" id="inputLoginEmail" required maxlength="40" value="<?php echo $login_email ?? ''; ?>">
				<span class="error"><?php echo $erros['login_email']; ?></span>
			</div>
			<div class="form-group">
				<label class="control-label" for="inputSenha">Senha</label>
				<input class="form-control" type="password" name="senha" id="inputSenha" required maxlength="40">
				<span class="error"><?php echo $erros['senha']; ?></span>
			</div>
			 <input type="submit" class="btn btn-default" name="logar"></input>
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
			$("#formLoginUsuario").validate();
		});

	</script>

 </body>

 </html>