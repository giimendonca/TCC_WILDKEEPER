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

// Pega o id do funcionário
$especieId = trim($_POST['id'] ?? '');

// Verifica se o id veio vazio
if (empty($especieId)) {
    die("ID inválido.");
}

// Dados da especie
$especie = [
    "nome_popular" => trim($_POST['nome_popular'] ?? ''),
    "nome_cientifico" => trim($_POST['nome_cientifico'] ?? ''),
    "descricao" => trim($_POST['descricao'] ?? ''),
    "origem" => trim($_POST['origem'] ?? ''),
    "vida_media" => trim($_POST['vida_media'] ?? ''),
    "peso_medio" => trim($_POST['peso_medio'] ?? ''),
    "altura_media" => trim($_POST['altura_media'] ?? ''),
    "categoria_id" => trim($_POST['categoria'] ?? ''),
    "classificacao_alimentar_id" => trim($_POST['classificacao_alimentar'] ?? ''),
    "risco_extincao_id" => trim($_POST['risco_extincao'] ?? ''),
    "especie_id" => $especieId
];

// Campos obrigatórios
$camposObrigatorios = [
    "nome_popular",
    "nome_cientifico",
    "descricao",
    "origem",
    "vida_media",
    "peso_medio",
    "altura_media",
    "categoria_id",
    "classificacao_alimentar_id",
    "risco_extincao_id",
    "especie_id"
];


// Verifica se os campos obrigatórios foram preenchidos
if (!verificarCamposObrigatorios($especie, $camposObrigatorios)) {
    die("Há campos obrigatórios não preenchidos.");
}

try {
    // Prepara o UPDATE na tabela de users
    $sql = "UPDATE especies SET nome_popular = ?, nome_cientifico = ?, descricao = ?, origem = ?, vida_media = ?, peso_medio = ?, altura_media = ?, categoria_id = ?, classificacao_alimentar_id = ?, risco_extincao_id = ? 
    WHERE id = ?";

    $stmt = $conexao->prepare($sql);
    $stmt->bind_param("ssssiddiiii", ...array_values($especie));
    $stmt->execute();

    header("Location: mostrar_especie.php?id=$especieId");
    exit();
} catch (mysqli_sql_exception $e) {
    die("Erro ao atualizar: " . $e->getMessage());
}
?>