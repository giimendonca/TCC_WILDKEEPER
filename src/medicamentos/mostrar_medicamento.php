<?php

session_start();

include "../includes/conexao.php";
include "../includes/autenticacao.php";

// ====================================
// Verificações de sessão
// ====================================

// Verifica se já existe uma sessão
if (!isset($_SESSION['id'])) {
    header("Location: ../auth/login.php");
    exit();
}

// Verifica a permissão que o usuário possui
requireNivel(60);

// ====================================
// Pega o ID do medicamento
// ====================================

$medicamentoId = trim($_GET['id'] ?? "");

// Verifica se o ID veio vazio
if (empty($medicamentoId)) {
    die("ID inválido.");
}

// ====================================
// SELECT do medicamento
// ====================================

$sql = "SELECT
            id,
            nome,
            descricao,
            fabricante,
            estoque,
            lote,
            vencimento,
            created_at,
            updated_at
        FROM medicamentos
        WHERE id = ?";

$stmt = $conexao->prepare($sql);

$stmt->bind_param("i", $medicamentoId);

$stmt->execute();

$result = $stmt->get_result();

$medicamento = $result->fetch_assoc();

// Verifica se o medicamento foi encontrado
if (!$medicamento) {
    die("Medicamento não encontrado.");
}

?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Medicamento | WildKeeper</title>
</head>
<body>

    <?php include "../includes/dashboard-header.php" ?>

    <main>
        <section>

            <h1>Medicamento: <?= htmlspecialchars($medicamento['nome']) ?></h1>

            <article>

                <h2>Informações Gerais</h2>

                <p>Nome: <?= htmlspecialchars($medicamento['nome']) ?></p>
                <p>Descrição: <?= htmlspecialchars($medicamento['descricao']) ?></p>
                <p>Fabricante: <?= htmlspecialchars($medicamento['fabricante']) ?></p>

            </article>

            <article>

                <h2>Estoque</h2>

                <p>Quantidade: <?= htmlspecialchars($medicamento['estoque']) ?></p>
                <p>Lote: <?= htmlspecialchars($medicamento['lote']) ?></p>
                <p>Vencimento: <?= htmlspecialchars($medicamento['vencimento']) ?></p>

            </article>

            <a href="editar_medicamento.php?id=<?= $medicamentoId ?>">Editar</a>
            <a href="index.php">Voltar</a>

        </section>
    </main>

    <?php include "../includes/dashboard-footer.php" ?>

</body>
</html>