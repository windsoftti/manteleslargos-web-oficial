<?php

function createSubscriptionOrder(
    int $businessId,
    int $planId,
    string $billingCycle = 'monthly'
): array
{
    global $mysqli;

    /*
    |--------------------------------------------------------------------------
    | Obtener plan
    |--------------------------------------------------------------------------
    */

    $sql = "
        SELECT
            idPlan,
            Plan,
            monthly_price,
            semiannual_price,
            annual_price
        FROM ml_planes
        WHERE
            idPlan = ?
        LIMIT 1
    ";

    $stmt = $mysqli->prepare($sql);

    if (!$stmt) {

        return [
            'status' => 'error',
            'message' => $mysqli->error
        ];
    }

    $stmt->bind_param(
        'i',
        $planId
    );

    $stmt->execute();

    $result = $stmt->get_result();

    if (!$result->num_rows) {

        return [
            'status' => 'error',
            'message' => 'Plan no encontrado.'
        ];
    }

    $plan = $result->fetch_assoc();

    /*
    |--------------------------------------------------------------------------
    | Precio según ciclo
    |--------------------------------------------------------------------------
    */

    $amount = 0;

    switch ($billingCycle) {

        case 'annual':
            $amount = $plan['annual_price'];
            break;

        case 'semiannual':
            $amount = $plan['semiannual_price'];
            break;

        default:
            $amount = $plan['monthly_price'];
            break;
    }

    /*
    |--------------------------------------------------------------------------
    | Crear orden
    |--------------------------------------------------------------------------
    */

    $sql = "
        INSERT INTO
        ml_subscription_orders
        (
            id_salon,
            id_plan,
            billing_cycle,
            amount,
            status,
            created_at
        )
        VALUES
        (
            ?,
            ?,
            ?,
            ?,
            'pending',
            NOW()
        )
    ";

    $stmt = $mysqli->prepare($sql);

    if (!$stmt) {

        return [
            'status' => 'error',
            'message' => $mysqli->error
        ];
    }

    $stmt->bind_param(
        'iisd',
        $businessId,
        $planId,
        $billingCycle,
        $amount
    );

    if (!$stmt->execute()) {

        return [
            'status' => 'error',
            'message' => $stmt->error
        ];
    }

    return [
        'status' => 'success',
        'order_id' => $mysqli->insert_id
    ];
}

function getBusinessOrders(): array
{
    global $mysqli;

    if (
        empty($_SESSION['session_business_id'])
    ) {
        return [];
    }

    $businessId =
        (int) $_SESSION['session_business_id'];

    $sql = "
        SELECT
            o.id_order,
            p.Plan AS plan_name,
            p.slug,
            o.billing_cycle,
            o.amount,
            o.status,
            o.created_at
        FROM ml_subscription_orders o

        INNER JOIN ml_planes p
            ON p.idPlan = o.id_plan

        WHERE o.id_salon = ?

        ORDER BY o.id_order DESC
    ";

    $stmt = $mysqli->prepare($sql);

    if (!$stmt) {
        return [];
    }

    $stmt->bind_param(
        'i',
        $businessId
    );

    $stmt->execute();

    $result = $stmt->get_result();

    $orders = [];

    while (
        $row = $result->fetch_assoc()
    ) {
        $orders[] = $row;
    }

    return $orders;
}

function getSubscriptionOrder(
    int $orderId
): ?array
{
    global $mysqli;

    $sql = "
        SELECT *
        FROM ml_subscription_orders
        WHERE id_order = ?
        LIMIT 1
    ";

    $stmt = $mysqli->prepare($sql);

    if (!$stmt) {
        return null;
    }

    $stmt->bind_param(
        'i',
        $orderId
    );

    $stmt->execute();

    $result = $stmt->get_result();

    if (!$result->num_rows) {
        return null;
    }

    return $result->fetch_assoc();
}

function getOrderById(
    int $orderId
): ?array
{
    global $mysqli;

    $businessId =
        (int) $_SESSION['session_business_id'];

    $sql = "
        SELECT
            o.*,
            p.Plan,
            p.slug
        FROM ml_subscription_orders o

        INNER JOIN ml_planes p
            ON p.idPlan = o.id_plan

        WHERE
            o.id_order = ?
            AND o.id_salon = ?

        LIMIT 1
    ";

    $stmt = $mysqli->prepare($sql);

    if (!$stmt) {
        return null;
    }

    $stmt->bind_param(
        'ii',
        $orderId,
        $businessId
    );

    $stmt->execute();

    $result =
        $stmt->get_result();

    if (!$result->num_rows) {
        return null;
    }

    return $result->fetch_assoc();
}

function processPaidOrder(
    int $orderId,
    string $reference = ''
): array
{
    global $mysqli;

    $order =
        getSubscriptionOrder(
            $orderId
        );

    if (!$order) {

        return [
            'status' => 'error',
            'message' => 'Orden no encontrada.'
        ];
    }

    if (
        $order['status'] !== 'pending'
    ) {

        return [
            'status' => 'error',
            'message' => 'La orden ya fue procesada.'
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | Marcar pagada
    |--------------------------------------------------------------------------
    */

    $sql = "
        UPDATE
            ml_subscription_orders
        SET
            status = 'paid',
            payment_reference = ?,
            updated_at = NOW()
        WHERE
            id_order = ?
    ";

    $stmt = $mysqli->prepare($sql);

    $stmt->bind_param(
        'si',
        $reference,
        $orderId
    );

    $stmt->execute();

    /*
    |--------------------------------------------------------------------------
    | Activar suscripción
    |--------------------------------------------------------------------------
    */

    $activation =
        activatePremiumSubscription(
            (int) $order['id_salon'],
            $order['billing_cycle']
        );

    if (
        $activation['status']
        !== 'success'
    ) {

        return $activation;
    }

    registerSubscriptionHistory(
        $activation['subscription_id'],
        'payment_received',
        'Orden #' . $orderId . ' pagada'
    );

    return [
        'status' => 'success'
    ];
}

function markOrderAsPaid(
    int $orderId,
    string $reference = ''
): array
{
    return processPaidOrder(
        $orderId,
        $reference
    );
}
/*function markOrderAsPaid(
    int $orderId
): array
{
    global $mysqli;

    $sql = "
        SELECT
            *
        FROM ml_subscription_orders
        WHERE
            id_order = ?
        LIMIT 1
    ";

    $stmt = $mysqli->prepare($sql);

    if (!$stmt) {

        return [
            'status' => 'error',
            'message' => $mysqli->error
        ];
    }

    $stmt->bind_param(
        'i',
        $orderId
    );

    $stmt->execute();

    $result =
        $stmt->get_result();

    if (!$result->num_rows) {

        return [
            'status' => 'error',
            'message' => 'Orden no encontrada.'
        ];
    }

    $order =
        $result->fetch_assoc();

    if (
        $order['status'] !== 'pending'
    ) {

        return [
            'status' => 'error',
            'message' => 'La orden ya fue procesada.'
        ];
    }

    $sql = "
        UPDATE
            ml_subscription_orders
        SET
            status = 'paid',
            updated_at = NOW()
        WHERE
            id_order = ?
    ";

    $stmt = $mysqli->prepare($sql);

    $stmt->bind_param(
        'i',
        $orderId
    );

    $stmt->execute();

    $activation =
        activatePremiumSubscription(
            (int) $order['id_salon'],
            $order['billing_cycle']
        );

    if (
        $activation['status']
        !== 'success'
    ) {

        return $activation;
    }

    registerSubscriptionHistory(
        $activation['subscription_id'],
        'payment_received',
        'Orden #' . $orderId . ' pagada'
    );

    return [
        'status' => 'success'
    ];
}*/

function getBillingCycleLabel(
    string $cycle
): string
{
    $cycles = [

        'monthly' => 'Mensual',

        'semiannual' => 'Semestral',

        'annual' => 'Anual'

    ];

    return $cycles[$cycle] ?? $cycle;
}

function getOrderStatusLabel(
    string $status
): string
{
    $statuses = [

        'pending' => 'Pendiente',

        'paid' => 'Pagada',

        'cancelled' => 'Cancelada'

    ];

    return $statuses[$status] ?? $status;
}

function getPlanLabel(
    string $planSlug
): string
{
    $plans = [

        'basico'     => 'Básico',

        'premium'    => 'Premium',

        'pro'        => 'PRO',

        'enterprise' => 'Enterprise'

    ];

    return $plans[$planSlug] ?? $planSlug;
}