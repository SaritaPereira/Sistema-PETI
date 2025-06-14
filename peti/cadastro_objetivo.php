<?php
include 'conexao.php';

$dados = [];
$edit_id = isset($_GET['edit']) ? (int)$_GET['edit'] : 0;
$projeto_id = isset($_POST['projeto_id']) ? (int)$_POST['projeto_id'] : (isset($_GET['projeto_id']) ? (int)$_GET['projeto_id'] : 0);

// Verifica se o projeto_id é válido
$projeto_valido = false;
if ($projeto_id) {
    $sql_verifica = "SELECT id FROM projetos WHERE id = ?";
    $stmt_verifica = $conn->prepare($sql_verifica);
    $stmt_verifica->bind_param("i", $projeto_id);
    $stmt_verifica->execute();
    $projeto_valido = $stmt_verifica->get_result()->num_rows > 0;
    $stmt_verifica->close();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['acao']) && $_POST['acao'] === 'salvar') {
    $titulo = $_POST['titulo'];
    $tipo = $_POST['tipo'];
    $descricao = $_POST['descricao'];
    $projeto_id = (int)$_POST['projeto_id'];

    if ($titulo && $tipo && $descricao && $projeto_valido) {
        if ($edit_id) {
            $sql = "UPDATE objetivos SET projeto_id=?, titulo=?, tipo=?, descricao=? WHERE id=?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("isssi", $projeto_id, $titulo, $tipo, $descricao, $edit_id);
        } else {
            $sql = "INSERT INTO objetivos (projeto_id, titulo, tipo, descricao) VALUES (?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("isss", $projeto_id, $titulo, $tipo, $descricao);
        }
        if ($stmt->execute()) {
            // Redireciona mantendo o projeto_id e adiciona um parâmetro para indicar sucesso
            header("Location: cadastro_objetivo.php?projeto_id=$projeto_id&sucesso=dados_salvos&reset=true");
        } else {
            header("Location: cadastro_objetivo.php?projeto_id=$projeto_id&erro=banco_dados");
        }
    } else {
        header("Location: cadastro_objetivo.php?projeto_id=$projeto_id&erro=campos_vazios");
    }
}

$sql = "SELECT * FROM objetivos WHERE projeto_id=?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $projeto_id);
$stmt->execute();
$objetivos = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

if ($edit_id) {
    $sql = "SELECT * FROM objetivos WHERE id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $edit_id);
    $stmt->execute();
    $dados = $stmt->get_result()->fetch_assoc();
}

$conn->close();
?>

<?php
if (isset($_GET['sucesso'])) {
    $mensagem = match($_GET['sucesso']) {
        'dados_salvos' => 'Salvo com sucesso!',
        default => 'Operação realizada com sucesso!'
    };
    echo '<div class="alert success" id="success-message">' . $mensagem . '</div>';
}

if (isset($_GET['erro'])) {
    $mensagem = match($_GET['erro']) {
        'campos_vazios' => 'Todos os campos são obrigatórios ou o projeto não existe!',
        'banco_dados' => 'Erro ao salvar no banco de dados',
        default => 'Ocorreu um erro!'
    };
    echo '<div class="alert error" id="error-message">' . $mensagem . '</div>';
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Cadastro de Objetivos</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>
<body>
    <nav class="navbar">
        <div class="navbar-brand">
            <i class="fas fa-laptop-code"></i> Sistema PETI
        </div>
        <ul class="navbar-menu">
            <li><a href="home.php"><i class="fas fa-home"></i> Início</a></li>
            <li><a href="cadastro_empresa.php"><i class="fas fa-building"></i> Dados da Empresa</a></li>
            <li><a href="cadastro_projeto.php"><i class="fas fa-project-diagram"></i> Projetos</a></li>
            <li><a href="cadastro_objetivo.php"><i class="fas fa-bullseye"></i> Objetivos</a></li>
        </ul>
    </nav>
    <div class="content">
        <header class="header">
            <h1>Cadastro de Objetivos</h1>
            <p>Preencha os dados abaixo.</p>
        </header>
        <div class="form-container">
            <form action="cadastro_objetivo.php" method="POST" class="form" id="cadastro-form">
                <input type="hidden" name="acao" value="salvar">
                <div class="form-group">
                    <label for="projeto_id">ID do Projeto:</label>
                    <input type="number" id="projeto_id" name="projeto_id" value="<?php echo $projeto_id; ?>" required>
                </div>
                <div class="form-group">
                    <label for="titulo">Título do Objetivo:</label>
                    <input type="text" id="titulo" name="titulo" value="<?php echo isset($dados['titulo']) ? htmlspecialchars($dados['titulo']) : ''; ?>" required>
                </div>
                <div class="form-group">
                    <label for="tipo">Tipo:</label>
                    <select id="tipo" name="tipo" required>
                        <option value="">Selecione</option>
                        <option value="Organizacional" <?php echo isset($dados['tipo']) && $dados['tipo'] === 'Organizacional' ? 'selected' : ''; ?>>Organizacional</option>
                        <option value="TI" <?php echo isset($dados['tipo']) && $dados['tipo'] === 'TI' ? 'selected' : ''; ?>>TI</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="descricao">Descrição:</label>
                    <textarea name="descricao" id="descricao" rows="4" cols="50" required><?php echo isset($dados['descricao']) ? htmlspecialchars($dados['descricao']) : ''; ?></textarea>
                </div>
                <div class="form-actions">
                    <button type="submit" class="btn save">Salvar</button>
                    <button type="button" class="btn cancel" onclick="window.location.href='cadastro_projeto.php'">Cancelar</button>
                </div>
            </form>
            <div class="table-container">
                <h2>Objetivos Cadastrados</h2>
                <table>
                    <thead>
                        <tr>
                            <th>Título</th>
                            <th>Tipo</th>
                            <th>Descrição</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($objetivos as $objetivo): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($objetivo['titulo']); ?></td>
                                <td><?php echo htmlspecialchars($objetivo['tipo']); ?></td>
                                <td><?php echo htmlspecialchars($objetivo['descricao']); ?></td>
                                <td>
                                    <a href="cadastro_objetivo.php?edit=<?php echo $objetivo['id']; ?>&projeto_id=<?php echo $objetivo['projeto_id']; ?>" class="btn edit">Editar</a>
                                    <a href="delete_objetivo.php?id=<?php echo $objetivo['id']; ?>&projeto_id=<?php echo $objetivo['projeto_id']; ?>" class="btn delete" onclick="return confirm('Tem certeza?')">Excluir</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <a href="cadastro_projeto.php" class="back-link">← Voltar</a>
        </div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const successMessage = document.getElementById('success-message');
            const errorMessage = document.getElementById('error-message');
            const form = document.getElementById('cadastro-form');

            // Limpa os campos (exceto projeto_id) após sucesso
            if (successMessage && new URLSearchParams(window.location.search).get('reset') === 'true') {
                document.getElementById('titulo').value = '';
                document.getElementById('tipo').value = '';
                document.getElementById('descricao').value = '';
                setTimeout(() => {
                    successMessage.style.opacity = '0';
                    setTimeout(() => successMessage.style.display = 'none', 500);
                }, 3000);
            }

            if (errorMessage) {
                setTimeout(() => {
                    errorMessage.style.opacity = '0';
                    setTimeout(() => errorMessage.style.display = 'none', 500);
                }, 3000);
            }
        });
    </script>