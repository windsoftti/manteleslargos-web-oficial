<?php
include 'public-session.php';

if (!$_SESSION['session_user_id'] || $_SESSION['session_user_level'] != 'Usuario') :
  header('location:' . BASE_URL);
endif;

$supplier_access = checkSupplierAccessStatus();

if ($supplier_access['status'] == 'unverified') {
  header('location:' . BASE_URL . '/verificar-cuenta-proveedor');
} else if ($supplier_access['status'] == 'no-business') {
  header('location:' . BASE_URL . '/mi-cuenta');
} else if ($supplier_access['status'] == 'logged') {
  //header('location:' . BASE_URL . '/mi-cuenta');
} else {
  //header('location:' . BASE_URL);
}
