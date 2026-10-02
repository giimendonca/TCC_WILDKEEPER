<?php

session_start();

include "../includes/conexao.php";
include "../includes/funcoes.php";
include "../includes/autenticacao.php";

// Verifica se já existe uma sessão
if (!isset($_SESSION['id'])) {
    header("Location: ../auth/login.php");
    exit();
}

// Verifica a permissão que o usuário possui
requireNivel(60);

$instituicao_id = $_SESSION['instituicao_id'];

// Pega o id da consulta
$consultaId = trim($_GET['id'] ?? '');

// Verifica se o id veio vazio
if (empty($consultaId)) {
    die("ID inválido.");
}

// Busca a consulta junto com os dados do evento
$sql = "SELECT
    consultas.id,
    consultas.evento_id,
    consultas.diagnostico,
    consultas.tratamento,
    consultas.observacoes,
    consultas.data_retorno,

    eventos.titulo,
    eventos.data_inicio,
    eventos.data_fim,
    eventos.status AS evento_status,

    animais.nome AS animal_nome,
    users.nome AS funcionario_nome

FROM consultas

INNER JOIN eventos ON consultas.evento_id = eventos.id
INNER JOIN animais ON eventos.animal_id = animais.id
INNER JOIN users ON eventos.funcionario_id = users.id

WHERE consultas.id = ?
AND eventos.instituicao_id = ?
AND eventos.tipo = 'Consulta'";

$stmt = $conexao->prepare($sql);
$stmt->bind_param("ii", $consultaId, $instituicao_id);
$stmt->execute();

$result = $stmt->get_result();
$consulta = $result->fetch_assoc();

// Verifica se a consulta foi encontrada
if (!$consulta) {
    die("Consulta não encontrada.");
}
?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Consulta | WildKeeper</title>
    <link rel="stylesheet" href="../../assets/css/reset.css">
    <link rel="stylesheet" href="../../assets/css/dashboard_global.css">
    <?php include "../includes/fonte.php" ?>
</head>

<body>
    <?php include "../includes/dashboard-header.php" ?>

    <main>
        <section>
            <h1>Editar Consulta #<?= htmlspecialchars($consulta['id']) ?></h1>

            <article>
                <h2>Informações do Agendamento</h2>

                <p>
                    <strong>Animal:</strong>
                    <?= htmlspecialchars($consulta['animal_nome']) ?>
                </p>

                <p>
                    <strong>Veterinário responsável:</strong>
                    <?= htmlspecialchars($consulta['funcionario_nome']) ?>
                </p>

                <p>
                    <strong>Data da consulta:</strong>
                    <?= htmlspecialchars($consulta['data_inicio']) ?>
                </p>

                <p>
                    <strong>Status:</strong>
                    <?= htmlspecialchars($consulta['evento_status']) ?>
                </p>
            </article>

            <article>
                <h2>Resultado da Consulta</h2>

                <form action="atualizar_consulta.php" method="POST">

                    <input type="hidden" name="id" value="<?= htmlspecialchars($consulta['id']) ?>">

                    <label for="diagnostico">Diagnóstico:</label>
                    <textarea name="diagnostico" id="diagnostico" placeholder="Detalhe aqui o diagnóstico do paciente" required><?= htmlspecialchars($consulta['diagnostico']) ?></textarea>

                    <label for="tratamento">Tratamento:</label>
                    <textarea name="tratamento" id="tratamento" placeholder="Detalhe aqui o tratamento que o paciente precisa" required><?= htmlspecialchars($consulta['tratamento']) ?></textarea>

                    <label for="observacoes">Observações:</label>
                    <textarea name="observacoes" id="observacoes" placeholder="Digite aqui as observações"><?= htmlspecialchars($consulta['observacoes'] ?? '') ?></textarea>

                    <label for="data_retorno">Data de retorno:</label>
                    <input type="date" name="data_retorno" id="data_retorno" value="<?= htmlspecialchars($consulta['data_retorno'] ?? '') ?>">

                    <button type="submit">Atualizar Consulta</button>
                    <a href="mostrar_consulta.php?id=<?= htmlspecialchars($consulta['id']) ?>">Cancelar</a>

                </form>
            </article>
        </section>
    </main>

    <?php include "../includes/dashboard-footer.php" ?>
</body>

</html>