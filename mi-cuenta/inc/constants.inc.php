<?php

/*
|--------------------------------------------------------------------------
| Detectar ambiente automáticamente
|--------------------------------------------------------------------------
|
| Local:
|   localhost
|
| Desarrollo:
|   dev.manteleslargos.com
|
| Producción:
|   manteleslargos.com
|
*/

$host = $_SERVER['HTTP_HOST'];

switch ($host) {

    /*
    |--------------------------------------------------------------------------
    | Localhost
    |--------------------------------------------------------------------------
    */

    case 'localhost':

        define(
            'BASE_URL_FRONTED',
            'http://localhost/devs/manteles'
        );

        define(
            'BASE_PATH_FRONTED',
            dirname(__DIR__, 2)
        );

    break;

    /*
    |--------------------------------------------------------------------------
    | Desarrollo
    |--------------------------------------------------------------------------
    */

    case 'dev.manteleslargos.com':

        define(
            'BASE_URL_FRONTED',
            'https://dev.manteleslargos.com'
        );

        define(
            'BASE_PATH_FRONTED',
            $_SERVER['DOCUMENT_ROOT']
        );

    break;

    /*
    |--------------------------------------------------------------------------
    | Producción
    |--------------------------------------------------------------------------
    */

    default:

        define(
            'BASE_URL_FRONTED',
            'https://manteleslargos.com'
        );

        define(
            'BASE_PATH_FRONTED',
            $_SERVER['DOCUMENT_ROOT']
        );

    break;
}

/*
|--------------------------------------------------------------------------
| Mi Cuenta
|--------------------------------------------------------------------------
*/

define(
    'BASE_URL',
    BASE_URL_FRONTED . '/mi-cuenta'
);

define(
    'BASE_PATH',
    BASE_PATH_FRONTED . '/mi-cuenta'
);

/*
|--------------------------------------------------------------------------
| API Keys
|--------------------------------------------------------------------------
*/

require_once __DIR__ . '/../../inc/apikeys.php';

/*
|--------------------------------------------------------------------------
| MYSQLI PASSWORD SECRET
|--------------------------------------------------------------------------
*/

define(
    'MYSQLI_PASSWORD_SECRET',
    '@sistema/_-rentas/_-salones-fiestas/_-2021/_-IEM/_-IYS_-s0f74r3'
);

/*
|--------------------------------------------------------------------------
| Images
|--------------------------------------------------------------------------
*/

define(
    'RECENT_EVENTS_IMAGE_FOLDER',
    '../../../src/assets/images/recent-events/'
);

define(
    'RECENT_EVENTS_GALLERY_FOLDER',
    '../../../src/assets/images/recent-events/gallery/'
);

define(
    'TIPS_IMAGE_FOLDER',
    '../../../src/assets/images/tips/'
);

define(
    'TIPS_GALLERY_FOLDER',
    '../../../src/assets/images/tips/gallery/'
);