<?php 
	if(isset($_POST['alterar']))
    {
    	try
    	{
    		require('classes/BancoDados.php');
            require('classes/User.php');

            session_start();

			$usuarioLogado = $_SESSION['usuario'] ?? '';

		    session_write_close();

            $conexao = (new Conexao())->conectar();
            if(!empty($conexao))
            {
            	$senhaAtual = trim(htmlspecialchars($_POST['senhaAtual']));
                $novaSenha = trim(htmlspecialchars($_POST['novaSenha']));
                $confirmNovaSenha = trim(htmlspecialchars($_POST['confirmNovaSenha']));
        		$idUsuario = $usuarioLogado['id'];

				$usuario = new Usuario($conexao);

                $resultado = $usuario->alterarSenha($senhaAtual, $novaSenha, $confirmNovaSenha, $idUsuario);

                if($resultado === 1)
                {
                    if(isset($_POST['JSON']))
                    {
                        echo json_encode(array('sucesso' => 1));
                    }
                    else
                    {
                        header('location: index.php');
                    }
                }
                else
                {
                    if(is_array($resultado))
                    {
                        $erros = $resultado;

                        if(isset($_POST['JSON']))
                        {
                            echo json_encode(array('erro' => 1, 'campos' => $erros));
                        }
                    }
                    else
                    {
                        $bcdErro = $resultado;

                        if(isset($_POST['JSON']))
                        {
                            echo json_encode(array('erro' => 1, 'mensagem' => $bcdErro));
                        }
                    }
                }
            }
            else
            {
                $bcdErro = "Houve um problema no banco de dados";

                if(isset($_POST['JSON']))
                {
                    echo json_encode(array('erro' => 1, 'mensagem' => $bcdErro));
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
 ?>