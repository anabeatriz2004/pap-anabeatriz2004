<?php 
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
	<h3 class="centro">Configurações</h3>
	<hr>
	
	<br>

    <div id="divEditarUser">
		
		<?php
			// Esta variável armazena os dados do utilizador
			$utilizador = NULL;
			$ID = NULL;
			
			if ($_SERVER['REQUEST_METHOD'] == 'GET')
			{
				// Verificar que variável de sessão Utilizador_ID é válida
				if (isset($_SESSION["Utilizador_ID"]))
				{
					if (is_numeric($_SESSION["Utilizador_ID"]))
					{
						// Armazenar o ID numa variável
						$ID = (int) $_SESSION["Utilizador_ID"];

						// Obter os dados do utilizador
						$utilizador = GetUtilizador($ID);
					}
				}
			}
			elseif ($_SERVER['REQUEST_METHOD'] == 'POST')
			{
				$Validacao = True;

				// Verificar que os campos obrigatórios estão preenchidos
				// Campo Password
				if (empty($_POST['Password']))
				{
					$ErroPassword = "Preenchimento obrigatório";
					$Validacao = False;
				}

				// Campo Email
				if (empty($_POST['Email']))
				{
					$ErroEmail = "Preenchimento obrigatório";
					$Validacao = False;
				}

				// Atribuir os dados do formulário à variável $produto e, ao mesmo tempo, retirar carateres perigosos			
				$utilizador = array('Nome' => htmlentities(trim($_POST['Nome'])),
							   'Apelido' => htmlentities(trim($_POST['Apelido'])), 
							   'Email' => htmlentities(trim($_POST['Email'])),	
							   'Telemovel' => htmlentities(trim($_POST['Telemovel'])),
							   'Morada' => htmlentities(trim($_POST['Morada'])),
							   'CodPostal' => htmlentities(trim($_POST['CodPostal'])),
							   'Localidade' => htmlentities(trim($_POST['Localidade'])),
							   'Porta' => htmlentities(trim($_POST['Porta'])),
							   'NIF' => htmlentities(trim($_POST['NIF'])));	

				// Verificar que os campos numéricos apenas contêm números
				// Telemovel
				if ((!empty($_POST['Telemovel'])) && (!is_numeric($utilizador['Telemovel'])))
				{
					$ErroTelemovel = "Apenas pode conter números";
					$Validacao = False;
				}
				
				// NIF
				if ((!empty($_POST['NIF'])) && (!is_numeric($utilizador['NIF'])))
				{
					$ErroNIF = "Apenas pode conter números";
					$Validacao = False;
				}

				// Se não ocorreram erros de validação, atualizar o produto
				if ($Validacao)
				{
					// Verificar que o ID está armazenado numa variável de sessão
					if (isset($_SESSION['ID']))
					{
						if (EditarUtilizador($_SESSION['ID'], $utilizador))
						{
							// Remover a variável de sessão ID
							unset($_SESSION['ID']);
							
							// Terminar a sessão
							session_write_close();
							
							// Redirecionar para a página inicial
							echo '<script>
								window.location.assign("index.php");
							</script>';
							exit();
						}
						else
						{
							echo "<div class='alert alert-danger' role='alert'><span class='glyphicon glyphicon-remove'></span>&nbsp;<strong>Ocorreu um erro ao tentar atualizar o produto!</strong></div>";
						}
					}
				}
			}
		?>
    
	<!--Formulário-->
	<form id="formEditarUtilizadores" name="DadosUtilizadores" class="form form-horizontal row g-3" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST" role="form">
		
		<h4 class="centro">Editar Conta</h4> 
		<hr>

		</br>
		
		<div class="alert castanho-escuro" role="alert">
			<i style='font-size:24px' class='fas'>&#xf129;</i>
			<strong>Campos marcados com <span style='color:#ff0000'>*</span> são obrigatórios</strong>
		</div>
	
		<!--Nome & Apelido-->
		<div class="input-group <?php if (!empty($ErroNome)) { ?>has-error<?php } ?> <?php if (!empty($ErroApelido)) { ?>has-error<?php } ?>">
			<span class="input-group-text castanho-escuro">Nome</span>
			<input name="Nome" type="text" aria-label="First name" class="form-control" value="<?php if (!empty($utilizador['Nome'])) echo $utilizador['Nome']; ?>" maxlength="1000" class="form-control" placeholder="Nome">
			<input name="Apelido" type="text" aria-label="Last name" class="form-control" value="<?php if (!empty($utilizador['Apelido'])) echo $utilizador['Apelido']; ?>" maxlength="1000" class="form-control" placeholder="Apelido">
		</div>
		


		<!--Email-->
		<div class="form-group <?php if (!empty($ErroEmail)) { ?>has-error<?php } ?>">
			<label for="inputEmail" class="form-label">Email<span style='color:#ff0000'>*</span></label>
			<input name="Email" type="email" class="form-control" id="inputEmail" placeholder="example@gmail.com" value="<?php if (!empty($utilizador['Email'])) echo $utilizador['Email']; ?>">
			<?php if (!empty($ErroEmail)) { ?>
					<span class="help-inline small" style="color:#ff0000"><?php echo $ErroEmail; ?></span>
			<?php } ?>
		</div>
		
		<!--Telemóvel-->
		<div class="form-group <?php if (!empty($ErroTelemovel)) { ?>has-error<?php } ?>">
			<label for="telemovel" class="col-sm-2 control-label">Telemóvel</label>
			<div class="col-xs-2">
				<input type="text" name="Telemovel" value="<?php if (!empty($utilizador['Telemovel'])) echo $utilizador['Telemovel']; ?>" maxlength="9" class="form-control" placeholder="Telemóvel" />

				<?php if (!empty($ErroTelemovel)) { ?>
					<span class="help-inline small" style="color:#ff0000">
						<div class="alert alert-danger" role="alert">
							<i style='font-size:24px' class='fas'>&#xf071;</i>
							<?php echo $ErroTelemovel; ?>
						</div>
					</span>
				<?php } ?>
			</div>
		</div>

		<br><br><br><br>

		<!--Recuperar Password-->
		<p>
			<b> Recuperar Password <b>
			&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;
			&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;
			&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;
			&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;
			&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;
			&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;
			<a class="btn castanho-escuro" href="recuperar-password.php"> >>> </a>
		</p>

		<br>

		<!--NIF-->
		<div class="form-group <?php if (!empty($ErroNIF)) { ?>has-error<?php } ?>">
			<label for="nif" class="col-sm-2 control-label">NIF</label>
			<div class="col-xs-2">
				<input type="text" name="NIF" value="<?php if (!empty($utilizador['NIF'])) echo $utilizador['NIF']; ?>" maxlength="100" class="form-control" placeholder="NIF" />

				<?php if (!empty($ErroNIF)) { ?>
					<span class="help-inline small" style="color:#ff0000">
						<i style='font-size:24px' class='fas'>&#xf071;</i>
						<?php echo $ErroNIF; ?>
				</span>
				<?php } ?>
			</div>
		</div>

		<!-- Morada -->
		<div class="form-group">
			<label for="inputMorada" class="form-label">Morada</label>
			<input name="Morada" type="text" class="form-control" id="inputMorada" placeholder="Morada" value="<?php if (!empty($utilizador['Morada'])) echo $utilizador['Morada']; ?>">
			<?php if (!empty($ErroMorada)) { ?>
				<span class="help-inline small" style="color:#ff0000">
					<?php echo $ErroMorada; ?>
				</span>
			<?php } ?>
		</div>

		<!-- Código-Postal -->
		<div class="col-md-3">
			<label for="inputCodPostal" class="form-label">Código Postal</label>
			<input name="CodPostal" type="text" class="form-control" id="inputCodPostal" placeholder="xxxx-xxx" value="<?php if (!empty($utilizador['CodPostal'])) echo $utilizador['CodPostal']; ?>">
			<?php if (!empty($ErroCodPostal)) { ?>
				<span class="help-inline small" style="color:#ff0000">
					<?php echo $ErroCodPostal; ?>
				</span>
			<?php } ?>
		</div>

		<!-- Localidade -->
		<div class="col-md-6">
			<label for="inputLocalidade" class="form-label">Localidade</label>
			<input name="Localidade" type="text" class="form-control" id="inputLocalidade" placeholder="Localidade" value="<?php if (!empty($utilizador['Localidade'])) echo $utilizador['Localidade']; ?>">
			<?php if (!empty($ErroLocalidade)) { ?>
				<span class="help-inline small" style="color:#ff0000">
					<?php echo $ErroLocalidade; ?>
				</span>
			<?php } ?>
		</div>

		<!-- Porta -->
		<div class="col-md-3">
			<label for="inputPorta" class="form-label">Porta</label>
			<input name="Porta" type="porta" class="form-control" id="inputPorta" placeholder="Porta" value="<?php if (!empty($utilizador['Porta'])) echo $utilizador['Porta']; ?>">
			<?php if (!empty($ErroPorta)) { ?>
				<span class="help-inline small" style="color:#ff0000">
					<?php echo $ErroPorta; ?>
				</span>
			<?php } ?>
		</div>
		 		
		</br>
		
		<div class="col-12">
			<button type="submit" class="btn castanho-escuro">Editar</button>
			<a class="btn castanho-escuro" href="index.php">Cancelar</a>
				</div>
	</form>
    </div>

	</br>
</div>
	<!-- footer-->
	<?php require_once('inc/footer.php'); ?>
	
</body>
</html>