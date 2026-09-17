<?php

session_start();

include "../includes/conexao.php";

include "../includes/autenticacao.php";

// Verifica se ja existe uma sessão
if (!isset($_SESSION['id'])) {

    header("Location: ../auth/login.php");

    exit();

}

// Verifica a permissão que o usuário possui
requireNivel(60);

// Pega o id da consulta
$consultaId = trim($_GET['id'] ?? '');

// Verifica se o id veio vazio
if (empty($consultaId)) {

    die("ID inválido.");

}

// Faz o SELECT da consulta
$sql = "SELECT 
    consultas.id,
    consultas.data_consulta,
    consultas.diagnostico,
    consultas.tratamento,
    consultas.observacoes,
    consultas.data_retorno,
    animais.id AS animal_id,
    animais.nome AS animal_nome,
    users.id AS funcionario_id,
    users.nome AS funcionario_nome
FROM consultas
INNER JOIN animais ON consultas.animal_id = animais.id
INNER JOIN users ON consultas.funcionario_id = users.id
WHERE consultas.id = ? AND consultas.instituicao_id = ?";

$stmt = $conexao->prepare($sql);

$stmt->bind_param("ii", $consultaId, $_SESSION['instituicao_id']);

$stmt->execute();

$result = $stmt->get_result();

$consulta = $result->fetch_assoc();

// Verifica se a consulta foi encontrada
if (!$consulta) {

    die("Consulta não encontrada.");

}

// Busca os medicamentos associados à consulta
$sqlMedicamentos = "SELECT 
    medicamentos.nome,
    medicamentos_consulta.dosagem,
    medicamentos_consulta.observacoes
FROM medicamentos_consulta
INNER JOIN medicamentos ON medicamentos_consulta.medicamento_id = medicamentos.id
WHERE medicamentos_consulta.consulta_id = ?";

$stmtMedicamentos = $conexao->prepare($sqlMedicamentos);

$stmtMedicamentos->bind_param("i", $consultaId);

$stmtMedicamentos->execute();

$resultMedicamentos = $stmtMedicamentos->get_result();

?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Consulta | WildKeeper</title>
</head>
<body>

    <?php include "../includes/dashboard-header.php" ?>

    <main>

        <section>

            <article>

                <div>

                    <h1>Consulta #<?= htmlspecialchars($consulta['id']) ?></h1>

                    <p>Animal: <?= htmlspecialchars($consulta['animal_nome']) ?></p>

                    <p>Veterinário Responsável: <?= htmlspecialchars($consulta['funcionario_nome']) ?></p>

                </div>

            </article>

            <article>

                <h2>Informações da Consulta</h2>

                <p>
                    <strong>Data da consulta:</strong>
                    <?= htmlspecialchars($consulta['data_consulta']) ?>
                </p>

                <p>
                    <strong>Data de retorno:</strong>
                    <?= htmlspecialchars($consulta['data_retorno']) ?>
                </p>

            </article>

            <article>

                <h2>Diagnóstico</h2>

                <p><?= nl2br(htmlspecialchars($consulta['diagnostico'])) ?></p>

            </article>

            <article>

                <h2>Tratamento</h2>

                <p><?= nl2br(htmlspecialchars($consulta['tratamento'])) ?></p>

            </article>

            <article>

                <h2>Observações</h2>

                <?php if (!empty($consulta['observacoes'])): ?>

                    <p><?= nl2br(htmlspecialchars($consulta['observacoes'])) ?></p>

                <?php else: ?>

                    <p>Nenhuma observação registrada.</p>

                <?php endif; ?>

            </article>

            <article>

                <h2>Medicamentos</h2>

                <?php if ($resultMedicamentos->num_rows > 0): ?>

                    <table border="1">

                        <thead>

                            <tr>

                                <th>Medicamento</th>

                                <th>Dosagem</th>

                                <th>Observações</th>

                            </tr>

                        </thead>

                        <tbody>

                            <?php while ($medicamento = $resultMedicamentos->fetch_assoc()): ?>

                                <tr>

                                    <td><?= htmlspecialchars($medicamento['nome']) ?></td>

                                    <td><?= htmlspecialchars($medicamento['dosagem']) ?></td>

                                    <td>
                                        <?= htmlspecialchars($medicamento['observacoes'] ?? '') ?>
                                    </td>

                                </tr>

                            <?php endwhile; ?>

                        </tbody>

                    </table>

                <?php else: ?>

                    <p>Nenhum medicamento associado a esta consulta.</p>

                <?php endif; ?>

            </article>

            <div>

                <?php if (nivelMinimo(60)): ?>

                    <a href="editar_consulta.php?id=<?= htmlspecialchars($consultaId) ?>">
                        Editar Consulta
                    </a>

                <?php endif; ?>

                <a href="index.php">Voltar</a>

            </div>

        </section>

    </main>

    <?php include "../includes/dashboard-footer.php" ?>

</body>
</html>