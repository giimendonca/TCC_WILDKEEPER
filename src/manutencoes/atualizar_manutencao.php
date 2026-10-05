<?php

session_start();

include "../includes/conexao.php";
include "../includes/autenticacao.php";

if (!isset($_SESSION['id'])) {
    header("Location: ../auth/login.php");
    exit();
}

requireNivel(40);

$instituicao_id = $_SESSION['instituicao_id'];

// ====================================
// Dados enviados
// ====================================

$id = $_POST['id'] ?? null;
$descricao = trim($_POST['descricao'] ?? '');

if (!$id || !$descricao) {
    die("Preencha todos os campos obrigatórios.");
}

// ====================================
// Verifica se a manutenção pertence
// à instituição do usuário
// ====================================

$sql = "SELECT manutencao_habitats.id

FROM manutencao_habitats

INNER JOIN eventos
    ON manutencao_habitats.evento_id = eventos.id

WHERE manutencao_habitats.id = ?
AND eventos.tipo = 'Manutenção'
AND eventos.instituicao_id = ?";

$stmt = $conexao->prepare($sql);
$stmt->bind_param("ii", $id, $instituicao_id);
$stmt->execute();

$result = $stmt->get_result();

if ($result->num_rows === 0) {
    die("Manutenção não encontrada.");
}

// ====================================
// Atualiza a manutenção
// ====================================

$sql = "UPDATE manutencao_habitats

SET descricao = ?

WHERE id = ?";

$stmt = $conexao->prepare($sql);
$stmt->bind_param("si", $descricao, $id);

if (!$stmt->execute()) {
    die("Erro ao atualizar a manutenção.");
}

header("Location: mostrar_manutencao.php?id=$id&sucesso=atualizado");
exit();