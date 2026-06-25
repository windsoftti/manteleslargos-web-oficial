UPDATE ml_settings
SET setting_value = 'APP_USR-1dceab15-5901-4567-828a-a33aa77987c5'
WHERE setting_key = 'mercadopago_public_key';

UPDATE ml_settings
SET setting_value = 'APP_USR-3950225347039663-062416-e4953d0efd5afee673e3d65541dad9ec-3497117010'
WHERE setting_key = 'mercadopago_access_token';

UPDATE ml_settings
SET setting_value = 'yes'
WHERE setting_key = 'mercadopago_enabled';