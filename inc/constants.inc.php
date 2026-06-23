<?php
/* ::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::
//-- BASE URL
:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::: */
# define('URL_SCHEME', 'https');
# define('HOST_URL', 'www.manteleslargos.com/manteleslargos');
# define('BASE_URL', URL_SCHEME . '://' . HOST_URL);
# define('BASE_PATH', $_SERVER['DOCUMENT_ROOT'] . '/manteleslargos');

define('URL_SCHEME', getWebSiteProtocol());
define('HOST_URL', getServerName());
//define('BASE_URL', URL_SCHEME .  HOST_URL);
define('BASE_URL', 'http://localhost/devs/manteles');
define('BASE_PATH', getBasePath());

require_once __DIR__ . "/apikeys.php";

/* ::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::
//-- MYSQLI PASSWORD SECRET
:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::: */
define('MYSQLI_PASSWORD_SECRET', '@sistema/_-rentas/_-salones-fiestas/_-2021/_-IEM/_-IYS_-s0f74r3');

/* ::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::
//-- PHPMAILER CONFIG
:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::: */
define('PHPMAILER_HOST', 'mail.manteleslargos.com');

# PHPMAILER SUPPORT MAIL
define('PHPMAILER_SUPPORT_EMAIL', 'soporte@manteleslargos.com');
define('PHPMAILER_SUPPORT_PASSWORD', 'WS+wO,M[K%Bi');

# PHPMAILER CONTACT MAIL
define('PHPMAILER_CONTACT_EMAIL', 'soporte@manteleslargos.com');
define('PHPMAILER_CONTACT_PASSWORD', 'WS+wO,M[K%Bi');

/* ::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::
//-- IMAGES
:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::: */
define('INVITATIONS_IMAGE_FOLDER', '../../src/assets/images/invitations/');
define('INVITATIONS_GALLERY_FOLDER', '../../src/assets/images/invitations/gallery/');

define('BUSINESS_IMAGE_FOLDER', '../../src/assets/images/listing/');
define('BUSINESS_GALLERY_FOLDER', '../../src/assets/images/listing/gallery/');
