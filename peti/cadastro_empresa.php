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
// Exibir mensagens de sucesso ou erro
if (isset($_GET['sucesso'])) {
    $mensagem = match ($_GET['sucesso']) {
        'dados_salvos' => 'Salvo com sucesso!',
        default => 'Operação realizada com sucesso!'
    };
    echo '<div class="alert success" id="success-message">' . $mensagem . '</div>';
}

if (isset($_GET['erro'])) {
    $mensagem = match ($_GET['erro']) {
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
    <title>Cadastro de Missão e Visão</title>
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
            <h1>Cadastro de Missão e Visão</h1>
            <p>Preencha os dados abaixo.</p>
        </header>
        <div class="form-container">
            <form action="salvar_missao_visao.php" method="POST" class="form" id="cadastro-form">
                <div class="form-group">
                    <label for="nome">Nome:</label>
                    <input type="text" id="nome" name="nome" value="<?php echo (isset($_GET['sucesso']) ? '' : (isset($dados['nome']) ? htmlspecialchars($dados['nome']) : '')); ?>" required>
                </div>
                <div class="form-group">
                    <label for="missao">Missão:</label>
                    <textarea name="missao" id="missao" rows="4" cols="50" required><?php echo (isset($_GET['sucesso']) ? '' : (isset($dados['missao']) ? htmlspecialchars($dados['missao']) : '')); ?></textarea>
                </div>
                <div class="form-group">
                    <label for="visao">Visão:</label>
                    <textarea name="visao" id="visao" rows="4" cols="50" required><?php echo (isset($_GET['sucesso']) ? '' : (isset($dados['visao']) ? htmlspecialchars($dados['visao']) : '')); ?></textarea>
                </div>
                <button type="submit" class="btn">Salvar</button>
            </form>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const successMessage = document.getElementById('success-message');
            const errorMessage = document.getElementById('error-message');
            const form = document.getElementById('cadastro-form');

            if (successMessage) {
                form.reset(); // Reseta o formulário se a mensagem de sucesso estiver presente
                setTimeout(() => {
                    successMessage.style.transition = 'opacity 0.5s';
                    successMessage.style.opacity = '0';
                    setTimeout(() => {
                        successMessage.style.display = 'none';
                        // Remove o parâmetro 'sucesso' da URL
                        window.history.replaceState({}, document.title, window.location.pathname);
                    }, 500);
                }, 3000);
            }

            if (errorMessage) {
                setTimeout(() => {
                    errorMessage.style.transition = 'opacity 0.5s';
                    errorMessage.style.opacity = '0';
                    setTimeout(() => {
                        errorMessage.style.display = 'none';
                        // Remove o parâmetro 'erro' da URL
                        window.history.replaceState({}, document.title, window.location.pathname);
                    }, 500);
                }, 3000);
            }
        });
    </script>
</body>
</html>