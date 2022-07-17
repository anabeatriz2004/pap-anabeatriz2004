<?php
session_start();

// verificar se existe uma sessão iniciada
// e se o utilizador clicou no botão de logout
//if ( isset($_SESSION["utilizador"]) && isset($_POST["botaoLogout"]) ) {
	
	// terminar a sessão
	session_unset();
	session_destroy();
//}

Header("Location: index.php");
exit();

?>