<?php
if (isset($_GET['id'])) {
    $userId = $_GET['id'];
    
    include 'Connect.inc.php';

    $sql = "SELECT id FROM users WHERE id = ?";
    $stmt = $conn->prepare($sql);

    if (!$stmt) {
        die("Erro na preparação da declaração: " . $conn->error);
    }

    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $deleteSql = "DELETE FROM users WHERE id = ?";
        $deleteStmt = $conn->prepare($deleteSql);

        if (!$deleteStmt) {
            die("Erro na preparação da declaração de exclusão: " . $conn->error);
        }

        $deleteStmt->bind_param("i", $userId);
        $deleteStmt->execute();

        if ($deleteStmt->affected_rows > 0) {
            echo "Usuário excluído com sucesso.";
            header("Location: admin_dashboard.php");
        } else {
            echo "Erro ao excluir o usuário.";
        }

        $deleteStmt->close();
    } else {
        echo "Usuário não encontrado.";
    }

    $stmt->close();
    $conn->close();
} else {
    echo "ID de usuário não fornecido.";
}
?>
