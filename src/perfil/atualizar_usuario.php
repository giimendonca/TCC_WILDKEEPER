<?php
session_start();

include "../includes/conexao.php";
include "../includes/funcoes.php";
include "../includes/autenticacao.php";

// Verifica se há uma sessão ativa
if (!isset($_SESSION['id'])) {
    header("Location: ../auth/login.php");
    exit();
}

// Pega o ID diretamente da sessão
$usuarioId = $_SESSION['id'];

// Dados do usuário
$usuario = [
    "nome" => trim($_POST['nome'] ?? ''),
    "cpf" => trim($_POST['cpf'] ?? ''),
    "data_nascimento" => trim($_POST['data_nascimento'] ?? ''),
    "genero" => trim($_POST['genero'] ?? ''),
    "telefone" => trim($_POST['telefone'] ?? ''),
    "email" => trim($_POST['email'] ?? '')
];

// Campos obrigatórios
$camposObrigatorios = [
    "nome",
    "cpf",
    "data_nascimento",
    "genero",
    "telefone",
    "email"
];

// Verifica se os campos obrigatórios foram preenchidos
if (!verificarCamposObrigatorios($usuario, $camposObrigatorios)) {
    die("Há campos obrigatórios não preenchidos.");
}

// Trata o CPF
$usuario['cpf'] = apenasNumeros($usuario['cpf']);

if (strlen($usuario['cpf']) !== 11) {
    die("CPF inválido.");
}

if (registroExisteOutro($conexao, "users", "cpf", $usuario['cpf'], $usuarioId)) {
    die("CPF já cadastrado.");
}

// Trata o telefone
$usuario['telefone'] = apenasNumeros($usuario['telefone']);

if (strlen($usuario['telefone']) !== 11) {
    die("Telefone inválido.");
}

if (registroExisteOutro($conexao, "users", "telefone", $usuario['telefone'], $usuarioId)) {
    die("Telefone já cadastrado.");
}

// Verifica se o email é válido
if (!emailValido($usuario['email'])) {
    die("Email do usuário inválido.");
}

if (registroExisteOutro($conexao, "users", "email", $usuario['email'], $usuarioId)) {
    die("Email já cadastrado.");
}

// Verifica se o gênero é válido
$generosPermitidos = [
    'Masculino',
    'Feminino',
    'Não-binário',
    'Outro',
    'Prefiro não informar'
];

if (!in_array($usuario['genero'], $generosPermitidos, true)) {
    die("Gênero inválido.");
}

try {
    // Atualiza somente as informações pessoais
    $sql = "UPDATE users SET
        nome = ?,
        cpf = ?,
        data_nascimento = ?,
        genero = ?,
        telefone = ?,
        email = ?
    WHERE id = ?";

    $stmt = $conexao->prepare($sql);
    $stmt->bind_param(
        "ssssssi",
        $usuario['nome'],
        $usuario['cpf'],
        $usuario['data_nascimento'],
        $usuario['genero'],
        $usuario['telefone'],
        $usuario['email'],
        $usuarioId
    );

    $stmt->execute();

    header("Location: index.php");
    exit();
} catch (mysqli_sql_exception $e) {
    die("Erro ao atualizar informações: " . $e->getMessage());
}
?>