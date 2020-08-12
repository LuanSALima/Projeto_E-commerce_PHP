<?php 

    if(isset($_POST['logar']))
    {
    	require('classes/User.php');

        $login_email = htmlspecialchars($_POST['login-email']);
        $senha = htmlspecialchars($_POST['senha']);

        $erros = Usuario::errosLogin($login_email, $senha);

        if(empty($erros))
        {
        	try
        	{
        		if(isset($_POST['JSON']))
                {
                    require('../../bcd/bcd_connect.php');
                }
                else
                {
                    require('../bcd/bcd_connect.php');
                }

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
        	catch(Exception $e)
            {
                $bcdErro = $e; 
            }   
        }
    }

 ?>