CREATE TABLE ml_subscription_orders (

    id_order INT NOT NULL AUTO_INCREMENT,

    id_salon INT NOT NULL,

    id_plan INT NOT NULL,

    billing_cycle ENUM(
        'monthly',
        'semiannual',
        'annual'
    ) NOT NULL DEFAULT 'monthly',

    amount DECIMAL(10,2) NOT NULL,

    status ENUM(
        'pending',
        'paid',
        'cancelled'
    ) NOT NULL DEFAULT 'pending',

    payment_method VARCHAR(50) DEFAULT NULL,

    payment_reference VARCHAR(255) DEFAULT NULL,

    created_at DATETIME NOT NULL,

    updated_at DATETIME DEFAULT NULL,

    PRIMARY KEY (id_order),

    KEY idx_salon (id_salon),

    KEY idx_plan (id_plan),

    KEY idx_status (status)

) ENGINE=InnoDB
DEFAULT CHARSET=utf8mb4
COLLATE=utf8mb4_unicode_ci;