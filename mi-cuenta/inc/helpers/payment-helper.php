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