<?php

function getPlan()
{
    global $mysqli;

    if (
        empty($_SESSION['session_business_id'])
    ) {
        return 'Básico';
    }

    $id_salon =
        (int) $_SESSION['session_business_id'];

    $sql = "
        SELECT
            p.Plan
        FROM ml_business_subscriptions s

        INNER JOIN ml_planes p
            ON p.idPlan = s.id_plan

        WHERE
            s.id_salon = ?
            AND s.status IN ('active','trial')

        ORDER BY s.id_subscription DESC

        LIMIT 1
    ";

    $stmt = $mysqli->prepare($sql);

    if (!$stmt) {
        return 'Básico';
    }

    $stmt->bind_param(
        'i',
        $id_salon
    );

    $stmt->execute();

    $result = $stmt->get_result();

    if (!$result->num_rows) {
        return 'Básico';
    }

    $row = $result->fetch_assoc();

    return $row['Plan'];
}

function hasPremiumAccess(): bool
{
    return in_array(
        $_SESSION['session_user_plan'] ?? '',
        [
            'Premium',
            'PRO',
            'Enterprise'
        ]
    );
}

function isTrialSubscription(): bool
{
    $subscription =
        getCurrentSubscription();

    return (
        $subscription &&
        $subscription['status'] === 'trial'
    );
}