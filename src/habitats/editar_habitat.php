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

// Pega o id do Habitat
$habitatId = trim($_GET['id'] ?? '');

// Verifica se o id veio vazio
if (empty($habitatId)) {
    die("ID inválido.");
}

// Faz o SELECT do Habitat
$sql = "SELECT 
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

// Verifica se o Habitat foi encontrado
if (!$habitat) {
    die("Habitat não encontrado.");
}
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Habitat | WildKeeper</title>
</head>

<body>
    <?php include "../includes/dashboard-header.php" ?>
    <main>
        <section>
            <h1>Habitat: <?= htmlspecialchars($habitat['nome']) ?></h1>

            <form action="atualizar_habitat.php" method="post">

                <input type="hidden" name="id" id="id" value="<?= htmlspecialchars($habitatId) ?>">

                <label for="nome">Nome do Habitat</label>
                <input type="text" name="nome" id="nome" maxlength="50" placeholder="Digite o nome" value="<?= htmlspecialchars($habitat['nome']) ?>" required>

                <label for="descricao">Descrição</label>
                <textarea name="descricao" id="descricao" required><?= htmlspecialchars($habitat['descricao']) ?></textarea>

                <label for="bioma">Bioma</label>
                <input type="text" name="bioma" id="bioma" placeholder="digite o nome do bioma" value="<?= htmlspecialchars($habitat['bioma']) ?>" required>

                <label for="temperatura">Temperatura (°C)</label>
                <input type="number" name="temperatura" id="temperatura" step="0.01" placeholder="Digite a temperatura em graus Celsius" value="<?= htmlspecialchars($habitat['temperatura']) ?>" required>

                <label for="umidade">Umidade Relativa do Ar (%)</label>
                <input type="number" name="umidade" id="umidade" step="0.01" min="0" max="100" placeholder="Digite a umidade em porcentagem" value="<?= htmlspecialchars($habitat['umidade']) ?>" required>

                <label for="capacidade">Capacidade (animais)</label>
                <input type="number" name="capacidade" id="capacidade" step="1" min="1" placeholder="Digite a capacidade máxima" value="<?= htmlspecialchars($habitat['capacidade']) ?>" required>

                <label for="status">Status</label>
                <select name="status" id="status">
                    <option value="Ativo" <?= $habitat['status'] === "Ativo" ? "selected" : "" ; ?> >Ativo</option>
                    <option value="Em manutenção" <?= $habitat['status'] === "Em manutenção" ? "selected" : "" ; ?> >Em manutenção</option>
                    <option value="Interditado" <?= $habitat['status'] === "Interditado" ? "selected" : "" ; ?> >Interditado</option>
                </select>

                <button type="submit">Atualizar Habitat</button>
            </form>
        </section>
    </main>
    <?php include "../includes/dashboard-footer.php" ?>
</body>

</html>