<?php

require_once "conexao.php";

// Inicia a sessão se ela ainda não esteja iniciada
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Verifica se o usuário possui uma sessão ativa
if (!isset($_SESSION['id'])) {
    header("Location: ../auth/login.php");
    exit();
}

// Consulta o status atual do usuário no banco
$sql = "SELECT status FROM users WHERE id = ?";
$stmt = $conexao->prepare($sql);
$stmt->bind_param("i", $_SESSION['id']);
$stmt->execute();

$result = $stmt->get_result();
$user = $result->fetch_assoc();

// Usuário não existe ou não está ativo
if (!$user || $user['status'] !== 'Ativo') {
    session_unset();
    session_destroy();

    header("Location: ../auth/login.php");
    exit();
}

// Verifica se o usuário tem o nível mínimo para acessar
function nivelMinimo($nivel) {
    return $_SESSION['nivel'] >= $nivel;
}

// Define o nível de permissão para poder acessar
function requireNivel($nivel) {
    if (!nivelMinimo($nivel)) {
        die("Acesso negado.");
    }
}