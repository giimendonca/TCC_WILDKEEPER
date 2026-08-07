<?php

// Inicia a sessão se ela ainda não esteja iniciada
if(session_status() === PHP_SESSION_NONE){
    session_start();
}

// Verifica se o usuário possui uma sessão ativa
if(!isset($_SESSION['id'])){
    header("Location: ../auth/login.php");
    exit();
}

// Verifica se o usuário tem o nível minímo para acessar
function nivelMinimo($nivel){
    return $_SESSION['nivel'] >= $nivel;
}

// Define o nível de permissão para poder acessar
function requireNivel($nivel){
    if(!nivelMinimo($nivel)){
        die("Acesso negado.");
    }
}
?>