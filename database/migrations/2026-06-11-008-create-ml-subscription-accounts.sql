CREATE TABLE ml_subscription_accounts (

    id_account INT NOT NULL AUTO_INCREMENT,

    idUsuario INT NOT NULL,

    billing_day TINYINT NOT NULL DEFAULT 1,

    status ENUM(
        'active',
        'suspended',
        'cancelled'
    ) NOT NULL DEFAULT 'active',

    created_at DATETIME NOT NULL,
    updated_at DATETIME NULL,

    PRIMARY KEY (id_account),

    UNIQUE KEY uk_usuario (idUsuario),

    INDEX idx_usuario (idUsuario)

) ENGINE=InnoDB
DEFAULT CHARSET=utf8mb4
COLLATE=utf8mb4_unicode_ci;