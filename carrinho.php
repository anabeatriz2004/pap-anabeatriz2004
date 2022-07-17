<?php
	//var_dump($_SESSION); exit();

	$Produtos = NULL;
	//$ItensCarrinho = NULL;
?>

<!DOCTYPE html>
<html lang="pt">
<head>
	<?php require_once('inc/header.php'); ?>

	<style>
	* {
		box-sizing: border-box;
	}
	</style>

	<script>

	function alterarQuantidadeItem(itemId) {
		var produto_id = itemId.substr(4);  // obter a substring com o id do produto (por ex., se itemId for "item123", produto_id fica igual a "123"
		var item_quantidade = document.getElementById(itemId).value;
		window.location.assign("gerir-carrinho.php?item_quantidade=" + item_quantidade + "&Produto_ID=" + produto_id);
	}

	</script>

</head>

<body>
	<?php 
		require_once('inc/navbar.php');
	?>

	</div>
	<?php
		require_once "inc/database.php";
	?>

	<?php

	$conexao = mysqli_connect("localhost", "root", "", "dom");

	if ( ! $conexao ) {
		// para debug
//echo mysqli_connect_error() . "</br>" . mysqli_connect_errno();
		
		$_SESSION["erro"] = "Não foi possível ligar à base de dados.";
		echo '<script>
			window.location.assign("index.php");
		</script>';		
		exit();
	}

	mysqli_set_charset( $conexao, "utf8");

	// obter o carrinho ativo
	$sql = "select * from carrinho where carrinho.Estado=0 and carrinho.Utilizadores_ID=
				(select Utilizadores_ID from utilizadores where Email = '" . $_SESSION["Email"] . "')";
			
	$resultado = mysqli_query($conexao, $sql);
//echo $sql; echo mysqli_connect_error(); exit();
	if (! $resultado ) {
		$_SESSION["erro"] = "Não foi possível obter os dados do carrinho";
		echo '<script>
			window.location.assign("index.php");
		</script>';	
		exit();
	}

	if (mysqli_num_rows($resultado) == 0) {
	?>
		<div class="margens">
			<h4 class="centro">Carrinho</h4>
			<hr>
			<p class="centro"><small>Para ver o histórico de carrinho. Clique 
			<a href="historico-compras.php" class="azul-link">aqui.</a></small></p>

			<br>
			
			<h2 class="display-2 centro"> Carrinho vazio </h2>
		</div>

		<div class="fixed-bottom">
			<!-- footer-->
			<?php require_once('inc/footer.php');?>
		</div>

	<?php
		exit();
	}

	$registo = mysqli_fetch_array( $resultado );
	$Carrinho_ID = $registo["ID"];
	$PrecoTotal = $registo["PrecoTotal"];

	// obter os itens do carrinho ativo
	$sql = "select * from itenscarrinho, carrinho, produtos where 
				Carrinho.ID=itenscarrinho.Carrinho_ID and Produtos.ID = itenscarrinho.Produto_ID
				and Carrinho.ID=" . $Carrinho_ID;
	//echo $sql; exit();		
	$resultado = mysqli_query($conexao, $sql);
	if (! $resultado ) {
		$_SESSION["erro"] = "Não foi possível obter os dados dos produtos";
		echo '<script>
			window.location.assign("index.php");
		</script>';	
		exit();
	}

	?>

	<div class="margens">
		<h4 class="centro">Carrinho</h4>
		<hr>

			<p class="centro"><small>Para ver o histórico de carrinho. Clique 
				<a href="historico-compras.php" class="azul-link">aqui</a>.</small></p>

			<table class="table">
				<thead>
					<tr>
						<th scope="col">Descrição</th>
						<th scope="col">Quantidade</th>
						<th scope="col">Preço</th>
						<th></th>
					</tr>

			<?php
				while ( $registo = mysqli_fetch_array( $resultado ) ) {
			?>
					<tbody>
						<tr>
							<td><?php echo $registo["Designacao"]; ?></td>
							<td>
								<input id="<?php echo 'item' . $registo['Produto_ID']?>" type="number" min="0" value="<?php echo $registo["Quantidade"]; ?>">
								<a class="azul-link" onclick="alterarQuantidadeItem('<?php echo 'item' . $registo['Produto_ID']?>')">
									<i class="fas fa-sync-alt"></i>
								</a>
							</td>
							<td><?php echo $registo["PrecoTotalProdutos"] . " €"; ?></td>
							<td>
								<a onclick="window.location.assign('gerir-carrinho.php?item_quantidade=0&Produto_ID=<?php echo $registo['Produto_ID']?>')"><i class="fas fa-trash"></i></a>
							</td>	
						</tr>
			<?php
				}
			?>
						<tr>
							<th scope="row"> Preço Total:</th>
							<td></td>
							<td>Mais preço de envio (2,50 €)</td>
							<td><?php echo $PrecoTotal . " €";?></td>
						</tr>
					</tbody>
				</table>
			

			<div>
				<a onclick="window.location.assign('gerir-carrinho.php?remover_carrinho=<?php echo $Carrinho_ID; ?>')">
					<i class="fas fa-dumpster-fire"></i>
					Remover todos os itens
				</a>

				&emsp;

				<a onclick="window.location.assign('gerir-carrinho.php?Carrinho_Estado=1&Carrinho_ID=<?php echo $Carrinho_ID; ?>')">
					<i class="material-icons">&#xe065;</i>
					Finalizar Compras
				</a>

				<div>
					Total: <?php echo $PrecoTotal . " €";?>
				</div>
			</div>
			<!--Fim de sessão iniciada-->

			</div>
		</div>
	
<!-- footer-->
<?php require_once('inc/footer.php');?>

</body>
</html>