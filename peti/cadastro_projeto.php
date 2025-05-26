<?php
include 'conexao.php';

$dados = [];
$sql = "SELECT nome, missao, visao FROM organizacao LIMIT 1";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $dados = $result->fetch_assoc();
}

$conn->close();
?>

<?php

if (isset($_GET['sucesso'])) {
    $mensagem = match($_GET['sucesso']) {
        'dados_salvos' => 'Salvo com sucesso!',
        default => 'Operação realizada com sucesso!'
    };
    echo '<div class="alert success" id="success-message">'.$mensagem.'</div>';
}

if (isset($_GET['erro'])) {
    $mensagem = match($_GET['erro']) {
        'campos_vazios' => 'Todos os campos são obrigatórios!',
        'banco_dados' => 'Erro ao salvar no banco de dados',
        default => 'Ocorreu um erro!'
    };
    echo '<div class="alert error" id="error-message">'.$mensagem.'</div>';
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
                    <input type="text" id="custo" name="custo" value="<?php echo isset($dados['custo']) ? htmlspecialchars($dados['custo']) : ''; ?>" required>
                </div>
                <div class="form-group">
                    <label for="prazo">Prazo:</label>
                    <input type="date" id="prazo" name="prazo" value="<?php echo isset($dados['prazo']) ? htmlspecialchars($dados['prazo']) : ''; ?>" required>
                </div>
                <button type="submit" class="btn">Salvar</button>
            </form>
        </div>
    </div>
    <script>
        // Limpar os campos do formulário e estilizar a notificação de sucesso
        document.addEventListener('DOMContentLoaded', function() {
            const successMessage = document.getElementById('success-message');
            const form = document.getElementById('cadastro-form');

            if (successMessage) {
                // Limpar os campos do formulário
                form.reset();

                // Adicionar efeito de fade-out após 3 segundos
                setTimeout(() => {
                    successMessage.style.opacity = '0';
                    setTimeout(() => {
                        successMessage.style.display = 'none';
                    }, 500); // Tempo para o fade-out
                }, 3000); // Exibe por 3 segundos
            }

            const errorMessage = document.getElementById('error-message');
            if (errorMessage) {
                // Adicionar efeito de fade-out para mensagens de erro também
                setTimeout(() => {
                    errorMessage.style.opacity = '0';
                    setTimeout(() => {
                        errorMessage.style.display = 'none';
                    }, 500);
                }, 3000);
            }
        });
    </script>
</body>
</html>