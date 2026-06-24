<?php

function isMercadoPagoEnabled(): bool
{
    if (
        getSetting(
            'mercadopago_enabled',
            'no'
        ) !== 'yes'
    ) {

        return false;
    }

    if (
        empty(
            getMercadoPagoAccessToken()
        )
    ) {

        return false;
    }

    return true;
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

    updateOrderProviderData(
        (int) $order['id_order'],
        'mercadopago',
        null
    );

    /*
    |--------------------------------------------------------------------------
    | Próxima integración:
    |
    | 1. Crear preferencia Mercado Pago
    | 2. Guardar provider_order_id
    | 3. Retornar init_point
    |--------------------------------------------------------------------------
    */

    return [

        'status' => 'success',

        'checkout_url' =>
            'confirmar-pago?id=' .
            $order['id_order'],

        'provider_order_id' => null

    ];
}

function getMercadoPagoPublicKey(): string
{
    return (string)
        getSetting(
            'mercadopago_public_key',
            ''
        );
}

function getMercadoPagoAccessToken(): string
{
    return (string)
        getSetting(
            'mercadopago_access_token',
            ''
        );
}

function getMercadoPagoWebhookSecret(): string
{
    return (string)
        getSetting(
            'mercadopago_webhook_secret',
            ''
        );
}