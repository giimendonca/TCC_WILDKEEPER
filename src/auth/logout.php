<?php
// Inicia a sessão
session_start();

// Remove todas as varáveis da sessão
$_SESSION = [];

// Destrói a sessão
session_destroy();

// Redireciona para o login
header("Location: ../auth/login.php");
exit();
?>