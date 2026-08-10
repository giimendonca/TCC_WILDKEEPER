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
requireNivel(100);

// Faz o SELECT dos funcionários
$sql = "SELECT 
    users.id,
    users.nome, 
    users.status,
    cargos.id AS cargo_id,
    cargos.nome AS cargo_nome
FROM users
INNER JOIN cargos ON cargos.id = users.cargo_id
WHERE users.instituicao_id = ?";

$stmt = $conexao->prepare($sql);
$stmt->bind_param("i", $_SESSION['instituicao_id']);
$stmt->execute();

$result = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Funcionários | WildKeeper</title>
</head>

<body>
    <?php include "../includes/dashboard-header.php" ?>
    <main>
        <section>
            <h1>Funcionários <?= htmlspecialchars($_SESSION['instituicao_nome']) ?></h1>

            <table border="1">
                <thead>
                    <tr>
                        <td>Nome</td>
                        <td>Cargo</td>
                        <td>Status</td>
                        <td>Ações</td>
                    </tr>
                </thead>

                <tbody>
                    <?php while ($funcionario = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?= htmlspecialchars($funcionario['nome']) ?></td>
                            <td><?= htmlspecialchars($funcionario['cargo_nome']) ?></td>
                            <td><?= htmlspecialchars($funcionario['status']) ?></td>
                            <td><a href="mostrar_funcionario.php?id=<?= $funcionario['id'] ?>">Ver informações</a></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>

            </table>
        </section>

        <a href="../funcionarios/cadastro_funcionario.php">Cadastrar Funcionário</a>
    </main>
    <?php include "../includes/dashboard-footer.php" ?>
</body>

</html>