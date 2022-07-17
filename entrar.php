<?php
	$Utilizadores = NULL;
?>

<!DOCTYPE html>
<html lang="pt">
<!--Parte inteligente da página-->
<head>
	<?php require_once('inc/header.php'); ?>
</head>

<body>
	
<!--Para fazer a margens-->
<div class="margens">		
		<?php
			if ($_SERVER['REQUEST_METHOD'] == 'POST')
			{ 
				$Validacao = True;

				// Verificar que os campos obrigatórios estão preenchidos

				// Campo Email
				if (empty($_POST['Email']))
				{
					$ErroEmail = "Preenchimento obrigatório";
					$Validacao = False;
				}
				
				// Campo Password
				if (empty($_POST['Password']))
				{
					$ErroPassword = "Preenchimento obrigatório";
					$Validacao = False;
				}
				

				// Atribuir os dados do formulário a um array e, ao mesmo tempo, retirar carateres perigosos
				$utilizador = array('Email' => htmlentities(trim($_POST['Email'])),
							   'Password' => htmlentities(trim($_POST['Password'])));
		

							   
				
				$conexao = mysqli_connect("localhost", "root", "", "dom");

	if ( ! $conexao ) {
		// para debug
		//echo mysqli_connect_error() . "</br>" . mysqli_connect_errno();
		
		$_SESSION["erro"] = "Não foi possível ligar à base de dados.2";
		echo '<script>
				window.location.assign("entrar.php");
			</script>';
		exit();
	}

	mysqli_set_charset( $conexao, "utf8");	
			
	$sql = "select * from utilizadores where Email='" . $utilizador["Email"] . "'";
	//var_dump($utilizador);				
	//echo $sql; exit();
				
	$resultado = mysqli_query($conexao, $sql);
	if (! $resultado ) {
		$_SESSION["erro"] = "Não foi possível obter os dados do utilizador.0";
		echo '<script>
				window.location.assign("entrar.php");
			</script>';
		exit();
	}

	// verificar se o utilizador existe
	if ( $registo = mysqli_fetch_array( $resultado ) ) {

		// verificar se a password  está correta
		$sql = "select * from utilizadores
			where Email='" . $_POST["Email"] . "'
				&& Password=Password('" . $_POST["Password"] . "')";
// echo $sql; exit();
		
		$resultado = mysqli_query($conexao, $sql);
		if (! $resultado ) {
			$_SESSION["erro"] = "Não foi possível obter os dados do utilizador.1";
var_dump($_SESSION); exit();
			echo '<script>
				window.location.assign("entrar.php");
			</script>';
			exit();
		}
		if ( $registo = mysqli_fetch_array( $resultado ) ) {
			// o utilizador e a password estão corretos;
			// iniciar sessão
			$_SESSION["Email"] = $registo["Email"];
			$_SESSION["Tipo_ID"] = $registo["Tipo_ID"];
			$_SESSION["Utilizador_ID"] = $registo["ID"];
			// $registo["tipos_utilizador_id_tipos_utilizador"];		
//var_dump($_SESSION);
			
			echo '<script>
				window.location.assign("index.php");
			</script>';	
			exit();
		}
		else {
			// a password está errada
			$_SESSION["erro"] = "A palavra-passe está incorreta.";
			echo '<script>
				window.location.assign("entrar.php");
			</script>';
			exit();
		}
	}
	else {
		// o utilizador não existe
		//echo "<script>alert('O utilizador não existe.')</script>";
		$_SESSION["erro"] = "O utilizador não existe.";
		echo '<script>
			window.location.assign("entrar.php");
		</script>';
		exit();
		}	
	}
	?>
	
	<h1 class="centro"><a class="castanho-escuro-link" href="index.php" style="text-decoration: none">Eu Queria Ter O Dom</a></h1>

	<!--Formulário-->
	<form class="form margem-login" action="entrar.php" method="post" >
		<h4 class="centro">Iniciar Sessão</h4>
		<hr>
	
		<div class="mb-3">
			<input name="Email" type="email" class="form-control" id="Email" aria-describedby="emailHelp" placeholder="Email">
		</div>

		<script>
			function mostrarOcultarPassword(){
				var password = document.getElementById("password");
				if (password.type=="password"){
					password.type="text";
				}
				else{
					password.type="password";
				}
			}
		</script>

		<div class="mb-3">
			<input name="Password" type="password" class="form-control" id="password" placeholder="Password">

			<label class="container"> 
				<small> Mostrar Senha </small>
  				<input type="checkbox" onclick="mostrarOcultarPassword()">
  				<span class="checkmark"> </span>
			</label>
		</div>

		<p class="centro"><small>Ainda não criou conta? Clique 
		<a href="registrar.php" class="azul-link">aqui.</a></small></p>
		
		<div class="centro">
			<button type="submit" class="btn castanho-claro centro">Iniciar Sessão</button>
		</div>
	</form>

	</br>

		<!-- The Modal -->
		<div id="erro" class="modal1">
		<div class="modal-content1">
            <div class="modal-header1 castanho-escuro">
                <span class="close1">&times;</span>       
                <h2>Iniciar Sessão</h2>
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
</body>
</html>