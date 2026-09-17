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
    animais.nome AS animal_nome,
    eventos.funcionario_id,
    users.nome AS funcionario_nome
FROM eventos
LEFT JOIN animais ON animais.id = eventos.animal_id
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

// ====================================
// Verifica onde o evento está sendo usado
// ====================================

$quantidadeConsultas = 0;
$quantidadeAlimentacoes = 0;
$quantidadeVacinas = 0;
$quantidadeManutencoes = 0;
$quantidadeHistoricos = 0;

$stmt = $conexao->prepare("SELECT COUNT(*) AS total FROM consultas WHERE evento_id = ? AND instituicao_id = ?");
$stmt->bind_param("ii", $eventoId, $_SESSION['instituicao_id']);
$stmt->execute();
$quantidadeConsultas = $stmt->get_result()->fetch_assoc()['total'];

$stmt = $conexao->prepare("SELECT COUNT(*) AS total FROM alimentacoes WHERE evento_id = ? AND instituicao_id = ?");
$stmt->bind_param("ii", $eventoId, $_SESSION['instituicao_id']);
$stmt->execute();
$quantidadeAlimentacoes = $stmt->get_result()->fetch_assoc()['total'];

$stmt = $conexao->prepare("SELECT COUNT(*) AS total FROM vacinas WHERE evento_id = ? AND instituicao_id = ?");
$stmt->bind_param("ii", $eventoId, $_SESSION['instituicao_id']);
$stmt->execute();
$quantidadeVacinas = $stmt->get_result()->fetch_assoc()['total'];

$stmt = $conexao->prepare("SELECT COUNT(*) AS total FROM manutencao_habitats WHERE evento_id = ? AND instituicao_id = ?");
$stmt->bind_param("ii", $eventoId, $_SESSION['instituicao_id']);
$stmt->execute();
$quantidadeManutencoes = $stmt->get_result()->fetch_assoc()['total'];

$stmt = $conexao->prepare("SELECT COUNT(*) AS total FROM historico_habitats WHERE evento_id = ? AND instituicao_id = ?");
$stmt->bind_param("ii", $eventoId, $_SESSION['instituicao_id']);
$stmt->execute();
$quantidadeHistoricos = $stmt->get_result()->fetch_assoc()['total'];

$totalRegistrosRelacionados =
    $quantidadeConsultas +
    $quantidadeAlimentacoes +
    $quantidadeVacinas +
    $quantidadeManutencoes +
    $quantidadeHistoricos;

?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Evento | WildKeeper</title>
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

            <article>

                <h2>Registros relacionados</h2>

                <?php if ($totalRegistrosRelacionados > 0): ?>

                    <?php if ($quantidadeConsultas > 0): ?>
                        <p>Consultas: <?= $quantidadeConsultas ?></p>
                    <?php endif; ?>

                    <?php if ($quantidadeVacinas > 0): ?>
                        <p>Vacinas: <?= $quantidadeVacinas ?></p>
                    <?php endif; ?>

                    <?php if ($quantidadeAlimentacoes > 0): ?>
                        <p>Alimentações: <?= $quantidadeAlimentacoes ?></p>
                    <?php endif; ?>

                    <?php if ($quantidadeManutencoes > 0): ?>
                        <p>Manutenções: <?= $quantidadeManutencoes ?></p>
                    <?php endif; ?>

                    <?php if ($quantidadeHistoricos > 0): ?>
                        <p>Históricos de habitat: <?= $quantidadeHistoricos ?></p>
                    <?php endif; ?>

                <?php else: ?>

                    <p>Este evento ainda não possui registros relacionados.</p>

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