<?php
include '../session.php';
include '../../inc/functions.inc.php';
include '../lib/pagination.php';

$action = isset($_POST['action']) && $_POST['action'] !== '' ? $_POST['action'] : '';

switch ($action) {
  case 'list_users':
    $response = array(
      'state'   => 'error',
      'title'   => '¡Error de correo!',
      'message' => 'Verifique la estructura de su correo.'
    );

    $user_level       = $_SESSION['session_user_level'];

    $page             = isset($_POST['page']) && $_POST['page'] !== '' ? $_POST['page'] : 1;
    $per_page         = 15;

    $user             = cleanStr($_POST['searchByUser']);
    $search_by_user   = $user != '' ? "Usuario LIKE '%$user%'" : "1=1";

    $type             = cleanStr($_POST['type']);
    $search_by_type   = "Nivel = ''";

    if ($type == 'administrador') $search_by_type = "Nivel = 'Administrador'";
    if ($type == 'proveedor')     $search_by_type = "Nivel = 'Usuario'";
    if ($type == 'cliente')       $search_by_type = "Nivel = 'Usuario Final'";

    $search_by_user_type = $user_level === 'Super Usuario' ? '1=1' : 'Nivel != "Super Usuario"';

    $from   = "FROM usuarios";
    $where  = "WHERE 
        ($search_by_user) AND
        ($search_by_type) AND
        ($search_by_user_type) AND
        (Username != 'admin@windsoftti.com')
      ORDER BY idUsuario 
      DESC
    ";

    $start_rows = ($page - 1) * $per_page;
    $stop_rows  = $per_page;

    $limit_rows = "LIMIT $start_rows, $stop_rows";

    $query        = "SELECT COUNT(idUsuario) AS Total $from $where LIMIT 1";
    $query_result = mysqli_query($mysqli, $query);

    while ($row = mysqli_fetch_array($query_result)) {
      $num_pages = ceil($row['Total'] / $stop_rows);
    }

    if (!$num_pages) {
      $default_message = '¡No hay usuarios registrados!.' . $type;
      if ($user != '') {
        $default_message = '¡No hay usuarios que coincidan con la busqueda "' . $user . '"!.';
      }

      ob_start();
      include '../default_message.php';
      $data_message = base64_encode(ob_get_clean());

      $response['content'] = $data_message;
    }

    if ($num_pages) {
      $query = "SELECT
          idUsuario,
          Usuario,
          Correo,
          Telefono,
          Celular,
          Puesto,
          Nivel,
          Username,
          Password,
          Status
        $from $where $limit_rows
      ";

      $query_result = mysqli_query($mysqli, $query);

      ob_start();
      include 'users_table.php';
      $data_table = base64_encode(ob_get_clean());

      $response['content'] = $data_table;
    }
    break;

  case 'add_user':
    $id_user_create = $_SESSION['session_user_id'];
    $user           = cleanStr($_POST['user']);
    $email          = cleanStr($_POST['email']);
    $phone          = cleanStr($_POST['phone']);
    $cell_phone     = cleanStr($_POST['cellPhone']);
    $username       = cleanStr($_POST['username']);
    $password       = encrypt($_POST['password'], $secret);
    $level          = ($_SESSION['session_user_level'] === 'Super Usuario' || $_SESSION['session_user_level'] === 'Administrador') ? cleanStr($_POST['level']) : 'Usuario';

    $email_structure = filter_var($email, FILTER_VALIDATE_EMAIL);

    if (!$email_structure) {
      $response = array(
        'state'   => 'error',
        'title'   => '¡Error de correo!',
        'message' => 'Verifique la estructura de su correo.'
      );

      echo json_encode($response);
      mysqli_close($mysqli);
      return;
    }

    $query = "SELECT Correo, Username FROM usuarios WHERE
        Correo    = '$email' OR 
        Username  = '$username'
    ";

    $query_result   = mysqli_query($mysqli, $query);
    $num_rows       = mysqli_num_rows($query_result);

    if ($num_rows) {
      while ($row = mysqli_fetch_array($query_result)) {
        if ($row['Correo'] === $email && $row['Username'] === $username) {
          $response = array(
            'state'   => 'warning',
            'title'   => '¡Credenciales no validas!',
            'message' => 'El username y el correo ya estan en uso.'
          );
        } else if ($row['Correo'] === $email) {
          $response = array(
            'state'   => 'warning',
            'title'   => '¡Correo no valido!',
            'message' => 'El correo ya estan en uso.'
          );
        } else if ($row['Username'] === $username) {
          $response = array(
            'state'   => 'warning',
            'title'   => '¡Username no valido!',
            'message' => 'El username ya estan en uso.'
          );
        }
      }
    }

    if (!$num_rows) {
      $query = "INSERT INTO usuarios (
          Usuario,
          Correo,
          Telefono,
          Celular,
          Nivel,
          Username,
          Password,
          Status
        ) VALUES (
          '$user',
          '$email',
          '$phone',
          '$cell_phone',
          '$level',
          '$username',
          '$password',
          'Activo'
        )
      ";

      $query_result = mysqli_query($mysqli, $query);

      if (!$query_result) {
        $response = array(
          'state' => 'error',
          'title' => '¡Error!',
          'message' => '¡Error!, Intentelo nuevamente.'
        );
      }

      if ($query_result) {
        $response = array(
          'state' => 'success',
          'title' => '¡Datos guardados!',
          'message' => 'El usuario "' . $user . '" se agregó correctamente.'
        );
      }
    }
    break;

  case 'edit_user':
    $session_user_level = $_SESSION['session_user_level'];

    $user_id    = cleanStr($_POST['userId']);
    $user       = cleanStr($_POST['user']);
    $email      = cleanStr($_POST['email']);
    $phone      = cleanStr($_POST['phone']);
    $cell_phone = cleanStr($_POST['cellPhone']);
    $level      = cleanStr($_POST['level']);

    $query_update_level = ($_SESSION['session_user_level'] === 'Super Usuario' || $_SESSION['session_user_level'] === 'Administrador') ? ", Nivel = '$level'" : "";

    if ($_SESSION['session_user_level'] != 'Super Usuario') {
      $query = "SELECT Nivel FROM usuarios WHERE idUsuario = '$user_id' LIMIT 1";
      $query_result = mysqli_query($mysqli, $query);

      while ($row = mysqli_fetch_array($query_result)) {
        $user_level = $row['Nivel'];
      }

      if ($user_level === 'Super Usuario') {
        $response = array(
          'state'   => 'error',
          'title'   => '¡Error!',
          'message' => 'No tienes los permisos necesarios para editar a este usuario.'
        );

        echo json_encode($response);
        mysqli_close($mysqli);
        return;
      }
    }

    $email_structure = filter_var($email, FILTER_VALIDATE_EMAIL);

    if (!$email_structure) {
      $response = array(
        'state'   => 'error',
        'title'   => '¡Error de correo!',
        'message' => 'Verifique la estructura de su correo.'
      );

      echo json_encode($response);
      mysqli_close($mysqli);
      return;
    }

    $query = "SELECT Correo FROM usuarios WHERE Correo = '$email'";

    $query_result   = mysqli_query($mysqli, $query);
    $num_rows       = mysqli_num_rows($query_result);

    if ($num_rows) {
      $response = array(
        'state'   => 'warning',
        'title'   => '¡Correo no valido!',
        'message' => 'El correo ya estan en uso.'
      );
    }

    if ($num_rows) {
      $query = "UPDATE usuarios SET
          Usuario   = '$user',
          Correo    = '$email',
          Telefono  = '$phone',
          Celular   = '$cell_phone'
          $query_update_level
        WHERE idUsuario = '$user_id'
      ";
      $query_result = mysqli_query($mysqli, $query);

      if (!$query_result) {
        $response = array(
          'state'   => 'error',
          'title'   => '¡Error!',
          'message' => '¡Error!, Intentelo nuevamente.'
        );
      }

      if ($query_result) {
        $response = array(
          'state'   => 'success',
          'title'   => '¡Datos guardados!',
          'message' => 'El usuario se actualizó correctamente.'
        );
      }
    }
    break;

  case 'delete_user':
    $session_user_id  = $_SESSION['session_user_id'];
    $user_id          = cleanStr($_POST['userId']);
    $user             = cleanStr($_POST['user']);

    if ($session_user_id == $user_id) {
      $response = array(
        'state' => 'warning',
        'title' => '¡No puedes eliminarte a ti mismo!.'
      );

      echo json_encode($response);
      mysqli_close($mysqli);
      return;
    }

    if ($_SESSION['session_user_level'] != 'Super Usuario') {
      $query = "SELECT Nivel FROM usuarios WHERE idUsuario = '$user_id' LIMIT 1";
      $query_result = mysqli_query($mysqli, $query);

      while ($row = mysqli_fetch_array($query_result)) {
        $user_level = $row['Nivel'];
      }

      if ($user_level === 'Super Usuario') {
        $response = array(
          'state' => 'error',
          'title' => 'No tienes los permisos necesarios para realizar esta operación.'
        );

        echo json_encode($response);
        mysqli_close($mysqli);
        return;
      }
    }

    $query = "DELETE FROM usuarios WHERE idUsuario = '$user_id'";
    $query_result = mysqli_query($mysqli, $query);

    if (!$query_result) {
      $response = array(
        'state' => 'error',
        'title' => '¡Error!, Intentelo nuevamente.'
      );
    }

    if ($query_result) {
      $response = array(
        'state' => 'success',
        'title' => 'El usuario "' . $user . '" se eliminó correctamente.'
      );
    }
    break;

  case 'send_credentials':
    $user   = $_POST['user'];
    $email  = cleanStr($_POST['email']);

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

    if (!$num_rows) {
      $response = array(
        'state' => 'error',
        'title' => '¡Error!, El correo ingresado no es valido.'
      );
    }

    if ($num_rows) {
      while ($row = mysqli_fetch_array($query_result)) {
        $password = decrypt($row['Password'], $secret);
        $username = $row['Username'];
      }

      #SEND EMAIL
      $from     = "no-responder@manteleslargos.com";
      $to       = $email;
      $subject  = "Manteles Largos | Credenciales de Acceso";

      $message  = "$user tus credenciales de acceso son:<br><br>\n";
      $message .= "Nombre: $user<br>\n";
      $message .= "Correo: $email<br>\n";
      $message .= "Username: $username<br>\n";
      $message .= "Contraseña: $password<br>\n";

      $headers = 'MIME-Version: 1.0' . "\r\n";
      $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
      $headers .= 'From: Manteles Largos <no-responder@manteleslargos.com>' . "\r\n";

      $send = mail($to, $subject, $message, $headers);

      if (!$send) {
        $response = array(
          'state' => 'error',
          'title' => 'No pudo enviarse las credenciales de acceso, intentelo mas tarde.'
        );
      }

      if ($send) {
        $response = array(
          'state' => 'success',
          'title' => 'Las credenciales de acceso se enviaron correctamente.'
        );
      }
    }
    break;

  default:
    $response = array(
      'state'   => 'error',
      'title'   => '¡Error!',
      'message' => '¡Error!, Intentelo nuevamente.',
      'content' => '¡Error!, Recargue la página'
    );
    break;
}

echo json_encode($response);
mysqli_close($mysqli);
