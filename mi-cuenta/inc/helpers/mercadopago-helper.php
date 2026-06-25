<?php

/*function createMercadoPagoPreference(
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

    return [

        'status' => 'success',

        'checkout_url' =>
            'confirmar-pago?id=' .
            $order['id_order'],

        'provider_order_id' => null

    ];
}*/

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

    $backUrls =
        getMercadoPagoBackUrls();

    $payload = [

        'items' => [

            [

                'title' =>
                    'Suscripción Premium MantelesLargos',

                'quantity' => 1,

                'currency_id' => 'MXN',

                'unit_price' =>
                    (float) $order['amount']

            ]

        ],

        'external_reference' =>
            (string) $order['id_order'],

        'back_urls' =>
            $backUrls,

        'auto_return' =>
            'approved'

    ];

    $result =
        executeMercadoPagoRequest(
            '/checkout/preferences',
            $payload
        );

    if (
        $result['http_code'] < 200 ||
        $result['http_code'] >= 300
    ) {

        return [

            'status' => 'error',

            'message' =>
                'No fue posible crear la preferencia.',

            'response' =>
                $result

        ];
    }

    $preference =
        $result['response'];

    updateOrderProviderData(
        (int) $order['id_order'],
        'mercadopago',
        $preference['id']
    );

    return [

        'status' => 'success',

        'checkout_url' =>
            $preference['init_point'],

        'provider_order_id' =>
            $preference['id']

    ];
}

function getMercadoPagoHeaders(): array
{
    return [

        'Authorization: Bearer ' .
        getMercadoPagoAccessToken(),

        'Content-Type: application/json'

    ];
}

function getMercadoPagoBaseUrl(): string
{
    return 'https://api.mercadopago.com';
}

function executeMercadoPagoRequest(
    string $endpoint,
    array $payload
): array
{
    $ch = curl_init();

    curl_setopt_array(
        $ch,
        [

            CURLOPT_URL =>
                getMercadoPagoBaseUrl() .
                $endpoint,

            CURLOPT_RETURNTRANSFER => true,

            CURLOPT_POST => true,

            CURLOPT_HTTPHEADER =>
                getMercadoPagoHeaders(),

            CURLOPT_POSTFIELDS =>
                json_encode($payload)

        ]
    );

    $response =
        curl_exec($ch);

    $httpCode =
        curl_getinfo(
            $ch,
            CURLINFO_HTTP_CODE
        );

    $curlError =
        curl_error($ch);

    curl_close($ch);

    return [

        'http_code' => $httpCode,

        'curl_error' => $curlError,

        'response' =>
            json_decode(
                $response,
                true
            )

    ];
}

function getMercadoPagoBackUrls(): array
{
    $appUrl = getAppUrl();

    return [

        'success' =>
            $appUrl .
            '/mi-cuenta/pago-exitoso',

        'failure' =>
            $appUrl .
            '/mi-cuenta/pago-error',

        'pending' =>
            $appUrl .
            '/mi-cuenta/pago-pendiente'

    ];
}