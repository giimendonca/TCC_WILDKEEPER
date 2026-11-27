USE wildkeeper;

CREATE TABLE IF NOT EXISTS login_rate_limits (
    escopo ENUM('email_ip', 'ip') NOT NULL,
    chave BINARY(32) NOT NULL,
    tentativas SMALLINT UNSIGNED NOT NULL DEFAULT 0,
    inicio_janela DATETIME NOT NULL,
    bloqueado_ate DATETIME DEFAULT NULL,
    atualizado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (escopo, chave),
    KEY idx_login_rate_limits_limpeza (inicio_janela, bloqueado_ate)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
