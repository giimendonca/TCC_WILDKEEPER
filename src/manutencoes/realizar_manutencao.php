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

$eventoId = trim($_GET['evento_id'] ?? '');

if (empty($eventoId)) {
    die("Evento inválido.");
}

// ====================================
// Busca o evento
// ====================================

$sql = "SELECT
    eventos.id,
    eventos.titulo,
    eventos.descricao,
    eventos.data_inicio,
    eventos.data_fim,
    eventos.status,

    habitats.id AS habitat_id,
    habitats.nome AS habitat_nome,

    users.id AS funcionario_id,
    users.nome AS funcionario_nome

FROM eventos

INNER JOIN habitats ON eventos.habitat_id = habitats.id
INNER JOIN users ON eventos.funcionario_id = users.id

WHERE eventos.id = ?
AND eventos.tipo = 'Manutenção'
AND eventos.instituicao_id = ?";

$stmt = $conexao->prepare($sql);
$stmt->bind_param("ii", $eventoId, $instituicao_id);
$stmt->execute();

$result = $stmt->get_result();
$evento = $result->fetch_assoc();

if (!$evento) {
    die("Evento de manutenção não encontrado.");
}

// ====================================
// Verifica se já foi realizada
// ====================================

$sqlManutencao = "SELECT id
FROM manutencao_habitats
WHERE evento_id = ?";

$stmtManutencao = $conexao->prepare($sqlManutencao);
$stmtManutencao->bind_param("i", $eventoId);
$stmtManutencao->execute();

$resultManutencao = $stmtManutencao->get_result();

if ($resultManutencao->num_rows > 0) {
    die("Esta manutenção já foi realizada.");
}

?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Realizar Manutenção | WildKeeper</title>

    <link rel="stylesheet" href="../../assets/css/reset.css">
    <link rel="stylesheet" href="../../assets/css/dashboard_global.css">

    <?php include "../includes/fonte.php" ?>

</head>

<body>

    <?php include "../includes/dashboard-header.php" ?>

    <main>

        <section>

            <h1>Realizar Manutenção</h1>

            <article>

                <h2>Informações do Agendamento</h2>

                <p>
                    <strong>Habitat:</strong>
                    <?= htmlspecialchars($evento['habitat_nome']) ?>
                </p>

                <p>
                    <strong>Funcionário responsável:</strong>
                    <?= htmlspecialchars($evento['funcionario_nome']) ?>
                </p>

                <p>
                    <strong>Data e horário:</strong>
                    <?= htmlspecialchars($evento['data_inicio']) ?>
                </p>

                <?php if (!empty($evento['data_fim'])): ?>

                    <p>
                        <strong>Fim previsto:</strong>
                        <?= htmlspecialchars($evento['data_fim']) ?>
                    </p>

                <?php endif; ?>

                <?php if (!empty($evento['descricao'])): ?>

                    <p>
                        <strong>Descrição do agendamento:</strong>
                        <?= nl2br(htmlspecialchars($evento['descricao'])) ?>
                    </p>

                <?php endif; ?>

            </article>

            <article>

                <h2>Resultado da Manutenção</h2>

                <form action="salvar_manutencao.php" method="POST">

                    <input type="hidden" name="evento_id" value="<?= htmlspecialchars($evento['id']) ?>">

                    <label for="descricao">Descrição da manutenção:</label>

                    <textarea name="descricao" id="descricao" placeholder="Descreva os serviços realizados no habitat" required></textarea>

                    <button type="submit">Finalizar Manutenção</button>

                    <a href="index.php">Cancelar</a>

                </form>

            </article>

        </section>

    </main>

    <?php include "../includes/dashboard-footer.php" ?>

</body>

</html>