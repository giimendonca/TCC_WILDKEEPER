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
    eventos.data_inicio,
    eventos.data_fim,

    animais.nome AS animal_nome,
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
    <title>Editar Vacinação | WildKeeper</title>
    <link rel="stylesheet" href="../../assets/css/reset.css">
    <link rel="stylesheet" href="../../assets/css/dashboard_global.css">
    <?php include "../includes/fonte.php" ?>
</head>

<body>

    <?php include "../includes/dashboard-header.php" ?>

    <main>

        <section>

            <h1>Editar Vacinação</h1>

            <article>

                <h2>Informações do Agendamento</h2>

                <p>
                    <strong>Animal:</strong>
                    <?= htmlspecialchars($vacina['animal_nome']) ?>
                </p>

                <p>
                    <strong>Funcionário responsável:</strong>
                    <?= htmlspecialchars($vacina['funcionario_nome']) ?>
                </p>

                <p>
                    <strong>Data e horário:</strong>
                    <?= htmlspecialchars($vacina['data_inicio']) ?>
                </p>

            </article>

            <article>

                <h2>Dados da Vacinação</h2>

                <form action="atualizar_vacina.php" method="POST">

                    <input type="hidden" name="id" value="<?= htmlspecialchars($vacina['id']) ?>">

                    <label for="nome_vacina">Nome da vacina:</label>
                    <input type="text" name="nome_vacina" id="nome_vacina" maxlength="50" value="<?= htmlspecialchars($vacina['nome_vacina']) ?>" required>

                    <label for="data_aplicacao">Data da aplicação:</label>
                    <input type="date" name="data_aplicacao" id="data_aplicacao" value="<?= htmlspecialchars($vacina['data_aplicacao']) ?>" required>

                    <label for="proxima_aplicacao">Próxima aplicação:</label>
                    <input type="date" name="proxima_aplicacao" id="proxima_aplicacao" value="<?= htmlspecialchars($vacina['proxima_aplicacao'] ?? '') ?>">

                    <label for="observacoes">Observações:</label>
                    <textarea name="observacoes" id="observacoes"><?= htmlspecialchars($vacina['observacoes'] ?? '') ?></textarea>

                    <button type="submit">Salvar alterações</button>

                    <a href="mostrar_vacina.php?id=<?= htmlspecialchars($vacina['id']) ?>">
                        Cancelar
                    </a>

                </form>

            </article>

        </section>

    </main>

    <?php include "../includes/dashboard-footer.php" ?>

</body>

</html>