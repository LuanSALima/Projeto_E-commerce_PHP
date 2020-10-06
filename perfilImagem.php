<?php
	require('php/classes/BancoDados.php');
	$conexao = (new Conexao())->conectar();

	$idUsuario = $_GET["idUsuario"];

    if($conexao)
    {
    	$comandoSQL = "SELECT foto FROM usuarios WHERE id = '$idUsuario'";

		$resultado = mysqli_query($conexao, $comandoSQL);

		if($resultado)
		{
			$usuario = mysqli_fetch_assoc($resultado);
			mysqli_free_result($resultado);

			mysqli_close($conexao);

			if($usuario['foto'])
			{
				Header( "Content-type: image/gif");
				echo $usuario['foto'];
			}
			else
			{
				Header( "Content-type: image/gif");
				echo readfile("img/iconePerfil.png");
			}
			
		}
		else
		{
			Header( "Content-type: image/gif");
			echo readfile("img/iconePerfil.png");
		}

		
		
    }
    else
    {
    	$bcdErro = "Houve um problema no banco de dados";
    }
	
?>