CREATE TABLE ml_invoices (

    id_invoice INT NOT NULL AUTO_INCREMENT,

    id_account INT NOT NULL,

    subtotal DECIMAL(10,2) NOT NULL DEFAULT 0.00,
    discount DECIMAL(10,2) NOT NULL DEFAULT 0.00,
    total DECIMAL(10,2) NOT NULL DEFAULT 0.00,

    status ENUM(
        'pending',
        'paid',
        'cancelled'
    ) NOT NULL DEFAULT 'pending',

    created_at DATETIME NOT NULL,

    PRIMARY KEY (id_invoice),

    INDEX idx_account (id_account)

) ENGINE=InnoDB
DEFAULT CHARSET=utf8mb4
COLLATE=utf8mb4_unicode_ci;