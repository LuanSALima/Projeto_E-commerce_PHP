
<?php 
	
	session_start();

	if($_SERVER['QUERY_STRING'] == 'logout')
    {
        //Remove a 'sessão usuario'
        unset($_SESSION['usuario']);

        //Remove todas as sessões
        //session_unset(); 
    }

	$logado = $_SESSION['usuario'] ?? '';

	session_write_close();

 ?>

	<meta charset="utf-8">
 	<meta name="viewport" content="width=device-width, initial-scale=1">

	<!-- Latest compiled and minified CSS -->
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">

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
		      <li><a href="lista-produtos.php">Lista Produtos</a></li>
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