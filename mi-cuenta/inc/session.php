<?php
session_start();

require_once 'inc/config.inc.php';
require_once 'inc/constants.inc.php';
require_once 'inc/functions.inc.php';

verifyUserSession();

//Verificamos si hay suscripciones ya vencidas
processExpiredSubscriptions();

if (empty($_SESSION['session_user_id'])) {
  header('Location:' . BASE_URL_FRONTED . '/soy-proveedor');
  die();
}

if ($_SESSION['session_user_level'] != 'Usuario') {
  header('Location:' . BASE_URL_FRONTED . '/soy-proveedor');
  die();
}

$session_supplier_access = checkSupplierAccessStatus();

if ($session_supplier_access['status'] == 'unverified') {
  header('location:' . BASE_URL_FRONTED . '/verificar-cuenta-proveedor');
} else if ($session_supplier_access['status'] == 'no-business') {
  header('location:' . BASE_URL_FRONTED . '/agregar-negocio');
} else if ($session_supplier_access['status'] == 'logged') {
  //header('location:' . BASE_URL_FRONTED . '/mi-cuenta');
} else {
  header('location:' . BASE_URL . '/cerrar-sesion');
}

$session_user_business = getUserBusinesses($_SESSION['session_user_id']);

if ($_POST) {
  $session_business_id = cleanStr($_POST['s_business_id']);
  if ($session_business_id != '') $_SESSION['session_business_id'] = $session_business_id;
}

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

$session_user_plan = getPlan();
$_SESSION['session_user_plan'] = $session_user_plan;

$session_target_free_plan = 'javascript:showUpdatePlanAlert()';
