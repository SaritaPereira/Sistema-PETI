<?php
include 'conexao.php';

$dados = [];
$edit_id = isset($_GET['edit']) ? (int)$_GET['edit'] : 0;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['acao']) && $_POST['acao'] === 'salvar') {
    $nome = $_POST['nome'];
    $responsavel = $_POST['responsavel'];
    $custo = $_POST['custo'];
    $prazo = $_POST['prazo'];

    if ($nome && $responsavel && $custo && $prazo) {
        if ($edit_id) {
            $sql = "UPDATE projetos SET nome=?, responsavel=?, custo=?, prazo=? WHERE id=?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("sssd", $nome, $responsavel, $custo, $prazo, $edit_id);
        } else {
            $sql = "INSERT INTO projetos (nome, responsavel, custo, prazo) VALUES (?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("sssd", $nome, $responsavel, $custo, $prazo);
        }
        if ($stmt->execute()) {
            header("Location: cadastro_projeto.php?sucesso=dados_salvos");
        } else {
            header("Location: cadastro_projeto.php?erro=banco_dados");
        }
    } else {
        header("Location: cadastro_projeto.php?erro=campos_vazios");
    }
}

$sql = "SELECT * FROM projetos";
$result = $conn->query($sql);
$projetos = $result->fetch_all(MYSQLI_ASSOC);

if ($edit_id) {
    $sql = "SELECT * FROM projetos WHERE id=?";
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
        'campos_vazios' => 'Todos os campos são obrigatórios!',
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
    <title>Cadastro de Projetos</title>
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
            <h1>Cadastro de Projetos</h1>
            <p>Preencha os dados abaixo.</p>
        </header>
        <div class="form-container">
            <form action="cadastro_projeto.php" method="POST" class="form" id="cadastro-form">
                <input type="hidden" name="acao" value="salvar">
                <div class="form-group">
                    <label for="nome">Nome:</label>
                    <input type="text" id="nome" name="nome" value="<?php echo isset($dados['nome']) ? htmlspecialchars($dados['nome']) : ''; ?>" required>
                </div>
                <div class="form-group">
                    <label for="responsavel">Responsável:</label>
                    <input type="text" id="responsavel" name="responsavel" value="<?php echo isset($dados['responsavel']) ? htmlspecialchars($dados['responsavel']) : ''; ?>" required>
                </div>
                <div class="form-group">
                    <label for="custo">Custo:</label>
                    <input type="number" step="0.01" id="custo" name="custo" value="<?php echo isset($dados['custo']) ? htmlspecialchars($dados['custo']) : ''; ?>" required>
                </div>
                <div class="form-group">
                    <label for="prazo">Prazo:</label>
                    <input type="date" id="prazo" name="prazo" value="<?php echo isset($dados['prazo']) ? htmlspecialchars($dados['prazo']) : ''; ?>" required>
                </div>
                <div class="form-actions">
                    <button type="submit" class="btn save">Salvar</button>
                    <button type="button" class="btn cancel" onclick="window.location.href='home.php'">Cancelar</button>
                </div>
            </form>
            <div class="table-container">
                <h2>Projetos Cadastrados</h2>
                <table>
                    <thead>
                        <tr>
                            <th>Nome</th>
                            <th>Responsável</th>
                            <th>Custo</th>
                            <th>Prazo</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($projetos as $projeto): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($projeto['nome']); ?></td>
                                <td><?php echo htmlspecialchars($projeto['responsavel']); ?></td>
                                <td>R$ <?php echo number_format($projeto['custo'], 2, ',', '.'); ?></td>
                                <td><?php echo date('d/m/Y', strtotime($projeto['prazo'])); ?></td>
                                <td>
                                    <a href="cadastro_projeto.php?edit=<?php echo $projeto['id']; ?>" class="btn edit">Editar</a>
                                    <a href="delete_projeto.php?id=<?php echo $projeto['id']; ?>" class="btn delete" onclick="return confirm('Tem certeza?')">Excluir</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <a href="home.php" class="back-link">← Voltar ao início</a>
        </div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const successMessage = document.getElementById('success-message');
            const errorMessage = document.getElementById('error-message');
            const form = document.getElementById('cadastro-form');

            if (successMessage) {
                form.reset();
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