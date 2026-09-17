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

$animal_id = $_POST['animal_id'] ?? null;
$funcionario_id = $_POST['funcionario_id'] ?? null;
$data_consulta = $_POST['data_consulta'] ?? null;
$diagnostico = trim($_POST['diagnostico'] ?? '');
$tratamento = trim($_POST['tratamento'] ?? '');
$observacoes = trim($_POST['observacoes'] ?? '');
$data_retorno = $_POST['data_retorno'] ?? null;

if (!$animal_id || !$funcionario_id || !$data_consulta || !$diagnostico || !$tratamento || !$data_retorno) {
    die("Preencha todos os campos obrigatórios.");
}

// Verifica se o animal pertence à instituição
$sqlAnimal = "SELECT id FROM animais WHERE id = ? AND instituicao_id = ?";
$stmtAnimal = $conexao->prepare($sqlAnimal);
$stmtAnimal->bind_param("ii", $animal_id, $instituicao_id);
$stmtAnimal->execute();
$resultAnimal = $stmtAnimal->get_result();

if ($resultAnimal->num_rows === 0) {
    die("Animal inválido.");
}

// Verifica se o funcionário é veterinário da instituição
$sqlVeterinario = "SELECT users.id
                   FROM users
                   INNER JOIN cargos ON users.cargo_id = cargos.id
                   WHERE users.id = ?
                   AND users.instituicao_id = ?
                   AND cargos.nome = 'Veterinário'
                   AND users.status != 'Desligado'";

$stmtVeterinario = $conexao->prepare($sqlVeterinario);
$stmtVeterinario->bind_param("ii", $funcionario_id, $instituicao_id);
$stmtVeterinario->execute();
$resultVeterinario = $stmtVeterinario->get_result();

if ($resultVeterinario->num_rows === 0) {
    die("Veterinário inválido.");
}

$sql = "INSERT INTO consultas
        (animal_id, funcionario_id, instituicao_id, data_consulta, diagnostico, tratamento, observacoes, data_retorno)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

$stmt = $conexao->prepare($sql);
$stmt->bind_param(
    "iiisssss",
    $animal_id,
    $funcionario_id,
    $instituicao_id,
    $data_consulta,
    $diagnostico,
    $tratamento,
    $observacoes,
    $data_retorno
);

if ($stmt->execute()) {
    header("Location: index.php?sucesso=cadastrado");
    exit();
}

die("Erro ao cadastrar consulta: " . $conexao->error);
