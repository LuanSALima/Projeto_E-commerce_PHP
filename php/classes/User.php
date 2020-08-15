<?php 

	class Usuario
	{
		private $conexao;

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

						$comandoSQL = "INSERT INTO usuarios (login, email, senha) VALUES ('$login', '$email', '$senha');";
	                        
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
				if(isset($loginBanco))
					mysqli_free_result($emailBanco);
			}
		}
	}
 ?>