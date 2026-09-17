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

// Busca os animais da instituição
$sqlAnimais = "SELECT id, nome FROM animais WHERE instituicao_id = ? ORDER BY nome";
$stmtAnimais = $conexao->prepare($sqlAnimais);
$stmtAnimais->bind_param("i", $instituicao_id);
$stmtAnimais->execute();
$animais = $stmtAnimais->get_result();

// Busca somente veterinários ativos da instituição
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
    <title>Cadastrar Consulta</title>
</head>

<body>
    <?php include "../includes/dashboard-header.php" ?>

    <main>
        <section>

            <h1>Cadastrar Consulta</h1>

            <form action="salvar_consulta.php" method="POST">

                <label for="animal_id">Animal:</label>
                <select name="animal_id" id="animal_id" required>
                    <option value="">Selecione um animal</option>

                    <?php while ($animal = $animais->fetch_assoc()): ?>
                        <option value="<?= $animal['id'] ?>">
                            <?= htmlspecialchars($animal['nome']) ?>
                        </option>
                    <?php endwhile; ?>

                </select>

                <label for="funcionario_id">Veterinário:</label>
                <select name="funcionario_id" id="funcionario_id" required>
                    <option value="">Selecione um veterinário</option>

                    <?php while ($veterinario = $veterinarios->fetch_assoc()): ?>
                        <option value="<?= $veterinario['id'] ?>">
                            <?= htmlspecialchars($veterinario['nome']) ?>
                        </option>
                    <?php endwhile; ?>

                </select>

                <label for="data_consulta">Data da consulta:</label>
                <input type="date" name="data_consulta" id="data_consulta" required>

                <label for="diagnostico">Diagnóstico:</label>
                <textarea name="diagnostico" id="diagnostico" placeholder="Detalhe aqui o diagnóstico do paciente" required></textarea>

                <label for="tratamento">Tratamento:</label>
                <textarea name="tratamento" id="tratamento" placeholder="Detalhe aqui o tratamento que o paciente precisa" required></textarea>

                <label for="observacoes">Observações:</label>
                <textarea name="observacoes" id="observacoes" placeholder="Digite aqui as observções"></textarea>

                <label for="data_retorno">Data de retorno:</label>
                <input type="date" name="data_retorno" id="data_retorno" required>

                <button type="submit">Cadastrar</button>
                <a href="index.php">Cancelar</a>

            </form>
        </section>
    </main>
    <?php include "../includes/dashboard-footer.php" ?>
</body>

</html>