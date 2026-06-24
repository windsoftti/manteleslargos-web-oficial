<?php

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