<?php

session_start();

// tamanho máximo permitido, em bytes, para o ficheiro do qual se vai fazer o upload
$_SESSION["tamanho_maximo"] = 1024000;

// $_SESSION["sucesso_upload"] fica com valor:
// "sucesso" se foi feito o upload de um ficheiro com sucesso;
// "erro" se o upload falhou;
// unset se não foi feita uma tentativa de upload.
if (isset($_SESSION["sucesso_upload"]))
{	// houve uma tentativa de upload de um ficheiro
	if ($_SESSION["sucesso_upload"] == "sucesso")
	{
		echo "<script> alert('Ficheiro enviado com sucesso.') </script>";
	}
	else
	{
		echo "<script> alert('Erro no envio do ficheiro.') </script>";
	}
	
	unset($_SESSION["sucesso_upload"]);
}
?>

<script>

function valida_ficheiro()
{
    var fich_up = document.getElementById('ficheiro').files[0];  // ficheiro escolhido para upload
	
	if (!fich_up)
	{
		document.getElementById("confirma_upload_ficheiro").disabled = true;
	}
	else if (fich_up.size > "<?php echo $_SESSION['tamanho_maximo']; ?>")
	{
		document.getElementById("confirma_upload_ficheiro").disabled = true;
		alert('O tamanho do ficheiro excede o máximo permitido.');
	}
	else if (fich_up.type.substring(0,6) !="image/")
	{
		alert('O ficheiro escolhido tem de ser uma imagem.');
	}
	else
	{
		document.getElementById("confirma_upload_ficheiro").disabled = false;
		document.getElementById('imagem').src = window.URL.createObjectURL(fich_up)
	}
}
</script>


<!--
	Form para upload de um ficheiro.
	MAX_FILE_SIZE (aqui com o valor de 20Mbytes=20971520 bytes) deve preceder o campo de file input.
	O nome do elemento de input do tipo "file" determina o nome no array $_FILES.
-->
<form name="form_upload_ficheiro" enctype="multipart/form-data" action="upload_ficheiro.php" method="post">
    <input type="hidden" name="MAX_FILE_SIZE" value="<?php echo $_SESSION['tamanho_maximo']; ?>">
	<input type="file" name="ficheiro" id="ficheiro" accept="image/*" onchange="valida_ficheiro()"> <br><br>
	<button type="submit" name="confirma_upload_ficheiro" id="confirma_upload_ficheiro" disabled> Enviar ficheiro </button>
</form>
<img id="imagem" style="width:160px;height:160px">


			
<?php
	//////////////
	// Pôr aqui o código para inserir o produto na base de dados:
	// insert into produtos ... (..., '" . $_SESSION["fich_nome"] . "')";
	//
	// ou para alterar a imagem do produto já previamente inserido na base de dados:
	// update produtos set ...=..., Img='" . $_SESSION["fich_nome"] . "' where ID=...
	/////////////
?>