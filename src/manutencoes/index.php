<?php

session_start();

include "../includes/conexao.php";
include "../includes/funcoes.php";
include "../includes/autenticacao.php";

if (!isset($_SESSION['id'])) {
    header("Location: ../auth/login.php");
    exit();
}

requireNivel(40);

// ====================================
// Filtros
// ====================================

$titulo = trim($_GET['titulo'] ?? "");
$nomeHabitat = trim($_GET['nome_habitat'] ?? "");
$nomeFuncionario = trim($_GET['nome_funcionario'] ?? "");
$dataManutencao = trim($_GET['data_manutencao'] ?? "");
$status = trim($_GET['status'] ?? "");

// ====================================
// SELECT
// ====================================

$sql = "SELECT
    eventos.id AS evento_id,
    eventos.titulo,
    eventos.descricao AS evento_descricao,
    eventos.data_inicio,
    eventos.data_fim,
    eventos.status AS evento_status,

    habitats.id AS habitat_id,
    habitats.nome AS habitat_nome,

    users.id AS funcionario_id,
    users.nome AS funcionario_nome,

    manutencao_habitats.id AS manutencao_id,
    manutencao_habitats.descricao AS manutencao_descricao

FROM eventos

INNER JOIN habitats ON eventos.habitat_id = habitats.id
INNER JOIN users ON eventos.funcionario_id = users.id

LEFT JOIN manutencao_habitats
    ON manutencao_habitats.evento_id = eventos.id

WHERE eventos.tipo = 'Manutenção'
AND eventos.instituicao_id = ?";

$params = [
    $_SESSION['instituicao_id']
];

$types = "i";

// ====================================
// Filtros
// ====================================

// Filtro do título
if ($titulo != "") {
    $sql .= " AND eventos.titulo LIKE ?";
    $params[] = "%$titulo%";
    $types .= "s";
}

// Filtro do habitat
if ($nomeHabitat != "") {
    $sql .= " AND habitats.nome LIKE ?";
    $params[] = "%$nomeHabitat%";
    $types .= "s";
}

// Filtro do funcionário
if ($nomeFuncionario != "") {
    $sql .= " AND users.nome LIKE ?";
    $params[] = "%$nomeFuncionario%";
    $types .= "s";
}

// Filtro da data
if ($dataManutencao != "") {
    $sql .= " AND DATE(eventos.data_inicio) = ?";
    $params[] = $dataManutencao;
    $types .= "s";
}

// Filtro do status
if ($status != "") {
    $sql .= " AND eventos.status = ?";
    $params[] = $status;
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
    <title>Manutenções | WildKeeper</title>

    <link rel="stylesheet" href="../../assets/css/reset.css">
    <link rel="stylesheet" href="../../assets/css/dashboard_global.css">
    <link rel="stylesheet" href="../../assets/css/tabela.css">

    <?php include "../includes/fonte.php" ?>
</head>

<body>

    <?php include "../includes/dashboard-header.php" ?>

    <main>
        <section>

            <h1>Gerenciamento de Manutenções</h1>

            <form action="index.php" method="get">

                <label for="titulo">Título</label>
                <input type="text" data-pesquisa="titulo" data-campos="titulo" name="titulo" id="titulo" placeholder="Encontrar pelo título do evento" value="<?= htmlspecialchars($titulo) ?>">

                <label for="nome_habitat">Habitat</label>
                <input type="text" data-pesquisa="nome_habitat" data-campos="nome_habitat" name="nome_habitat" id="nome_habitat" placeholder="Encontrar pelo nome do habitat" value="<?= htmlspecialchars($nomeHabitat) ?>">

                <label for="nome_funcionario">Funcionário</label>
                <input type="text" data-pesquisa="nome_funcionario" data-campos="nome_funcionario" name="nome_funcionario" id="nome_funcionario" placeholder="Encontrar pelo funcionário responsável" value="<?= htmlspecialchars($nomeFuncionario) ?>">

                <label for="data_manutencao">Data da Manutenção</label>
                <input type="date" name="data_manutencao" id="data_manutencao" value="<?= htmlspecialchars($dataManutencao) ?>">

                <label for="status">Status</label>
                <select name="status" id="status">
                    <option value="">Todos</option>
                    <option value="Agendado" <?= $status == 'Agendado' ? 'selected' : '' ?>>Agendado</option>
                    <option value="Em andamento" <?= $status == 'Em andamento' ? 'selected' : '' ?>>Em andamento</option>
                    <option value="Concluído" <?= $status == 'Concluído' ? 'selected' : '' ?>>Concluído</option>
                    <option value="Cancelado" <?= $status == 'Cancelado' ? 'selected' : '' ?>>Cancelado</option>
                </select>

                <button type="submit">Pesquisar</button>

            </form>

            <table border="1" id="tabela">

                <thead>
                    <tr>
                        <th>Título</th>
                        <th>Habitat</th>
                        <th>Funcionário Responsável</th>
                        <th>Data da Manutenção</th>
                        <th>Status</th>
                        <th>Descrição</th>
                        <th>Ações</th>
                    </tr>
                </thead>

                <tbody>

                    <?php if ($result->num_rows > 0): ?>

                        <?php while ($manutencao = $result->fetch_assoc()): ?>

                            <tr>

                                <td data-campo="titulo">
                                    <?= htmlspecialchars($manutencao['titulo']) ?>
                                </td>

                                <td data-campo="nome_habitat">
                                    <?= htmlspecialchars($manutencao['habitat_nome']) ?>
                                </td>

                                <td data-campo="nome_funcionario">
                                    <?= htmlspecialchars($manutencao['funcionario_nome']) ?>
                                </td>

                                <td>
                                    <?= htmlspecialchars($manutencao['data_inicio']) ?>
                                </td>

                                <td>
                                    <?= htmlspecialchars($manutencao['evento_status']) ?>
                                </td>

                                <td>
                                    <?php if ($manutencao['manutencao_id']): ?>
                                        <?= htmlspecialchars($manutencao['manutencao_descricao']) ?>
                                    <?php else: ?>
                                        Não realizada
                                    <?php endif; ?>
                                </td>

                                <td>

                                    <?php if ($manutencao['evento_status'] == 'Agendado' && !$manutencao['manutencao_id']): ?>

                                        <a href="realizar_manutencao.php?evento_id=<?= $manutencao['evento_id'] ?>">
                                            Realizar manutenção
                                        </a>

                                    <?php elseif ($manutencao['manutencao_id']): ?>

                                        <a href="mostrar_manutencao.php?id=<?= $manutencao['manutencao_id'] ?>">
                                            Ver informações
                                        </a>

                                        <a href="editar_manutencao.php?id=<?= $manutencao['manutencao_id'] ?>">
                                            Editar
                                        </a>

                                    <?php else: ?>

                                        <a href="../eventos/mostrar_evento.php?id=<?= $manutencao['evento_id'] ?>">
                                            Ver evento
                                        </a>

                                    <?php endif; ?>

                                </td>

                            </tr>

                        <?php endwhile; ?>

                    <?php else: ?>

                        <tr>
                            <td colspan="7">Nenhuma manutenção encontrada.</td>
                        </tr>

                    <?php endif; ?>

                </tbody>

            </table>

        </section>
    </main>

    <?php include "../includes/dashboard-footer.php" ?>

    <script src="../../assets/js/tabela.js"></script>

</body>

</html>