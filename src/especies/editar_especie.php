<?php
session_start();

include "../includes/conexao.php";
include "../includes/funcoes.php";
include "../includes/autenticacao.php";

// Verifica se ja existe uma sessão
if (!isset($_SESSION['id'])) {
    header("Location: ../auth/login.php");
    exit();
}

// Verifica a permissão que o usuário possui
requireNivel(100);

// Pega o id do Espécie
$especieId = trim($_GET['id'] ?? '');

// Verifica se o id veio vazio
if (empty($especieId)) {
    die("ID inválido.");
}

// Faz o select das categorias, classificações e riscos existentes
$categorias = selectTabela($conexao, "categorias");
$classificacoes = selectTabela($conexao, "classificacao_alimentar");
$riscos = selectTabela($conexao, "risco_extincao");

// Faz o SELECT da espécie
$sql = "SELECT 
    especies.id,
    especies.nome_popular,
    especies.nome_cientifico,
    especies.descricao,
    especies.origem,
    especies.vida_media,
    especies.peso_medio,
    especies.altura_media,

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
WHERE especies.id = ?";

$stmt = $conexao->prepare($sql);
$stmt->bind_param("i", $especieId);
$stmt->execute();

$result = $stmt->get_result();

$especie = $result->fetch_assoc();

// Verifica se o Espécie foi encontrado
if (!$especie) {
    die("Espécie não encontrado.");
}
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Espécie | WildKeeper</title>
</head>

<body>
    <?php include "../includes/dashboard-header.php" ?>
    <main>
        <section>
            <h1>Espécie: <?= htmlspecialchars($especie['nome_popular']) ?></h1>

            <form action="atualizar_especie.php" method="post">

                <input type="hidden" name="id" id="id" value="<?= htmlspecialchars($especieId) ?>">

                <label for="nome_popular">Nome Popular</label>
                <input type="text" name="nome_popular" id="nome_popular" maxlength="255" placeholder="Digite o nome comum" value="<?= htmlspecialchars($especie['nome_popular']) ?>" required>

                <label for="nome_cientifico">Nome Científico</label>
                <input type="text" name="nome_cientifico" id="nome_cientifico" maxlength="255" placeholder="Digite o nome científico"  value="<?= htmlspecialchars($especie['nome_cientifico']) ?>"required>

                <label for="descricao">Descrição</label>
                <textarea name="descricao" id="descricao" required><?= htmlspecialchars($especie['descricao']) ?>"</textarea>

                <label for="origem">Origem</label>
                <input type="text" name="origem" id="origem" placeholder="País de origem" value="<?= htmlspecialchars($especie['origem']) ?>" required>

                <label for="vida_media">Vida Média</label>
                <input type="number" name="vida_media" id="vida_media" min="1" placeholder="Em anos" value="<?= htmlspecialchars($especie['vida_media']) ?>" required>

                <label for="peso_medio">Peso Médio</label>
                <input type="number" name="peso_medio" id="peso_medio" step="0.001" min="0.001" placeholder="Em Kg" value="<?= htmlspecialchars($especie['peso_medio']) ?>" required>                

                <label for="altura_media">Altura Média</label>
                <input type="number" name="altura_media" id="altura_media" step="0.001" min="0.001" placeholder="Em metros" value="<?= htmlspecialchars($especie['altura_media']) ?>" required>

                <label for="categoria">Categoria</label>
                <select name="categoria" id="categoria" required>
                    <option value="">Selecione</option>
                    <?php while($c = $categorias->fetch_assoc()): ?>
                        <option value="<?= htmlspecialchars($c['id']) ?>" <?= $especie['categoria_id'] === $c['id'] ? 'selected' : '' ?> ><?= htmlspecialchars($c['nome']) ?></option>
                    <?php endwhile; ?>
                </select>

                <label for="classificacao_alimentar">Classificação Alimentar</label>
                <select name="classificacao_alimentar" id="classificacao_alimentar">
                    <option value="">Selecione</option>
                    <?php while($c = $classificacoes->fetch_assoc()): ?>
                        <option value="<?= htmlspecialchars($c['id']) ?>" <?= $especie['classificacao_alimentar_id'] === $c['id'] ? 'selected' : '' ?> ><?= htmlspecialchars($c['nome']) ?></option>
                    <?php endwhile; ?>
                </select>

                <label for="risco_extincao">Risco de Extinção</label>
                <select name="risco_extincao" id="risco_extincao" required>
                    <option value="">Selecione</option>
                    <?php while($r = $riscos->fetch_assoc()): ?>
                        <option value="<?= htmlspecialchars($r['id']) ?>" <?= $especie['risco_extincao_id'] === $r['id'] ? 'selected' : '' ?> ><?= htmlspecialchars($r['nome']) ?></option>
                    <?php endwhile; ?>
                </select>


                <button type="submit">Atualizar Espécie</button>
            </form>
        </section>
    </main>
    <?php include "../includes/dashboard-footer.php" ?>
</body>

</html>