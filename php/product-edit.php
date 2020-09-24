<?php
    if(isset($_POST['editar']))
    {
        try
        {
            require('classes/BancoDados.php');
            require('classes/Product.php');

            session_start();

			$usuario = $_SESSION['usuario'] ?? '';

		    session_write_close();

            $conexao = (new Conexao())->conectar();
            if(!empty($conexao))
            {
                $nome = trim(htmlspecialchars($_POST['nome']));
				$preco = trim(htmlspecialchars($_POST['preco']));
				$imagem = $_FILES['imagem'];
				$idUsuario = $usuario['id'];
                $idProduto = trim(htmlspecialchars($_POST['idProduto']));
                $tagsPost =  isset($_POST['tags']) ? $_POST['tags'] : null;

                $produto = new Produto($conexao);

                $resultado = $produto->editar($nome, $preco, $imagem, $idUsuario, $tagsPost, $idProduto);

                if($resultado === 1)
                {
                    if(isset($_POST['JSON']))
                    {
                        echo json_encode(array('sucesso' => 1));
                    }
                    else
                    {
                        header('location: meus-produtos.php');
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