<?php
date_default_timezone_set('America/Mexico_City');
include '../lib/public-session.php';
require_once '../lib/security/turnstile.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;

include '../lib/php-mailer/vendor/autoload.php';

$response = array(
  'status'  => 'error',
  'title'   => '¡Error!',
  'message' => 'Error inesperado, Intentalo nuevamente.',
  'content' => 'Error inesperado, Intentalo nuevamente.'
);

$action = $_POST['action'];

switch ($action) {
  case 'logIn':

    $turnstile_token = $_POST['cf-turnstile-response'] ?? '';

    if (!validateTurnstile($turnstile_token)) {

        $response['message'] = 'La validación de seguridad falló, verifica que la casilla esté marcada o inténtalo más tarde.';

        echo json_encode($response);
        mysqli_close($mysqli);
        exit();
    }

    $username = cleanStr($_POST['username']);
    $password = encrypt($_POST['password'], MYSQLI_PASSWORD_SECRET);

    $query = "SELECT
        idUsuario,
        Usuario,
        Username,
        Password,
        Telefono,
        Correo,
        Nivel,
        Status
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
        )
          AND 
        Status = 'Activo' AND
        Nivel  = 'Usuario Final'
      LIMIT 1
    ";

    $query_result = mysqli_query($mysqli, $query);
    $num_rows     = mysqli_num_rows($query_result);

    if (!$num_rows) :
      addAuthenticationLogHistory(
        $username,
        'Error: Usuario y/o Passwd incorrecto'
      );

      $response['message'] = '¡Tus datos de acceso son icorrectos!';
    endif;

    if ($num_rows) :
      unset($_COOKIE['MLSESSCOOID']);
      setcookie('MLSESSCOOID', null, -1, '/');

      $user_data = mysqli_fetch_array($query_result);

      $user_id      = $user_data['idUsuario'];
      $user_level   = $user_data['Nivel'];
      $user_name    = $user_data['Usuario'];
      $user_email   = $user_data['Correo'];
      $user_phone   = $user_data['Telefono'];
      $user_status  = $user_data['Status'];

      $_SESSION['session_user_id']      = $user_id;
      $_SESSION['session_user_name']    = $user_name;
      $_SESSION['session_user_level']   = $user_level;
      $_SESSION['session_user_email']   = $user_email;
      $_SESSION['session_user_status']  = $user_status;

      addAuthenticationLogHistory(
        $username,
        'Success: Sesión iniciada'
      );

      $response = array(
        'status'    => 'success',
        'fullName'  => $user_name,
        'email'     => $user_email,
        'phone'     => $user_phone
      );
    endif;
    break;

  case 'signUp':
    $turnstile_token = $_POST['cf-turnstile-response'] ?? '';

    if (!validateTurnstile($turnstile_token)) {

        $response['message'] = 'La validación de seguridad falló, verifica que la casilla esté marcada o inténtalo más tarde.';

        echo json_encode($response);
        mysqli_close($mysqli);
        exit();
    }
    try {
      $username = cleanStr($_POST['username']);
      $password = encrypt($_POST['password'], MYSQLI_PASSWORD_SECRET);

      $full_name      = cleanStr($_POST['fullName']);
      $user_email     = cleanStr($_POST['email']);
      $user_phone     = cleanStr($_POST['cellPhone']);
      $user_country   = 'Mexico';
      $user_state     = cleanStr($_POST['state']);
      $username       = cleanStr($_POST['username']);
      $user_password  = encrypt($_POST['password'], MYSQLI_PASSWORD_SECRET);

      $confirm_password = encrypt($_POST['confirmatePassword'], MYSQLI_PASSWORD_SECRET);
      $check_password = $user_password === $confirm_password ? true : false;

      if (!$check_password) {
        $response['message'] = '¡Verifique su contraseña!, las contraseñas no coinciden.';

        echo json_encode($response);
        mysqli_close($mysqli);
        exit();
      }

      $validate_email = filter_var($user_email, FILTER_VALIDATE_EMAIL);

      if (!$validate_email) {
        $response['message'] = '¡Ingresa un correo válido!.';

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
            Correo    = '$user_email' OR
            Username  = '$username'   OR
            Username  = '$user_email'
          ) AND Nivel = 'Usuario Final'
        LIMIT 1
      ";

      $query_result = mysqli_query($mysqli, $query);
      $num_rows     = mysqli_num_rows($query_result);

      if ($num_rows) :
        $user_data = mysqli_fetch_array($query_result);

        $tb_username  = $user_data['Username'];
        $tb_email     = $user_data['Correo'];

        if ($tb_username == $username && $tb_email == $user_email) {
          $response['message'] = 'El nombre de usuario y el correo, ya estan en uso.';
        } else if ($tb_email == $user_email) {
          $response['message'] = 'El correo ingresado, ya esta en uso.';
        } else if ($tb_username == $username) {
          $response['message'] = 'El nombre de usuario, ya esta en uso.';
        } else if ($tb_username == $user_email) {
          $response['message'] = 'Nombre de usuario invalido.';
        }

        echo json_encode($response);
        mysqli_close($mysqli);
        exit();
      endif;

      $today_date   = date('dmYHis');
      $random_id    = str_shuffle("abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789" . uniqid());
      $access_token = md5($today_date . '-' . $random_id);
      $button_url   = BASE_URL . '/confirmar-cuenta/' . $access_token;

      $query = "INSERT INTO usuarios (
          Usuario,
          Correo,
          Telefono,
          Pais,
          idEstado,
          Username,
          Password,
          AccessToken,
          Status,
          Nivel
        ) VALUES (
          '$full_name',
          '$user_email',
          '$user_phone',
          '$user_country',
          '$user_state',
          '$username',
          '$user_password',
          '$access_token',
          'Inactivo',
          'Usuario Final'
        )
      ";

      $query_result = mysqli_query($mysqli, $query);

      if ($query_result) :
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

        $mail->setFrom(PHPMAILER_CONTACT_EMAIL, 'Manteles Largos | Activar cuenta');
        $mail->addAddress($user_email, $full_name);

        ob_start();
        include '../lib/email-templates/user-account-verification.php';
        $message_to_send = ob_get_clean();

        // GUARDAR HTML
        //file_put_contents('debug-email.html', $message_to_send);
        // MOSTRAR HTML
        //echo $message_to_send;
        //exit;

        $mail->isHTML(true);
        $mail->Subject = '¡Activa tu cuenta de Manteles Largos!';
        $mail->Body    = $message_to_send;

        $mail->send();

        $response = array(
          'status'    => 'success',
          'message'   => '¡Cuenta creada!, Hemos enviado un email de verificación a tu correo electrónico para activar tu cuenta de Manteles Largos.',
          'fullName'  => $full_name,
          'email'     => $user_email,
          'phone'     => $user_phone
        );
      endif;
    } catch (Exception $e) {
      $response['message'] = $e->getMessage();
    }
    break;

  case 'quote-signUp':
    try {
      $username = cleanStr($_POST['username']);
      $password = encrypt($_POST['password'], MYSQLI_PASSWORD_SECRET);

      $full_name      = cleanStr($_POST['fullName']);
      $user_email     = cleanStr($_POST['email']);
      $user_phone     = cleanStr($_POST['cellPhone']);
      $user_country   = 'Mexico';
      $user_state     = cleanStr($_POST['state']);
      $username       = cleanStr($_POST['username']);
      $user_password  = encrypt($_POST['password'], MYSQLI_PASSWORD_SECRET);

      $confirm_password = encrypt($_POST['confirmatePassword'], MYSQLI_PASSWORD_SECRET);
      $check_password = $user_password === $confirm_password ? true : false;

      if (!$check_password) {
        $response['message'] = '¡Verifique su contraseña!, las contraseñas no coinciden.';

        echo json_encode($response);
        mysqli_close($mysqli);
        exit();
      }

      $validate_email = filter_var($user_email, FILTER_VALIDATE_EMAIL);

      if (!$validate_email) {
        $response['message'] = '¡Ingresa un correo válido!.';

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
            Correo    = '$user_email' OR
            Username  = '$username'   OR
            Username  = '$user_email'
          ) AND Nivel = 'Usuario Final'
        LIMIT 1
      ";

      $query_result = mysqli_query($mysqli, $query);
      $num_rows     = mysqli_num_rows($query_result);

      if ($num_rows) :
        $user_data = mysqli_fetch_array($query_result);

        $tb_username  = $user_data['Username'];
        $tb_email     = $user_data['Correo'];

        if ($tb_username == $username && $tb_email == $user_email) {
          $response['message'] = 'El nombre de usuario y el correo, ya estan en uso.';
        } else if ($tb_email == $user_email) {
          $response['message'] = 'El correo ingresado, ya esta en uso.';
        } else if ($tb_username == $username) {
          $response['message'] = 'El nombre de usuario, ya esta en uso.';
        } else if ($tb_username == $user_email) {
          $response['message'] = 'Nombre de usuario invalido.';
        }

        echo json_encode($response);
        mysqli_close($mysqli);
        exit();
      endif;

      $today_date   = date('dmYHis');
      $random_id    = str_shuffle("abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789" . uniqid());
      $access_token = md5($today_date . '-' . $random_id);
      $button_url   = BASE_URL . '/confirmar-cuenta/' . $access_token;

      $query = "INSERT INTO usuarios (
          Usuario,
          Correo,
          Telefono,
          Pais,
          idEstado,
          Username,
          Password,
          AccessToken,
          Status,
          Nivel
        ) VALUES (
          '$full_name',
          '$user_email',
          '$user_phone',
          '$user_country',
          '$user_state',
          '$username',
          '$user_password',
          '$access_token',
          'Pendiente',
          'Usuario Final'
        )
      ";

      $query_result = mysqli_query($mysqli, $query);

      if ($query_result) :
        $user_id = mysqli_insert_id($mysqli);

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

        $mail->setFrom(PHPMAILER_CONTACT_EMAIL, 'Manteles Largos | Activar cuenta');
        $mail->addAddress($user_email, $full_name);

        ob_start();
        include '../lib/email-templates/user-account-verification.php';
        $message_to_send = ob_get_clean();

        $mail->isHTML(true);
        $mail->Subject = '¡Activa tu cuenta de Manteles Largos!';
        $mail->Body    = $message_to_send;

        $mail->send();

        $response = array(
          'status'    => 'success',
          'title'     => '¡Cuenta creada!',
          'message'   => 'Ahora puedes solicitar una cotización, Hemos enviado un email de verificación a tu correo electrónico para activar tu cuenta de Manteles Largos, Actívala lo antes posible.',
          'fullName'  => $full_name,
          'email'     => $user_email,
          'phone'     => $user_phone
        );

        $_SESSION['session_user_id']      = $user_id;
        $_SESSION['session_user_name']    = $full_name;
        $_SESSION['session_user_level']   = 'Usuario Final';
        $_SESSION['session_user_email']   = $user_email;
        $_SESSION['session_user_status']  = 'Pendiente';
      endif;
    } catch (Exception $e) {
      $response['message'] = $e->getMessage();
    }
    break;

  case 'recover_password':
    try {
      $email            = cleanStr($_POST['email']);
      $email_structure  = filter_var($email, FILTER_VALIDATE_EMAIL);

      if (!$email_structure) $response['message'] = 'El correo ingresado no es valido.';

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
            Nivel   = 'Usuario Final'
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

          $template_to_include  = $etrc_account_type === 'Manteles Largos' ? '../lib/email-templates/recover-credentials.php' : '../lib/email-templates/social-recover-credentials.php';
          $subject              = $etrc_account_type === 'Manteles Largos' ? 'Manteles Largos | Credenciales de Acceso' : 'Tu solicitud de restablecimiento de contraseña';

          ob_start();
          include $template_to_include;
          $message_to_send = ob_get_clean();

          $mail->isHTML(true);
          $mail->Subject = $subject;
          $mail->Body    = $message_to_send;

          $send = $mail->send();

          if (!$send) $response['message'] = 'No pudo enviarse las credenciales de acceso, intentelo mas tarde.';

          if ($send) $response = array(
            'status'   => 'success',
            'title'   => '¡Datos enviados!',
            'message' => '"' . $etrc_name . '" tus credenciales de acceso se enviaron correctamente a tu correo electrónico, no olvides revisar tu bandeja de spam o promociones, Si no recibiste dicho link, intenta acceder con el inicio de sesión por redes sociales.'
          );
        }
      }
    } catch (Exception $e) {
      $response['message'] = $e->getMessage();
    }
    break;
}

echo json_encode($response);
mysqli_close($mysqli);
die();
