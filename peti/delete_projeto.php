<?php
include 'conexao.php';

if (isset($_GET['id'])) {
    $id = (int)$_GET['id'];
    $sql = "DELETE FROM projetos WHERE id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    if ($stmt->execute()) {
        header("Location: cadastro_projeto.php?sucesso=dados_salvos");
    } else {
        header("Location: cadastro_projeto.php?erro=banco_dados");
    }
}

$conn->close();
?>