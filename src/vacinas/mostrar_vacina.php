<?php

session_start();

include "../includes/conexao.php";
include "../includes/funcoes.php";
include "../includes/autenticacao.php";

if (!isset($_SESSION['id'])) {
    header("Location: ../auth/login.php");
    exit();
}

requireNivel(60);

$instituicao_id = $_SESSION['instituicao_id'];

$id = trim($_GET['id'] ?? '');

if (empty($id)) {
    die("Vacinação inválida.");
}

// Busca a vacinação
$sql = "SELECT
    vacinas.id,
    vacinas.nome_vacina,
    vacinas.data_aplicacao,
    vacinas.proxima_aplicacao,
    vacinas.observacoes,

    eventos.id AS evento_id,
    eventos.titulo,
    eventos.descricao,
    eventos.data_inicio,
    eventos.data_fim,
    eventos.status,

    animais.id AS animal_id,
    animais.nome AS animal_nome,

    users.id AS funcionario_id,
    users.nome AS funcionario_nome

FROM vacinas

INNER JOIN eventos ON vacinas.evento_id = eventos.id
INNER JOIN animais ON eventos.animal_id = animais.id
INNER JOIN users ON eventos.funcionario_id = users.id

WHERE vacinas.id = ?
AND eventos.instituicao_id = ?";

$stmt = $conexao->prepare($sql);
$stmt->bind_param("ii", $id, $instituicao_id);
$stmt->execute();

$result = $stmt->get_result();
$vacina = $result->fetch_assoc();

if (!$vacina) {
    die("Vacinação não encontrada.");
}

?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Informações da Vacinação | WildKeeper</title>
    <link rel="stylesheet" href="../../assets/css/reset.css">
    <link rel="stylesheet" href="../../assets/css/dashboard_global.css">
    <?php include "../includes/fonte.php" ?>
</head>

<body>

    <?php include "../includes/dashboard-header.php" ?>

    <main>

        <section>

            <h1>Informações da Vacinação</h1>

            <article>

                <h2>Informações do Animal</h2>

                <p>
                    <strong>Animal:</strong>
                    <?= htmlspecialchars($vacina['animal_nome']) ?>
                </p>

                <p>
                    <strong>Funcionário responsável:</strong>
                    <?= htmlspecialchars($vacina['funcionario_nome']) ?>
                </p>

            </article>

            <article>

                <h2>Informações da Vacinação</h2>

                <p>
                    <strong>Vacina:</strong>
                    <?= htmlspecialchars($vacina['nome_vacina']) ?>
                </p>

                <p>
                    <strong>Data da aplicação:</strong>
                    <?= htmlspecialchars($vacina['data_aplicacao']) ?>
                </p>

                <p>
                    <strong>Próxima aplicação:</strong>
                    <?= htmlspecialchars($vacina['proxima_aplicacao'] ?? 'Não possui') ?>
                </p>

                <p>
                    <strong>Observações:</strong>
                    <?= !empty($vacina['observacoes']) ? nl2br(htmlspecialchars($vacina['observacoes'])) : 'Nenhuma observação.' ?>
                </p>

            </article>

            <article>

                <h2>Informações do Evento</h2>

                <p>
                    <strong>Título:</strong>
                    <?= htmlspecialchars($vacina['titulo']) ?>
                </p>

                <?php if (!empty($vacina['descricao'])): ?>

                    <p>
                        <strong>Descrição:</strong>
                        <?= nl2br(htmlspecialchars($vacina['descricao'])) ?>
                    </p>

                <?php endif; ?>

                <p>
                    <strong>Data e horário agendados:</strong>
                    <?= htmlspecialchars($vacina['data_inicio']) ?>
                </p>

                <?php if (!empty($vacina['data_fim'])): ?>

                    <p>
                        <strong>Fim previsto:</strong>
                        <?= htmlspecialchars($vacina['data_fim']) ?>
                    </p>

                <?php endif; ?>

                <p>
                    <strong>Status:</strong>
                    <?= htmlspecialchars($vacina['status']) ?>
                </p>

            </article>

            <a href="editar_vacina.php?id=<?= htmlspecialchars($vacina['id']) ?>">
                Editar
            </a>

            <a href="index.php">
                Voltar
            </a>

        </section>

    </main>

    <?php include "../includes/dashboard-footer.php" ?>

</body>

</html>