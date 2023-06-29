<?php
session_start();
require_once 'Registration.php';

if(isset($_SESSION['id']))
{ 
    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        // Verificar se o ID do evento foi enviado
        if (isset($_POST["event_id"])) {
            $userId = $_SESSION['id'];
            $eventId = $_POST["event_id"];

            Registration::removeRegistration($userId, $eventId);
        }
    }
    header("Location: event_details.php?event_id=$eventId");
    exit();
} else
{
  echo "<h2>Você deve estar logado para acessar essa página.<h2>";                    
  echo "<br><br>
  <form id ='voltar' method='POST' action='user_login.php'>
      <input type='submit' class='button' value='Acesse sua conta'>
  </form>";

}
?>
