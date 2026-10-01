<?php
session_start();

include "../includes/conexao.php";
include "../includes/funcoes.php";
include "../includes/autenticacao.php";

// ====================================
// Verificações de sessão
// ====================================

// Verifica se já existe uma sessão
if (!isset($_SESSION['id'])) {
    header("Location: ../auth/login.php");
    exit();
}

// Verifica a permissão que o usuário possui
requireNivel(60);

// ====================================
// Filtros
// ====================================

// Pega os filtros enviados pelo método GET
$nomeAnimal = trim($_GET['nome_animal'] ?? "");
$nomeFuncionario = trim($_GET['nome_funcionario'] ?? "");
$dataConsulta = trim($_GET['data_consulta'] ?? "");
$dataRetorno = trim($_GET['data_retorno'] ?? "");

// ====================================
// SELECT
// ====================================

// Busca os eventos do tipo Consulta e,
// quando a consulta já foi realizada,
// busca também os dados da tabela consultas.
$sql = "SELECT 
    eventos.id AS evento_id,
    eventos.titulo,
    eventos.data_inicio,
    eventos.data_fim,
    eventos.status AS evento_status,

    animais.id AS animal_id,
    animais.nome AS animal_nome,

    users.id AS funcionario_id,
    users.nome AS funcionario_nome,

    consultas.id AS consulta_id,
    consultas.diagnostico,
    consultas.data_retorno

FROM eventos

INNER JOIN animais ON eventos.animal_id = animais.id
INNER JOIN users ON eventos.funcionario_id = users.id

LEFT JOIN consultas ON consultas.evento_id = eventos.id

WHERE eventos.tipo = 'Consulta'
AND eventos.instituicao_id = ?";

// Declara os parâmetros e tipos iniciais da pesquisa
$params = [
    $_SESSION['instituicao_id']
];
$types = "i";

// ====================================
// Filtros
// ====================================

// Filtro do nome do animal
if ($nomeAnimal != "") {
    $sql .= " AND animais.nome LIKE ?";
    $params[] = "%$nomeAnimal%";
    $types .= "s";
}

// Filtro do nome do funcionário
if ($nomeFuncionario != "") {
    $sql .= " AND users.nome LIKE ?";
    $params[] = "%$nomeFuncionario%";
    $types .= "s";
}

// Filtro da data de retorno
if ($dataRetorno != "") {
    $sql .= " AND consultas.data_retorno = ?";
    $params[] = $dataRetorno;
    $types .= "s";
}

// Filtro da data da consulta
if ($dataConsulta != "") {
    $sql .= " AND DATE(eventos.data_inicio) = ?";
    $params[] = $dataConsulta;
    $types .= "s";
}

$sql .= " ORDER BY eventos.data_inicio DESC";

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
    <title>Consultas | WildKeeper</title>
    <link rel="stylesheet" href="../../assets/css/reset.css">
    <link rel="stylesheet" href="../../assets/css/dashboard_global.css">
    <link rel="stylesheet" href="../../assets/css/tabela.css">
    <?php include "../includes/fonte.php" ?>
</head>

<body>
    <?php include "../includes/dashboard-header.php" ?>

    <main>
        <section>
            <h1>Gerenciamento de Consultas</h1>

            <form action="index.php" method="get">
                <label for="nome_animal">Nome Animal</label>
                <input type="text" data-pesquisa="nome_animal" data-campos="nome_animal" name="nome_animal" id="nome_animal" placeholder="Encontrar pelo nome do animal" value="<?= htmlspecialchars($nomeAnimal) ?>">

                <label for="nome_funcionario">Nome Funcionário</label>
                <input type="text" data-pesquisa="nome_funcionario" data-campos="nome_funcionario" name="nome_funcionario" id="nome_funcionario" placeholder="Encontrar pelo nome do funcionário" value="<?= htmlspecialchars($nomeFuncionario) ?>">

                <label for="data_consulta">Data da Consulta</label>
                <input type="date" name="data_consulta" id="data_consulta" value="<?= htmlspecialchars($dataConsulta) ?>">

                <label for="data_retorno">Data de Retorno</label>
                <input type="date" name="data_retorno" id="data_retorno" value="<?= htmlspecialchars($dataRetorno) ?>">

                <button type="submit">Pesquisar</button>
            </form>

            <table border="1" id="tabela">
                <thead>
                    <tr>
                        <th>Nome Animal</th>
                        <th>Funcionário Responsável</th>
                        <th>Data da Consulta</th>
                        <th>Status</th>
                        <th>Diagnóstico</th>
                        <th>Data de Retorno</th>
                        <th>Ações</th>
                    </tr>
                </thead>

                <tbody>
                    <?php if ($result->num_rows > 0): ?>
                        <?php while ($consulta = $result->fetch_assoc()): ?>
                            <tr>
                                <td data-campo="nome_animal"><?= htmlspecialchars($consulta['animal_nome']) ?></td>

                                <td data-campo="nome_funcionario"><?= htmlspecialchars($consulta['funcionario_nome']) ?></td>

                                <td><?= htmlspecialchars($consulta['data_inicio']) ?></td>

                                <td><?= htmlspecialchars($consulta['evento_status']) ?></td>

                                <td><?= $consulta['consulta_id'] ? htmlspecialchars($consulta['diagnostico']) : "Não realizada" ?></td>

                                <td><?= htmlspecialchars($consulta['data_retorno'] ?? 'Não possui') ?></td>

                                <td>
                                    <?php if ($consulta['evento_status'] == 'Agendado' && !$consulta['consulta_id']): ?>
                                        <a href="realizar_consulta.php?evento_id=<?= $consulta['evento_id'] ?>">Realizar consulta</a>

                                    <?php elseif ($consulta['consulta_id']): ?>
                                        <a href="mostrar_consulta.php?id=<?= $consulta['consulta_id'] ?>">Ver informações</a>
                                        <a href="editar_consulta.php?id=<?= $consulta['consulta_id'] ?>">Editar</a>

                                    <?php else: ?>
                                        <a href="../eventos/mostrar_evento.php?id=<?= $consulta['evento_id'] ?>">Ver evento</a>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endwhile; ?>

                    <?php else: ?>
                        <tr>
                            <td colspan="7">Nenhuma consulta encontrada.</td>
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