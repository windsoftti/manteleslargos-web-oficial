CREATE TABLE ml_settings (

    id_setting INT AUTO_INCREMENT PRIMARY KEY,

    setting_key VARCHAR(150) NOT NULL,

    setting_value TEXT NULL,

    UNIQUE KEY uq_setting_key (
        setting_key
    )

) ENGINE=InnoDB
DEFAULT CHARSET=utf8mb4
COLLATE=utf8mb4_unicode_ci;