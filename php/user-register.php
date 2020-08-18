<?php
    if(isset($_POST['cadastrar']))
    {
        try
        {
            require('classes/BancoDados.php');
            require('classes/User.php');

            $conexao = (new Conexao())->conectar();
            if(!empty($conexao))
            {
                $login = trim(htmlspecialchars($_POST['login']));
                $email = trim(htmlspecialchars($_POST['email']));
                $senha = trim(htmlspecialchars($_POST['senha']));
                $confirmarSenha = trim(htmlspecialchars($_POST['confirmSenha']));

                $usuario = new Usuario($conexao);

                $resultado = $usuario->cadastrar($login, $email, $senha, $confirmarSenha);

                if($resultado === 1)
                {
                    if(isset($_POST['JSON']))
                    {
                        echo json_encode(array('sucesso' => 1));
                    }
                    else
                    {
                        header('location: login-usuario.php');
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