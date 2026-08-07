<?php
session_start();

// Verifica se ja existe uma sessão
if(!isset($_SESSION['id'])){
    header("Location: ../auth/login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
</head>
<body>
    <header>
        <img src="/TCC_WILDKEEPER/assets/img/icon_wildkeeper.webp" alt="Logo do WildKeeper">

        <h1>Olá, <?= $_SESSION['nome'] ?>!</h1>
        <h2>Bem-vindo(a) novamente.</h2>
    </header>
    <main>
        <h3>Painel</h3>

        
    </main>
</body>
</html>