<?php
include "../includes/conexao.php";
include "../includes/funcoes.php";

// Pega os dados do formulário
$email = trim($_POST['email'] ?? '');
$senha =  trim($_POST['senha'] ?? '');

// Verifica se os cmapos foram preenchidos
if(empty($email) || empty($senha)){
    die("Preencha todos os campos!");
}

// Realiza o SELECT de acordo com o email
$sql = "SELECT 
        id,
        nome,
        email,
        senha_hash,
        cargo_id,
        instituicao_id
    FROM users WHERE email = ?";

$stmt = $conexao->prepare($sql);
$stmt->bind_param("s", $email);
$stmt->execute();

$result = $stmt->get_result();

$user = $result->fetch_assoc();

// Valida se as senhas são iguais e se o email existe
if(!$user || !password_verify($senha, $user['senha_hash'])){
    die("Email e/ou senha inválido(s).");
}

// Começa a sessão e define os dados
session_start();

session_regenerate_id(true);

$_SESSION['id'] = $user['id'];
$_SESSION['nome'] = $user['nome'];
$_SESSION['email'] = $user['email'];
$_SESSION['cargo_id'] = $user['cargo_id'];
$_SESSION['instituicao_id'] = $user['instituicao_id'];

header("Location: ../dashboard/index.php");
exit();
?>