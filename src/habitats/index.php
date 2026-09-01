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
requireNivel(40);

// ====================================
// Filtros
// ====================================

// Pega os filtros enviados pelo metódo GET
$nomeFiltrado = trim($_GET['nome'] ?? "");
$biomaFiltrado = trim($_GET['bioma'] ?? "");
$capacidadeFiltrada = trim($_GET['capacidade'] ?? "");
$statusFiltrado = trim($_GET['status'] ?? "");

// Faz o SELECT dos Habitats
$sql = "SELECT 
    id,
    nome, 
    bioma,
    capacidade, 
    status
FROM habitats
WHERE instituicao_id = ?";

// Declara os parâmetros e tipos iniciais da pesquisa
$params = [
    $_SESSION['instituicao_id']
];
$types = "i";

// Verifica quais filtros foram enviados
// Filtro do nome
if ($nomeFiltrado != "") {
    $sql .= " AND nome LIKE ?";
    $params[] = "%$nomeFiltrado%";
    $types .= "s";
}

// Filtro do bioma
if ($biomaFiltrado != "") {
    $sql .= " AND bioma LIKE ?";
    $params[] = "%$biomaFiltrado%";
    $types .= "s";
}

// Filtro da capacidade
if ($capacidadeFiltrada != "") {
    $sql .= " AND capacidade >= ?";
    $params[] = $capacidadeFiltrada;
    $types .= "i";
}

// Filtro do status
if ($statusFiltrado != "") {
    $sql .= " AND status = ?";
    $params[] = $statusFiltrado;
    $types .= "s";
}

$stmt = $conexao->prepare($sql);
if (!empty($types) && !empty($params)) {
    $stmt->bind_param($types, ...$params);
}
$stmt->execute();

$result = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Habitats | WildKeeper</title>
</head>

<body>
    <?php include "../includes/dashboard-header.php" ?>
    <main>
        <section>
            <h1>Gerenciamento de Habitats</h1>

            <form action="index.php" method="get">
                <label for="nome">Nome</label>
                <input type="text" name="nome" id="nome" placeholder="Pesquisar por nome do habitat" value="<?= htmlspecialchars($nomeFiltrado) ?>">

                <label for="bioma">Bioma</label>
                <input type="text" name="bioma" id="bioma" placeholder="Pesquisar por bioma" value="<?= htmlspecialchars($biomaFiltrado) ?>">

                <label for="capacidade">Capacidade</label>
                <input type="number" name="capacidade" id="capacidade" placeholder="Pesquisar por quantidade mínima" value="<?= htmlspecialchars($capacidadeFiltrada) ?>">

                <label for="status">Status</label>
                <select name="status" id="status">
                    <option value="" <?= $statusFiltrado === '' ? 'selected' : '' ?>>Todos</option>
                    <option value="Ativo" <?= $statusFiltrado === 'Ativo' ? 'selected' : '' ?>>Ativo</option>
                    <option value="Em manutenção" <?= $statusFiltrado === 'Em manutenção' ? 'selected' : '' ?>>Em manutenção</option>
                    <option value="Interditado" <?= $statusFiltrado === 'Interditado' ? 'selected' : '' ?>>Interditado</option>
                    
                </select>

                <button type="submit">Pesquisar</button>
            </form>

            <table border="1">
                <thead>
                    <tr>
                        <th>Nome</th>
                        <th>Bioma</th>
                        <th>Capacidade</th>
                        <th>Status</th>
                        <th>Ações</th>
                    </tr>
                </thead>

                <tbody>
                    <?php if ($result->num_rows > 0): ?>
                        <?php while ($habitat = $result->fetch_assoc()): ?>
                            <tr>
                                <td><?= htmlspecialchars($habitat['nome']) ?></td>
                                <td><?= htmlspecialchars($habitat['bioma']) ?></td>
                                <td><?= htmlspecialchars($habitat['capacidade']) ?></td>
                                <td><?= htmlspecialchars($habitat['status']) ?></td>

                                <td><a href="mostrar_habitat.php?id=<?= $habitat['id'] ?>">Ver informações</a></td>
                            </tr>
                        <?php endwhile; ?>

                    <?php else: ?>
                        <tr>
                            <td colspan="5">Nenhum habitat encontrado.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>

            </table>
        </section>

        <a href="cadastrar_habitat.php">Cadastrar Habitat</a>
    </main>
    <?php include "../includes/dashboard-footer.php" ?>
</body>

</html>