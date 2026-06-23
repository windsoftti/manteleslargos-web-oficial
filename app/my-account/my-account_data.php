<?php
include '../inc/session.php';
date_default_timezone_set('America/Mexico_City');

$action = $json['action'];

$response = array(
  'status'  => 'error',
  'title'   => '¡Error inesperado!',
  'message' => 'Intentalo nuevamente.'
);

switch ($action) {
  case 'get-account':
    try {
      $parameters   = $json['parameters'];
      $user_id      = cleanStr($parameters['userId']);

      $query = "SELECT
          idUsuario,
          idNegocio,
          Usuario,
          Correo,
          Telefono,
          Celular,
          Username,
          Password,
          Pais,
          idEstado,
          idPais,
          Plan
        FROM usuarios
        WHERE idUsuario = $user_id
        LIMIT 1
      ";

      $query_result = mysqli_query($mysqli, $query);

      if ($query_result) {
        $data = mysqli_fetch_array($query_result);

        $state = $data['idEstado'] != '0' && $data['idEstado'] != null ? $data['idEstado'] : '';

        $user_data = array(
          'fullName'        => $data['Usuario'],
          'email'           => $data['Correo'],
          'phone'           => $data['Telefono'],
          'cellPhone'       => $data['Celular'],
          'username'        => $data['Username'],
          'country'         => $data['idPais'],
          'state'           => $state,
          'plan'            => $data['Plan'],
          'password'        => '',
          'confirmPassword' => ''
        );

        $response = array(
          'status'    => 'success',
          'userData'  => $user_data
        );
      }
    } catch (Exception $e) {
      $response['message'] = $e->getMessage();
    }
    break;

  case 'update-account':
    try {
      $parameters       = $json['parameters'];

      $user_id          = cleanStr($parameters['userId']);
      $edit_password    = $parameters['editPassword'];

      $values           = $parameters['values'];

      $full_name        = cleanStr($values['fullName']);
      $email            = cleanStr($values['email']);
      $phone            = cleanStr($values['phone']);
      //$cell_phone       = cleanStr($values['cellPhone']);
      $country          = cleanStr($values['country']);
      $state            = cleanStr($values['state']);
      $username         = cleanStr($values['username']);
      $password         = encrypt($values['password'], $mysqli_secret);

      $query = "SELECT Correo, Username FROM usuarios WHERE
          (
            Correo    = '$email' OR
            Username  = '$username'
          ) AND
          idUsuario   != $user_id AND
          Nivel       = 'Usuario'
      ";

      $query_result   = mysqli_query($mysqli, $query);
      $num_rows       = mysqli_num_rows($query_result);

      if ($num_rows) {
        while ($row = mysqli_fetch_array($query_result)) {
          $response['title'] = '¡Error!';

          if ($row['Correo'] === $email && $row['Username'] === $username) {
            $response['message'] = 'El Nombre de usuario y el correo no estan disponibles.';
          } else if ($row['Correo'] === $email) {
            $response['message'] = 'El correo ya esta en uso.';
          } else if ($row['Username'] === $username) {
            $response['message'] = 'El username ya esta en uso.';
          }
        }
      }

      if (!$num_rows) {
        $query_password = $edit_password ? "
          , Password = '$password'
        " : "";

        $query = "UPDATE usuarios SET
            Usuario   = '$full_name',
            Correo    = '$email',
            Telefono  = '$phone',
            idPais    = '$country',
            idEstado  = '$state',
            Username  = '$username'
            $query_password
          WHERE
            idUsuario   = $user_id
        ";

        $query_result = mysqli_query($mysqli, $query);

        if ($query_result) $response = array(
          'status'  => 'success',
          'title'   => '¡Datos guardados!',
          'message' => 'Tu cuenta se actualizó correctamente'
        );
      }
    } catch (Exception $e) {
      $response['message'] = $e->getMessage();
    }
    break;
}

echo json_encode($response);
mysqli_close($mysqli);
exit();
