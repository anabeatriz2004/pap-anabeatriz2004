<?php 
//var_dump($_POST);
	////////////////////////////require_once 'inc/database.php';
	

	//////////////// método de ligação à base de dados que funciona com a variável $conexao
	$conexao = mysqli_connect("localhost", "root", "", "dom");
	if ( ! $conexao ) {
		echo "Não foi possível ligar à base de dados";
	
		//echo "</br>" . mysqli_connect_error() . "</br>" .
		//	mysqli_connect_errno();
	
		exit();
	}

	mysqli_set_charset( $conexao, "utf8");
	////////////////////////
?>

<!DOCTYPE html>
<html lang="pt">
<head>
	<?php require_once 'inc/header.php'; ?>
</head>
<body>

<!--navbar-->
<?php require_once('inc/navbar.php'); ?>

<?php
	//var_dump($_SESSION); echo "</br>"; var_dump($_POST); exit();

	// verificar se foi premido o botão de
	// recuperação de password
	if ( isset($_POST["Password"]) ) 
	{
		//require "assets/inc/db.php";

		// gerar uma password aleatória
		$password = "";
		$comprimento = rand(8, 12);
		for($i = 0; $i < $comprimento; $i++) 
		{
			if ( rand(1,100) < 33 ) 
			{
				$caracter = chr(rand(ord("0"), ord("9")));
			}
			else if ( rand(1,100) < 50 ) 
			{
				$caracter = chr(rand(ord("a"), ord("z")));
			}
			else 
			{
				$caracter = chr(rand(ord("A"), ord("Z")));
			}
			$password .= $caracter;
		}

		// echo $password; exit();
		$sql = "update utilizadores set Password=Password('" . $password . "'), EstadoConta='1', token='' where Email ='" . $_POST["Email"] . "' ";
//echo $sql; exit();
					
		$resultado = mysqli_query($conexao, $sql);
		if (! $resultado ) {
			$_SESSION["erro"] = "Ocorreu um erro. Por favor tente novamente, mais tarde. 1";
			Header("Location: recuperar-password.php");	
			exit();
		}

		if (mysqli_affected_rows($conexao) == 0) {
			$_SESSION["erro"] = "O email introduzido não existe.";
			Header("Location: recuperar-password.php");	
			exit();
		}
		else {
			// o email existe; 
			// obter a conta de utilizador correspondente
			$sql = "select * from utilizadores where Email ='" . $_POST["Email"] . "'";		
			//echo $sql; exit();
						
			$resultado = mysqli_query($conexao, $sql);
			if (! $resultado ) {
				$_SESSION["erro"] = "Não foi possível aceder ao servidor. Tente novamente mais tarde.";
				Header("Location: recuperar-password.php");	
				exit();
			}
			else {
				$registo = mysqli_fetch_array($resultado);
				$utilizador = $registo["Username"];
			}
		}

		$_SESSION["mensagem"] = "A sua palavra-passe foi alterada com sucesso. A nova palavra-passe é: $password";
		echo "<script> window.location.assign('entrar.php') </script>";	
		exit();
	}
?>
	
	<h1 class="centro"><a class="castanho-escuro-letra" href="index.php" style="text-decoration: none">Eu Queria Ter O Dom</a></h1>

	<!--Formulário-->
	<form class="form margem-login centro" action="recuperar-password.php" method="post" >
		<h2 class="centro">Recuperar Password</h4>
		<hr>
	
		<div class="mb-3">
			<input name="Email" type="Email" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Email">
		</div>

		<p class="centro"><small>
		<a href="editar.php" class="azul-link"> Regressar ao Editar Utilizador! </a></small></p>

		<p class="centro"><small>
		<a href="entrar.php" class="azul-link">Regressar ao login!</a></small></p>
		
		<div class="centro">
			<button name="Password" type="submit" class="btn castanho-claro centro">Recuperar Password</button>
		</div>
		
	</form>

	<br>

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
		
	<!-- footer-->
	<?php require_once('inc/footer.php');?>

</body>
</html>