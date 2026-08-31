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

// Faz o select das categorias, classificações e riscos existentes
$categorias = selectTabela($conexao, "categorias");
$classificacoes = selectTabela($conexao, "classificacao_alimentar");
$riscos = selectTabela($conexao, "risco_extincao");
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nova Espécie | WildKeeper</title>
</head>

<body>
    <?php include "../includes/dashboard-header.php" ?>
    <main>
        <section>
            <h1>Nova Espécie</h1>

            <form action="salvar_especie.php" method="post">
                <label for="nome_popular">Nome Popular</label>
                <input type="text" name="nome_popular" id="nome_popular" maxlength="255" placeholder="Digite o nome comum" required>

                <label for="nome_cientifico">Nome Científico</label>
                <input type="text" name="nome_cientifico" id="nome_cientifico" maxlength="255" placeholder="Digite o nome científico" required>

                <label for="descricao">Descrição</label>
                <textarea name="descricao" id="descricao" required></textarea>

                <label for="origem">Origem</label>
                <input type="text" name="origem" id="origem" placeholder="País de origem" required>

                <label for="vida_media">Vida Média</label>
                <input type="number" name="vida_media" id="vida_media" min="1" placeholder="Em anos" required>

                <label for="peso_medio">Peso Médio</label>
                <input type="number" name="peso_medio" id="peso_medio" step="0.001" min="0.001" placeholder="Em Kg" required>                

                <label for="altura_media">Altura Média</label>
                <input type="number" name="altura_media" id="altura_media" step="0.001" min="0.001" placeholder="Em metros" required>

                <label for="categoria">Categoria</label>
                <select name="categoria" id="categoria" required>
                    <option value="">Selecione</option>
                    <?php while($c = $categorias->fetch_assoc()): ?>
                        <option value="<?= htmlspecialchars($c['id']) ?>"><?= htmlspecialchars($c['nome']) ?></option>
                    <?php endwhile; ?>
                </select>

                <label for="classificacao_alimentar">Classificação Alimentar</label>
                <select name="classificacao_alimentar" id="classificacao_alimentar">
                    <option value="">Selecione</option>
                    <?php while($c = $classificacoes->fetch_assoc()): ?>
                        <option value="<?= htmlspecialchars($c['id']) ?>"><?= htmlspecialchars($c['nome']) ?></option>
                    <?php endwhile; ?>
                </select>

                <label for="risco_extincao">Risco de Extinção</label>
                <select name="risco_extincao" id="risco_extincao" required>
                    <option value="">Selecione</option>
                    <?php while($r = $riscos->fetch_assoc()): ?>
                        <option value="<?= htmlspecialchars($r['id']) ?>"><?= htmlspecialchars($r['nome']) ?></option>
                    <?php endwhile; ?>
                </select>

                <button type="submit">Salvar Espécie</button>
            </form>
        </section>
    </main>
    <?php include "../includes/dashboard-footer.php" ?>
</body>

</html>