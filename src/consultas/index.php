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
requireNivel(60);

// ====================================
// Filtros
// ====================================

// Pega os filtros enviados pelo metódo GET
$nomeAnimal = trim($_GET['nome_animal'] ?? "");
$nomeFuncionario = trim($_GET['nome_funcionario'] ?? "");
$dataConsulta = trim($_GET['data_consulta'] ?? "");
$dataRetorno = trim($_GET['data_retorno'] ?? "");

// Faz o SELECT dos consultas
$sql = "SELECT 
    consultas.id,
    consultas.data_consulta,
    consultas.diagnostico,
    
    consultas.data_retorno,

    animais.id AS animal_id,
    animais.nome AS animal_nome,

    users.id AS funcionario_id,
    users.nome AS funcionario_nome
FROM consultas
INNER JOIN animais ON consultas.animal_id = animais.id
INNER JOIN users ON consultas.funcionario_id = users.id
WHERE 1=1 AND consultas.instituicao_id = ?";

// Declara os parâmetros e tipos iniciais da pesquisa
$params = [
    $_SESSION['instituicao_id']
];
$types = "s";

// Verifica quais filtros foram enviados
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
    $sql .= " AND consultas.data_retorno LIKE ?";
    $params[] = "$dataRetorno";
    $types .= "s";
}

// Filtro da data da consulta
if ($dataConsulta != "") {
    $sql .= " AND consultas.data_consulta LIKE ?";
    $params[] = "$dataConsulta";
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
    <title>Consultas | WildKeeper</title>
</head>

<body>
    <?php include "../includes/dashboard-header.php" ?>
    <main>
        <section>
            <h1>Gerenciamento de Consultas</h1>

            <form action="index.php" method="get">
                <label for="nome_animal">Nome Animal</label>
                <input type="text" name="nome_animal" id="nome_animal" placeholder="Encontrar pelo nome do animal" value="<?= htmlspecialchars($nomeAnimal) ?>">
                
                <label for="nome_funcionario">Nome Funcionário</label>
                <input type="text" name="nome_funcionario" id="nome_funcionario" placeholder="Encontrar pelo nome do funcionário" value="<?= htmlspecialchars($nomeFuncionario) ?>">

                <label for="data_consulta">Data da Consulta</label>
                <input type="date" name="data_consulta" id="data_consulta" placeholder="Encontar por data da consulta" value="<?= htmlspecialchars($dataConsulta) ?>">

                <label for="data_retorno">Data da Retorno</label>
                <input type="date" name="data_retorno" id="data_retorno" placeholder="Encontar por data de retorno" value="<?= htmlspecialchars($dataRetorno) ?>">
                
                <button type="submit">Pesquisar</button>
            </form>

            <table border="1">
                <thead>
                    <tr>
                        <th>Nome Animal</th>
                        <th>Funcionário Responsável</th>
                        <th>Data da Consulta</th>
                        <th>Diagnóstico</th>
                        <th>Data de Retorno</th>
                        <th>Ações</th>
                    </tr>
                </thead>

                <tbody>
                    <?php if ($result->num_rows > 0): ?>
                        <?php while ($consulta = $result->fetch_assoc()): ?>
                            <tr>
                                <td><?= htmlspecialchars($consulta['animal_nome']) ?></td>
                                <td><?= htmlspecialchars($consulta['funcionario_nome']) ?></td>
                                <td><?= htmlspecialchars($consulta['data_consulta']) ?></td>
                                <td><?= htmlspecialchars($consulta['diagnostico']) ?></td>
                                <td><?= htmlspecialchars($consulta['data_retorno']) ?></td>
                                <td><a href="mostrar_consulta.php?id=<?= $consulta['id'] ?>">Ver informações</a> <a href="editar_consulta.php?id=<?= $consulta['id'] ?>">Editar</a></td>
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

        <a href="../consultas/cadastrar_consulta.php">Cadastrar Consulta</a>
    </main>
    <?php include "../includes/dashboard-footer.php" ?>
</body>

</html>