<?php
session_start();

include "../includes/conexao.php";
include "../includes/funcoes.php";
include "../includes/autenticacao.php";

// Verifica se há uma sessão ativa
if(!isset($_SESSION['id'])){
    header("Location: ../auth/login.php");
    exit();
}

// Verifica a permissão que o usuário possui
requireNivel(100);

// Pega o id do funcionário
$funcionarioId = trim($_POST['id'] ?? '');

// Verifica se o id veio vazio
if (empty($funcionarioId)) {
    die("ID inválido.");
}

// Dados do novo usuário
$funcionario = [
    "instituicao_id" => $_SESSION['instituicao_id'],
    "user_id" => $funcionarioId,
    "nome" => trim($_POST['nome'] ?? ''),
    "cpf" => trim($_POST['cpf'] ?? ''),
    "data_nascimento" => trim($_POST['data_nascimento'] ?? ''),
    "genero" => trim($_POST['genero'] ?? ''),
    "telefone" => trim($_POST['telefone'] ?? ''),
    "email" => trim($_POST['email'] ?? ''),
    "cargo_id" => trim($_POST['cargo_id'] ?? ''),
    "status" => trim($_POST['status'] ?? '')
];

// Campos obrigatórios
$camposObrigatorios = [
    "instituicao_id",
    "user_id",
    "nome",
    "cpf",
    "data_nascimento",
    "genero",
    "telefone",
    "email",
    "cargo_id",
    "status"
];


// Verifica se os campos obrigatórios foram preenchidos
if (!verificarCamposObrigatorios($funcionario, $camposObrigatorios)) {
    die("Há campos obrigatórios não preenchidos.");
}

// Trata os dados e verifica se algum dado já foi inserido antes
$funcionario['cpf'] = apenasNumeros($funcionario['cpf']);
if (registroExisteOutro($conexao, "users", "cpf", $funcionario['cpf'], $funcionarioId)) {
    die("CPF já cadastrado.");
}
if (strlen($funcionario['cpf']) !== 11) {
    die("CPF inválido.");
}

$funcionario['telefone'] = apenasNumeros($funcionario['telefone']);
if (registroExisteOutro($conexao, "users", "telefone", $funcionario['telefone'], $funcionarioId)) {
    die("Telefone já cadastrado.");
}
if (strlen($funcionario['telefone']) !== 11) {
    die("Telefone inválido.");
}

// Verifica se o email é válido
if (!emailValido($funcionario['email'])) {
    die("Email do usuário inválido.");
}
if (registroExisteOutro($conexao, "users", "email", $funcionario['email'], $funcionarioId)) {
    die("Email já cadastrado.");
}

// Verifica se o status é válido
$statusPermitidos = [
    'Ativo',
    'Férias',
    'Afastado',
    'Desligado'
];

if (!in_array($funcionario['status'], $statusPermitidos, true)) {
    die("Status inválido.");
}

// Verifica se o genero é válido
$generosPermitidos = [
    'Masculino', 
    'Feminino', 
    'Não-binário', 
    'Outro', 
    'Prefiro não informar'
];

if (!in_array($funcionario['genero'], $generosPermitidos, true)) {
    die("Gênero inválido.");
}

// Valida se o id do cargo existe
$sql = "SELECT id FROM cargos WHERE id = ?";

$stmt = $conexao->prepare($sql);
$stmt->bind_param("i", $funcionario['cargo_id']);
$stmt->execute();

$result = $stmt->get_result();

if ($result->num_rows === 0) {
    die("Cargo inválido.");
}

// Rorganiza os dados
$dadosFuncionario = [
    "nome" => $funcionario['nome'],
    "cpf" => $funcionario['cpf'],
    "data_nascimento" => $funcionario['data_nascimento'],
    "genero" => $funcionario['genero'],
    "telefone" => $funcionario['telefone'],
    "email" => $funcionario['email'],
    "cargo_id" => $funcionario['cargo_id'],
    "status" => $funcionario['status'],
    "id" => $funcionarioId
];

try {
    // Prepara o INSERT na tabela de users
    $sql = "UPDATE users SET nome = ?, cpf = ?, data_nascimento = ?, genero = ?, telefone = ?, email = ?, cargo_id = ?, status = ?
    WHERE id = ?";

    $stmt = $conexao->prepare($sql);
    $stmt->bind_param("ssssssisi", ...array_values($dadosFuncionario));
    $stmt->execute();

    header("Location: mostrar_funcionario.php?id=$funcionarioId");
    exit();
} catch (mysqli_sql_exception $e) {
    die("Erro ao atualizar: " . $e->getMessage());
}
?>