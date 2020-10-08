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
				return 'Ocorreu um erro interno'.$e;
			}
			finally
			{
				mysqli_close($this->conexao);
			}
		}

		public function searchAcessUserProducts($idUsuario)
		{
			//Retorna a lista de todos os produtos com a contagem de acessos total e recente
			try
			{
				$listaAcessos = array();

				date_default_timezone_set('America/Sao_Paulo');

				$idUsuario = mysqli_real_escape_string($this->conexao, $idUsuario);

				if($resultadoProdutos = mysqli_query($this->conexao, "SELECT id, nome FROM produto WHERE id_usuario = $idUsuario"))
				{
					while($produto = mysqli_fetch_assoc($resultadoProdutos))
					{
						$acessosProduto = array('id' => $produto['id'], 'nome' => $produto['nome'], 'total_acessos' => 0, 'ultimo_dia' => 0, 'ultima_semana' => 0, 'ultimo_mes' => 0);
						
						if($acessosProd = mysqli_query($this->conexao, "SELECT data FROM acessosproduto WHERE id_produto = ".$produto['id']))
						{
							while($acesso = mysqli_fetch_assoc($acessosProd))
							{
								$acessosProduto['total_acessos']++;

								$hoje = new DateTime();
								$dataAcesso = new DateTime($acesso['data']);

								$dif = $dataAcesso->diff($hoje);

								if( ($dif->d == 0) && (($hoje->format('d') - $dataAcesso->format('d')) == 0) )
								{
									$acessosProduto['ultimo_dia']++;
								}
								else if($dif->d < 8)
								{
									$acessosProduto['ultima_semana']++;
								}
								else if($dif->m == 1)
								{
									$acessosProduto['ultimo_mes']++;
								}
							}

							mysqli_free_result($acessosProd);
							$acessosProd = null;
						}
						else
						{
							return "Ocorreu um erro ao buscar os acessos de um produto";
						}
						
						array_push($listaAcessos, $acessosProduto);
					}

					mysqli_free_result($resultadoProdutos);
					$resultadoProdutos = null;
				}
				else
				{
					return "Ocorreu um erro ao buscar os produtos do usuário";
				}

				return $listaAcessos;
			}
			catch(Exception $e)
			{
				return 'Ocorreu um erro interno';
			}
			finally
			{
				mysqli_close($this->conexao);

				if(isset($resultadoProdutos))
					mysqli_free_result($resultadoProdutos);

				if(isset($acessosProd))
					mysqli_free_result($acessosProd);
			}
		}

	}

 ?>