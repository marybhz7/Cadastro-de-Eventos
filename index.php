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
  <title>Events</title>
  
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
  <?php 

    if(isset($_SESSION['id']))
    {  
        include('connect.inc.php');
        $page  = (isset($_GET['page'])) ? (int)$_GET['page'] : 1;
        $quantidade = 9;
        $inicio   = ($quantidade*$page)-$quantidade;
        $sem_resultados = TRUE;
        
        $sql = "SELECT COUNT(*) AS Quantidade FROM events";
        $result = $conn->query($sql);
        $row = $result->fetch_assoc();
        $num_pages = ceil($row['Quantidade']/$quantidade);
        

        $sql = "SELECT e.id, e.title, e.description, e.date, e.time, e.location, c.name, e.price, e.image
                FROM events e
                JOIN categories c WHERE e.category_id = c.id
                ORDER BY e.id DESC LIMIT $inicio, $quantidade";
        $result = $conn->query($sql);
        

        if ($result->num_rows > 0) 
        {                    
            $sem_resultados = FALSE;
            
            while($row = $result->fetch_assoc()) 
            {            

                $titulo = $row['title'];
                $descricao = $row['description'];
                $data = $row['date'];
                $hora = $row['time'];
                $banner = "img/".$row['image'];
                if(strlen($descricao) > 50)
                {
                  $descricao = substr($descricao, 0, 50)."...";
                }
                $id = $row['id'];
                echo "<form class = 'formulário' id='form1' method='POST' action='event_details.php?event_id=$id'>
                        <div class='quadro-events'>
                        <input type='hidden' id='event' name='event' value=$id>
                            <h2>$titulo</h2>
                            <figure class = 'banner'>
                              <img src=$banner>
                            </figure>
                            <p class = 'descricao'>$descricao</p>
                            <p>Data:".date('d M Y', strtotime($data))."</p>
                            <p> Horário:". date('H:i', strtotime($hora))."</p>
                            <div class = 'botões'>
                              <input type='submit' class='button' value='Ver Mais'>
                            </div>
                        </div>
                      </form>";
            }
          

          echo "<div><ul class = 'paginação'>";
          for($i = 1; $i < $num_pages+1; $i++)
          { 
              echo "<li><a href='index.php?page=$i' style='text-decoration: none;'>$i</a></li>";
          }
          echo "</ul></div><br><br><br>";
          }
          if($sem_resultados)
          {
              echo "<h1>Sem Resultados.</h1>";
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
