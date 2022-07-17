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

	<!-- Criar produto -->
	<?php
		// caso a a sessão tenha sido inicidada e se for admin
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

    <?php
        $tipo = null;
        $designacaoTipoProduto = null;

        // Verificar se são recebidos dados por GET
        if ($_SERVER['REQUEST_METHOD'] == 'GET')
        {
            // Verificar que o parâmetro 'tipo' é válido
            if (!empty($_GET['tipo']))
            {
                if (ctype_digit($_GET['tipo']))
                {
                    $tipo = (int) $_GET['tipo'];
                    $designacaoTipoProduto = getDesignacaoTipoProduto($tipo);
                }
            }
        }

        $tipo_produto = null;

        if ($tipo === null)
        {
            // Obter apenas os produtos em destaque
            $tipo_produto = GetProdutos(true);
        }
        else
        {
            // Obter os produtos de um determinado tipo
            $tipo_produto = GetProdutos(false, $tipo);
        }
    ?>    

        <h3 class="centro margens"><?php echo $designacaoTipoProduto; ?></h3>
        <hr>

        <br>

        <div class="contentor">
            
        <?php
            // Verificar se existem produtos para mostrar
            if (empty($tipo_produto) === true)
            {
                ?>
                    <h2 class="centro margens display-2 castanho-escuro-letra">Sem produtos para mostrar</h2>
                <?php
            }

            else
            {

            for ($i = 0; $i < count($tipo_produto); ++$i)
            {
        ?>

            <div class="card coluna-3-produtos" style="width: 18rem;">
                <img src="img/<?php echo $tipo_produto[$i]['Img']; ?>"  alt="<?php echo $tipo_produto[$i]['Designacao']; ?>" style="max-width:250px; max-height:225px; width: auto; height: auto" class="card-img-top">
                    <div class="card-body">
                        <p class="card-title"><b><?php echo $tipo_produto[$i]['Designacao']; ?></b></p>
                        <p class="card-text"><?php echo $tipo_produto[$i]['Preco']; ?>€</p>

                        <form action="exemplo-produto.php" method="get">
                            <input class="btn castanho-escuro" type="submit" value="Visualizar" >
                            <input type="text" value="<?php echo $tipo_produto[$i]['ID']; ?>" name="Produto_ID" hidden>
                        </form>	

                        <!-- Configurar Produto -->
                        <?php
                        // caso a a sessão tenha sido inicidada e se for admin
                        // mostrar o botão de editar produto
                        if ( isset($_SESSION["Tipo_ID"]) && $_SESSION["Tipo_ID"]==0 ) {
                            // código a ser executado se o utilizador é um administrador
                        ?>

                            <form action="editar-produtos.php" method="get">
                                <input class="btn castanho-escuro" type="submit" value="Editar Produto" >
                                <input type="text" value="<?php echo $tipo_produto[$i]['ID']; ?>" name="Produto_ID" hidden>
                            </form>
                        <?php
                        }
                        ?>

                        <br>

                        <?php	
                        // caso a a sessão tenha sido inicidada
                        // mostrar o botão de terminar sessão
                        if (isset($_SESSION["Email"]) ) { 
                        ?>
                        <button type="button" class="btn castanho-escuro" onclick="window.location.assign('gerir-carrinho.php?adicionar_item=true&Produto_ID=<?php echo $tipo_produto[$i]['ID']; ?>')">
                            Adicionar ao Carrinho
                        </button>
                    <?php
                        }
                    ?>
                </div>
            </div>

        <?php
                }
            }
        ?>
    </div>
	
	<!-- footer-->
	<?php require_once('inc/footer.php'); ?>
	
</body>
</html>