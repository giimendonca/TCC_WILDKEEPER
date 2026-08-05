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

// Valida cpf
function cpfValido($cpf, $conexao)
{
    $cpfFormatado = apenasNumeros($cpf);

    if (strlen($cpfFormatado) !== 11) {
        return die("CPF inválido.");
    }

    $sql = "SELECT 1 FROM users WHERE cpf = ?";

    $stmt = $conexao->prepare($sql);
    $stmt->bind_param("s", $cpfFormatado);
    $stmt->execute();

    $result = $stmt->get_result();

    $result = $result->fetch_assoc();

    if(!$result){
        return die("CPF já cadastrado.");
    }
}
