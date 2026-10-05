<?php

session_start();

include "../includes/conexao.php";
include "../includes/funcoes.php";
include "../includes/autenticacao.php";

if (!isset($_SESSION['id'])) {
    header("Location: ../auth/login.php");
    exit();
}

requireNivel(40);

$instituicao_id = $_SESSION['instituicao_id'];

$id = trim($_GET['id'] ?? '');

if (empty($id)) {
    die("Manutenção inválida.");
}

// ====================================
// Busca a manutenção
// ====================================

$sql = "SELECT
    manutencao_habitats.id,
    manutencao_habitats.descricao,

    eventos.id AS evento_id,
    eventos.titulo,
    eventos.data_inicio,
    eventos.status,

    habitats.nome AS habitat_nome,
    users.nome AS funcionario_nome

FROM manutencao_habitats

INNER JOIN eventos
    ON manutencao_habitats.evento_id = eventos.id

INNER JOIN habitats
    ON eventos.habitat_id = habitats.id

INNER JOIN users
    ON eventos.funcionario_id = users.id

WHERE manutencao_habitats.id = ?
AND eventos.tipo = 'Manutenção'
AND eventos.instituicao_id = ?";

$stmt = $conexao->prepare($sql);
$stmt->bind_param("ii", $id, $instituicao_id);
$stmt->execute();

$result = $stmt->get_result();

$manutencao = $result->fetch_assoc();

if (!$manutencao) {
    die("Manutenção não encontrada.");
}

?>

<!DOCTYPE html>
<html lang="pt-br">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Editar Manutenção | WildKeeper</title>

    <link rel="stylesheet" href="../../assets/css/reset.css">
    <link rel="stylesheet" href="../../assets/css/dashboard_global.css">

    <?php include "../includes/fonte.php" ?>

</head>

<body>

    <?php include "../includes/dashboard-header.php" ?>

    <main>

        <section>

            <h1>Editar Manutenção</h1>

            <article>

                <h2>Informações da Manutenção</h2>

                <p>
                    <strong>Título:</strong>
                    <?= htmlspecialchars($manutencao['titulo']) ?>
                </p>

                <p>
                    <strong>Habitat:</strong>
                    <?= htmlspecialchars($manutencao['habitat_nome']) ?>
                </p>

                <p>
                    <strong>Funcionário responsável:</strong>
                    <?= htmlspecialchars($manutencao['funcionario_nome']) ?>
                </p>

                <p>
                    <strong>Data:</strong>
                    <?= htmlspecialchars($manutencao['data_inicio']) ?>
                </p>

                <p>
                    <strong>Status:</strong>
                    <?= htmlspecialchars($manutencao['status']) ?>
                </p>

            </article>

            <form action="atualizar_manutencao.php" method="POST">

                <input type="hidden" name="id" value="<?= htmlspecialchars($manutencao['id']) ?>">

                <label for="descricao">Descrição da manutenção:</label>

                <textarea name="descricao" id="descricao" required><?= htmlspecialchars($manutencao['descricao']) ?></textarea>

                <button type="submit">Salvar alterações</button>

                <a href="mostrar_manutencao.php?id=<?= $manutencao['id'] ?>">
                    Cancelar
                </a>

            </form>

        </section>

    </main>

    <?php include "../includes/dashboard-footer.php" ?>

</body>

</html>