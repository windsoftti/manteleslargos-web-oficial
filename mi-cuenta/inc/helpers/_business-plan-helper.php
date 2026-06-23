<?php
///AUN NO LO USAREMOS
function getCurrentBusinessPlan()
{
    global $mysqli;

    if (
        empty($_SESSION['session_business_id'])
    ) {
        return 'Basico';
    }

    $id_salon = (int) $_SESSION['session_business_id'];

    $sql = "
        SELECT
            plan_slug
        FROM ml_business_subscriptions
        WHERE
            id_salon = ?
            AND status = 'active'
        ORDER BY expires_at DESC
        LIMIT 1
    ";

    $stmt = $mysqli->prepare($sql);

    if (!$stmt) {
        return 'Basico';
    }

    $stmt->bind_param(
        'i',
        $id_salon
    );

    $stmt->execute();

    $result = $stmt->get_result();

    if (!$result->num_rows) {
        return 'Basico';
    }

    $subscription = $result->fetch_assoc();

    return ucfirst(
        strtolower(
            $subscription['plan_slug']
        )
    );
}