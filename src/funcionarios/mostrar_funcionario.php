<?php
session_start();

include "../includes/conexao.php";
include "../includes/autenticacao.php";

// Verifica se ja existe uma sessão
if (!isset($_SESSION['id'])) {
    header("Location: ../auth/login.php");
    exit();
}

// Verifica a permissão que o usuário possui
requireNivel(100);

// Pega o id do funcionário
$funcionarioId = trim($_GET['id'] ?? '');

// Verifica se o id veio vazio
if (empty($funcionarioId)) {
    die("ID inválido.");
}

// Faz o SELECT dos funcionários
$sql = "SELECT 
    users.id,
    users.nome, 
    users.cpf, 
    users.data_nascimento, 
    users.genero, 
    users.telefone, 
    users.email, 
    users.status,
    cargos.id AS cargo_id,
    cargos.nome AS cargo_nome,
    users.status
FROM users
INNER JOIN cargos ON cargos.id = users.cargo_id
WHERE users.instituicao_id = ? AND users.id = ?";

$stmt = $conexao->prepare($sql);
$stmt->bind_param("ii", $_SESSION['instituicao_id'], $funcionarioId);
$stmt->execute();

$result = $stmt->get_result();

$funcionario = $result->fetch_assoc();
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Funcionário | WildKeeper</title>
</head>

<body>
    <?php include "../includes/dashboard-header.php" ?>
    <main>
        <section>
            <h1>Funcionário: <?= htmlspecialchars($funcionario['nome']) ?></h1>

            <article>
                <h2>Informações Pessoais</h2>

                <p>CPF: <?= htmlspecialchars($funcionario['cpf']) ?></p>
                <p>Data de nascimento: <?= htmlspecialchars($funcionario['data_nascimento']) ?></p>
                <p>Gênero: <?= htmlspecialchars($funcionario['genero']) ?></p>
            </article>

            <article>
                <h2>Contato</h2>

                <p>Telefone: <?= htmlspecialchars($funcionario['telefone']) ?></p>
                <p>Email: <?= htmlspecialchars($funcionario['email']) ?></p>
            </article>

            <article>
                <h2>Informações Profissionais</h2>

                <p>Cargo: <?= htmlspecialchars($funcionario['cargo_nome']) ?></p>
                <p>Status: <?= htmlspecialchars($funcionario['status']) ?></p>
            </article>

            <a href="editar_funcionario.php?id=<?= $funcionarioId ?>">Editar</a>
            <a href="index.php">Voltar</a>
        </section>
    </main>
    <?php include "../includes/dashboard-footer.php" ?>
</body>

</html>