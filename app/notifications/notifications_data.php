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
  case 'get-num-notifications':
    try {
      $parameters = $json['parameters'];

      $user_id      = cleanStr($parameters['userId']);
      $business_id  = cleanStr($parameters['businessId']);

      $num_pending_quotes = queryCount(
        "idCotizacion",
        "cotizaciones",
        "
          idProveedor = $user_id      AND
          idNegocio   = $business_id  AND
          Status      = 'Pendiente'
        "
      );

      # Notificaciones de recordatorios de pagos ::::::::::::::::::::::::::::
      $today_date = date('Y-m-d');
      $payment_reminders_query = "SELECT
          RP.idRecordatorioPago,
          RP.idReservacion,
          RP.Porcentaje,
          RP.Fecha,
          DATE_FORMAT(RP.Fecha, '%d-%m-%Y') AS FechaRecordatorio,
          S.Salon,
          S.idSalon
        FROM recordatorio_pagos AS RP
          LEFT JOIN reservaciones AS R ON (RP.idReservacion = R.idReservacion)
          LEFT JOIN salones       AS S ON (R.idNegocio      = S.idSalon)
        WHERE
          RP.idUsuario  = $user_id      AND
          RP.Fecha      = '$today_date' AND
          S.idSalon     = $business_id
        GROUP BY R.idReservacion
      ";

      $payment_reminders_query_result  = mysqli_query($mysqli, $payment_reminders_query);
      $payment_reminders_num_rows      = mysqli_num_rows($payment_reminders_query_result);

      # Notificaciones de recordatorios de eventos ::::::::::::::::::::::::::
      $event_reminders_query = "SELECT
          ECR.idEventoCalendarioRecordatorio,
          ECR.idEventoCalendario,
          ECR.FechaInicial,
          EC.Titulo,
          EC.Descripcion,
          EC.FechaHasta
        FROM eventos_calendario_recordatorios AS ECR
          LEFT JOIN eventos_calendario AS EC ON (ECR.idEventoCalendario = EC.idEventoCalendario)
        WHERE
          (NOW() BETWEEN ECR.FechaInicial AND EC.FechaHasta) AND
          EC.idNegocio = $business_id
      ";

      $event_reminders_query_result  = mysqli_query($mysqli, $event_reminders_query);
      $event_reminders_num_rows      = mysqli_num_rows($event_reminders_query_result);

      $num_notifications = $num_pending_quotes + $payment_reminders_num_rows + $event_reminders_num_rows;

      $response = array(
        'status'            => 'success',
        'numNotifications'  => $num_notifications
      );
    } catch (Exception $e) {
      $response['message'] = $e->getMessage();
    }
    break;

  case 'get-notifications':
    try {
      $parameters = $json['parameters'];

      $user_id      = cleanStr($parameters['userId']);
      $business_id  = cleanStr($parameters['businessId']);

      $quote_notifications            = array();
      $payment_reminder_notifications = array();
      $event_reminder_notifications   = array();

      $quotes_query = "SELECT
          C.idCotizacion,
          S.Salon,
          C.NombreCompleto,
          DATE_FORMAT(C.FechaCreacion, '%d-%m-%Y') AS FechaCreacion,
          S.Salon
        FROM cotizaciones AS C
          LEFT JOIN salones AS S ON (C.idNegocio = S.idSalon)
        WHERE
          C.idProveedor = $user_id    AND
          C.Status      = 'Pendiente' AND
          S.idSalon     = $business_id
        ORDER BY
          C.idCotizacion
        DESC
      ";

      $quotes_query_result  = mysqli_query($mysqli, $quotes_query);

      while ($row = mysqli_fetch_array($quotes_query_result)) :
        $title        = '¡Nueva cotización!';
        $description  = "$row[NombreCompleto] ha solicitado una cotización en $row[Salon]";
        $date         = $row['FechaCreacion'];

        array_push($quote_notifications, array(
          'title'       => $title,
          'description' => $description,
          'date'        => $date
        ));
      endwhile;

      # Notificaciones de recordatorios de pagos ::::::::::::::::::::::::::::
      $today_date = date('Y-m-d');
      $payment_reminders_query = "SELECT
          RP.idRecordatorioPago,
          RP.idReservacion,
          RP.Porcentaje,
          RP.Fecha,
          DATE_FORMAT(RP.Fecha, '%d-%m-%Y') AS FechaRecordatorio,
          S.Salon,
          S.idSalon
        FROM recordatorio_pagos AS RP
          LEFT JOIN reservaciones AS R ON (RP.idReservacion = R.idReservacion)
          LEFT JOIN salones       AS S ON (R.idNegocio      = S.idSalon)
        WHERE
          RP.idUsuario  = $user_id      AND
          RP.Fecha      = '$today_date' AND
          S.idSalon     = $business_id
        GROUP BY
          R.idReservacion
        ORDER BY
          RP.idRecordatorioPago
        DESC
      ";

      $payment_reminders_query_result  = mysqli_query($mysqli, $payment_reminders_query);

      while ($row = mysqli_fetch_array($payment_reminders_query_result)) :
        $title        = '¡Nuevo recordatorio!';
        $description  = "Hoy se cumple el pago del $row[Porcentaje]%";
        $date         = $row['FechaRecordatorio'];

        array_push($payment_reminder_notifications, array(
          'title'       => $title,
          'description' => $description,
          'date'        => $date
        ));
      endwhile;

      # Notificaciones de recordatorios de eventos ::::::::::::::::::::::::::
      $event_reminders_query = "SELECT
          ECR.idEventoCalendarioRecordatorio,
          ECR.idEventoCalendario,
          ECR.FechaInicial,
          EC.Titulo,
          EC.Descripcion,
          EC.FechaHasta
        FROM eventos_calendario_recordatorios AS ECR
          LEFT JOIN eventos_calendario AS EC ON (ECR.idEventoCalendario = EC.idEventoCalendario)
        WHERE
          (NOW() BETWEEN ECR.FechaInicial AND EC.FechaHasta) AND
          EC.idNegocio = $business_id
        ORDER BY
          ECR.idEventoCalendarioRecordatorio
        DESC
      ";

      $event_reminders_query_result  = mysqli_query($mysqli, $event_reminders_query);

      while ($row = mysqli_fetch_array($event_reminders_query_result)) :
        $title        = $row['Titulo'];
        $description  = $row['Descripcion'];
        $date         = date('d-m-Y');

        array_push($event_reminder_notifications, array(
          'title'       => $title,
          'description' => $description,
          'date'        => $date
        ));
      endwhile;

      $response = array(
        'status'                        => 'success',
        'quoteNotifications'            => $quote_notifications,
        'paymentReminderNotifications'  => $payment_reminder_notifications,
        'eventReminderNotifications'    => $event_reminder_notifications
      );
    } catch (Exception $e) {
      $response['message'] = $e->getMessage();
    }
    break;
}

echo json_encode($response);
mysqli_close($mysqli);
exit();
