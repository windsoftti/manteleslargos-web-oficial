CREATE TABLE ml_business_subscription_history (

    id_history INT AUTO_INCREMENT PRIMARY KEY,

    id_subscription INT NOT NULL,

    action VARCHAR(100) NOT NULL,

    notes TEXT NULL,

    created_at DATETIME NOT NULL,

    INDEX idx_subscription (id_subscription)

) ENGINE=InnoDB
DEFAULT CHARSET=utf8mb4
COLLATE=utf8mb4_unicode_ci;