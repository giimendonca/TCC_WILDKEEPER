<?php

session_start();

include "../includes/conexao.php";
include "../includes/funcoes.php";
include "../includes/autenticacao.php";

// ====================================
// Verificações de sessão
// ====================================

if (!isset($_SESSION['id'])) {
    header("Location: ../auth/login.php");
    exit();
}

// Verifica a permissão
requireNivel(40);

// ====================================
// Dados do novo evento
// ====================================

$evento = [
    "titulo" => trim($_POST['titulo'] ?? ''),
    "descricao" => trim($_POST['descricao'] ?? ''),
    "tipo" => trim($_POST['tipo'] ?? ''),
    "data_inicio" => trim($_POST['data_inicio'] ?? ''),
    "data_fim" => trim($_POST['data_fim'] ?? ''),
    "animal_id" => trim($_POST['animal_id'] ?? ''),
    "funcionario_id" => trim($_POST['funcionario_id'] ?? ''),
    "status" => trim($_POST['status'] ?? ''),
    "instituicao_id" => $_SESSION['instituicao_id']
];

// ====================================
// Campos obrigatórios
// ====================================

$camposObrigatorios = [
    "titulo",
    "tipo",
    "data_inicio",
    "data_fim",
    "funcionario_id",
    "status",
    "instituicao_id"
];

// Verifica se os campos obrigatórios foram preenchidos
if (!verificarCamposObrigatorios($evento, $camposObrigatorios)) {
    die("Há campos obrigatórios não preenchidos.");
}

// ====================================
// Validação do tipo
// ====================================

$tiposPermitidos = [
    "Consulta",
    "Vacinação",
    "Alimentação",
    "Manutenção",
    "Transferência",
    "Outro"
];

if (!in_array($evento['tipo'], $tiposPermitidos)) {
    die("Tipo de evento inválido.");
}

// ====================================
// Validação do status
// ====================================

$statusPermitidos = [
    "Agendado",
    "Em andamento",
    "Concluído",
    "Cancelado"
];

if (!in_array($evento['status'], $statusPermitidos)) {
    die("Status de evento inválido.");
}

// ====================================
// Ajusta as datas
// ====================================

$evento['data_inicio'] = str_replace("T", " ", $evento['data_inicio']);
$evento['data_fim'] = str_replace("T", " ", $evento['data_fim']);

// Verifica se a data final é posterior à inicial
if (strtotime($evento['data_fim']) <= strtotime($evento['data_inicio'])) {
    die("A data de fim deve ser posterior à data de início.");
}

// ====================================
// Verifica o funcionário
// ====================================

$sqlFuncionario = "SELECT id
FROM users
WHERE id = ?
AND instituicao_id = ?
AND status != 'Desligado'";

$stmtFuncionario = $conexao->prepare($sqlFuncionario);
$stmtFuncionario->bind_param(
    "ii",
    $evento['funcionario_id'],
    $evento['instituicao_id']
);
$stmtFuncionario->execute();

$resultFuncionario = $stmtFuncionario->get_result();

if ($resultFuncionario->num_rows == 0) {
    die("Funcionário inválido.");
}

// ====================================
// Verifica o animal
// ====================================

if ($evento['animal_id'] != "") {

    $sqlAnimal = "SELECT id
    FROM animais
    WHERE id = ?
    AND instituicao_id = ?";

    $stmtAnimal = $conexao->prepare($sqlAnimal);
    $stmtAnimal->bind_param(
        "ii",
        $evento['animal_id'],
        $evento['instituicao_id']
    );
    $stmtAnimal->execute();

    $resultAnimal = $stmtAnimal->get_result();

    if ($resultAnimal->num_rows == 0) {
        die("Animal inválido.");
    }

} else {

    $evento['animal_id'] = null;

}

// ====================================
// Cadastra o evento
// ====================================

$sql = "INSERT INTO eventos
(titulo, descricao, tipo, data_inicio, data_fim, animal_id, funcionario_id, status, instituicao_id)
VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";

$stmt = $conexao->prepare($sql);

$stmt->bind_param(
    "sssssiisi",
    $evento['titulo'],
    $evento['descricao'],
    $evento['tipo'],
    $evento['data_inicio'],
    $evento['data_fim'],
    $evento['animal_id'],
    $evento['funcionario_id'],
    $evento['status'],
    $evento['instituicao_id']
);

$stmt->execute();

$eventoId = $conexao->insert_id;

header("Location: mostrar_evento.php?id=" . $eventoId);
exit();