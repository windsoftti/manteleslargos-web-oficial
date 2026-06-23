CREATE TABLE ml_business_payments (

    id_payment INT AUTO_INCREMENT PRIMARY KEY,

    id_subscription INT NOT NULL,

    id_salon INT NOT NULL,

    amount DECIMAL(10,2) NOT NULL,

    currency VARCHAR(10) NOT NULL DEFAULT 'MXN',

    payment_provider VARCHAR(50) NULL,

    provider_payment_id VARCHAR(255) NULL,

    provider_subscription_id VARCHAR(255) NULL,

    status ENUM(
        'pending',
        'paid',
        'failed',
        'refunded'
    ) NOT NULL DEFAULT 'pending',

    paid_at DATETIME NULL,

    created_at DATETIME NOT NULL,

    INDEX idx_subscription (id_subscription),
    INDEX idx_salon (id_salon)

) ENGINE=InnoDB
DEFAULT CHARSET=utf8mb4
COLLATE=utf8mb4_unicode_ci;