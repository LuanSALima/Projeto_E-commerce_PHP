<?php 
	
	class Produto
	{
		private $conexao;

		public function __construct($conexao)
		{
			$this->conexao = $conexao;
		}

		public function listar($pagina)
		{
			try
			{
				$qntResultados = 8;


				$pagina = mysqli_real_escape_string($this->conexao, $pagina);
				$qntResultados = mysqli_real_escape_string($this->conexao, $qntResultados);

				
				$buscaTotal = "SELECT COUNT(*) FROM produto;";
				$buscaTotal = mysqli_query($this->conexao, $buscaTotal);
				$totalProdutos = $buscaTotal->fetch_row()[0];

				$totalPaginas = ceil($totalProdutos/$qntResultados);

				if($pagina > $totalPaginas)
					$pagina = $totalPaginas;
				if($pagina < 1)
					$pagina = 1;

		    	$comandoSQL = "SELECT id, id_usuario, nome, preco, imagem FROM produto LIMIT ".(($pagina-1)*$qntResultados).", $qntResultados;";

				$resultado = mysqli_query($this->conexao, $comandoSQL);

				if($resultado -> num_rows)
				{
					$produtos = mysqli_fetch_all($resultado, MYSQLI_ASSOC);

					foreach ($produtos as $produto) {
						$produto['preco'] = $this->convertToReais($produto['preco']);
					}
					
					return array('paginaAtual' => $pagina, 'ultimaPagina' => $totalPaginas, 'produtos' => $produtos);
				}
				else
				{
					return "Não há nenhum produto cadastrado";
				}
			}
			catch(Exception $e)
			{
				return 'Ocorreu um erro interno';
			}
			finally
			{
				if(isset($resultado))
					mysqli_free_result($resultado);

				mysqli_close($this->conexao);
			}
		}

		public function cadastrar($nome, $preco, $imagem, $idUsuario, $tags)
		{
			try
			{
				$precoDolar = $this->convertToDolar($preco);

				$erros['nome'] = $this->nomeIsValid($nome);
				$erros['preco'] = $this->precoIsValid($precoDolar);
				$erros['imagem'] = $this->imagemIsValid($imagem);
				$erros['tags'] = $this->tagIsValid($tags);

				if(!array_filter($erros))
				{
					$nome = mysqli_real_escape_string($this->conexao, $nome);
					$precoDolar = mysqli_real_escape_string($this->conexao, $precoDolar);
					$idUsuario = mysqli_real_escape_string($this->conexao, $idUsuario);

					$nomeFinal = time().'.jpg';
					if (move_uploaded_file($imagem['tmp_name'], $nomeFinal)) {
						$tamanhoImg = filesize($nomeFinal);

						$mysqlImg = addslashes(fread(fopen($nomeFinal, "r"), $tamanhoImg));
						unlink($nomeFinal);

						$comandoSQL = "INSERT INTO produto (id_usuario, nome, preco, imagem) VALUES ('$idUsuario', '$nome', '$precoDolar', '$mysqlImg');";

						if(mysqli_query($this->conexao, $comandoSQL))
		                {
		                    $ultimoID = mysqli_insert_id($this->conexao);

		                    return $this->cadastrarTags($ultimoID, $tags);
		                }
		                else
		                {
		                	return "Não foi possível cadastrar no banco de dados";
		                }
					}
					else
					{
						return "Ocorreu um erro ao cadastrar a imagem";
					}
				}
				else
				{
					return $erros;
				}
			}
			catch(Exception $e)
			{
				return 'Ocorreu um erro interno';
			}
			finally
			{
				mysqli_close($this->conexao);
			}
		}

		public function editar($nome, $preco, $imagem, $idUsuario, $idProduto)
		{
			try
			{
				$precoDolar = $this->convertToDolar($preco);

				$erros['nome'] = $this->nomeIsValid($nome);
				$erros['preco'] = $this->precoIsValid($precoDolar);
				if($imagem['error'] != 4)
				{
					$erros['imagem'] = $this->imagemIsValid($imagem);
				}

				if(!array_filter($erros))
				{
					$nome = mysqli_real_escape_string($this->conexao, $nome);
					$precoDolar = mysqli_real_escape_string($this->conexao, $precoDolar);
					$idProduto = mysqli_real_escape_string($this->conexao, $idProduto);
					$idUsuario = mysqli_real_escape_string($this->conexao, $idUsuario);

					if($imagem['error'] == 4)
					{
						$comandoSQL = "UPDATE produto SET nome = '$nome', preco = '$precoDolar'WHERE id = $idProduto AND id_usuario = $idUsuario;";
					}
					else
					{
						$nomeFinal = time().'.jpg';
						if (move_uploaded_file($imagem['tmp_name'], $nomeFinal)) {
							$tamanhoImg = filesize($nomeFinal);

							$mysqlImg = addslashes(fread(fopen($nomeFinal, "r"), $tamanhoImg));

							$comandoSQL = "UPDATE produto SET nome = '$nome', preco = '$precoDolar', imagem = '$mysqlImg' WHERE id = $idProduto AND id_usuario = $idUsuario;";
						}
						unlink($nomeFinal);
					}

					if(mysqli_query($this->conexao, $comandoSQL))
	                {
	                    return 1;
	                }
	                else
	                {
	                	return "Não foi possível editar no banco de dados";
	                }
				}
				else
				{
					return $erros;
				}
			}
			catch(Exception $e)
			{
				return 'Ocorreu um erro interno';
			}
			finally
			{
				mysqli_close($this->conexao);
			}
		}

		public function buscaProduto($idProduto)
		{
			try
			{
				$idProduto = mysqli_real_escape_string($this->conexao, $idProduto);

		    	$comandoSQL = "SELECT id, id_usuario, nome, preco, imagem FROM produto WHERE id = $idProduto";

				$resultado = mysqli_query($this->conexao, $comandoSQL);

				if($resultado -> num_rows)
				{
					$produto = mysqli_fetch_all($resultado, MYSQLI_ASSOC)[0];
					$produto['preco'] = $this->convertToReais($produto['preco']);
					return $produto;
				}
				else
				{
					return "Não foi possível localizar o produto";
				}
			}
			catch(Exception $e)
			{
				return 'Ocorreu um erro interno';
			}
			finally
			{
				if(isset($resultado))
					mysqli_free_result($resultado);

				mysqli_close($this->conexao);
			}
		}

		public function buscaProdutoUsuario()
		{

		}

		public function listaTags()
		{
			try
			{
				$comandoSQL = "SELECT id, nome FROM tag";

				$resultado = mysqli_query($this->conexao, $comandoSQL);

				return mysqli_fetch_all($resultado, MYSQLI_ASSOC);
			}
			catch(Exception $e)
			{
				return "Ocorreu um problema ao carregar as tags";
			}
			finally
			{
				if(isset($resultado))
					mysqli_free_result($resultado);
			}
		}

		public function deletar($idProduto, $idUsuario)
		{
			try
			{
				$dono = $this->ownerProduct($idProduto, $idUsuario);

				if($dono === 1)
				{
					$limpaAvaliacoes = $this->removeProductRating($idProduto);

					if($limpaAvaliacoes === 1)
					{
						$idProduto = mysqli_real_escape_string($this->conexao, $idProduto);
						$idUsuario = mysqli_real_escape_string($this->conexao, $idUsuario);

						$comandoSQL = "DELETE FROM produto WHERE id = $idProduto AND id_usuario = $idUsuario;";

						if(mysqli_query($this->conexao, $comandoSQL))
		                {
		                    return 1;
		                }
		                else
		                {
		                	return "Não foi possível remover do banco de dados";
		                }
					}
					else
					{
						return $limpaAvaliacoes;
					}
				}
				else if($dono === 0)
				{
					return "Não é possível remover o produto de outro usuário";
				}
				else
				{
					return $dono;
				}
			}
			catch(Exception $e)
			{
				return 'Ocorreu um erro interno';
			}
			finally
			{
				mysqli_close($this->conexao);
			}
		}

		private function removeProductRating($idProduto)
		{
			try
			{
				$idProduto = mysqli_real_escape_string($this->conexao, $idProduto);

				$comandoSQL = "DELETE FROM avaliacaoproduto WHERE id_produto = '$idProduto';";

				if(mysqli_query($this->conexao, $comandoSQL))
                {
                    return 1;
                }
                else
                {
                	return "Não foi possível remover as avaliações do banco de dados";
                }
			}
			catch(Exception $e)
			{
				return 'Ocorreu um erro interno';
			}
		}

		private function cadastrarTags($idProduto, $arrayTags)
		{
			try
			{
				$idProduto = mysqli_real_escape_string($this->conexao, $idProduto);

				foreach ($arrayTags as $tag) 
				{
					$tag = mysqli_real_escape_string($this->conexao, $tag);

					$comandoSQL = "INSERT INTO tagsproduto (id_produto, id_tag) VALUES ($idProduto, $tag);";

					if(!mysqli_query($this->conexao, $comandoSQL))
	                {
	                	return 'Não foi possivel cadastrar as tags corretamente';
	                }

				}
			}
			catch(Exception $e)
			{
				return "Ocorreu um erro ao cadastrar as tags";
			}
		}

		public function detalhes()
		{

		}

		private function ownerProduct($idProduto, $idUsuario)
		{
			try
			{
				$idProduto = mysqli_real_escape_string($this->conexao, $idProduto);
				$idUsuario = mysqli_real_escape_string($this->conexao, $idUsuario);

				$resultado = mysqli_query($this->conexao, "SELECT * FROM produto WHERE id = '$idProduto' AND id_usuario = '$idUsuario'");

				if($resultado -> num_rows)
				{
					return 1;
				}
				else
				{
					return 0;
				}
			}
			catch(Exception $e)
			{
				return 'Ocorreu um erro';
			}
			finally
			{
				if(isset($resultado))
					mysqli_free_result($resultado);
			}
		}

		private function nomeIsValid($nome)
		{
			if(empty($nome))
	        {
	            return "Preencha o Nome";          
	        }
	        else if(strlen($nome) > 100)
	        {
	        	 return "Nome deve possuir no máximo 100 caracteres";
	        }
		}

		private function precoIsValid($preco)
		{
			if(empty($preco))
	        {
	            return "Preencha o Preço";
	        }
	        else if(!is_numeric($preco))
	        {
	        	return "Preço deve ser um número";
	        }
		}

		private function imagemIsValid($imagem)
		{
			if($imagem['error'] == 4) 
			{
				return "É necessário colocar uma imagem do seu produto";
			}
			else
			{
				$tiposImagemValidas = ["image/jpeg", "image/png"];

				if(!in_array($imagem['type'], $tiposImagemValidas))
				{
					return "O arquivo deve ser uma imagem";
				}
				else if($imagem['size'] > 102400)
				{
					return "A imagem deve ter no máximo 100 KB";
				}
			}
		}

		private function tagIsValid($tags)
		{	
			if(empty($tags))
			{
				return "É necessário escolher pelo menos uma tag";
			}
			else if(!is_array($tags))
			{
				return "Tags inválida";
			}
			else if(!array_filter($tags, "is_numeric"))
			{
				return "Tags inválidas";
			}
		}

		private function convertToDolar($valor)
		{
			$valorDolar = str_replace('.', '', $valor);
			$valorDolar = str_replace(',', '.', $valorDolar);

			return $valorDolar;
		}

		private function convertToReais($valor)
		{
			$valorReais = str_replace('.', ',', $valor);

			return $valorReais;
		}
	}
 ?>