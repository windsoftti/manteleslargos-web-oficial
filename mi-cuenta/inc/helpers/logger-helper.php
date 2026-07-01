<?php

function writeLog(
    string $channel,
    string $message
): bool
{
    /*
    |--------------------------------------------------------------------------
    | Directorio
    |--------------------------------------------------------------------------
    */

    $directory =
        BASE_PATH .
        '/logs/' .
        $channel;

    if (
        !is_dir($directory)
    ) {

        mkdir(
            $directory,
            0775,
            true
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Archivo
    |--------------------------------------------------------------------------
    */

    $file =

        $directory .

        '/' .

        date('Y-m-d') .

        '.log';

    /*
    |--------------------------------------------------------------------------
    | Línea
    |--------------------------------------------------------------------------
    */

    $content =

        '[' .

        date('Y-m-d H:i:s') .

        '] ' .

        $message .

        PHP_EOL;

    return (bool)

        file_put_contents(

            $file,

            $content,

            FILE_APPEND | LOCK_EX

        );
}

function writeMercadoPagoLog(
    string $message,
    array $context = []
): bool
{
    if (!empty($context)) {

        $message .=

            PHP_EOL .

            json_encode(

                $context,

                JSON_PRETTY_PRINT |
                JSON_UNESCAPED_UNICODE

            );
    }

    return writeLog(
        'mercadopago',
        $message
    );
}