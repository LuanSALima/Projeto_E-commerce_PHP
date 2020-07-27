<?php 

	$nomeSite = "Projeto";

 ?>

 <!DOCTYPE html>
 <html lang="pt-br">

 <head>

 	<title><?php echo $nomeSite; ?> - Home</title>

 	<?php require('cabecalho.php') ?>

	<div class="container">
	  <div class="jumbotron">
	    <h1><?php echo $nomeSite; ?></h1>      
	    <p>Um projeto e-commerce para fins educativos.</p>
	  </div>   
	</div>

	<!-- jQuery library -->
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

	<!-- Latest compiled JavaScript -->
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>  

 </body>

 </html>