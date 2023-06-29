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
  <title>Detalhes do Evento</title>
  
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
      <h1>Detalhes do Evento</h1>

      <?php
      require_once 'Event.php';
      include 'Connect.inc.php';
      require_once 'Registration.php';
      require_once 'Review.php';
      if(isset($_SESSION['id']))
      { 
        if (isset($_GET['event_id'])) {
          
          $eventId = $_GET['event_id'];        
          $userId = $_SESSION['id'];

          $event = Event::getEventById($eventId);
          
          $isRegistered = Registration::isUserRegistered($userId, $eventId);; 
          if ($isRegistered) {
            echo "<form action='unregister.php' method='POST'>
              <input type='hidden' name='event_id' value=".$event->getId().">
              <button class = 'button' type='submit'>Remove Registration</button>
            </form>";
          } else {
              echo "<form action='register.php' method='POST'>
              <input type='hidden' name='event_id' value=".$event->getId().">
              <button class = 'button' type='submit'>Register</button>
            </form>";
          }

          if ($event) {
            echo "<h2>" . $event->getTitle(). '('.$event->getId().')' . "</h2>";
            echo "<p>Descrição: " . $event->getDescription() . "</p>";
            echo "<p>Data: " . date('d M Y', strtotime($event->getDate())) . "</p>";
            echo "<p>Hora: " . date('H:i', strtotime($event->getTime())) . "</p>";
            echo "<p>Local: " . $event->getLocation() . "</p>";
            echo "<p>Categoria: " . Event::getCategoryName($event->getCategoryId()). '('.$event->getCategoryId().')' . "</p>";
            echo "<p>Preço: " . $event->getPrice() . "</p>";
            $banner = "img/".$event->getImage();
            echo "<p> <figure class = 'banner-detail'>
                        <img src=$banner>
                      </figure>";

            if($_SESSION['id'] == $event->getUserId()){ 
              echo '<div class="button-container">';             
              echo '<a href="edit_event.php?event_id=' . $eventId . '"class="button">Editar</a>';
              echo '<a href="delete_event.php?event_id=' . $eventId . '" onclick="return confirmDelete();"class="button">Excluir</a>';
              echo '</div>';
            }
            
        echo "<h1>Avaliação do Evento</h1>";
        if($isRegistered) {
          echo "<form class = 'comments' action='save_review.php' method='POST'>
              <input type='hidden' name='eventId' value=$eventId />
              <p><b>Classificação<b><br>
                  <select name='rating' id='rating'>
                      <option value='1'>1 - Ruim</option>
                      <option value='2'>2 - Regular</option>
                      <option value='3'>3 - Bom</option>
                      <option value='4'>4 - Muito Bom</option>
                      <option value='5'>5 - Excelente</option>
                  </select>
              </p>
              <br />
              <p><b>Comentário<b><br>
                  <textarea name='comment' id='comment' rows='4' cols='50'></textarea>
              </p>
              <input type='submit' class='button' value='Enviar Avaliação' />
          </form>";
        }
        
        $reviews = Review::getReviewsByEventId($eventId);
        echo '<div class="review">';
        foreach ($reviews as $review) {
            $userName = $review['name'];
            $rating = $review['rating'];
            $comment = $review['comment'];
            echo '<div class="review">';
            echo '<p><strong>Nome do usuário:</strong> ' . $userName . '</p>';
            echo '<p><strong>Classificação:</strong> ';
            for ($i = 1; $i <= $rating; $i++) {
              echo '★';
            }
            '</p>';
            echo '<p><strong>Comentário:</strong> ' . $comment . '</p>';
            if ($_SESSION['id'] == $review['user_id']) {
              echo '<form action="delete_review.php" method="POST" onsubmit="return confirmDelete();">';
              echo '<input type="hidden" name="review_id" value="' . $review['id'] . '" />';
              echo '<input type="hidden" name="user_id" value="' . $review['user_id'] . '" />';
              echo '<input type="hidden" name="event_id" value="' . $eventId . '" />';
              echo '<input type="submit" value="Excluir Comentário" />';
              echo '</form>';
            }
            echo '</div>';
        }
        echo '</div>';
          } else {
            echo "<p>Evento não encontrado.</p>";
          }
        } else {
          echo "<p>ID do evento não fornecido.</p>";
        }
      } else
      {
          echo "<h2>Você deve estar logado para acessar essa página.<h2>";                    
          echo "<br><br>
          <form id ='voltar' method='POST' action='user_login.php'>
              <input type='submit' class='button' value='Acesse sua conta'>
          </form>";
        
      }
      ?>
    </div>
</body>
</html>
