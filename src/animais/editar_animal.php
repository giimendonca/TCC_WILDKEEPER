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
requireNivel(40);

// Pega o id do Animal
$animalId = trim($_GET['id'] ?? '');

// Verifica se o id veio vazio
if (empty($animalId)) {
    die("ID inválido.");
}

// Faz o select das especies, habitats status_animais e riscos existentes
$especies = selectTabela($conexao, "especies");
$habitats = selectTabela($conexao, "habitats");
$statusAnimais = selectTabela($conexao, "status_animais");
$saudeStatus = selectTabela($conexao, "saude_status");

// Faz o SELECT da Animal
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

// Verifica se o Animal foi encontrado
if (!$animal) {
    die("Animal não encontrado.");
}
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Animal | WildKeeper</title>
</head>

<body>
    <?php include "../includes/dashboard-header.php" ?>
    <main>
        <section>
            <h1>Animal: <?= htmlspecialchars($animal['nome']) ?></h1>

            <form action="atualizar_animal.php" method="post">

                <input type="hidden" name="id" id="id" value="<?= htmlspecialchars($animalId) ?>" required>

                <label for="nome">Nome</label>
                <input type="text" name="nome" id="nome" maxlength="100" placeholder="Digite o nome do animal" value="<?= htmlspecialchars($animal['nome']) ?>" required>

                <label for="sexo">Sexo</label>
                <select name="sexo" id="sexo" required>
                    <option value="Masculino" <?= $animal['sexo'] === "Masculino" ? 'selected' : ''; ?>>Masculino</option>
                    <option value="Feminino" <?= $animal['sexo'] === "Feminino" ? 'selected' : ''; ?>>Feminino</option>
                    <option value="Indeterminado" <?= $animal['sexo'] === "Indeterminado" ? 'selected' : ''; ?>>Indeterminado</option>
                </select>

                <label for="data_nascimento">Data de Nascimento</label>
                <input type="date" name="data_nascimento" id="data_nascimento" value="<?= htmlspecialchars($animal['data_nascimento']) ?>" required>

                <label for="data_chegada">Data de Chegada</label>
                <input type="date" name="data_chegada" id="data_chegada" value="<?= htmlspecialchars($animal['data_chegada']) ?>" required>

                <label for="peso">Peso (Kg)</label>
                <input type="number" name="peso" id="peso" step="0.001" min="0.001" placeholder="Em Kg" value="<?= htmlspecialchars($animal['peso']) ?>" required>

                <label for="altura">Altura (cm)</label>
                <input type="number" name="altura" id="altura" step="0.001" min="0.001" placeholder="Em centímetros" value="<?= htmlspecialchars($animal['altura']) ?>" required>

                <label for="microchip">Microchip</label>
                <input type="text" name="microchip" id="microchip" maxlength="20" placeholder="Digite o código do microchip" value="<?= htmlspecialchars($animal['microchip']) ?>" required>

                <label for="observacoes">Observações</label>
                <textarea name="observacoes" id="observacoes" placeholder="Digite informações importantes sobre o animal..."><?= htmlspecialchars($animal['observacoes']) ?></textarea>

                <label for="especie_id">Espécie</label>
                <select name="especie_id" id="especie_id" required>
                    <option value="">Selecione</option>
                    <?php while ($e = $especies->fetch_assoc()): ?>
                        <option value="<?= htmlspecialchars($e['id']) ?>" <?= $animal['especie_id'] === $e['id'] ? 'selected' : ''; ?>><?= htmlspecialchars($e['nome_popular']) ?></option>
                    <?php endwhile; ?>
                </select>

                <p>Ainda não encontrou a espécie? <a href="../especies/cadastrar_especie.php">Cadastre uma nova espécie.</a></p>

                <label for="habitat_id">Habitat</label>
                <select name="habitat_id" id="habitat_id" required>
                    <option value="">Selecione</option>
                    <?php while ($h = $habitats->fetch_assoc()): ?>
                        <option value="<?= htmlspecialchars($h['id']) ?>" <?= $animal['habitat_id'] === $h['id'] ? 'selected' : ''; ?>><?= htmlspecialchars($h['nome']) ?></option>
                    <?php endwhile; ?>
                </select>

                <p>Ainda não encontrou o habitat? <a href="../habitats/cadastrar_habitat.php">Cadastre um novo habitat.</a></p>

                <label for="status_id">Status do Animal</label>
                <select name="status_id" id="status_id" required>
                    <option value="">Selecione</option>
                    <?php while ($s = $statusAnimais->fetch_assoc()): ?>
                        <option value="<?= htmlspecialchars($s['id']) ?>" <?= $animal['status_animal_id'] === $s['id'] ? 'selected' : ''; ?>><?= htmlspecialchars($s['nome']) ?></option>
                    <?php endwhile; ?>
                </select>

                <label for="saude_status_id">Status de Saúde</label>
                <select name="saude_status_id" id="saude_status_id" required>
                    <option value="">Selecione</option>
                    <?php while ($s = $saudeStatus->fetch_assoc()): ?>
                        <option value="<?= htmlspecialchars($s['id']) ?>" <?= $animal['saude_status_id'] === $s['id'] ? 'selected' : ''; ?>><?= htmlspecialchars($s['nome']) ?></option>
                    <?php endwhile; ?>
                </select>

                <button type="submit">Atualizar Animal</button>
            </form>
        </section>
    </main>
    <?php include "../includes/dashboard-footer.php" ?>
</body>

</html>