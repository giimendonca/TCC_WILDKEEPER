<?php
include "../src/includes/conexao.php";

// Dados da instituição
$instituicao = [
    "nome" => trim($_POST['nome_instituicao'] ?? ''),
    "cnpj" => trim($_POST['cnpj'] ?? ''),
    "telefone" => trim($_POST['telefone_instituicao'] ?? ''),
    "email" => trim($_POST['email_instituicao'] ?? ''),
    "website" => trim($_POST['website'] ?? ''),
    "cep" => trim($_POST['cep'] ?? ''),
    "rua" => trim($_POST['rua'] ?? ''),
    "numero" => trim($_POST['numero'] ?? ''),
    "bairro" => trim($_POST['bairro'] ?? ''),
    "cidade" => trim($_POST['cidade'] ?? ''),
    "estado" => trim($_POST['estado'] ?? '')
];

// Dados do Administrador
$usuario = [
    "nome" => trim($_POST['nome_adm'] ?? ''),
    "cpf" => trim($_POST['cpf'] ?? ''),
    "telefone" => trim($_POST['telefone_adm'] ?? ''),
    "email" => trim($_POST['email_adm'] ?? ''),
    "data_nascimento" => trim($_POST['data_nascimento'] ?? ''),
    "genero" => trim($_POST['genero'] ?? ''),
    "senha" => trim($_POST['senha'] ?? ''),
    "confirmar_senha" => trim($_POST['confirmar_senha'] ?? '')
];
