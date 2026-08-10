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

// Dados do novo usuário
$funcionario = [
    "instituicao_id" => $_SESSION['instituicao_id'],
    "nome" => trim($_POST['nome'] ?? ''),
    "cpf" => trim($_POST['cpf'] ?? ''),
    "data_nascimento" => trim($_POST['data_nascimento'] ?? ''),
    "genero" => trim($_POST['genero'] ?? ''),
    "telefone" => trim($_POST['telefone'] ?? ''),
    "email" => trim($_POST['email'] ?? ''),
    "cargo_id" => trim($_POST['cargo_id'] ?? ''),
    "senha" => trim($_POST['senha'] ?? ''),
    "confirmar_senha" => trim($_POST['confirmar_senha'] ?? '')
];

// Campos obrigatórios
$camposObrigatorios = [
    "instituicao_id",
    "nome",
    "cpf",
    "data_nascimento",
    "genero",
    "telefone",
    "email",
    "cargo_id",
    "senha",
    "confirmar_senha"
];


// Verifica se os campos obrigatórios foram preenchidos
if (!verificarCamposObrigatorios($funcionario, $camposObrigatorios)) {
    die("Há campos obrigatórios não preenchidos.");
}

// Trata os dados e verifica se algum dado já foi inserido antes
$funcionario['cpf'] = apenasNumeros($funcionario['cpf']);
if (registroExiste($conexao, "users", "cpf", $funcionario['cpf'])) {
    die("CPF já cadastrado.");
}
if (strlen($funcionario['cpf']) !== 11) {
    die("CPF inválido.");
}

$funcionario['telefone'] = apenasNumeros($funcionario['telefone']);
if (registroExiste($conexao, "users", "telefone", $funcionario['telefone'])) {
    die("Telefone já cadastrado.");
}
if (strlen($funcionario['telefone']) !== 11) {
    die("Telefone inválido.");
}

// Verifica se o email é válido
if (!emailValido($funcionario['email'])) {
    die("Email do usuário inválido.");
}
if (registroExiste($conexao, "users", "email", $funcionario['email'])) {
    die("Email já cadastrado.");
}

// Valida as senhas
if($funcionario['senha'] !== $funcionario['confirmar_senha']){
    die("As senhas devem ser iguais.");
}
if(strlen($funcionario['senha']) < 6){
    die("A senha deve possuir pelo menos 6 caracteres.");
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

// Faz o hash da senha e reorganiza os dados
$dadosFuncionario = [
    "nome" => $funcionario['nome'],
    "cpf" => $funcionario['cpf'],
    "data_nascimento" => $funcionario['data_nascimento'],
    "genero" => $funcionario['genero'],
    "telefone" => $funcionario['telefone'],
    "email" => $funcionario['email'],
    "senha_hash" => password_hash($funcionario['senha'], PASSWORD_DEFAULT),    // faz o hash da senha
    "cargo_id" => $funcionario['cargo_id'],
    "instituicao_id" => $funcionario['instituicao_id']
];

try {
    // Prepara o INSERT na tabela de users
    $sql = "INSERT INTO users (nome, cpf, data_nascimento, genero, telefone, email, senha_hash, cargo_id, instituicao_id)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";

    $stmt = $conexao->prepare($sql);
    $stmt->bind_param("sssssssii", ...array_values($dadosFuncionario));
    $stmt->execute();

    header("Location: ../dashboard/index.php");
    exit();
} catch (mysqli_sql_exception $e) {
    die("Erro ao cadastrar: " . $e->getMessage());
}
?>