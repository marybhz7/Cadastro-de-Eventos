<?php
session_start();
require_once 'User.php';
require_once 'Authentication.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require_once 'Authentication.php';
    if (isset($_POST['logout'])) {
        Authentication::logout();
    }
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $userType = $_POST['user_type'];

    $user = new User($name, $email, $password, $userType);
    $auth = new Authentication($user);

    $auth->registerUser();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Registro de Usuário</title>
  <link rel="stylesheet" type="text/css" href="CSS/style.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
  <script src="JS/script.js"></script>

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
    <div class = "conteudo" id="conteudo">
    <h1>Registro de Usuário</h1>
        <form class='formulário' method="POST" action="user_registration.php" onsubmit="return verificarCamposObrigatorios();">
            <label for="name"><b>Nome</b></label><br>
            <input type="text" name="name" required><br><br>

            <label for="email"><b>E-mail</b></label><br>
            <input type="email" name="email" required><br><br>

            <label for="password"><b>Senha</b></label><br>
            <input type="password" name="password" required><br><br>
            <input type="hidden" name="user_type" value="participant">

            <input type="submit" class="button" value="Registrar">
        </form>
    </div>
</body>
</html>
