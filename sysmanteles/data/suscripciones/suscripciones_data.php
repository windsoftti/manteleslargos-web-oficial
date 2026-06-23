<?php

include '../lib/session-root.php';
include '../lib/pagination.php';
include '../lib/subscriptions.php';

//$action = $_POST['action'];
$action = $_POST['action'] ?? '';

switch($action){

    case 'list_subscriptions':

        $page = intval($_POST['page']);
        $page = $page > 0 ? $page : 1;

        $per_page = intval($_POST['perPage']);
        $per_page = $per_page > 0 ? $per_page : 10;

        $search = cleanStr($_POST['search']);

        $where_search = $search != ''
            ? "AND s.Salon LIKE '%$search%'"
            : "";

        $query_total = "
            SELECT COUNT(s.idSalon) AS Total
            FROM salones s
            WHERE
                s.Status = 'Activo'
                $where_search
        ";

        $num_pages = numPages(
            $query_total,
            $per_page
        );

        $offset = ($page - 1) * $per_page;

        $query = "
            SELECT

                s.idSalon,
                s.Salon,

                bs.id_subscription,
                bs.status,
                bs.billing_cycle,
                bs.is_recurring,

                bs.starts_at,
                bs.expires_at,

                bs.trial_starts_at,
                bs.trial_expires_at,

                p.idPlan,
                p.Plan,

                CASE
                    WHEN bs.id_subscription IS NULL THEN 'Basico'
                    ELSE p.Plan
                END AS CurrentPlan

            FROM salones s

            LEFT JOIN ml_business_subscriptions bs
                ON bs.id_salon = s.idSalon

            LEFT JOIN ml_planes p
                ON p.idPlan = bs.id_plan

            WHERE
                s.Status = 'Activo'
                $where_search

            ORDER BY

            CASE
                WHEN bs.id_subscription IS NULL THEN 2
                ELSE 1
            END ASC,

            bs.expires_at ASC,

            s.Salon ASC

            LIMIT $offset,$per_page
        ";

        $query_result = mysqli_query(
            $mysqli,
            $query
        );

        include 'suscripciones_table.php';

        mysqli_close($mysqli);
        die();

    break;

    case 'load_plans':

        $plans = getActivePlans($mysqli);

        echo json_encode([
            'status' => 'success',
            'plans' => $plans
        ]);

        mysqli_close($mysqli);
        exit;

    break;

    case 'load_businesses':

        $query = "
            SELECT
                idSalon,
                Salon
            FROM salones
            WHERE
                Status = 'Activo'
            ORDER BY Salon ASC
        ";

        $result = mysqli_query(
            $mysqli,
            $query
        );

        $businesses = [];

        while(
            $row = mysqli_fetch_assoc(
                $result
            )
        ){
            $businesses[] = $row;
        }

        echo json_encode([
            'status' => 'success',
            'businesses' => $businesses
        ]);

        mysqli_close($mysqli);
        exit;

    break;

    case 'add_subscription':

        $idSalon = intval(
            $_POST['idSalon']
        );

        $idPlan = intval(
            $_POST['idPlan']
        );

        $status = cleanStr(
            $_POST['status']
        );

        $billingCycle = cleanStr(
            $_POST['billingCycle']
        );

        $isRecurring = cleanStr(
            $_POST['isRecurring']
        );

        $trialEnabled = intval(
            $_POST['trialEnabled']
        );

        $trialDays = intval(
            $_POST['trialDays']
        );

        $startsAt = cleanStr(
            $_POST['startsAt']
        );

        $expiresAt = cleanStr(
            $_POST['expiresAt']
        );

        if (
            !$idSalon ||
            !$idPlan
        ) {

            echo json_encode([
                'status' => 'error',
                'message' => 'Datos incompletos'
            ]);

            exit;
        }

        $startTimestamp =
            strtotime($startsAt);

        $expireTimestamp =
            strtotime($expiresAt);

        if (
            $expireTimestamp <= $startTimestamp
        ) {

            echo json_encode([
                'status' => 'error',
                'message' =>
                    'La fecha de vencimiento debe ser mayor a la fecha de inicio'
            ]);

            exit;
        }

        $plan = getPlanById(
            $mysqli,
            $idPlan
        );

        if (!$plan) {

            echo json_encode([
                'status' => 'error',
                'message' => 'Plan inválido'
            ]);

            exit;
        }

        mysqli_query(
            $mysqli,
            "
            UPDATE
                ml_business_subscriptions
            SET
                is_current = 'no'
            WHERE
                id_salon = $idSalon
            "
        );

        $trialStartsAt = 'NULL';
        $trialExpiresAt = 'NULL';

        if ($trialEnabled === 1) {

            $trialExpireTimestamp =
                strtotime(
                    $startsAt .
                    " +$trialDays days"
                );

            if (
                $trialExpireTimestamp >
                strtotime($expiresAt)
            ) {

                echo json_encode([
                    'status' => 'error',
                    'message' =>
                        'El periodo de prueba excede la fecha de vencimiento'
                ]);

                exit;
            }

            $status = 'trial';

            $trialStartsAt =
                "'" .
                $startsAt .
                " 00:00:00'";

            $trialExpiresAt =
                "'" .
                date(
                    'Y-m-d H:i:s',
                    strtotime(
                        $startsAt .
                        " +$trialDays days"
                    )
                ) .
                "'";
        }

        $query = "
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
                is_recurring,
                created_at
            )
            VALUES
            (
                $idSalon,
                $idPlan,
                '{$plan['slug']}',
                '$billingCycle',
                '$status',
                'yes',
                '$startsAt 00:00:00',
                '$expiresAt 23:59:59',
                $trialStartsAt,
                $trialExpiresAt,
                '$isRecurring',
                NOW()
            )
        ";

        $result = mysqli_query(
            $mysqli,
            $query
        );

        if (!$result) {

            echo json_encode([
                'status' => 'error',
                'message' => mysqli_error(
                    $mysqli
                )
            ]);

            exit;
        }

        $subscriptionId =
            mysqli_insert_id(
                $mysqli
            );

        createSubscriptionHistory(
            $mysqli,
            $subscriptionId,
            'created',
            'Suscripción creada desde SysManteles'
        );

        echo json_encode([
            'status' => 'success',
            'message' => 'Suscripción creada correctamente'
        ]);

        mysqli_close(
            $mysqli
        );

        exit;

    break;

    case 'edit_subscription':

        $subscriptionId = intval(
            $_POST['idSubscription']
        );

        $planId = intval(
            $_POST['idPlan']
        );

        $status = cleanStr(
            $_POST['status']
        );

        $billingCycle = cleanStr(
            $_POST['billingCycle']
        );

        $isRecurring = cleanStr(
            $_POST['isRecurring']
        );

        $startsAt = cleanStr(
            $_POST['startsAt']
        );

        $expiresAt = cleanStr(
            $_POST['expiresAt']
        );

        $startTimestamp =
            strtotime($startsAt);

        $expireTimestamp =
            strtotime($expiresAt);

        if (
            $expireTimestamp <= $startTimestamp
        ) {

            echo json_encode([
                'status' => 'error',
                'message' =>
                    'La fecha de vencimiento debe ser mayor a la fecha de inicio'
            ]);

            exit;
        }

        if (
            !$subscriptionId ||
            !$planId
        ) {

            echo json_encode([
                'status' => 'error',
                'message' => 'Datos incompletos'
            ]);

            exit;
        }

        /*
        |--------------------------------------------------------------------------
        | VALIDAR PLAN
        |--------------------------------------------------------------------------
        */

        $allowedPlans =
            getAllowedPlanIds();

        if (
            !in_array(
                $planId,
                $allowedPlans
            )
        ) {

            echo json_encode([
                'status' => 'error',
                'message' => 'Plan no permitido'
            ]);

            exit;
        }

        $plan = getPlanById(
            $mysqli,
            $planId
        );

        if (!$plan) {

            echo json_encode([
                'status' => 'error',
                'message' => 'Plan inválido'
            ]);

            exit;
        }

        /*
        |--------------------------------------------------------------------------
        | OBTENER SUSCRIPCIÓN ACTUAL
        |--------------------------------------------------------------------------
        */

        $queryCurrent = "
            SELECT *
            FROM ml_business_subscriptions
            WHERE
                id_subscription = $subscriptionId
            LIMIT 1
        ";

        $resultCurrent = mysqli_query(
            $mysqli,
            $queryCurrent
        );

        if (
            !$resultCurrent ||
            !mysqli_num_rows(
                $resultCurrent
            )
        ) {

            echo json_encode([
                'status' => 'error',
                'message' => 'Suscripción no encontrada'
            ]);

            exit;
        }

        $currentSubscription =
            mysqli_fetch_assoc(
                $resultCurrent
            );

        /*
        |--------------------------------------------------------------------------
        | UPDATE
        |--------------------------------------------------------------------------
        */

        $query = "
            UPDATE
                ml_business_subscriptions
            SET

                id_plan = $planId,

                plan_slug = '{$plan['slug']}',

                status = '$status',

                billing_cycle = '$billingCycle',

                starts_at = '$startsAt 00:00:00',

                expires_at = '$expiresAt 23:59:59',

                is_recurring = '$isRecurring',

                updated_at = NOW()

            WHERE
                id_subscription = $subscriptionId

            LIMIT 1
        ";

        $updated = mysqli_query(
            $mysqli,
            $query
        );

        if (!$updated) {

            echo json_encode([
                'status' => 'error',
                'message' => mysqli_error(
                    $mysqli
                )
            ]);

            exit;
        }

        /*
        |--------------------------------------------------------------------------
        | HISTORIAL
        |--------------------------------------------------------------------------
        */

        createSubscriptionHistory(
            $mysqli,
            $subscriptionId,
            'updated',
            'Actualización manual desde SysManteles'
        );

        /*
        |--------------------------------------------------------------------------
        | CAMBIO DE PLAN
        |--------------------------------------------------------------------------
        */

        if (
            $currentSubscription['id_plan']
            !=
            $planId
        ) {

            createSubscriptionHistory(
                $mysqli,
                $subscriptionId,
                'plan_changed',
                'Cambio de plan desde SysManteles'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | CANCELACIÓN
        |--------------------------------------------------------------------------
        */

        if (
            $status === 'cancelled'
            &&
            $currentSubscription['status']
            !== 'cancelled'
        ) {

            createSubscriptionHistory(
                $mysqli,
                $subscriptionId,
                'cancelled',
                'Suscripción cancelada desde SysManteles'
            );
        }

        echo json_encode([
            'status' => 'success',
            'message' => 'Suscripción actualizada correctamente'
        ]);

        mysqli_close(
            $mysqli
        );

        exit;

    break;

    /*case 'edit_subscription':

        $subscriptionId =
            intval($_POST['idSubscription']);

        $planId =
            intval($_POST['idPlan']);

        $status =
            cleanStr($_POST['status']);

        $billingCycle =
            cleanStr($_POST['billingCycle']);

        $isRecurring =
            cleanStr($_POST['isRecurring']);

        $startsAt =
            cleanStr($_POST['startsAt']);

        $expiresAt =
            cleanStr($_POST['expiresAt']);

        $queryPlan = "
            SELECT
                idPlan,
                slug
            FROM ml_planes
            WHERE idPlan = $planId
            LIMIT 1
        ";

        $resultPlan = mysqli_query(
            $mysqli,
            $queryPlan
        );

        if(
            !$resultPlan ||
            !mysqli_num_rows($resultPlan)
        ){

            echo json_encode([
                'status' => 'error',
                'message' => 'Plan inválido'
            ]);

            exit;
        }

        $plan =
            mysqli_fetch_assoc(
                $resultPlan
            );

        $planSlug =
            $plan['slug'];

        $query = "
            UPDATE ml_business_subscriptions
            SET

                id_plan = $planId,
                plan_slug = '$planSlug',

                status = '$status',

                billing_cycle = '$billingCycle',

                starts_at = '$startsAt 00:00:00',

                expires_at = '$expiresAt 23:59:59',

                is_recurring = '$isRecurring',

                updated_at = NOW()

            WHERE
                id_subscription = $subscriptionId
            LIMIT 1
        ";

        $updated = mysqli_query(
            $mysqli,
            $query
        );

        if(!$updated){

            echo json_encode([
                'status' => 'error',
                'message' => 'No fue posible actualizar'
            ]);

            exit;
        }

        createSubscriptionHistory(
            $mysqli,
            $subscriptionId,
            'updated',
            'Actualización manual desde SysManteles'
        );

        echo json_encode([
            'status' => 'success',
            'message' => 'Suscripción actualizada'
        ]);

        exit;

    break;*/
}