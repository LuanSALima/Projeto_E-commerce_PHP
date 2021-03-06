
<?php 
	
	session_start();

	if($_SERVER['QUERY_STRING'] == 'logout')
    {
        //Remove os Cookies de lembrar conta
        setcookie("lembrar_id", '', time() - (86400 * 365), "/");
        setcookie("lembrar_login", '', time() - (86400 * 365), "/");

        //Remove a 'sessão usuario'
        unset($_SESSION['usuario']);
    }
    else
    {
    	if(isset($_COOKIE['lembrar_id']) && isset($_COOKIE['lembrar_login']))
		{
			$idDesencriptado = openssl_decrypt($_COOKIE['lembrar_id'], "AES-128-CTR", "LembrarConta", 0, "7070707070707070");

			$loginDesencriptado = openssl_decrypt($_COOKIE['lembrar_login'], "AES-128-CTR", "LembrarConta", 0, "7070707070707070");

			$_SESSION['usuario'] = array('id' => $idDesencriptado, 'login' => $loginDesencriptado);
		}
    }

	$logado = $_SESSION['usuario'] ?? '';

	session_write_close();

 ?>

	<meta charset="utf-8">
 	<meta name="viewport" content="width=device-width, initial-scale=1">

	<!-- Latest compiled and minified CSS -->
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">

	<style type="text/css">
		
		.col-md-3
		{
			margin-top: 20px;
			margin-bottom: 20px;
		}

	</style>

 </head>

 <body>
 
 	<nav class="navbar navbar-default">
	  <div class="container-fluid">
	    <div class="navbar-header">
	      <a class="navbar-brand" href="index.php">
	      	<?php echo $nomeSite; ?>
	      </a>
	      <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#menu" aria-expanded="false">
	        <span class="sr-only">Toggle navigation</span>
	        <span class="icon-bar"></span>
	        <span class="icon-bar"></span>
	        <span class="icon-bar"></span>
	      </button>
	    </div>
	    	
	    <div class="collapse navbar-collapse" id="menu">
		    <ul class="nav navbar-nav">
		      <li><a href="index.php">Home</a></li>
		      <li><a href="produto-listar.php">Lista Produtos</a></li>
		      	<?php if($logado): ?>
					<li>
						<a href="produto-cadastrar.php"><span>Cadastrar Produto</span></a>
					</li>
		      	<?php endif; ?>
		    </ul>
		    <ul class="nav navbar-nav navbar-right">
		    	<?php if(!$logado): ?>
					<li>
						<a href="usuario-registrar.php"><span class="glyphicon glyphicon-user"></span> Sign Up</a>
					</li>
					<li>
						<a href="usuario-login.php"><span class="glyphicon glyphicon-log-in"></span> Login</a>
					</li>
		      	<?php else: ?>
		      		<li>
						<a href="meus-produtos.php"><span>Meus Produtos</span></a>
					</li>
					<li>
						<li class="dropdown">
				           <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><?php echo $logado['login'];	 ?>  <img style="width: 25px;height: 25px;border-radius: 100%;" src="perfilImagem.php?idUsuario=<?php echo $logado['id']; ?>"><span class="caret"></span></a>
				           <ul class="dropdown-menu">
				            	<li><a href="usuario-editar.php">Editar Perfil</a></li>
				            	<li><a href="usuario-alterarSenha.php">Alterar Senha</a></li>
				           </ul>
					</li>
					<li>
						<a href="<?php echo 'index.php?logout'; ?>"><span class="glyphicon glyphicon-log-out"></span> Logout</a>
					</li>
		      	<?php endif; ?>
		    </ul>
		</div>
	  </div>
	</nav>