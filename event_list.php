<?php
session_start();
    require_once 'Authentication.php';
    if (isset($_POST['logout'])) {
        Authentication::logout();
    }
?>
<!DOCTYPE html>
<html>
<head>
  <title>Filtrar Eventos</title>
  
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
    <h1>Filtrar Eventos</h1>
    <?php
    require_once 'Event.php';
    include 'Connect.inc.php';


    $categories = array();
    $categoryQuery = "SELECT DISTINCT category_id FROM events";
    $categoryResult = $conn->query($categoryQuery);

    while ($row = $categoryResult->fetch_assoc()) {
      $categories[] = $row['category_id'];
    }

    $search = '';
    $category = '';

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
      if (!empty($_POST['search'])) {
        $search = $_POST['search'];
      }

      if (!empty($_POST['category'])) {
        $category = $_POST['category'];
      }
    }

    if ($search == '' && $category == '') {
      $events = Event::getAllEvents();
    } elseif ($search != '' && $category == '') {
      $events = Event::getEventsBySearch($search);
    } elseif ($search == '' && $category != '') {
      $events = Event::getEventsByCategory($category);
    } else {
      $events = Event::getFilteredEvents($search, $category);
    }
    ?>
    <form class='formulário' method="POST" action="">
      <p>
        <b>Nome do Evento</b><br>
        <input type="text" name="search" value="<?php echo $search; ?>">
      </p>

      <p>
        <b>Categoria</b><br>
        <select name="category">
          <option value="">Todas as categorias</option>
          <?php
          foreach ($categories as $cat) {
            $selected = ($cat === $category) ? 'selected' : '';

            $categoryName = Event::getCategoryName($cat);

            echo "<option value='$cat' $selected>$categoryName</option>";
          }
          ?>
        </select>
      </p>

      <input type="submit" class="button" value="Filtrar">

    </form>

    <?php
      if (!empty($events)) {
        echo "<div style=' margin-top: 1vh; display: flex; flex-wrap: wrap;'>";

        foreach ($events as $event) {
          $categoryId = $event->getCategoryId();
          echo "<div style='width: 31%; border: 1px solid black; padding: 10px;'>";
          echo "<h2><a href='event_details.php?event_id=" . $event->getId() . "'>" . $event->getTitle(). "</a></h2>";
          echo "<p>Categoria: " . Event::getCategoryName($event->getCategoryId()) . "</p>";
          echo "<p>Data: " . $event->getDate() . "</p>";
          echo "<p>Hora: " . $event->getTime() . "</p>"; 
          echo "<hr>";
          echo "</div>";
        }
      } else {
        echo "<p>Nenhum evento encontrado.</p>";
      }
      echo "</div>";

    ?>
      </div>
</body>
</html>
