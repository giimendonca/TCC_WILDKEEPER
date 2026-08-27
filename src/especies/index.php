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
$categoriaFiltrado = trim($_GET['categoria'] ?? "");
$classAlimentarFiltrado = trim($_GET['class_alimentar'] ?? "");
$riscoFiltrado = trim($_GET['risco_extincao'] ?? "");

// Pega os filtros que são de outras tabelas
$categorias = selectTabela($conexao, "categorias");
$classificacoes = selectTabela($conexao, "classificacao_alimentar");
$riscos = selectTabela($conexao, "risco_extincao");

// Faz o SELECT dos Espécies
$sql = "SELECT 
    especies.id,
    especies.nome_popular,
    especies.nome_cientifico,
    especies.origem,

    categorias.id AS categoria_id,
    categorias.nome AS categoria_nome,

    classificacao_alimentar.id AS classificacao_alimentar_id,
    classificacao_alimentar.nome AS classificacao_alimentar_nome,

    risco_extincao.id AS risco_extincao_id,
    risco_extincao.nome AS risco_extincao_nome
FROM especies
INNER JOIN categorias ON categorias.id = especies.categoria_id
INNER JOIN classificacao_alimentar ON classificacao_alimentar.id = especies.classificacao_alimentar_id
INNER JOIN risco_extincao ON risco_extincao.id = especies.risco_extincao_id
WHERE 1=1";

// Declara os parâmetros e tipos iniciais da pesquisa
$params = [];
$types = "";

// Verifica quais filtros foram enviados
// Filtro do nome
if ($nomeFiltrado != "") {
    $sql .= " AND especies.nome_popular LIKE ? OR especies.nome_cientifico LIKE ?";
    $params[] = "%$nomeFiltrado%";
    $params[] = "%$nomeFiltrado%";
    $types .= "ss";
}

// Filtro da categoria
if ($categoriaFiltrado != "") {
    $sql .= " AND especies.categoria_id = ?";
    $params[] = $categoriaFiltrado;
    $types .= "i";
}

// Filtro da classificaáão alimentar
if ($classAlimentarFiltrado != "") {
    $sql .= " AND especies.classificacao_alimentar_id = ?";
    $params[] = $classAlimentarFiltrado;
    $types .= "i";
}

// Filtro do risco de extinção
if ($riscoFiltrado != "") {
    $sql .= " AND especies.risco_extincao_id = ?";
    $params[] = $riscoFiltrado;
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
    <title>Espécies | WildKeeper</title>
</head>

<body>
    <?php include "../includes/dashboard-header.php" ?>
    <main>
        <section>
            <h1>Gerenciamento de Espécies</h1>

            <form action="index.php" method="get">
                <label for="nome">Nome</label>
                <input type="text" name="nome" id="nome" placeholder="Pesquisar por nome científico ou popular" value="<?= htmlspecialchars($nomeFiltrado) ?>">

                <label for="categoria">Categoria</label>
                <select name="categoria" id="categoria">
                    <option value="">Todos</option>
                    <?php while ($categoria = $categorias->fetch_assoc()): ?>
                        <option value="<?= $categoria['id'] ?>" <?= ($categoriaFiltrado == $categoria['id']) ? "selected" : "" ?>><?= htmlspecialchars($categoria['nome']) ?></option>
                    <?php endwhile; ?>
                </select>

                <label for="class_alimentar">Classificação Alimentar</label>
                <select name="class_alimentar" id="class_alimentar">
                    <option value="">Todos</option>
                    <?php while ($classificacao = $classificacoes->fetch_assoc()): ?>
                        <option value="<?= $classificacao['id'] ?>" <?= ($classAlimentarFiltrado == $classificacao['id']) ? "selected" : "" ?>><?= htmlspecialchars($classificacao['nome']) ?></option>
                    <?php endwhile; ?>
                </select>

                <label for="risco_extincao">Risco de Extinção</label>
                <select name="risco_extincao" id="risco_extincao">
                    <option value="">Todos</option>
                    <?php while ($risco = $riscos->fetch_assoc()): ?>
                        <option value="<?= $risco['id'] ?>" <?= ($riscoFiltrado == $risco['id']) ? "selected" : "" ?>><?= htmlspecialchars($risco['nome']) ?></option>
                    <?php endwhile; ?>
                </select>

                <button type="submit">Pesquisar</button>
            </form>

            <table border="1">
                <thead>
                    <tr>
                        <th>Nome Popular</th>
                        <th>Nome Científico</th>
                        <th>Origem</th>
                        <th>Categoria</th>
                        <th>Classificação Alimentar</th>
                        <th>Risco de Extinção</th>
                        <th>Ações</th>
                    </tr>
                </thead>

                <tbody>
                    <?php if ($result->num_rows > 0): ?>
                        <?php while ($especie = $result->fetch_assoc()): ?>
                            <tr>
                                <td><?= htmlspecialchars($especie['nome_popular']) ?></td>
                                <td><?= htmlspecialchars($especie['nome_cientifico']) ?></td>
                                <td><?= htmlspecialchars($especie['origem']) ?></td>
                                <td><?= htmlspecialchars($especie['categoria_nome']) ?></td>
                                <td><?= htmlspecialchars($especie['classificacao_alimentar_nome']) ?></td>
                                <td><?= htmlspecialchars($especie['risco_extincao_nome']) ?></td>
                                <td><a href="mostrar_especie.php?id=<?= $especie['id'] ?>">Ver informações</a></td>
                            </tr>
                        <?php endwhile; ?>

                    <?php else: ?>
                        <tr>
                            <td colspan="7">Nenhuma espécie encontrada.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>

            </table>
        </section>

        <a href="../especies/cadastrar_especie.php">Cadastrar Espécie</a>
    </main>
    <?php include "../includes/dashboard-footer.php" ?>
</body>

</html>