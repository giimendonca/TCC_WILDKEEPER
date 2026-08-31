<?php
session_start();

include "../includes/conexao.php";
include "../includes/autenticacao.php";

// Verifica se ja existe uma sessão
if (!isset($_SESSION['id'])) {
    header("Location: ../auth/login.php");
    exit();
}

// Verifica a permissão que o usuário possui
requireNivel(100);

// Pega o id da espécie
$especieId = trim($_GET['id'] ?? '');

// Verifica se o id veio vazio
if (empty($especieId)) {
    die("ID inválido.");
}

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

// Verifica se a espécie foi encontrada
if(!$especie){
    die("Espécie não encontrada.");
}
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Espécie | WildKeeper</title>
</head>

<body>
    <?php include "../includes/dashboard-header.php" ?>
    <main>
        <section>
            <h1>Espécie: <?= htmlspecialchars($especie['nome_popular']) ?></h1>

            <article>
                <h2>Informações Gerais</h2>

                <p>Nome Popular: <?= htmlspecialchars($especie['nome_popular']) ?></p>
                <p>Nome Científico: <?= htmlspecialchars($especie['nome_cientifico']) ?></p>
                <p>Origem: <?= htmlspecialchars($especie['origem']) ?></p>
                <p>Descrição: <?= htmlspecialchars($especie['descricao']) ?></p>
            </article>

            <article>
                <h2>Características</h2>

                <p>Vida Média: <?= htmlspecialchars($especie['vida_media']) ?> anos</p>
                <p>Peso Médio: <?= htmlspecialchars($especie['peso_medio']) ?> kg</p>
                <p>Altura Média: <?= htmlspecialchars($especie['altura_media']) ?> m</p>
            </article>

            <article>
                <h2>Classificação</h2>

                <p>Categoria: <?= htmlspecialchars($especie['categoria_nome']) ?></p>
                <p>Classificação Alimentar: <?= htmlspecialchars($especie['classificacao_alimentar_nome']) ?></p>
                <p>Risco de Extinção: <?= htmlspecialchars($especie['risco_extincao_nome']) ?></p>
            </article>

            <a href="editar_especie.php?id=<?= $especieId ?>">Editar</a>
            <a href="index.php">Voltar</a>
        </section>
    </main>
    <?php include "../includes/dashboard-footer.php" ?>
</body>

</html>