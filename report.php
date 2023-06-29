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

include 'Connect.inc.php';

function getEventCount() {
    global $conn;
    $sql = "SELECT COUNT(*) AS event_count FROM events";
    $result = $conn->query($sql);
    $row = $result->fetch_assoc();
    return $row['event_count'];
}

function getOrganizerCount() {
    global $conn;
    $sql = "SELECT COUNT(*) AS organizer_count FROM users WHERE user_type = 'organizer'";
    $result = $conn->query($sql);
    $row = $result->fetch_assoc();
    return $row['organizer_count'];
}

function getTopRatedEvents() {
    global $conn;
    $sql = "SELECT events.title, AVG(reviews.rating) AS average_rating
            FROM events
            INNER JOIN reviews ON events.id = reviews.event_id
            GROUP BY events.id
            ORDER BY average_rating DESC
            LIMIT 10";
    $result = $conn->query($sql);
    $events = array();
    while ($row = $result->fetch_assoc()) {
        $events[] = $row;
    }
    return $events;
}

function getUserCount() {
    global $conn;
    $sql = "SELECT COUNT(*) AS user_count FROM users";
    $result = $conn->query($sql);
    $row = $result->fetch_assoc();
    return $row['user_count'];
}

function getAllEvents() {
    global $conn;
    $sql = "SELECT * FROM events";
    $result = $conn->query($sql);
    $events = array();
    while ($row = $result->fetch_assoc()) {
        $events[] = $row;
    }
    return $events;
}

function getAllUsers() {
    global $conn;
    $sql = "SELECT * FROM users";
    $result = $conn->query($sql);
    $users = array();
    while ($row = $result->fetch_assoc()) {
        $users[] = $row;
    }
    return $users;
}


?>

<!DOCTYPE html>
<html>
<head>
    <title>Relatórios</title>
    
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
    <h1>Relatórios</h1>
    <?php
    if (isset($_GET['action'])) {
        $action = $_GET['action'];
        switch ($action) {
            case 'event_count':
                $count = getEventCount();
                echo "Quantidade de Eventos: " . $count;
                break;
            case 'organizer_count':
                $count = getOrganizerCount();
                echo "Quantidade de Organizadores: " . $count;
                break;
            case 'top_rated_events':
                $events = getTopRatedEvents();
                echo "Eventos Melhor Avaliados:<br>";
                foreach ($events as $event) {
                    echo $event['title'] . " - Avaliação: " . $event['average_rating'] . "<br>";
                }
                break;
            case 'user_count':
                $count = getUserCount();
                echo "Quantidade de Usuários: " . $count;
                break;
            case 'all_events':
                $events = getAllEvents();
                echo "Relatório de Todos os Eventos:<br>";
                foreach ($events as $event) {
                    echo "ID: " . $event['id'] . "<br>";
                    echo "Título: " . $event['title'] . "<br>";
                    echo "Descrição: " . $event['description'] . "<br>";
                    echo "Data: " . $event['date'] . "<br>";
                    echo "Horário: " . $event['time'] . "<br>";
                    echo "Localização: " . $event['location'] . "<br>";
                    echo "Preço: " . $event['price'] . "<br>";
                    echo "Imagem: " . $event['image'] . "<br><br>";
                }
                break;
            case 'all_users':
                $users = getAllUsers();
                echo "Relatório de Todos os Usuários:<br>";
                foreach ($users as $user) {
                    echo "ID: " . $user['id'] . "<br>";
                    echo "Nome: " . $user['name'] . "<br>";
                    echo "Email: " . $user['email'] . "<br>";
                    echo "Tipo de Usuário: " . $user['user_type'] . "<br><br>";
                }
                break;
            default:
                echo "Ação inválida.";
                break;
        }
    }
    ?>
    <ul>
        <li><a href="report.php?action=event_count">Quantidade de Eventos</a></li>
        <li><a href="report.php?action=organizer_count">Quantidade de Organizadores</a></li>
        <li><a href="report.php?action=top_rated_events">Eventos Melhor Avaliados</a></li>
        <li><a href="report.php?action=user_count">Quantidade de Usuários</a></li>
        <li><a href="report.php?action=all_events">Relatório de Todos os Eventos</a></li>
        <li><a href="report.php?action=all_users">Relatório de Todos os Usuários</a></li>
    </ul>
    </div>
</body>
</html>
