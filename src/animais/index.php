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
requireNivel(20);

// ====================================
// Filtros
// ====================================

// Pega os filtros enviados pelo metódo GET
$nomeFiltrado = trim($_GET['nome'] ?? "");
$especieFiltrada = trim($_GET['especie'] ?? "");
$habitatFiltrado = trim($_GET['habitat'] ?? "");
$statusAnimalFiltrado = trim($_GET['status_animal'] ?? "");
$saudeStatusFiltrado = trim($_GET['saude_status'] ?? "");

// Pega os filtros que são de outras tabelas
$especies = selectTabela($conexao, "especies");
$habitats = selectTabela($conexao, "habitats");
$statusAnimais = selectTabela($conexao, "status_animais");
$saudeStatus = selectTabela($conexao, "saude_status");

// Faz o SELECT dos Animais
$sql = "SELECT 
    animais.id,
    animais.nome,

    especies.id AS especie_id,
    especies.nome_popular AS especie_nome,

    animais.sexo,
    animais.data_nascimento,

    habitats.id AS habitat_id,
    habitats.nome AS habitat_nome,

    status_animais.id AS status_animal_id,
    status_animais.nome AS status_animal_nome,

    saude_status.id AS saude_status_id,
    saude_status.nome AS saude_status_nome

FROM animais
INNER JOIN especies ON especies.id = animais.especie_id
INNER JOIN habitats ON habitats.id = animais.habitat_id
INNER JOIN status_animais ON status_animais.id = animais.status_id
INNER JOIN saude_status ON saude_status.id = animais.saude_status_id
WHERE animais.instituicao_id = ?";

// Declara os parâmetros e tipos iniciais da pesquisa
$params = [
    $_SESSION['instituicao_id']
];
$types = "i";

// Verifica quais filtros foram enviados
// Filtro do nome
if ($nomeFiltrado != "") {
    $sql .= " AND animais.nome LIKE ?";
    $params[] = "%$nomeFiltrado%";
    $types .= "s";
}

// Filtro da espécie
if ($especieFiltrada != "") {
    $sql .= " AND animais.especie_id = ?";
    $params[] = $especieFiltrada;
    $types .= "i";
}

// Filtro do habitat
if ($habitatFiltrado != "") {
    $sql .= " AND animais.habitat_id = ?";
    $params[] = $habitatFiltrado;
    $types .= "i";
}

// Filtro do status do animal
if ($statusAnimalFiltrado != "") {
    $sql .= " AND animais.status_id = ?";
    $params[] = $statusAnimalFiltrado;
    $types .= "i";
}

// Filtro do status da saúde
if ($saudeStatusFiltrado != "") {
    $sql .= " AND animais.saude_status_id = ?";
    $params[] = $saudeStatusFiltrado;
    $types .= "i";
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
    <title>Animais | WildKeeper</title>
</head>

<body>
    <?php include "../includes/dashboard-header.php" ?>
    
    <main>
        <section>
            <h1>Gerenciamento de Animais</h1>
            <p>Consulte, pesquise e acompanhe os animais cadastrados na instituição.</p>


            <?php if (nivelMinimo(40)): ?>
                <a href="../animals/cadastrar_animal.php">Cadastrar Animal</a>
            <?php endif; ?>

            <form action="index.php" method="get">
                <label for="nome">Nome</label>
                <input type="text" name="nome" id="nome" placeholder="Encontrar pelo nome do animal" value="<?= htmlspecialchars($nomeFiltrado) ?>">

                <label for="especie">Espécie</label>
                <select name="especie" id="especie">
                    <option value="">Todos</option>
                    <?php while ($e = $especies->fetch_assoc()): ?>
                        <option value="<?= htmlspecialchars($e['id']) ?>" <?= $e['id'] == $especieFiltrada ? 'selected' : ''; ?>><?= htmlspecialchars($e['nome_popular']) ?></option>
                    <?php endwhile; ?>
                </select>

                <label for="habitat">Habitat</label>
                <select name="habitat" id="habitat">
                    <option value="">Todos</option>
                    <?php while ($h = $habitats->fetch_assoc()): ?>
                        <option value="<?= htmlspecialchars($h['id']) ?>" <?= $h['id'] == $habitatFiltrado ? 'selected' : ''; ?>><?= htmlspecialchars($h['nome']) ?></option>
                    <?php endwhile; ?>
                </select>

                <label for="status_animal">Status do Animal</label>
                <select name="status_animal" id="status_animal">
                    <option value="">Todos</option>
                    <?php while ($s = $statusAnimais->fetch_assoc()): ?>
                        <option value="<?= htmlspecialchars($s['id']) ?>" <?= $s['id'] == $statusAnimalFiltrado ? 'selected' : ''; ?>><?= htmlspecialchars($s['nome']) ?></option>
                    <?php endwhile; ?>
                </select>

                <label for="saude_status">Status de Saúde do Animal</label>
                <select name="saude_status" id="saude_status">
                    <option value="">Todos</option>
                    <?php while ($s = $saudeStatus->fetch_assoc()): ?>
                        <option value="<?= htmlspecialchars($s['id']) ?>" <?= $s['id'] == $saudeStatusFiltrado ? 'selected' : ''; ?>><?= htmlspecialchars($s['nome']) ?></option>
                    <?php endwhile; ?>
                </select>

                <button type="submit">Pesquisar</button>
            </form>

            <table border="1">
                <thead>
                    <tr>
                        <th>Nome</th>
                        <th>Espécie</th>
                        <th>Sexo</th>
                        <th>Data de Nascimento</th>
                        <th>Habitat</th>
                        <th>Status</th>
                        <th>Status de Saúde</th>
                        <th>Ações</th>
                    </tr>
                </thead>

                <tbody>
                    <?php if ($result->num_rows > 0): ?>
                        <?php while ($animal = $result->fetch_assoc()): ?>
                            <tr>
                                <td><?= htmlspecialchars($animal['nome']) ?></td>
                                <td><?= htmlspecialchars($animal['especie_nome']) ?></td>
                                <td><?= htmlspecialchars($animal['sexo']) ?></td>
                                <td><?= htmlspecialchars($animal['data_nascimento']) ?></td>
                                <td><?= htmlspecialchars($animal['habitat_nome']) ?></td>
                                <td><?= htmlspecialchars($animal['status_animal_nome']) ?></td>
                                <td><?= htmlspecialchars($animal['saude_status_nome']) ?></td>
                                <td><a href="mostrar_animal.php?id=<?= $animal['id'] ?>">Ver informações</a></td>
                            </tr>
                        <?php endwhile; ?>

                    <?php else: ?>
                        <tr>
                            <td colspan="8">Nenhum animal encontrado.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>

            </table>
        </section>
    </main>
    <?php include "../includes/dashboard-footer.php" ?>
</body>

</html>