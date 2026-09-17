<?php

session_start();

include "../includes/conexao.php";
include "../includes/funcoes.php";
include "../includes/autenticacao.php";

// ====================================
// Verificações de sessão
// ====================================

if (!isset($_SESSION['id'])) {
    header("Location: ../auth/login.php");
    exit();
}

// Verifica a permissão que o usuário possui
requireNivel(20);

// ====================================
// Filtros
// ====================================

$tituloFiltrado = trim($_GET['titulo'] ?? "");
$tipoFiltrado = trim($_GET['tipo'] ?? "");
$statusFiltrado = trim($_GET['status'] ?? "");

// ====================================
// SELECT dos eventos
// ====================================

$sql = "SELECT
    eventos.id,
    eventos.titulo,
    eventos.descricao,
    eventos.tipo,
    eventos.data_inicio,
    eventos.data_fim,
    eventos.status,
    eventos.animal_id,
    animais.nome AS animal_nome,
    eventos.funcionario_id,
    users.nome AS funcionario_nome
FROM eventos
LEFT JOIN animais ON animais.id = eventos.animal_id
INNER JOIN users ON users.id = eventos.funcionario_id
WHERE eventos.instituicao_id = ?";

$params = [
    $_SESSION['instituicao_id']
];

$types = "i";

// Filtro do título
if ($tituloFiltrado != "") {
    $sql .= " AND eventos.titulo LIKE ?";
    $params[] = "%$tituloFiltrado%";
    $types .= "s";
}

// Filtro do tipo
if ($tipoFiltrado != "") {
    $sql .= " AND eventos.tipo = ?";
    $params[] = $tipoFiltrado;
    $types .= "s";
}

// Filtro do status
if ($statusFiltrado != "") {
    $sql .= " AND eventos.status = ?";
    $params[] = $statusFiltrado;
    $types .= "s";
}

$sql .= " ORDER BY eventos.data_inicio DESC";

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
    <title>Eventos | WildKeeper</title>
</head>
<body>

    <?php include "../includes/dashboard-header.php" ?>

    <main>
        <section>

            <h1>Gerenciamento de Eventos</h1>

            <p>Consulte, pesquise e acompanhe os eventos cadastrados na instituição.</p>

            <?php if (nivelMinimo(40)): ?>
                <a href="cadastrar_evento.php">Cadastrar Evento</a>
            <?php endif; ?>

            <form action="index.php" method="get">

                <label for="titulo">Título</label>
                <input type="text" name="titulo" id="titulo" placeholder="Encontrar pelo título" value="<?= htmlspecialchars($tituloFiltrado) ?>">

                <label for="tipo">Tipo</label>
                <select name="tipo" id="tipo">
                    <option value="">Todos</option>
                    <option value="Consulta" <?= $tipoFiltrado == "Consulta" ? "selected" : "" ?>>Consulta</option>
                    <option value="Vacinação" <?= $tipoFiltrado == "Vacinação" ? "selected" : "" ?>>Vacinação</option>
                    <option value="Alimentação" <?= $tipoFiltrado == "Alimentação" ? "selected" : "" ?>>Alimentação</option>
                    <option value="Manutenção" <?= $tipoFiltrado == "Manutenção" ? "selected" : "" ?>>Manutenção</option>
                    <option value="Transferência" <?= $tipoFiltrado == "Transferência" ? "selected" : "" ?>>Transferência</option>
                    <option value="Outro" <?= $tipoFiltrado == "Outro" ? "selected" : "" ?>>Outro</option>
                </select>

                <label for="status">Status</label>
                <select name="status" id="status">
                    <option value="">Todos</option>
                    <option value="Agendado" <?= $statusFiltrado == "Agendado" ? "selected" : "" ?>>Agendado</option>
                    <option value="Em andamento" <?= $statusFiltrado == "Em andamento" ? "selected" : "" ?>>Em andamento</option>
                    <option value="Concluído" <?= $statusFiltrado == "Concluído" ? "selected" : "" ?>>Concluído</option>
                    <option value="Cancelado" <?= $statusFiltrado == "Cancelado" ? "selected" : "" ?>>Cancelado</option>
                </select>

                <button type="submit">Pesquisar</button>

            </form>

            <table border="1">

                <thead>
                    <tr>
                        <th>Título</th>
                        <th>Tipo</th>
                        <th>Início</th>
                        <th>Fim</th>
                        <th>Animal</th>
                        <th>Funcionário</th>
                        <th>Status</th>
                        <th>Ações</th>
                    </tr>
                </thead>

                <tbody>

                    <?php if ($result->num_rows > 0): ?>

                        <?php while ($evento = $result->fetch_assoc()): ?>

                            <tr>

                                <td><?= htmlspecialchars($evento['titulo']) ?></td>

                                <td><?= htmlspecialchars($evento['tipo']) ?></td>

                                <td><?= date("d/m/Y H:i", strtotime($evento['data_inicio'])) ?></td>

                                <td><?= date("d/m/Y H:i", strtotime($evento['data_fim'])) ?></td>

                                <td>
                                    <?= $evento['animal_nome'] ? htmlspecialchars($evento['animal_nome']) : "Evento geral" ?>
                                </td>

                                <td><?= htmlspecialchars($evento['funcionario_nome']) ?></td>

                                <td><?= htmlspecialchars($evento['status']) ?></td>

                                <td>
                                    <a href="mostrar_evento.php?id=<?= $evento['id'] ?>">Ver informações</a>
                                </td>

                            </tr>

                        <?php endwhile; ?>

                    <?php else: ?>

                        <tr>
                            <td colspan="8">Nenhum evento encontrado.</td>
                        </tr>

                    <?php endif; ?>

                </tbody>

            </table>

        </section>
    </main>

    <?php include "../includes/dashboard-footer.php" ?>

</body>
</html>