<?php
include "../includes/conexao.php";
include "../includes/funcoes.php";

// Pega os dados do formulário
$email = trim($_POST['email'] ?? '');
$senha =  trim($_POST['senha'] ?? '');

// Verifica se os cmapos foram preenchidos
if (empty($email) || empty($senha)) {
    die("Preencha todos os campos!");
}

// Realiza o SELECT de acordo com o email
$sql = "SELECT
    users.id,
    users.nome,
    users.email,
    users.senha_hash,
    cargos.id AS cargo_id,
    cargos.nome AS cargo_nome,
    cargos.nivel,
    instituicoes.id AS instituicao_id,
    instituicoes.nome AS instituicao_nome
FROM users
INNER JOIN cargos ON cargos.id = users.cargo_id
INNER JOIN instituicoes ON instituicoes.id = users.instituicao_id
WHERE users.email = ?";

$stmt = $conexao->prepare($sql);
$stmt->bind_param("s", $email);
$stmt->execute();

$result = $stmt->get_result();

$user = $result->fetch_assoc();

// Valida se as senhas são iguais e se o email existe
if (!$user || !password_verify($senha, $user['senha_hash'])) {
    die("Email e/ou senha inválido(s).");
}

// Começa a sessão e define os dados
session_start();

session_regenerate_id(true);

$_SESSION['id'] = $user['id'];
$_SESSION['nome'] = $user['nome'];
$_SESSION['email'] = $user['email'];
$_SESSION['nivel'] = $user['nivel'];
$_SESSION['cargo_id'] = $user['cargo_id'];
$_SESSION['cargo_nome'] = $user['cargo_nome'];
$_SESSION['instituicao_id'] = $user['instituicao_id'];
$_SESSION['instituicao_nome'] = $user['instituicao_nome'];

header("Location: ../dashboard/index.php");
exit();
?>