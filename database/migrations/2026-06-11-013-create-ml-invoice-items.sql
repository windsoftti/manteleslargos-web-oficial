CREATE TABLE ml_invoice_items (

    id_item INT NOT NULL AUTO_INCREMENT,

    id_invoice INT NOT NULL,

    id_salon INT NOT NULL,

    concept VARCHAR(255) NOT NULL,

    amount DECIMAL(10,2) NOT NULL,

    PRIMARY KEY (id_item),

    INDEX idx_invoice (id_invoice),
    INDEX idx_salon (id_salon)

) ENGINE=InnoDB
DEFAULT CHARSET=utf8mb4
COLLATE=utf8mb4_unicode_ci;