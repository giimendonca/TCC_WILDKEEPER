<?php
include "../includes/conexao.php";
include "../includes/funcoes.php";
session_start();

include "../includes/autenticacao.php";

// Verifica se ja existe uma sessão
if (!isset($_SESSION['id'])) {
    header("Location: ../auth/login.php");
    exit();
}

// Verifica a permissão que o usuário possui
requireNivel(40);

?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Novo Habitat | WildKeeper</title>
</head>

<body>
    <?php include "../includes/dashboard-header.php" ?>
    <main>
        <section>
            <h1>Novo Habitat</h1>

            <form action="salvar_habitat.php" method="post">


                <label for="nome">Nome do Habitat</label>
                <input type="text" name="nome" id="nome" maxlength="50" placeholder="Digite o nome do habitat" required>

                <label for="descricao">Descrição</laøbel>
                <textarea name="descricao" id="descricao" required></textarea>

                <label for="bioma">Bioma</label>
                <input type="text" name="bioma" id="bioma" maxlength="50" placeholder="Digite o nome do bioma" required>

                <label for="temperatura">Temperatura</label>
                <input type="number" name="temperatura" id="temperatura" step="0.01" placeholder="Digite a temperatura em graus Celsius" required>

                <label for="umidade">Umidade Relativa do Ar</label>
                <input type="number" name="umidade" id="umidade" step="0.01" min="0" max="100" placeholder="Digite a umidade em porcentagem" required>

                <label for="capacidade">Capacidade</label>
                <input type="number" name="capacidade" id="capacidade" step="1" min="1" placeholder="Digite a capacidade máxima" required>

                <label for="status">Status</label>
                <select name="status" id="status">
                    <option value="">Selecione</option>
                    <option value="Ativo">Ativo</option>
                    <option value="Em manutenção">Em manutenção</option>
                    <option value="Interditado">Interditado</option>
                </select>

                <button type="submit">Salvar Habitat</button>
            </form>
        </section>
    </main>
    <?php include "../includes/dashboard-footer.php" ?>
</body>

</html>