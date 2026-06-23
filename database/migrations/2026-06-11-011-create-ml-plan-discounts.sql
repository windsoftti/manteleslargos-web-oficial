CREATE TABLE ml_plan_discounts (

    id_discount INT NOT NULL AUTO_INCREMENT,

    min_businesses INT NOT NULL,
    max_businesses INT NOT NULL,

    discount_percent DECIMAL(5,2) NOT NULL,

    status ENUM(
        'active',
        'inactive'
    ) NOT NULL DEFAULT 'active',

    created_at DATETIME NOT NULL,

    PRIMARY KEY (id_discount)

) ENGINE=InnoDB
DEFAULT CHARSET=utf8mb4
COLLATE=utf8mb4_unicode_ci;