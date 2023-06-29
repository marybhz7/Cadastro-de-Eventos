<?php
session_start();
require_once 'Event.php';
require_once 'Registration.php';
if(isset($_SESSION['id']))
{ 
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (isset($_POST['event_id'])) {
            $eventId = $_POST['event_id'];
            $event = Event::getEventById($eventId);

            if ($event) {
                // Lógica de inscrição
                $userId = $_SESSION['id']; // Supondo que você tenha um sistema de autenticação e obtenha o ID do usuário atualmente logado
                $paymentStatus = 'pending'; // Supondo que você tenha um sistema de pagamento e defina o status inicial como 'pending'

                $registration = new Registration($userId, $eventId, $paymentStatus);
                $registration->saveRegistration();

                // Verificação se a inscrição foi salva com sucesso
                if ($registration->getId()) {
                    echo "Registration successful!";                
                    header("Location: event_details.php?event_id=$eventId");
                    
                    // Redirecionar o usuário para uma página de confirmação ou qualquer outra ação necessária
                } else {
                    echo "Failed to register.";
                }
            } else {
                echo "Event not found.";
            }
        } else {
            echo "Invalid request.";
        }
    } else {
        echo "Invalid request.";
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
