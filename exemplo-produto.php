<?php
	require_once('inc/database.php');

	$Produto = NULL;
?>

<!DOCTYPE html>
<html lang="pt">
<head>
	<?php require_once('inc/header.php'); ?>
</head>

<body>
	<!--navbar-->
	<?php require_once('inc/navbar.php'); ?>

<!--Para fazer a margens-->
<div class="margens">	
	
	<div class="margens">
		<div class="container">
			<?php
				// Obter os Produtos		
				$Produto = GetProduto($_GET["Produto_ID"]);
//var_dump($Produto);exit();
			?>

			<div>	
				<img class="imagem-exemplo-produto" src="img/<?php echo $Produto['Img']; ?>" alt="<?php echo $Produto['Descricao']; ?>" style="width:100%">
				<div>
					<p><b><?php echo $Produto['Designacao']; ?></b></p>
					<p><?php echo $Produto['Preco']; ?>€</p>
				</div>
			</div>
		</div>

		<?php	
			// caso a a sessão tenha sido inicidada
			// mostrar o botão de terminar sessão
			if (isset($_SESSION["Email"]) ) { 
		?>

			<button type="button" class="btn castanho-escuro" onclick="window.location.assign('gerir-carrinho.php?adicionar_item=true&Produto_ID=<?php echo $_GET['Produto_ID']?>')">
				Adicionar ao Carrinho
			</button>
		<?php
			}
		?>

		<!-- Configurar Produto -->
		<?php
		// caso a a sessão tenha sido inicidada e se for admin
		// mostrar o botão de editar produto
		if ( isset($_SESSION["Tipo_ID"]) && $_SESSION["Tipo_ID"]==0 ) {
			// código a ser executado se o utilizador é um administrador
		?>
			<form action="editar-produtos.php" method="get">
				<input class="btn castanho-escuro" type="submit" value="Editar Produto" >
				<input type="text" value="<?php echo $Produto['ID']; ?>" name="Produto_ID" hidden>
			</form>
		<?php
		}
		?>

	</div>

	<table class="table">
			<thead class="thead-dark">
				<tr>
					<th scope="col">Detalhes</th>
				</tr>
			</thead>
			<tbody>
				<tr>
					<th scope="row">Descrição</th>
					<td><?php echo $Produto['Descricao']; ?></td>
				</tr>

				<tr>
					<th scope="row">Tipo</th>
					<td><?php echo $Produto['Tipo_Produto_Designacao']; ?></td>
				</tr>

				<tr>
					<th scope="row">Cor</th>
					<td><?php echo $Produto['Cor']; ?></td>
				</tr>
			</tbody>
		</table>

		<table class="table">
			<!-- Vizualizar parte administrativa de um produtos -->
			<?php
				// caso a a sessão tenha sido inicidada e se for admin
				// mostrar a parte administrativa de um produto
				if ( isset($_SESSION["Tipo_ID"]) && $_SESSION["Tipo_ID"]==0 ) {
				// código a ser executado se o utilizador é um administrador
			?>
				<br>

				<thead class="thead-dark">
					<tr>
						<th scope="col">Detalhes (Apenas Assecíveis pelo Administrador)</th>
					</tr>
				</thead>
			<tbody>
				<tr>
					<th scope="row">EmStock</th>
					<td><?php echo $Produto['EmStock']; ?></td>
				</tr>

				<tr>
					<th scope="row">EmDestaque</th>
					<td><?php echo $Produto['EmDestaque']; ?></td>
				</tr>
			<?php
				}
			?>
			</tbody>
		</table>

</div>

	<!-- footer-->
	<?php require_once('inc/footer.php'); ?>
	
</body>
</html>