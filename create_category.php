<?php
  if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['create_category'])) {
    include 'Connect.inc.php';

    $name = $_POST['name'];

    $sql = "INSERT INTO categories (name) VALUES (?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $name);

    if ($stmt->execute()) {
      echo "Categoria cadastrada com sucesso!";
    } else {
      echo "Erro ao cadastrar a categoria.";
    }

    $stmt->close();
    $conn->close();
  }
?>

<h1  style ='border-top: 1px solid #000000;'>Cadastro de Categoria</h1>

  <form class="formulário" method="POST" action="">
    <input type="hidden" name ="create_category" value="1">
    <label for="name">Nome da Categoria:</label>
    <input type="text" name="name" required><br>

    <input type="submit" class="button" value="Cadastrar">
  </form>