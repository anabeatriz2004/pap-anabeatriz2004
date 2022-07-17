<header class="p-3 bg-dark text-white castanho-escuro">
    <div class="container">
      <div class="d-flex flex-wrap align-items-center justify-content-center justify-content-lg-start">
        <a class="navbar-brand" href="index.php">
            <img src="img/marca-trasparente-cor-branca.png" class="d-block mx-lg-auto img-fluid" width="40" height="32" alt="Logotipo" loading="lazy">
        </a>

        <ul class="nav col-12 col-lg-auto me-lg-auto mb-2 justify-content-center mb-md-0">
            <li class="nav-item dropdown">

            <!-- Carrinho -->
            <?php
            // caso a sessão tenha sido iniciada
            // mostrar o botão de terminar sessão
            if (isset($_SESSION["Email"]) ) { 
            ?>
                <li class="nav-item">
                    <a class="nav-link branco-letra" href="carrinho.php">Carrinho</a>
                </li>
            <?php
            }
            ?>


            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle branco-letra" href="#" id="navbarDropdownMenuLink" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                Produtos
                </a>
                <ul class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                    
                    <!-- Criar produto -->
                    <?php
                        // caso a a sessão tenha sido inicidada e se for admin
                        // mostrar o botão de criar produto
                        if ( isset($_SESSION["Tipo_ID"]) && $_SESSION["Tipo_ID"]==0 ) {
                    ?>
                            <li class="nav-item">
                                <a class="dropdown-item castanho-escuro-link" href="criar-produto.php">Criar Produto</a>
                            </li>
                            <hr>
                    <?php
                        }
                    ?>

                    <li class="nav-item">
                        <a class="dropdown-item castanho-escuro-link" href="todos_Produtos.php">Todos os Produtos</a>
                    </li>
                    
                    <?php 
                        $tipos = null;
                        
                        // Obter os tipos de produtos
                        $tipos = getTiposProdutos();

                        if ($tipos !== null)
                        {
                            foreach ($tipos as $tipo)
                            {
                                echo "<li><a class=\"dropdown-item castanho-escuro-link\" href=\"tipo_produto.php?tipo={$tipo['ID']}\">{$tipo['Designacao']}</a></li>";
                            }
                        }
                    ?>
                </ul>
            </li>
        </ul>

        <div class="text-end">
        <!--Entrar/Registo-->
        <?php
            // caso a sessão não tenha sido iniciada 
            // mostrar o botão de criar conta ou iniciar sessão
            if (! isset($_SESSION["Email"]) ) { 
        ?>

            <button onclick="window.location.href='/pap-anabeatriz2004/entrar.php'" class="btn"
                style="background-color: #964b00; color: white;">
                    Entrar
            </button>

            <button onclick="window.location.href='/pap-anabeatriz2004/registrar.php'" class="btn"
                style="background-color: #964b00; color: white;">
                    Criar Conta
            </button>

        <?php
            }
        ?>

        <!-- Editar Utilizadores & Terminar Sessão-->
        <?php
            // caso a a sessão tenha sido inicidada
            // mostrar o botão de terminar sessão
            if (isset($_SESSION["Email"]) ) { 
        ?>
            <?php echo $_SESSION["Email"]; ?>

            &ensp;

            <button onclick="window.location.href='/pap-anabeatriz2004/editar.php'" class="btn" 
                style="background-color: #964b00; color: white;">
                    Editar
            </button>

            <button onclick="window.location.href='/pap-anabeatriz2004/logout.php'" class="btn"
                style="background-color: #964b00; color: white;">
                    Log-out
            </button>

        <?php
            }
        ?>

        </div>
      </div>
    </div>
  </header>

  <!-- The Modal -->
  <div id="erro" class="modal1">
        <div class="modal-content1">
            <div class="modal-header1">
                <span class="close1">&times;</span>
                <h2>Mensagem:</h2>
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
            <!--<div class="modal-footer1">
                <h3>Modal Footer</h3>
            </div>-->
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
            modal.style.display = "none";
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