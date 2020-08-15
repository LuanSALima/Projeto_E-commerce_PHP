<?php 
	
	//https://stackoverflow.com/questions/15036831/php-get-warning-and-error-messages
	function exception_error_handler($errno, $errstr, $errfile, $errline)
	{
	    if (error_reporting()) { // skip errors that were muffled
	        throw new ErrorException($errstr, $errno, 0, $errfile, $errline);
	    }
	}

	set_error_handler("exception_error_handler");

	class Conexao
	{
		//Atributos
		private $server = '127.0.0.1';
		private $usuario = 'devPHP';
		private $senha = 'dev$php';
		private $banco = 'ecommerce';

		//Métodos
		public function conectar()
		{
			mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

			try
			{
				$conexao = mysqli_connect($this->server, $this->usuario, $this->senha, $this->banco);
			}
			catch(Exception $e)
			{
				$conexao = null;
			}
			
			return $conexao;
		}

	}

 ?>