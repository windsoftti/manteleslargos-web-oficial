<?php
date_default_timezone_set('America/Mexico_City');
include '../lib/public-session.php';
include '../lib/pagination.php';

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
  case 'request_info':
    try {
      $business_id        = cleanStr($_POST['businessId']);
      $business_data      = getBusinessDataById($business_id);

      $etdc_name          = cleanStr($_POST['contactName']);
      $etdc_email         = cleanStr($_POST['contactEmail']);
      $etdc_phone         = cleanStr($_POST['contactPhone']);
      $etdc_message       = cleanStr($_POST['contactMessage']);

      $etdc_business_name = $business_data['Salon'];

      $addressee_name     = $business_data['Usuario'];
      $addressee_email    = $business_data['Correo'];

      $mail = new PHPMailer(true);

      $mail->SMTPDebug = 0;
      $mail->isSMTP();
      $mail->Host       = PHPMAILER_HOST;
      $mail->SMTPAuth   = true;
      $mail->Username   = PHPMAILER_CONTACT_EMAIL;
      $mail->Password   = PHPMAILER_CONTACT_PASSWORD;
      $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
      $mail->Port       = 465;

      $mail->CharSet = 'UTF-8';

      $mail->setFrom(PHPMAILER_CONTACT_EMAIL, 'Manteles Largos | Solicitud de información');
      $mail->addAddress($addressee_email, $addressee_name);

      ob_start();
      include '../lib/email-templates/direct-contact.php';
      $message_to_send = ob_get_clean();

      $mail->isHTML(true);
      $mail->Subject = 'Solicitud de información | Manteles Largos';
      $mail->Body    = $message_to_send;

      $mail->send();

      $query = "SELECT
        FormularioEnviado AS Counter
      FROM salones
      WHERE
        idSalon = $business_id
      LIMIT 1
    ";

      $query_result = mysqli_query($mysqli, $query);
      $num_rows     = mysqli_num_rows($query_result);

      if ($num_rows > 0) :
        $business_data  = mysqli_fetch_array($query_result);
        $counter        = intval($business_data['Counter']);
        $new_counter    = $counter + 1;

        $query = "UPDATE salones SET
          FormularioEnviado = $new_counter
        WHERE
          idSalon = $business_id
      ";

        mysqli_query($mysqli, $query);
      endif;

      $response = array(
        'status'  => 'success',
        'message' => '¡Datos enviados!, gracias por contactar con ' . $etdc_business_name
      );
    } catch (Exception $e) {
      $response['message'] = $e->getMessage();
    }
    break;

  case 'request_quote':
    try {
      $user_id  = ($_SESSION['session_user_id'] && $_SESSION['session_user_level'] == 'Usuario Final') ? $_SESSION['session_user_id'] : '0';

      $package      = cleanStr($_POST['package']);
      $name         = cleanStr($_POST['fullName']);
      $email        = cleanStr($_POST['email']);
      $phone        = cleanStr($_POST['phone']);
      $event_type   = cleanStr($_POST['eventType']);
      $date         = parseDate($_POST['requestedDate']);
      $today_date   = date('Y-m-d');

      $business_data = getBusinessDataByPackage($package);

      if (!$business_data) :
        echo json_encode($response);
        mysqli_close($mysqli);
        return;
      endif;

      if (strtotime($date) < strtotime($today_date)) :
        $response['message'] = '¡La fecha no está disponible!';
        echo json_encode($response);
        mysqli_close($mysqli);
        return;
      endif;

      $supplier_id  = $business_data['supplierId'];
      $business_id  = $business_data['businessId'];
      $business     = $business_data['business'];

      $date_status = getBusinessDateStatus(
        $supplier_id,
        $business_id,
        $date
      );

      if ($date_status == 'Ocupado') :
        $response['message'] = '¡La fecha no está disponible!';

        echo json_encode($response);
        mysqli_close($mysqli);
        return;
      endif;

      $folio = getQuoteFolio($business_id);

      $query = "INSERT INTO cotizaciones (
          idProveedor,
          idUsuarioFinal,
          idNegocio,
          idPaquete,
          idTipoEvento,
          Folio,
          NombreCompleto,
          Email,
          Telefono,
          FechaSolicitada
        ) VALUES (
          $supplier_id,
          $user_id,
          $business_id,
          $package,
          '$event_type',
          '$folio',
          '$name',
          '$email',
          '$phone',
          '$date'
        )
      ";

      $query_result = mysqli_query($mysqli, $query);

      if ($query_result) {
        sendQuoteNotification(
          $supplier_id,
          $name,
          $business
        );

        $response = array(
          'status'  => 'success',
          'message' => '¡La cotización se solicitó correctamente!'
        );
      }
    } catch (Exception $e) {
      $response['message'] = $supplier_id . ':::' . $e->getMessage();
    }
    break;

  case 'add_counter':
    $business_id  = cleanStr($_POST['businessId']);
    $event        = cleanStr($_POST['event']);
    $column_name  = "";


    if ($event === 'telefono-visto')  $column_name = "TelefonoVisto";
    if ($event === 'click-llamar')    $column_name = "ClickLlamar";
    if ($event === 'click-whatsapp')  $column_name = "ClickWhatsapp";

    $query = "SELECT
        $column_name AS Counter
      FROM salones
      WHERE
        idSalon = $business_id
      LIMIT 1
    ";

    $query_result = mysqli_query($mysqli, $query);
    $num_rows     = mysqli_num_rows($query_result);

    if ($num_rows > 0) :
      $business_data  = mysqli_fetch_array($query_result);
      $counter        = intval($business_data['Counter']);
      $new_counter    = $counter + 1;

      $query = "UPDATE salones SET
          $column_name = $new_counter
        WHERE
          idSalon = $business_id
      ";

      mysqli_query($mysqli, $query);
    endif;
    break;
}

echo json_encode($response);
mysqli_close($mysqli);
die();
