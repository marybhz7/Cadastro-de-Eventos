<?php
session_start();
if (isset($_GET['event_id'])) {
    $eventId = $_GET['event_id'];

    require_once 'Event.php';
    include 'Connect.inc.php';

    $event = Event::getEventById($eventId);

    if ($event) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            require_once 'Authentication.php';
            if (isset($_POST['logout'])) {
                Authentication::logout();
            }
            if ($event->deleteEvent()) {
                echo "Evento excluído com sucesso!";
                header("Location: index.php");
            } else {
                echo "Ocorreu um erro ao excluir o evento.";
            }

        } else {
            ?>
            <!DOCTYPE html>
            <html>
            <head>
                <title>Excluir Evento</title>
                
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
                <h1>Excluir Evento</h1>
                <p>Tem certeza de que deseja excluir o evento "<?php echo $event->getTitle(); ?>"?</p>
                <form class='formulário' method="POST" action="">
                    <input type="submit" class="button" value="Excluir">
                    <a href="index.php" class="button">Início</a>
                    <a href="event_list.php" class="button">Filtrar Eventos</a>
                </form>
            </div>
            </body>
            </html>
            <?php
        }
    } else {
        echo "Evento não encontrado.";
    }
} else {
    echo "ID do evento não fornecido.";
}
?>
