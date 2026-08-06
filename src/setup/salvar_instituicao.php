<?php
include "../includes/conexao.php";
include "../includes/funcoes.php";

// ====================================
// Dados da instituição
// ====================================
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
// Campos obrigatórios
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

// ====================================
// Dados do Administrador
// ====================================
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
// Campos obrigatórios
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

// ====================================
// Verificação se os campos foram todos preenchidos
// ====================================
if(!verificarCamposObrigatorios($instituicao, $camposInstituicao) ||
    !verificarCamposObrigatorios($usuario, $camposUsuario)
){
    die("Há campos obrigatórios não preenchidos.");
}

// ====================================
// Validações dos dados da instituição
// ====================================

// Formata o CNPJ
$instituicao['cnpj'] = apenasNumeros($instituicao['cnpj']);
// Valida o CNPJ
if(strlen($instituicao['cnpj']) !== 14){
    die("CNPJ inválido.");
}
// Verifica se o CNPJ já foi cadastrado
if(registroExiste($conexao, "instituicoes", "cnpj", $instituicao['cnpj'])){
    die("CNPJ já cadastrado.");
}

// Valida o email
if(!emailValido($instituicao['email'])){
    die("Email da instituição inválido.");
}
// Valida se o email já foi cadastrado
if(registroExiste($conexao, "instituicoes", "email", $instituicao['email'])){
    die("Email já cadastrado.");
}

// Formata o telefone
$instituicao['telefone'] = apenasNumeros($instituicao['telefone']);
// Valida o telefone
if(strlen($instituicao['telefone'] !== 11)){
    die("Telefone inválido.");
}
// Valida se o telefone ja foi cadastrado
if(registroExiste($conexao, "instituicoes", "telefone", $instituicao['telefone'])){
    die("Telefone já cadastrado.");
}

// Valida o Estado
if(strlen($instituicao['estado']) != 2){
    die("Estado inválido.");
}

// Formata o CEP 
$instituicao['cep'] = apenasNumeros($instituicao['cep']);
// Valida o CEP
if(strlen($instituicao['cep']) !== 8){
    die("CEP inválido.");
}

// ====================================
// Validações dos dados do usuário ADMIN
// ====================================

// Valida as senhas
if($usuario['senha'] !== $usuario['confirmar_senha']){
    die("As senhas devem ser iguais.");
}

// Formata o CPF
$usuario['cpf'] = apenasNumeros($usuario['cpf']);
// Valida o CPF
if(strlen($usuario['cpf']) !== 11){
    die("CPF inválido.");
}
// Verifica se o CPF já foi cadastrado
if(registroExiste($conexao, "users", "cpf", $usuario['cpf'])){
    die("CPF já cadastrado.");
}

// Formata o telefone
$usuario['telefone'] = apenasNumeros($usuario['telefone']);
// Valida o telefone
if(strlen($usuario['telefone'] !== 11)){
    die("Telefone inválido.");
}
// Valida se o telefone ja foi cadastrado
if(registroExiste($conexao, "users", "telefone", $usuario['telefone'])){
    die("Telefone já cadastrado.");
}

// Valida o email
if(!emailValido($usuario['email'])){
    die("Email do usuário inválido.");
}
// Verifica se o email já foi cadastrado 
if(registroExiste($conexao, "users", "email", $usuario['email'])){
    die("Email do usuário já cadastrado.");
}