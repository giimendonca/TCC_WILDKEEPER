<?php

session_start();

include "../includes/conexao.php";
include "../includes/autenticacao.php";

if (!isset($_SESSION['id'])) {
    header("Location: ../auth/login.php");
    exit();
}

requireNivel(60);

$instituicao_id = $_SESSION['instituicao_id'];

// Pega os dados enviados pelo formulário
$id = $_POST['id'] ?? null;
$nome_vacina = trim($_POST['nome_vacina'] ?? '');
$data_aplicacao = trim($_POST['data_aplicacao'] ?? '');
$proxima_aplicacao = trim($_POST['proxima_aplicacao'] ?? '');
$observacoes = trim($_POST['observacoes'] ?? '');

// Verifica os campos obrigatórios
if (!$id || !$nome_vacina || !$data_aplicacao) {
    die("Preencha todos os campos obrigatórios.");
}

if (empty($proxima_aplicacao)) {
    $proxima_aplicacao = null;
}

if (empty($observacoes)) {
    $observacoes = null;
}

// ====================================
// Busca e valida a vacinação
// ====================================

$sql = "SELECT
    vacinas.id
FROM vacinas

INNER JOIN eventos ON vacinas.evento_id = eventos.id

WHERE vacinas.id = ?
AND eventos.tipo = 'Vacinação'
AND eventos.instituicao_id = ?";

$stmt = $conexao->prepare($sql);
$stmt->bind_param("ii", $id, $instituicao_id);
$stmt->execute();

$result = $stmt->get_result();
$vacina = $result->fetch_assoc();

if (!$vacina) {
    die("Vacinação não encontrada.");
}

// ====================================
// Atualiza a vacinação
// ====================================

$sql = "UPDATE vacinas
SET nome_vacina = ?,
    data_aplicacao = ?,
    proxima_aplicacao = ?,
    observacoes = ?
WHERE id = ?";

$stmt = $conexao->prepare($sql);

$stmt->bind_param(
    "ssssi",
    $nome_vacina,
    $data_aplicacao,
    $proxima_aplicacao,
    $observacoes,
    $id
);

if (!$stmt->execute()) {
    die("Erro ao atualizar a vacinação.");
}

header("Location: mostrar_vacina.php?id=" . $id . "&sucesso=atualizado");
exit();