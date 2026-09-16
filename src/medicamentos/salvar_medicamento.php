<?php

session_start();

include "../includes/conexao.php";
include "../includes/funcoes.php";
include "../includes/autenticacao.php";

// ====================================
// Verificações de sessão
// ====================================

if (!isset($_SESSION['id'])) {
    header("Location: ../auth/login.php");
    exit();
}

requireNivel(40);

// ====================================
// Pega os dados enviados pelo formulário
// ====================================

$nome = trim($_POST['nome'] ?? "");
$descricao = trim($_POST['descricao'] ?? "");
$fabricante = trim($_POST['fabricante'] ?? "");
$estoque = trim($_POST['estoque'] ?? "");
$lote = trim($_POST['lote'] ?? "");
$vencimento = trim($_POST['vencimento'] ?? "");

// ====================================
// Validação
// ====================================

if (empty($nome) || empty($fabricante) || $estoque === "" || empty($lote) || empty($vencimento)) {
    die("Preencha todos os campos obrigatórios.");
}

// Verifica se o estoque é válido
if (!is_numeric($estoque) || $estoque < 0) {
    die("Estoque inválido.");
}

// ====================================
// INSERT
// ====================================

$sql = "INSERT INTO medicamentos (nome, descricao, fabricante, estoque, lote, vencimento)
        VALUES (?, ?, ?, ?, ?, ?)";

$stmt = $conexao->prepare($sql);

$stmt->bind_param("sssiss", $nome, $descricao, $fabricante, $estoque, $lote, $vencimento);

$stmt->execute();

// ====================================
// Redirecionamento
// ====================================

header("Location: index.php");
exit();