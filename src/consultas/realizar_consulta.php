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

// Busca o evento da consulta
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
AND eventos.tipo = 'Consulta'
AND eventos.instituicao_id = ?";

$stmt = $conexao->prepare($sql);
$stmt->bind_param("ii", $eventoId, $instituicao_id);
$stmt->execute();

$result = $stmt->get_result();
$evento = $result->fetch_assoc();

// Verifica se o evento foi encontrado
if (!$evento) {
    die("Evento de consulta não encontrado.");
}

// Verifica se a consulta já foi realizada
$sqlConsulta = "SELECT id FROM consultas WHERE evento_id = ?";

$stmtConsulta = $conexao->prepare($sqlConsulta);
$stmtConsulta->bind_param("i", $eventoId);
$stmtConsulta->execute();

$resultConsulta = $stmtConsulta->get_result();

if ($resultConsulta->num_rows > 0) {
    die("Esta consulta já foi realizada.");
}
?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Realizar Consulta | WildKeeper</title>
</head>

<body>
    <?php include "../includes/dashboard-header.php" ?>

    <main>
        <section>
            <h1>Realizar Consulta</h1>

            <article>
                <h2>Informações do Agendamento</h2>

                <p>
                    <strong>Animal:</strong>
                    <?= htmlspecialchars($evento['animal_nome']) ?>
                </p>

                <p>
                    <strong>Veterinário responsável:</strong>
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
                <h2>Resultado da Consulta</h2>

                <form action="salvar_consulta.php" method="POST">

                    <input type="hidden" name="evento_id" value="<?= htmlspecialchars($evento['id']) ?>">

                    <label for="diagnostico">Diagnóstico:</label>
                    <textarea name="diagnostico" id="diagnostico" placeholder="Detalhe aqui o diagnóstico do paciente" required></textarea>

                    <label for="tratamento">Tratamento:</label>
                    <textarea name="tratamento" id="tratamento" placeholder="Detalhe aqui o tratamento que o paciente precisa" required></textarea>

                    <label for="observacoes">Observações:</label>
                    <textarea name="observacoes" id="observacoes" placeholder="Digite aqui as observações"></textarea>

                    <label for="data_retorno">Data de retorno:</label>
                    <input type="date" name="data_retorno" id="data_retorno">

                    <button type="submit">Finalizar Consulta</button>
                    <a href="index.php">Cancelar</a>

                </form>
            </article>
        </section>
    </main>

    <?php include "../includes/dashboard-footer.php" ?>
</body>

</html>