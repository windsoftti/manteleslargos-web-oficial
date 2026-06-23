<?php

function getActivePlans(mysqli $mysqli): array
{
    $plans = [];

    $query = "
        SELECT
            idPlan,
            Plan,
            slug
        FROM ml_planes
        WHERE status = 'active'
        AND slug IN (
        'basico',
        'premium'
        )
        ORDER BY idPlan
    ";

    $result = mysqli_query($mysqli, $query);

    while ($row = mysqli_fetch_assoc($result)) {
        $plans[] = $row;
    }

    return $plans;
}

function getBusinessSubscription(
    mysqli $mysqli,
    int $businessId
): ?array {

    $query = "
        SELECT
            bs.*,
            p.Plan
        FROM ml_business_subscriptions bs
        INNER JOIN ml_planes p
            ON p.idPlan = bs.id_plan
        WHERE
            bs.id_salon = $businessId
            AND bs.is_current = 'yes'
        LIMIT 1
    ";

    $result = mysqli_query($mysqli, $query);

    if (!$result) {
        return null;
    }

    return mysqli_fetch_assoc($result);
}

/**POR AHORA NO USAMOS ESTA FUNCION**/

/*function getBillingDays(
    string $billingCycle
): int {

    return match ($billingCycle) {

        'monthly' => 30,

        'semiannual' => 180,

        'annual' => 365,

        default => 30
    };
}*/
function getBillingDays($billingCycle)
{
    switch ($billingCycle) {

        case 'monthly':
            return 30;

        case 'semiannual':
            return 180;

        case 'annual':
            return 365;

        default:
            return 30;
    }
}

function getAllowedPlans(): array
{
    return [
        'basico',
        'premium'
    ];
}

function getAllowedPlanIds(): array
{
    return [
        1,
        2
    ];
}

/*function createSubscriptionHistory(
    mysqli $mysqli,
    int $subscriptionId,
    string $actionType,
    string $notes = ''
): void {

    $subscriptionId = intval(
        $subscriptionId
    );

    $actionType = mysqli_real_escape_string(
        $mysqli,
        $actionType
    );

    $notes = mysqli_real_escape_string(
        $mysqli,
        $notes
    );

    $query = "
        INSERT INTO
        ml_subscription_history
        (
            id_subscription,
            action_type,
            notes,
            created_at
        )
        VALUES
        (
            $subscriptionId,
            '$actionType',
            '$notes',
            NOW()
        )
    ";

    mysqli_query(
        $mysqli,
        $query
    );
}*/
function createSubscriptionHistory(
    mysqli $mysqli,
    int $subscriptionId,
    string $actionType,
    string $notes = ''
): bool {

    $subscriptionId = intval(
        $subscriptionId
    );

    $actionType = mysqli_real_escape_string(
        $mysqli,
        $actionType
    );

    $notes = mysqli_real_escape_string(
        $mysqli,
        $notes
    );

    $query = "
        INSERT INTO
        ml_subscription_history
        (
            id_subscription,
            action_type,
            notes,
            created_at
        )
        VALUES
        (
            $subscriptionId,
            '$actionType',
            '$notes',
            NOW()
        )
    ";

    return mysqli_query(
        $mysqli,
        $query
    );
}

function getPlanById(
    mysqli $mysqli,
    int $idPlan
): ?array {

    $query = "
        SELECT
            idPlan,
            Plan,
            slug
        FROM ml_planes
        WHERE idPlan = $idPlan
        LIMIT 1
    ";

    $result = mysqli_query(
        $mysqli,
        $query
    );

    if (
        !$result ||
        !mysqli_num_rows($result)
    ) {
        return null;
    }

    return mysqli_fetch_assoc(
        $result
    );
}