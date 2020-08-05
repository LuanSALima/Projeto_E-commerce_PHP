<?php
	require('../bcd/bcd_connect.php');

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
    	$bcdErro = "Houve um problema no banco de dados";
    }
	
?>