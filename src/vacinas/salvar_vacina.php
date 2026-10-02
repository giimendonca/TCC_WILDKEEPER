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
$evento_id = $_POST['evento_id'] ?? null;
$nome_vacina = trim($_POST['nome_vacina'] ?? '');
$data_aplicacao = trim($_POST['data_aplicacao'] ?? '');
$proxima_aplicacao = trim($_POST['proxima_aplicacao'] ?? '');
$observacoes = trim($_POST['observacoes'] ?? '');

// Verifica os campos obrigatórios
if (!$evento_id || !$nome_vacina || !$data_aplicacao) {
    die("Preencha todos os campos obrigatórios.");
}

if (empty($proxima_aplicacao)) {
    $proxima_aplicacao = null;
}

if (empty($observacoes)) {
    $observacoes = null;
}

// ====================================
// Busca e valida o evento
// ====================================

$sqlEvento = "SELECT id, status
FROM eventos
WHERE id = ?
AND tipo = 'Vacinação'
AND instituicao_id = ?";

$stmtEvento = $conexao->prepare($sqlEvento);
$stmtEvento->bind_param("ii", $evento_id, $instituicao_id);
$stmtEvento->execute();

$resultEvento = $stmtEvento->get_result();
$evento = $resultEvento->fetch_assoc();

// Verifica se o evento existe
if (!$evento) {
    die("Evento de vacinação inválido.");
}

// Verifica se o evento pode ser realizado
if ($evento['status'] != 'Agendado' && $evento['status'] != 'Em andamento') {
    die("Este evento não pode ser realizado.");
}

// ====================================
// Verifica se a vacinação já foi realizada
// ====================================

$sqlVacina = "SELECT id
FROM vacinas
WHERE evento_id = ?";

$stmtVacina = $conexao->prepare($sqlVacina);
$stmtVacina->bind_param("i", $evento_id);
$stmtVacina->execute();

$resultVacina = $stmtVacina->get_result();

if ($resultVacina->num_rows > 0) {
    die("Esta vacinação já foi realizada.");
}

// ====================================
// Salva a vacinação e conclui o evento
// ====================================

$conexao->begin_transaction();

try {

    // Insere o registro da vacinação
    $sql = "INSERT INTO vacinas
        (nome_vacina, data_aplicacao, proxima_aplicacao, observacoes, evento_id)
        VALUES (?, ?, ?, ?, ?)";

    $stmt = $conexao->prepare($sql);

    $stmt->bind_param(
        "ssssi",
        $nome_vacina,
        $data_aplicacao,
        $proxima_aplicacao,
        $observacoes,
        $evento_id
    );

    if (!$stmt->execute()) {
        throw new Exception("Erro ao registrar a vacinação.");
    }

    // Altera o status do evento para concluído
    $sqlEvento = "UPDATE eventos
    SET status = 'Concluído'
    WHERE id = ?
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

    die("Erro ao realizar vacinação: " . $e->getMessage());
}