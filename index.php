<?php
	//session_start();
	//var_dump($_SESSION); exit();

// mudar a base de dados
	//require_once('inc/database.php');

	$Produtos = NULL;
?>

<!DOCTYPE html>
<html lang="pt">
<head>
	<?php require_once('inc/header.php'); ?>
</head>
<body>
    <?php require_once('inc/navbar.php'); ?>
    
    <!--Cabeçalho-->
    <header>
        <div class="container col-xxl-8 px-4 py-10">
            <div class="row flex-lg-row-reverse align-items-center g-5 py-5">
                <div class="col-10 col-sm-8 col-lg-6">
                    <img src="img/marca.jpeg" class="d-block mx-lg-auto img-fluid" alt="Logotipo" width="400" loading="lazy">
                </div>

                <div class="col-lg-6">
                    <h1 class="display-5 fw-bold lh-1 mb-3">Eu Queria Ter O Dom</h1>
                    
                    <p class="lead" style="margin-top: -10px">"Hoje eu penso na capoeira </p>
                    <p class="lead" style="margin-top: -10px">Como forma de viver </p>
                    <p class="lead" style="margin-top: -10px">Assim foi Bimba e Pastinha </p>
                    <p class="lead" style="margin-top: -10px">Waldemar e Aberrê" </p>
                    <i>~Contrameste Gesso</i>

                    <br><br><br>
                    
                    <div class="d-grid gap-2 d-md-flex justify-content-md-start">

                    </div>
                </div>
            </div>
        </div>
    </header>
    <!--Fim Do Cabeçalho-->
	<div class="centro">
        <h3>Produtos em destaque</h3>
        <hr>
    </div>

    <div class="contentor centro">
        <?php
        $tipo = null;

        // Verificar se são recebidos dados por GET
        if ($_SERVER['REQUEST_METHOD'] == 'GET')
        {
            // Verificar que o parâmetro 'tipo' é válido
            if (!empty($_GET['tipo']))
            {
                if (ctype_digit($_GET['tipo']))
                {
                    $tipo = (int) $_GET['tipo'];
                }
            }
        }

        $produto_destaque = null;
        $tipo_produto = null;

        if ($tipo === null)
        {
            // Obter apenas os produtos em destaque
            $produto_destaque = GetProdutos(true);
        }
        else
        {
            // Obter os produtos de um determinado tipo
            $tipo_produto = GetProdutos(false, $tipo);
        }
    ?> 

<?php
        for ($i = 0; $i < count($produto_destaque); ++$i)
        {
    ?>

        <div class="card coluna-3-produtos" style="width: 18rem;">
            <img src="img/<?php echo $produto_destaque[$i]['Img']; ?>"  alt="<?php echo $produto_destaque[$i]['Designacao']; ?>" style="max-width:250px; max-height:225px; width: auto; height: auto" class="card-img-top">
                <div class="card-body">
                    <p class="card-title"><b><?php echo $produto_destaque[$i]['Designacao']; ?></b></p>
                    <p class="card-text"><?php echo $produto_destaque[$i]['Preco']; ?>€</p>

                    <form action="exemplo-produto.php" method="get">
                        <input class="btn castanho-escuro" type="submit" value="Visualizar" >
                        <input type="text" value="<?php echo $produto_destaque[$i]['ID']; ?>" name="Produto_ID" hidden>
                    </form>	
                    
                    <br>

                    <!-- Configurar Produto -->
                    <?php
                    // caso a a sessão tenha sido inicidada e se for admin
                    // mostrar o botão de editar produto
                    if ( isset($_SESSION["Tipo_ID"]) && $_SESSION["Tipo_ID"]==0 ) {
                        // código a ser executado se o utilizador é um administrador
                    ?>
                        <form action="editar-produtos.php" method="get">
                            <input class="btn castanho-escuro" type="submit" value="Editar Produto" >
                            <input type="text" value="<?php echo $produto_destaque[$i]['ID']; ?>" name="Produto_ID" hidden>
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
                    <button type="button" class="btn castanho-escuro" onclick="window.location.assign('gerir-carrinho.php?adicionar_item=true&Produto_ID=<?php echo $produto_destaque[$i]['ID']; ?>')">
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