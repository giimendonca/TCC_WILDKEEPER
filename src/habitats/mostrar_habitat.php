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
requireNivel(40);

// Pega o id do Habitat
$habitatId = trim($_GET['id'] ?? '');

// Verifica se o id veio vazio
if (empty($habitatId)) {
    die("ID inválido.");
}

// Faz o SELECT do Habitat
$sql = "SELECT 
    id,
    nome,
    descricao,
    bioma,
    temperatura,
    umidade,
    capacidade,
    status
FROM habitats
WHERE instituicao_id = ? AND id = ?";

$stmt = $conexao->prepare($sql);
$stmt->bind_param("ii", $_SESSION['instituicao_id'], $habitatId);
$stmt->execute();

$result = $stmt->get_result();

$habitat = $result->fetch_assoc();

// Verifica se a Habitat foi encontrado
if (!$habitat) {
    die("Habitat não encontrado.");
}
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Habitat | WildKeeper</title>
</head>

<body>
    <?php include "../includes/dashboard-header.php" ?>
    <main>
        <section>
            <h1>Habitat: <?= htmlspecialchars($habitat['nome']) ?></h1>

            <article>
                <h2>Informações Gerais</h2>

                <p>Nome: <?= htmlspecialchars($habitat['nome']) ?></p>
                <p>Descrição: <?= htmlspecialchars($habitat['descricao']) ?></p>
            </article>

            <article>
                <h2>Características</h2>

                <p>Bioma: <?= htmlspecialchars($habitat['bioma']) ?></p>
                <p>Temperatura: <?= htmlspecialchars($habitat['temperatura']) ?> °C</p>
                <p>Umidade: <?= htmlspecialchars($habitat['umidade']) ?> %</p>
                <p>Capacidade: <?= htmlspecialchars($habitat['capacidade']) ?> animais</p>
                <p>Status: <?= htmlspecialchars($habitat['status']) ?></p>
            </article>

            <a href="editar_habitat.php?id=<?= $habitatId ?>">Editar</a>
            <a href="index.php">Voltar</a>
        </section>
    </main>
    <?php include "../includes/dashboard-footer.php" ?>
</body>

</html>