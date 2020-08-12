<?php 
    
    if(isset($_POST['cadastrar']))
    {
        require('classes/User.php');

        $login = htmlspecialchars($_POST['login']);
        $email = htmlspecialchars($_POST['email']);
        $senha = htmlspecialchars($_POST['senha']);
        $confirmarSenha = htmlspecialchars($_POST['confirmSenha']);

        $erros = Usuario::errosUsuario($login, $email, $senha, $confirmarSenha);

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
                    $errosBCD = [];

                    $login = trim(mysqli_real_escape_string($conexao, $_POST['login']));
                    $email = trim(mysqli_real_escape_string($conexao, $_POST['email']));
                    $senha = trim(mysqli_real_escape_string($conexao, $_POST['senha']));

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
                            mysqli_close($conexao);

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
                            if(isset($_POST['JSON']))
                            {
                                echo json_encode(array('erro' => 1, 'mensagem' => "Ocorreu um erro ao inserir no banco de dados"));
                            }
                            else
                            {
                                $bcdErro =  "Ocorreu um erro ao inserir no banco de dados";
                                //mysqli_error($conexao);
                            }
                        }
                    }
                    else
                    {
                        if(isset($_POST['JSON']))
                        {
                            echo json_encode(array('erro' => 1, 'campos' => $errosBCD));
                        }
                    }
                }
                else
                {
                    if(isset($_POST['JSON']))
                    {
                        echo json_encode(array('erro' => 1, 'mensagem' => 'Houve um problema no banco de dados'));
                    }
                    else
                    {
                        $bcdErro = "Houve um problema no banco de dados";
                    }
                }
            }
            catch(Exception $e)
            {
                if(isset($_POST['JSON']))
                {
                    echo json_encode(array('erro' => 1, 'mensagem' => "Ocorreu um erro interno no servidor"));
                }
                else
                {
                    $bcdErro = $e;
                }
            }
        }
        else
        {
            if(isset($_POST['JSON']))
            {
                echo json_encode(array('erro' => 1, 'campos' => $erros));
            }
        }
    }
    else
    {
        header('location: ../index.php');
    }

 ?>