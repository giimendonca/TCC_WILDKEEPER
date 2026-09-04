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

// Faz o select das especies, habitats e status_animal existentes
$especies = selectTabela($conexao, "especies");
$habitats = selectTabela($conexao, "habitats");
$statusAnimais = selectTabela($conexao, "status_animais");
$saudeStatus = selectTabela($conexao, "saude_status");
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Novo Animal | WildKeeper</title>
</head>

<body>
    <?php include "../includes/dashboard-header.php" ?>
    <main>
        <section>
            <h1>Novo Animal</h1>

            <form action="salvar_animal.php" method="post" enctype="multipart/form-data">

                <label for="nome">Nome</label>
                <input type="text" name="nome" id="nome" maxlength="100" placeholder="Digite o nome do animal" required>

                <label for="sexo">Sexo</label>
                <select name="sexo" id="sexo" required>
                    <option value="Masculino">Masculino</option>
                    <option value="Feminino">Feminino</option>
                    <option value="Indeterminado">Indeterminado</option>
                </select>

                <label for="data_nascimento">Data de Nascimento</label>
                <input type="date" name="data_nascimento" id="data_nascimento" required>

                <label for="data_chegada">Data de Chegada</label>
                <input type="date" name="data_chegada" id="data_chegada" required>

                <label for="peso">Peso (Kg)</label>
                <input type="number" name="peso" id="peso" step="0.001" min="0.001" placeholder="Em Kg" required>

                <label for="altura">Altura (cm)</label>
                <input type="number" name="altura" id="altura" step="0.001" min="0.001" placeholder="Em centímetros" required>

                <label for="microchip">Microchip</label>
                <input type="text" name="microchip" id="microchip" maxlength="20" placeholder="Digite o código do microchip" required>

                <label for="observacoes">Observações</label>
                <textarea name="observacoes" id="observacoes" placeholder="Digite informações importantes sobre o animal..."></textarea>

                <label for="especie_id">Espécie</label>
                <select name="especie_id" id="especie_id" required>
                    <option value="">Selecione</option>
                    <?php while ($e = $especies->fetch_assoc()): ?>
                        <option value="<?= htmlspecialchars($e['id']) ?>"><?= htmlspecialchars($e['nome_popular']) ?></option>
                    <?php endwhile; ?>
                </select>

                <p>Ainda não encontrou a espécie? <a href="../especies/cadastrar_especie.php">Cadastre uma nova espécie.</a></p>

                <label for="habitat_id">Habitat</label>
                <select name="habitat_id" id="habitat_id" required>
                    <option value="">Selecione</option>
                    <?php while ($h = $habitats->fetch_assoc()): ?>
                        <option value="<?= htmlspecialchars($h['id']) ?>"><?= htmlspecialchars($h['nome']) ?></option>
                    <?php endwhile; ?>
                </select>

                <p>Ainda não encontrou o habitat? <a href="../habitats/cadastrar_habitat.php">Cadastre um novo habitat.</a></p>

                <label for="status_id">Status do Animal</label>
                <select name="status_id" id="status_id" required>
                    <option value="">Selecione</option>
                    <?php while ($s = $statusAnimais->fetch_assoc()): ?>
                        <option value="<?= htmlspecialchars($s['id']) ?>"><?= htmlspecialchars($s['nome']) ?></option>
                    <?php endwhile; ?>
                </select>

                <label for="saude_status_id">Status de Saúde</label>
                <select name="saude_status_id" id="saude_status_id" required>
                    <option value="">Selecione</option>
                    <?php while ($s = $saudeStatus->fetch_assoc()): ?>
                        <option value="<?= htmlspecialchars($s['id']) ?>"><?= htmlspecialchars($s['nome']) ?></option>
                    <?php endwhile; ?>
                </select>

                <label for="fotos">Fotos do Animal</label>
                <input type="file" name="fotos[]" id="fotos" accept="image/*" multiple>
                <p>Você pode adicionar até 5 fotos. Cada foto deve possuir uam descrição.</p>

                <div id="descricoesFotos">

                </div>

                <button type="submit">Salvar Animal</button>
            </form>
        </section>
    </main>
    <?php include "../includes/dashboard-footer.php" ?>

    <script>
        const inputFotos = document.getElementById("fotos")
        const descricoesFotos = document.getElementById("descricoesFotos")

        inputFotos.addEventListener("change", function() {
            descricoesFotos.innerHTML = ""

            if (inputFotos.files.length > 5) {
                alert("Você pode selecionar no máximo 5 fotos.")
                inputFotos.value = ""
                return
            }

            for (let i = 0; i < inputFotos.files.length; i++) {
                const arquivo = inputFotos.files[i]

                const div = document.createElement("div")

                const nomeArquivo = document.createElement("p")
                nomeArquivo.textContent = `Foto: ${arquivo.name}`

                const label = document.createElement("label")
                label.htmlFor = `descricao-${i}`
                label.textContent = "Descrição"

                const input = document.createElement("input")
                input.id = `descricao-${i}`
                input.type = "text"
                input.name = "descricoes[]"
                input.maxLength = 255
                input.required = true
                input.placeholder = "Descreva essa foto"

                div.appendChild(nomeArquivo)
                div.appendChild(label)
                div.appendChild(input)

                descricoesFotos.appendChild(div)

            }
        })
    </script>
</body>

</html>