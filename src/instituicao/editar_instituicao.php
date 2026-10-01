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

// Verifica a permissão que o usuário possui
requireNivel(100);

// Pega o id da instituição
$instituicaoId = trim($_GET['id'] ?? '');

// Verifica se o id veio vazio
if (empty($instituicaoId)) {
    die("ID inválido.");
}

// Faz o SELECT da instituição
$sql = "SELECT id, nome, cnpj, email, telefone, website, rua, numero, bairro, cidade, estado, cep
        FROM instituicoes
        WHERE id = ? AND id = ?";

$stmt = $conexao->prepare($sql);
$stmt->bind_param("ii", $instituicaoId, $_SESSION['instituicao_id']);
$stmt->execute();

$result = $stmt->get_result();

$instituicao = $result->fetch_assoc();

// Verifica se a instituição foi encontrada
if (!$instituicao) {
    die("Instituição não encontrada.");
}
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Instituição | WildKeeper</title>
    <link rel="stylesheet" href="../../assets/css/reset.css">
    <link rel="stylesheet" href="../../assets/css/dashboard_global.css">
    <?php include "../includes/fonte.php" ?>
</head>

<body>
    <?php include "../includes/dashboard-header.php" ?>

    <main>
        <section>
            <h1>Editar Instituição: <?= htmlspecialchars($instituicao['nome']) ?></h1>

            <form action="atualizar_instituicao.php" method="post">

                <input type="hidden" name="id" value="<?= $instituicaoId ?>">

                <label for="nome">Nome da Instituição</label>
                <input type="text" name="nome" id="nome" maxlength="100" placeholder="Digite o nome da instituição" value="<?= htmlspecialchars($instituicao['nome']) ?>">

                <label for="cnpj">CNPJ</label>
                <input type="text" name="cnpj" id="cnpj" maxlength="18" placeholder="00.000.000/0000-00" value="<?= htmlspecialchars($instituicao['cnpj']) ?>">

                <label for="email">Email</label>
                <input type="email" name="email" id="email" maxlength="100" placeholder="ex.: contato@instituicao.com" value="<?= htmlspecialchars($instituicao['email']) ?>">

                <label for="telefone">Telefone</label>
                <input type="text" name="telefone" id="telefone" maxlength="15" placeholder="(11) 99999-9999" value="<?= htmlspecialchars($instituicao['telefone']) ?>">

                <label for="website">Website</label>
                <input type="url" name="website" id="website" maxlength="255" placeholder="https://www.exemplo.com.br" value="<?= htmlspecialchars($instituicao['website'] ?? '') ?>">

                <label for="cep">CEP</label>
                <input type="text" name="cep" id="cep" maxlength="9" placeholder="00000-000" value="<?= htmlspecialchars($instituicao['cep']) ?>">

                <p id="mensagem-cep"></p>

                <label for="rua">Rua</label>
                <input type="text" name="rua" id="rua" maxlength="150" placeholder="Digite o nome da rua" value="<?= htmlspecialchars($instituicao['rua']) ?>">

                <label for="numero">Número</label>
                <input type="text" name="numero" id="numero" maxlength="20" placeholder="Ex.: 123 ou S/N" value="<?= htmlspecialchars($instituicao['numero']) ?>">

                <label for="bairro">Bairro</label>
                <input type="text" name="bairro" id="bairro" maxlength="80" placeholder="Digite o bairro" value="<?= htmlspecialchars($instituicao['bairro']) ?>">

                <label for="cidade">Cidade</label>
                <input type="text" name="cidade" id="cidade" maxlength="80" placeholder="Digite a cidade" value="<?= htmlspecialchars($instituicao['cidade']) ?>">

                <label for="estado">Estado</label>
                <select name="estado" id="estado" data-estado-atual="<?= htmlspecialchars($instituicao['estado']) ?>">
                    <option value="">Carregando estados...</option>
                </select>

                <button type="submit">Atualizar Instituição</button>
            </form>
        </section>
    </main>

    <?php include "../includes/dashboard-footer.php" ?>

    <script src="../../assets/js/instituicao.js"></script>
</body>

</html>