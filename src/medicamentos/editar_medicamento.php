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
requireNivel(40);

// ====================================
// Pega o ID do medicamento
// ====================================

$medicamentoId = trim($_GET['id'] ?? "");

if (empty($medicamentoId)) {
    die("ID inválido.");
}

// ====================================
// Busca o medicamento
// ====================================

$sql = "SELECT
            id,
            nome,
            descricao,
            fabricante,
            estoque,
            lote,
            vencimento
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
    <title>Editar Medicamento | WildKeeper</title>
</head>
<body>

    <?php include "../includes/dashboard-header.php" ?>

    <main>
        <section>

            <h1>Editar Medicamento</h1>

            <form action="atualizar_medicamento.php" method="post">

                <input type="hidden" name="id" value="<?= htmlspecialchars($medicamento['id']) ?>">

                <label for="nome">Nome</label>
                <input type="text" name="nome" id="nome" maxlength="255" value="<?= htmlspecialchars($medicamento['nome']) ?>" required>

                <label for="descricao">Descrição</label>
                <textarea name="descricao" id="descricao"><?= htmlspecialchars($medicamento['descricao']) ?></textarea>

                <label for="fabricante">Fabricante</label>
                <input type="text" name="fabricante" id="fabricante" maxlength="255" value="<?= htmlspecialchars($medicamento['fabricante']) ?>" required>

                <label for="estoque">Estoque</label>
                <input type="number" name="estoque" id="estoque" min="0" value="<?= htmlspecialchars($medicamento['estoque']) ?>" required>

                <label for="lote">Lote</label>
                <input type="text" name="lote" id="lote" maxlength="100" value="<?= htmlspecialchars($medicamento['lote']) ?>" required>

                <label for="vencimento">Vencimento</label>
                <input type="date" name="vencimento" id="vencimento" value="<?= htmlspecialchars($medicamento['vencimento']) ?>" required>

                <button type="submit">Atualizar Medicamento</button>

            </form>

        </section>
    </main>

    <?php include "../includes/dashboard-footer.php" ?>

</body>
</html>