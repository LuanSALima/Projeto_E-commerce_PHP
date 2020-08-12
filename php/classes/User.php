<?php 

	class Usuario
	{
		private $id;
		private $login;
		private $email;
		private $senha;

		public function __construct($id, $login, $email, $senha)
		{
			$this->id = $id;
			$this->login = $login;
			$this->email = $email;
			$this->senha = $senha;
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