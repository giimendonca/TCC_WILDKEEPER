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

// Pega o id do evento
$eventoId = trim($_GET['evento_id'] ?? '');

// Verifica se o id veio vazio
if (empty($eventoId)) {
    die("Evento inválido.");
}

// Busca o evento da vacinação
$sql = "SELECT
    eventos.id,
    eventos.titulo,
    eventos.descricao,
    eventos.data_inicio,
    eventos.data_fim,
    eventos.status,
    animais.id AS animal_id,
    animais.nome AS animal_nome,
    users.id AS funcionario_id,
    users.nome AS funcionario_nome
FROM eventos
INNER JOIN animais ON eventos.animal_id = animais.id
INNER JOIN users ON eventos.funcionario_id = users.id
WHERE eventos.id = ?
AND eventos.tipo = 'Vacinação'
AND eventos.instituicao_id = ?";

$stmt = $conexao->prepare($sql);
$stmt->bind_param("ii", $eventoId, $instituicao_id);
$stmt->execute();

$result = $stmt->get_result();
$evento = $result->fetch_assoc();

// Verifica se o evento foi encontrado
if (!$evento) {
    die("Evento de vacinação não encontrado.");
}

// Verifica se a vacinação já foi realizada
$sqlVacina = "SELECT id FROM vacinas WHERE evento_id = ?";

$stmtVacina = $conexao->prepare($sqlVacina);
$stmtVacina->bind_param("i", $eventoId);
$stmtVacina->execute();

$resultVacina = $stmtVacina->get_result();

if ($resultVacina->num_rows > 0) {
    die("Esta vacinação já foi realizada.");
}

?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Realizar Vacinação | WildKeeper</title>
    <link rel="stylesheet" href="../../assets/css/reset.css">
    <link rel="stylesheet" href="../../assets/css/dashboard_global.css">
    <?php include "../includes/fonte.php" ?>
</head>

<body>

    <?php include "../includes/dashboard-header.php" ?>

    <main>

        <section>

            <h1>Realizar Vacinação</h1>

            <article>

                <h2>Informações do Agendamento</h2>

                <p>
                    <strong>Animal:</strong>
                    <?= htmlspecialchars($evento['animal_nome']) ?>
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

                <h2>Registro da Vacinação</h2>

                <form action="salvar_vacina.php" method="POST">

                    <input type="hidden" name="evento_id" value="<?= htmlspecialchars($evento['id']) ?>">

                    <label for="nome_vacina">Nome da vacina:</label>
                    <input type="text" name="nome_vacina" id="nome_vacina" maxlength="50" placeholder="Digite o nome da vacina" required>

                    <label for="data_aplicacao">Data da aplicação:</label>
                    <input type="date" name="data_aplicacao" id="data_aplicacao" required>

                    <label for="proxima_aplicacao">Próxima aplicação:</label>
                    <input type="date" name="proxima_aplicacao" id="proxima_aplicacao">

                    <label for="observacoes">Observações:</label>
                    <textarea name="observacoes" id="observacoes" placeholder="Digite aqui as observações"></textarea>

                    <button type="submit">Finalizar Vacinação</button>
                    <a href="index.php">Cancelar</a>

                </form>

            </article>

        </section>

    </main>

    <?php include "../includes/dashboard-footer.php" ?>

</body>

</html>