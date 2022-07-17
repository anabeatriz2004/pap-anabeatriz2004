<?php
/*
Gere o carrinho de compras, mediante os parâmetros fornecidos por outros scripts:

**** Operações globais sobre um carrinho ****

$_GET["carrinho_id"]
	ID do carrinho na tabela "carrinho", enviado em simultâneo com	$_GET["carrinho_estado"]

$_GET["carrinho_estado"]
	Quando se cria um carrinho, ele tem estado 0 na base de dados - carrinho ativo. Para finalizar-se o carrinho
	(ou efetuar-se a compra), envia-se o GET com valor 1.
	(Ex.: gerir-carrinho.php?carrinho_estado=1&carrinho_id=15 - finalizar o carrinho com ID 15

$_GET["remover_carrinho"]
	Especificar a remoção do carrinho com um dado ID na tabela "carrinho", bem como dos itens que lhe pertenciam
	(Ex.: gerir-carrinho.php?remover_carrinho=12 - remover o carrinho com ID 12


**** Operações sobre os itens de um carrinho ****

$_GET["Produto_ID"]
	ID do item na tabela "produtos" (e também na tabela "itenscarrinho"), enviado em simultâneo com
	$_GET["adicionar_item"] ou $_GET["item_quantidade"]

$_GET["adicionar_item"]
	Se estiver ativa, adiciona-se um novo item ao carrinho (ou soma-se uma unidade à quantidade existente, se o item já estiver no carrinho).
	Se o carrinho não existir, cria-se um novo.
	(Ex.: gerir-carrinho.php?adicionar_item=true&Produto_ID=20 - adicionar uma unidade do produto com ID 20 ao carrinho)

$_GET["item_quantidade"]
	Se estiver ativa, altera-se a quantidade de um item que tem de já estar previamente no carrinho
	(Ex.: gerir-carrinho.php?item_quantidade=3&Produto_ID=10 - alterar para 3 a quantidade do produto com ID 10 no carrinho)
	Para se remover um produto do carrinho, especifica-se que $_GET["item_quantidade"] é igual a zero
	(Ex.: gerir-carrinho.php?item_quantidade=0&Produto_ID=30 - remover do carrinho o produto com ID 30


As variáveis $_GET["adicionar_item"], $_GET["item_quantidade"], $_GET["carrinho_estado"] e $_GET["remover_carrinho"]
não podem ser enviadas simultaneamente com um GET.

*/

session_start();
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
//var_dump($_SESSION); echo "</br>"; var_dump($_GET); exit();

// Atualiza o custo total do carrinho
function atualizar_custo_carrinho($Carrinho_ID) {
	$sql = "update Carrinho set PrecoTotal=
		(select sum(PrecoTotalProdutos) from itenscarrinho where Carrinho_ID=" . $Carrinho_ID . ")
		where id=" . $Carrinho_ID ;
	
	global $conexao;  // para a função ter acesso à variável global $conexao
	
	$resultado = mysqli_query($conexao, $sql);
	//echo $sql . " " . mysqli_error($conexao); exit();
	if (! $resultado ) {
		return false;
	}
	else {
		return true;
	}
}

// verificar se existe uma sessão iniciada
if ( ! isset($_SESSION["Email"]) ) {
	$_SESSION["mensagem"] = "Inicie sessão para efetuar compras.";
	Header("Location: index.php");
	exit();
}


// **** Operações globais sobre um carrinho ****


// verificar se foi especificada a alteração do estado do carrinho
if ( isset($_GET["Carrinho_Estado"]) ) {

	$sql = "update Carrinho set Estado=" . $_GET["Carrinho_Estado"] . ", Data=now() where id=" . $_GET["Carrinho_ID"];
//echo $sql; exit();
	$resultado = mysqli_query($conexao, $sql);
//echo $resultado; exit();

	if (! $resultado ) {
		$_SESSION["erro"] = "Não foi possível finalizar o carrinho.";
		Header("Location: carrinho.php");
		exit();
	}
	else {
		// estado do carrinho alterado com sucesso
		$_SESSION["mensagem"] = "Carrinho finalizado com sucesso.";
		Header("Location: carrinho.php");
		exit();
	}
}
else if ( isset($_GET["remover_carrinho"]) ) {

	// remover primeiro os itens do carrinho
	$sql = "delete from itenscarrinho where Carrinho_ID =" . $_GET["remover_carrinho"];
	$resultado = mysqli_query($conexao, $sql);
	//echo $sql . " " . mysqli_error($conexao); exit();
	if (! $resultado ) {
		$_SESSION["erro"] = "Não foi possível remover os itens do carrinho.";
	}

	// remover o carrinho
	$sql = "delete from Carrinho where ID =" . $_GET["remover_carrinho"];
	$resultado = mysqli_query($conexao, $sql);
	//echo $sql . " " . mysqli_error($conexao); exit();
	if (! $resultado ) {
		$_SESSION["erro"] = "Não foi possível remover o carrinho.1";
	}

	Header("Location: carrinho.php");
	exit();
}


// **** Operações sobre os itens de um carrinho ****


// verificar se foi especificada uma quantidade para um item do carrinho
if ( isset($_GET["item_quantidade"]) ) {
	if ( ! is_numeric($_GET["item_quantidade"]) || $_GET["item_quantidade"] < 0) { 
		$_SESSION["erro"] = "A quantidade do item no carrinho tem de ser positiva ou zero (remoção do item).";
		Header("Location: carrinho.php");
		exit();
	}
}
// verificar se foi especificada a adição de um item ao carrinho
else if ( ! isset($_GET["adicionar_item"]) ) {
	Header("Location: index.php");
	exit();
}

$Produto_ID = $_GET["Produto_ID"];

// verificar se existe um carrinho ativo
$sql = "select Carrinho.ID from Carrinho where Carrinho.Estado=0 and 
	Carrinho.Utilizadores_ID=(select ID from utilizadores where Email='" . $_SESSION["Email"] . "')";
	
$resultado = mysqli_query($conexao, $sql);
//echo $sql . " " . mysqli_error($conexao); exit();
if (! $resultado ) {
	$_SESSION["erro"] = "Não foi possível alterar o carrinho de compras.0";
	Header("Location: carrinho.php");
	exit();
}

if ( mysqli_num_rows($resultado) != 0 ) {
	// existe um carrinho ativo
	$registo = mysqli_fetch_array($resultado);
	$Carrinho_ID = $registo["ID"];
}
else {
	// não existe um carrinho ativo - criá-lo
	$sql = "insert into carrinho (ID, PrecoTotal, Data, Estado, Utilizadores_ID) values
		(null, 0, now(), 0, (select ID from utilizadores where Email='" . $_SESSION["Email"] . "'))";
	
	$resultado = mysqli_query($conexao, $sql);
//echo $sql . " " . mysqli_error($conexao); exit();
		
	if (! $resultado ) {
		$_SESSION["erro"] = "Não foi possível alterar o carrinho de compras.1";
		Header("Location: carrinho.php");
		exit();	
	}
	
	$sql = "select last_insert_id() as Carrinho_ID";  // obter o id do carrinho criado
	$resultado = mysqli_query($conexao, $sql);
	//echo $sql . " " . mysqli_error($conexao); exit();
	if (! $resultado ) {
		$_SESSION["erro"] = "Não foi possível alterar o carrinho.2";
		Header("Location: carrinho.php");
		exit();	
	}
		
	$registo = mysqli_fetch_array($resultado);
	$Carrinho_ID = $registo["Carrinho_ID"];
}

// obter o registo da tabela itenscarrinho onde se encontra o produto a alterar
$sql = "select itenscarrinho.ID as itenscarrinho_ID, itenscarrinho.Quantidade, itenscarrinho.Produto_ID from itenscarrinho
		where itenscarrinho.Carrinho_ID=" . $Carrinho_ID . " and itenscarrinho.Produto_ID=" . $Produto_ID;

$resultado = mysqli_query($conexao, $sql);
//echo $sql . " " . mysqli_error($conexao); exit();
if (! $resultado ) {
	$_SESSION["erro"] = "Não foi possível alterar o carrinho.3";
	Header("Location: carrinho.php");
	exit();
}

if ( mysqli_num_rows($resultado) == 0 ) {
	// o produto ainda não está no carrinho - adicioná-lo com um insert
		
	$sql = "insert into itenscarrinho (ID, Quantidade, PrecoTotalProdutos, Carrinho_ID, Produto_ID)
		values (null, 1, (select Preco from produtos where ID=" . $Produto_ID . "), " . $Carrinho_ID . ", " . $Produto_ID . ")";
		
	$resultado = mysqli_query($conexao, $sql);
	if (! $resultado ) {
		$_SESSION["erro"] = "Não foi possível inserir o item no carrinho.";
	}
	
	// novo item inserido com sucesso
	else if ( ! atualizar_custo_carrinho($Carrinho_ID) ) {
		$_SESSION["erro"] = "Não foi possível atualizar o custo do carrinho.1";
	}
	
	$_SESSION["mensagem"] = "Item adicionado ao carrinho com sucesso!";
	Header("Location: " . $_SERVER['HTTP_REFERER']);
	exit();
}
else {  // o produto está no carrinho

	$registo = mysqli_fetch_array($resultado);
	$itenscarrinho_ID = $registo["itenscarrinho_ID"];
	
	if ( isset($_GET["item_quantidade"]) && $_GET["item_quantidade"] == 0 ) {  ~
		// se foi especificado que a quantidade do item é zero, precede-se à sua remoção do carrinho
		$sql = "delete from itenscarrinho where id=" . $itenscarrinho_ID;
	
		$resultado = mysqli_query($conexao, $sql);
		if (! $resultado ) {
			$_SESSION["erro"] = "Não foi possível remover o item do carrinho.";
			Header("Location: carrinho.php");
			exit();
		}
	
		// verificar se ainda existem itens no carrinho, após a remoção do item atual
		$sql = "select * from itenscarrinho where Carrinho_ID =" . $Carrinho_ID;
		$resultado = mysqli_query($conexao, $sql);
		if (! $resultado ) {
			$_SESSION["erro"] = "Não foi possível alterar o carrinho.4";
			Header("Location: carrinho.php");
			exit();
		}

		if ( mysqli_num_rows($resultado) == 0 ) {
			// o carrinho ficou vazio - removê-lo
			$sql = "delete from carrinho where id =" . $Carrinho_ID;
			$resultado = mysqli_query($conexao, $sql);
//echo $sql . " " . mysqli_error($conexao); exit();
			if (! $resultado ) {
				$_SESSION["erro"] = "Não foi possível remover o carrinho.2";
			}
		}
		// o carrinho não ficou vazio
		else if ( ! atualizar_custo_carrinho($Carrinho_ID) ) {
			$_SESSION["erro"] = "Não foi possível atualizar o custo do carrinho.2";
		}
						
		Header("Location: carrinho.php");
		exit();
	}
	else {
		// alterar a quantidade do item no carrinho
		
		if ( isset($_GET["adicionar_item"]) ) {  // adicionar uma unidade à quantidade do item já existente no carrinho
			$item_quantidade = $registo["Quantidade"] + 1;
		}
		else {
			$item_quantidade = $_GET["item_quantidade"]; // substituir a anterior quantidade do item no carrinho
		}

		$sql = "update itenscarrinho set Quantidade=" . $item_quantidade . ",
		PrecoTotalProdutos = ((select preco from produtos where id=" . $Produto_ID . ") * " . $item_quantidade . ")
			where id=" . $itenscarrinho_ID . " and Produto_ID=" . $Produto_ID ;
			
		$resultado = mysqli_query($conexao, $sql);
		//echo $sql . " " . mysqli_error($conexao); exit();
		if (! $resultado ) {
			$_SESSION["erro"] = "Não foi possível alterar a quantidade do item no carrinho.";
		}
		else if ( ! atualizar_custo_carrinho($Carrinho_ID) ) {
			$_SESSION["erro"] = "Não foi possível atualizar o custo do carrinho.";
		}
		
		$_SESSION["mensagem"] = "Quantidade alterada com sucesso!";
		Header("Location: " . $_SERVER['HTTP_REFERER']);
		exit();
	}
}
?>