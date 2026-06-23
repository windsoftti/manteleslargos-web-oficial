<?php

/*function getCurrentSubscription(): array
{
    global $mysqli;

    if (
        empty($_SESSION['session_business_id'])
    ) {

        return [
            'plan'        => 'Básico',
            'plan_slug'   => 'basico',
            'status'      => 'active',
            'expires_at'  => null,
            'days_left'   => null,
            'is_trial'    => false
        ];
    }

    $idSalon =
        (int) $_SESSION['session_business_id'];

    $sql = "
        SELECT
            s.id_subscription,
            s.plan_slug,
            s.status,
            s.expires_at,
            s.trial_expires_at,
            p.Plan
        FROM ml_business_subscriptions s

        INNER JOIN ml_planes p
            ON p.idPlan = s.id_plan

        WHERE
            s.id_salon = ?
            AND s.is_current = 'yes'

        ORDER BY s.id_subscription DESC

        LIMIT 1
    ";

    $stmt = $mysqli->prepare($sql);

    if (!$stmt) {

        return [
            'plan'        => 'Básico',
            'plan_slug'   => 'basico',
            'status'      => 'active',
            'expires_at'  => null,
            'days_left'   => null,
            'is_trial'    => false
        ];
    }

    $stmt->bind_param(
        'i',
        $idSalon
    );

    $stmt->execute();

    $result = $stmt->get_result();

    if (!$result->num_rows) {

        return [
            'plan'        => 'Básico',
            'plan_slug'   => 'basico',
            'status'      => 'active',
            'expires_at'  => null,
            'days_left'   => null,
            'is_trial'    => false
        ];
    }

    $subscription =
        $result->fetch_assoc();

    $isTrial =
        $subscription['status'] === 'trial';

    $expirationDate =
        $isTrial
            ? $subscription['trial_expires_at']
            : $subscription['expires_at'];

    $daysLeft = null;

    if (!empty($expirationDate)) {

        $today = new DateTime();

        $expires = new DateTime(
            $expirationDate
        );

        $daysLeft =
            (int) $today->diff(
                $expires
            )->format('%r%a');
    }

    return [
        'plan'        => $subscription['Plan'],
        'plan_slug'   => $subscription['plan_slug'],
        'status'      => $subscription['status'],
        'expires_at'  => $expirationDate,
        'days_left'   => $daysLeft,
        'is_trial'    => $isTrial
    ];
}*/

function getCurrentSubscription(): array
{
    global $mysqli;

    $defaultSubscription = [
        'id_subscription'  => null,
        'id_plan'          => 1,

        'plan'             => 'Básico',
        'plan_slug'        => 'basico',

        'status'           => 'active',
        'billing_cycle'    => null,

        'starts_at'        => null,
        'expires_at'       => null,
        'grace_until'      => null,

        'days_left'        => null,

        'is_trial'         => false,
        'is_recurring'     => false,

        'monthly_price'    => 0,
        'semiannual_price' => 0,
        'annual_price'     => 0
    ];

    if (
        empty($_SESSION['session_business_id'])
    ) {
        return $defaultSubscription;
    }

    $idSalon =
        (int) $_SESSION['session_business_id'];

    $sql = "
        SELECT
            s.id_subscription,
            s.id_plan,
            s.plan_slug,
            s.billing_cycle,
            s.status,
            s.starts_at,
            s.expires_at,
            s.grace_until,
            s.trial_starts_at,
            s.trial_expires_at,
            s.is_recurring,

            p.Plan,
            p.monthly_price,
            p.semiannual_price,
            p.annual_price

        FROM ml_business_subscriptions s

        INNER JOIN ml_planes p
            ON p.idPlan = s.id_plan

        WHERE
            s.id_salon = ?
            AND s.is_current = 'yes'

        ORDER BY s.starts_at DESC

        LIMIT 1
    ";

    $stmt = $mysqli->prepare($sql);

    if (!$stmt) {
        return $defaultSubscription;
    }

    $stmt->bind_param(
        'i',
        $idSalon
    );

    $stmt->execute();

    $result = $stmt->get_result();

    if (!$result->num_rows) {
        return $defaultSubscription;
    }

    $subscription =
        $result->fetch_assoc();

    $isTrial =
        $subscription['status'] === 'trial';

    $expirationDate =
        $isTrial
            ? $subscription['trial_expires_at']
            : $subscription['expires_at'];

    $daysLeft = null;

    if (!empty($expirationDate)) {

        $today = new DateTime();

        $expires = new DateTime(
            $expirationDate
        );

        $daysLeft =
            (int) $today->diff(
                $expires
            )->format('%r%a');
    }

    return [

        'id_subscription' =>
            (int) $subscription['id_subscription'],

        'id_plan' =>
            (int) $subscription['id_plan'],

        'plan' =>
            $subscription['Plan'],

        'plan_slug' =>
            $subscription['plan_slug'],

        'status' =>
            $subscription['status'],

        'billing_cycle' =>
            $subscription['billing_cycle'],

        'starts_at' =>
            $subscription['starts_at'],

        'expires_at' =>
            $expirationDate,

        'grace_until' =>
            $subscription['grace_until'],

        'days_left' =>
            $daysLeft,

        'is_trial' =>
            $isTrial,

        'is_recurring' =>
            $subscription['is_recurring'] === 'yes',

        'monthly_price' =>
            (float) $subscription['monthly_price'],

        'semiannual_price' =>
            (float) $subscription['semiannual_price'],

        'annual_price' =>
            (float) $subscription['annual_price']
    ];
}

function businessCanActivateTrial(): bool
{
    global $mysqli;

    if (
        empty($_SESSION['session_business_id'])
    ) {
        return false;
    }

    $idSalon =
        (int) $_SESSION['session_business_id'];

    $sql = "
        SELECT
            id_subscription
        FROM ml_business_subscriptions
        WHERE
            id_salon = ?
            AND trial_used = 'yes'
        LIMIT 1
    ";

    $stmt = $mysqli->prepare($sql);

    if (!$stmt) {
        return false;
    }

    $stmt->bind_param(
        'i',
        $idSalon
    );

    $stmt->execute();

    $result = $stmt->get_result();

    return !$result->num_rows;
}

function activateBusinessTrial(): array
{
    global $mysqli;

    $businessId =
        (int) $_SESSION['session_business_id'];

    if (!$businessId) {

        return [
            'status'  => 'error',
            'message' => 'Negocio inválido.'
        ];
    }

    if (!businessCanActivateTrial()) {

        return [
            'status'  => 'error',
            'message' => 'La prueba gratuita ya fue utilizada.'
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | Desactivar suscripciones anteriores
    |--------------------------------------------------------------------------
    */

    $sql = "
        UPDATE
            ml_business_subscriptions
        SET
            is_current = 'no'
        WHERE
            id_salon = ?
    ";

    $stmt = $mysqli->prepare($sql);

    if (!$stmt) {

        return [
            'status'  => 'error',
            'message' => $mysqli->error
        ];
    }

    $stmt->bind_param(
        'i',
        $businessId
    );

    $stmt->execute();

    /*
    |--------------------------------------------------------------------------
    | Fechas Trial
    |--------------------------------------------------------------------------
    */

    $now =
        date('Y-m-d H:i:s');

    $trialExpiresAt =
        date(
            'Y-m-d H:i:s',
            strtotime('+30 days')
        );

    /*
    |--------------------------------------------------------------------------
    | Crear Trial Premium
    |--------------------------------------------------------------------------
    */

    $sql = "
        INSERT INTO
        ml_business_subscriptions
        (
            id_salon,
            id_plan,
            plan_slug,
            billing_cycle,
            status,
            is_current,
            starts_at,
            expires_at,
            trial_starts_at,
            trial_expires_at,
            trial_used,
            is_recurring,
            created_at
        )
        VALUES
        (
            ?,
            2,
            'premium',
            'monthly',
            'trial',
            'yes',
            ?,
            ?,
            ?,
            ?,
            'yes',
            'no',
            ?
        )
    ";

    $stmt = $mysqli->prepare($sql);

    if (!$stmt) {

        return [
            'status'  => 'error',
            'message' => $mysqli->error
        ];
    }

    $stmt->bind_param(
        'isssss',
        $businessId,
        $now,
        $trialExpiresAt,
        $now,
        $trialExpiresAt,
        $now
    );

    $result = $stmt->execute();

    if (!$result) {

        return [
            'status'  => 'error',
            'message' => $stmt->error
        ];
    }

    if ($stmt->affected_rows <= 0) {

        return [
            'status'  => 'error',
            'message' => 'No fue posible crear la suscripción.'
        ];
    }

    $subscriptionId =
    $mysqli->insert_id;

    registerSubscriptionHistory(
        $subscriptionId,
        'trial_started',
        'Prueba Premium activada por 30 días.'
    );

    return [
        'status'  => 'success',
        'message' => 'Prueba Premium activada correctamente.'
    ];
}

function processExpiredSubscriptions(): void
{
    global $mysqli;

    $sql = "
        SELECT
            id_subscription
        FROM ml_business_subscriptions
        WHERE
            status = 'trial'
            AND trial_expires_at <= NOW()
    ";

    $result = $mysqli->query($sql);

    while (
        $subscription =
            $result->fetch_assoc()
    ) {

        registerSubscriptionHistory(
            $subscription['id_subscription'],
            'disabled',
            'Periodo de prueba expirado.'
        );
    }

    $sql = "
        UPDATE
            ml_business_subscriptions
        SET
            status = 'expired',
            is_current = 'no',
            updated_at = NOW()
        WHERE
            status = 'trial'
            AND trial_expires_at <= NOW()
    ";

    $mysqli->query($sql);
}

function activatePremiumSubscription(
    int $businessId,
    string $billingCycle = 'monthly'
): array
{
    global $mysqli;

    /*
    |--------------------------------------------------------------------------
    | Obtener plan Premium
    |--------------------------------------------------------------------------
    */

    $sql = "
        SELECT
            idPlan,
            Plan
        FROM ml_planes
        WHERE slug = 'premium'
        LIMIT 1
    ";

    $result = $mysqli->query($sql);

    if (!$result || !$result->num_rows) {

        return [
            'status'  => 'error',
            'message' => 'No existe el plan Premium.'
        ];
    }

    $plan = $result->fetch_assoc();

    $idPlan = (int) $plan['idPlan'];

    /*
    |--------------------------------------------------------------------------
    | Desactivar suscripción actual
    |--------------------------------------------------------------------------
    */

    $sql = "
        UPDATE
            ml_business_subscriptions
        SET
            is_current = 'no',
            updated_at = NOW()
        WHERE
            id_salon = ?
    ";

    $stmt = $mysqli->prepare($sql);

    $stmt->bind_param(
        'i',
        $businessId
    );

    $stmt->execute();

    /*
    |--------------------------------------------------------------------------
    | Fechas
    |--------------------------------------------------------------------------
    */

    $startsAt =
        date('Y-m-d H:i:s');

    switch ($billingCycle) {

        case 'annual':

            $expiresAt =
                date(
                    'Y-m-d H:i:s',
                    strtotime('+365 days')
                );

            break;

        case 'semiannual':

            $expiresAt =
                date(
                    'Y-m-d H:i:s',
                    strtotime('+180 days')
                );

            break;

        default:

            $expiresAt =
                date(
                    'Y-m-d H:i:s',
                    strtotime('+30 days')
                );
    }

    /*
    |--------------------------------------------------------------------------
    | Crear suscripción Premium
    |--------------------------------------------------------------------------
    */

    $sql = "
        INSERT INTO
        ml_business_subscriptions
        (
            id_salon,
            id_plan,
            plan_slug,
            billing_cycle,
            status,
            is_current,
            starts_at,
            expires_at,
            trial_used,
            is_recurring,
            created_at
        )
        VALUES
        (
            ?,
            ?,
            'premium',
            ?,
            'active',
            'yes',
            ?,
            ?,
            'yes',
            'no',
            NOW()
        )
    ";

    $stmt = $mysqli->prepare($sql);

    if (!$stmt) {
        return [
            'status'  => 'error',
            'message' => $mysqli->error
        ];
    }

    $stmt->bind_param(
        'iisss',
        $businessId,
        $idPlan,
        $billingCycle,
        $startsAt,
        $expiresAt
    );

    $result = $stmt->execute();

    if (!$result) {

        return [
            'status'  => 'error',
            'message' => $stmt->error
        ];
    }

    $subscriptionId =
        $mysqli->insert_id;

    /*
    |--------------------------------------------------------------------------
    | Historial
    |--------------------------------------------------------------------------
    */

    registerSubscriptionHistory(
        $subscriptionId,
        'created',
        'Suscripción Premium activada.'
    );

    return [
        'status' => 'success',
        'subscription_id' => $subscriptionId,
        'plan' => 'premium'
    ];
}

////////
function trialAvailableLabel(): string
{
    return businessCanActivateTrial()
        ? 'Disponible'
        : 'Utilizado';
}