<?php
include "../includes/conexao.php";
session_start();

include "../includes/autenticacao.php";

// Verifica se ja existe uma sessão
if (!isset($_SESSION['id'])) {
    header("Location: ../auth/login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard | WildKeeper</title>
</head>

<body>
    <?php include "../includes/dashboard-header.php" ?>
    <main>
        <section>
            <h1>Olá, <?= htmlspecialchars($_SESSION['nome']) ?>!</h1>
            <p>Bem-vindo(a) novamente ao WildKeeper</p>
        </section>
        <h3>Painel</h3>

        <?php if (nivelMinimo(100)): ?>
            <section id="">
                <h4>Área Administrativa</h4>

                <a href="">Cadastrar Funcionário</a>
                <a href="">Cadastrar Cargo</a>
                <a href="">Configurações</a>
            </section>

        <?php endif; ?>

        <?php if (nivelMinimo(60)): ?>
            <section id="">
                <h4>Veterinário</h4>

                <a href="">Prontuários</a>
                <a href="">Consultas</a>
            </section>
        <?php endif; ?>

        <?php if (nivelMinimo(40)): ?>
            <section id="">
                <h4>Tratador</h4>

                <a href="">Alimentação</a>
            </section>
        <?php endif; ?>
    </main>

    <?php include "../includes/dashboard-footer.php" ?>
</body>

</html>