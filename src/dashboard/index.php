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
        <h2>Painel</h2>

        <section>

            <h3>Resumo</h3>

            <article>
                <h4>Animais</h4>
                <p>0</p>
            </article>

            <article>
                <h4>Espécies</h4>
                <p>0</p>
            </article>

            <article>
                <h4>Habitats</h4>
                <p>0</p>
            </article>

            <article>
                <h4>Eventos</h4>
                <p>0</p>
            </article>

        </section>

        <section>
            <h2>Acesso Rápido</h2>

            <?php if (nivelMinimo(100)): ?>
                <article>
                    <h3>Adminsitração</h3>

                    <a href="../funcionarios/index.php">Funcionários</a>
                    <a href="">Configurações</a>
                </article>

            <?php endif; ?>

            <?php if (nivelMinimo(60)): ?>
                <article>
                    <h3>Veterinária</h3>

                    <a href="">Consultas</a>
                    <a href="">Vacinas</a>
                    <a href="">Medicamentos</a>
                </article>
            <?php endif; ?>

            <?php if (nivelMinimo(40)): ?>
                <article>
                    <h3>Manejo</h3>

                    <a href="">Alimentação</a>
                    <a href="">Habitats</a>
                    <a href="../especies/index.php">Espécies</a>
                </article>
            <?php endif; ?>

            <?php if (nivelMinimo(20)): ?>
                <article>
                    <h3>Operações</h3>

                    <a href="">Animais</a>
                    <a href="">Eventos</a>
                </article>
            <?php endif; ?>
        </section>

    </main>

    <?php include "../includes/dashboard-footer.php" ?>
</body>

</html>