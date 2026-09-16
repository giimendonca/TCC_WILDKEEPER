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

// Pega os filtros enviados pelo método GET
$nomeFiltrado = trim($_GET['nome'] ?? "");
$estoqueOrdenacao = $_GET['estoque'] ?? "";
$vencimentoOrdenacao = $_GET['vencimento'] ?? "";

// ====================================
// Consulta
// ====================================

// Monta a consulta base
$sql = "SELECT id, nome, fabricante, estoque, lote, vencimento
        FROM medicamentos
        WHERE 1 = 1";

$parametros = [];
$tipos = "";

// Filtra pelo nome do medicamento
if ($nomeFiltrado !== "") {
    $sql .= " AND nome LIKE ?";
    $parametros[] = "%" . $nomeFiltrado . "%";
    $tipos .= "s";
}

// ====================================
// Ordenação
// ====================================

$ordenacoes = [];

if ($estoqueOrdenacao === "maior") {
    $ordenacoes[] = "estoque DESC";
} elseif ($estoqueOrdenacao === "menor") {
    $ordenacoes[] = "estoque ASC";
}

if ($vencimentoOrdenacao === "proximo") {
    $ordenacoes[] = "vencimento ASC";
} elseif ($vencimentoOrdenacao === "distante") {
    $ordenacoes[] = "vencimento DESC";
}

if (empty($ordenacoes)) {
    $sql .= " ORDER BY nome ASC";
} else {
    $sql .= " ORDER BY " . implode(", ", $ordenacoes);
}

// Executa a consulta
$stmt = $conexao->prepare($sql);

if (!empty($parametros)) {
    $stmt->bind_param($tipos, ...$parametros);
}

$stmt->execute();

$resultado = $stmt->get_result();

?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Medicamentos | WildKeeper</title>
</head>
<body>
    <?php include "../includes/dashboard-header.php" ?>

    <main>
        <section>
            <h1>Gerenciamento de Medicamentos</h1>

            <!-- ====================================
                 Filtros
            ==================================== -->

            <form action="index.php" method="get">

                <label for="nome">Nome</label>
                <input type="text" name="nome" id="nome" placeholder="Pesquisar por nome" value="<?= htmlspecialchars($nomeFiltrado) ?>">

                <label for="estoque">Ordenar estoque</label>
                <select name="estoque" id="estoque">
                    <option value="">Padrão</option>
                    <option value="maior" <?= $estoqueOrdenacao === "maior" ? "selected" : "" ?>>Maior estoque</option>
                    <option value="menor" <?= $estoqueOrdenacao === "menor" ? "selected" : "" ?>>Menor estoque</option>
                </select>

                <label for="vencimento">Ordenar vencimento</label>
                <select name="vencimento" id="vencimento">
                    <option value="">Padrão</option>
                    <option value="proximo" <?= $vencimentoOrdenacao === "proximo" ? "selected" : "" ?>>Vencimento mais próximo</option>
                    <option value="distante" <?= $vencimentoOrdenacao === "distante" ? "selected" : "" ?>>Vencimento mais distante</option>
                </select>

                <button type="submit">Filtrar</button>

                <a href="index.php">Limpar filtros</a>

            </form>

            <!-- ====================================
                 Lista de medicamentos
            ==================================== -->

            <a href="cadastrar_medicamento.php">Cadastrar medicamento</a>

            <?php if ($resultado->num_rows > 0): ?>

                <table border="1">
                    <thead>
                        <tr>
                            <th>Nome</th>
                            <th>Fabricante</th>
                            <th>Estoque</th>
                            <th>Lote</th>
                            <th>Vencimento</th>
                            <th>Ações</th>
                        </tr>
                    </thead>

                    <tbody>

                        <?php while ($medicamento = $resultado->fetch_assoc()): ?>

                            <tr>
                                <td><?= htmlspecialchars($medicamento['nome']) ?></td>

                                <td><?= htmlspecialchars($medicamento['fabricante']) ?></td>

                                <td>
                                    <?= htmlspecialchars($medicamento['estoque']) ?>

                                    <?php if ($medicamento['estoque'] == 0): ?>
                                        <span>Fora de estoque</span>
                                    <?php endif; ?>
                                </td>

                                <td><?= htmlspecialchars($medicamento['lote']) ?></td>

                                <td>
                                    <?= date("d/m/Y", strtotime($medicamento['vencimento'])) ?>
                                </td>

                                <td>
                                    <a href="mostrar_medicamento.php?id=<?= $medicamento['id'] ?>">
                                        Visualizar
                                    </a>

                                    <a href="editar_medicamento.php?id=<?= $medicamento['id'] ?>">
                                        Editar
                                    </a>
                                </td>
                            </tr>

                        <?php endwhile; ?>

                    </tbody>
                </table>

            <?php else: ?>

                <p>Nenhum medicamento encontrado.</p>

            <?php endif; ?>

        </section>
    </main>

    <?php include "../includes/dashboard-footer.php" ?>
</body>
</html>