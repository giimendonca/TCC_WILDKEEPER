<?php

require_once __DIR__ . '/../includes/csrf.php';
exigirTokenCsrfValido();

include "../includes/conexao.php";
include "../includes/autenticacao.php";

if (!isset($_SESSION['id'])) {
    header("Location: ../auth/login.php");
    exit();
}

requireNivel(40);

$animalId = filter_var($_POST['animal_id'] ?? null, FILTER_VALIDATE_INT);

if (!$animalId || $animalId < 1) {
    die("ID do animal inválido.");
}

$instituicaoId = (int) $_SESSION['instituicao_id'];
$limiteFotos = 5;
$limiteArquivo = 5 * 1024 * 1024;
$tiposPermitidos = [
    "image/jpeg" => "jpeg",
    "image/png" => "png",
    "image/webp" => "webp"
];
$fotosNovas = [];
$arquivosSalvos = [];

if (isset($_FILES['fotos'])) {
    $arquivos = $_FILES['fotos'];

    if (!isset($arquivos['error'], $arquivos['tmp_name'], $arquivos['size']) || !is_array($arquivos['error'])) {
        die("Arquivos enviados inválidos.");
    }

    foreach ($arquivos['error'] as $indice => $erro) {
        if ($erro === UPLOAD_ERR_NO_FILE) {
            continue;
        }

        if ($erro !== UPLOAD_ERR_OK) {
            die("O upload falhou.");
        }

        $tmpName = $arquivos['tmp_name'][$indice] ?? '';
        $tamanho = $arquivos['size'][$indice] ?? 0;

        if (!is_string($tmpName) || !is_uploaded_file($tmpName)) {
            die("Arquivo enviado inválido.");
        }

        if (!is_numeric($tamanho) || $tamanho > $limiteArquivo) {
            die("O tamanho da imagem excede o limite de 5 MB.");
        }

        $finfo = new finfo(FILEINFO_MIME_TYPE);
        $mime = $finfo->file($tmpName);

        if (!isset($tiposPermitidos[$mime])) {
            die("O tipo de imagem não permitido.");
        }

        $descricaoRecebida = $_POST['descricoes'][$indice] ?? null;

        if (!is_string($descricaoRecebida)) {
            die("Cada foto deve possuir uma descri\u00e7\u00e3o.");
        }

        $descricao = trim($descricaoRecebida);

        if ($descricao === '' || mb_strlen($descricao, 'UTF-8') > 255) {
            die("Cada foto deve possuir uma descrição de até 255 caracteres.");
        }

        $fotosNovas[] = [
            "tmp_name" => $tmpName,
            "mime" => $mime,
            "descricao" => $descricao
        ];

        if (count($fotosNovas) > $limiteFotos) {
            die("Você pode adicionar no máximo 5 fotos por animal.");
        }
    }
}

try {
    $conexao->begin_transaction();

    $sqlAnimal = "SELECT id
                  FROM animais
                  WHERE id = ? AND instituicao_id = ?
                  FOR UPDATE";
    $stmtAnimal = $conexao->prepare($sqlAnimal);
    $stmtAnimal->bind_param("ii", $animalId, $instituicaoId);
    $stmtAnimal->execute();

    if (!$stmtAnimal->get_result()->fetch_assoc()) {
        throw new Exception("Animal não encontrado.");
    }

    $sqlContagem = "SELECT COUNT(*) AS total
                    FROM animais_fotos
                    WHERE animal_id = ? AND instituicao_id = ?";
    $stmtContagem = $conexao->prepare($sqlContagem);
    $stmtContagem->bind_param("ii", $animalId, $instituicaoId);
    $stmtContagem->execute();
    $totalFotos = (int) $stmtContagem->get_result()->fetch_assoc()['total'];

    if ($totalFotos + count($fotosNovas) > $limiteFotos) {
        throw new Exception("Cada animal pode possuir no máximo 5 fotos.");
    }

    $pasta = __DIR__ . "/../../assets/img/animais/";

    if (count($fotosNovas) > 0 && !is_dir($pasta)) {
        throw new Exception("Pasta de imagens indisponível.");
    }

    foreach ($fotosNovas as $foto) {
        $extensao = $tiposPermitidos[$foto['mime']];
        $nomeArquivo = bin2hex(random_bytes(16)) . "." . $extensao;
        $destino = $pasta . $nomeArquivo;

        if (!move_uploaded_file($foto['tmp_name'], $destino)) {
            throw new Exception("Erro ao salvar a imagem.");
        }

        $arquivosSalvos[] = $destino;
        $caminho = "assets/img/animais/" . $nomeArquivo;

        $sqlFoto = "INSERT INTO animais_fotos
                    (animal_id, caminho_arquivo, descricao, instituicao_id)
                    VALUES (?, ?, ?, ?)";
        $stmtFoto = $conexao->prepare($sqlFoto);
        $stmtFoto->bind_param("issi", $animalId, $caminho, $foto['descricao'], $instituicaoId);
        $stmtFoto->execute();
    }

    $conexao->commit();

    if (count($fotosNovas) > 0) {
        $quantidadeAdicionada = count($fotosNovas);
        $_SESSION['mensagem_sucesso_fotos'] = $quantidadeAdicionada === 1
            ? "Foto adicionada com sucesso."
            : $quantidadeAdicionada . " fotos adicionadas com sucesso.";
    }

    header("Location: editar_fotos.php?id=" . urlencode((string) $animalId));
    exit();
} catch (Throwable $e) {
    $conexao->rollback();

    foreach ($arquivosSalvos as $arquivo) {
        if (file_exists($arquivo)) {
            unlink($arquivo);
        }
    }

    die("Erro ao adicionar fotos: " . $e->getMessage());
}
