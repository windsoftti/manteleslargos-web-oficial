CREATE TABLE ml_business_subscriptions (

    id_subscription INT AUTO_INCREMENT PRIMARY KEY,

    id_salon INT NOT NULL,

    id_plan INT NOT NULL,

    plan_slug VARCHAR(50) NOT NULL,

    billing_cycle ENUM(
        'monthly',
        'semiannual',
        'annual'
    ) NOT NULL DEFAULT 'monthly',

    status ENUM(
        'pending',
        'active',
        'grace',
        'expired',
        'cancelled'
    ) NOT NULL DEFAULT 'pending',

    starts_at DATETIME NOT NULL,

    expires_at DATETIME NOT NULL,

    grace_until DATETIME NULL,

    is_recurring ENUM(
        'yes',
        'no'
    ) NOT NULL DEFAULT 'no',

    created_at DATETIME NOT NULL,

    updated_at DATETIME NULL,

    INDEX idx_salon (id_salon),
    INDEX idx_plan (id_plan),
    INDEX idx_plan_slug (plan_slug),
    INDEX idx_status (status),

    CONSTRAINT fk_subscription_plan
        FOREIGN KEY (id_plan)
        REFERENCES ml_planes(idPlan)

) ENGINE=InnoDB
DEFAULT CHARSET=utf8mb4
COLLATE=utf8mb4_unicode_ci;