CREATE TABLE ml_plan_limits (

    id_limit INT AUTO_INCREMENT PRIMARY KEY,

    plan_slug VARCHAR(50) NOT NULL,

    limit_key VARCHAR(100) NOT NULL,

    limit_value INT NOT NULL,

    UNIQUE KEY uq_plan_limit (
        plan_slug,
        limit_key
    ),

    INDEX idx_plan_slug (plan_slug)

) ENGINE=InnoDB
DEFAULT CHARSET=utf8mb4
COLLATE=utf8mb4_unicode_ci;