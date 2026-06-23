<?php
include '../session.php';
include '../../inc/functions.inc.php';
include '../lib/pagination.php';

$action = isset($_POST['action']) && $_POST['action'] !== '' ? $_POST['action'] : '';

switch ($action) {
  case 'list_users':
    $id_user_create = $_SESSION['session_user_id'];

    $page             = isset($_POST['page']) && $_POST['page'] !== '' ? $_POST['page'] : 1;
    $per_page         = 15;

    $user             = cleanStr($_POST['searchByUser']);
    $search_by_user   = $user != '' ? "Usuario LIKE '%$user%'" : "1=1";

    $from   = "FROM usuarios";

    $where  = "WHERE 
        ($search_by_user) AND
        (PerteneceA = $id_user_create) AND
        (idNegocio  = $_SESSION[session_business_id]) AND
        (Username  != 'admin@windsoftti.com')
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
      $default_message = '¡No hay usuarios registrados!.';

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
      include 'my_users_table.php';
      $data_table = base64_encode(ob_get_clean());

      $response['content'] = $data_table;
    }
    break;

  case 'add_user':
    $id_user_create = $_SESSION['session_user_id'];
    $business_id    = $_SESSION['session_business_id'];

    $user           = cleanStr($_POST['user']);
    $email          = cleanStr($_POST['email']);
    $phone          = cleanStr($_POST['phone']);
    $cell_phone     = cleanStr($_POST['cellPhone']);
    $username       = cleanStr($_POST['username']);
    $password       = encrypt($_POST['password'], $secret);
    $level          = 'Usuario';

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
          idNegocio,
          Usuario,
          Correo,
          Telefono,
          Celular,
          Nivel,
          Username,
          Password,
          Status,
          PerteneceA
        ) VALUES (
          '$business_id',
          '$user',
          '$email',
          '$phone',
          '$cell_phone',
          '$level',
          '$username',
          '$password',
          'Activo',
          '$id_user_create'
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
    $id_user_create = $_SESSION['session_user_id'];

    $user_id    = cleanStr($_POST['userId']);
    $user       = cleanStr($_POST['user']);
    $email      = cleanStr($_POST['email']);
    $phone      = cleanStr($_POST['phone']);
    $cell_phone = cleanStr($_POST['cellPhone']);

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
        'message' => 'El correo ya esta en uso.'
      );
    }

    if ($num_rows) {
      $query = "UPDATE usuarios SET
          Usuario                 = '$user',
          Correo                  = '$email',
          Telefono                = '$phone',
          Celular                 = '$cell_phone'
        WHERE
          idUsuario   = '$user_id' AND
          PerteneceA  = $id_user_create
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

    $query = "DELETE FROM usuarios WHERE
      idUsuario   = '$user_id' AND
      PerteneceA  = $session_user_id
    ";

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

  case 'list_permissions':
    $user_id        = cleanStr($_POST['userId']);
    $id_user_create = $_SESSION['session_user_id'];

    $content;

    $query = "SELECT
        idPaginaUsuarioPermiso,
        idPagina,
        idUsuario
      FROM ml_paginas_usuarios_permisos
      WHERE
        idUsuario     = $user_id AND
        idUserCreate  = $id_user_create
    ";

    $query_result = mysqli_query($mysqli, $query);
    $num_rows     = mysqli_num_rows($query_result);

    if (!$num_rows) $content = createPermissionsList();

    if ($num_rows) {
      $array_permissions = array();

      while ($row = mysqli_fetch_array($query_result)) {
        array_push($array_permissions, $row['idPagina']);
      }

      $content = createEditPermissionsList(0, $array_permissions);
    }

    $response = array(
      'content' => base64_encode($content)
    );
    break;

  case 'add_user_permissions':
    $user_id        = cleanStr($_POST['userId']);
    $id_user_create = $_SESSION['session_user_id'];

    $query_delete_permissions = "DELETE FROM ml_paginas_usuarios_permisos WHERE
      idUserCreate  = $id_user_create AND
      idusuario     = $user_id
    ";

    $query_delete_permissions_result = mysqli_query($mysqli, $query_delete_permissions);

    if (!$query_delete_permissions_result) $response = array(
      'state' => 'error',
      'title' => '¡Error!, Intentelo nuevamente.'
    );

    if ($query_delete_permissions_result) {
      $pages = $_POST['idPagina'];

      foreach ($pages as $key => $value) {
        $page_id = $pages[$key];

        $query_add_permissions = "INSERT INTO ml_paginas_usuarios_permisos (
            idUserCreate,
            idPagina,
            idUsuario
          ) VALUES (
            $id_user_create,
            $page_id,
            $user_id
          )
        ";

        mysqli_query($mysqli, $query_add_permissions);
      }

      $response = array(
        'state' => 'success',
        'title' => '¡Los permisos se actualizaron correctamnete!.'
      );
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
