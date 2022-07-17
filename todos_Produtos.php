<?php
	$Produtos = NULL;
?>

<!DOCTYPE html>
<html lang="pt">
<head>
	<?php require_once('inc/header.php'); ?>
</head>

<body>
	<?php 
		require_once('inc/navbar.php');
	?>
	
	<br>

	<!-- Inserir produto -->
	<?php
		// caso a sessão tenha sido iniciada e se for admin
		// mostrar o botão de criar produto
		if ( isset($_SESSION["Tipo_ID"]) && $_SESSION["Tipo_ID"]==0 ) {
	?>
			<form action="criar-produto.php" method="get">
				<input class="btn castanho-escuro" type="submit" value="Inserir Produto" >
				<input type="text"hidden>
			</form>	
	<?php
		}
	?>


	<br>

	<h3 class="centro">Todos Os Produtos</h3>
	<hr>

	<div class="contentor">

		<br>
		
		<?php
			// Obter os Produtos		
			$Produtos = GetProdutos();
//var_dump($Produtos); exit();	
			for ($i = 0; $i < count($Produtos); ++$i)
			{
		?>

		<div class="card coluna-3-produtos" style="width: 18rem;">
			<img src="img/<?php echo $Produtos[$i]['Img']; ?>"  alt="<?php echo $Produtos[$i]['Designacao']; ?>" style="max-width:250px; max-height:225px; width: auto; height: auto" class="card-img-top">
			<div class="card-body">
				<p class="card-title"><b><?php echo $Produtos[$i]['Designacao']; ?></b></p>
				<p class="card-text"><?php echo $Produtos[$i]['Preco']; ?>€</p>

				<form action="exemplo-produto.php" method="get">
					<input class="btn castanho-escuro" type="submit" value="Visualizar" >
					<input type="text" value="<?php echo $Produtos[$i]['ID']; ?>" name="Produto_ID" hidden>
				</form>	

				<!-- Configurar Produto -->
				<?php
                    // caso a sessão tenha sido iniciada e se for admin
                    // mostrar o botão de editar produto
                    if ( isset($_SESSION["Tipo_ID"]) && $_SESSION["Tipo_ID"]==0 ) {
                        // código a ser executado se o utilizador é um administrador
                    ?>

                        <form action="editar-produtos.php" method="get">
                            <input class="btn castanho-escuro" type="submit" value="Editar Produto" >
                            <input type="text" value="<?php echo $Produtos[$i]['ID']; ?>" name="Produto_ID" hidden>
                        </form>
				<?php
                    }
				?>

				<?php	
				// caso a a sessão tenha sido inicidada
				// mostrar o botão de terminar sessão
				if (isset($_SESSION["Email"]) ) { 
				?>
					<button type="button" class="btn castanho-escuro" onclick="window.location.assign('gerir-carrinho.php?adicionar_item=true&Produto_ID=<?php echo $Produtos[$i]['ID'];?>')">
						Adicionar ao Carrinho
					</button>
				<?php
				}
				?>
			</div>
		</div>

		<?php
			}
		?>
		
	</div>
	
	<!-- footer-->
	<?php require_once('inc/footer.php'); ?>
	
</body>
</html>