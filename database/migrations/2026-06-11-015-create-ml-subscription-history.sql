CREATE TABLE `ml_subscription_history` (

    `id_history` INT(11) NOT NULL AUTO_INCREMENT,

    `id_subscription` INT(11) NOT NULL,

    `action_type` ENUM(
        'created',
        'updated',
        'renewed',
        'cancelled',
        'trial_started',
        'trial_extended',
        'plan_changed',
        'payment_received',
        'prorated_charge',
        'disabled'
    ) NOT NULL,

    `notes` TEXT NULL,

    `created_at` DATETIME NOT NULL,

    PRIMARY KEY (`id_history`),

    KEY `idx_subscription` (
        `id_subscription`
    ),

    KEY `idx_action_type` (
        `action_type`
    ),

    CONSTRAINT `fk_history_subscription`
        FOREIGN KEY (`id_subscription`)
        REFERENCES `ml_business_subscriptions`(
            `id_subscription`
        )
        ON DELETE CASCADE

) ENGINE=InnoDB
DEFAULT CHARSET=utf8mb4
COLLATE=utf8mb4_unicode_ci;