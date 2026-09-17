<?php
session_start();
include "../includes/conexao.php";
include "../includes/autenticacao.php";

if (!isset($_SESSION['id'])) {
    header("Location: ../auth/login.php");
    exit();
}

requireNivel(60);

$instituicao_id = $_SESSION['instituicao_id'];
$id = $_GET['id'] ?? null;

if (!$id || !filter_var($id, FILTER_VALIDATE_INT)) {
    die("Consulta inválida.");
}

// Busca a consulta
$sql = "SELECT * FROM consultas WHERE id = ? AND instituicao_id = ?";
$stmt = $conexao->prepare($sql);
$stmt->bind_param("ii", $id, $instituicao_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    die("Consulta não encontrada.");
}

$consulta = $result->fetch_assoc();

// Busca os animais da instituição
$sqlAnimais = "SELECT id, nome FROM animais WHERE instituicao_id = ? ORDER BY nome";
$stmtAnimais = $conexao->prepare($sqlAnimais);
$stmtAnimais->bind_param("i", $instituicao_id);
$stmtAnimais->execute();
$animais = $stmtAnimais->get_result();

// Busca os veterinários da instituição
$sqlVeterinarios = "SELECT users.id, users.nome
                    FROM users
                    INNER JOIN cargos ON users.cargo_id = cargos.id
                    WHERE users.instituicao_id = ?
                    AND cargos.nome = 'Veterinário'
                    AND users.status != 'Desligado'
                    ORDER BY users.nome";
$stmtVeterinarios = $conexao->prepare($sqlVeterinarios);
$stmtVeterinarios->bind_param("i", $instituicao_id);
$stmtVeterinarios->execute();
$veterinarios = $stmtVeterinarios->get_result();
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Consulta</title>
</head>

<body>
    <?php include "../includes/dashboard-header.php" ?>

    <main>
        <section>
            <h1>Editar Consulta</h1>

            <form action="atualizar_consulta.php" method="POST">


                <input type="hidden" name="id" value="<?= $consulta['id'] ?>">

                <label for="animal_id">Animal:</label>
                <select name="animal_id" id="animal_id" required>
                    <option value="">Selecione um animal</option>

                    <?php while ($animal = $animais->fetch_assoc()): ?>
                        <option value="<?= $animal['id'] ?>" <?= $animal['id'] == $consulta['animal_id'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($animal['nome']) ?>
                        </option>
                    <?php endwhile; ?>

                </select>

                <label for="funcionario_id">Veterinário:</label>
                <select name="funcionario_id" id="funcionario_id" required>
                    <option value="">Selecione um veterinário</option>

                    <?php while ($veterinario = $veterinarios->fetch_assoc()): ?>
                        <option value="<?= $veterinario['id'] ?>" <?= $veterinario['id'] == $consulta['funcionario_id'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($veterinario['nome']) ?>
                        </option>
                    <?php endwhile; ?>

                </select>

                <label for="data_consulta">Data da consulta:</label>
                <input type="date" name="data_consulta" id="data_consulta" value="<?= htmlspecialchars($consulta['data_consulta']) ?>" required>

                <label for="diagnostico">Diagnóstico:</label>
                <textarea name="diagnostico" id="diagnostico" required><?= htmlspecialchars($consulta['diagnostico']) ?></textarea>

                <label for="tratamento">Tratamento:</label>
                <textarea name="tratamento" id="tratamento" required><?= htmlspecialchars($consulta['tratamento']) ?></textarea>

                <label for="observacoes">Observações:</label>
                <textarea name="observacoes" id="observacoes"><?= htmlspecialchars($consulta['observacoes'] ?? '') ?></textarea>

                <label for="data_retorno">Data de retorno:</label>
                <input type="date" name="data_retorno" id="data_retorno" value="<?= htmlspecialchars($consulta['data_retorno']) ?>" required>

                <button type="submit">Salvar alterações</button>
                <a href="mostrar.php?id=<?= $consulta['id'] ?>">Cancelar</a>

            </form>
        </section>
    </main>
    <?php include "../includes/dashboard-footer.php" ?>
</body>

</html>