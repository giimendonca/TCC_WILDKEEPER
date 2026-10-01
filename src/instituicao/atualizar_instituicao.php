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

// Verifica a permissão que o usuário possui
requireNivel(100);

// Pega o id da instituição
$instituicaoId = trim($_POST['id'] ?? '');

// Verifica se o id veio vazio
if (empty($instituicaoId)) {
    die("ID inválido.");
}

// Dados da instituição
$instituicao = [
    "id" => $instituicaoId,
    "nome" => trim($_POST['nome'] ?? ''),
    "cnpj" => trim($_POST['cnpj'] ?? ''),
    "email" => trim($_POST['email'] ?? ''),
    "telefone" => trim($_POST['telefone'] ?? ''),
    "website" => trim($_POST['website'] ?? ''),
    "rua" => trim($_POST['rua'] ?? ''),
    "numero" => trim($_POST['numero'] ?? ''),
    "bairro" => trim($_POST['bairro'] ?? ''),
    "cidade" => trim($_POST['cidade'] ?? ''),
    "estado" => trim($_POST['estado'] ?? ''),
    "cep" => trim($_POST['cep'] ?? '')
];

// Campos obrigatórios
$camposObrigatorios = [
    "id",
    "nome",
    "cnpj",
    "email",
    "telefone",
    "rua",
    "bairro",
    "cidade",
    "estado",
    "cep"
];

// Verifica se os campos obrigatórios foram preenchidos
if (!verificarCamposObrigatorios($instituicao, $camposObrigatorios)) {
    die("Há campos obrigatórios não preenchidos.");
}

// Verifica se a instituição pertence à instituição da sessão
$sql = "SELECT id FROM instituicoes WHERE id = ? AND id = ?";

$stmt = $conexao->prepare($sql);
$stmt->bind_param("ii", $instituicaoId, $_SESSION['instituicao_id']);
$stmt->execute();

$result = $stmt->get_result();

if ($result->num_rows === 0) {
    die("Instituição não encontrada.");
}


// ====================================
// VALIDA NOME
// ====================================

if (strlen($instituicao['nome']) > 100) {
    die("Nome da instituição inválido.");
}

if (registroExisteOutro($conexao, "instituicoes", "nome", $instituicao['nome'], $instituicaoId)) {
    die("Nome da instituição já cadastrado.");
}


// ====================================
// VALIDA CNPJ
// ====================================

$instituicao['cnpj'] = apenasNumeros($instituicao['cnpj']);

if (strlen($instituicao['cnpj']) !== 14) {
    die("CNPJ inválido.");
}

if (registroExisteOutro($conexao, "instituicoes", "cnpj", $instituicao['cnpj'], $instituicaoId)) {
    die("CNPJ já cadastrado.");
}


// ====================================
// VALIDA TELEFONE
// ====================================

$instituicao['telefone'] = apenasNumeros($instituicao['telefone']);

if (strlen($instituicao['telefone']) !== 11) {
    die("Telefone inválido.");
}

if (registroExisteOutro($conexao, "instituicoes", "telefone", $instituicao['telefone'], $instituicaoId)) {
    die("Telefone já cadastrado.");
}


// ====================================
// VALIDA EMAIL
// ====================================

if (!emailValido($instituicao['email'])) {
    die("Email da instituição inválido.");
}

if (registroExisteOutro($conexao, "instituicoes", "email", $instituicao['email'], $instituicaoId)) {
    die("Email já cadastrado.");
}


// ====================================
// VALIDA WEBSITE
// ====================================

if (!empty($instituicao['website']) && !filter_var($instituicao['website'], FILTER_VALIDATE_URL)) {
    die("Website inválido.");
}


// ====================================
// VALIDA ESTADO
// ====================================

$estadosPermitidos = [
    'AC', 'AL', 'AP', 'AM', 'BA', 'CE', 'DF', 'ES', 'GO',
    'MA', 'MT', 'MS', 'MG', 'PA', 'PB', 'PR', 'PE', 'PI',
    'RJ', 'RN', 'RS', 'RO', 'RR', 'SC', 'SP', 'SE', 'TO'
];

if (!in_array($instituicao['estado'], $estadosPermitidos, true)) {
    die("Estado inválido.");
}


// ====================================
// VALIDA CEP
// ====================================

$instituicao['cep'] = apenasNumeros($instituicao['cep']);

if (strlen($instituicao['cep']) !== 8) {
    die("CEP inválido.");
}


// ====================================
// VALIDA NÚMERO
// ====================================

if (empty($instituicao['numero'])) {
    $instituicao['numero'] = 'S/N';
}


// ====================================
// ORGANIZA OS DADOS
// ====================================

$dadosInstituicao = [
    "nome" => $instituicao['nome'],
    "cnpj" => $instituicao['cnpj'],
    "email" => $instituicao['email'],
    "telefone" => $instituicao['telefone'],
    "website" => $instituicao['website'] !== '' ? $instituicao['website'] : null,
    "rua" => $instituicao['rua'],
    "numero" => $instituicao['numero'],
    "bairro" => $instituicao['bairro'],
    "cidade" => $instituicao['cidade'],
    "estado" => $instituicao['estado'],
    "cep" => $instituicao['cep'],
    "id" => $instituicaoId
];


// ====================================
// ATUALIZA A INSTITUIÇÃO
// ====================================

try {
    $sql = "UPDATE instituicoes SET
                nome = ?,
                cnpj = ?,
                email = ?,
                telefone = ?,
                website = ?,
                rua = ?,
                numero = ?,
                bairro = ?,
                cidade = ?,
                estado = ?,
                cep = ?
            WHERE id = ? AND id = ?";

    $stmt = $conexao->prepare($sql);

    $stmt->bind_param(
        "sssssssssssii",
        $dadosInstituicao['nome'],
        $dadosInstituicao['cnpj'],
        $dadosInstituicao['email'],
        $dadosInstituicao['telefone'],
        $dadosInstituicao['website'],
        $dadosInstituicao['rua'],
        $dadosInstituicao['numero'],
        $dadosInstituicao['bairro'],
        $dadosInstituicao['cidade'],
        $dadosInstituicao['estado'],
        $dadosInstituicao['cep'],
        $dadosInstituicao['id'],
        $_SESSION['instituicao_id']
    );

    $stmt->execute();

    header("Location: index.php");
    exit();

} catch (mysqli_sql_exception $e) {
    die("Erro ao atualizar: " . $e->getMessage());
}
?>