<?php 
	
	class Produto
	{
		private $conexao;

		public function __construct($conexao)
		{
			$this->conexao = $conexao;
		}

		public function cadastrar($nome, $preco, $imagem, $idUsuario)
		{
			try
			{
				$precoDolar = $this->convertToDolar($preco);

				$erros['nome'] = $this->nomeIsValid($nome);
				$erros['preco'] = $this->precoIsValid($precoDolar);
				$erros['imagem'] = $this->imagemIsValid($imagem);

				if(!array_filter($erros))
				{
					$nome = mysqli_real_escape_string($this->conexao, $nome);
					$precoDolar = mysqli_real_escape_string($this->conexao, $precoDolar);
					$idUsuario = mysqli_real_escape_string($this->conexao, $idUsuario);

					$nomeFinal = time().'.jpg';
					if (move_uploaded_file($imagem['tmp_name'], $nomeFinal)) {
						$tamanhoImg = filesize($nomeFinal);

						$mysqlImg = addslashes(fread(fopen($nomeFinal, "r"), $tamanhoImg));

						$comandoSQL = "INSERT INTO produto (id_usuario, nome, preco, imagem) VALUES ('$idUsuario', '$nome', '$precoDolar', '$mysqlImg');";

						unlink($nomeFinal);

						if(mysqli_query($this->conexao, $comandoSQL))
		                {
		                    return 1;
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
				return 'Ocorreu um erro interno'.$e;
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
				return 'Ocorreu um erro interno'.$e;
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

		public function deletar()
		{

		}

		public function detalhes()
		{

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