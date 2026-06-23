<?php
session_start();

$return_url = $url_host;

require_once 'inc/config.inc.php';
require_once 'inc/constants.inc.php';
require_once 'inc/functions.inc.php';

verifyUserSession();

if (!isset($_SESSION['session_user_id']) || ($_SESSION['session_user_level'] !== 'Super Usuario')) {
    header('Location:' . $return_url);
}

$session_user_business = getUserBusinesses($_SESSION['session_user_id']);

if ($_POST) {
    $session_business_id = cleanStr($_POST['s_business_id']);
    if ($session_business_id != '') $_SESSION['session_business_id'] = $session_business_id;
}

$session_user_plan = getPlan();
$session_target_free_plan = 'javascript:showUpdatePlanAlert()';

if ($_GET) :
    $session_business_id = cleanStr($_GET['business_ref']);
    if ($session_business_id != '') :
        $session_business_id = explode('-', $session_business_id);
        $session_check_business = checkIfBusinessIsForUser($session_business_id[0]);

        if ($session_check_business) :
            $_SESSION['session_business_id'] = $session_business_id[0];
        endif;

        if (!$session_check_business) :
            header('location:cerrar-sesion');
            die;
        endif;
    endif;
endif;
