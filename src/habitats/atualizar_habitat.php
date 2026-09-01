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
requireNivel(40);

// Pega o id do habitat
$habitatId = trim($_POST['id'] ?? '');

// Verifica se o id veio vazio
if (empty($habitatId)) {
    die("ID inválido.");
}

// Dados do habitat
$habitat = [
    "nome" => trim($_POST['nome'] ?? ''),
    "bioma" => trim($_POST['bioma'] ?? ''),
    "descricao" => trim($_POST['descricao'] ?? ''),
    "temperatura" => trim($_POST['temperatura'] ?? ''),
    "umidade" => trim($_POST['umidade'] ?? ''),
    "capacidade" => trim($_POST['capacidade'] ?? ''),
    "status" => trim($_POST['status'] ?? ''),
    "instituicao_id" => $_SESSION['instituicao_id'],
    "id" => $habitatId
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
    "instituicao_id",
    "id"
];

// Verifica se os campos obrigatórios foram preenchidos
if (!verificarCamposObrigatorios($habitat, $camposObrigatorios)) {
    die("Há campos obrigatórios não preenchidos.");
}

try {
    // Prepara o UPDATE na tabela de habitats
    $sql = "UPDATE habitats SET nome = ?, bioma = ?, descricao = ?, temperatura = ?, umidade = ?, capacidade = ?, status = ?
    WHERE instituicao_id = ? AND id = ?";

    $stmt = $conexao->prepare($sql);
    $stmt->bind_param("sssddisii", ...array_values($habitat));
    $stmt->execute();

    header("Location: mostrar_habitat.php?id=$habitatId");
    exit();
} catch (mysqli_sql_exception $e) {
    die("Erro ao atualizar: " . $e->getMessage());
}
