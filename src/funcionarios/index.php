<?php
session_start();

include "../includes/conexao.php";
include "../includes/funcoes.php";
include "../includes/autenticacao.php";

// ====================================
// Verificações de sessão
// ====================================

// Verifica se ja existe uma sessão
if (!isset($_SESSION['id'])) {
    header("Location: ../auth/login.php");
    exit();
}

// Verifica a permissão que o usuário possui
requireNivel(100);

// ====================================
// Filtros
// ====================================

// Pega os filtros enviados pelo metódo GET
$nomeFiltrado = trim($_GET['nome'] ?? "");
$cargoFiltrado = trim($_GET['cargo'] ?? "");
$statusFiltrado = trim($_GET['status'] ?? "Ativo");

// Pega os cargos existentes
$cargos = selectTabela($conexao, "cargos");

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

// Declara os parâmetros e tipos iniciais da pesquisa
$params = [
    $_SESSION['instituicao_id']
];
$types = "i";

// Verifica quais filtros foram enviados
// Filtro do nome
if($nomeFiltrado != ""){
    $sql .= " AND users.nome LIKE ?";
    $params[] = "%$nomeFiltrado%";
    $types .= "s";
}

// Filtro do cargo
if($cargoFiltrado != ""){
    $sql .= " AND users.cargo_id = ?";
    $params[] = $cargoFiltrado;
    $types .= "i";
}

// Filtro do status
$sql .= " AND status = ?";
$params[] = $statusFiltrado;
$types .= "s";

$stmt = $conexao->prepare($sql);
$stmt->bind_param($types, ...$params);
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

            <form action="index.php" method="get">
                <label for="nome">Nome</label>
                <input type="text" name="nome" id="nome" placeholder="Pesquisar por nome">

                <label for="cargo">Cargo</label>
                <select name="cargo" id="cargo">
                    <option value="">Todos</option>
                    <?php while($cargo = $cargos->fetch_assoc()): ?>
                        <option value="<?= $cargo['id'] ?>" <?= ($cargoFiltrado == $cargo['id']) ? "selected" : "" ?>><?= htmlspecialchars($cargo['nome']) ?></option>
                    <?php endwhile; ?>
                </select>

                <label for="status">Status</label>
                <select name="status" id="status">
                    <option value="Ativo" <?= $statusFiltrado === 'Ativo' ? 'selected' : '' ?>>Ativo</option>
                    <option value="Férias" <?= $statusFiltrado === 'Férias' ? 'selected' : '' ?>>Férias</option>
                    <option value="Afastado" <?= $statusFiltrado === 'Afastado' ? 'selected' : '' ?>>Afastado</option>
                    <option value="Desligado" <?= $statusFiltrado === 'Desligado' ? 'selected' : '' ?>>Desligado</option>
                </select>

                <button type="submit">Pesquisar</button>
            </form>

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