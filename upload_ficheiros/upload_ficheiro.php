<?php

session_start();

// verificar se o utilizador premiu o botão de upload de um ficheiro
if (isset($_POST["confirma_upload_ficheiro"])) {

	if ($_FILES["ficheiro"]["size"] > $_SESSION["tamanho_maximo"])
	{
		// o tamanho do ficheiro é maior do que o máximo permitido
		$_SESSION["sucesso_upload"] = "erro_tamanho";
		header("Location: index.php");
		exit();
	}

	// verificar se o upload foi feito com sucesso por meio de um HTTP POST
	if ( is_uploaded_file($_FILES["ficheiro"]["tmp_name"]) ) {

		// diretoria "./img", para onde se vai fazer o upload
		$diretoria_ficheiro = "./img/";
		// nome do ficheiro
		$nome_ficheiro = basename($_FILES["ficheiro"]["name"]);
		// caminho completo do ficheiro no servidor
		$caminho_ficheiro = $diretoria_ficheiro . $nome_ficheiro;

		// mover o ficheiro da directoria temporária para onde foi feito o upload, para a directoria desejada;
		// se retornar false, é porque não foi feito um upload com sucesso por meio de HTTP POST,
		// ou ele foi feito mas não foi possível mover o ficheiro para o destino
		if ( move_uploaded_file($_FILES["ficheiro"]["tmp_name"] , $caminho_ficheiro) )
		{
			$_SESSION["sucesso_upload"] = "sucesso";
			$_SESSION["fich_nome"] = $nome_ficheiro;
			header("Location: index.php");
			exit();
		}
		else
		{	
			$_SESSION["sucesso_upload"] = "erro";
			header("Location: index.php");
			exit();
		}
	}
	else {
			$_SESSION["sucesso_upload"] = "erro";
			header("Location: index.php");
			exit();
	}
}
else
{   // o utilizador não premiu o botão de upload de um ficheiro
	header("Location: index.php");
	exit();
}

?>