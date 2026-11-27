<?php

if (!function_exists('wildkeeperProcessarTentativaLogin')) {
    /**
     * Checks and records a login attempt while holding locks for both buckets.
     * The callback must return the authenticated user array or null on failure.
     */
    function wildkeeperProcessarTentativaLogin($conexao, $emailNormalizado, $ipBinario, $chaveHmac, $autenticar)
    {
        $limiteEmailIp = 5;
        $limiteIp = 20;
        $duracaoJanela = 15;

        $chaves = [
            'email_ip' => hash_hmac(
                'sha256',
                "email_ip\0" . pack('N', strlen($emailNormalizado)) . $emailNormalizado . $ipBinario,
                $chaveHmac,
                true
            ),
            'ip' => hash_hmac('sha256', "ip\0" . $ipBinario, $chaveHmac, true)
        ];

        // Opportunistic cleanup; bounded so a login request cannot delete an unbounded number of rows.
        $conexao->query("DELETE FROM login_rate_limits
                         WHERE inicio_janela < DATE_SUB(NOW(), INTERVAL 15 MINUTE)
                           AND (bloqueado_ate IS NULL OR bloqueado_ate <= NOW())
                         ORDER BY escopo, chave
                         LIMIT 100");

        $buckets = [
            ['escopo' => 'email_ip', 'chave' => $chaves['email_ip'], 'limite' => $limiteEmailIp],
            ['escopo' => 'ip', 'chave' => $chaves['ip'], 'limite' => $limiteIp]
        ];

        try {
            $conexao->begin_transaction();

            // Always create and lock in the same order to reduce deadlock risk.
            foreach ($buckets as $bucket) {
                $stmt = $conexao->prepare(
                    "INSERT INTO login_rate_limits
                        (escopo, chave, tentativas, inicio_janela, bloqueado_ate)
                     VALUES (?, ?, 0, NOW(), NULL)
                     ON DUPLICATE KEY UPDATE atualizado_em = CURRENT_TIMESTAMP"
                );
                $stmt->bind_param('ss', $bucket['escopo'], $bucket['chave']);
                $stmt->execute();
            }

            $linhas = [];
            foreach ($buckets as $bucket) {
                $stmt = $conexao->prepare(
                    "SELECT tentativas,
                            UNIX_TIMESTAMP(inicio_janela) AS inicio_ts,
                            UNIX_TIMESTAMP(bloqueado_ate) AS bloqueio_ts,
                            GREATEST(0, TIMESTAMPDIFF(SECOND, NOW(), bloqueado_ate)) AS restante_ts,
                            UNIX_TIMESTAMP(NOW()) AS agora_ts
                     FROM login_rate_limits
                     WHERE escopo = ? AND chave = ?
                     FOR UPDATE"
                );
                $stmt->bind_param('ss', $bucket['escopo'], $bucket['chave']);
                $stmt->execute();
                $linhas[] = $stmt->get_result()->fetch_assoc();
            }

            $bloqueado = false;
            $tempoRestante = 0;
            foreach ($linhas as $linha) {
                if ((int) ($linha['bloqueio_ts'] ?? 0) > (int) $linha['agora_ts']) {
                    $bloqueado = true;
                    $tempoRestante = max($tempoRestante, (int) $linha['restante_ts']);
                }
            }

            if ($bloqueado) {
                $conexao->commit();
                return [
                    'bloqueado' => true,
                    'tempo_restante' => max(1, $tempoRestante),
                    'usuario' => null
                ];
            }

            foreach ($buckets as $indice => $bucket) {
                $linha = $linhas[$indice];
                if ((int) $linha['inicio_ts'] <= ((int) $linha['agora_ts'] - ($duracaoJanela * 60))) {
                    $stmt = $conexao->prepare(
                        "UPDATE login_rate_limits
                         SET tentativas = 0, inicio_janela = NOW(), bloqueado_ate = NULL
                         WHERE escopo = ? AND chave = ?"
                    );
                    $stmt->bind_param('ss', $bucket['escopo'], $bucket['chave']);
                    $stmt->execute();
                    $linhas[$indice]['tentativas'] = 0;
                }
            }

            $usuario = $autenticar();

            if (is_array($usuario)) {
                // Successful authentication clears only the email+IP bucket.
                $stmt = $conexao->prepare(
                    "DELETE FROM login_rate_limits
                     WHERE escopo = 'email_ip' AND chave = ?"
                );
                $stmt->bind_param('s', $chaves['email_ip']);
                $stmt->execute();
                $conexao->commit();

                return ['bloqueado' => false, 'usuario' => $usuario];
            }

            $bloqueioAplicado = false;
            $bucketsBloqueados = [];
            foreach ($buckets as $indice => $bucket) {
                $tentativasAtuais = (int) $linhas[$indice]['tentativas'];
                $tentativasNovas = $tentativasAtuais + 1;
                $bloqueioMinutos = $tentativasNovas >= $bucket['limite'] ? $duracaoJanela : null;
                if ($bloqueioMinutos !== null) {
                    $bloqueioAplicado = true;
                    $bucketsBloqueados[] = $bucket;
                }

                if ($bloqueioMinutos === null) {
                    $stmt = $conexao->prepare(
                        "UPDATE login_rate_limits
                         SET inicio_janela = IF(tentativas = 0, NOW(), inicio_janela),
                             tentativas = ?, bloqueado_ate = NULL
                         WHERE escopo = ? AND chave = ?"
                    );
                    $stmt->bind_param('iss', $tentativasNovas, $bucket['escopo'], $bucket['chave']);
                } else {
                    $stmt = $conexao->prepare(
                        "UPDATE login_rate_limits
                         SET inicio_janela = IF(tentativas = 0, NOW(), inicio_janela),
                             tentativas = ?, bloqueado_ate = DATE_ADD(NOW(), INTERVAL 15 MINUTE)
                         WHERE escopo = ? AND chave = ?"
                    );
                    $stmt->bind_param('iss', $tentativasNovas, $bucket['escopo'], $bucket['chave']);
                }
                $stmt->execute();
            }

            $tempoRestante = 0;
            foreach ($bucketsBloqueados as $bucket) {
                $stmt = $conexao->prepare(
                    "SELECT GREATEST(0, TIMESTAMPDIFF(SECOND, NOW(), bloqueado_ate)) AS restante_ts
                     FROM login_rate_limits
                     WHERE escopo = ? AND chave = ?"
                );
                $stmt->bind_param('ss', $bucket['escopo'], $bucket['chave']);
                $stmt->execute();
                $linhaBloqueio = $stmt->get_result()->fetch_assoc();
                $tempoRestante = max($tempoRestante, (int) $linhaBloqueio['restante_ts']);
            }

            $conexao->commit();
            return [
                'bloqueado' => $bloqueioAplicado,
                'tempo_restante' => $bloqueioAplicado ? max(1, $tempoRestante) : 0,
                'usuario' => null
            ];
        } catch (Throwable $e) {
            $conexao->rollback();
            throw $e;
        }
    }
}
