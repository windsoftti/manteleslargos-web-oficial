<?php
session_start();

include '../../inc/config.inc.php';
include '../../inc/functions.inc.php';

$response = '';

$action = isset($_POST['action']) ? $_POST['action'] : '';

$valid_extensions = array('jpeg', 'jpg', 'png', 'JPEG', 'JPG', 'PNG');

switch ($action) {
  case 'logIn':
    $user_email     = cleanStr($_POST['userEmail']);
    $user_password  = cleanStr($_POST['userPassword']);
    $user_password  = encrypt($user_password, $secret);

    $query = "SELECT 
        idUsuario, 
        Usuario,
        Username,
        Password,
        Nivel,
        Status,
        PerteneceA
      FROM usuarios WHERE
        (
          (Correo   = BINARY '$user_email' AND Password = BINARY '$user_password') OR
          (Username = BINARY '$user_email' AND Password = BINARY '$user_password')
        ) AND
        Status = 'Activo'
    ";

    $query_result = mysqli_query($mysqli, $query);
    $num_rows     = mysqli_num_rows($query_result);

    if (!$num_rows) {
      $response = array(
        'state'   => 'warning',
        'title'   => '¡Usuario invalido!',
        'message' => 'Sus datos de acceso son incorectos.'
      );
    }

    if ($num_rows) {
      while ($row = mysqli_fetch_array($query_result)) {
        $user_id    = $row['idUsuario'];
        $user_level = $row['Nivel'];
        $user_name  = $row['Usuario'];
        $pertenece_a  = $row['PerteneceA'];
      }

      $session_id = ($pertenece_a != NUll && $pertenece_a != '') ? $pertenece_a : $user_id;

      $_SESSION['session_user_id']          = $session_id;
      //$_SESSION['session_user_id']        = $user_id;
      $_SESSION['session_user_level']       = $user_level;
      $_SESSION['session_user_name']        = $user_name;
      $_SESSION['session_user_parent']      = $pertenece_a;
      $_SESSION['session_user_children_id'] = $user_id;

      $response = array('state' => 'success');
    }
    break;

  case 'update_my_profile':
    //$user_id = $_SESSION['session_user_id'];
    $user_id = $_SESSION['session_user_children_id'] ? $_SESSION['session_user_children_id'] : $_SESSION['session_user_id'];

    $full_name      = cleanStr($_POST['userFullName']);
    $user_email     = cleanStr($_POST['userEmail']);
    $user_phone     = cleanStr($_POST['userPhone']);
    $user_country   = 'Mexico';
    $user_state     = cleanStr($_POST['userState']);
    $username       = cleanStr($_POST['username']);
    $change_password = cleanStr($_POST['changePassword']);
    $user_password  = encrypt($_POST['userPassword'], $secret);

    $supplier_logo = $_FILES['Logo'];

    # PICTURES QUERY
    $query_picture        = "";

    $validate_email = filter_var($user_email, FILTER_VALIDATE_EMAIL);

    if (!$validate_email) {
      $response = array(
        'state'   => 'error',
        'title'   => '¡Error!',
        'message' => 'Ingrese un correo valido'
      );

      echo json_encode($response);
      mysqli_close($mysqli);
      exit();
    }

    $query = "SELECT
        Correo,
        Username
      FROM usuarios
      WHERE
        (
          (
            Correo    = '$user_email' OR
            Username  = '$username'
          ) OR

          Username    = '$user_email'
        ) AND
        idUsuario != '$user_id'
      LIMIT 1
    ";

    $query_result = mysqli_query($mysqli, $query);
    $num_rows     = mysqli_num_rows($query_result);

    if ($num_rows) {
      $user_data = mysqli_fetch_array($query_result);

      $tb_username  = $user_data['Username'];
      $tb_email     = $user_data['Correo'];

      if ($tb_username == $username && $tb_email == $user_email) {
        $response = array(
          'state'   => 'error',
          'title'   => '¡Error!',
          'message' => 'El nombre de usuario y el correo, ya estan en uso.'
        );
      } else if ($tb_email == $user_email) {
        $response = array(
          'state'    => 'error',
          'title'    => '¡Error!',
          'message'  => 'El correo ingresado, ya esta en uso.'
        );
      } else if ($tb_username == $username) {
        $response = array(
          'state'    => 'error',
          'title'    => '¡Error!',
          'message'  => 'El nombre de usuario, ya esta en uso.'
        );
      } else if ($tb_username == $user_email) {
        $response = array(
          'state'    => 'error',
          'title'    => '¡Error!',
          'message'  => 'Nombre de usuario invalido.'
        );
      }

      echo json_encode($response);
      mysqli_close($mysqli);
      exit();
    }

    $query_password = '';

    if ($change_password == 'Si') $query_password = ", Password = '$user_password'";

    if ($supplier_logo['name']) :
      $proccess_supplier_logo = processFile(
        $supplier_logo,
        $valid_extensions,
        '../../../src/assets/images/suppliers/',
        'logo'
      );

      if ($proccess_supplier_logo !== 'no-move' && $proccess_supplier_logo !== 'no-valid') :
        $query_p        = "SELECT Logo FROM usuarios WHERE idUsuario = $user_id LIMIT 1";
        $query_p_result = mysqli_query($mysqli, $query_p);
        $image_data     = mysqli_fetch_array($query_p_result);
        $image_name     = $image_data['Logo'];

        deleteFile('../../../src/images/suppliers/' .  $image_name);

        $query_picture         .= ", Logo = '$proccess_supplier_logo'";
      endif;
    endif;

    $query = "UPDATE usuarios SET
        Usuario   = '$full_name',
        Correo    = '$user_email',
        Celular   = '$user_phone',
        Pais      = '$user_country',
        idEstado  = '$user_state',
        Username  = '$username'
        $query_password
        $query_picture
      WHERE idUsuario = '$user_id'
    ";

    $query_result = mysqli_query($mysqli, $query);

    if (!$query_result) $response = array(
      'state'   => 'error',
      'title'   => '¡Error inesperado!',
      'message' => 'Intentalo nuevamente.'
    );

    if ($query_result) $response = array(
      'state'   => 'success',
      'title'   => '¡Cuenta actualizada!',
      'message' => 'Sus datos se guardaron correctamente.'
    );
    break;

  case 'recover_credentials':
    $email = cleanStr($_POST['userEmail']);

    $email_structure = filter_var($email, FILTER_VALIDATE_EMAIL);

    if (!$email_structure) $response['message'] = 'El correo ingresado no es invalido.';

    if ($email_structure) {
      $query = "SELECT
          idUsuario,
          Usuario,
          Correo,
          Username,
          Password
        FROM usuarios WHERE
          Correo = BINARY '$email'
        LIMIT 1
      ";

      $query_result = mysqli_query($mysqli, $query);
      $num_rows     = mysqli_num_rows($query_result);

      if (!$num_rows) $response = array(
        'state'  => 'error',
        'title'   => '¡Correo no válido!',
        'message' => 'El correo ingresado no es valido.'
      );

      if ($num_rows) {
        $user_data = mysqli_fetch_array($query_result);

        $user     = $user_data['Usuario'];
        $username = $user_data['Username'];
        $password = decrypt($user_data['Password'], $secret);

        $from     = "no-responder@manteleslargos.com";
        $to       = $email;
        $subject  = "Manteles Largos | Credenciales de Acceso";

        $message   = $user . ' tus credenciales de acceso son: <br><br>';
        $message  .= '<b>Correo:</b> ' . $email . '<br>';
        $message  .= '<b>Usuario:</b> ' . $username . '<br>';
        $message  .= '<b>Contraseña:</b>' . $password . '<br>';

        $headers  = 'MIME-Version: 1.0' . "\r\n";
        $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
        $headers .= 'From: Manteles Largos <no-responder@manteleslargos.com>' . "\r\n";

        $send = mail($to, $subject, $message, $headers);

        if (!$num_rows) $response = array(
          'state'  => 'error',
          'title'   => '¡Error!',
          'message' => 'No pudo enviarse las credenciales de acceso, intentelo mas tarde.'
        );

        if ($send) $response = array(
          'state'   => 'success',
          'title'   => '¡Datos enviados!',
          'message' => '"' . $user . '" tus credenciales de acceso se enviaron correctamente a tu correo electrónico. <br>Nota: Favor de revisar el apartado de spam en caso de no aparecer en la bandeja principal.'
        );
      }
    }
    break;

  default:
    $response = array(
      'state' => 'error',
      'title' => '¡Error!',
      'message' => '¡Error!, Intentelo nuevamente.'
    );
    break;
}

echo json_encode($response);
mysqli_close($mysqli);
