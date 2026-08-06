<?php
$host = 'localhost';
$user = 'root';
$senha = 'Home@spSENAI2025!';
$banco = 'wildkeeper';

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

$conexao = new mysqli($host, $user, $senha, $banco);

if($conexao->connect_error){
    die("Falha na conexão: " . $conexao->connect_error);
}

$conexao->set_charset("utf8mb4");
?>