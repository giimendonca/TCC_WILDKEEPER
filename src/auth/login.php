<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | WildKeeper</title>
    <link rel="stylesheet" href="../../assets/css/reset.css">
    <link rel="stylesheet" href="../../assets/css/global.css">
    <link rel="stylesheet" href="../../assets/css/login.css">
</head>

<body>
    <?php include "../includes/header.php" ?>
    <main>
        <section id="hero">
            <h1>Bem-vindo de volta!</h1>
            <p>Faça login para acessar o sistema.</p>
        </section>
        <section id="login">
            <form action="verificar_login.php" method="post">
                <fieldset>
                    <legend>Login</legend>

                    <div class="campo">
                        <label for="email">Email</label>
                        <input type="email" name="email" id="email" placeholder="Digite seu email" autocomplete="email" required>
                    </div>

                    <div class="campo">
                        <label for="senha">Senha</label>
                        <input type="password" name="senha" id="senha" placeholder="Digite sua senha" autocomplete="current-password" required>
                    </div>

                    <div class="links">
                        <div class="lembrar">
                            <input type="checkbox" name="lembre" id="lembre">
                            <label for="lembre">Lembrar-me</label>
                        </div>

                        <a href="recuperar_senha.php">Esqueceu sua senha?</a>
                    </div>

                    <button type="submit">Entrar</button>
                </fieldset>
            </form>
        </section>
        <section id="cadastrar">
            <h2>Ainda não possui uma instituição?</h2>

            <a href="../setup/instituicao.php">Cadastrar Instituição</a>

        </section>
    </main>
    <?php include "../includes/footer.php" ?>
    <script src="/TCC_WILDKEEPER/assets/js/navbar.js"></script>
</body>

</html>