<?php
	$Utilizadores = NULL;
?>

<!DOCTYPE html>
<html lang="pt">
<!--Parte inteligente da página-->
<head>
	<?php require_once('inc/header.php'); ?>

	<!--Código para ver se a password e o confirma password correspondem-->
	<script>
	  function validate(){
            var a = document.getElementById("password").value;
            var b = document.getElementById("confirm_password").value;
            if (a!=b) {
               alert("As Passwords não correspondem");
               return false;
            }
        }
	</script>
</head>

<body>
<head>
	<!--navbar-->
	<?php require_once('inc/navbar.php'); ?>
</head>
	
	<br>
<!--Para fazer a margens-->
<div class="margens">	
	<div class="alert castanho-escuro" role="alert">
		<i style='font-size:24px' class='fas'>&#xf129;</i>
		<strong>Campos marcados com * são obrigatórios</strong>
	</div>
		
	<?php
		if ($_SERVER['REQUEST_METHOD'] == 'POST')
		{
			$Validacao = True;

			// Verificar que os campos obrigatórios estão preenchidos
			
			// Campo Password
			if (empty($_POST['Password']))
			{
				$ErroPassword = "Preenchimento obrigatório";
				$Validacao = False;
			}

			// Campo ConfirmPassword
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

			// Atribuir os dados do formulário a um array e, ao mesmo tempo, retirar carateres perigosos
			$utilizador = array('Nome' => htmlentities(trim($_POST['Nome'])),
							'Apelido' => htmlentities(trim($_POST['Apelido'])), 
							'Email' => htmlentities(trim($_POST['Email'])),
							'Password' => htmlentities(trim($_POST['Password'])),
							'Telemovel' => htmlentities(trim($_POST['Telemovel'])),
							'NIF' => htmlentities(trim($_POST['NIF'])),
							'Morada' => htmlentities(trim($_POST['Morada'])),
							'CodPostal' => htmlentities(trim($_POST['CodPostal'])),
							'Localidade' => htmlentities(trim($_POST['Localidade'])),
							'Porta' => htmlentities(trim($_POST['Porta'])),
							'EstadoConta' => htmlentities("0"));
						
			// Verificar que os campos numéricos apenas contêm números
			
			// Telemovel
			if ((!empty($_POST['Telemovel'])) && (!is_numeric($utilizador['Telemovel'])))
			{
				$ErroTelemovel = "Apenas pode conter números";
				$Validacao = False;
			}
			
			// NIB
			if ((!empty($_POST['NIF'])) && (!is_numeric($utilizador['NIF'])))
			{
				$ErroNIF = "Apenas pode conter números";
				$Validacao = False;
			}
			
			// Se não ocorreram erros de validação, inserir o utilizador
			if ($Validacao)
			{
				if (InserirUtilizadores($utilizador))
				{
					$_SESSION["Email"] = $utilizador["Email"];
					// Utilizador Cliente
					$_SESSION["Tipo_ID"] = 1;
					// Redirecionar para a página inicial
					$_SESSION["mensagem"] = "Conta criada com sucesso!";
					echo '<script>
							window.location.assign("index.php");
						</script>';
					exit();
				}
				else
				{
					echo "<div class='alert castanho-escuro' role='alert'><i style='font-size:24px' class='fas'>&#xf129;</i><span class='glyphicon glyphicon-remove'></span>&nbsp;<strong>Ocorreu um erro ao tentar inserir o novo utilizador!</strong></div>";
				}
			}

			// comfirmar se as passwords correspondem
			if($_POST['Password']!=$_POST['ConfirmaPassword']){
				$ErroPassEConfirmPass = "As palavras-passe não corespondem.";
				$Validacao = False;
			}
		}
	?>
	
	<br><br>

	<!--Formulário-->
	<form id="fromRegisto" name="DadosClientes" class="form row g-3" onSubmit="return validate();" action="registrar.php" method="post" role="form">

		<h4 class="centro">Criar Conta</h4>
		<hr>
	
		<!--Nome & Apelido-->
		<div class="input-group">
			<span class="input-group-text castanho-escuro">Nome</span>
			<input name="Nome" type="text" aria-label="First name" class="form-control"  value="<?php if (!empty($_POST['Nome'])) echo $_POST['Nome']; ?>" maxlength="1000" class="form-control" placeholder="Nome">
			<input name="Apelido" type="text" aria-label="Last name" class="form-control"  value="<?php if (!empty($_POST['Apelido'])) echo $_POST['Apelido']; ?>" maxlength="1000" class="form-control" placeholder="Apelido">
		</div>
		
		<!-- Email-->
		<div class="form-group">
			<label for="inputEmail4" class="form-label">Email*</label>
			<input name="Email" type="email" class="form-control" id="inputEmail" placeholder="example@gmail.com" value="<?php if (!empty($_POST['Email'])) echo $_POST['Email']; ?>">
			<?php if (!empty($ErroEmail)) { ?>
				<span class="help-inline small" style="color:#ff0000">
					<?php echo $ErroEmail; ?>
				</span>
			<?php } ?>
		</div>

		<!--Password-->
		<div class="col-md-6">
			<label for="inputPassword4" class="form-label">Password*</label>
			<input name="Password" type="password" class="form-control" id="inputPassword" value="<?php if (!empty($_POST['Password'])) echo $_POST['Password']; ?>" placeholder="Password">
			<?php if (!empty($ErroPassword)) { ?>
					<span class="help-inline small" style="color:#ff0000">
						<?php echo $ErroPassword; ?>
					</span>
			<?php } ?>
		</div>

		<!--Confirmar Password-->
		<div class="col-md-6">
			<label for="inputPassword4" class="form-label"> Confirmar Password*</label>
			<input name="ConfirmaPassword" type="password" class="form-control" id="inputConfirmaPassword" value="<?php if (!empty($_POST['Password'])) echo $_POST['Password']; ?>">
			<?php  if (!empty($ErroPassword)) { ?>
					<span class="help-inline small" style="color:#ff0000">
							<?php echo $ErroPassword; ?>
						</div>
					</span>
			<?php } ?>
		</div>
		
		<?php  
		// Mostrar erro caso as passwords não corresponderem
		if (!empty($ErroPassEConfirmPass)) { ?>
			<span class="help-inline small" style="color:#ff0000">
					<?php echo $ErroPassEConfirmPass; ?>
				</div>
			</span>
		<?php } ?>

		<!--Telemóvel-->
		<div class="form-group <?php if (!empty($ErroTelemovel)) { ?>has-error<?php } ?> col-md-4">
			<label for="telemovel" class="col-sm-2 control-label">Telemóvel</label>
			<div class="col-xs-2">
				<input type="text" name="Telemovel" value="<?php if (!empty($_POST['Telemovel'])) echo $_POST['Telemovel']; ?>" maxlength="9" class="form-control" placeholder="Telemovel" />

				<?php if (!empty($ErroTelemovel)) { ?>
					<span class="help-inline small" style="color:#ff0000">
						<?php echo $ErroTelemovel; ?>
					</span>
				<?php } ?>
			</div>
		</div>
		
		<!--NIF-->
		<div class="form-group <?php if (!empty($ErroNIF)) { ?>has-error<?php } ?> col-md-8">
			<label for="nif" class="col-sm-2 control-label">NIF</label>
			<div class="col-xs-2">
				<input type="text" name="NIF" value="<?php if (!empty($_POST['NIF'])) echo $_POST['NIF']; ?>" maxlength="100" class="form-control" placeholder="NIF" />

				<?php if (!empty($ErroNIF)) { ?>
					<span class="help-inline small" style="color:#ff0000">
						<?php echo $ErroNIF; ?>
					</span>
				<?php } ?>
			</div>
		</div>
		
		<!-- Morada -->
		<div class="form-group">
			<label for="inputMorada" class="form-label">Morada</label>
			<input name="Morada" type="text" class="form-control" id="inputMorada" placeholder="Morada" value="<?php if (!empty($_POST['Morada'])) echo $_POST['Morada']; ?>">
			<?php if (!empty($ErroMorada)) { ?>
				<span class="help-inline small" style="color:#ff0000">
					<?php echo $ErroMorada; ?>
				</span>
			<?php } ?>
		</div>

		<!-- Código-Postal -->
		<div class="col-md-3">
			<label for="inputCodPostal" class="form-label">Código Postal</label>
			<input name="CodPostal" type="text" class="form-control" id="inputCodPostal" placeholder="xxxx-xxx" value="<?php if (!empty($_POST['CodPostal'])) echo $_POST['CodPostal']; ?>">
			<?php if (!empty($ErroCodPostal)) { ?>
				<span class="help-inline small" style="color:#ff0000">
					<?php echo $ErroCodPostal; ?>
				</span>
			<?php } ?>
		</div>

		<!-- Localidade -->
		<div class="col-md-6">
			<label for="inputLocalidade" class="form-label">Localidade</label>
			<input name="Localidade" type="text" class="form-control" id="inputLocalidade" placeholder="Localidade" value="<?php if (!empty($_POST['Localidade'])) echo $_POST['Localidade']; ?>">
			<?php if (!empty($ErroLocalidade)) { ?>
				<span class="help-inline small" style="color:#ff0000">
					<?php echo $ErroLocalidade; ?>
				</span>
			<?php } ?>
		</div>

		<!-- Porta -->
		<div class="col-md-3">
			<label for="inputPorta" class="form-label">Porta</label>
			<input name="Porta" type="porta" class="form-control" id="inputPorta" placeholder="Porta" value="<?php if (!empty($_POST['Porta'])) echo $_POST['Porta']; ?>">
			<?php if (!empty($ErroPorta)) { ?>
				<span class="help-inline small" style="color:#ff0000">
					<?php echo $ErroPorta; ?>
				</span>
			<?php } ?>
		</div>
		
		</br>
		<p><small>Já criou conta? Clique 
		<a href="entrar.php" class="azul-link">aqui</a></small>.</p>
		
		<div class="col-12">
			<button type="submit" class="btn castanho-claro">Criar</button>
		</div>
	</form>

	</br>
	
</div>

	<!-- footer-->
	<?php require_once('inc/footer.php'); ?>

</body>
</html>