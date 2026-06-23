<?php
include 'inc/public-session.php';

$access_token = cleanStr($_GET['accessToken']);

if ($access_token == '') :
  header('location:' . BASE_URL);
  die();
endif;

$query = "SELECT
    idUsuario,
    Usuario,
    Correo,
    AccessToken,
    TokenStatus,
    Status,
    Nivel
  FROM usuarios
  WHERE
    AccessToken = '$access_token' AND
    TokenStatus = 'Nuevo' AND
    (
      Status = 'Inactivo' OR
      Status = 'Pendiente'
    )
  LIMIT 1
";

$query_result = mysqli_query($mysqli, $query);
$num_rows     = mysqli_num_rows($query_result);

if (!$num_rows) :
  header('location:' . BASE_URL);
  die();
endif;

if ($num_rows) :
  $user_data  = mysqli_fetch_array($query_result);

  $user_id      = $user_data['idUsuario'];
  $user_level   = $user_data['Nivel'];
  $user_name    = $user_data['Usuario'];
  $user_email   = $user_data['Correo'];
  $user_status  = $user_data['Status'];

  $query = "UPDATE usuarios SET
      TokenStatus = 'Usado',
      Status      = 'Activo'
    WHERE idUsuario = $user_id
  ";

  $query_result = mysqli_query($mysqli, $query);

  if (!$query_result) :
    header('location:' . BASE_URL);
    die();
  endif;

  if ($query_result) :
    $_SESSION['session_user_id']      = $user_id;
    $_SESSION['session_user_name']    = $user_name;
    $_SESSION['session_user_level']   = $user_level;
    $_SESSION['session_user_email']   = $user_email;
    $_SESSION['session_user_status']  = 'Activo';

    $uid = md5('verificado');

    header('location:' . BASE_URL . '/' . 'cuenta-verificada?uid=' . $uid);
    die();
  endif;
endif;
