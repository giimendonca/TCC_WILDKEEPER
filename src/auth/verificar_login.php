<?php

require_once __DIR__ . '/../includes/funcoes.php';
require_once __DIR__ . '/../includes/login_rate_limit.php';

if (($_SERVER['REQUEST_METHOD'] ?? '') !== 'POST') {
    http_response_code(405);
    exit('Requisição inválida.');
}

$emailRecebido = $_POST['email'] ?? '';
$senhaRecebida = $_POST['senha'] ?? '';
$emailRecebido = is_string($emailRecebido) ? trim($emailRecebido) : '';
$senha = is_string($senhaRecebida) ? trim($senhaRecebida) : '';
$emailNormalizado = function_exists('mb_strtolower')
    ? mb_strtolower($emailRecebido, 'UTF-8')
    : strtolower($emailRecebido);

$chaveHmacConfigurada = getenv('WILKEEPER_LOGIN_RATE_LIMIT_KEY');
if (!is_string($chaveHmacConfigurada) || !preg_match('/^[a-f0-9]{64}$/i', $chaveHmacConfigurada)) {
    http_response_code(500);
    exit('Não foi possível processar o login.');
}
$chaveHmac = hex2bin($chaveHmacConfigurada);

$ipTexto = $_SERVER['REMOTE_ADDR'] ?? '';
$ipBinario = is_string($ipTexto) ? @inet_pton($ipTexto) : false;

if ($ipBinario === false) {
    http_response_code(400);
    exit('Não foi possível processar o login.');
}

// Emails maiores que a coluna users.email não podem corresponder a uma conta.
$emailUtf8Valido = preg_match('//u', $emailNormalizado) === 1;
$emailCaracteres = false;

if ($emailUtf8Valido) {
    if (strlen($emailNormalizado) <= 100) {
        $emailCaracteres = strlen($emailNormalizado);
    } elseif (strlen($emailNormalizado) <= 400 && preg_match_all('/./us', $emailNormalizado, $correspondencias) !== false) {
        $emailCaracteres = count($correspondencias[0]);
    }
}

$emailDentroDoLimite = $emailCaracteres !== false && $emailCaracteres <= 100;
$emailValidoParaConsulta = $emailNormalizado !== ''
    && $emailDentroDoLimite
    && emailValido($emailNormalizado);

// Hash válido e fixo para executar password_verify() quando o email não existe.
$hashSenhaFicticio = '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi';

try {
    require_once __DIR__ . '/../includes/conexao.php';

    $resultadoLogin = wildkeeperProcessarTentativaLogin(
        $conexao,
        $emailNormalizado,
        $ipBinario,
        $chaveHmac,
        function () use ($conexao, $emailNormalizado, $emailValidoParaConsulta, $senha, $hashSenhaFicticio) {
            $user = null;

            if ($emailValidoParaConsulta) {
                $sql = "SELECT
                            users.id,
                            users.nome,
                            users.email,
                            users.senha_hash,
                            users.status,
                            cargos.id AS cargo_id,
                            cargos.nome AS cargo_nome,
                            cargos.nivel,
                            instituicoes.id AS instituicao_id,
                            instituicoes.nome AS instituicao_nome
                        FROM users
                        INNER JOIN cargos ON cargos.id = users.cargo_id
                        INNER JOIN instituicoes ON instituicoes.id = users.instituicao_id
                        WHERE users.email = ?";

                $stmt = $conexao->prepare($sql);
                $stmt->bind_param('s', $emailNormalizado);
                $stmt->execute();
                $user = $stmt->get_result()->fetch_assoc();
            }

            $hashArmazenado = is_array($user) ? $user['senha_hash'] : $hashSenhaFicticio;
            $senhaValida = password_verify($senha, $hashArmazenado);

            if (!$user || !$senhaValida || $user['status'] !== 'Ativo') {
                return null;
            }

            return $user;
        }
    );
} catch (Throwable $e) {
    error_log('Falha no fluxo de autenticação: ' . $e->getMessage());
    http_response_code(500);
    exit('Não foi possível processar o login. Tente novamente.');
}

if ($resultadoLogin['bloqueado']) {
    http_response_code(429);
    header('Retry-After: ' . max(1, (int) $resultadoLogin['tempo_restante']));
    exit('Não foi possível autenticar. Tente novamente mais tarde.');
}

$user = $resultadoLogin['usuario'];

if (!$user) {
    http_response_code(401);
    exit('Email e/ou senha inválido(s).');
}

session_start();
session_regenerate_id(true);

$_SESSION['id'] = $user['id'];
$_SESSION['nome'] = $user['nome'];
$_SESSION['email'] = $user['email'];
$_SESSION['status'] = $user['status'];
$_SESSION['nivel'] = $user['nivel'];
$_SESSION['cargo_id'] = $user['cargo_id'];
$_SESSION['cargo_nome'] = $user['cargo_nome'];
$_SESSION['instituicao_id'] = $user['instituicao_id'];
$_SESSION['instituicao_nome'] = $user['instituicao_nome'];

header('Location: ../dashboard/index.php');
exit();
