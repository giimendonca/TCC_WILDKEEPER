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

requireNivel(60);

// ====================================
// Pega os dados enviados
// ====================================

$medicamentoId = trim($_POST['id'] ?? "");
$nome = trim($_POST['nome'] ?? "");
$descricao = trim($_POST['descricao'] ?? "");
$fabricante = trim($_POST['fabricante'] ?? "");
$estoque = trim($_POST['estoque'] ?? "");
$lote = trim($_POST['lote'] ?? "");
$vencimento = trim($_POST['vencimento'] ?? "");

// ====================================
// Validação
// ====================================

if (empty($medicamentoId) || empty($nome) || empty($fabricante) || $estoque === "" || empty($lote) || empty($vencimento)) {
    die("Preencha todos os campos obrigatórios.");
}

// Verifica se o estoque é válido
if (!is_numeric($estoque) || $estoque < 0) {
    die("Estoque inválido.");
}

// ====================================
// UPDATE
// ====================================

$sql = "UPDATE medicamentos
        SET nome = ?,
            descricao = ?,
            fabricante = ?,
            estoque = ?,
            lote = ?,
            vencimento = ?
        WHERE id = ?";

$stmt = $conexao->prepare($sql);

$stmt->bind_param("sssissi", $nome, $descricao, $fabricante, $estoque, $lote, $vencimento, $medicamentoId);

$stmt->execute();

// ====================================
// Redirecionamento
// ====================================

header("Location: mostrar_medicamento.php?id=" . $medicamentoId);
exit();