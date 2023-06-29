<?php
    class User {
        private $id;
        private $name;
        private $email;
        private $password;
        private $userType;

        public function __construct($name, $email, $password, $userType) {
            $this->name = $name;
            $this->email = $email;
            $this->password = $password;
            $this->userType = $userType;
        }

        public function getId() {
            return $this->id;
        }

        public function getName() {
            return $this->name;
        }

        public function getEmail() {
            return $this->email;
        }

        public function getPassword() {
            return $this->password;
        }

        public function getUserType() {
            return $this->userType;
        }

        public function setId($id) {
            $this->id = $id;
        }

        public function setName($name) {
            $this->name = $name;
        }

        public function setEmail($email) {
            $this->email = $email;
        }

        public function setPassword($password) {
            $this->password = $password;
        }

        public function setUserType($userType) {
            $this->userType = $userType;
        }

        public static function checkEmailExists($email) {
            include 'Connect.inc.php';
        
            $sql = "SELECT COUNT(*) FROM users WHERE email = ?";
        
            $stmt = $conn->prepare($sql);
        
            if (!$stmt) {
                die("Erro na preparação da declaração: " . $conn->error);
            }
        
            $stmt->bind_param("s", $email);
            $stmt->execute();
        
            $stmt->bind_result($count);
            $stmt->fetch();
        
            $stmt->close();
            $conn->close();
        
            return $count > 0;
        }
        public function updateUser() {
            include 'Connect.inc.php';
        
            if ($email !== $this->email && $this->checkEmailExists($email)) {
                echo "Erro: O email já está sendo utilizado por outro usuário.";
                return;
            }
        
            if(is_null($this->password) || (trim($this->password) === '')){     
                echo "é null";           
                $sql = "UPDATE users SET name = ?, email = ? WHERE id = ?";
        
                $stmt = $conn->prepare($sql);
            
                if (!$stmt) {
                    die("Erro na preparação da declaração: " . $conn->error);
                }
            
                $stmt->bind_param("ssi", $this->name, $this->email, $this->id);
            } else {   
                echo "não é null";     
                $sql = "UPDATE users SET name = ?, email = ?, password = ? WHERE id = ?";
        
                $stmt = $conn->prepare($sql);
            
                if (!$stmt) {
                    die("Erro na preparação da declaração: " . $conn->error);
                }
            
                $stmt->bind_param("sssi", $this->name, $this->email, $this->password, $this->id);
            }
        
            if ($stmt->execute()) {
                echo "Usuário atualizado com sucesso.";
                $_SESSION['nome'] = $this->name;
                $_SESSION['email'] = $this->email;
        
                if (!is_null($password)) {
                    header('Location: index.php');
                    exit;
                }
            } else {
                echo "Erro ao atualizar o usuário: " . $stmt->error;
            }
        
            $stmt->close();
            $conn->close();
        }

        public function updateUserType($userId, $userType) {
        
            if ($_SESSION['user_type'] !== 'administrator') {
                echo "Erro: Apenas administradores podem alterar o tipo de usuário.";
                return;
            }
        
            include 'Connect.inc.php';
        
            $sql = "UPDATE users SET user_type = ? WHERE id = ?";
        
            $stmt = $conn->prepare($sql);
        
            if (!$stmt) {
                die("Erro na preparação da declaração: " . $conn->error);
            }
        
            $stmt->bind_param("si", $userType, $userId);
        
            if ($stmt->execute()) {
                echo "Tipo de usuário atualizado com sucesso.";
            } else {
                echo "Erro ao atualizar o tipo de usuário: " . $stmt->error;
            }
        
            $stmt->close();
            $conn->close();
        }

        
        public static function getAllUsers() {
            include 'Connect.inc.php';
        
            $loggedInUserId = $_SESSION['id']; 
        
            $sql = "SELECT id, name, email, user_type FROM users WHERE id != ?";
            $stmt = $conn->prepare($sql);
        
            if (!$stmt) {
                die("Erro na preparação da declaração: " . $conn->error);
            }
        
            $stmt->bind_param("i", $loggedInUserId);
            $stmt->execute();
            $result = $stmt->get_result();
        
            if ($result->num_rows > 0) {
                echo "<div style='display: flex; flex-wrap: wrap;'>";
        
                while ($row = $result->fetch_assoc()) {
                    $userId = $row['id'];
                    $name = $row['name'];
                    $email = $row['email'];
                    $userType = $row['user_type'];
        
                    echo "<div style='width: 31%; border: 1px solid black; padding: 10px;'>";
                    echo "Nome: $name<br>";
                    echo "Email: $email<br>";
                    echo "Tipo de Usuário: $userType<br>";
                    echo "<a href='delete_user.php?id=$userId' onclick='return confirmDelete()'>Excluir</a>";
                    echo "</div>";
                }
        
                echo "</div>";
            } else {
                echo "Nenhum usuário encontrado.";
            }
        
            $stmt->close();
            $conn->close();
        }
        
    }
?>
