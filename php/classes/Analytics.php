<?php 

	class Access
	{
		private $conexao;

		public function __construct($conexao)
		{
			$this->conexao = $conexao;
		}

		public function registerAccess($idUsuario, $idProduto)
		{
			//Grava o Acesso do Usuário no Produto
			try
			{
				$idUsuario = mysqli_real_escape_string($this->conexao, $idUsuario);
				$idProduto = mysqli_real_escape_string($this->conexao, $idProduto);

				$comandoSQL = "INSERT INTO acessosproduto (id_usuario, id_produto) VALUES ($idUsuario, $idProduto);";
	                        
                if(mysqli_query($this->conexao, $comandoSQL))
                {
                	return 1;
                }
                else
                {
                	return 'Ocorreu um problema ao registrar o acesso ao produto';
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

		public function searchAcessProduct($idProduto)
		{
			//Retorna a lista de todos os usuários que acessaram o produto
		}

		public function searchAcessUser($idUsuario)
		{
			//Retorna a lista de todos os produtos que o usuário acessou
		}

		public function searchAcessUsersProducts($idUsuario, $idProduto)
		{
			//Retorna a lista de todos os usuários que acessaram os produtos do usuario
		}

	}

 ?>