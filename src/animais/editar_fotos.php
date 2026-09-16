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
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Animal | WildKeeper</title>
</head>

<body>
    <?php include "../includes/dashboard-header.php" ?>
    <main>
        <section>
            <h1>Fotos: <?= htmlspecialchars($animal['nome']) ?></h1>

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

                        <a href="excluir_foto.php?id=<?= htmlspecialchars($foto['id']) ?>&animal_id=<?= htmlspecialchars($animal['id']) ?>">
                            Excluir foto
                        </a>

                    </div>

                <?php endwhile; ?>

            <?php else: ?>

                <p>O animal ainda não possui fotos.</p>

            <?php endif; ?>

            <h2>Adicionar novas fotos</h2>

            <form action="salvar_fotos.php" method="post" enctype="multipart/form-data">

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
                    multiple>

                <p>Você pode adicionar até 5 fotos no total.</p>

                <div id="descricoesFotos"></div>

                <button type="submit">Adicionar fotos</button>

            </form>

        </section>
    </main>
    <?php include "../includes/dashboard-footer.php" ?>

    <script>
        const inputFotos = document.getElementById("fotos");
        const descricoesFotos = document.getElementById("descricoesFotos");

        inputFotos.addEventListener("change", function() {

            descricoesFotos.innerHTML = "";

            if (inputFotos.files.length > 5) {
                alert("Você pode selecionar no máximo 5 fotos.");
                inputFotos.value = "";
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
        });
    </script>
</body>

</html>