<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | WildKeeper</title>
</head>

<body>
    <?php include "../includes/header.php" ?>
    <main>
        <section id="">
            <h1>Bem-vindo de volta!</h1>
            <p>Faça login para acessar o sistema.</p>
        </section>
        <section id="">
            <form action="verificar_login.php" method="post">
                <fieldset>
                    <legend>Login</legend>

                    <label for="email">Email</label>
                    <input type="email" name="email" id="email" placeholder="Digite seu email" autocomplete="email" required>

                    <label for="senha">Senha</label>
                    <input type="password" name="senha" id="senha" placeholder="Digite sua senha" autocomplete="current-password" required>

                    <input type="checkbox" name="lembre" id="lembre">
                    <label for="lembre">Lembrar-me</label>

                    <a href="recuperar_senha.php">Esqueceu sua senha?</a>
                    <button type="submit">Entrar</button>
                </fieldset>
            </form>
        </section>
        <section id="">
            <h2>Ainda nâo possui uma instituição?</h2>

            <a href="../setup/instituicao.php">Cadastrar Instituição</a>

        </section>
    </main>
    <?php include "../includes/footer.php" ?>
</body>

</html>