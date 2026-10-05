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

$evento_id = $_POST['evento_id'] ?? null;
$descricao = trim($_POST['descricao'] ?? '');

if (!$evento_id || !$descricao) {
    die("Preencha todos os campos obrigatórios.");
}

// ====================================
// Busca e valida o evento
// ====================================

$sqlEvento = "SELECT id, status
FROM eventos
WHERE id = ?
AND tipo = 'Manutenção'
AND instituicao_id = ?";

$stmtEvento = $conexao->prepare($sqlEvento);
$stmtEvento->bind_param("ii", $evento_id, $instituicao_id);
$stmtEvento->execute();

$resultEvento = $stmtEvento->get_result();
$evento = $resultEvento->fetch_assoc();

if (!$evento) {
    die("Evento de manutenção inválido.");
}

// ====================================
// Verifica se pode ser realizado
// ====================================

if ($evento['status'] != 'Agendado' && $evento['status'] != 'Em andamento') {
    die("Este evento não pode ser realizado.");
}

// ====================================
// Verifica se já existe manutenção
// ====================================

$sqlManutencao = "SELECT id
FROM manutencao_habitats
WHERE evento_id = ?";

$stmtManutencao = $conexao->prepare($sqlManutencao);
$stmtManutencao->bind_param("i", $evento_id);
$stmtManutencao->execute();

$resultManutencao = $stmtManutencao->get_result();

if ($resultManutencao->num_rows > 0) {
    die("Esta manutenção já foi realizada.");
}

// ====================================
// Salva a manutenção
// ====================================

$conexao->begin_transaction();

try {

    $sql = "INSERT INTO manutencao_habitats
        (descricao, evento_id)
        VALUES (?, ?)";

    $stmt = $conexao->prepare($sql);

    $stmt->bind_param(
        "si",
        $descricao,
        $evento_id
    );

    if (!$stmt->execute()) {
        throw new Exception("Erro ao registrar a manutenção.");
    }

    // ====================================
    // Conclui o evento
    // ====================================

    $sqlEvento = "UPDATE eventos
    SET status = 'Concluído'
    WHERE id = ?
    AND tipo = 'Manutenção'
    AND instituicao_id = ?";

    $stmtEvento = $conexao->prepare($sqlEvento);
    $stmtEvento->bind_param("ii", $evento_id, $instituicao_id);

    if (!$stmtEvento->execute()) {
        throw new Exception("Erro ao concluir o evento.");
    }

    $conexao->commit();

    header("Location: index.php?sucesso=realizado");
    exit();

} catch (Exception $e) {

    $conexao->rollback();

    die("Erro ao realizar manutenção: " . $e->getMessage());
}