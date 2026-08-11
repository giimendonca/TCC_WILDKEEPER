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

// Pega o id do funcionário
$funcionarioId = trim($_GET['id'] ?? '');

// Verifica se o id veio vazio
if (empty($funcionarioId)) {
    die("ID inválido.");
}

// Faz o SELECT dos cargos
$cargos = selectTabela($conexao, "cargos");

// Faz o SELECT dos funcionários
$sql = "SELECT 
    users.id,
    users.nome, 
    users.cpf, 
    users.data_nascimento, 
    users.genero, 
    users.telefone, 
    users.email, 
    users.status,
    cargos.id AS cargo_id,
    cargos.nome AS cargo_nome
FROM users
INNER JOIN cargos ON cargos.id = users.cargo_id
WHERE users.instituicao_id = ? AND users.id = ?";

$stmt = $conexao->prepare($sql);
$stmt->bind_param("ii", $_SESSION['instituicao_id'], $funcionarioId);
$stmt->execute();

$result = $stmt->get_result();

$funcionario = $result->fetch_assoc();

// Verifica se o funcionário foi encontrado
if (!$funcionario) {
    die("Funcionário não encontrado.");
}
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Funcionário | WildKeeper</title>
</head>

<body>
    <?php include "../includes/dashboard-header.php" ?>
    <main>
        <section>
            <h1>Funcionário: <?= htmlspecialchars($funcionario['nome']) ?></h1>

            <form action="atualizar_funcionario.php" method="post">

                <input type="hidden" name="id" value="<?= $funcionarioId ?>">

                <label for="nome">Nome do Funcionário</label>
                <input type="text" name="nome" id="nome" maxlength="100" placeholder="Digite o nome do usuário" value="<?= htmlspecialchars($funcionario['nome']) ?>">

                <label for="cpf">CPF</label>
                <input type="text" name="cpf" id="cpf" maxlength="14" placeholder="000.000.000-00" value="<?= htmlspecialchars($funcionario['cpf']) ?>">

                <label for="data_nascimento">Data Nascimento</label>
                <input type="date" name="data_nascimento" id="data_nascimento" value="<?= htmlspecialchars($funcionario['data_nascimento']) ?>">

                <label for="genero">Genêro</label>
                <select name="genero" id="genero">
                    <option value="Masculino" <?= $funcionario['genero'] === 'Masculino' ? 'selected' : '' ?>>Masculino</option>
                    <option value="Feminino" <?= $funcionario['genero'] === 'Feminino' ? 'selected' : '' ?>>Feminino</option>
                    <option value="Não-binário" <?= $funcionario['genero'] === 'Não-binário' ? 'selected' : '' ?>>Não-binário</option>
                    <option value="Outro" <?= $funcionario['genero'] === 'Outro' ? 'selected' : '' ?>>Outro</option>
                    <option value="Prefiro não informar" <?= $funcionario['genero'] === 'Prefiro não informar' ? 'selected' : '' ?>>Prefiro não informar</option>
                </select>

                <label for="telefone">Telefone</label>
                <input type="text" name="telefone" id="telefone" maxlength="20" placeholder="(11) 99999-9999" value="<?= htmlspecialchars($funcionario['telefone']) ?>">


                <label for="email">Email</label>
                <input type="email" name="email" id="email" maxlength="100" placeholder="ex.: administrador@email.com" value="<?= htmlspecialchars($funcionario['email']) ?>">

                <label for="cargo_id">Cargo</label>
                <select name="cargo_id" id="cargo_id">
                    <?php while($cargo = $cargos->fetch_assoc()): ?>
                        <option value="<?= $cargo['id'] ?>" <?= ($cargo['id'] == $funcionario['cargo_id']) ? "selected" : "" ?>><?= htmlspecialchars($cargo['nome']) ?></option>
                    <?php endwhile; ?>
                </select>

                <label for="status">Status</label>
                <select name="status" id="status">
                    <option value="Ativo" <?= $funcionario['status'] === 'Ativo' ? 'selected' : '' ?>>Ativo</option>
                    <option value="Férias" <?= $funcionario['status'] === 'Férias' ? 'selected' : '' ?>>Férias</option>
                    <option value="Afastado" <?= $funcionario['status'] === 'Afastado' ? 'selected' : '' ?>>Afastado</option>
                    <option value="Desligado" <?= $funcionario['status'] === 'Desligado' ? 'selected' : '' ?>>Desligado</option>
                </select>

                <button type="submit">Atualizar Funcionário</button>
            </form>
        </section>
    </main>
    <?php include "../includes/dashboard-footer.php" ?>
</body>

</html>