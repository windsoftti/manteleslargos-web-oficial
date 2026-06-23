<?php

function isMercadoPagoEnabled(): bool
{
    return
        getSetting(
            'mercadopago_enabled',
            'no'
        ) === 'yes';
}

function getMercadoPagoMode(): string
{
    return getSetting(
        'mercadopago_mode',
        'sandbox'
    );
}

function createMercadoPagoPreference(
    array $order
): array
{
    if (
        !isMercadoPagoEnabled()
    ) {

        return [

            'status' => 'error',

            'message' =>
                'Mercado Pago deshabilitado.'

        ];
    }

    return [

        'status' => 'success',

        /*
        |--------------------------------------------------------------------------
        | Temporal
        |--------------------------------------------------------------------------
        |
        | Más adelante aquí llegará la URL
        | real de Mercado Pago.
        |
        */

        'checkout_url' =>
            'confirmar-pago?id=' .
            $order['id_order'],

        'provider_order_id' => null

    ];
}