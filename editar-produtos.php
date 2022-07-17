<?php 
//var_dump($_POST);
	require_once 'inc/database.php';
?>

<!DOCTYPE html>
<html lang="pt">
<head>
	<?php require_once 'inc/header.php'; ?>
</head>
<body>

<!--navbar-->
<?php require_once('inc/navbar.php'); ?>

<!--Para fazer a margens-->
<div class="margens">
	<h2 class="centro">Editar Produto</h2>
	<hr>

	<?php
		// Esta variável armazena os dados do produto
		$produto = NULL;
		$ID = NULL;

		if ($_SERVER['REQUEST_METHOD'] == 'GET')
		{
			// Verificar que o parâmetro ID é válido
			if (!empty($_GET['Produto_ID']))
			{
				if (is_numeric($_GET['Produto_ID']))
				{
					// Armazenar o ID numa variável de sessão
					$_SESSION['Produto_ID'] = $_GET['Produto_ID'];

					// Obter os dados do produto
					$produto = GetProduto($_GET['Produto_ID']);
				}
			}
		}
		
		elseif ($_SERVER['REQUEST_METHOD'] == 'POST')
		{
			$Validacao = True;

			// Atribuir os dados do formulário à variável $produto e, ao mesmo tempo, retirar carateres perigosos			
			$produto = array('Tipo_ID' => htmlentities(trim($_POST['Tipo_ID'])),
							'Cor' => htmlentities(trim($_POST['Cor'])), 
							'Designacao' => htmlentities(trim($_POST['Designacao'])),
							'Descricao' => htmlentities(trim($_POST['Descricao'])),
							'Preco' => htmlentities(trim($_POST['Preco'])),
							'EmStock' => htmlentities(trim($_POST['EmStock'])),
							'EmDestaque' => htmlentities(trim($_POST['EmDestaque'])));			
							//'Img' => htmlentities(trim($_POST['Img'])));				

			// Verificar que os campos numéricos apenas contêm números
			// Campo Tipo_ID				
			if ((!empty($_POST['Tipo_ID'])) && (!is_numeric($produto['Tipo_ID'])))
			{
				$ErroTipo_ID = "Apenas pode conter números";
				$Validacao = False;
			}
			
			// Campo EmStock				
			if ((!empty($_POST['EmStock'])) && (!is_numeric($produto['EmStock'])))
			{
				$ErroEmStock = "Apenas pode conter números";
				$Validacao = False;
			}

			// Verificar que o conteúdo do campo Resumo não ultrapassa o número máximo de carateres permitidos
			if (strlen($produto['Cor']) > 100)
			{
				$ErroCor = "Ultrapassou o número máximo de carateres";
				$Validacao = False;
			}
			
			if (strlen($produto['Designacao']) > 100)
			{
				$ErroDesignacao = "Ultrapassou o número máximo de carateres";
				$Validacao = False;
			}

			if (strlen($produto['Descricao']) > 1000)
			{
				$ErroDescricao = "Ultrapassou o número máximo de carateres";
				$Validacao = False;
			}

			// Se não ocorreram erros de validação, atualizar o produto
			if ($Validacao)
			{
				$_SESSION["erro"] = "Ocorreram erros de validação.";

				// Verificar que o ID está armazenado numa variável de sessão
				if (isset($_SESSION['Produto_ID']))
				{
					$_SESSION["erro"] = "ID do produto Incorreto.";
					
					if (EditarProduto($_SESSION['Produto_ID'], $produto))
					{
						$_SESSION["mensagem"] = "Atualizado o produto com sucesso.";

						// Remover a variável de sessão ID
						unset($_SESSION['Produto_ID']);
						
						// Terminar a sessão
						session_write_close();
						
						// Redirecionar para a página inicial
						// header('Location: index.php?operacao=editar_ok');
						echo '<script> window.location.assign("index.php?operacao=editar_ok"); </script>';
						//exit();
					}
					else
					{
						echo "<div class='alert alert-danger' role='alert'><span class='glyphicon glyphicon-remove'></span>&nbsp;<strong>Ocorreu um erro ao tentar atualizar o produto!</strong></div>";
					}
				}
			}
		}
//var_dump($produto);
	?>		
	<form name="DadosProdutos" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST" class="form-horizontal" role="form">

		<!-- Tipo_ID -->
		<div class="form-group <?php if (!empty($ErroTipo_ID)) { ?>has-error<?php } ?>">
			<label for="tipo_ID" class="col-sm-2 control-label"> Categoria </label>
			<div class="col-sm-12">
				<input type="text" name="Tipo_ID" value="<?php if (!empty($produto['Tipo_ID'])) echo $produto['Tipo_ID']; ?>" maxlength="100" class="form-control" placeholder="Categoria" />
				
				<?php if (!empty($ErroTipo_ID)) { ?>
					<span class="help-block small" style="color:#ff0000"><?php echo $ErroTipo_ID; ?></span>
				<?php } ?>
			</div>
		</div>

		<div class="col-sm-offset-2 col-sm-10">
			<p class="small"><strong><span>Por favor, escrever o ID correspondente</span></strong></p>
			<p class="small"><strong><ul><small><li>ID = 1 - pulseira</li>
											<li>ID = 2 - brinco</li>
											<li>ID = 3 - colar</li>
											<li>ID = 4 - porta-chaves</li>
											<li>ID = 5 - T-shirt</li>
											<li>ID = 6 - Camisola</li>
				</small></ul></strong>
			</p>
		</div>

		<br>

		<!-- Cor -->
		<div class="form-group <?php if (!empty($ErroCor)) { ?>has-error<?php } ?>">
			<label for="Cor" class="col-sm-2 control-label"> Cor </label>
			<div class="col-sm-12">
				<input type="text" name="Cor" value="<?php if (!empty($produto['Cor'])) echo $produto['Cor']; ?>" maxlength="100" class="form-control" placeholder="Cor" />
				
				<?php if (!empty($ErroCor)) { ?>
					<span class="help-block small" style="color:#ff0000"><?php echo $ErroCor; ?></span>
				<?php } ?>
			</div>
		</div>

		<br>

		<!-- Designacao -->
		<div class="form-group <?php if (!empty($ErroDesignacao)) { ?>has-error<?php } ?>">
			<label for="designacao" class="col-sm-2 control-label"> Designação </label>
			<div class="col-sm-12">
				<input type="text" name="Designacao" value="<?php if (!empty($produto['Designacao'])) echo $produto['Designacao']; ?>" maxlength="100" class="form-control" placeholder="Designacao" />
				
				<?php if (!empty($ErroDesignacao)) { ?>
					<span class="help-block small" style="color:#ff0000"><?php echo $ErroDesignacao; ?></span>
				<?php } ?>
			</div>
		</div>

		<br>

		<!-- Descricao -->
		<div class="form-group <?php if (!empty($ErroDescricao)) { ?>has-error<?php } ?>">
			<label for="descricao" class="col-sm-2 control-label">Descrição </label>
			<div class="col-sm-12">
				<textarea class="form-control" id="exampleFormControlTextarea1" rows="5" type="text" name="Descricao" value="<?php if (!empty($produto['Descricao'])) echo $produto['Descricao']; ?>" maxlength="1000" placeholder="Descricao"></textarea>
				<script>document.getElementById("exampleFormControlTextarea1").value="<?php if (!empty($produto['Descricao'])) echo $produto['Descricao']; ?>";</script>
				<?php if (!empty($ErroDescricao)) { ?>
					<span class="help-block small" style="color:#ff0000"><?php echo $ErroDescricao; ?></span>
				<?php } ?>
			</div>
		</div>

		<br>

		<!-- Preco -->
		<div class="form-group <?php if (!empty($ErroPreco)) { ?>has-error<?php } ?>">
			<label for="preco" class="col-sm-2 control-label"> Preço </label>
			<div class="col-sm-12">
				<input type="text" name="Preco" value="<?php if (!empty($produto['Preco'])) echo $produto['Preco']; ?>" maxlength="100" class="form-control" placeholder="Preco" />
				
				<?php if (!empty($ErroPreco)) { ?>
					<span class="help-block small" style="color:#ff0000"><?php echo $ErroPreco; ?></span>
				<?php } ?>
			</div>
		</div>

		<br>

		<!-- EmStock -->
		<div class="form-group <?php if (!empty($ErroEmStock)) { ?>has-error<?php } ?>">
			<label for="emStock" class="col-sm-12 control-label"> Quantidade de Produtos em Stock </label>
			<div class="col-sm-12">
				<input type="text" name="EmStock" value="<?php if (!empty($produto['EmStock'])) echo $produto['EmStock']; ?>" maxlength="100" class="form-control" placeholder="EmStock" />
				
				<?php if (!empty($ErroEmStock)) { ?>
					<span class="help-block small" style="color:#ff0000"><?php echo $ErroEmStock; ?></span>
				<?php } ?>
			</div>
		</div>

		<br>

		<!-- EmDestaque -->
		<div class="form-group <?php if (!empty($ErroEmDestaque)) { ?>has-error<?php } ?>">
			<label for="emDestaque" class="col-sm-2 control-label"> Destaque</label>
			<div class="col-sm-12">
				<input type="text" name="EmDestaque" value="<?php if (!empty($produto['EmDestaque'])) echo $produto['EmDestaque']; ?>" maxlength="100" class="form-control" placeholder="EmDestaque" />
				
				<?php if (!empty($ErroEmDestaque)) { ?>
					<span class="help-block small" style="color:#ff0000"><?php echo $ErroEmDestaque; ?></span>
				<?php } ?>
			</div>
		</div>

		<br>

		<div class="col-sm-offset-2 col-sm-10">
			<p class="small"><strong><span>Por favor, escrever o ID correspondente</span></strong></p>
			<p class="small"><strong><ul><small><li>ID = 0 - Produto em Destaque (aparece na página principal nos "Produtos em Destaques")</li>
												<li>ID = 1 - Produto sem estar Destaque (Não aparece na página principal nos "Produtos em Destaques")</li>
				</small></ul></strong>
			</p>
		</div>
		
		<br>

		<div class="form-group">
			<div class="col-sm-offset-2 col-sm-10">
				<p class="small"><strong>Campos assinalados com <span style='color:#ff0000'>*</span> são obrigatórios</strong></p>
				
				<button type="submit" class="btn castanho-claro">OK</button>
				<a class="btn castanho-claro" href="index.php">Cancelar</a>
			</div>
		</div>
	</form>
</div>




	<!-- The Modal -->
	<div id="erro" class="modal1">
		<div class="modal-content1">
            <div class="modal-header1 castanho-escuro">
                <span class="close1">&times;</span>       
                <h2>Editar Produto</h2>
            </div>

            <div class="modal-body1">
                <p>
                    <?php
                        if ( isset($_SESSION["mensagem"])) {
                            echo $_SESSION["mensagem"];
                        }
                        else if( isset($_SESSION["erro"])) {
                            echo $_SESSION["erro"];
                        }
                    ?>
                </p> 
            </div>
        </div>
    </div>

	<script>
		// Get the modal
		var modal = document.getElementById("erro");

		// Get the <span> element that closes the modal
		var span = document.getElementsByClassName("close1")[0];

		// When the user clicks on <span> (x), close the modal
		span.onclick = function() {
		modal.style.display = "none";
		}

		// When the user clicks anywhere outside of the modal, close it
		window.onclick = function(event) {
		if (event.target == modal) {
		//modal.style.display = "none";
		}
		}
		<?php
			if ( isset($_SESSION["mensagem"]) || isset($_SESSION["erro"]) ){
		?>
			modal.style.display="block";
		<?php
			unset($_SESSION["erro"]);
			unset($_SESSION["mensagem"]);
			}
		?>
	</script>



	<!-- footer-->
	<?php require_once('inc/footer.php'); ?>
	
</body>
</html>