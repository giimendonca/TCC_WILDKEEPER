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

// Pega o ID da foto
$fotoId = trim($_GET['id'] ?? '');

// Pega o ID do animal
$animalId = trim($_GET['animal_id'] ?? '');

// Verifica se os IDs vieram vazios
if (empty($fotoId) || empty($animalId)) {
    die("ID inválido.");
}

// Busca a foto verificando se pertence ao animal e à instituição
$sql = "SELECT caminho_arquivo
        FROM animais_fotos
        WHERE id = ?
        AND animal_id = ?
        AND instituicao_id = ?";

$stmt = $conexao->prepare($sql);

$stmt->bind_param(
    "iii",
    $fotoId,
    $animalId,
    $_SESSION['instituicao_id']
);

$stmt->execute();

$result = $stmt->get_result();

$foto = $result->fetch_assoc();

if (!$foto) {
    die("Foto não encontrada.");
}

// Exclui a foto do banco
$sql = "DELETE FROM animais_fotos
        WHERE id = ?
        AND animal_id = ?
        AND instituicao_id = ?";

$stmt = $conexao->prepare($sql);

$stmt->bind_param(
    "iii",
    $fotoId,
    $animalId,
    $_SESSION['instituicao_id']
);

$stmt->execute();

// Exclui o arquivo físico
$caminhoArquivo = "../../" . $foto['caminho_arquivo'];

if (file_exists($caminhoArquivo)) {
    unlink($caminhoArquivo);
}

// Volta para o gerenciamento de fotos
header("Location: editar_fotos.php?id=" . urlencode($animalId));
exit();

?>