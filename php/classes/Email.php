<?php 

	class Email
	{
		private $configuration;

		public function __construct()
		{
			$this->configuration = $this->configure();
		}

		private function configure()
		{
			require('PHPMailer/PHPMailer.php');
			require('PHPMailer/SMTP.php');
			require('PHPMailer/Exception.php');
			
			$mail = new PHPMailer\PHPMailer\PHPMailer();

			$mail->isHTML(true);
			$mail->isSMTP();
			$mail->Port = "465";
			$mail->Host = "smtp.gmail.com";
			$mail->SMTPSecure = "ssl";
			$mail->Mailer = "smtp";
			$mail->CharSet = "UTF-8";
			$mail->SMTPAuth = true;
			$mail->SingleTo = true;


			require($_SERVER['DOCUMENT_ROOT'].'/usuario-email.php');

			$mail->Username = $usuarioEmail;
			$mail->Password = $senhaEmail;
			$mail->From = $usuarioEmail;
			$mail->FromName = "Equipe E-commerce_PHP";


			$mail->addAddress($usuarioEmail);

			return $mail;
		}

		public function sendEmailConfirm($userEmail, $userName, $token)
		{
			$email = $this->configuration;

			//$email->addAddress($email); Utilizarei meu próprio e-mail para testes
			$email->Subject = "Confirmação do endereço de email";
			$email->Body = "
			 		<h1>Bem vindo(a) <span style=\"color: green;\">".$userName."</span> ao Projeto E-commerce PHP</h1>
					<hr>
					<h3>Para continuar a acessar o nosso web site é necessário confirmar seu e-mail</h3>
					<br>
					<h3>
						Clique neste link para confirmar seu email:  
						<a href=\"".
						$_SERVER['SERVER_NAME']."/Projeto_E-commerce_PHP/confirmar-email.php?token=".$token
						."\">".
						$_SERVER['SERVER_NAME']."/Projeto_E-commerce_PHP/confirmar-email.php?token=".$token
						."
						</a>
					</h3>
					<br><br>
					<h4>Enviado para: ".$userEmail."</h4>
			";

			if($email->send())
			{
				return 1;
			}
			else
			{
				return $email->ErrorInfo;
			}
		}

		public function sendPasswordRecover($userEmail, $token)
		{
			$email = $this->configuration;

			//$email->addAddress($email); Utilizarei meu próprio e-mail para testes
			$email->Subject = "Recuperar Senha";
			$email->Body = "
			 		<h1>Bem vindo(a) ao Projeto E-commerce PHP</h1>
					<hr>
					<h3>Caso não tenha solicitado a recuperação de senha, tome cuidade! Pois tentaram recuperar a senha da conta com este e-mail cadastrado no nosso site.</h3>
					<br>
					<h3>
						Clique neste link para recuperar sua senha:  
						<a href=\"".
						$_SERVER['SERVER_NAME']."/Projeto_E-commerce_PHP/recuperar-senha.php?token=".$token
						."\">".
						$_SERVER['SERVER_NAME']."/Projeto_E-commerce_PHP/recuperar-senha.php?token=".$token
						."
						</a>
					</h3>
					<br><br>
					<h4>Enviado para: ".$userEmail."</h4>
			";

			if($email->send())
			{
				return 1;
			}
			else
			{
				return $email->ErrorInfo;
			}
		}
	}

 ?>