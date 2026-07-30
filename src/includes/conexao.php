<?php
$host = 'localhost';
$user = 'root';
$senha = 'Home@spSENAI2025!';
$banco = 'wildkeeper';

$conexao = new mysqli($host, $user, $senha, $banco);

if($conexao->connect_error){
    die("Falha na conexão: " . $conexao->connect_error);
}

$conexao->set_charset("utf8");
?>