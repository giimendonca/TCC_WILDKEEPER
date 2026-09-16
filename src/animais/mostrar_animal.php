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
requireNivel(20);

// Pega o id da Animal
$animalId = trim($_GET['id'] ?? '');

// Verifica se o id veio vazio
if (empty($animalId)) {
    die("ID inválido.");
}

// Faz o SELECT dos Animais
$sql = "SELECT 
    animais.id,
    animais.nome,

    especies.id AS especie_id,
    especies.nome_popular AS especie_nome,
    especies.nome_cientifico AS especie_nome_cientifico,

    animais.sexo,
    animais.data_nascimento,
    animais.data_chegada,
    animais.peso,
    animais.altura,
    animais.microchip,
    animais.observacoes,

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
WHERE animais.instituicao_id = ? AND animais.id = ?";

$stmt = $conexao->prepare($sql);
$stmt->bind_param("ii", $_SESSION['instituicao_id'], $animalId);
$stmt->execute();

$result = $stmt->get_result();

$animal = $result->fetch_assoc();

// Verifica se a Animal foi encontrada
if (!$animal) {
    die("Animal não encontrado.");
}

// Faz o SELECT das fotos do animal
$sqlFotos = "SELECT 
    id,
    caminho_arquivo,
    descricao
FROM animais_fotos
WHERE animal_id = ? AND instituicao_id = ?
ORDER BY id ASC";

$stmtFotos = $conexao->prepare($sqlFotos);
$stmtFotos->bind_param("ii", $animalId, $_SESSION['instituicao_id']);
$stmtFotos->execute();

$resultFotos = $stmtFotos->get_result();


?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Animal | WildKeeper</title>
</head>

<body>
    <?php include "../includes/dashboard-header.php" ?>
    <main>
        <section>
            <div>
                <h1><?= htmlspecialchars($animal['nome']) ?></h1>
                <h2><?= htmlspecialchars($animal['especie_nome']) ?> / <?= htmlspecialchars($animal['especie_nome_cientifico']) ?></h2>
                    <p>Status: <?= htmlspecialchars($animal['status_animal_nome']) ?></p>
                    <p>Saúde: <?= htmlspecialchars($animal['saude_status_nome']) ?></p>
                    <div>
                        <?php if ($resultFotos->num_rows > 0): ?>

                            <?php while ($foto = $resultFotos->fetch_assoc()): ?>

                                <img width="200px" height="200px"
                                    src="../../<?= htmlspecialchars($foto['caminho_arquivo']) ?>"
                                    alt="<?= htmlspecialchars($foto['descricao']) ?>">

                            <?php endwhile; ?>

                        <?php else: ?>

                            <span>Nenhuma foto cadastrada.</span>

                        <?php endif; ?>
                    </div>
                    <a href="editar_fotos.php?id=<?= htmlspecialchars($animalId) ?>">Editar Fotos</a>
            </div>


            <?php if (nivelMinimo(40)): ?>
                <a href="editar_animal.php?id=<?= $animalId ?>">Editar</a>
            <?php endif; ?>

            <article>
                <h3>Informações Gerais</h3>

                <p>Sexo: <?= htmlspecialchars($animal['sexo']) ?></p>
                <p>Nascimento: <?= htmlspecialchars($animal['data_nascimento']) ?></p>
                <p>Data de chegada: <?= htmlspecialchars($animal['data_chegada']) ?></p>
                <p>Altura: <?= htmlspecialchars($animal['altura']) ?> cm</p>
                <p>Peso: <?= htmlspecialchars($animal['peso']) ?> kg</p>
                <p>Microchip: <?= htmlspecialchars($animal['microchip']) ?></p>
            </article>

            <article>
                <h3>Observações</h3>
                <p><?= htmlspecialchars($animal['observacoes']) ?></p>
            </article>

            <article>
                <h3>Localização</h3>
                <p>Habitat Atual: <?= htmlspecialchars($animal['habitat_nome']) ?></p>
                <span>Colocar o histórico de habitats</span>
            </article>

            <article>
                <h3>Vacinas</h3>
                <span>Histórico de Vacinas</span>
            </article>

            <a href="index.php">Voltar</a>
        </section>
    </main>
    <?php include "../includes/dashboard-footer.php" ?>
</body>

</html>