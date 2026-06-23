<?php
include '../lib/session-auth.php';

$action = $_POST['action'];

$initial_response = array(
  'status'  => 'error',
  'title'   => '¡Error!',
  'message' => 'Error inesperado, intentalo nuevamente'
);

$response = $initial_response;

switch ($action) {
  case 'login':
    $user     = cleanStr($_POST['user']);
    $password = encrypt(cleanStr($_POST['password']), MYSQLI_PASSWORD_SECRET);

    $query = "SELECT
        UserId,
        FullName,
        UserType
      FROM ml_admin_users
      WHERE
        (
          Email         = BINARY '$user' AND
          UserPassword  = BINARY '$password'
        ) OR
        (
          Username      = BINARY '$user' AND
          UserPassword  = BINARY '$password'
        )
      LIMIT 1
    ";

    $query_result = mysqli_query($mysqli, $query);
    $num_rows     = mysqli_num_rows($query_result);

    if (!$num_rows) $response = array(
      'status'  => 'warning',
      'title'   => '¡Usuario invalido!',
      'message' => 'Sus datos de acceso son incorectos.'
    );

    if ($num_rows) {
      $user_data = mysqli_fetch_array($query_result);

      $user_id        = $user_data['UserId'];
      $user_type      = $user_data['UserType'];
      $user_full_name = $user_data['FullName'];

      $_SESSION['adm_session_user_id']          = $user_id;
      $_SESSION['adm_session_user_type']        = $user_type;
      $_SESSION['adm_session_user_full_name']   = $user_full_name;

      $response = array(
        'status' => 'success'
      );
    }
    break;

  case 'recover_credentials':
    $email = cleanStr($_POST['email']);

    $email_structure = filter_var($email, FILTER_VALIDATE_EMAIL);

    if (!$email_structure) $response['message'] = 'El correo ingresado no es invalido.';

    if ($email_structure) {
      $query = "SELECT
          UserId,
          FullName,
          Email,
          Username,
          UserPassword
        FROM ml_admin_users WHERE
          Email = BINARY '$email'
        LIMIT 1
      ";

      $query_result = mysqli_query($mysqli, $query);
      $num_rows     = mysqli_num_rows($query_result);

      if (!$num_rows) $response['message'] = 'El correo ingresado no es valido.';

      if ($num_rows) {
        $user_data = mysqli_fetch_array($query_result);

        $user     = $user_data['FullName'];
        $username = $user_data['Username'];
        $password = decrypt($user_data['UserPassword'], MYSQLI_PASSWORD_SECRET);

        $from     = "no-responder@windsoftti.com";
        $to       = $email;
        $subject  = "WindsoftTi | Credenciales de Acceso";

        $message   = $user . ' sus credenciales de acceso son: <br><br>';
        $message  .= '<b>Correo:</b> ' . $email . '<br>';
        $message  .= '<b>Usuario:</b> ' . $username . '<br>';
        $message  .= '<b>Contraseña:</b>' . $password . '<br>';

        $headers  = 'MIME-Version: 1.0' . "\r\n";
        $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
        $headers .= 'From: WindsoftTi <no-responder@windsoftti.com>' . "\r\n";

        $send = mail($to, $subject, $message, $headers);

        if (!$send) $response['message'] = 'No pudo enviarse las credenciales de acceso, intentelo mas tarde.';

        if ($send) $response = array(
          'status'   => 'success',
          'title'   => '¡Datos enviados!',
          'message' => '"' . $user . '" tus credenciales de acceso se enviaron correctamente a tu correo electrónico. <br>Nota: Favor de revisar el apartado de spam en caso de no aparecer en la bandeja principal.'
        );
      }
    }
    break;
}

echo json_encode($response);
mysqli_close($mysqli);
