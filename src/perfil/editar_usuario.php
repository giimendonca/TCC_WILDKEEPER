<?php
session_start();

include "../includes/conexao.php";
include "../includes/funcoes.php";
include "../includes/autenticacao.php";

// Verifica se já existe uma sessão
if (!isset($_SESSION['id'])) {
    header("Location: ../auth/login.php");
    exit();
}

// Busca os dados do usuário logado
$sql = "SELECT
    users.id,
    users.nome,
    users.cpf,
    users.data_nascimento,
    users.genero,
    users.telefone,
    users.email,
    users.status,
    cargos.nome AS cargo_nome
FROM users
INNER JOIN cargos ON cargos.id = users.cargo_id
WHERE users.id = ? AND users.instituicao_id = ?";

$stmt = $conexao->prepare($sql);
$stmt->bind_param("ii", $_SESSION['id'], $_SESSION['instituicao_id']);
$stmt->execute();

$result = $stmt->get_result();

$usuario = $result->fetch_assoc();

// Verifica se o usuário foi encontrado
if (!$usuario) {
    die("Usuário não encontrado.");
}
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Perfil | WildKeeper</title>
    <link rel="stylesheet" href="../../assets/css/reset.css">
    <link rel="stylesheet" href="../../assets/css/dashboard_global.css">
    <?php include "../includes/fonte.php" ?>
</head>

<body>
    <?php include "../includes/dashboard-header.php" ?>

    <main>
        <section>
            <h1>Editar Minhas Informações</h1>

            <form action="atualizar_usuario.php" method="post">

                <label for="nome">Nome</label>
                <input type="text" name="nome" id="nome" maxlength="100" placeholder="Digite seu nome" value="<?= htmlspecialchars($usuario['nome']) ?>">

                <label for="cpf">CPF</label>
                <input type="text" name="cpf" id="cpf" maxlength="14" placeholder="000.000.000-00" value="<?= htmlspecialchars($usuario['cpf']) ?>">

                <label for="data_nascimento">Data de Nascimento</label>
                <input type="date" name="data_nascimento" id="data_nascimento" value="<?= htmlspecialchars($usuario['data_nascimento']) ?>">

                <label for="genero">Gênero</label>
                <select name="genero" id="genero">
                    <option value="Masculino" <?= $usuario['genero'] === 'Masculino' ? 'selected' : '' ?>>Masculino</option>
                    <option value="Feminino" <?= $usuario['genero'] === 'Feminino' ? 'selected' : '' ?>>Feminino</option>
                    <option value="Não-binário" <?= $usuario['genero'] === 'Não-binário' ? 'selected' : '' ?>>Não-binário</option>
                    <option value="Outro" <?= $usuario['genero'] === 'Outro' ? 'selected' : '' ?>>Outro</option>
                    <option value="Prefiro não informar" <?= $usuario['genero'] === 'Prefiro não informar' ? 'selected' : '' ?>>Prefiro não informar</option>
                </select>

                <label for="telefone">Telefone</label>
                <input type="text" name="telefone" id="telefone" maxlength="20" placeholder="(11) 99999-9999" value="<?= htmlspecialchars($usuario['telefone']) ?>">

                <label for="email">Email</label>
                <input type="email" name="email" id="email" maxlength="100" placeholder="ex.: usuario@email.com" value="<?= htmlspecialchars($usuario['email']) ?>">

                <label>Cargo</label>
                <p><?= htmlspecialchars($usuario['cargo_nome']) ?></p>

                <label>Status</label>
                <p><?= htmlspecialchars($usuario['status']) ?></p>

                <button type="submit">Atualizar Informações</button>
            </form>
        </section>
    </main>

    <?php include "../includes/dashboard-footer.php" ?>
</body>

</html>