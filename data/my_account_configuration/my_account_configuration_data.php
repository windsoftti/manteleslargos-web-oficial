<?php
include '../lib/user-session.php';

$action = $_POST['action'];

$response = array(
  'status'  => 'error',
  'title'   => '¡Error!',
  'message' => 'Error inesperado, Intentalo nuevamente.'
);

switch ($action) {
  case 'update-account':
    try {
      $user_id          = $_SESSION['session_user_id'];
      $name             = cleanStr($_POST['fullname']);
      $email            = cleanStr($_POST['email']);
      $phone            = cleanStr($_POST['phone']);
      $state            = cleanStr($_POST['state']);
      $change_password  = cleanStr($_POST['changePassword']);
      $password         = encrypt($_POST['password'], MYSQLI_PASSWORD_SECRET);
      $confirm_password = encrypt($_POST['confirmPassword'], MYSQLI_PASSWORD_SECRET);

      $user_info        = getFinalUserDataById($user_id);
      $query_pass       = "";

      if ($user_info['AccessType'] !== 'Manteles Largos') $email = $user_info['Correo'];

      if ($user_info) :
        $validate_email = filter_var($email, FILTER_VALIDATE_EMAIL);

        if (!$validate_email) :
          $response['message'] = 'Ingresa un correo valido';

          echo json_encode($response);
          mysqli_close($mysqli);
          exit();
        endif;

        if ($change_password) :
          $is_valid_password = $password === $confirm_password ? true : false;

          if (!$is_valid_password) :
            $response['message'] = 'Las contraseñas no coinciden';

            echo json_encode($response);
            mysqli_close($mysqli);
            exit();
          endif;

          $query_pass == ", Password = '$password'";
        endif;

        $query = "SELECT
            Correo,
            Username
          FROM usuarios
          WHERE
            (
              (
                Correo    = '$email' OR
                Username  = '$username'
              ) OR

              Username    = '$email'
            ) AND
            idUsuario != '$user_id' AND
            Nivel     = 'Usuario Final'
          LIMIT 1
        ";

        $query_result = mysqli_query($mysqli, $query);
        $num_rows     = mysqli_num_rows($query_result);

        if ($num_rows > 0) :
          $user_data = mysqli_fetch_array($query_result);

          $tb_username  = $user_data['Username'];
          $tb_email     = $user_data['Correo'];

          if ($tb_username == $username && $tb_email == $email) {
            $response['message'] =  'El nombre de usuario y el correo, ya estan en uso.';
          } else if ($tb_email == $email) {
            $response['message'] =  'El correo ingresado, ya esta en uso.';
          } else if ($tb_username == $username) {
            $response['message'] = 'El nombre de usuario, ya esta en uso.';
          } else if ($tb_username == $email) {
            $response['message'] =  'Nombre de usuario invalido.';
          }

          echo json_encode($response);
          mysqli_close($mysqli);
          die();
        endif;

        $query = "UPDATE usuarios SET
            Usuario   = '$name',
            Correo    = '$email',
            Telefono  = '$phone',
            idEstado  = '$state'
            $query_pass
          WHERE
            idUsuario = '$user_id' AND
            Nivel     = 'Usuario Final'
        ";

        $query_result = mysqli_query($mysqli, $query);

        if ($query_result) $response = array(
          'status'   => 'success',
          'title'   => '¡Cuenta actualizada!',
          'message' => 'Tus datos se guardaron correctamente.'
        );
      endif;
    } catch (Exception $e) {
      $response['message'] = $e->getMessage();
    }
    break;
}

echo json_encode($response);
mysqli_close($mysqli, $query);
die();
