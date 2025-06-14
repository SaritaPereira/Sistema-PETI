<?php
include 'conexao.php';

if (isset($_GET['id'])) {
    $id = (int)$_GET['id'];
    $projeto_id = (int)$_GET['projeto_id'];
    $sql = "DELETE FROM objetivos WHERE id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    if ($stmt->execute()) {
        header("Location: cadastro_objetivo.php?projeto_id=$projeto_id&sucesso=dados_salvos");
    } else {
        header("Location: cadastro_objetivo.php?projeto_id=$projeto_id&erro=banco_dados");
    }
}

$conn->close();
?>