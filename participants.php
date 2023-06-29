<?php
        session_start();
    require_once 'Authentication.php';
    if (isset($_POST['logout'])) {
        Authentication::logout();
    }

    if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] !== 'administrator') {
        echo "Acesso negado. Você precisa ser um administrador para visualizar esta página.";
        exit;
    }   
    ?>
<!DOCTYPE html>
<html>
<head>
    <title>Lista de Participantes</title>
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
    <h1 style ='border-top: 1px solid #000000;'>Lista de Participantes</h1>

    <!-- Formulário para selecionar o evento -->
    <form method="post" action="">
        <label for="event">Selecione um evento:</label>
        <select name="event" id="event">
            <?php
            include 'Connect.inc.php';

            $query = "SELECT id, title FROM events";
            $result = $conn->query($query);

            while ($row = $result->fetch_assoc()) {
                echo '<option value="' . $row['id'] . '">' . $row['title'] . '</option>';
            }

            ?>
        </select>
        <button type="submit">Mostrar Participantes</button>
    </form>

    <?php
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $eventId = $_POST['event'];

        include 'Registration.php';

        $query = "SELECT registrations.id, users.name
                  FROM registrations
                  INNER JOIN users ON registrations.user_id = users.id
                  WHERE registrations.event_id = $eventId";
        $result = $conn->query($query);

        if ($result->num_rows > 0) {
            echo '<h2>Participantes do Evento</h2>';
            echo '<ul>';

            while ($row = $result->fetch_assoc()) {
                echo '<li>' . $row['name'] . '</li>';
            }

            echo '</ul>';
        } else {
            echo '<p>Nenhum participante encontrado para este evento.</p>';
        }

        $conn->close();
    }
    ?>
  </div>
</body>
</html>
