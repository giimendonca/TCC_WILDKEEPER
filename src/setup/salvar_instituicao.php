<?php
include "../includes/conexao.php";
include "../includes/funcoes.php";

// ====================================
// Dados da instituição
// ====================================
$instituicao = [
    "nome" => trim($_POST['nome_instituicao'] ?? ''),
    "cnpj" => trim($_POST['cnpj'] ?? ''),
    "email" => trim($_POST['email_instituicao'] ?? ''),
    "telefone" => trim($_POST['telefone_instituicao'] ?? ''),
    "website" => trim($_POST['website'] ?? ''),
    "rua" => trim($_POST['rua'] ?? ''),
    "numero" => trim($_POST['numero'] ?? ''),
    "bairro" => trim($_POST['bairro'] ?? ''),
    "cidade" => trim($_POST['cidade'] ?? ''),
    "estado" => trim($_POST['estado'] ?? ''),
    "cep" => trim($_POST['cep'] ?? ''),
];
// Campos obrigatórios
$camposInstituicao = [
    "nome",
    "cnpj",
    "email",
    "telefone",
    "rua",
    "numero",
    "bairro",
    "cidade",
    "estado",
    "cep"
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
if (
    !verificarCamposObrigatorios($instituicao, $camposInstituicao) ||
    !verificarCamposObrigatorios($usuario, $camposUsuario)
) {
    die("Há campos obrigatórios não preenchidos.");
}

// ====================================
// Validações dos dados da instituição
// ====================================

// Formata o CNPJ
$instituicao['cnpj'] = apenasNumeros($instituicao['cnpj']);
// Valida o CNPJ
if (strlen($instituicao['cnpj']) !== 14) {
    die("CNPJ inválido.");
}
// Verifica se o CNPJ já foi cadastrado
if (registroExiste($conexao, "instituicoes", "cnpj", $instituicao['cnpj'])) {
    die("CNPJ já cadastrado.");
}

// Valida o email
if (!emailValido($instituicao['email'])) {
    die("Email da instituição inválido.");
}
// Valida se o email já foi cadastrado
if (registroExiste($conexao, "instituicoes", "email", $instituicao['email'])) {
    die("Email já cadastrado.");
}

// Formata o telefone
$instituicao['telefone'] = apenasNumeros($instituicao['telefone']);
// Valida o telefone
if (strlen($instituicao['telefone']) !== 11) {
    die("Telefone inválido.");
}
// Valida se o telefone ja foi cadastrado
if (registroExiste($conexao, "instituicoes", "telefone", $instituicao['telefone'])) {
    die("Telefone já cadastrado.");
}

// Valida o Estado
if (strlen($instituicao['estado']) != 2) {
    die("Estado inválido.");
}

// Formata o CEP 
$instituicao['cep'] = apenasNumeros($instituicao['cep']);
// Valida o CEP
if (strlen($instituicao['cep']) !== 8) {
    die("CEP inválido.");
}

// ====================================
// Validações dos dados do usuário ADMIN
// ====================================

// Valida as senhas
if ($usuario['senha'] !== $usuario['confirmar_senha']) {
    die("As senhas devem ser iguais.");
}

// Formata o CPF
$usuario['cpf'] = apenasNumeros($usuario['cpf']);
// Valida o CPF
if (strlen($usuario['cpf']) !== 11) {
    die("CPF inválido.");
}
// Verifica se o CPF já foi cadastrado
if (registroExiste($conexao, "users", "cpf", $usuario['cpf'])) {
    die("CPF já cadastrado.");
}

// Formata o telefone
$usuario['telefone'] = apenasNumeros($usuario['telefone']);
// Valida o telefone
if (strlen($usuario['telefone']) !== 11) {
    die("Telefone inválido.");
}
// Valida se o telefone ja foi cadastrado
if (registroExiste($conexao, "users", "telefone", $usuario['telefone'])) {
    die("Telefone já cadastrado.");
}

// Valida o email
if (!emailValido($usuario['email'])) {
    die("Email do usuário inválido.");
}
// Verifica se o email já foi cadastrado 
if (registroExiste($conexao, "users", "email", $usuario['email'])) {
    die("Email do usuário já cadastrado.");
}

try {
    $conexao->begin_transaction();

    $sql = "INSERT INTO instituicoes(
        nome,
        cnpj,
        email,
        telefone,
        website,
        rua,
        numero,
        bairro,
        cidade,
        estado,
        cep
    ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

    // echo "<pre>";
    // var_dump($instituicao);
    // echo "</pre>";
    // die();

    $stmtInstituicao = $conexao->prepare($sql);
    $stmtInstituicao->bind_param("ssssssissss", ...array_values($instituicao));
    $stmtInstituicao->execute();
    $stmtInstituicao->close();

    // Pega o id que acabou de ser inserido na instituição
    $instituicao_id = $conexao->insert_id;

    // Array com os dados que serão inseridos
    $dadosUsuario = [
        "nome" => $usuario['nome'],
        "cpf" => $usuario['cpf'],
        "data_nascimento" => $usuario['data_nascimento'],
        "genero" => $usuario['genero'],
        "telefone" => $usuario['telefone'],
        "email" => $usuario['email'],
        "senha_hash" => password_hash($usuario['senha'], PASSWORD_DEFAULT),  // Faz o hash da senha
        "cargo_id" => 1,    // Cargo de Admin
        "instituicao_id" => $instituicao_id
    ];

    $sql = "INSERT INTO users(
        nome,
        cpf,
        data_nascimento,
        genero,
        telefone,
        email,
        senha_hash,
        cargo_id,
        instituicao_id
    ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";

    $stmtUsuario = $conexao->prepare($sql);
    $stmtUsuario->bind_param("sssssssii", ...array_values($dadosUsuario));
    $stmtUsuario->execute();
    $stmtUsuario->close();

    $conexao->commit();

    header("Location: ../auth/login.php?cadastro=sucesso");
    exit;
} catch (mysqli_sql_exception $e) {
    $conexao->rollback();

    die("Erro ao cadastrar: " . $e->getMessage());
}
?>