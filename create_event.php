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
  <title>Cadastro de Evento</title>
  
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
  <h1>Cadastro de Evento</h1>

  <?php
    require_once 'Event.php';
    include 'Connect.inc.php';
    require_once 'Category.php';


    if(isset($_SESSION['id']))
    {     
      if($_SESSION['user_type'] == 'organizer' || $_SESSION['user_type'] == 'administrator')
      {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
          if(isset($_FILES['image']) && $_FILES['image']['name'] != '' && $_FILES['image']['type'] != '')
          {
            var_dump($_FILES['image']);
              $extensao = strtolower(substr($_FILES['image']['name'], -4));
              if($extensao == "jpeg")
              {
                  $extensao = ".jpeg";
              }
              $arquivo = md5(time()).$extensao;
              $diretorio = "img/";
              move_uploaded_file($_FILES['image']['tmp_name'], $diretorio.$arquivo);
          }
          else {
            $arquivo = 'default.jpg';
          }

          $event = new Event($_SESSION['id'], $_POST['title'], $_POST['description'], $_POST['date'], $_POST['time'], $_POST['location'], $_POST['category_id'], $_POST['price'], $arquivo);

          if ($event->insertEvent()) {
            echo "Evento cadastrado com sucesso!";
          } else {
            echo "Erro ao cadastrar o evento.";
          }
        }

        echo "<form class='formulário' method='POST' action='' enctype='multipart/form-data' onsubmit='return verificarCamposObrigatorios();'>
          <p><b>Nome do Evento <br></b>
            <input type='text' name='title' required>
          </p>

          <p><b>Descrição<br></b>
            <textarea class = 'insere-texto' name='description' required></textarea>
          </p>

          <p><b>Data e Hora <br></b>
            <input type='date' name='date' required>
            <input type='time' name='time' required><br>
          </p>
          
          <p><b>Local<br></b>
            <input type='text' name='location' required>
          </p>

          <p><b>Categoria <br></b>
            <select name='category_id' required>";

            $categories = Category::getAllCategories();

            foreach ($categories as $category) {
              echo "<option value=\"" . $category->getId() . "\">" . $category->getName() . "</option>";
            }
        echo "</select> </p>

        <p><b>Preço <br></b>
          <input type='number' name='price' step='0.01' required><br>
        </p>

      
        <p><b>Imagem <br></b>
        <label class='custom-file-upload'> 
            Upload
            <input type='file' name='image' id = 'image'>
        </label>
        </p>

        <input type='submit' class='button' value='Cadastrar'>
      </form>";

      } else {
        echo "<h2>Apenas organizadores e administradores podem criar eventos, caso seja um organizador, contate o administrador.</h2>";
      }
        
    }
    else
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
