<?php
    session_start();
    require_once 'Authentication.php';
    if (isset($_POST['logout'])) {
        Authentication::logout();
    }

    include 'Connect.inc.php';
    include 'User.php';

    if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] !== 'administrator') {
        echo "Acesso negado. Você precisa ser um administrador para visualizar esta página.";
        exit;
    }        

    $sql = "SELECT id, name FROM users where id !=". $_SESSION['id'];
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $users = $result->fetch_all(MYSQLI_ASSOC);
    } else {
        $users = [];
    }

    $conn->close();
    

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['user_type'])) {
        if (isset($_POST['user_id']) && isset($_POST['user_type'])) {
            $user = new User('', '', '', '');

            $user->updateUserType($_POST['user_id'], $_POST['user_type']);
        } else {
            echo "Erro: Campos inválidos.";
        }
        }
    ?>

<!DOCTYPE html>
<html>
<head>
    <title>Alterar Tipo de Usuário</title>
    
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
    <h2  style ='border-top: 1px solid #000000;'>Relatórios</h1>
    <a href='report.php'>Relatórios</a></li>
    <h2  style ='border-top: 1px solid #000000;'>Participantes de Eventos</h1>
    <a href='participants.php'>Participantes</a></li>
    <h2>Alterar Tipo de Usuário</h1>
    <form method="POST" action="">
        <label for="user_id">Selecione o Usuário:</label>
        <input type="hidden" value = "1">
        <select name="user_id" id="user_id" required>
            <?php foreach ($users as $user): ?>
                <option value="<?php echo $user['id']; ?>"><?php echo $user['id'] . ' - ' . $user['name']; ?></option>
            <?php endforeach; ?>
        </select>

        <label for="user_type">Novo Tipo de Usuário:</label>
        <select name="user_type" name = "user_type" id="user_type" required>
            <option value="organizer">Organizador</option>
            <option value="participant">Participante</option>
            <option value="administrator">Administrador</option>
        </select>

        <input type="submit" value="Alterar">
    </form>
    <?php 
        include 'create_category.php';
        echo "<h2 style ='border-top: 1px solid #000000;'> Excluir Usuários </h2>";
        User::getAllUsers();
    ?>
  </div>
</body>
</html>
