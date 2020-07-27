<?php 

	$nomeSite = "Projeto";

    $erros = ['login' => '', 'email' => '', 'senha' => '', 'confirmarSenha' => ''];

    if(isset($_POST['cadastrar']))
    {
        $login = htmlspecialchars($_POST['login']);
        $email = htmlspecialchars($_POST['email']);
        $senha = htmlspecialchars($_POST['senha']);
        $confirmarSenha = htmlspecialchars($_POST['confirmSenha']);

        if( empty($login) )
        {
            $erros['login'] = "Preencha o login";          
        }
        else if( strlen($login) > 40)
        {
        	 $erros['login'] = "Login deve possuir no máximo 40 caracteres";
        }

        if( empty($email) )
        {
            $erros['email'] = "Preencha o e-mail";
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
            $erros['senha'] = "Preencha a senha";
        }
        else if( strlen($senha) > 40)
        {
        	 $erros['senha'] = "Senha deve possuir no máximo 40 caracteres";
        }

        if( empty($confirmarSenha) )
        {
            $erros['confirmarSenha'] = "Preencha a senha novamente";
        }
        else if($senha != $confirmarSenha)
        {
            $erros['confirmarSenha'] = "As senhas não coincidem";
        }

        if(!array_filter($erros))
        {
            include('banco_dados/bcd_connect.php');

            $errosBCD = ['login' => '', 'email' => ''];

            $login = mysqli_real_escape_string($conexao, $_POST['login']);
            $email = mysqli_real_escape_string($conexao, $_POST['email']);
            $senha = mysqli_real_escape_string($conexao, $_POST['senha']);

            $loginBanco = mysqli_query($conexao, "SELECT * FROM usuarios WHERE login = '$login'");
            $emailBanco = mysqli_query($conexao, "SELECT * FROM usuarios WHERE email = '$email'");

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

            if(!array_filter($errosBCD))
            {
                $comandoSQL = "INSERT INTO usuarios (login, email, senha) VALUES ('$login', '$email', '$senha');";
                
                if(mysqli_query($conexao, $comandoSQL))
                {
                    header('location: usuario-login.php');
                }
                else
                {
                    echo "Erro de comando: " . mysqli_error($conexao);
                }
            }
        }
    }

 ?>


 <!DOCTYPE html>
 <html lang="pt-br">

 <head>

 	<title><?php echo $nomeSite; ?> - Registrar-se</title>
 	
 	<style type="text/css">
 		
 		.error
 		{
 			color: red;
 			font-size: 16px;
 		}

 	</style>

 	<?php require('cabecalho.php') ?>

 	<h1>Cadastrar-se</h1>

	<div class="container">
	  
		<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST" id="formCadUsuario">
			<div class="form-group">
				<label class="control-label" for="inputLogin">Login</label>
				<input class="form-control" type="text" name="login" id="inputLogin" required maxlength="40">
				<span class="error"><?php echo $erros['login']; ?></span>
			</div>
			<div class="form-group">
				<label class="control-label" for="inputEmail">E-mail</label>
				<input class="form-control" type="email" name="email" id="inputEmail" required maxlength="50">
				<span class="error"><?php echo $erros['email']; ?></span>
			</div>
			<div class="form-group">
				<label class="control-label" for="inputSenha">Senha</label>
				<input class="form-control" type="password" name="senha" id="inputSenha" required maxlength="40">
				<span class="error"><?php echo $erros['senha']; ?></span>
			</div>
			<div class="form-group">
				<label class="control-label" for="inputConfirmSenha">Confirmar Senha</label>
				<input class="form-control" type="password" name="confirmSenha" id="inputConfirmSenha" required maxlength="40">
				<span class="error"><?php echo $erros['confirmarSenha']; ?></span>
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
			$("#formCadUsuario").validate();
		});

	</script>

 </body>

 </html>