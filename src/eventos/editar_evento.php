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
requireNivel(40);

// Pega o ID do evento
$eventoId = trim($_GET['id'] ?? '');

if (empty($eventoId)) {
    die("ID inválido.");
}

// ====================================
// Busca o evento
// ====================================

$sql = "SELECT *
FROM eventos
WHERE id = ?
AND instituicao_id = ?";

$stmt = $conexao->prepare($sql);
$stmt->bind_param("ii", $eventoId, $_SESSION['instituicao_id']);
$stmt->execute();

$result = $stmt->get_result();
$evento = $result->fetch_assoc();

if (!$evento) {
    die("Evento não encontrado.");
}

// ====================================
// Busca os animais
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
// Busca os funcionários
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
    <title>Editar Evento | WildKeeper</title>
</head>
<body>

    <?php include "../includes/dashboard-header.php" ?>

    <main>
        <section>

            <h1>Editar Evento</h1>

            <form action="atualizar_evento.php" method="post">

                <input type="hidden" name="id" value="<?= htmlspecialchars($evento['id']) ?>">

                <label for="titulo">Título</label>
                <input type="text" name="titulo" id="titulo" maxlength="255" value="<?= htmlspecialchars($evento['titulo']) ?>" required>

                <label for="descricao">Descrição</label>
                <textarea name="descricao" id="descricao"><?= htmlspecialchars($evento['descricao'] ?? "") ?></textarea>

                <label for="tipo">Tipo</label>
                <select name="tipo" id="tipo" required>
                    <option value="Consulta" <?= $evento['tipo'] == "Consulta" ? "selected" : "" ?>>Consulta</option>
                    <option value="Vacinação" <?= $evento['tipo'] == "Vacinação" ? "selected" : "" ?>>Vacinação</option>
                    <option value="Alimentação" <?= $evento['tipo'] == "Alimentação" ? "selected" : "" ?>>Alimentação</option>
                    <option value="Manutenção" <?= $evento['tipo'] == "Manutenção" ? "selected" : "" ?>>Manutenção</option>
                    <option value="Transferência" <?= $evento['tipo'] == "Transferência" ? "selected" : "" ?>>Transferência</option>
                    <option value="Outro" <?= $evento['tipo'] == "Outro" ? "selected" : "" ?>>Outro</option>
                </select>

                <label for="data_inicio">Data e hora de início</label>
                <input type="datetime-local" name="data_inicio" id="data_inicio" value="<?= date('Y-m-d\TH:i', strtotime($evento['data_inicio'])) ?>" required>

                <label for="data_fim">Data e hora de fim</label>
                <input type="datetime-local" name="data_fim" id="data_fim" value="<?= date('Y-m-d\TH:i', strtotime($evento['data_fim'])) ?>" required>

                <label for="animal_id">Animal</label>
                <select name="animal_id" id="animal_id">

                    <option value="">Nenhum / Evento geral</option>

                    <?php while ($animal = $animais->fetch_assoc()): ?>

                        <option value="<?= htmlspecialchars($animal['id']) ?>" <?= $animal['id'] == $evento['animal_id'] ? "selected" : "" ?>>
                            <?= htmlspecialchars($animal['nome']) ?>
                        </option>

                    <?php endwhile; ?>

                </select>

                <label for="funcionario_id">Funcionário responsável</label>
                <select name="funcionario_id" id="funcionario_id" required>

                    <option value="">Selecione</option>

                    <?php while ($funcionario = $funcionarios->fetch_assoc()): ?>

                        <option value="<?= htmlspecialchars($funcionario['id']) ?>" <?= $funcionario['id'] == $evento['funcionario_id'] ? "selected" : "" ?>>
                            <?= htmlspecialchars($funcionario['nome']) ?> - <?= htmlspecialchars($funcionario['cargo_nome']) ?>
                        </option>

                    <?php endwhile; ?>

                </select>

                <label for="status">Status</label>
                <select name="status" id="status" required>
                    <option value="Agendado" <?= $evento['status'] == "Agendado" ? "selected" : "" ?>>Agendado</option>
                    <option value="Em andamento" <?= $evento['status'] == "Em andamento" ? "selected" : "" ?>>Em andamento</option>
                    <option value="Concluído" <?= $evento['status'] == "Concluído" ? "selected" : "" ?>>Concluído</option>
                    <option value="Cancelado" <?= $evento['status'] == "Cancelado" ? "selected" : "" ?>>Cancelado</option>
                </select>

                <button type="submit">Atualizar Evento</button>

            </form>

            <a href="mostrar_evento.php?id=<?= $eventoId ?>">Cancelar</a>

        </section>
    </main>

    <?php include "../includes/dashboard-footer.php" ?>

</body>
</html>