<?php
include_once "conexao.php";

// Valida campos obrigatórios
function verificarCamposObrigatorios($dados, $camposObrigatorios)
{
    foreach ($camposObrigatorios as $campo) {
        if (empty($dados[$campo])) {
            return false;
        }
    }

    return true;
}

// Verifica se um registro já existe
function registroExiste($conexao, $tabela, $coluna, $valor)
{
    $sql = "SELECT 1 FROM $tabela WHERE $coluna = ?";

    $stmt = $conexao->prepare($sql);
    $stmt->bind_param("s", $valor);
    $stmt->execute();

    $result = $stmt->get_result();

    $stmt->close();

    return $result->num_rows > 0;
}

// Verifica se existe um registro igual mas que não seja ele mesmo
function registroExisteOutro($conexao, $tabela, $coluna, $valor, $id)
{
    $sql = "SELECT 1 FROM $tabela WHERE $coluna = ? AND id != ?";

    $stmt = $conexao->prepare($sql);
    $stmt->bind_param("si", $valor, $id);
    $stmt->execute();

    $result = $stmt->get_result();

    $stmt->close();

    return $result->num_rows > 0;
}

// Remove tudo o que não for numeros
function apenasNumeros($texto)
{
    return preg_replace('/\D/', '', $texto);
}

// Valida email
function emailValido($email)
{
    return filter_var($email, FILTER_VALIDATE_EMAIL);
}

// Faz o SELECT inteiro de uma tabela
function selectTabela($conexao, $tabela)
{
    $sql = "SELECT * FROM $tabela";

    $stmt = $conexao->prepare($sql);
    $stmt->execute();

    return $stmt->get_result();
}
