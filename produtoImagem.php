<?php
	require('php/classes/BancoDados.php');
	$conexao = (new Conexao())->conectar();

	$IdProduto = $_GET["IdProduto"];

    if($conexao)
    {
    	$comandoSQL = "SELECT imagem FROM produto WHERE id = '$IdProduto'";

		$resultado = mysqli_query($conexao, $comandoSQL);

		$produtoImagem = mysqli_fetch_assoc($resultado);

		mysqli_free_result($resultado);

		mysqli_close($conexao);

		Header( "Content-type: image/gif");
		echo $produtoImagem['imagem'];
		
    }
    else
    {
    	$bcdErro = "Houve um problema no banco de dados";
    }
	
?>