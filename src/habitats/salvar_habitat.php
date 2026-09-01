<?php
session_start();

include "../includes/conexao.php";
include "../includes/funcoes.php";
include "../includes/autenticacao.php";

// Verifica se há uma sessão ativa
if(!isset($_SESSION['id'])){
    header("Location: ../auth/login.php");
    exit();
}

// Verifica a permissão que o usuário possui
requireNivel(40);

// Dados da nova habitat
$habitat = [
    "nome" => trim($_POST['nome'] ?? ''),
    "bioma" => trim($_POST['bioma'] ?? ''),
    "descricao" => trim($_POST['descricao'] ?? ''),
    "temperatura" => trim($_POST['temperatura'] ?? ''),
    "umidade" => trim($_POST['umidade'] ?? ''),
    "capacidade" => trim($_POST['capacidade'] ?? ''),
    "status" => trim($_POST['status'] ?? ''),
    "instituicao_id" => $_SESSION['instituicao_id']
];

// Campos obrigatórios
$camposObrigatorios = [
    "nome",
    "bioma",
    "descricao",
    "temperatura",
    "umidade",
    "capacidade",
    "status",
    "instituicao_id"
];

// Verifica se os campos obrigatórios foram preenchidos
if (!verificarCamposObrigatorios($habitat, $camposObrigatorios)) {
    die("Há campos obrigatórios não preenchidos.");
}

if ($habitat['umidade'] < 0 || $habitat['umidade'] > 100) {
    die("A umidade deve estar entre 0% e 100%.");
}

$sql = "INSERT INTO habitats (nome, bioma, descricao, temperatura, umidade, capacidade, status, instituicao_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

$stmt = $conexao->prepare($sql);

$stmt->bind_param("sssddisi", ...array_values($habitat));
$stmt->execute();

header("Location: index.php");
exit();
?>