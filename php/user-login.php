<?php 
    if(isset($_POST['logar']))
    {
        try
        {
            require('classes/BancoDados.php');
            require('classes/User.php');

            $conexao = (new Conexao())->conectar();
            if(!empty($conexao))
            {
                $login_email = trim(htmlspecialchars($_POST['login_email']));
        		$senha = trim(htmlspecialchars($_POST['senha']));

                $usuario = new Usuario($conexao);

                $resultado = $usuario->logar($login_email, $senha);

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