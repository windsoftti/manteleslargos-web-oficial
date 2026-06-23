<?php
include '../inc/session.php';
date_default_timezone_set('America/Mexico_City');

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
//use PHPMailer\PHPMailer\Exception;

include '../lib/PHP-Mailer/vendor/autoload.php';

$action = $json['action'];

$response = array(
  'status'  => 'error',
  'title'   => '¡Error inesperado!',
  'message' => 'Intentalo nuevamente.'
);

switch ($action) {
  case 'logIn':
    $parameters = $json['parameters'];

    $username = cleanStr($parameters['username']);
    $password = encrypt($parameters['password'], $mysqli_secret);

    $query = "SELECT
        idUsuario,
        idNegocio,
        VerificationCode,
        VerificationCodeStatus,
        Status,
        Plan,
        PerteneceA
      FROM usuarios
      WHERE
        (
          (
            Correo   = BINARY '$username' AND
            Password = BINARY '$password'
          )
            OR
          (
            Username = BINARY '$username' AND
            Password = BINARY '$password'
          )
        ) AND
        Nivel  = 'Usuario'
        AND
        Status != 'Eliminado'
      LIMIT 1
    ";

    $query_result = mysqli_query($mysqli, $query);
    $num_rows     = mysqli_num_rows($query_result);

    if (!$num_rows) $response = array(
      'status'  => 'warning',
      'title'   => '¡Error de inicio!',
      'message' => 'Sus datos de acceso son incorrectos, intentalo nuevamente'
    );

    if ($num_rows) {
      $status           = 'unlogged';
      $business_id      = null;

      $user_data        = mysqli_fetch_array($query_result);

      $user_id          = $user_data['idUsuario'];
      $collaborator_id  = $user_data['idUsuario'];

      $user_plan        = $user_data['Plan'];
      $user_parent      = $user_data['PerteneceA'];

      $user_permissions = array();

      # VERIFICAR SI ES COLABORADOR
      $is_collaborator  = false;

      if ($user_parent) {
        $is_collaborator = true;
        $user_id         = $user_parent;
      }

      # OBTENER LOS PERMISOS DEL COLABORADOR
      if ($is_collaborator) :
        $user_permissions = getUserPermissions($collaborator_id);
      endif;

      $verification_code_status = $user_data['VerificationCodeStatus'];
      $user_status              = $user_data['Status'];

      if ($user_status === 'Descartado') $status = 'unlogged';

      if ($user_status === 'Inactivo') {
        if ($verification_code_status === 'Nuevo') $status = 'unverified';
        if ($verification_code_status === 'Usado') $status = 'unlogged';
      }

      if ($user_status === 'Activo') {
        if (!$user_plan) $status = 'no-package';

        if ($user_plan) {
          $num_business = getNumBusiness($user_id);

          if (!$num_business || $num_business === 0) $status = 'no-business';

          if ($num_business > 0) {
            $status       = 'logged';
            $business_id  = getFirstBusinessId($user_id);

            if ($is_collaborator) $business_id = $user_data['idNegocio'];
          }
        }
      }

      $response = array(
        'status'          => 'success',
        'userId'          => $user_id,
        'appUserStatus'   => $status,
        'businessId'      => $business_id,
        'package'         => $user_plan,
        'isCollaborator'  => $is_collaborator,
        'collaboratorId'  => $collaborator_id,
        'userPermissions' => $user_permissions
      );
    }
    break;

  case 'register-account':
    try {
      # OBTENER LOS DATOS
      $values = $json['parameters'];

      $full_name    = cleanStr($values['fullName']);
      $email        = cleanStr($values['email']);
      $phone        = cleanStr($values['phone']);
      $cell_phone   = cleanStr($values['cellPhone']);
      $username     = cleanStr($values['username']);
      $country      = cleanStr($values['country']);
      $password     = encrypt($values['password'], $mysqli_secret);

      # VERIFICAR SI LOS DATOS DE ACCESO YA EXISTEN
      $query = "SELECT Correo, Username FROM usuarios WHERE
          (
            Correo    = '$email'    OR
            Username  = '$username' OR
            Username  = '$email'
          ) AND
          Nivel       = 'Usuario'
      ";

      $query_result   = mysqli_query($mysqli, $query);
      $num_rows       = mysqli_num_rows($query_result);

      if ($num_rows) {
        while ($row = mysqli_fetch_array($query_result)) {
          $response['title'] = '¡Aviso!';
          $rq_email     = $row['Correo'];
          $rq_username  = $row['Username'];

          if ($rq_email     == $email)    $response['message'] = 'El correo electrónico ya está en uso';
          if ($rq_username  == $username) $response['message'] = 'El nombre de usuario ya está en uso';

          if (
            $rq_email       == $email &&
            $rq_username    == $username
          ) $response['message'] = 'El nombre de usuario y el correo electrónico ya estan en uso.';

          /* if ($row['Correo'] == $email && $row['Username'] == $username) {
            $response['message'] = 'El Nombre de usuario y el correo no estan disponibles.';
          } else if ($row['Email'] == $email) {
            $response['message'] = 'El correo ya esta en uso.';
          } else if ($row['Username'] == $username) {
            $response['message'] = 'El username ya esta en uso.';
          } else if ($row['Username'] == $email) {
            $response['message'] = 'El correo ya esta en uso.';
          } */
        }
      }

      if (!$num_rows) {
        # CREAR CUENTA DEL PROVEEDOR
        $verification_code  = generateVerificationCode();

        $query = "INSERT INTO usuarios (
            Usuario,
            Correo,
            Telefono,
            Celular,
            Nivel,
            Username,
            Password,
            VerificationCode,
            idPais
          ) VALUES (
            '$full_name',
            '$email',
            '$phone',
            '$cell_phone',
            'Usuario',
            '$username',
            '$password',
            '$verification_code',
            '$country'
          )
        ";

        $query_result = mysqli_query($mysqli, $query);

        if ($query_result) {
          $user_id = mysqli_insert_id($mysqli);

          # ENVIAR EL CORREO AL USUARIO REGISTRADO
          $mail = new PHPMailer(true);

          $mail->SMTPDebug = 0;
          $mail->isSMTP();
          $mail->Host       = PHPMAILER_HOST;
          $mail->SMTPAuth   = true;
          $mail->Username   = PHPMAILER_SUPPORT_EMAIL;
          $mail->Password   = PHPMAILER_SUPPORT_PASSWORD;
          $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
          $mail->Port       = 465;

          $mail->CharSet = 'UTF-8';

          $mail->setFrom(PHPMAILER_SUPPORT_EMAIL, 'Manteles Largos | Código de verificación');
          $mail->addAddress($email, $full_name);

          ob_start();
          include '../email-templates/verification-code.php';
          $message_to_send = ob_get_clean();

          $mail->isHTML(true);
          $mail->Subject = '¡Activa tu cuenta de Manteles Largos!';
          $mail->Body    = $message_to_send;

          $mail->send();

          $response = array(
            'status' => 'success',
            'userId' => $user_id
          );
        }
      }
    } catch (Exception $e) {
      $response['message'] = $e->getMessage();
    }
    break;

  case 'verify-app-user-status':
    $user_id = $json['parameters'];

    $query = "SELECT
        idUsuario,
        idNegocio,
        VerificationCode,
        VerificationCodeStatus,
        Status,
        Plan,
        PerteneceA
      FROM usuarios
      WHERE idUsuario = $user_id
    ";

    $query_result = mysqli_query($mysqli, $query);
    $num_rows     = mysqli_num_rows($query_result);

    if ($num_rows) {
      $status       = 'unlogged';
      $business_id  = null;
      $user_data    = mysqli_fetch_array($query_result);

      $verification_code_status = $user_data['VerificationCodeStatus'];
      $user_status              = $user_data['Status'];
      $collaborator_id  = $user_data['idUsuario'];

      $user_plan        = $user_data['Plan'];
      $user_parent      = $user_data['PerteneceA'];

      # VERIFICAR SI ES COLABORADOR
      $is_collaborator  = false;

      if ($user_parent) {
        $is_collaborator = true;
        $user_id         = $user_parent;
      }

      # OBTENER LOS PERMISOS DEL COLABORADOR
      if ($is_collaborator) :
        $user_permissions = getUserPermissions($collaborator_id);
      endif;

      if ($user_status === 'Descartado') $status = 'unlogged';

      if ($user_status === 'Inactivo') {
        if ($verification_code_status === 'Nuevo') $status = 'unverified';
        if ($verification_code_status === 'Usado') $status = 'unlogged';
      }

      if ($user_status === 'Activo') {
        if (!$user_plan) $status = 'no-package';

        if ($user_plan) {
          $num_business = getNumBusiness($user_id);

          if (!$num_business || $num_business === 0) $status = 'no-business';

          if ($num_business > 0) {
            $status       = 'logged';
            $business_id  = getFirstBusinessId($user_id);

            if ($is_collaborator) $business_id = $user_data['idNegocio'];
          }
        }
      }

      $response = array(
        'status'          => 'success',
        'appUserStatus'   => $status,
        'businessId'      => $business_id,
        'package'         => $user_plan,
        'isCollaborator'  => $is_collaborator,
        'collaboratorId'  => $collaborator_id,
        'userPermissions' => $user_permissions
      );
    }
    break;

  case 'confirmate-verification-code':
    try {
      $parameters = $json['parameters'];

      $user_id  = cleanStr($parameters['userId']);
      $key_code = $parameters['keyCode'];

      $code_1 = cleanStr($key_code['code1']);
      $code_2 = cleanStr($key_code['code2']);
      $code_3 = cleanStr($key_code['code3']);
      $code_4 = cleanStr($key_code['code4']);

      $verification_code = 'ML-' . $code_1 . $code_2 . $code_3 . $code_4;

      $query = "SELECT
          idUsuario
        FROM usuarios
        WHERE
          idUsuario               = $user_id              AND
          VerificationCode        = '$verification_code'  AND
          VerificationCodeStatus  = 'Nuevo'
      ";

      $query_result = mysqli_query($mysqli, $query);
      $num_rows     = mysqli_num_rows($query_result);

      if (!$num_rows) $response['message'] = '¡El código ingresado no es valido!';

      if ($num_rows) {
        $query = "UPDATE usuarios SET
            Plan                    = 'Básico',
            VerificationCodeStatus  = 'Usado',
            Status                  = 'Activo'
          WHERE
            idUsuario         = $user_id AND
            VerificationCode  = '$verification_code'
        ";

        $query_result = mysqli_query($mysqli, $query);

        if (!$query_result) $response['message'] = '¡Error inesperado!, Intentalo nuevamente.';

        if ($query_result) $response = array(
          'status' => 'success'
        );
      }
    } catch (Exception $e) {
      $response['message'] = $e->getMessage();
    }
    break;

  case 'recover-credentiales':
    try {
      $email = cleanStr($json['parameters']);

      $email_structure = filter_var($email, FILTER_VALIDATE_EMAIL);

      if (!$email_structure) $response['message'] = 'El correo ingresado no es invalido.';

      if ($email_structure) {
        $query = "SELECT
            idUsuario,
            Usuario,
            Correo,
            Username,
            Password,
            AccessType,
            Nivel
          FROM usuarios
          WHERE
            Correo  = BINARY '$email' AND
            Status  = 'Activo'        AND
            Nivel   = 'Usuario'
          LIMIT 1
        ";

        $query_result = mysqli_query($mysqli, $query);
        $num_rows     = mysqli_num_rows($query_result);

        if (!$num_rows) $response['message'] = 'El correo que has ingresado no está registrado.';

        if ($num_rows) {
          $user_data = mysqli_fetch_array($query_result);

          $etrc_name      = $user_data['Usuario'];
          $etrc_username  = $user_data['Username'];
          $etrc_password  = decrypt($user_data['Password'], MYSQLI_PASSWORD_SECRET);
          $etrc_email     = $email;
          $etrc_account_type = $user_data['AccessType'];
          $etrc_user_level = $user_data['Nivel'];

          $mail = new PHPMailer(true);

          $mail->SMTPDebug = 0;
          $mail->isSMTP();
          $mail->Host       = PHPMAILER_HOST;
          $mail->SMTPAuth   = true;
          $mail->Username   = PHPMAILER_SUPPORT_EMAIL;
          $mail->Password   = PHPMAILER_SUPPORT_PASSWORD;
          $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
          $mail->Port       = 465;

          $mail->CharSet = 'UTF-8';

          $mail->setFrom(PHPMAILER_CONTACT_EMAIL, 'Manteles Largos | Credenciales de Acceso');
          $mail->addAddress($etrc_email, $etrc_name);

          $subject = $etrc_account_type === 'Manteles Largos' ? 'Manteles Largos | Credenciales de Acceso' : 'Tu solicitud de restablecimiento de contraseña';

          ob_start();
          include '../email-templates/recover-password.php';
          $message_to_send = ob_get_clean();

          $mail->isHTML(true);
          $mail->Subject = $subject;
          $mail->Body    = $message_to_send;

          $send = $mail->send();

          if (!$send) $response['message'] = 'No pudo enviarse las credenciales de acceso, intentelo mas tarde.';

          if ($send) $response = array(
            'status'   => 'success',
            'title'   => '¡Datos enviados!',
            'message' => '"' . $etrc_name . '" tus credenciales de acceso se enviaron correctamente a tu correo electrónico, no olvides revisar tu bandeja de spam o promociones.'
          );
        }
      }
    } catch (Exception $e) {
      $response['message'] = $e->getMessage();
    }
    break;

  case 'save-token':
    $parameters = $json['parameters'];

    $user_id    = $parameters['userId'];
    $user_token = $parameters['userToken'];

    $find_token = findToken($user_id, $user_token);

    if ($find_token) $response = array(
      'status' => 'success'
    );

    if (!$find_token) :
      $query = "INSERT INTO app_tokens (
          idUsuario,
          Token
        ) VALUES (
          $user_id,
          '$user_token'
        )
      ";

      $query_result = mysqli_query($mysqli, $query);

      if ($query_result) $response = array(
        'status' => 'success'
      );
    endif;
    break;

  case 'deactivate-notifications':
    $parameters = $json['parameters'];

    $user_id    = $parameters['userId'];
    $user_token = $parameters['userToken'];

    $find_token = findToken($user_id, $user_token);

    if (!$find_token) $response = array(
      'status' => 'success'
    );

    if ($find_token) :
      $query = "DELETE FROM app_tokens WHERE
          idUsuario = $user_id AND
          Token     = '$user_token'
      ";

      $query_result = mysqli_query($mysqli, $query);

      if ($query_result) $response = array(
        'status' => 'success'
      );
    endif;
    break;

  case 'remove-account':
    $parameters = $json['parameters'];

    $user_id    = $parameters['userId'];
    $user_token = $parameters['userToken'];

    $find_token = findToken($user_id, $user_token);

    if ($find_token) :
      $query = "DELETE FROM app_tokens WHERE
        idUsuario = $user_id AND
        Token     = '$user_token'
      ";

      $query_result = mysqli_query($mysqli, $query);
    endif;

    $query = "UPDATE usuarios SET
        Status = 'Eliminado'
      WHERE
        idUsuario = ?
    ";

    $stmt = $mysqli->prepare($query);
    $stmt->bind_param('i', $user_id);

    $query_result = $stmt->execute();

    if ($query_result) $response = array(
      'status' => 'success'
    );
    break;
}

echo json_encode($response);
mysqli_close($mysqli);
exit();
