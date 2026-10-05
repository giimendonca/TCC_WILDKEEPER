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
    manutencao_habitats.id AS manutencao_id,
    manutencao_habitats.descricao AS manutencao_descricao,
    manutencao_habitats.created_at AS manutencao_created_at,
    manutencao_habitats.updated_at AS manutencao_updated_at,

    eventos.id AS evento_id,
    eventos.titulo,
    eventos.descricao AS evento_descricao,
    eventos.data_inicio,
    eventos.data_fim,
    eventos.status,

    habitats.id AS habitat_id,
    habitats.nome AS habitat_nome,

    users.id AS funcionario_id,
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

    <title>Informações da Manutenção | WildKeeper</title>

    <link rel="stylesheet" href="../../assets/css/reset.css">
    <link rel="stylesheet" href="../../assets/css/dashboard_global.css">

    <?php include "../includes/fonte.php" ?>

</head>

<body>

    <?php include "../includes/dashboard-header.php" ?>

    <main>

        <section>

            <h1>Informações da Manutenção</h1>

            <article>

                <h2>Informações do Evento</h2>

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
                    <strong>Início:</strong>
                    <?= htmlspecialchars($manutencao['data_inicio']) ?>
                </p>

                <p>
                    <strong>Fim:</strong>
                    <?= htmlspecialchars($manutencao['data_fim']) ?>
                </p>

                <p>
                    <strong>Status:</strong>
                    <?= htmlspecialchars($manutencao['status']) ?>
                </p>

                <?php if (!empty($manutencao['evento_descricao'])): ?>

                    <p>
                        <strong>Descrição do agendamento:</strong>
                        <?= nl2br(htmlspecialchars($manutencao['evento_descricao'])) ?>
                    </p>

                <?php endif; ?>

            </article>

            <article>

                <h2>Manutenção Realizada</h2>

                <p>
                    <strong>Descrição:</strong>
                </p>

                <p>
                    <?= nl2br(htmlspecialchars($manutencao['manutencao_descricao'])) ?>
                </p>

            </article>

            <div>

                <a href="editar_manutencao.php?id=<?= $manutencao['manutencao_id'] ?>">
                    Editar manutenção
                </a>

                <a href="index.php">
                    Voltar
                </a>

            </div>

        </section>

    </main>

    <?php include "../includes/dashboard-footer.php" ?>

</body>

</html>