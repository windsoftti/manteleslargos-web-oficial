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

$valid_extensions = array('jpeg', 'jpg', 'png', 'JPEG', 'JPG', 'PNG');

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
        idNegocio,
        PerteneceA,
        Usuario,
        Username,
        Password,
        Correo,
        Nivel,
        Status,
        Plan
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
        Nivel  = 'Usuario'
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
      $id_negocio   = $user_data['idNegocio'];
      $plan         = $user_data['Plan'];
      $user_level   = $user_data['Nivel'];
      $user_name    = $user_data['Usuario'];
      $user_email   = $user_data['Correo'];
      $user_status  = $user_data['Status'];
      $pertenece_a  = $user_data['PerteneceA'];

      //$session_id = ($pertenece_a != NUll && $pertenece_a != '') ? $pertenece_a : $user_id;

      /* $_SESSION['session_user_id']      = $session_id;
      $_SESSION['session_user_name']    = $user_name;
      $_SESSION['session_user_level']   = $user_level;
      $_SESSION['session_user_email']   = $user_email;
      $_SESSION['session_user_status']  = $user_status; */

      /* $_SESSION['session_user_id']          = $session_id;
      //$_SESSION['session_user_id']        = $user_id;
      $_SESSION['session_user_level']       = $user_level;
      $_SESSION['session_user_name']        = $user_name;
      $_SESSION['session_user_parent']      = $pertenece_a;
      $_SESSION['session_user_children_id'] = $user_id;
      $_SESSION['session_user_email']       = $user_email;
      $_SESSION['session_user_status']      = $user_status;

      session_user_is_admin_supplier */

      $session_id = ($pertenece_a != NUll && $pertenece_a != '') ? $pertenece_a : $user_id;

      $is_admin_supplier;
      $is_admin_supplier = !$pertenece_a ? 'Si' : 'No';

      $_SESSION['session_user_id']                = $session_id;
      $_SESSION['session_user_name']              = $user_name;
      $_SESSION['session_user_level']             = $user_level;
      $_SESSION['session_user_is_admin_supplier'] = $is_admin_supplier;
      $_SESSION['Plan']                           = $plan;
      $_SESSION['session_user_plan'] = $plan;

      if ($pertenece_a) $_SESSION['session_user_parent']      = $pertenece_a;
      if ($pertenece_a) $_SESSION['session_user_children_id'] = $user_id;
      if ($pertenece_a) $_SESSION['session_business_id']       = $id_negocio;

      if (!$id_negocio) {
        $query = "SELECT idSalon from salones WHERE idUsuario = $session_id AND Status = 'Activo' LIMIT 1";
        $query_result = mysqli_query($mysqli, $query);
        $data_negocio = mysqli_fetch_array($query_result);

        $id_negocio = $data_negocio['idSalon'];
        $_SESSION['session_business_id'] = $id_negocio;
      }

      addAuthenticationLogHistory(
        $username,
        'Success: Sesión iniciada como proveedor'
      );

      $response = array('status' => 'success');
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

    $cell_phone = '';// NO ESTABA DECLARADA LA VARIABLE EN NINGUNA PARTE DEL CÓDIGO

    try {
      # OBTENER LOS DATOS
      $values = $_POST;

      $full_name      = cleanStr($values['fullName']);
      $email          = cleanStr($values['email']);
      $phone          = cleanStr($values['phone']);
      $username       = cleanStr($values['username']);
      $country        = cleanStr($values['country'] ?? '');
      $password       = encrypt($values['password'], MYSQLI_PASSWORD_SECRET);

      $supplier_logo  = $_FILES['supplierLogo'] ?? null;

      $confirm_password = encrypt($_POST['confirmatePassword'], MYSQLI_PASSWORD_SECRET);
      $check_password   = $password === $confirm_password ? true : false;

      # PICTURES QUERY
      $query_picture        = "";
      $query_insert_picture = "";

      if (!$check_password) {
        $response['message'] = '¡Verifique su contraseña!, las contraseñas no coinciden.';

        echo json_encode($response);
        mysqli_close($mysqli);
        exit();
      }

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
          $response['title'] = '¡Error!';

          if ($row['Correo'] === $email && $row['Username'] === $username) {
            $response['message'] = 'El Nombre de usuario y el correo no estan disponibles.';
          } else if ($row['Correo'] === $email) {
            $response['message'] = 'El correo ya esta en uso.';
          } else if ($row['Username'] === $username) {
            $response['message'] = 'El username ya esta en uso.';
          } else if ($row['Username'] === $email) {
            $response['message'] = 'El correo ya esta en uso.';
          }
        }
      }

      if (!$num_rows) {
        # CREAR CUENTA DEL PROVEEDOR
        $verification_code  = generateVerificationCode();

        #SUBIR LOGO DEL USUARIO
        if ($supplier_logo['name']) :
          $proccess_supplier_logo = processFile(
            $supplier_logo,
            $valid_extensions,
            SUPPLIERS_IMAGE_FOLDER,
            'logo'
          );

          if ($proccess_supplier_logo !== 'no-move' && $proccess_supplier_logo !== 'no-valid') :
            $query_picture         .= ", Logo";
            $query_insert_picture  .= ", '$proccess_supplier_logo'";
          endif;
        endif;
        //El valor de VerificationCodeStatus anteriormente era Usado
        $query = "INSERT INTO usuarios (
            Usuario,
            Correo,
            Telefono,
            Celular,
            Nivel,
            Username,
            Password,
            VerificationCode,
            VerificationCodeStatus,
            Status,
            Plan
            $query_picture
          ) VALUES (
            '$full_name',
            '$email',
            '$phone',
            '$cell_phone',
            'Usuario',
            '$username',
            '$password',
            '$verification_code',
            'Nuevo',
            'Inactivo',
            'Básico'
            $query_insert_picture
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
          include '../lib/email-templates/verification-code.php';
          $message_to_send = ob_get_clean();

          $mail->isHTML(true);
          $mail->Subject = '¡Activa tu cuenta de Manteles Largos!';
          $mail->Body    = $message_to_send;

          $mail->send();

          $_SESSION['verification_code_sent_at'] = time();

          $_SESSION['session_user_id'] = $user_id;
          $_SESSION['session_user_level'] = 'Usuario';

          # :::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::

          /*

          $query = "SELECT
              idUsuario,
              idNegocio,
              PerteneceA,
              Usuario,
              Username,
              Password,
              Correo,
              Nivel,
              Status,
              Plan
            FROM usuarios
            WHERE idUsuario = $user_id
            LIMIT 1
          ";

          $query_result = mysqli_query($mysqli, $query);
          $num_rows     = mysqli_num_rows($query_result);

          if (!$num_rows) $response['message'] = '¡El código ingresado no es valido!';

          if ($num_rows) {
            $user_data  = mysqli_fetch_array($query_result);
            $user_id      = $user_data['idUsuario'];
            $id_negocio   = $user_data['idNegocio'];
            $plan         = $user_data['Plan'];
            $user_level   = $user_data['Nivel'];
            $user_name    = $user_data['Usuario'];
            $user_email   = $user_data['Correo'];
            $user_status  = $user_data['Status'];
            $pertenece_a  = $user_data['PerteneceA'];//$row['PerteneceA'];

            $is_admin_supplier = 'Si';

            $_SESSION['session_user_id']                = $user_id;
            $_SESSION['session_user_name']              = $user_name;
            $_SESSION['session_user_level']             = $user_level;
            $_SESSION['session_user_is_admin_supplier'] = $is_admin_supplier;
            $_SESSION['Plan']                           = $plan;

            if ($pertenece_a) $_SESSION['session_user_parent']      = $pertenece_a;
            if ($pertenece_a) $_SESSION['session_user_children_id'] = $user_id;
            if ($id_negocio)  $_SESSION['session_business_id']      = $id_negocio;
          }*/

          #::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::

          $response = array(
            'status'  => 'success',
            'title'   => '¡Cuenta creada!',
            'message' => 'Hemos enviado un email de verificación a tu correo electrónico para activar tu cuenta de Manteles Largos.'
          );

          /*$response = array(
            'status'  => 'success',
            'title'   => '¡Cuenta creada!',
            'message' => 'Tu registro ha sido exitoso, da click en continuar para registrar su primer negocio.'
          );*/

        }
      }
    } catch (Exception $e) {
      $response['message'] = $e->getMessage();
    }
    break;

  case 'verify_account':
    try {
      $code               = cleanStr($_POST['code']);
      $user_id            = $_SESSION['session_user_id'];
      $verification_code  = 'ML-' . $code;

      $query = "SELECT
          idUsuario,
          idNegocio,
          PerteneceA,
          Usuario,
          Username,
          Password,
          Correo,
          Nivel,
          Status,
          VerificationCodeStatus,
          Plan
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
        $user_data  = mysqli_fetch_array($query_result);
        $user_id      = $user_data['idUsuario'];
        $id_negocio   = $user_data['idNegocio'];
        $plan         = $user_data['Plan'];
        $user_level   = $user_data['Nivel'];
        $user_name    = $user_data['Usuario'];
        $user_email   = $user_data['Correo'];
        $user_status  = $user_data['Status'];
        $pertenece_a  = $user_data['PerteneceA']; //$row['PerteneceA'];
        $verification_code_status = $user_data['VerificationCodeStatus'];
        //se crea sesión temporal para mostrar modal verificación/cuenta exitosa
        if($user_level == 'Usuario' && $user_status == 'Inactivo' && $verification_code_status == 'Nuevo'){
          $_SESSION['account_verified_success'] = true;
        }

        $query = "UPDATE usuarios SET
            VerificationCodeStatus  = 'Usado',
            Status                  = 'Activo'
          WHERE
            idUsuario         = $user_id AND
            VerificationCode  = '$verification_code'
        ";

        $query_result = mysqli_query($mysqli, $query);

        if (!$query_result) $response['message'] = '¡Error inesperado!, Intentalo nuevamente.';

        if ($query_result) {
          $is_admin_supplier = 'Si';

          $_SESSION['session_user_id']                = $user_id;
          $_SESSION['session_user_name']              = $user_name;
          $_SESSION['session_user_level']             = $user_level;
          $_SESSION['session_user_is_admin_supplier'] = $is_admin_supplier;
          $_SESSION['Plan']                           = $plan;
          $_SESSION['session_user_plan'] = $plan;

          if ($pertenece_a) $_SESSION['session_user_parent']      = $pertenece_a;
          if ($pertenece_a) $_SESSION['session_user_children_id'] = $user_id;
          if ($id_negocio)  $_SESSION['session_business_id']      = $id_negocio;

          $response = array(
            'status' => 'success'
          );
        }
      }
    } catch (Exception $e) {
      $response['message'] = $e->getMessage();
    }
    break;

    case 'resend_verification_code':

      try {

          $user_id = $_SESSION['session_user_id'];

          if (!$user_id) {
              throw new Exception('Sesión inválida.');
          }

          if (
              isset($_SESSION['verification_code_sent_at']) &&
              (time() - $_SESSION['verification_code_sent_at']) < 60
          ) {

              $remaining =
                  60 - (time() - $_SESSION['verification_code_sent_at']);

              $response['message'] =
                  "Espera {$remaining} segundos para reenviar el código.";

              echo json_encode($response);
              exit();
          }

          $query = "
              SELECT
                  Usuario,
                  Correo,
                  Username
              FROM usuarios
              WHERE idUsuario = $user_id
              LIMIT 1
          ";

          $query_result = mysqli_query($mysqli, $query);

          if (!mysqli_num_rows($query_result)) {
              throw new Exception('Usuario no encontrado.');
          }

          $user_data = mysqli_fetch_assoc($query_result);

          $full_name = $user_data['Usuario'];
          $email     = $user_data['Correo'];
          $username     = $user_data['Username'];

          $verification_code = generateVerificationCode();

          $query = "
              UPDATE usuarios
              SET
                  VerificationCode = '$verification_code',
                  VerificationCodeStatus = 'Nuevo'
              WHERE idUsuario = $user_id
          ";

          mysqli_query($mysqli, $query);

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

          $mail->setFrom(
              PHPMAILER_SUPPORT_EMAIL,
              'Manteles Largos | Código de verificación'
          );

          $mail->addAddress(
              $email,
              $full_name
          );

          ob_start();

          include '../lib/email-templates/verification-code.php';

          $message_to_send = ob_get_clean();

          $mail->isHTML(true);
          $mail->Subject = 'Nuevo código de verificación';
          $mail->Body    = $message_to_send;

          $mail->send();

          $_SESSION['verification_code_sent_at'] = time();

          $response = array(
              'status' => 'success',
              'message' => 'Hemos enviado un nuevo código a tu correo.'
          );

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
            'message' => '"' . $etrc_name . '" tus credenciales de acceso se enviaron correctamente a tu correo electrónico, no olvides revisar tu bandeja de spam o promociones.'
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
