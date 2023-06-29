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
        <link href="CSS/style.css" rel="stylesheet">
	</head>

	<body>
        <div class="barra-superior" id="barra-superior">
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
                        if(isset($_SESSION['id']) && $_SESSION['user_type'] == 'administrator')
                        {
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
                </ul>
            </div>
        </div>
        <div class="conteudo" id="conteudo">
            <div>
                <h1>Acesse sua conta</h1>
                <?php
                    require_once 'User.php';
                    
                    if(isset($_POST['email']) && isset($_POST['password']))
                    {
                        $email = $_POST['email'];
                        $password = $_POST['password'];
                        $name = '';
                        $user_type = '';
                        $user = new User($name, $email, $password, $user_type);
                        $auth = new Authentication($user);
                        $resultado = $auth->login();
                        if ($resultado === true) {
                            header("Location: index.php");
                            exit();
                        } else {
                            echo $resultado;
                        }
                    }
                ?>
                <form class="formulário" action="" method="POST">
                    <p>
                        <label>E-mail</label>
                        <input type="text" name="email">
                    </p>
                    <p>
                        <label>Senha</label>
                        <input type="password" name="password">
                    </p>
                    <p>
                        <input type="submit" class="button" value="Entrar">
                    </p>
                </form>
            </div>
        </div>
    </body>
</html>
