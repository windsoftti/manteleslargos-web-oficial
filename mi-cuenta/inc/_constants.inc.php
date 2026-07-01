<?php
/* ::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::
//-- BASE URL
:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::: */
define('URL_SCHEME', 'https');
define('HOST_URL', 'manteleslargos.com');

define('BASE_URL_FRONTED', URL_SCHEME . '://' . HOST_URL);
define('BASE_PATH_FRONTED', $_SERVER['DOCUMENT_ROOT']);

define('BASE_URL', BASE_URL_FRONTED . '/mi-cuenta');
define('BASE_PATH', BASE_PATH_FRONTED . '/mi-cuenta');

require_once __DIR__ . "/../../inc/apikeys.php";

/* ::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::
//-- MYSQLI PASSWORD SECRET
:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::: */
define('MYSQLI_PASSWORD_SECRET', '@sistema/_-rentas/_-salones-fiestas/_-2021/_-IEM/_-IYS_-s0f74r3');

/* ::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::
//-- IMAGES
:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::: */
define('RECENT_EVENTS_IMAGE_FOLDER', '../../../src/assets/images/recent-events/');
define('RECENT_EVENTS_GALLERY_FOLDER', '../../../src/assets/images/recent-events/gallery/');

define('TIPS_IMAGE_FOLDER', '../../../src/assets/images/tips/');
define('TIPS_GALLERY_FOLDER', '../../../src/assets/images/tips/gallery/');
