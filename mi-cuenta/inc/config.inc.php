<?php

/*
|--------------------------------------------------------------------------
| Ambiente
|--------------------------------------------------------------------------
*/

require_once __DIR__ . '/environment.php';

/*
|--------------------------------------------------------------------------
| Configuración según ambiente
|--------------------------------------------------------------------------
*/

switch (APP_ENV) {

    case 'local':

        require_once __DIR__ . '/config/local.php';

        break;

    case 'development':

        require_once __DIR__ . '/config/development.php';

        break;

    default:

        require_once __DIR__ . '/config/production.php';

}

/*
|--------------------------------------------------------------------------
| Configuración PHP
|--------------------------------------------------------------------------
*/

error_reporting(
    $appConfig['debug']
        ? E_ALL
        : 0
);

date_default_timezone_set(
    $appConfig['timezone']
);

/*
|--------------------------------------------------------------------------
| Variables compatibles con el proyecto actual
|--------------------------------------------------------------------------
*/

$db_host =
    $appConfig['database']['host'];

$db_data_base =
    $appConfig['database']['database'];

$db_user =
    $appConfig['database']['username'];

$db_password =
    $appConfig['database']['password'];

/*
|--------------------------------------------------------------------------
| Conexión MySQL
|--------------------------------------------------------------------------
*/

$mysqli = new mysqli(

    $db_host,

    $db_user,

    $db_password,

    $db_data_base

);

if ($mysqli->connect_error) {

    die(
        'Error de conexión.'
    );

}

$mysqli->set_charset(
    'utf8'
);

/*
|--------------------------------------------------------------------------
| URL base
|--------------------------------------------------------------------------
*/

$protocol =
    $appConfig['site']['scheme'];

$host =
    $appConfig['site']['host'];

$baseFolder =
    $appConfig['site']['base_folder'];

$url_host =
    $protocol .
    '://' .
    $host .
    $baseFolder .
    '/';

/*
|--------------------------------------------------------------------------
| Compatibilidad
|--------------------------------------------------------------------------
*/

$secret =
    '@sistema/_-rentas/_-salones-fiestas/_-2021/_-IEM/_-IYS_-s0f74r3';

$images_url =
    '../../src/assets/images/';

$images_absolute_url =
    $url_host .
    'src/assets/images/';