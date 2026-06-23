<?php

include '../session.php';
include '../../inc/functions.inc.php';

$response = [
    'status' => 'error',
    'message' => 'Acción inválida'
];

$action = $_POST['action'] ?? '';

switch ($action) {

    case 'activate_trial':

        $response =
            activateBusinessTrial();

    break;

    case 'create_subscription_order':

        $businessId =
            (int) $_SESSION['session_business_id'];

        $planId =
            (int) ($_POST['plan_id'] ?? 0);

        $billingCycle =
            cleanStr(
                $_POST['billing_cycle']
                ?? 'monthly'
            );

        $result =
            createSubscriptionOrder(
                $businessId,
                $planId,
                $billingCycle
            );

        $response = $result;

    break;

    case 'create_premium_order':

        $businessId =
            (int) $_SESSION['session_business_id'];

        $result =
            createSubscriptionOrder(
                $businessId,
                2,
                'monthly'
            );

        $response = $result;

    break;
}

echo json_encode($response);