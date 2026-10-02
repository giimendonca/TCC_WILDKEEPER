<?php

session_start();

include "../includes/conexao.php";
include "../includes/autenticacao.php";

// Verifica se já existe uma sessão
if (!isset($_SESSION['id'])) {
    header("Location: ../auth/login.php");
    exit();
}

// Verifica a permissão
requireNivel(20);

// Pega o ID do evento
$eventoId = trim($_GET['id'] ?? '');

// Verifica se o ID veio vazio
if (empty($eventoId)) {
    die("ID inválido.");
}

// Busca o evento
$sql = "SELECT
    eventos.id,
    eventos.titulo,
    eventos.descricao,
    eventos.tipo,
    eventos.data_inicio,
    eventos.data_fim,
    eventos.status,
    eventos.animal_id,
    eventos.habitat_id,
    animais.nome AS animal_nome,
    habitats.nome AS habitat_nome,
    eventos.funcionario_id,
    users.nome AS funcionario_nome
FROM eventos
LEFT JOIN animais ON animais.id = eventos.animal_id
LEFT JOIN habitats ON habitats.id = eventos.habitat_id
INNER JOIN users ON users.id = eventos.funcionario_id
WHERE eventos.id = ? AND eventos.instituicao_id = ?";

$stmt = $conexao->prepare($sql);
$stmt->bind_param("ii", $eventoId, $_SESSION['instituicao_id']);
$stmt->execute();

$result = $stmt->get_result();
$evento = $result->fetch_assoc();

// Verifica se o evento foi encontrado
if (!$evento) {
    die("Evento não encontrado.");
}

?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Evento | WildKeeper</title>
    <link rel="stylesheet" href="../../assets/css/reset.css">
    <link rel="stylesheet" href="../../assets/css/dashboard_global.css">
    <?php include "../includes/fonte.php" ?>
</head>
<body>

    <?php include "../includes/dashboard-header.php" ?>

    <main>
        <section>

            <h1><?= htmlspecialchars($evento['titulo']) ?></h1>

            <p>
                <strong>Tipo:</strong>
                <?= htmlspecialchars($evento['tipo']) ?>
            </p>

            <p>
                <strong>Status:</strong>
                <?= htmlspecialchars($evento['status']) ?>
            </p>

            <p>
                <strong>Início:</strong>
                <?= date("d/m/Y H:i", strtotime($evento['data_inicio'])) ?>
            </p>

            <p>
                <strong>Fim:</strong>
                <?= date("d/m/Y H:i", strtotime($evento['data_fim'])) ?>
            </p>

            <p>
                <strong>Animal:</strong>
                <?= $evento['animal_nome'] ? htmlspecialchars($evento['animal_nome']) : "Evento geral" ?>
            </p>

            <p>
                <strong>Habitat:</strong>
                <?= $evento['habitat_nome'] ? htmlspecialchars($evento['habitat_nome']) : "Evento geral" ?>
            </p>

            <p>
                <strong>Funcionário responsável:</strong>
                <?= htmlspecialchars($evento['funcionario_nome']) ?>
            </p>

            <article>

                <h2>Descrição</h2>

                <?php if (!empty($evento['descricao'])): ?>
                    <p><?= nl2br(htmlspecialchars($evento['descricao'])) ?></p>
                <?php else: ?>
                    <p>Nenhuma descrição cadastrada.</p>
                <?php endif; ?>

            </article>

            <?php if (nivelMinimo(40)): ?>
                <a href="editar_evento.php?id=<?= $eventoId ?>">Editar</a>
            <?php endif; ?>

            <br>

            <a href="index.php">Voltar</a>

        </section>
    </main>

    <?php include "../includes/dashboard-footer.php" ?>

</body>
</html>