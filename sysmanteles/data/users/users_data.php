<?php
include '../lib/session-root.php';

$action = $_POST['action'];

$initial_response = array(
  'status'  => 'error',
  'title'   => '¡Error!',
  'message' => 'Error inesperado, intentalo nuevamente'
);

$response = $initial_response;

switch ($action) {
  case 'list_users':
    $user_type = $_SESSION['adm_session_user_type'];

    $page       = cleanStr($_POST['page']);
    $page       = $page != '' ? $page : 1;

    $per_page   = cleanStr($_POST['perPage']);
    $per_page   = $per_page != '' ? $per_page : 1;

    $search     = cleanStr($_POST['search']);
    $search_by  = $search != '' ? "FullName LIKE '%$search%'" : "1=1";

    $w_type   = $user_type === 'Root' ? "1=1" : "UserType != 'Root'";

    $from     = "FROM ml_admin_users";

    $where    = "WHERE
        ($search_by)  AND
        ($w_type)     AND
        (Username != 'admin@windsoftti.com')
      ORDER BY UserId DESC
    ";

    $start_rows = ($page - 1) * $per_page;
    $stop_rows  = $per_page;

    $limit_rows = "LIMIT $start_rows, $stop_rows";

    $query      = "SELECT COUNT(UserId) AS Total $from $where LIMIT 1";
    $num_pages  = numPages($query, $stop_rows);

    if (!$num_pages) {
      $default_message = '¡No hay usuarios registrados!.';

      if ($search != '') {
        $default_icon = 'fas fa-search';
        $default_message = '¡No se encontraron resultados!. "' . $search . '".';
      }

      include '../lib/default_message.php';
    }

    if ($num_pages) {
      $query = "SELECT
          UserId,
          FullName,
          Email,
          Phone,
          UserType,
          Username,
          UserPassword
        $from $where $limit_rows
      ";

      $query_result = mysqli_query($mysqli, $query);

      include 'users_table.php';
    }

    mysqli_close($mysqli);
    die();
    break;

  case 'add_user':
    $user             = cleanStr($_POST['fullName']);
    $email            = cleanStr($_POST['email']);
    $phone            = cleanStr($_POST['phone']);
    $username         = cleanStr($_POST['username']);
    $password         = encrypt($_POST['password'], MYSQLI_PASSWORD_SECRET);
    $confirm_password = encrypt($_POST['confirmPassword'], MYSQLI_PASSWORD_SECRET);
    $user_type  = ($_SESSION['adm_session_user_type'] === 'Root' || $_SESSION['adm_session_user_type'] === 'Administrator') ? cleanStr($_POST['userType']) : 'User';

    $password_confirm = $password == $confirm_password  ? true : false;

    if (!$password_confirm) {
      $response['message'] = 'Las contraseñas no coinciden.';

      echo json_encode($response);
      mysqli_close($mysqli);
      exit();
    }

    $email_structure = filter_var($email, FILTER_VALIDATE_EMAIL);

    if (!$email_structure) $response['message'] = 'El correo ingresado no es invalido.';

    if ($email_structure) {
      $query = "SELECT
          Email,
          Username
        FROM ml_admin_users
        WHERE
          (
            Email     = '$email' OR
            Username  = '$username'
          ) OR
          Username    = '$email'
        LIMIT 1
      ";

      $query_result = mysqli_query($mysqli, $query);
      $num_rows     = mysqli_num_rows($query_result);

      if ($num_rows) {
        $user_data = mysqli_fetch_array($query_result);

        $tb_username  = $user_data['Username'];
        $tb_email     = $user_data['Email'];

        if ($tb_username == $username && $tb_email == $email) {
          $response['message'] = 'El nombre de usuario y el correo, ya estan en uso.';
        } else if ($tb_email == $email) {
          $response['message'] = 'El correo ingresado, ya esta en uso.';
        } else if ($tb_username == $username) {
          $response['message'] = 'El nombre de usuario, ya esta en uso.';
        } else if ($tb_username == $email) {
          $response['message']  = 'Nombre de usuario invalido.';
        }
      }

      if (!$num_rows) {
        $query = "INSERT INTO ml_admin_users (
            FullName,
            Email,
            Phone,
            Username,
            UserPassword,
            UserType
          ) VALUES (
            '$user',
            '$email',
            '$phone',
            '$username',
            '$password',
            '$user_type'
          )
        ";

        $query_result = mysqli_query($mysqli, $query);

        if (!$query_result) $response['message']  = 'Intentalo nuevamente.';

        if ($query_result) $response = array(
          'status'  => 'success',
          'title'   => '¡Datos guarados!',
          'message' => 'El usuario "' . $user . '" se agregó correctamente.'
        );
      }
    }
    break;

  case 'edit_user':
    $adm_session_user_type = $_SESSION['adm_session_user_type'];

    $user_id    = cleanStr($_POST['userId']);

    $user             = cleanStr($_POST['fullName']);
    $email            = cleanStr($_POST['email']);
    $phone            = cleanStr($_POST['phone']);
    $username         = cleanStr($_POST['username']);
    $password         = encrypt($_POST['password'], MYSQLI_PASSWORD_SECRET);
    $confirm_password = encrypt($_POST['confirmPassword'], MYSQLI_PASSWORD_SECRET);
    $user_type  = cleanStr($_POST['userType']);

    $query_update_type = ($_SESSION['adm_session_user_type'] === 'Root' || $_SESSION['adm_session_user_type'] === 'Administrator') ? ", UserType = '$user_type'" : "";

    $password_confirm = $password == $confirm_password  ? true : false;

    if (!$password_confirm) {
      $response['message'] = 'Las contraseñas no coinciden.';

      echo json_encode($response);
      mysqli_close($mysqli);
      exit();
    }

    if ($_SESSION['adm_session_user_type'] != 'Root') {
      $query = "SELECT UserType FROM ml_admin_users WHERE UserId = '$user_id' LIMIT 1";
      $query_result = mysqli_query($mysqli, $query);

      while ($row = mysqli_fetch_array($query_result)) {
        $user_type = $row['UserType'];
      }

      if ($user_type === 'Root') {
        $response['message'] = 'No tienes los permisos necesarios para editar a este usuario.';

        echo json_encode($response);
        mysqli_close($mysqli);
        exit();
      }
    }

    $email_structure = filter_var($email, FILTER_VALIDATE_EMAIL);

    if (!$email_structure) {
      $response['message'] = 'Verifique la estructura de su correo.';

      echo json_encode($response);
      mysqli_close($mysqli);
      exit();
    }

    $query = "SELECT
        Email,
        Username
      FROM ml_admin_users
      WHERE
        (
          (
            Email     = '$email' OR
            Username  = '$username'
          ) OR
          Username    = '$email'
        ) AND
        UserId != '$user_id'
      LIMIT 1
    ";

    $query_result = mysqli_query($mysqli, $query);
    $num_rows     = mysqli_num_rows($query_result);

    if ($num_rows) {
      $user_data = mysqli_fetch_array($query_result);

      $tb_username  = $user_data['Username'];
      $tb_email     = $user_data['Email'];

      if ($tb_username == $username && $tb_email == $email) {
        $response['message'] = 'El nombre de usuario y el correo, ya estan en uso.';
      } else if ($tb_email == $email) {
        $response['message'] = 'El correo ingresado, ya esta en uso.';
      } else if ($tb_username == $username) {
        $response['message'] = 'El nombre de usuario, ya esta en uso.';
      } else if ($tb_username == $email) {
        $response['message']  = 'Nombre de usuario invalido.';
      }
    }

    if (!$num_rows) {
      $query = "UPDATE ml_admin_users SET
          FullName      = '$user',
          Email         = '$email',
          Phone         = '$phone',
          Username      = '$username',
          UserPassword  = '$password'
          $query_update_type
        WHERE UserId = '$user_id'
      ";

      $query_result = mysqli_query($mysqli, $query);

      if (!$query_result) $response['message'] = '¡Error!, Intentelo nuevamente.';

      if ($query_result) $response = array(
        'status'   => 'success',
        'title'   => '¡Datos guardados!',
        'message' => 'El usuario se actualizó correctamente.'
      );
    }
    break;

  case 'send_credentials':
    $user   = $_POST['user'];
    $email  = cleanStr($_POST['email']);

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

        $from     = "no-responder@manteleslargos.com";
        $to       = $email;
        $subject  = "Manteles Largos | Credenciales de Acceso";

        $message   = $user . ' sus credenciales de acceso son: <br><br>';
        $message  .= '<b>Correo:</b> ' . $email . '<br>';
        $message  .= '<b>Usuario:</b> ' . $username . '<br>';
        $message  .= '<b>Contraseña:</b>' . $password . '<br>';

        $headers  = 'MIME-Version: 1.0' . "\r\n";
        $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
        $headers .= 'From: Manteles Largos <no-responder@manteleslargos.com>' . "\r\n";

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

  case 'delete_user':
    $adm_session_user_id  = $_SESSION['adm_session_user_id'];
    $user_id          = cleanStr($_POST['userId']);
    $user             = cleanStr($_POST['user']);

    if ($adm_session_user_id == $user_id) {
      $response['message'] = '¡Error!, Intentalo nuevamente.';

      echo json_encode($response);
      mysqli_close($mysqli);
      exit();
    }

    if ($_SESSION['adm_session_user_type'] != 'Root') {
      $query = "SELECT UserType FROM ml_admin_users WHERE UserId = '$user_id' LIMIT 1";
      $query_result = mysqli_query($mysqli, $query);

      while ($row = mysqli_fetch_array($query_result)) {
        $user_type = $row['UserType'];
      }

      if ($user_type === 'Root') {
        $response['message'] = 'No tienes los permisos necesarios para realizar esta operación.';

        echo json_encode($response);
        mysqli_close($mysqli);
        exit();
      }
    }

    $query = "DELETE FROM ml_admin_users WHERE UserId = '$user_id'";
    $query_result = mysqli_query($mysqli, $query);

    if (!$query_result) $response['message'] = '¡Error!, Intentelo nuevamente.';

    if ($query_result) $response = array(
      'status' => 'success',
      'message' => 'El usuario "' . $user . '" se eliminó correctamente.'
    );
    break;

  default:
    break;
}

echo json_encode($response);
mysqli_close($mysqli);
