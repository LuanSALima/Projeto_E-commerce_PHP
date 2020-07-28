
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
	      <a class="navbar-brand" href="#">
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
		      <li><a href="#">Home</a></li>
		      <li><a href="#">Page 1</a></li>
		      <li><a href="#">Page 2</a></li>
		      <li><a href="#">Page 3</a></li>
		    </ul>
		    <ul class="nav navbar-nav navbar-right">
		    	<?php if(!$logado): ?>
					<li>
						<a href="registrar-usuario.php"><span class="glyphicon glyphicon-user"></span> Sign Up</a>
					</li>
					<li>
						<a href="login-usuario.php"><span class="glyphicon glyphicon-log-in"></span> Login</a>
					</li>
		      	<?php else: ?>
					<li>
						<a>Logado como: <?php echo $logado;	 ?></a>
					</li>
					<li>
						<a href="<?php echo 'index.php?logout'; ?>"><span class="glyphicon glyphicon-log-out"></span> Logout</a>
					</li>
		      	<?php endif; ?>
		    </ul>
		</div>
	  </div>
	</nav>