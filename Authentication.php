<?php

class Authentication {
  private User $user;


  public function __construct( $user) {
    $this->user = new User($user->getName(), $user->getEmail(), $user->getPassword(), $user->getUserType());
}


  public function login() {
    include('connect.inc.php');
    require_once 'User.php';

    $email = $this->user->getEmail();
    $password = $this->user->getPassword();

    if (empty($password) || empty($email)) {
        return "Preencha seus dados!";
    }

    $senha = $conn->real_escape_string($password);
    $email = $conn->real_escape_string($email);
    $sql = "SELECT * FROM users WHERE password = '$senha' AND email = '$email'";            
    $result = $conn->query($sql) or die("Falha na execução do código SQL: " . $conn->error);

    $quantidade = $result->num_rows;
    if ($quantidade == 1) {
        $usuario = $result->fetch_assoc();

        if (!isset($_SESSION)) {
            session_start();
        }

        $_SESSION['id'] = $usuario['id'];                
        $_SESSION['nome'] = $usuario['name'];                
        $_SESSION['email'] = $usuario['email'];
        $_SESSION['user_type'] = $usuario['user_type'];

        $conn->close();

        header("Location: index.php");
        exit();
    } else {
        $conn->close();
        return "E-mail ou Senha incorretos.";
    }
  }
  
        
        
  public function registerUser() {
    include 'Connect.inc.php';
    require_once 'User.php';

    $name = $this->user->getName();
    $email = $this->user->getEmail();
    $senha= $this->user->getPassword();
    $userType = $this->user->getUserType();
    if (User::checkEmailExists($email)) {
        echo "Erro: O email já está sendo utilizado por outro usuário.";
        return;
    }

    $sql = "INSERT INTO users (name, email, password, user_type) VALUES (?, ?, ?, ?)";

    $stmt = $conn->prepare($sql);

    if (!$stmt) {
        die("Erro na preparação da declaração: " . $conn->error);
    }

    $stmt->bind_param("ssss", $name, $email, $senha, $userType);

    if ($stmt->execute()) {
        $id = $stmt->insert_id;
        echo "Usuário cadastrado com sucesso. ID: " . $id;
    } else {
        echo "Erro ao cadastrar o usuário: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
  }

  public static function logout() {
    if(!isset($_SESSION))
    {
        session_start();
    }
    session_destroy();
    header("Location: user_login.php");
  }
}

?>
