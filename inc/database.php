<?php
	// Dados de ligação à base de dados
	const DB_SERVER = "localhost";
	const DB_USER = "root";
	const DB_PASSWORD = "";
	const DB_NAME = "dom";

	// buscar o tipo de produtos
	function getTiposProdutos()
	{
		try
		{
			$db = new PDO('mysql:host=' . DB_SERVER . ';dbname=' . DB_NAME . ';charset=utf8', DB_USER, DB_PASSWORD);
		}
		catch (PDOException $e)
		{
			return null;
		}

		// Declarar e inicializar a variável que irá conter os dados
		$dados = null;

		try
		{
			$stmt = $db->prepare("SELECT * FROM tipo_produtos");

			// Executar a query e verificar que não retornou false
			if ($stmt->execute())
			{
				$dados = $stmt->fetchAll(PDO::FETCH_ASSOC);
			}
		}
		catch (PDOException $e)
		{
			// Registar no log
			LogSystem::Log(new LogEvent(LogEventType::ERRO, 0, __FILE__, __CLASS__, __METHOD__ . "()", $e->getMessage()));

			$dados = null;
		}

		return $dados;
	}

	// Obter a designação de um tipo de produtos.
	function getDesignacaoTipoProduto($tipo)
	{
		try
		{
			$db = new PDO('mysql:host=' . DB_SERVER . ';dbname=' . DB_NAME . ';charset=utf8', DB_USER, DB_PASSWORD);
		}
		catch (PDOException $e)
		{
			return null; 
		}

		// Declarar e inicializar a variável que irá conter os dados
		$designacao = null;

		try
		{
			$stmt = $db->prepare("SELECT Designacao FROM tipo_produtos WHERE ID = ?");
			$stmt->bindValue(1, $tipo, PDO::PARAM_INT);
		
			// Executar a query e verificar que não retornou false
			if ($stmt->execute())
			{
				$registo = $stmt->fetch();
				$designacao = $registo['Designacao'];
			}
		}
		catch (PDOException $e)
		{
			// Registar no log
			LogSystem::Log(new LogEvent(LogEventType::ERRO, 0, __FILE__, __CLASS__, __METHOD__ . "()", $e->getMessage()));

			$designacao = null;
		}

		return $designacao;
	}

	/*
		Retorna os dados de um produto existente na base de dados.

		Parâmetros:
			$ID = a chave primária do registo que se pretende obter
			
		Retorna:
			Os dados do produto ou NULL caso o registo não exista ou tenha ocorrido qualquer erro
	*/	
	function GetProduto($ID)
	{
		// Declarar e inicializar a variável que irá conter os dados
		$registo = null;

		try
		{
			$db = new PDO('mysql:host=' . DB_SERVER . ';dbname=' . DB_NAME . ';charset=utf8', DB_USER, DB_PASSWORD);
		}
		catch (PDOException $e)
		{
			echo "Ocorreu o seguinte erro: " . $e->getMessage() . "<br/>";
			die();
		}

        $stmt = $db->prepare('SELECT produtos.*, tipo_produtos.Designacao AS Tipo_Produto_Designacao FROM produtos, tipo_produtos WHERE produtos.Tipo_ID = tipo_produtos.ID AND produtos.ID = ?');
        $stmt->bindValue(1, $ID, PDO::PARAM_INT);
		
		// Executar a query e verificar que não retornou false
		if ($stmt->execute())
		{
			$registo = $stmt->fetch();
		}
		
		// Terminar a ligação
		$db = null;

		// Retornar os dados
		return $registo;
	}	
	
	/*
		Obtém os dados de todos os produtos existentes na base de dados.

		Retorna:
			os dados dos produtos
			null caso ocorra algum erro ou não existam dados.
	*/
	function GetProdutos($destaque = false, $tipo = null)
	{
		try
		{
			$db = new PDO('mysql:host=' . DB_SERVER . ';dbname=' . DB_NAME . ';charset=utf8', DB_USER, DB_PASSWORD);
		}
		catch (PDOException $e)
		{
			return null;
		}
	
		// Declarar e inicializar a variável que irá conter os dados
		$dados = null;
	
		$sql = 'SELECT produtos.ID, tipo_produtos.Designacao AS Tipo, Cor, produtos.Designacao AS Designacao, Descricao, Preco, Img FROM produtos, tipo_produtos WHERE produtos.Tipo_ID = tipo_produtos.ID';
	
		// Verificar se se pretendem apenas produtos em destaque
		if ($destaque === true)
		{
			$sql .= ' AND EmDestaque = true';
		}
	
		// Verificar se se pretendem apenas produtos de um determinado tipo
		if ($tipo !== null)
		{
			$sql .= ' AND produtos.Tipo_ID = :Tipo_ID';
		}
	
		try
		{
			$stmt = $db->prepare($sql);
	
			// Acrescentar um parâmetro à query caso se pretendam produtos de um determinado tipo
			if ($tipo !== null)
			{
				$stmt->bindValue(':Tipo_ID', $tipo, PDO::PARAM_INT);
			}
	
			// Executar a query e verificar que não retornou false
			if ($stmt->execute())
			{
				$dados = $stmt->fetchAll(PDO::FETCH_ASSOC);
			}
		}
		catch (PDOException $e)
		{
			// Registar no log
			LogSystem::Log(new LogEvent(LogEventType::ERRO, 0, __FILE__, __CLASS__, __METHOD__ . "()", $e->getMessage()));
	
			$dados = null;
		}
	 
		return $dados;
	}

	/*
		Cria um novo registo para um produto.
	   
		Retorna:
			True se a operação foi executada com sucesso
			False caso contrário
	*/
	function InserirProduto($DadosProdutos)
	{
		// Inicializar uma variável que sinaliza o sucesso ou insucesso da operação
		$sucesso = False;

		try
		{
			$db = new PDO('mysql:host=' . DB_SERVER . ';dbname=' . DB_NAME . ';charset=utf8', DB_USER, DB_PASSWORD);

			$stmt = $db->prepare('INSERT INTO produtos (Tipo_ID, Cor, Designacao, Descricao, Preco, 
									EmStock, EmDestaque, Img) 
									VALUES (?, ?, ?, ?, ?, ?, ?, ?)');
			$stmt->bindValue(1, (int) $DadosProdutos['Tipo_ID'], PDO::PARAM_INT);
			$stmt->bindValue(2, $DadosProdutos['Cor'], PDO::PARAM_STR);
			$stmt->bindValue(3, $DadosProdutos['Designacao'], PDO::PARAM_STR);
			$stmt->bindValue(4, $DadosProdutos['Descricao'], PDO::PARAM_STR);
			$stmt->bindValue(5, $DadosProdutos['Preco'], PDO::PARAM_STR);
			$stmt->bindValue(6, (int) $DadosProdutos['EmStock'], PDO::PARAM_INT);
			$stmt->bindValue(7, $DadosProdutos['EmDestaque'], PDO::PARAM_STR);
			$stmt->bindValue(8, $DadosProdutos['Img'], PDO::PARAM_STR);
//var_dump($stmt); exit();		

			// Executar a query e verificar que não retornou false
			if ($stmt->execute())
			{
				// A operação foi executada com sucesso
				$sucesso = True;
			}
		}
		catch (PDOException $e)
		{
			echo "Ocorreu o seguinte erro: " . $e->getMessage() . "<br/>";
			die();
		}

		// Terminar a ligação
		$db = null;

		return $sucesso;
	}	

	/*
		Editar o registo de um produto.

		Parâmetros:
			$ID = a chave primária do registo que se pretende atualizar
			$DadosProdutos = os dados do produto a atualizar
			
		Retorna:
			True se a operação foi executada com sucesso
			False caso contrário
	*/
	function EditarProduto($ID, $DadosProdutos)
	{
		// Inicializar uma variável que sinaliza o sucesso ou insucesso da operação
		$sucesso = False;

		try
		{
			$db = new PDO('mysql:host=' . DB_SERVER . ';dbname=' . DB_NAME . ';charset=utf8', DB_USER, DB_PASSWORD);

			// $stmt - com a parametro IMG
			//$stmt = $db->prepare('UPDATE produtos SET Tipo_ID = ?, Cor = ?, Designacao = ?, Descricao = ?, Preco = ?, EmStock = ?, EmDestaque = ?, Img = ? WHERE ID = ?');
			
			// $stmt - sem a parametro IMG
			$stmt = $db->prepare('UPDATE produtos 
									SET Tipo_ID = ?, Cor = ?, Designacao = ?, Descricao = ?, Preco = ?, EmStock = ?, EmDestaque = ? 
									WHERE ID = ?');

			$stmt->bindValue(1, (int) $DadosProdutos['Tipo_ID'], PDO::PARAM_INT);
			$stmt->bindValue(2, $DadosProdutos['Cor'], PDO::PARAM_STR);
			$stmt->bindValue(3, $DadosProdutos['Designacao'], PDO::PARAM_STR);
			$stmt->bindValue(4, $DadosProdutos['Descricao'], PDO::PARAM_STR);
			$stmt->bindValue(5, (int) $DadosProdutos['EmStock'], PDO::PARAM_INT);
			$stmt->bindValue(6, $DadosProdutos['EmDestaque'], PDO::PARAM_STR);
			$stmt->bindValue(7, $DadosProdutos['Preco'], PDO::PARAM_STR);
			//$stmt->bindValue(8, $DadosProdutos['Img'], PDO::PARAM_STR);
			$stmt->bindValue(8, (int) $ID, PDO::PARAM_INT);

			// Executar a query e verificar que não retornou false
			if ($stmt->execute())
			{
				// A operação foi executada com sucesso
				$sucesso = True;
			}

		}
		catch (PDOException $e)
		{
			echo "Ocorreu o seguinte erro: " . $e->getMessage() . "<br/>";
			die();
		}

        
		
		// Terminar a ligação
		$db = null;

		return $sucesso;
	}	

	/*
		Elimina o registo de um produto.
	   
		Parâmetros:
			$ID = a chave primária do registo que se pretende eliminar
			
		Retorna:
			True se a operação foi executada com sucesso
			False caso contrário
	*/
	function EliminarProduto($ID)
	{
		// Inicializar uma variável que sinaliza o sucesso ou insucesso da operação
		$sucesso = False;

		try
		{
			$db = new PDO('mysql:host=' . DB_SERVER . ';dbname=' . DB_NAME . ';charset=utf8', DB_USER, DB_PASSWORD);
		}
		catch (PDOException $e)
		{
			echo "Ocorreu o seguinte erro: " . $e->getMessage() . "<br/>";
			die();
		}

        $stmt = $db->prepare('DELETE FROM produtos WHERE ID = ?');
		$stmt->bindValue(1, $ID, PDO::PARAM_INT);
		
		// Executar a query e verificar que não retornou false
		if ($stmt->execute())
		{
			// A operação foi executada com sucesso
			$sucesso = True;
		}
		
		// Terminar a ligação
		$db = null;

		return $sucesso;
	}
	

	
	
	
	
	
	
	
	
	
	
	





























	
	
	




	
	/*
		Retorna os dados de um utilizador existente na base de dados.

		Parâmetros:
			$ID = a chave primária do registo que se pretende obter
			
		Retorna:
			Os dados do utilizador ou NULL caso o registo não exista ou tenha ocorrido qualquer erro
	*/	
	function GetUtilizador($ID)
	{
		// Declarar e inicializar a variável que irá conter os dados
		$registo = null;

		try
		{
			$db = new PDO('mysql:host=' . DB_SERVER . ';dbname=' . DB_NAME . ';charset=utf8', DB_USER, DB_PASSWORD);
		}
		catch (PDOException $e)
		{
			echo "Ocorreu o seguinte erro: " . $e->getMessage() . "<br/>";
			die();
		}

        $stmt = $db->prepare('SELECT * FROM utilizadores WHERE ID = ?');
        $stmt->bindValue(1, $ID, PDO::PARAM_INT);
		
		// Executar a query e verificar que não retornou false
		if ($stmt->execute())
		{
			$registo = $stmt->fetch();
		}
		
		// Terminar a ligação
		$db = null;

		// Retornar os dados
		return $registo;
	}	
	
	/*
		Obtém os dados de todos os utilizadores existentes na base de dados.

		Retorna:
			os dados utilizadores
			null caso ocorra algum erro ou não existam dados.
	*/
	function GetUtilizadores()
	{
		// Declarar e inicializar a variável que irá conter os dados
		$registos = NULL;

		try
		{
			// Criar a ligação
			$db = new PDO('mysql:host=' . DB_SERVER . ';dbname=' . DB_NAME . ';charset=utf8', DB_USER, DB_PASSWORD);
		}
		catch (PDOException $e)
		{
			echo "Ocorreu o seguinte erro: " . $e->getMessage() . "<br/>";
			die();
		}
// MUDAR
		// Executar a query
		if (($stmt = $db->query('SELECT Nome, Apelido, Email, `Password`, Telemovel, NIF, Morada, CodPostal, Localidade, Porta FROM utilizadores;', PDO::FETCH_ASSOC)) != false)
		{
			// Colocar os dados obtidos na variável $registos, em formato array associativo
			$registos = $stmt->fetchAll();
		}
		
		// Terminar a ligação
		$db = null;

		// Retornar os dados
		return $registos;
	}

	/*
		Cria um novo registo para um utilizador.
	   
		Retorna:
			True se a operação foi executada com sucesso
			False caso contrário
	*/
	function InserirUtilizadores($DadosUtilizadores)
	{
		// Inicializar uma variável que sinaliza o sucesso ou insucesso da operação
		$sucesso = False;

		try
		{
			$db = new PDO('mysql:host=' . DB_SERVER . ';dbname=' . DB_NAME . ';charset=utf8', DB_USER, DB_PASSWORD);
						
			$stmt = $db->prepare('INSERT INTO utilizadores (Nome, Apelido, Email, `Password`, Telemovel, NIF, Morada, CodPostal, Localidade, Porta, EstadoConta) 
									VALUES (?, ?, ?, Password(?), ?, ?, ?, ?, ?, ?, ?)');
			$stmt->bindValue(1, $DadosUtilizadores['Nome'], PDO::PARAM_STR);
			$stmt->bindValue(2, $DadosUtilizadores['Apelido'], PDO::PARAM_STR);
			$stmt->bindValue(3, $DadosUtilizadores['Email'], PDO::PARAM_STR);
			$stmt->bindValue(4, $DadosUtilizadores['Password'], PDO::PARAM_STR);
			$stmt->bindValue(5, (int) $DadosUtilizadores['Telemovel'], PDO::PARAM_INT);
			$stmt->bindValue(6, (int) $DadosUtilizadores['NIF'], PDO::PARAM_INT);
			$stmt->bindValue(7, $DadosUtilizadores['Morada'], PDO::PARAM_STR);
			$stmt->bindValue(8, $DadosUtilizadores['CodPostal'], PDO::PARAM_STR);
			$stmt->bindValue(9, $DadosUtilizadores['Localidade'], PDO::PARAM_STR);
			$stmt->bindValue(10, $DadosUtilizadores['Porta'], PDO::PARAM_STR);
			$stmt->bindValue(11, $DadosUtilizadores['EstadoConta'], PDO::PARAM_STR);
//var_dump($stmt); exit();		
			
			// Executar a query e verificar que não retornou false
			if ($stmt->execute())
			{
				// A operação foi executada com sucesso
				$sucesso = True;
			}
		}
		catch (PDOException $e)
		{
			echo "Ocorreu o seguinte erro: " . $e->getMessage() . "<br/>";
			die();
		}

		// Terminar a ligação
		$db = null;

		return $sucesso;
	}	

	/*
		Editar o registo de utilizador.

		Parâmetros:
			$ID = a chave primária do registo que se pretende atualizar
			$DadosUtilizadores = os dados do utilizador a atualizar
			
		Retorna:
			True se a operação foi executada com sucesso
			False caso contrário
	*/
	function EditarUtilizadores($ID, $DadosUtilizadores)
	{
		// Inicializar uma variável que sinaliza o sucesso ou insucesso da operação
		$sucesso = False;
		try
		{
			$db = new PDO('mysql:host=' . DB_SERVER . ';dbname=' . DB_NAME . ';charset=utf8', DB_USER, DB_PASSWORD);
			$stmt = $db->prepare('UPDATE utilizadores SET Nome = ?, Apelido = ?, Email = ?, Password = ?, Telemovel = ?, NIF = ?, Morada = ?, CodPostal = ?, Localidade = ?, Porta = ?, EstadoConta = ? WHERE ID = ?');
			$stmt->bindValue(1, $DadosUtilizadores['Nome'], PDO::PARAM_STR);
			$stmt->bindValue(2, $DadosUtilizadores['Apelido'], PDO::PARAM_STR);
			$stmt->bindValue(3, $DadosUtilizadores['Email'], PDO::PARAM_STR);
			$stmt->bindValue(4, $DadosUtilizadores['Password'], PDO::PARAM_STR);
			$stmt->bindValue(5, (int) $DadosUtilizadores['Telemovel'], PDO::PARAM_INT);
			$stmt->bindValue(6, (int) $DadosUtilizadores['NIF'], PDO::PARAM_INT);
			$stmt->bindValue(7, $DadosUtilizadores['Morada'], PDO::PARAM_STR);
			$stmt->bindValue(8, $DadosUtilizadores['CodPostal'], PDO::PARAM_STR);
			$stmt->bindValue(9, $DadosUtilizadores['Localidade'], PDO::PARAM_STR);
			$stmt->bindValue(10, $DadosUtilizadores['Porta'], PDO::PARAM_STR);
			$stmt->bindValue(11, $DadosUtilizadores['EstadoConta'], PDO::PARAM_STR);
			$stmt->bindValue(12, $ID, PDO::PARAM_INT);
			// Executar a query e verificar que não retornou false
			if ($stmt->execute())
			{
				// A operação foi executada com sucesso
				$sucesso = True;
			}

		}
		catch (PDOException $e)
		{
			echo "Ocorreu o seguinte erro: " . $e->getMessage() . "<br/>";
			die();
		}

        
		
		// Terminar a ligação
		$db = null;

		return $sucesso;
	}	

	/*
		Elimina o registo de um utilizador.
	   
		Parâmetros:
			$ID = a chave primária do registo que se pretende eliminar
			
		Retorna:
			True se a operação foi executada com sucesso
			False caso contrário
	*/
	function EliminarUtilizadores($ID)
	{
		// Inicializar uma variável que sinaliza o sucesso ou insucesso da operação
		$sucesso = False;

		try
		{
			$db = new PDO('mysql:host=' . DB_SERVER . ';dbname=' . DB_NAME . ';charset=utf8', DB_USER, DB_PASSWORD);
		}
		catch (PDOException $e)
		{
			echo "Ocorreu o seguinte erro: " . $e->getMessage() . "<br/>";
			die();
		}

        $stmt = $db->prepare('DELETE FROM utilizadores WHERE ID = ?');
		$stmt->bindValue(1, $ID, PDO::PARAM_INT);
		
		// Executar a query e verificar que não retornou false
		if ($stmt->execute())
		{
			// A operação foi executada com sucesso
			$sucesso = True;
		}
		
		// Terminar a ligação
		$db = null;

		return $sucesso;
	}
?>














































<?php
	// Compras

	/*
		Retorna os dados de um utilizador existente na base de dados.

		Parâmetros:
			$ID = a chave primária do registo que se pretende obter
			
		Retorna:
			Os dados do utilizador ou NULL caso o registo não exista ou tenha ocorrido qualquer erro
	*/	
	function GetItensCarrinho($ID)
	{
		// Declarar e inicializar a variável que irá conter os dados
		$registo = null;

		try
		{
			$db = new PDO('mysql:host=' . DB_SERVER . ';dbname=' . DB_NAME . ';charset=utf8', DB_USER, DB_PASSWORD);
		}
		catch (PDOException $e)
		{
			echo "Ocorreu o seguinte erro: " . $e->getMessage() . "<br/>";
			die();
		}

        $stmt = $db->prepare('SELECT * FROM itenscarrinho WHERE ID = ?');
        $stmt->bindValue(1, $ID, PDO::PARAM_INT);
		
		// Executar a query e verificar que não retornou false
		if ($stmt->execute())
		{
			$registo = $stmt->fetch();
		}
		
		// Terminar a ligação
		$db = null;

		// Retornar os dados
		return $registo;
	}	
	
	/*
		Obtém os dados de todos os utilizadores existentes na base de dados.

		Retorna:
			os dados utilizadores
			null caso ocorra algum erro ou não existam dados.
	*/
	function GetCarrinho()
	{
		// Declarar e inicializar a variável que irá conter os dados
		$registos = NULL;

		try
		{
			// Criar a ligação
			$db = new PDO('mysql:host=' . DB_SERVER . ';dbname=' . DB_NAME . ';charset=utf8', DB_USER, DB_PASSWORD);
		}
		catch (PDOException $e)
		{
			echo "Ocorreu o seguinte erro: " . $e->getMessage() . "<br/>";
			die();
		}

		// Executar a query
		if (($stmt = $db->query('SELECT Produto_ID, Quantidade, Carrinho_ID, tipo_produtos.Designacao AS Tipo, produtos.Cor, produtos.Designacao AS Desiganaçâo, produtos.Descricao AS Descricao, produtos.Preco, produtos.EmStock AS QuantidadeEmStock, produtos.Img, produtos.ID, carrinho.ID, produtos.Tipo_ID, tipo_produtos.ID, carrinho.ID 
		FROM itenscarrinho, tipo_produtos, produtos, carrinho 
		WHERE itenscarrinho.Produto_ID = produtos.ID AND itenscarrinho.Carrinho_ID = carrinho.ID AND produtos.Tipo_ID = tipo_produtos.ID;', PDO::FETCH_ASSOC)) != false)
		{
			// Colocar os dados obtidos na variável $registos, em formato array associativo
			$registos = $stmt->fetchAll();
		}
		else
		{
			echo "Erro ao selecionar os dados";
		}
		
		// Terminar a ligação
		$db = null;

		// Retornar os dados
		return $registos;
	}

	/*
		Cria um novo registo para um utilizador.
	   
		Retorna:
			True se a operação foi executada com sucesso
			False caso contrário
	*/
	function InserirCarrinho($DadosItensCarrinho)
	{
		// Inicializar uma variável que sinaliza o sucesso ou insucesso da operação
		$sucesso = False;

		try
		{
			$db = new PDO('mysql:host=' . DB_SERVER . ';dbname=' . DB_NAME . ';charset=utf8', DB_USER, DB_PASSWORD);
			
			$stmt = $db->prepare('INSERT INTO utilizadores (Nome, Apelido, Email, `Password`, Telemovel, NIF, EstadoConta) VALUES (?, ?, ?, Password(?), ?, ?, ?)');
			$stmt->bindValue(1, $DadosUtilizadores['Nome'], PDO::PARAM_STR);
			$stmt->bindValue(2, $DadosUtilizadores['Apelido'], PDO::PARAM_STR);
			$stmt->bindValue(3, $DadosUtilizadores['Email'], PDO::PARAM_STR);
			$stmt->bindValue(4, $DadosUtilizadores['Password'], PDO::PARAM_STR);
			$stmt->bindValue(5, (int) $DadosUtilizadores['Telemovel'], PDO::PARAM_INT);
			$stmt->bindValue(6, (int) $DadosUtilizadores['NIF'], PDO::PARAM_INT);
			$stmt->bindValue(7, $DadosUtilizadores['EstadoConta'], PDO::PARAM_STR);
			
			// Executar a query e verificar que não retornou false
			if ($stmt->execute())
			{
				// A operação foi executada com sucesso
				$sucesso = True;
			}
		}
		catch (PDOException $e)
		{
			echo "Ocorreu o seguinte erro: " . $e->getMessage() . "<br/>";
			die();
		}

		// Terminar a ligação
		$db = null;

		return $sucesso;
	}	

	/*
		Editar o registo de utilizador.

		Parâmetros:
			$ID = a chave primária do registo que se pretende atualizar
			$DadosUtilizadores = os dados do utilizador a atualizar
			
		Retorna:
			True se a operação foi executada com sucesso
			False caso contrário
	*/
	function EditarCarrinho($ID, $DadosUtilizadores)
	{
		// Inicializar uma variável que sinaliza o sucesso ou insucesso da operação
		$sucesso = False;
		try
		{
			$db = new PDO('mysql:host=' . DB_SERVER . ';dbname=' . DB_NAME . ';charset=utf8', DB_USER, DB_PASSWORD);
			$stmt = $db->prepare('UPDATE utilizadores SET Nome = ?, Apelido = ?, Email = ?, Password = ?, Telemovel = ?, NIF = ?, EstadoConta= ? WHERE ID = ?');
			$stmt->bindValue(1, $DadosUtilizadores['Nome'], PDO::PARAM_STR);
			$stmt->bindValue(2, $DadosUtilizadores['Apelido'], PDO::PARAM_STR);
			$stmt->bindValue(3, $DadosUtilizadores['Email'], PDO::PARAM_INT);
			$stmt->bindValue(4, $DadosUtilizadores['Password'], PDO::PARAM_INT);
			$stmt->bindValue(5, (int) $DadosUtilizadores['Telemovel'], PDO::PARAM_INT);
			$stmt->bindValue(6, (int) $DadosUtilizadores['NIF'], PDO::PARAM_INT);
			$stmt->bindValue(7, $DadosUtilizadores['EstadoConta'], PDO::PARAM_STR);
			$stmt->bindValue(8, $ID, PDO::PARAM_INT);
			
			// Executar a query e verificar que não retornou false
			if ($stmt->execute())
			{
				// A operação foi executada com sucesso
				$sucesso = True;
			}

		}
		catch (PDOException $e)
		{
			echo "Ocorreu o seguinte erro: " . $e->getMessage() . "<br/>";
			die();
		}

        
		
		// Terminar a ligação
		$db = null;

		return $sucesso;
	}	

	/*
		Elimina o registo de um utilizador.
	   
		Parâmetros:
			$ID = a chave primária do registo que se pretende eliminar
			
		Retorna:
			True se a operação foi executada com sucesso
			False caso contrário
	*/
	function EliminarCarrinho($ID)
	{
		// Inicializar uma variável que sinaliza o sucesso ou insucesso da operação
		$sucesso = False;

		try
		{
			$db = new PDO('mysql:host=' . DB_SERVER . ';dbname=' . DB_NAME . ';charset=utf8', DB_USER, DB_PASSWORD);
		}
		catch (PDOException $e)
		{
			echo "Ocorreu o seguinte erro: " . $e->getMessage() . "<br/>";
			die();
		}

        $stmt = $db->prepare('DELETE FROM utilizadores WHERE ID = ?');
		$stmt->bindValue(1, $ID, PDO::PARAM_INT);
		
		// Executar a query e verificar que não retornou false
		if ($stmt->execute())
		{
			// A operação foi executada com sucesso
			$sucesso = True;
		}
		
		// Terminar a ligação
		$db = null;

		return $sucesso;
	}
?>