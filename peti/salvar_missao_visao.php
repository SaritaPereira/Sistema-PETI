<?php
include 'conexao.php';

$nome = $_POST['nome'];
$missao = $_POST['missao'];
$visao = $_POST['visao'];

// Verifica se já existe uma entrada
$sql_verifica = "SELECT * FROM organizacao LIMIT 1";
$result = $conn->query($sql_verifica);
$sql = null;
if ($result->num_rows > 0) {
    // Atualiza
    $sql = "UPDATE organizacao SET nome=?, missao=?, visao=? WHERE id=1";
} else {
    // Insere
    $sql = "INSERT INTO organizacao (nome, missao, visao) VALUES (?, ?, ?)";
}
$stmt = $conn->prepare($sql);
$stmt->bind_param("sss", $nome, $missao, $visao);

if ($stmt->execute()) {
    header("Location: cadastro_empresa.php?sucesso=dados_salvos");
} else {
    header("Location: cadastro_empresa.php?erro=banco_dados");
}

$conn->close();
?>