<?php
include "../includes/conexao.php";
session_start();

include "../includes/autenticacao.php";

// Verifica se ja existe uma sessão
if (!isset($_SESSION['id'])) {
    header("Location: ../auth/login.php");
    exit();
}

// Verifica a permissão que o usuário possui
requireNivel(100);

// Faz o select dos cargos existentes
$sql = "SELECT * FROM cargos";

$stmt = $conexao->prepare($sql);
$stmt->execute();

$result = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Novo Funcionário | WildKeeper</title>
</head>

<body>
    <?php include "../includes/dashboard-header.php" ?>
    <main>
        <section>
            <h1>Novo Funcionário</h1>

            <form action="salvar_funcionario.php" method="post">
                <label for="nome">Nome do Funcionário</label>
                <input type="text" name="nome" id="nome" maxlength="100" placeholder="Digite o nome do usuário" required>

                <label for="cpf">CPF</label>
                <input type="text" name="cpf" id="cpf" maxlength="14" placeholder="000.000.000-00" required>

                <label for="data_nascimento">Data Nascimento</label>
                <input type="date" name="data_nascimento" id="data_nascimento" required>

                <label for="genero">Genêro</label>
                <select name="genero" id="genero" required>
                    <option value="">Selecione...</option>
                    <option value="Masculino">Masculino</option>
                    <option value="Feminino">Feminino</option>
                    <option value="Não-binário">Não-binário</option>
                    <option value="Outro">Outro</option>
                    <option value="Prefiro não informar">Prefiro não informar</option>
                </select>

                <label for="telefone">Telefone</label>
                <input type="text" name="telefone" id="telefone" maxlength="20" placeholder="(11) 99999-9999" required>


                <label for="email">Email</label>
                <input type="email" name="email" id="email" maxlength="100" placeholder="ex.: administrador@email.com" required>

                <label for="cargo_id">Cargo</label>
                <select name="cargo_id" id="cargo_id" required>
                    <option value="">Selecione...</option>
                    <?php while($cargo = $result->fetch_assoc()): ?>
                        <option value="<?= $cargo['id'] ?>"><?= htmlspecialchars($cargo['nome']) ?></option>
                    <?php endwhile; ?>
                </select>

                <label for="senha">Senha</label>
                <input type="password" name="senha" id="senha" minlength="6" placeholder="Digite uma senha" autocomplete="new-password" required>

                <label for="confirmar_senha">Confirmar senha</label>
                <input type="password" name="confirmar_senha" id="confirmar_senha" minlength="6" placeholder="Confirme a senha" autocomplete="new-password" required>

                <button type="submit">Salvar Funcionário</button>
            </form>
        </section>
    </main>
    <?php include "../includes/dashboard-footer.php" ?>
</body>

</html>