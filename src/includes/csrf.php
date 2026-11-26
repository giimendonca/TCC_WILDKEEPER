<?php

if (!function_exists('iniciarSessaoCsrf')) {
    function iniciarSessaoCsrf()
    {
        if (session_status() === PHP_SESSION_NONE && session_start() === false) {
            return false;
        }

        return session_status() === PHP_SESSION_ACTIVE;
    }
}

if (!function_exists('csrfToken')) {
    function csrfToken()
    {
        if (!iniciarSessaoCsrf()) {
            return '';
        }

        if (!isset($_SESSION['csrf_token']) || !is_string($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }

        return $_SESSION['csrf_token'];
    }
}

if (!function_exists('validarTokenCsrf')) {
    function validarTokenCsrf()
    {
        if (($_SERVER['REQUEST_METHOD'] ?? '') !== 'POST') {
            return false;
        }

        if (!isset($_POST['csrf_token']) || !is_string($_POST['csrf_token'])) {
            return false;
        }

        if (!iniciarSessaoCsrf()) {
            return false;
        }

        if (!isset($_SESSION['csrf_token']) || !is_string($_SESSION['csrf_token'])) {
            return false;
        }

        return hash_equals($_SESSION['csrf_token'], $_POST['csrf_token']);
    }
}

if (!function_exists('exigirTokenCsrfValido')) {
    function exigirTokenCsrfValido()
    {
        if (!validarTokenCsrf()) {
            http_response_code(403);
            exit('Requisição inválida.');
        }
    }
}
