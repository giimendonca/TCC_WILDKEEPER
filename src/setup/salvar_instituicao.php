<?php
include "../includes/conexao.php";
include "../includes/funcoes.php";

// Dados da instituição
$instituicao = [
    "nome" => trim($_POST['nome_instituicao'] ?? ''),
    "cnpj" => trim($_POST['cnpj'] ?? ''),
    "telefone" => trim($_POST['telefone_instituicao'] ?? ''),
    "email" => trim($_POST['email_instituicao'] ?? ''),
    "website" => trim($_POST['website'] ?? ''),
    "cep" => trim($_POST['cep'] ?? ''),
    "rua" => trim($_POST['rua'] ?? ''),
    "numero" => trim($_POST['numero'] ?? ''),
    "bairro" => trim($_POST['bairro'] ?? ''),
    "cidade" => trim($_POST['cidade'] ?? ''),
    "estado" => trim($_POST['estado'] ?? '')
];

$camposInstituicao = [
    "nome",
    "cnpj",
    "telefone",
    "email",
    "cep",
    "rua",
    "numero",
    "bairro",
    "cidade",
    "estado"
];

// Dados do Administrador
$usuario = [
    "nome" => trim($_POST['nome_adm'] ?? ''),
    "cpf" => trim($_POST['cpf'] ?? ''),
    "telefone" => trim($_POST['telefone_adm'] ?? ''),
    "email" => trim($_POST['email_adm'] ?? ''),
    "data_nascimento" => trim($_POST['data_nascimento'] ?? ''),
    "genero" => trim($_POST['genero'] ?? ''),
    "senha" => trim($_POST['senha'] ?? ''),
    "confirmar_senha" => trim($_POST['confirmar_senha'] ?? '')
];

$camposUsuario = [
    "nome",
    "cpf",
    "telefone",
    "email",
    "data_nascimento",
    "genero",
    "senha",
    "confirmar_senha"
];

// Verificação se os campos foram todos preenchidos
if(!verificarCamposObrigatorios($instituicao, $camposInstituicao) ||
    !verificarCamposObrigatorios($usuario, $camposUsuario)
){
    die("Há campos obrigatórios não preenchidos.");
}

// Validações dos dados da instituição
// Valida o email
if(!emailValido($instituicao['email'])){
    die("Email da instituição inválido.");
}

// Valida se o email já foi cadastrado
$sql = "SELECT id FROM instituicoes WHERE email = ?";

$stmt = $conexao->prepare($sql);
$stmt->bind_param("s", $instituicao['email']);
$stmt->execute();

$result = $stmt->get_result();

if(!$result){
    die("Email da intituição já cadastrado.");
}

// Valida o Estado
if(strlen($instituicao['estado']) != 2){
    die("Estado inválido.");
}

// Formata o CNPJ
$cnpj = apenasNumeros($instituicao['cnpj']);

if(strlen($cnpj) !== 14){
    die("CNPJ inválido.");
}

// Formata o CEP 
$cep = apenasNumeros($instituicao['cep']);

if(strlen($cep) !== 8){
    die("CEP inválido.");
}


// Validações dos dados do usuário ADMIN
// Valida as senhas
if($usuario['senha'] !== $usuario['confirmar_senha']){
    die("As senhas devem ser iguais.");
}

// Valida o email
if(!emailValido($usuario['email'])){
    die("Email do usuário inválido.");
}

// Formata o CPF
$cpf = apenasNumeros($instituicao['cpf']);

if(strlen($cpf) !== 11){
    die("CPF inválido.");
}

// Verifica se o email já foi cadastrado
$sql = "SELECT id FROM users WHERE email = ?";

$stmt = $conexao->prepare($sql);
$stmt->bind_param("s", $usuario['email']);
$stmt->execute();

$result = $stmt->get_result();

if(!$result){
    die("Email do usuário já cadastrado.");
}