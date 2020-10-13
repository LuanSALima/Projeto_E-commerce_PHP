<?php

	if(isset($_POST['esqueciSenha']))
	{
		try
		{
			require('classes/BancoDados.php');
			require('classes/User.php');

			$conexao = (new Conexao())->conectar();
	        if(!empty($conexao))
	        {
	        	$classeUsuario = new Usuario($conexao);

	        	$email = trim(htmlspecialchars($_POST['email']));

	        	$resultado = $classeUsuario->gerarTokenEmail($email);

	        	if($resultado === 1)
	        	{
	        		require('classes/Email.php');

	        		$classeEmail = new Email();

	        		$resultadoEmail = $classeEmail->sendPasswordRecover($email, $classeUsuario->getTokenGenerated());

	        		if($resultadoEmail == 1)
	        		{
	        			$emailEnviado = "O e-mail foi enviado !";

	        			if(isset($_POST['JSON']))
	                    {
	                        echo json_encode(array('sucesso' => 1, 'mensagem' => $emailEnviado));
	                    }
	        		}
	        		else if (is_string($resultadoEmail))
	        		{
	        			$erro = "Ocorreu um erro ao enviar o email para recuperar senha";
	        			//$erro = $resultadoEmail;

	        			if(isset($_POST['JSON']))
	                    {
	                        echo json_encode(array('erro' => 1, 'mensagem' => $erro));
	                    }
	        		}

	        	}
	        	else if(is_string($resultado))
	        	{
	        		$erro = $resultado;

	        		if(isset($_POST['JSON']))
                    {
                        echo json_encode(array('erro' => 1, 'mensagem' => $erro));
                    }
	        	}
	        	else if(is_array($resultado))
	        	{
	        		$erros = $resultado;

	        		if(isset($_POST['JSON']))
					{
						echo json_encode(array('erro' => 1, 'campos' => $erros));
					}
	        	}
	        	else
	        	{
	        		$erro = "Resultado inesperado do servidor";

	        		if(isset($_POST['JSON']))
                    {
                        echo json_encode(array('erro' => 1, 'mensagem' => $erro));
                    }
	        	}
	        }
	        else
	        {
	        	$erro = "Ocorreu um problema ao conectar ao Banco de Dados";

	        	if(isset($_POST['JSON']))
                {
                    echo json_encode(array('erro' => 1, 'mensagem' => $erro));
                }
	        }
		}
		catch(Exception $e)
    	{
    		$bcdErro = "Ocorreu um problema interno no servidor";

            if(isset($_POST['JSON']))
            {
                echo json_encode(array('erro' => 1, 'mensagem' => $bcdErro));
            }
    	}
	}
	else if(isset($_POST['recuperarSenha']))
	{
		try
		{
			$novaSenha = trim(htmlspecialchars($_POST['novaSenha']));
	        $confirmNovaSenha = trim(htmlspecialchars($_POST['confirmNovaSenha']));
	        $token = trim(htmlspecialchars($_POST['token']));

	        $_GET['token'] = $token;

	        require('classes/BancoDados.php');
			require('classes/User.php');

			$conexao = (new Conexao())->conectar();
	        if(!empty($conexao))
	        {
	        	$classeUsuario = new Usuario($conexao);

	        	$resultado = $classeUsuario->recuperarSenha($token, $novaSenha, $confirmNovaSenha);

				if($resultado === 1)
				{
					if(isset($_POST['JSON']))
                    {
                        echo json_encode(array('sucesso' => 1));
                    }
                    else
                    {
                        header('location: usuario-login.php');
                    }
				}
				else if(is_array($resultado))
				{
					$erros = $resultado;

					if(isset($_POST['JSON']))
					{
						echo json_encode(array('erro' => 1, 'campos' => $erros));
					}
				}
				else
				{
					$erro = $resultado;

					if(isset($_POST['JSON']))
                    {
                        echo json_encode(array('erro' => 1, 'mensagem' => $erro));
                    }
				}
	        }
	        else
	        {
	        	$erro = "Ocorreu um problema ao conectar ao Banco de Dados";

	        	if(isset($_POST['JSON']))
                {
                    echo json_encode(array('erro' => 1, 'mensagem' => $erro));
                }
	        }
		}
		catch(Exception $e)
    	{
    		$bcdErro = "Ocorreu um problema interno no servidor";

            if(isset($_POST['JSON']))
            {
                echo json_encode(array('erro' => 1, 'mensagem' => $bcdErro));
            }
    	}
	}
	else
    {
        if(isset($_POST['JSON']))
        {
            echo json_encode(array('erro' => 1, 'mensagem' => "Ocorreu um problema ao enviar os dados"));
        }
        else
        {
            header('location: ../index.php');
        }
    }

 ?>