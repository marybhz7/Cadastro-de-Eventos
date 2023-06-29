<?php
session_start();
    require_once 'Authentication.php';
    if (isset($_POST['logout'])) {
        Authentication::logout();
    }
?>
<html>

	<head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8"> 
        <title>Login</title>
        
  <link rel="stylesheet" type="text/css" href="CSS/style.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">

	</head>

	<body>
    <div class = "barra-superior" id="barra-superior">
            <div class="menu">
                <h2>Events</h2>
                <ul>
                    <li><a href="index.php">Início</a></li>
                    <li><a href="create_event.php">Criar Evento</a></li>
                    <li>
                        <?php
                            if(!isset($_SESSION['id']))
                            {
                                echo "<a href=\"user_login.php\">Acesse sua conta</a>";
                            }
                            else
                            {
                                echo "<a href=\"user_profile.php\">Minha Conta</a>"; 
                            }
                        ?>
                    </li>
                    <li><a href="user_registration.php">Cadastrar Usuário</a></li>
                    <?php
                        if (isset($_SESSION['id']) && $_SESSION['user_type'] === 'administrator') {
                            echo "<li><a href='admin_dashboard.php'>Painel do Administrador</a></li>";
                        }
                    ?>
                    <li><a href="event_list.php">Filtrar Eventos</a></li>
            <li>
                <?php
                    if (isset($_SESSION['id'])){
                        echo "
                        <form id='voltar' method='POST' action=''>
                            <input type='hidden' name='logout' value='1'>
                            <button type='submit' class='logout-button'>
                                <i class='fas fa-sign-out-alt'></i>
                                Sair
                            </button>
                        </form>";

                    }
                ?>
            </li>
            <li>
                <?php
                    if (isset($_SESSION['id'])){
                        echo "
                        <form id='voltar' method='POST' action=''>
                            <input type='hidden' name='logout' value='1'>
                            <button type='submit' class='logout-button'>
                                <i class='fas fa-sign-out-alt'></i>
                                Sair
                            </button>
                        </form>";

                    }
                ?>
            </li>
                </ul>
            </div>
        </div>
    <div class = "conteudo" id="conteudo">



	<div>
        
        <h1>Acesse sua conta</h1>
        <?php
            include('connect.inc.php');
            if(isset($_POST['email']) || isset($_POST['senha']))
            {
                if(strlen($_POST['senha']) == 0 || strlen($_POST['email']) == 0 )
                {
                    echo "Preencha seus dados!";
                }
                else
                {
                    $senha = $conn->real_escape_string($_POST['senha']);
                    $email = $conn->real_escape_string($_POST['email']);
                    $sql = "SELECT * FROM users WHERE password = '$senha' AND email = '$email'";            
                    $result = $conn->query($sql) or die("Falha na execução do código SQL:" .$mysqli->error);

                    $quantidade = $result->num_rows;
                    if($quantidade == 1)
                    {
                        $usuario = $result->fetch_assoc();

                        if(!isset($_SESSION))
                        {
                            session_start();
                        }

                        $_SESSION['id'] = $usuario['id'];                
                        $_SESSION['nome'] = $usuario['name'];                
                        $_SESSION['email'] = $usuario['email'];
                        $_SESSION['user_type'] = $usuario['user_type'];
                        header("Location: index.php");
                    }
                    else
                    {
                        echo"E-mail ou Senha incorretos.";
                    }
                }
            }
        ?>
        <form class='formulário' action="" method="POST">
            <p>
                <label>E-mail</label>
                <input type="text" name="email">
            </p>
            <p>
                <label>Senha</label>
                <input type="password" name="senha">
            </p>
            <p>
            <input type="submit" class="button" value= "Entrar"></button>
            </p>
        </form>
    
        
    </div>
	</body>
	</html>
</html>