<?php 

	class Usuario
	{
		private $conexao;
		private $tokenGenerated;

		public function __construct($conexao)
		{
			$this->conexao = $conexao;
		}

		public function cadastrar($login, $email, $senha, $confirmarSenha)
		{
			try
			{
				$erros['login'] = $this->loginIsValid($login);
				$erros['email'] = $this->emailIsValid($email);
				$erros['senha'] = $this->senhaIsValid($senha);
				$erros['confirmarSenha'] = $this->confirmarSenhaIsValid($senha, $confirmarSenha);

				if(!array_filter($erros))
				{
					$errosBCD['login'] = $this->existLogin($login);
					$errosBCD['email'] = $this->existEmail($email);

					if(!array_filter($errosBCD))
					{
						$login = mysqli_real_escape_string($this->conexao, $login);
						$email = mysqli_real_escape_string($this->conexao, $email);
						$senha = mysqli_real_escape_string($this->conexao, $senha);

						$senha = $this->encript($senha);
						$token = $this->generateToken();

						$comandoSQL = "INSERT INTO usuarios (login, email, senha, confirmado, token) VALUES ('$login', '$email', '$senha', 0, '$token');";
	                        
	                    if(mysqli_query($this->conexao, $comandoSQL))
	                    {
	                    	return 1;
	                    }
	                    else
	                    {
	                    	return 'Não foi possível cadastrar no banco de dados';
	                    }
					}
					else
					{
						return $errosBCD;
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

		public function logar($login_email, $senha)
		{
			try
			{
				$erros['login_email'] = $this->loginEmailIsValid($login_email);
				$erros['senha'] = $this->senhaIsValid($senha);

				if(!array_filter($erros))
				{
					$loginEmail = mysqli_real_escape_string($this->conexao, $login_email);
					$senha = mysqli_real_escape_string($this->conexao, $senha);

					$senha = $this->encript($senha);

					$comandoSQL = "SELECT id, login, senha FROM usuarios WHERE login = '$loginEmail' OR email = '$loginEmail';";

			        $resultado = mysqli_query($this->conexao, $comandoSQL);

			        $usuario = mysqli_fetch_assoc($resultado);

			        if(empty($usuario))
			        {
			            return "Login e E-mail não encontrado";
			        }
			        else
			        {
			            if($senha != $usuario['senha'])
			            {
			                return "Senha incorreta";
			            }
			            else
			            {
			                session_start();
			                unset($_SESSION['usuario']);
			                $_SESSION['usuario'] = $usuario;
			                session_write_close();
			                
			                return 1;
			            }
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
				if(isset($resultado))
					mysqli_free_result($resultado);

				mysqli_close($this->conexao);
			}
		}

		public function buscaUsuario($idUsuario)
		{
			try
			{
				$idUsuario = mysqli_real_escape_string($this->conexao, $idUsuario);

		    	$comandoSQL = "SELECT id, login, email, foto FROM usuarios WHERE id = $idUsuario";

				$resultado = mysqli_query($this->conexao, $comandoSQL);

				if($resultado -> num_rows)
				{
					$usuario = mysqli_fetch_all($resultado, MYSQLI_ASSOC)[0];
					return $usuario;
				}
				else
				{
					return "Não foi possível localizar o usuário";
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

		public function editar($login, $email, $senhaAtual, $imagem, $idUsuario)
		{
			try
			{
				$erros['login'] = $this->loginIsValid($login);
				$erros['email'] = $this->emailIsValid($email);
				$erros['senhaAtual'] = $this->senhaIsValid($senhaAtual);
				$erros['imagem'] = $this->imagemIsValid($imagem);

				if(!array_filter($erros))
				{
					$errosBCD['login'] = $this->existLoginUser($login, $idUsuario);
					$errosBCD['email'] = $this->existEmailUser($email, $idUsuario);
					$errosBCD['senhaAtual'] = $this->senhaIsCorrect($senhaAtual, $idUsuario);

					if(!array_filter($errosBCD))
					{
						$login = mysqli_real_escape_string($this->conexao, $login);
						$email = mysqli_real_escape_string($this->conexao, $email);
						$senhaAtual = mysqli_real_escape_string($this->conexao, $senhaAtual);
						$idUsuario = mysqli_real_escape_string($this->conexao, $idUsuario);

						//Se não tiver imagem, não será alterada
						if($imagem['error'] == 4)
						{
							$comandoSQL = "UPDATE usuarios SET login = '$login', email = '$email' WHERE id = $idUsuario;";
						}
						else
						{
							$nomeFinal = time().'.jpg';
							if (move_uploaded_file($imagem['tmp_name'], $nomeFinal)) {
								$tamanhoImg = filesize($nomeFinal);

								$mysqlImg = addslashes(fread(fopen($nomeFinal, "r"), $tamanhoImg));

								$comandoSQL = "UPDATE usuarios SET login = '$login', email = '$email', foto = '$mysqlImg' WHERE id = $idUsuario;";
							}
							unlink($nomeFinal);
						}

						if(mysqli_query($this->conexao, $comandoSQL))
		                {
		                	return $this->logar($login, $senhaAtual);
		                }
		                else
	                    {
	                    	return 'Não foi possível editar no banco de dados';
	                    }
					}
					else
					{
						return $errosBCD;
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
		}

		public function alterarSenha($senhaAtual, $novaSenha, $confirmNovaSenha, $idUsuario)
		{
			try
			{
				$erros['senhaAtual'] = $this->senhaIsValid($senhaAtual);
				$erros['novaSenha'] = $this->senhaIsValid($novaSenha);
				$erros['confirmNovaSenha'] = $this->confirmarSenhaIsValid($novaSenha, $confirmNovaSenha);

				if(!array_filter($erros))
				{
					$errosBCD['senhaAtual'] = $this->senhaIsCorrect($senhaAtual, $idUsuario);

					if(!array_filter($errosBCD))
					{
						$novaSenha = mysqli_real_escape_string($this->conexao, $novaSenha);
						$idUsuario = mysqli_real_escape_string($this->conexao, $idUsuario);

						$novaSenha = $this->encript($novaSenha);

						$comandoSQL = "UPDATE usuarios SET senha = '$novaSenha' WHERE id = $idUsuario;";

						if(mysqli_query($this->conexao, $comandoSQL))
		                {
		                    return 1;
		                }
		                else
	                    {
	                    	return 'Não foi possível alterar a senha no banco de dados';
	                    }
					}
					else
					{
						return $errosBCD;
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
		}

		private function loginIsValid($login)
		{
			if(empty($login))
			{
				return "Preencha o login";
			}
			else if (strlen($login) > 40)
			{
				return "Login deve possuir no máximo 40 caracteres";
			}
		}

		private function emailIsValid($email)
		{
			if(empty($email))
	        {
	            return "Preencha o e-mail";
	        }
	        else if(strlen($email) > 50)
	        {
	        	return "E-mail deve possuir no máximo 50 caracteres";
	        }
	        else if(!filter_var($email, FILTER_VALIDATE_EMAIL))
	        {
	        	return "Digite um e-mail válido";
	        }
		}

		private function senhaIsValid($senha)
		{
			if( empty($senha) )
	        {
	            return "Preencha a senha";
	        }
	        else if( strlen($senha) > 40)
	        {
	        	return "Senha deve possuir no máximo 40 caracteres";
	        }
		}

		private function confirmarSenhaIsValid($senha, $confirmarSenha)
		{
			if( empty($confirmarSenha) )
	        {
	            return "Preencha a senha novamente";
	        }
	        else if($senha != $confirmarSenha)
	        {
	            return "As senhas não coincidem";
	        }
		}

		private function loginEmailIsValid($loginEmail)
		{
			if(empty($loginEmail))
			{
				return "Preencha o Login/E-mail";
			}
			else if(strlen($loginEmail) > 50)
	        {
	        	return "Login/Email deve possuir no máximo 50 caracteres";
	        }
		}

		private function imagemIsValid($imagem)
		{
			if($imagem['error'] != 4) 
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

		private function existLogin($login)
		{
			try
			{
				$login = mysqli_real_escape_string($this->conexao, $login);

				$loginBanco = mysqli_query($this->conexao, "SELECT * FROM usuarios WHERE login = '$login'");

				if($loginBanco -> num_rows)
				{
					return "Login já cadastrado";
				}
			}
			catch(Exception $e)
			{
				return 'Ocorreu um erro';
			}
			finally
			{
				if(isset($loginBanco))
					mysqli_free_result($loginBanco);
			}
		}

		private function existLoginUser($login, $idUsuario)
		{
			try
			{
				$login = mysqli_real_escape_string($this->conexao, $login);
				$idUsuario = mysqli_real_escape_string($this->conexao, $idUsuario);

				$loginBanco = mysqli_query($this->conexao, "SELECT * FROM usuarios WHERE login = '$login' AND id != '$idUsuario'");

				if($loginBanco -> num_rows)
				{
					return "Login já cadastrado";
				}
			}
			catch(Exception $e)
			{
				return 'Ocorreu um erro';
			}
			finally
			{
				if(isset($loginBanco))
					mysqli_free_result($loginBanco);
			}
		}

		private function existEmail($email)
		{
			try
			{
				$email = mysqli_real_escape_string($this->conexao, $email);

				$emailBanco = mysqli_query($this->conexao, "SELECT * FROM usuarios WHERE email = '$email'");

				if($emailBanco -> num_rows)
				{
					return "E-mail já cadastrado";
				}
			}
			catch(Exception $e)
			{
				return 'Ocorreu um erro';
			}
			finally
			{
				if(isset($emailBanco))
					mysqli_free_result($emailBanco);
			}
		}

		private function existEmailUser($email, $idUsuario)
		{
			try
			{
				$email = mysqli_real_escape_string($this->conexao, $email);
				$idUsuario = mysqli_real_escape_string($this->conexao, $idUsuario);

				$emailBanco = mysqli_query($this->conexao, "SELECT * FROM usuarios WHERE email = '$email' AND id != '$idUsuario'");

				if($emailBanco -> num_rows)
				{
					return "E-mail já cadastrado";
				}
			}
			catch(Exception $e)
			{
				return 'Ocorreu um erro';
			}
			finally
			{
				if(isset($emailBanco))
					mysqli_free_result($emailBanco);
			}
		}

		private function senhaIsCorrect($senha, $idUsuario)
		{
			try
			{
				$senha = mysqli_real_escape_string($this->conexao, $senha);
				$idUsuario = mysqli_real_escape_string($this->conexao, $idUsuario);

				$senha = $this->encript($senha);

				$resultadoSenhaBanco = mysqli_query($this->conexao, "SELECT senha FROM usuarios WHERE id = '$idUsuario'");

				$senhaBanco = mysqli_fetch_all($resultadoSenhaBanco, MYSQLI_ASSOC)[0];

				if($senhaBanco['senha'] != $senha)
	            {
	            	return "Senha incorreta";
	            }
			}
			catch(Exception $e)
			{
				return 'Ocorreu um erro';
			}
			finally
			{
				if(isset($resultadoSenhaBanco))
					mysqli_free_result($resultadoSenhaBanco);
			}
		}

		private function encript($value)
		{
			try
			{
				$metodoEncriptamento = "AES-128-CTR";
				$chaveEncriptamento = "TestingEncryption";
				$opcoesEncriptamento = 0;
				$vetorInicialEncriptamento = "7070707070707070";

				$valorEncriptado = openssl_encrypt($value, $metodoEncriptamento, $chaveEncriptamento, $opcoesEncriptamento, $vetorInicialEncriptamento);;

				return $valorEncriptado;
			}
			catch(Exception $e)
			{
				return "Ocorreu um erro ao encriptar a senha";
			}
		}

		private function generateToken()
		{
			try
			{
				$token = null;
				while($token == null)
				{
					$numeroCaracteresToken = 16;
					$listaCaracteres = str_split("abcdefghijklmnopqrstuvwxyz0123456789");

					$charsToken = array();

					for($i = 0 ; $i < $numeroCaracteresToken ; $i++)
					{
						$charAleatorio = rand(0, sizeof($listaCaracteres)-1);
						$charsToken[$i] = $listaCaracteres[$charAleatorio];
					}

					$token = join('', $charsToken);

					if($existeToken = mysqli_query($this->conexao, "SELECT token FROM usuarios WHERE token = '$token'"))
					{
						if($existeToken->num_rows)
						{
							$token = null;
						}
					}
					else
					{
						return "Ocorreu um erro ao gerar o token de confirmação de email";
					}
				}

				$this->tokenGenerated = $token;
				return $token;
			}
			catch(Exception $e)
			{
				return "Ocorreu um erro ao criar o token de confirmação de email";
			}
		}

		public function confirmarToken($token)
		{
			try
			{
				if($resultado = mysqli_query($this->conexao, "SELECT id, login, senha FROM usuarios WHERE token = '$token';"))
				{
					$usuario = mysqli_fetch_assoc($resultado);

					if(mysqli_query($this->conexao, "UPDATE usuarios SET confirmado = 1 WHERE id = ".$usuario['id']))
					{
						return 1;
					}
					else
					{
						return "Ocorreu um erro ao confirmar o token";
					}
				}
				else
				{
					return "Token não encontrado !";
				}
			}
			catch(Exception $e)
			{
				return "Ocorreu um erro ao buscar o token";
			}
		}

		public function isEmailConfirmed($idUsuario)
		{
			try
			{
				if($resultado = mysqli_query($this->conexao, "SELECT confirmado FROM usuarios WHERE id = $idUsuario"))
				{
					$confirmado = mysqli_fetch_assoc($resultado);

					if($confirmado['confirmado'] == 1)
					{
						return 1;
					}
					else
					{
						return "É necessário confirmar o e-mail";
					}
				}
				else
				{
					return "Ocorreu um erro ao buscar o usuario";
				}
			}
			catch(Exception $e)
			{
				return "Ocorreu um erro pesquisar se o email está confirmado";
			}
		}

		public function getTokenGenerated()
		{
			if(isset($this->tokenGenerated))
			{
				return $this->tokenGenerated;
			}
			else
			{
				return null;
			}
		}
	}
 ?>