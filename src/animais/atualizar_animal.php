<?php

session_start();

include "../includes/conexao.php";
include "../includes/funcoes.php";
include "../includes/autenticacao.php";

// Verifica se já existe uma sessão
if (!isset($_SESSION['id'])) {
    header("Location: ../auth/login.php");
    exit();
}

// Verifica a permissão que o usuário possui
requireNivel(40);

// Pega os dados enviados pelo formulário
$animal = [
    "id" => trim($_POST['id'] ?? ''),
    "nome" => trim($_POST['nome'] ?? ''),
    "sexo" => trim($_POST['sexo'] ?? ''),
    "data_nascimento" => trim($_POST['data_nascimento'] ?? ''),
    "data_chegada" => trim($_POST['data_chegada'] ?? ''),
    "peso" => trim($_POST['peso'] ?? ''),
    "altura" => trim($_POST['altura'] ?? ''),
    "microchip" => trim($_POST['microchip'] ?? ''),
    "observacoes" => trim($_POST['observacoes'] ?? ''),
    "especie_id" => trim($_POST['especie_id'] ?? ''),
    "habitat_id" => trim($_POST['habitat_id'] ?? ''),
    "status_id" => trim($_POST['status_id'] ?? ''),
    "saude_status_id" => trim($_POST['saude_status_id'] ?? '')
];

// Verifica se os campos obrigatórios foram preenchidos
$camposObrigatorios = [
    "id",
    "nome",
    "sexo",
    "data_nascimento",
    "data_chegada",
    "peso",
    "altura",
    "microchip",
    "observacoes",
    "especie_id",
    "habitat_id",
    "status_id",
    "saude_status_id"
];

if (!verificarCamposObrigatorios($animal, $camposObrigatorios)) {
    die("Há campos obrigatórios não preenchidos.");
}

try {

    $sql = "UPDATE animais SET
                nome = ?,
                sexo = ?,
                data_nascimento = ?,
                data_chegada = ?,
                peso = ?,
                altura = ?,
                microchip = ?,
                observacoes = ?,
                especie_id = ?,
                habitat_id = ?,
                status_id = ?,
                saude_status_id = ?
            WHERE id = ? AND instituicao_id = ?";

    $stmt = $conexao->prepare($sql);

    $stmt->bind_param(
        "ssssddssiiiiii",
        $animal['nome'],
        $animal['sexo'],
        $animal['data_nascimento'],
        $animal['data_chegada'],
        $animal['peso'],
        $animal['altura'],
        $animal['microchip'],
        $animal['observacoes'],
        $animal['especie_id'],
        $animal['habitat_id'],
        $animal['status_id'],
        $animal['saude_status_id'],
        $animal['id'],
        $_SESSION['instituicao_id']
    );

    $stmt->execute();

    if ($stmt->affected_rows === 0) {
        die("Nenhuma alteração foi realizada.");
    }

} catch (Exception $e) {

    die("Erro ao atualizar animal: " . $e->getMessage());

}

header("Location: mostrar_animal.php?id=" . urlencode($animal['id']));
exit();

?>