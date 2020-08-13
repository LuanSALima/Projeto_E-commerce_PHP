<?php 

	$nomeSite = "Projeto";

	session_start();

	$usuario = $_SESSION['usuario'] ?? '';

    session_write_close();

	$idProduto = $_GET['idProduto'] ?? '';

	require('../bcd/bcd_connect.php');

    $bcdErro = '';
    $erroBCD = '';

    if($conexao)
    {
    	if(isset($_POST['avaliar']))
    	{
    		$erros = [];
    		$idProduto = trim(htmlspecialchars($_POST['idProduto']));

    		if($usuario)
    		{
    			
	    		$comentarioAvaliacao = trim(htmlspecialchars($_POST['comentarioAvaliacao']));
	    		if(isset($_POST['notaAvaliacao']))
	    		{
	    			$notaAvaliacao = trim(htmlspecialchars($_POST['notaAvaliacao']));
	    		}
	    		else
	    		{
	    			$notaAvaliacao = null;
	    		}
		        
		        if(empty($notaAvaliacao))
		        {
		        	$erros['nota'] = "Dê uma nota ao produto";
		        }

		        if(empty($comentarioAvaliacao))
		        {
		        	$erros['comentario'] = "Escreva um comentário sobre o produto";
		        }
		        else
		        {
		        	if(strlen($comentarioAvaliacao) > 150)
		        	{
		        		$erros['comentario'] = "Comentário deve possuir no máximo 150 caracteres";
		        	}
		        }

		        if(!array_filter($erros))
		        {
		        	$idProduto = trim(mysqli_real_escape_string($conexao, $_POST['idProduto']));
		        	$notaAvaliacao = trim(mysqli_real_escape_string($conexao, $_POST['notaAvaliacao']));
		        	$comentarioAvaliacao = trim(mysqli_real_escape_string($conexao, $_POST['comentarioAvaliacao']));
		        	$idUsuario = $usuario['id'];

		        	$comandoSQL = "INSERT INTO avaliacaoproduto (id_autor, id_produto, nota, texto) VALUES ('$idUsuario', '$idProduto', '$notaAvaliacao', '$comentarioAvaliacao');";

		        	if(!mysqli_query($conexao, $comandoSQL))
	                {
	                    echo "Erro de comando: " . mysqli_error($conexao);
	                }
		        }
    		}
    		else
    		{
    			$erros['login'] = "É necessário estar logado para avaliar um produto";
    		}
    		

    	}

    	if(isset($_POST['editarAvaliacao']))
    	{
    		$erros = [];

    		if($usuario)
    		{
		    	$idUsuario = $usuario['id'];
		    	$idProduto = htmlspecialchars($_POST['idProduto']);

	    		$comentarioAvaliacao = trim(htmlspecialchars($_POST['comentarioAvaliacao']));

		        if(empty($comentarioAvaliacao))
		        {
		        	$erros['comentario'] = "Escreva um comentário sobre o produto";
		        }
		        else
		        {
		        	if(strlen($comentarioAvaliacao) > 150)
		        	{
		        		$erros['comentario'] = "Comentário deve possuir no máximo 150 caracteres";
		        	}
		        }

		        if(!array_filter($erros))
		        {
		    		$idProduto = mysqli_real_escape_string($conexao, $_POST['idProduto']);
		    		$notaAvaliacao = mysqli_real_escape_string($conexao, $_POST['notaAvaliacao']);
		    		$comentarioAvaliacao = mysqli_real_escape_string($conexao, $_POST['comentarioAvaliacao']);

		    		
	    			$comandoSQL = "UPDATE avaliacaoproduto SET nota = '$notaAvaliacao', texto = '$comentarioAvaliacao' WHERE id_produto = '$idProduto' AND id_autor = '$idUsuario';";

	    			if(!mysqli_query($conexao, $comandoSQL))
					{
						$erroBCD = "Não Foi possível editar o Produto, tente novamente.";
					}
		    	}

	    	}
    		else
    		{
    			$erroBCD = 'É necessário estar logado para editar seu comentário';
    		}
    	}

    	if(isset($_POST['removerAvaliacao']))
    	{
    		if($usuario)
    		{
    			$idProduto = mysqli_real_escape_string($conexao,$_POST['idProduto']);
    			$idUsuario = $usuario['id'];

    			$comandoSQL = "DELETE FROM avaliacaoproduto WHERE id_produto = '$idProduto' AND id_autor = '$idUsuario'";

    			if(!mysqli_query($conexao, $comandoSQL))
				{
					//não deletou
					$erroBCD = "Não Foi possível deletar o Produto, tente novamente.";
				}

    		}
    		else
    		{
    			$erroBCD = 'É necessário estar logado para remover seu comentário';
    		}
    	}


		if($idProduto)
		{				
	    	try
	    	{
		    	$idProduto = mysqli_real_escape_string($conexao, $idProduto);

		    	$comandoSQL = "SELECT produto.id, id_usuario, usuarios.login, nome, preco, imagem FROM produto INNER JOIN usuarios ON produto.id_usuario = usuarios.id WHERE produto.id = $idProduto";

				$resultado = mysqli_query($conexao, $comandoSQL);

				$produto = mysqli_fetch_all($resultado, MYSQLI_ASSOC)[0];

				$produto['preco'] = str_replace('.', ',', $produto['preco']);	

				mysqli_free_result($resultado);

				if($usuario)
				{
					$idUsuario = $usuario['id'];

					$comandoAvaliacaoLogadoSQL = "SELECT id_autor, usuarios.login, nota, texto, data FROM avaliacaoproduto INNER JOIN usuarios ON avaliacaoproduto.id_autor = usuarios.id  WHERE id_produto = $idProduto AND id_autor = '$idUsuario'";

					$resultadoAvaliacaoLogado = mysqli_query($conexao, $comandoAvaliacaoLogadoSQL);

					if(($resultadoAvaliacaoLogado -> num_rows)>0)
					{
						$avaliacaoLogado = mysqli_fetch_all($resultadoAvaliacaoLogado, MYSQLI_ASSOC)[0];
					}

					mysqli_free_result($resultadoAvaliacaoLogado);

				}

				$comandoAvaliacaoSQL = "SELECT id_autor, usuarios.login, nota, texto, data FROM avaliacaoproduto INNER JOIN usuarios ON avaliacaoproduto.id_autor = usuarios.id  WHERE id_produto = $idProduto ORDER BY data DESC";

				$resultadoAvaliacao = mysqli_query($conexao, $comandoAvaliacaoSQL);

				$avaliacoes = mysqli_fetch_all($resultadoAvaliacao, MYSQLI_ASSOC);

				mysqli_free_result($resultadoAvaliacao);

				mysqli_close($conexao);

			}
			catch(Exception $e)
			{
				$erroBCD = "Não foi possível localizar o produto ";
			}
		}
		else
		{
			$erroBCD = "Não foi possível localizar o produto";
		}
	}
	else
    {
    	$bcdErro = "Houve um problema no banco de dados";
    }

 ?>

 <!DOCTYPE html>
 <html lang="pt-br">

 <head>

 	<title><?php echo $nomeSite; ?> - Home</title>

 	<style>

 		.error
 		{
 			color: red;
 			font-size: 16px;
 		}
 		
 		.notaAvaliacao {
		    float: left;
		    height: 46px;
		    padding: 0 10px;
		}
		.notaAvaliacao:not(:checked) > input {
		    display: none;
		}
		.notaAvaliacao:not(:checked) > label {
		    float:right;
		    width:1em;
		    overflow:hidden;
		    white-space:nowrap;
		    cursor:pointer;
		    font-size:30px;
		    color:#ccc;
		}
		.notaAvaliacao:not(:checked) > label:before {
		    content: '★ ';
		}
		.notaAvaliacao > input:checked ~ label {
		    color: #ffc700;    
		}
		.notaAvaliacao:not(:checked) > label:hover,
		.notaAvaliacao:not(:checked) > label:hover ~ label {
		    color: #deb217;  
		}
		.notaAvaliacao > input:checked + label:hover,
		.notaAvaliacao > input:checked + label:hover ~ label,
		.notaAvaliacao > input:checked ~ label:hover,
		.notaAvaliacao > input:checked ~ label:hover ~ label,
		.notaAvaliacao > label:hover ~ input:checked ~ label {
		    color: #c59b08;
		}

		.notaComentario label:before
		{
			content: '★ ';
			color: #ffc700;
			font-size: 20px;
		}

		.avaliacaoUsuario
		{
			padding: 10px 20px;
			background-color: #d4d0d0;
		}

 	</style>

 	<?php require('cabecalho.php') ?>

	<div class="container">
	  <h1>Detalhes do Produto</h1>

		<div style="width: 100%; text-align: center;">
		 	<h2 style="color: red;">
		 		<?php
		 			echo $bcdErro ?? ''; 
		 			echo $erroBCD ?? '';
		 		?>
		 	</h2>
	 	</div>

	 	<?php if(!$erroBCD && !$bcdErro): ?>

	  		<h4>Nome:</h4>
	  		<p><?php echo $produto['nome']; ?></p>

	  		<h4>Preço:</h4>
	  		<p>R$ <?php echo $produto['preco']; ?></p>

	  		<h4>Vendedor:</h4>
	  		<p><?php echo $produto['login'] ?></p>

	  		<h4>Imagem:</h4>
	  		<img id="imagemPreview" style="width: 400px;height: 400px;" src="produtoImagem.php?IdProduto=<?php echo $produto['id']; ?>"> 	

	  		<br>
	  		<hr>
	  		<br>

	  		<?php if(!isset($avaliacaoLogado)): ?>
	  		<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST" id="formAvaliarProduto">
	  			<div style="width: 100%; text-align: center;">
				 	<h2 style="color: red;">
				 		<?php
				 			echo $erros['login'] ?? ''; 
				 		?>
				 	</h2>
			 	</div>
	  			<input type="hidden" name="idProduto" value="<?php echo $produto['id']; ?>">
	  			<div class="form-group">
	  				<div class="notaAvaliacao">
					    <input type="radio" id="star5" name="notaAvaliacao" value="5">
					    <label for="star5" title="text">5 stars</label>
					    <input type="radio" id="star4" name="notaAvaliacao" value="4">
					    <label for="star4" title="text">4 stars</label>
					    <input type="radio" id="star3" name="notaAvaliacao" value="3">
					    <label for="star3" title="text">3 stars</label>
					    <input type="radio" id="star2" name="notaAvaliacao" value="2">
					    <label for="star2" title="text">2 stars</label>
					    <input type="radio" id="star1" name="notaAvaliacao" value="1">
					    <label for="star1" title="text">1 star</label>
				  	</div>
			  	</div>

			  	<span class="error"><?php echo $erros['nota'] ?? ''; ?></span>

			  	<div class="form-group">
			  		<textarea class="form-control" placeholder="Deixe um comentário" name="comentarioAvaliacao" id="addComment" rows="5"><?php echo $comentarioAvaliacao ?? '' ?></textarea>
			  	</div>
			  	<span class="error"><?php echo $erros['comentario'] ?? ''; ?></span>

			  	<div class="form-group">            
                        <input class="btn btn-success btn-circle text-uppercase" type="submit" id="submitComment" name="avaliar" value="Enviar Avaliação"></input>
                </div>   
	  		</form>
	  		<?php else: ?>
	  			<div>
		  			<h2 style="text-align: center;">Sua Avaliação</h2>

		  			<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST" id="formEditarAvaliacao">
			  			<input type="hidden" name="idProduto" value="<?php echo $produto['id']; ?>">
			  			<div class="form-group">
			  				<div class="notaAvaliacao">
			  					<span></span>
							    <input <?php if($avaliacaoLogado['nota']==5){echo 'checked';} ?> type="radio" id="star5" name="notaAvaliacao" value="5">
							    <label for="star5" title="text">5 stars</label>
							    <input <?php if($avaliacaoLogado['nota']==4){echo 'checked';} ?> type="radio" id="star4" name="notaAvaliacao" value="4">
							    <label for="star4" title="text">4 stars</label>
							    <input <?php if($avaliacaoLogado['nota']==3){echo 'checked';} ?> type="radio" id="star3" name="notaAvaliacao" value="3">
							    <label for="star3" title="text">3 stars</label>
							    <input <?php if($avaliacaoLogado['nota']==2){echo 'checked';} ?> type="radio" id="star2" name="notaAvaliacao" value="2">
							    <label for="star2" title="text">2 stars</label>
							    <input <?php if($avaliacaoLogado['nota']==1){echo 'checked';} ?> type="radio" id="star1" name="notaAvaliacao" value="1">
							    <label for="star1" title="text">1 star</label>
						  	</div>
					  	</div>

					  	<div class="form-group">
					  		<textarea class="form-control" placeholder="Deixe um comentário" name="comentarioAvaliacao" id="addComment" rows="5"><?php echo $avaliacaoLogado['texto'] ?? $comentarioAvaliacao ?? ''; ?></textarea>
					  	</div>

					  	<div class="form-group">            
		                        <input style="float: left"; class="btn btn-success btn-circle text-uppercase" type="submit" id="submitComment" name="editarAvaliacao" value="Editar">
		                </div>   
			  		</form>
			  		<form style="margin-left:5px;float: left;" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
	                  	<input type="hidden" name="idProduto" value="<?php echo $produto['id']; ?>">		
	                  	<input class="btn btn-danger btn-circle text-uppercase" type="submit" name="removerAvaliacao" value="Remover">		                  	 
	               	</form>
	               	<span class="error"><?php echo $erros['comentario'] ?? ''; ?></span>
            	</div>
	  		<?php endif; ?>

	  		
	  		<br><br><br>
	  		<ul class="media-list">
	  		<?php foreach ($avaliacoes as $avaliacao): ?>
	  			<?php if($usuario): ?>
	  				<?php if($avaliacao['id_autor'] == $usuario['id']): ?>
	  				<div class="avaliacaoUsuario">
	  				<?php else: ?>
	  				<div>
	  				<?php endif; ?>
	  			<?php else: ?>
	  				<div>
	  			<?php endif; ?>
				
              	<li class="media">
              		<!--Colocar link do perfil-->
	                <a class="pull-left" href="#">
	                  <img style="width: 128px; height: 128px;" class="media-object img-circle" src="perfilImagem.php?idUsuario=<?php echo $avaliacao['id_autor']; ?>"" alt="profile">
	                </a>
	                <div class="media-body">
	                  <div class="well well-lg">
	                      <h4 class="media-heading text-uppercase reviews">
	                      	<?php echo $avaliacao['login']; ?> 
	                      </h4>
	                      <span>
	                      	<?php echo $avaliacao['data']; ?>
	                      </span>
	                      <br>
	                      <div class="notaComentario">
	                      	<?php for($i = 0; $i< $avaliacao['nota']; $i++): ?>
	                      		<label></label>
	                      	<?php endfor; ?>
	                      </div>
	                      <br>
	                      <p class="media-comment">
	                        <?php echo $avaliacao['texto']; ?>
	                      </p>
	                  </div>              
	                </div>
            	</li>
            	</div>
            <?php endforeach; ?>
            </ul>

	  	<?php else: ?>
	 		<a href="lista-produtos.php">Voltar</a>
	 	<?php endif; ?>
	</div>

	<!-- jQuery library -->
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

	<!-- Latest compiled JavaScript -->
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>  

 </body>

 </html>