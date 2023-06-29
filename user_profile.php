<?php
session_start();

    require_once 'User.php';
    require_once 'Authentication.php';
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        
        if (isset($_POST['logout'])) {
            Authentication::logout();
        }
        if (isset($_POST['updateUser'])) {
            $name = $_POST['user_nome'];
            $email = $_POST['user_email'];
            $password = $_POST['pass'];

            if (!empty($name) && !empty($email)) {
                if (isset($_SESSION['id'])) {
                    $user = new User($name, $email, $password, $_SESSION['user_type']);

                    $user->setId($_SESSION['id']);
                    $user->updateUser();
                } else {
                    echo "Erro: Sessão não encontrada.";
                }
            } else {
                echo "Erro: Preencha todos os campos obrigatórios (Nome, E-mail).";
            }
            echo $_POST['user_senha'];
        }
    }
?>
<!DOCTYPE html>
<html>
<head>
  <title>Minha Conta</title>
  <link rel="stylesheet" type="text/css" href="CSS/style.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">

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
                    if (!isset($_SESSION['id'])) {
                        echo "<a href=\"user_login.php\">Acesse sua conta</a>";
                    } else {
                        echo "<a href=\"user_profile.php\">Minha Conta</a>";
                    }
                ?>
            </li>
            <li><a href="user_registration.php">Cadastrar Usuário</a></li>
            <?php
                if (isset($_SESSION['id']) && isset($_SESSION['user_type']) && $_SESSION['user_type'] === 'administrator') {
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
    <?php     
        if (isset($_SESSION['id'])) {
            echo "<h2 style='margin-top:30px;'>Bem-vindo ao Painel, ".$_SESSION['nome']."</h2>";
            echo "<form class='formulário' method='POST' action=''>
                        <input type='hidden' name='updateUser' value='1'>
                        <p>
                            <b>Nome</b> <br>
                            <input type='text' name='user_nome' value='".$_SESSION['nome']."'>
                        </p>
                        
                        <p>
                            <b>E-mail</b> <br>
                            <input type='text' name='user_email' value='".$_SESSION['email']."'>
                        </p>
                        
                        <p>
                            <b>Senha</b><br> 
                            <input type='password' name='pass'>
                        </p>            

                        <p>
                            <input type='submit' class='button' value='Atualizar'>
                        </p>
                    </form>";            
            echo "<br><br>
                <form id='voltar' method='POST' action=''>
                    <input type='hidden' name='logout' value='1'>
                    <input type='submit' class='button' value='Sair'>
                </form>";
        } else {
            echo "<h2>Você deve estar logado para acessar essa página.</h2>";                    
            echo "<br><br>
                <form id='voltar' method='POST' action='login.php'>
                    <input type='submit' class='button' value='Acesse sua conta'>
                </form>";
        }
    ?>          
</div>
</body>
</html>
