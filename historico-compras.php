

<!DOCTYPE html>
<html lang="pt">
<head>

	<?php require_once('inc/header.php'); ?>

</head>

<body>

<?php require_once('inc/navbar.php'); ?>

<br>

<h3 class="centro">Histórico de Compras</h3>
<hr>

<br>

<?php

$conexao = mysqli_connect("localhost", "root", "", "dom");

// obter os carrinhos finalizados
$sql_historico = "select * from carrinho where carrinho.Estado=1 and carrinho.Utilizadores_ID=
			(select Utilizadores_ID from utilizadores where Email = '" . $_SESSION["Email"] . "')
			order by Data desc";

$resultado_historico = mysqli_query($conexao, $sql_historico);
//exit();

if (! $resultado_historico ) {
	$_SESSION["erro"] = "Não foi possível obter o histórico de compras.1";
	Header("Location: index.php");
	exit();
}

if (mysqli_num_rows($resultado_historico) == 0) {
	echo "<h2 style='text-align: center' class='display-2 centro castanho-escuro-letra'> Sem compras efetuadas. </h3>";
	require_once('inc/footer.php');
	exit();
}

// listar todos os carrinhos finalizados
while ( $registo_historico = mysqli_fetch_array( $resultado_historico) ) {
	// obter os itens de cada carrinho
	$sql = "select * from itenscarrinho, carrinho, produtos where 
				carrinho.ID = itenscarrinho.Carrinho_ID and produtos.ID = itenscarrinho.Produto_ID
				and carrinho.ID=" . $registo_historico["ID"];
		
	$resultado = mysqli_query($conexao, $sql);
	if (! $resultado ) {
		$_SESSION["erro"] = "Não foi possível obter o histórico de compras.2";
		Header("Location: index.php");
		exit();
	}

?>

	<div class="carrinho">

	<?php
	while ( $registo = mysqli_fetch_array( $resultado ) ) {
	?>
		<div class="item_carrinho">
			<div>
				<?php echo $registo["Designacao"]; ?>
			</div>
			<div>
				Quantidade:
				<?php echo $registo["Quantidade"]; ?>				
			</div>
			<div>
				<?php echo $registo["PrecoTotalProdutos"] . " €"; ?>
			</div>
		</div>
	<?php
	}
	?>
		<div class="carrinho_footer">
			<div>
				Total: <?php echo $registo_historico["PrecoTotal"] . " €";?>
			</div>
			<div>
				Data: <?php echo $registo_historico["Data"];?>
			</div>
		</div>
	</div>

	<br>
	<br>
	<br>
<?php
}  // while exterior
?>

<div id="mensagem" style="color:blue"></div>
<div id="erro" style="color:red"></div>

<?php

// apresentar eventuais mensagens informativas
if ( isset($_SESSION["mensagem"]) ) {
	echo "<script>
				document.getElementById('mensagem').innerHTML	= '" . $_SESSION["mensagem"] . "';
		 </script>";
	unset($_SESSION["mensagem"]);
}

// verificar se houve erro e dar a respetiva mensagem
if ( isset($_SESSION["erro"]) ) {
	echo "<script>
				document.getElementById('erro').innerHTML	= '" . $_SESSION["erro"] . "';
		 </script>";
	unset($_SESSION["erro"]);
}
?>

	<?php require_once('inc/footer.php'); ?>

</body>

</html>