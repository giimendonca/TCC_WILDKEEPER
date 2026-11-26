<?php

session_start();
require_once __DIR__ . '/../includes/csrf.php';

include "../includes/conexao.php";
include "../includes/funcoes.php";
include "../includes/autenticacao.php";

// Verifica se já existe uma sessão
if (!isset($_SESSION['id'])) {
    header("Location: ../auth/login.php");
    exit();
}

// Verifica a permissão que o usuário possui
requireNivel(40);

// Pega o ID do animal
$animalId = trim($_GET['id'] ?? '');

// Verifica se o ID veio vazio
if (empty($animalId)) {
    die("ID inválido.");
}

// Faz o select do animal e confirma se ele pertence a instituição e existe
$sql = "SELECT id, nome
        FROM animais
        WHERE id = ? AND instituicao_id = ?";

$stmt = $conexao->prepare($sql);
$stmt->bind_param("ii", $animalId, $_SESSION['instituicao_id']);
$stmt->execute();

$result = $stmt->get_result();
$animal = $result->fetch_assoc();

// Verifica se existe 
if (!$animal) {
    die("Animal não encontrado.");
}

// Busca as fotos
$sqlFotos = "SELECT id, caminho_arquivo, descricao
             FROM animais_fotos
             WHERE animal_id = ? AND instituicao_id = ?";

$stmtFotos = $conexao->prepare($sqlFotos);
$stmtFotos->bind_param("ii", $animalId, $_SESSION['instituicao_id']);
$stmtFotos->execute();

$resultFotos = $stmtFotos->get_result();
$quantidadeFotos = $resultFotos->num_rows;
$vagasFotos = max(0, 5 - $quantidadeFotos);
$mensagemSucessoFotos = $_SESSION['mensagem_sucesso_fotos'] ?? '';
unset($_SESSION['mensagem_sucesso_fotos']);
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Animal | WildKeeper</title>
    <link rel="stylesheet" href="../../assets/css/reset.css">
    <link rel="stylesheet" href="../../assets/css/dashboard_global.css">
    <?php include "../includes/fonte.php" ?>
</head>

<body>
    <?php include "../includes/dashboard-header.php" ?>
    <main>
        <section>
            <h1>Fotos: <?= htmlspecialchars($animal['nome']) ?></h1>

            <?php if ($mensagemSucessoFotos !== ''): ?>
                <p role="status" style="color: var(--sucesso); font-weight: 600;">
                    <?= htmlspecialchars($mensagemSucessoFotos, ENT_QUOTES, 'UTF-8') ?>
                </p>
            <?php endif; ?>

            <?php if ($resultFotos->num_rows > 0): ?>

                <?php while ($foto = $resultFotos->fetch_assoc()): ?>

                    <div>

                        <img
                            src="../../<?= htmlspecialchars($foto['caminho_arquivo']) ?>"
                            alt="<?= htmlspecialchars($foto['descricao']) ?>"
                            width="200">

                        <p>
                            <?= htmlspecialchars($foto['descricao']) ?>
                        </p>

                        <form action="excluir_foto.php" method="post">
                            <input type="hidden" name="csrf_token" value="<?= htmlspecialchars(csrfToken(), ENT_QUOTES, "UTF-8") ?>">
                            <input type="hidden" name="id" value="<?= htmlspecialchars($foto["id"]) ?>">
                            <input type="hidden" name="animal_id" value="<?= htmlspecialchars($animal["id"]) ?>">
                            <button type="submit" class="link-excluir-foto">Excluir foto</button>
                        </form>

                    </div>

                <?php endwhile; ?>

            <?php else: ?>

                <p>O animal ainda não possui fotos.</p>

            <?php endif; ?>

            <h2>Adicionar novas fotos</h2>

            <p id="statusQuantidadeFotos" role="status" aria-live="polite"></p>

            <form action="salvar_fotos.php" method="post" enctype="multipart/form-data">
                <input type="hidden" name="csrf_token" value="<?= htmlspecialchars(csrfToken(), ENT_QUOTES, "UTF-8") ?>">

                <input
                    type="hidden"
                    name="animal_id"
                    value="<?= htmlspecialchars($animal['id']) ?>">

                <label for="fotos">Fotos</label>

                <input
                    type="file"
                    name="fotos[]"
                    id="fotos"
                    accept="image/*"
                    multiple
                    <?= $vagasFotos === 0 ? 'disabled' : '' ?>>

                <div id="descricoesFotos"></div>

                <button type="submit" id="botaoAdicionarFotos" <?= $vagasFotos === 0 ? 'disabled' : '' ?>>Adicionar fotos</button>

            </form>

        </section>
    </main>
    <?php include "../includes/dashboard-footer.php" ?>

    <script>
        const inputFotos = document.getElementById("fotos");
        const descricoesFotos = document.getElementById("descricoesFotos");
        const statusQuantidadeFotos = document.getElementById("statusQuantidadeFotos");
        const botaoAdicionarFotos = document.getElementById("botaoAdicionarFotos");
        const quantidadeFotosExistentes = <?= (int) $quantidadeFotos ?>;
        const vagasFotos = <?= (int) $vagasFotos ?>;

        function atualizarStatusQuantidade() {
            const selecionadas = inputFotos.files.length;
            const restantes = Math.max(0, vagasFotos - selecionadas);

            if (vagasFotos === 0) {
                statusQuantidadeFotos.textContent = `Você já possui ${quantidadeFotosExistentes} fotos. Remova alguma foto antes de adicionar outra.`;
            } else if (selecionadas === 0) {
                statusQuantidadeFotos.textContent = `Você já possui ${quantidadeFotosExistentes} fotos e pode adicionar mais ${vagasFotos}.`;
            } else {
                statusQuantidadeFotos.textContent = `Você já possui ${quantidadeFotosExistentes} fotos, selecionou ${selecionadas} e ainda pode selecionar mais ${restantes}.`;
            }

            botaoAdicionarFotos.disabled = vagasFotos === 0 || selecionadas === 0 || selecionadas > vagasFotos;
        }

        inputFotos.addEventListener("change", function() {

            descricoesFotos.innerHTML = "";

            if (inputFotos.files.length > vagasFotos) {
                alert(`Você já possui ${quantidadeFotosExistentes} fotos e pode adicionar mais ${vagasFotos}. Remova alguma foto ou selecione menos arquivos.`);
                inputFotos.value = "";
                atualizarStatusQuantidade();
                return;
            }

            for (let i = 0; i < inputFotos.files.length; i++) {

                const arquivo = inputFotos.files[i];

                const div = document.createElement("div");

                const nomeArquivo = document.createElement("p");
                nomeArquivo.textContent = `Foto: ${arquivo.name}`;

                const label = document.createElement("label");
                label.htmlFor = `descricao-${i}`;
                label.textContent = "Descrição";

                const input = document.createElement("input");

                input.id = `descricao-${i}`;
                input.type = "text";
                input.name = "descricoes[]";
                input.maxLength = 255;
                input.required = true;
                input.placeholder = "Descreva essa foto";

                div.appendChild(nomeArquivo);
                div.appendChild(label);
                div.appendChild(input);

                descricoesFotos.appendChild(div);
            }

            atualizarStatusQuantidade();
        });

        document.querySelector('form[action="salvar_fotos.php"]').addEventListener("submit", function(event) {
            if (inputFotos.files.length === 0 || inputFotos.files.length > vagasFotos) {
                event.preventDefault();
                alert(vagasFotos === 0
                    ? "Remova alguma foto antes de adicionar outra."
                    : `Selecione no máximo ${vagasFotos} foto(s).`);
            }
        });

        atualizarStatusQuantidade();
    </script>
</body>

</html>
