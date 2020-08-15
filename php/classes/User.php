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
				return 'Ocorreu um erro '.$e;
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
				return 'Ocorreu um erro '.$e;
			}
		}



		public static function errosUsuario($login, $email, $senha, $confirmarSenha)
		{
			$errors = [];

			$login = trim($login);
			$email = trim($email);
			$senha = trim($senha);
			$confirmarSenha = trim($confirmarSenha);

			if(empty($login))
			{
				$errors['login'] = "Preencha o login";
			}
			else if (strlen($login) > 40)
			{
				$errors['login'] = "Login deve possuir no máximo 40 caracteres";
			}

			if( empty($email) )
	        {
	            $errors['email'] = "Preencha o e-mail";
	        }
	        else if( strlen($email) > 50)
	        {
	        	 $errors['email'] = "E-mail deve possuir no máximo 50 caracteres";
	        }
	        else if(!filter_var($email, FILTER_VALIDATE_EMAIL))
	        {
	            $errors['email'] = "Digite um e-mail válido";
	        }

	        if( empty($senha) )
	        {
	            $errors['senha'] = "Preencha a senha";
	        }
	        else if( strlen($senha) > 40)
	        {
	        	 $errors['senha'] = "Senha deve possuir no máximo 40 caracteres";
	        }

	        if( empty($confirmarSenha) )
	        {
	            $errors['confirmarSenha'] = "Preencha a senha novamente";
	        }
	        else if($senha != $confirmarSenha)
	        {
	            $errors['confirmarSenha'] = "As senhas não coincidem";
	        }

	        return $errors;
		}

		public static function errosLogin($login_email, $senha)
		{
			$errors = [];

			$login_email = trim($login_email);
			$senha = trim($senha);

			if(empty($login_email))
	        {
	            $errors['login_email'] = "Preencha o Login/E-mail";
	        }
	        else if(strlen($login_email) > 50)
	        {
	        	 $errors['login_email'] = "Login/Email deve possuir no máximo 50 caracteres";
	        }

	        if(empty($senha))
	        {
	            $errors['senha'] = "Preencha a senha";
	        }
	        else if(strlen($senha) > 40)
	        {
	        	 $errors['senha'] = "Senha deve possuir no máximo 40 caracteres";
	        }

	        return $errors;
		}

	}

 ?>