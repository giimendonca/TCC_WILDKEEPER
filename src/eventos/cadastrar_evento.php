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

// Verifica a permissão
requireNivel(40);

// ====================================
// Busca os animais da instituição
// ====================================

$sqlAnimais = "SELECT id, nome
FROM animais
WHERE instituicao_id = ?
ORDER BY nome ASC";

$stmtAnimais = $conexao->prepare($sqlAnimais);
$stmtAnimais->bind_param("i", $_SESSION['instituicao_id']);
$stmtAnimais->execute();

$animais = $stmtAnimais->get_result();

// ====================================
// Busca os funcionários da instituição
// ====================================

$sqlFuncionarios = "SELECT
    users.id,
    users.nome,
    cargos.nome AS cargo_nome
FROM users
INNER JOIN cargos ON cargos.id = users.cargo_id
WHERE users.instituicao_id = ?
AND users.status != 'Desligado'
ORDER BY users.nome ASC";

$stmtFuncionarios = $conexao->prepare($sqlFuncionarios);
$stmtFuncionarios->bind_param("i", $_SESSION['instituicao_id']);
$stmtFuncionarios->execute();

$funcionarios = $stmtFuncionarios->get_result();

?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Novo Evento | WildKeeper</title>
</head>
<body>

    <?php include "../includes/dashboard-header.php" ?>

    <main>
        <section>

            <h1>Novo Evento</h1>

            <form action="salvar_evento.php" method="post">

                <label for="titulo">Título</label>
                <input type="text" name="titulo" id="titulo" maxlength="255" placeholder="Digite o título do evento" required>

                <label for="descricao">Descrição</label>
                <textarea name="descricao" id="descricao" placeholder="Digite uma descrição para o evento..."></textarea>

                <label for="tipo">Tipo</label>
                <select name="tipo" id="tipo" required>
                    <option value="">Selecione</option>
                    <option value="Consulta">Consulta</option>
                    <option value="Vacinação">Vacinação</option>
                    <option value="Alimentação">Alimentação</option>
                    <option value="Manutenção">Manutenção</option>
                    <option value="Transferência">Transferência</option>
                    <option value="Outro">Outro</option>
                </select>

                <label for="data_inicio">Data e hora de início</label>
                <input type="datetime-local" name="data_inicio" id="data_inicio" required>

                <label for="data_fim">Data e hora de fim</label>
                <input type="datetime-local" name="data_fim" id="data_fim" required>

                <label for="animal_id">Animal</label>
                <select name="animal_id" id="animal_id">
                    <option value="">Nenhum / Evento geral</option>

                    <?php while ($animal = $animais->fetch_assoc()): ?>

                        <option value="<?= htmlspecialchars($animal['id']) ?>">
                            <?= htmlspecialchars($animal['nome']) ?>
                        </option>

                    <?php endwhile; ?>

                </select>

                <label for="funcionario_id">Funcionário responsável</label>
                <select name="funcionario_id" id="funcionario_id" required>

                    <option value="">Selecione</option>

                    <?php while ($funcionario = $funcionarios->fetch_assoc()): ?>

                        <option value="<?= htmlspecialchars($funcionario['id']) ?>">
                            <?= htmlspecialchars($funcionario['nome']) ?> - <?= htmlspecialchars($funcionario['cargo_nome']) ?>
                        </option>

                    <?php endwhile; ?>

                </select>

                <label for="status">Status</label>
                <select name="status" id="status" required>
                    <option value="Agendado">Agendado</option>
                    <option value="Em andamento">Em andamento</option>
                    <option value="Concluído">Concluído</option>
                    <option value="Cancelado">Cancelado</option>
                </select>

                <button type="submit">Salvar Evento</button>

            </form>

            <a href="index.php">Voltar</a>

        </section>
    </main>

    <?php include "../includes/dashboard-footer.php" ?>

</body>
</html>