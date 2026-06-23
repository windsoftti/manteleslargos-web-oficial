<?php
date_default_timezone_set('America/Mexico_City');
include '../session.php';
include '../../inc/functions.inc.php';
include '../lib/pagination.php';

$action = $_POST['action'];

$response = array(
  'status'  => 'error',
  'title'   => '¡Error!',
  'message' => '¡Error!, Intentelo nuevamente.'
);

switch ($action) {
  case 'list_upcoming_event_payments':
    $id_user_create     = $_SESSION['session_user_id'];
    $reservation_id  = cleanStr($_POST['reservationId']);
    $date               = (isset($_POST['date']) && $_POST['date'] != '') ? parseDatePicker($_POST['date']) : '';

    $search_by_date = $date != '' ? "ECP.Fecha = '$date'" : "1=1";

    $query = "SELECT
        ECP.idReservacionPago,
        ECP.idReservacion,
        ECP.Pago,
        DATE_FORMAT(ECP.Fecha, '%d-%m-%Y') AS FechaFormat,
        ECP.Fecha,
        ECP.Comentarios,
        EC.CostoTotal
      FROM reservaciones_pagos AS ECP
        LEFT JOIN reservaciones AS EC ON (ECP.idReservacion = EC.idReservacion)
      WHERE
        ECP.idUsuario           = $id_user_create AND
        ECP.idReservacion  = $reservation_id AND
        ($search_by_date)
      ORDER BY ECP.idReservacionPago
      DESC
    ";

    $query_result = mysqli_query($mysqli, $query);
    $num_rows     = mysqli_num_rows($query_result);

    if (!$num_rows) {
      if ($date == '') {
        $query_payments = "SELECT
            CostoTotal
          FROM reservaciones
          WHERE idReservacion = '$reservation_id'
          LIMIT 1
        ";


        $query_result_payments = mysqli_query($mysqli, $query_payments);
        $data_payment = mysqli_fetch_array($query_result_payments);


        $total = $data_payment['CostoTotal'];
        $balance = $data_payment['CostoTotal'];

        $num_row_table  = 0;

        ob_start();
        include 'upcoming_event_payments_table.php';

        $content              = base64_encode(ob_get_clean());
        //$content            = base64_encode($query);
        $response['content']  = $content;
        $response['balance']  = $balance;
        $response['totalPayments']  = 0;
      } else {
        $default_message = 'No hay pagos registrados';

        ob_start();
        include '../default_message.php';

        $data_message         = base64_encode(ob_get_clean());
        $response['content']  = $data_message;
      }
    }

    if ($num_rows) {
      $query_payments = "SELECT
          ECP.Pago,
          EC.CostoTotal
        FROM reservaciones_pagos AS ECP
          LEFT JOIN reservaciones AS EC ON (ECP.idReservacion = EC.idReservacion)
        WHERE
          ECP.idUsuario           = $id_user_create AND
          ECP.idReservacion  = $reservation_id
        ORDER BY ECP.idReservacionPago
        DESC
      ";

      $total          = 0;
      $balance        = 0;
      $total_payments = 0;

      $query_result_payments = mysqli_query($mysqli, $query_payments);

      while ($row = mysqli_fetch_array($query_result_payments)) {
        $total          = $row['CostoTotal'];
        $total_payments = $total_payments + $row['Pago'];
      }

      $num_row_table  = 0;

      ob_start();
      include 'upcoming_event_payments_table.php';

      $content                    = base64_encode(ob_get_clean());
      //$content                  = base64_encode($query);
      $response['content']        = $content;
      $response['balance']        = $balance;
      $response['totalPayments']  = $total_payments;
    }
    break;

  case 'add_new_payment':
    $reservation_id  = cleanStr($_POST['reservationId']);
    $payment            = cleanStr($_POST['payment']);
    $comments           = cleanStr($_POST['comments']);
    $date               = parseDatePicker($_POST['date']);

    $query = "SELECT
        EC.idReservacion,
        EC.CostoTotal,
        (EC.CostoTotal - SUM(ECP.Pago)) AS SaldoActual
      FROM reservaciones AS EC
        LEFT JOIN reservaciones_pagos AS ECP ON (EC.idReservacion = ECP.idReservacion)
      WHERE
        EC.idReservacion  = '$reservation_id' AND
        EC.idUsuario           = '$_SESSION[session_user_id]'
      LIMIT 1
    ";

    $query_result = mysqli_query($mysqli, $query);
    $num_rows     = mysqli_num_rows($query_result);

    if ($num_rows) {
      $event_data = mysqli_fetch_array($query_result);
      $current_balance = $event_data['SaldoActual'];

      if (($payment <= $current_balance) || $current_balance == NULL) {
        $query = "INSERT INTO reservaciones_pagos (
            idUsuario,
            idReservacion,
            Pago,
            Fecha,
            Comentarios
          ) VALUES (
            '$_SESSION[session_user_id]',
            '$reservation_id',
            '$payment',
            '$date',
            '$comments'
          )
        ";

        $query_result = mysqli_query($mysqli, $query);

        if ($query_result) $response = array(
          'status'  => 'success',
          'title'   => '¡Pago agregado!',
          'message' => 'El pago se agregó correctamente.'
        );
      }
    }
    break;

  case 'edit_payment':
    $event_calendar_payment_id = cleanStr($_POST['reservationPaymentId']);

    $payment            = cleanStr($_POST['payment']);
    $comments           = cleanStr($_POST['comments']);
    $date               = parseDatePicker($_POST['date']);

    $query = "UPDATE reservaciones_pagos SET
        Pago        = '$payment',
        Fecha       = '$date',
        Comentarios = '$comments'
      WHERE
        idReservacionPago  = $event_calendar_payment_id AND
        idUsuario               = $_SESSION[session_user_id]
    ";

    $query_result = mysqli_query($mysqli, $query);

    if ($query_result) $response = array(
      'status'  => 'success',
      'title'   => '¡Pago actualizado!',
      'message' => 'El pago se actualizó correctamente.'
    );
    break;

  case 'delete_payment':
    $event_calendar_payment_id = cleanStr($_POST['reservationPaymentId']);

    $query = "DELETE FROM reservaciones_pagos WHERE
      idReservacionPago  = $event_calendar_payment_id AND
      idUsuario               = $_SESSION[session_user_id]
    ";

    $query_result = mysqli_query($mysqli, $query);

    if ($query_result) $response = array(
      'status'  => 'success',
      'message' => 'El pago se eliminó correctamente'
    );
    break;

  default:
    $response = array(
      'status'  => 'error',
      'title'   => '¡Error!',
      'message' => '¡Error!, Intentelo nuevamente.'
    );
    break;
}

echo json_encode($response);
mysqli_close($mysqli);
