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

requireNivel(60);

// ====================================
// Filtros
// ====================================

$titulo = trim($_GET['titulo'] ?? "");
$nomeAnimal = trim($_GET['nome_animal'] ?? "");
$nomeFuncionario = trim($_GET['nome_funcionario'] ?? "");
$nomeVacina = trim($_GET['nome_vacina'] ?? "");
$dataAplicacao = trim($_GET['data_aplicacao'] ?? "");
$proximaAplicacao = trim($_GET['proxima_aplicacao'] ?? "");

// ====================================
// SELECT
// ====================================

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

    vacinas.id AS vacina_id,
    vacinas.nome_vacina,
    vacinas.data_aplicacao,
    vacinas.proxima_aplicacao,
    vacinas.observacoes

FROM eventos

INNER JOIN animais ON eventos.animal_id = animais.id
INNER JOIN users ON eventos.funcionario_id = users.id

LEFT JOIN vacinas ON vacinas.evento_id = eventos.id

WHERE eventos.tipo = 'Vacinação'
AND eventos.instituicao_id = ?";

$params = [
    $_SESSION['instituicao_id']
];

$types = "i";

// ====================================
// Filtros
// ====================================

// Filtro do título do evento
if($titulo != ""){
    $sql .= " AND eventos.titulo LIKE ?";
    $params[] = "%$titulo%";
    $types .= "s";
}

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

// Filtro do nome da vacina
if ($nomeVacina != "") {
    $sql .= " AND vacinas.nome_vacina LIKE ?";
    $params[] = "%$nomeVacina%";
    $types .= "s";
}

// Filtro da data de aplicação
if ($dataAplicacao != "") {
    $sql .= " AND vacinas.data_aplicacao = ?";
    $params[] = $dataAplicacao;
    $types .= "s";
}

// Filtro da próxima aplicação
if ($proximaAplicacao != "") {
    $sql .= " AND vacinas.proxima_aplicacao = ?";
    $params[] = $proximaAplicacao;
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
    <title>Vacinas | WildKeeper</title>
    <link rel="stylesheet" href="../../assets/css/reset.css">
    <link rel="stylesheet" href="../../assets/css/dashboard_global.css">
    <link rel="stylesheet" href="../../assets/css/tabela.css">
    <?php include "../includes/fonte.php" ?>
</head>

<body>

    <?php include "../includes/dashboard-header.php" ?>

    <main>
        <section>

            <h1>Gerenciamento de Vacinas</h1>

            <form action="index.php" method="get">
                <label for="titulo">Título</label>
                <input type="text" data-pesquisa="titulo" data-campos="titulo" name="titulo" id="titulo" placeholder="Encontrar pelo título do evento" value="<?= htmlspecialchars($titulo) ?>">

                <label for="nome_animal">Nome Animal</label>
                <input type="text" data-pesquisa="nome_animal" data-campos="nome_animal" name="nome_animal" id="nome_animal" placeholder="Encontrar pelo nome do animal" value="<?= htmlspecialchars($nomeAnimal) ?>">

                <label for="nome_funcionario">Nome Funcionário</label>
                <input type="text" data-pesquisa="nome_funcionario" data-campos="nome_funcionario" name="nome_funcionario" id="nome_funcionario" placeholder="Encontrar pelo nome do funcionário" value="<?= htmlspecialchars($nomeFuncionario) ?>">

                <label for="nome_vacina">Nome da Vacina</label>
                <input type="text" data-pesquisa="nome_vacina" data-campos="nome_vacina" name="nome_vacina" id="nome_vacina" placeholder="Encontrar pelo nome da vacina" value="<?= htmlspecialchars($nomeVacina) ?>">

                <label for="data_aplicacao">Data da Aplicação</label>
                <input type="date" name="data_aplicacao" id="data_aplicacao" value="<?= htmlspecialchars($dataAplicacao) ?>">

                <label for="proxima_aplicacao">Próxima Aplicação</label>
                <input type="date" name="proxima_aplicacao" id="proxima_aplicacao" value="<?= htmlspecialchars($proximaAplicacao) ?>">

                <button type="submit">Pesquisar</button>

            </form>

            <table border="1" id="tabela">

                <thead>
                    <tr>
                        <th>Título</th>
                        <th>Nome Animal</th>
                        <th>Funcionário Responsável</th>
                        <th>Vacina</th>
                        <th>Data da Aplicação</th>
                        <th>Próxima Aplicação</th>
                        <th>Status</th>
                        <th>Ações</th>
                    </tr>
                </thead>

                <tbody>

                    <?php if ($result->num_rows > 0): ?>

                        <?php while ($vacina = $result->fetch_assoc()): ?>

                            <tr>
                                <td data-campo="titulo"><?= htmlspecialchars($vacina['titulo']) ?></td>

                                <td data-campo="nome_animal"><?= htmlspecialchars($vacina['animal_nome']) ?></td>

                                <td data-campo="nome_funcionario"><?= htmlspecialchars($vacina['funcionario_nome']) ?></td>

                                <td data-campo="nome_vacina"><?= $vacina['vacina_id'] ? htmlspecialchars($vacina['nome_vacina']) : "Não realizada" ?></td>

                                <td><?= $vacina['vacina_id'] ? htmlspecialchars($vacina['data_aplicacao']) : "Não realizada" ?></td>

                                <td><?= $vacina['vacina_id'] ? htmlspecialchars($vacina['proxima_aplicacao'] ?? 'Não possui') : "Não realizada" ?></td>

                                <td><?= htmlspecialchars($vacina['evento_status']) ?></td>

                                <td>

                                    <?php if (($vacina['evento_status'] == 'Agendado' || $vacina['evento_status'] == 'Em andamento') && !$vacina['vacina_id']): ?>

                                        <a href="realizar_vacina.php?evento_id=<?= $vacina['evento_id'] ?>">
                                            Realizar vacinação
                                        </a>

                                    <?php elseif ($vacina['vacina_id']): ?>

                                        <a href="mostrar_vacina.php?id=<?= $vacina['vacina_id'] ?>">
                                            Ver informações
                                        </a>

                                        <a href="editar_vacina.php?id=<?= $vacina['vacina_id'] ?>">
                                            Editar
                                        </a>

                                    <?php else: ?>

                                        <a href="../eventos/mostrar_evento.php?id=<?= $vacina['evento_id'] ?>">
                                            Ver evento
                                        </a>

                                    <?php endif; ?>

                                </td>

                            </tr>

                        <?php endwhile; ?>

                    <?php else: ?>

                        <tr>
                            <td colspan="7">Nenhuma vacinação encontrada.</td>
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