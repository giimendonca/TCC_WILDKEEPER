<?php

include "../includes/conexao.php";
include "../includes/funcoes.php";

session_start();

include "../includes/autenticacao.php";

// ====================================
// Verificações de sessão
// ====================================

// Verifica se já existe uma sessão
if (!isset($_SESSION['id'])) {
    header("Location: ../auth/login.php");
    exit();
}

// Verifica a permissão que o usuário possui
requireNivel(60);

?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Novo Medicamento | WildKeeper</title>
</head>
<body>

    <?php include "../includes/dashboard-header.php" ?>

    <main>
        <section>

            <h1>Novo Medicamento</h1>

            <form action="salvar_medicamento.php" method="post">

                <label for="nome">Nome</label>
                <input type="text" name="nome" id="nome" maxlength="255" placeholder="Digite o nome do medicamento" required>

                <label for="descricao">Descrição</label>
                <textarea name="descricao" id="descricao" placeholder="Digite uma descrição do medicamento"></textarea>

                <label for="fabricante">Fabricante</label>
                <input type="text" name="fabricante" id="fabricante" maxlength="255" placeholder="Digite o fabricante" required>

                <label for="estoque">Estoque</label>
                <input type="number" name="estoque" id="estoque" min="0" placeholder="Quantidade em estoque" required>

                <label for="lote">Lote</label>
                <input type="text" name="lote" id="lote" maxlength="100" placeholder="Digite o lote" required>

                <label for="vencimento">Vencimento</label>
                <input type="date" name="vencimento" id="vencimento" required>

                <button type="submit">Salvar Medicamento</button>

            </form>

        </section>
    </main>

    <?php include "../includes/dashboard-footer.php" ?>

</body>
</html>