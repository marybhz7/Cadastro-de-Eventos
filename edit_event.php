
<?php
session_start();
if (isset($_GET['event_id'])) {
    $eventId = $_GET['event_id'];

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        require_once 'Authentication.php';
        if (isset($_POST['logout'])) {
            Authentication::logout();
        }
        require_once 'Event.php';
        include 'Connect.inc.php';

        $event = Event::getEventById($eventId);

        if ($event) {
            $event->setTitle($_POST['title']);
            $event->setDescription($_POST['description']);
            $event->setDate($_POST['date']);
            $event->setTime($_POST['time']);
            $event->setLocation($_POST['location']);
            $event->setCategoryId($_POST['category_id']);
            $event->setPrice($_POST['price']);

            if ($event->saveEvent()) {
                echo "Evento atualizado com sucesso!";                
                header("Location: event_details.php?event_id=$eventId");
            } else {
                echo "Ocorreu um erro ao atualizar o evento.";
                echo "<a href='index.php' class ='button'>Início</a>";
            }
        } else {
            echo "Evento não encontrado.";
            echo "<a href='index.php' class ='button'>Início</a>";
        }
    } else {
        require_once 'Event.php';
        include 'Connect.inc.php';

        $event = Event::getEventById($eventId);

        if ($event) {
            ?>
            <!DOCTYPE html>
            <html>
            <head>
                <title>Editar Evento</title>
                
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
                <h1>Editar Evento</h1>
                <form class='formulário' method="POST" action="">
                    <input type="hidden" value="<?php echo $event->getUserId(); ?>">
                    <label for="title"><b>Título</b></label><br>
                    <input type="text" name="title" value="<?php echo $event->getTitle(); ?>"><br>

                    <label for="description"><b>Descrição</b></label><br>
                    <textarea name="description" class = "insere-texto"><?php echo $event->getDescription(); ?></textarea><br>

                    <label for="date"><b>Data</b></label><br>
                    <input type="text" name="date" value="<?php echo $event->getDate(); ?>"><br>

                    <label for="time"><b>Hora</b></label><br>
                    <input type="text" name="time" value="<?php echo $event->getTime(); ?>"><br>

                    <label for="location"><b>Local</b></label><br>
                    <input type="text" name="location" value="<?php echo $event->getLocation(); ?>"><br>

                    <label for="category_id"><b>Categoria</b></label><br>
                    <select name="category_id">
                    <?php
                        require_once 'Category.php';

                        $categories = Category::getAllCategories();

                        foreach ($categories as $category) {
                        echo "<option value=\"" . $category->getId() . "\">" . $category->getName() . "</option>";
                        }
                        ?>
                    </select><br>

                    <label for="price"><b>Preço</b></label><br>
                    <input type="text" name="price" value="<?php echo $event->getPrice(); ?>"><br>

                    <input type="submit" class="button" value="Salvar">                    
                    <a href="<?php echo 'event_details.php?event_id='.$event->getId(); ?>" class="button">Voltar</a>
                </form>
                </div>
            </body>
            </html>
            <?php
        } else {
            echo "Evento não encontrado.";
        }
    }
} else {
    echo "ID do evento não fornecido.";
}
?>
