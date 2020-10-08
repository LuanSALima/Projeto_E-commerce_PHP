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


			require('../../usuario-email.php');

			$mail->Username = $usuarioEmail;
			$mail->Password = $senhaEmail;
			$mail->From = $usuarioEmail;
			$mail->FromName = "Equipe E-commerce_PHP";
			$mail->addAddress($usuarioEmail);

			return $mail;
		}

		public function sendEmailConfirm($userName, $token)
		{
			$email = $this->configuration;

			$email->Subject = "Confirmação do endereço de email";
			$email->Body = "
			 		<h1>Bem vindo <span style=\"color: green;\">".$userName."</span> ao Projeto E-commerce PHP</h1>
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