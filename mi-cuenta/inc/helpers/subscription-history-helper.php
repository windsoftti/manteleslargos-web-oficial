<?php

function registerSubscriptionHistory(
    int $subscriptionId,
    string $actionType,
    string $notes = ''
): bool
{
    global $mysqli;

    $sql = "
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
            ?,
            ?,
            ?,
            NOW()
        )
    ";

    $stmt = $mysqli->prepare($sql);

    if (!$stmt) {
        return false;
    }

    $stmt->bind_param(
        'iss',
        $subscriptionId,
        $actionType,
        $notes
    );

    return $stmt->execute();
}