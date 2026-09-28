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
$diagnostico = trim($_POST['diagnostico'] ?? '');
$tratamento = trim($_POST['tratamento'] ?? '');
$observacoes = trim($_POST['observacoes'] ?? '');
$data_retorno = trim($_POST['data_retorno'] ?? '');

// Verifica os campos obrigatórios
if (!$evento_id || !$diagnostico || !$tratamento) {
    die("Preencha todos os campos obrigatórios.");
}

if(empty($data_retorno)){
    $data_retorno = null;
}

// ====================================
// Busca e valida o evento
// ====================================

$sqlEvento = "SELECT id, status
FROM eventos
WHERE id = ?
AND tipo = 'Consulta'
AND instituicao_id = ?";

$stmtEvento = $conexao->prepare($sqlEvento);
$stmtEvento->bind_param("ii", $evento_id, $instituicao_id);
$stmtEvento->execute();

$resultEvento = $stmtEvento->get_result();
$evento = $resultEvento->fetch_assoc();

// Verifica se o evento existe
if (!$evento) {
    die("Evento de consulta inválido.");
}

// Verifica se o evento pode ser realizado
if ($evento['status'] != 'Agendado' && $evento['status'] != 'Em andamento') {
    die("Este evento não pode ser realizado.");
}

// ====================================
// Verifica se a consulta já foi realizada
// ====================================

$sqlConsulta = "SELECT id
FROM consultas
WHERE evento_id = ?";

$stmtConsulta = $conexao->prepare($sqlConsulta);
$stmtConsulta->bind_param("i", $evento_id);
$stmtConsulta->execute();

$resultConsulta = $stmtConsulta->get_result();

if ($resultConsulta->num_rows > 0) {
    die("Esta consulta já foi realizada.");
}

// ====================================
// Salva a consulta e conclui o evento
// ====================================

$conexao->begin_transaction();

try {

    // Insere o resultado da consulta
    $sql = "INSERT INTO consultas
        (evento_id, diagnostico, tratamento, observacoes, data_retorno)
        VALUES (?, ?, ?, ?, ?)";

    $stmt = $conexao->prepare($sql);

    $stmt->bind_param(
        "issss",
        $evento_id,
        $diagnostico,
        $tratamento,
        $observacoes,
        $data_retorno
    );

    if (!$stmt->execute()) {
        throw new Exception("Erro ao registrar a consulta.");
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

    die("Erro ao realizar consulta: " . $e->getMessage());
}
