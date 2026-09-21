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
$consultaId = $_POST['id'] ?? null;
$diagnostico = trim($_POST['diagnostico'] ?? '');
$tratamento = trim($_POST['tratamento'] ?? '');
$observacoes = trim($_POST['observacoes'] ?? '');
$data_retorno = trim($_POST['data_retorno'] ?? '');

// Verifica os campos obrigatórios
if (!$consultaId || !$diagnostico || !$tratamento) {
    die("Preencha todos os campos obrigatórios.");
}

// Verifica se a consulta pertence à instituição
$sqlConsulta = "SELECT consultas.id
FROM consultas
INNER JOIN eventos ON consultas.evento_id = eventos.id
WHERE consultas.id = ?
AND eventos.instituicao_id = ?
AND eventos.tipo = 'Consulta'";

$stmtConsulta = $conexao->prepare($sqlConsulta);
$stmtConsulta->bind_param("ii", $consultaId, $instituicao_id);
$stmtConsulta->execute();

$resultConsulta = $stmtConsulta->get_result();

if ($resultConsulta->num_rows === 0) {
    die("Consulta inválida.");
}

// Atualiza a consulta
$sql = "UPDATE consultas
SET diagnostico = ?,
    tratamento = ?,
    observacoes = ?,
    data_retorno = ?
WHERE id = ?";

$stmt = $conexao->prepare($sql);

$stmt->bind_param(
    "ssssi",
    $diagnostico,
    $tratamento,
    $observacoes,
    $data_retorno,
    $consultaId
);

if ($stmt->execute()) {
    header("Location: mostrar_consulta.php?id=" . $consultaId . "&sucesso=atualizado");
    exit();
}

die("Erro ao atualizar consulta: " . $conexao->error);