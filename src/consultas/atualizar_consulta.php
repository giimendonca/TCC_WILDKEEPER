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
$medicamentos = $_POST['medicamentos'] ?? [];

// Verifica os campos obrigatórios
if (!$consultaId || !$diagnostico || !$tratamento) {
    die("Preencha todos os campos obrigatórios.");
}

if (empty($data_retorno)) {
    $data_retorno = null;
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

// Valida os medicamentos selecionados
// Valida os medicamentos selecionados
if (!is_array($medicamentos)) {
    die("Dados dos medicamentos inválidos.");
}

$medicamentosSelecionados = [];

foreach ($medicamentos as $medicamento_id => $dados) {

    if (!is_array($dados) || !isset($dados['selecionado'])) {
        continue;
    }

    $medicamento_id = filter_var($medicamento_id, FILTER_VALIDATE_INT);

    if (!$medicamento_id || $medicamento_id <= 0) {
        die("Medicamento inválido.");
    }

    $sqlMedicamento = "SELECT id
    FROM medicamentos
    WHERE id = ?
    AND instituicao_id = ?";

    $stmtMedicamento = $conexao->prepare($sqlMedicamento);
    $stmtMedicamento->bind_param("ii", $medicamento_id, $instituicao_id);
    $stmtMedicamento->execute();

    if ($stmtMedicamento->get_result()->num_rows === 0) {
        die("Um dos medicamentos selecionados é inválido.");
    }

    $medicamentosSelecionados[] = [
        'id' => $medicamento_id,
        'dosagem' => trim($dados['dosagem'] ?? ''),
        'observacoes' => trim($dados['observacoes'] ?? '')
    ];
}

$conexao->begin_transaction();

try {
    // Atualiza os dados da consulta
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

    if (!$stmt->execute()) {
        throw new Exception("Erro ao atualizar consulta.");
    }

    // Remove os relacionamentos anteriores
    $sqlExcluir = "DELETE FROM medicamentos_consulta
    WHERE consulta_id = ?";

    $stmtExcluir = $conexao->prepare($sqlExcluir);
    $stmtExcluir->bind_param("i", $consultaId);

    if (!$stmtExcluir->execute()) {
        throw new Exception("Erro ao atualizar medicamentos.");
    }

    // Insere os novos relacionamentos
    $sqlAssociacao = "INSERT INTO medicamentos_consulta
    (consulta_id, medicamento_id, dosagem, observacoes)
    VALUES (?, ?, ?, ?)";

    $stmtAssociacao = $conexao->prepare($sqlAssociacao);

    foreach ($medicamentosSelecionados as $medicamento) {
    $stmtAssociacao->bind_param(
        "iiss",
        $consultaId,
        $medicamento['id'],
        $medicamento['dosagem'],
        $medicamento['observacoes']
    );

    if (!$stmtAssociacao->execute()) {
        throw new Exception("Erro ao associar medicamentos.");
    }
}

    $conexao->commit();

    header("Location: mostrar_consulta.php?id=" . $consultaId . "&sucesso=atualizado");
    exit();
} catch (Exception $e) {
    $conexao->rollback();
    die("Erro ao atualizar consulta: " . $e->getMessage());
}