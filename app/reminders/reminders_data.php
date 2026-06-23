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
  case 'add-reminder':
    try {
      $parameters   = $json['parameters'];

      $user_id      = cleanStr($parameters['userId']);
      $business_id  = cleanStr($parameters['businessId']);
      $values       = $parameters['values'];

      $title        = cleanStr($values['title']);
      $color        = cleanStr($values['color']);
      $description  = cleanStr($values['description']);
      $date_desde   = cleanStr($values['dateDesde']);
      $date_hasta   = cleanStr($values['dateHasta']);
      $reminders    = $values['reminders'];

      $today_date   = date('Y-m-d');

      if (strtotime($date_desde) < strtotime($today_date)) {
        $response = array(
          'status' => 'warning',
          'title' => '¡Cuidado!',
          'message' => '¡La fecha "desde" que ha seleccionado ya no esta disponible.'
        );

        echo json_encode($response);
        mysqli_close($mysqli);
        exit();
      }

      if (strtotime($date_hasta) < strtotime($date_desde)) {
        $response = array(
          'status' => 'warning',
          'title' => '¡Cuidado!',
          'message' => '¡La fecha "hasta" debe de ser mayor a la fecha "desde".'
        );

        echo json_encode($response);
        mysqli_close($mysqli);
        exit();
      }

      $query_add_event_calendar = "INSERT INTO eventos_calendario (
          idUsuario,
          idNegocio,
          Titulo,
          Descripcion,
          FechaDesde,
          FechaHasta,
          Color
        ) VALUES (
          $user_id,
          $business_id,
          '$title',
          '$description',
          '$date_desde',
          '$date_hasta',
          '$color'
        )
      ";

      $query_add_event_calendar_result = mysqli_query($mysqli, $query_add_event_calendar);

      if ($query_add_event_calendar_result) {
        $event_calendar_id = mysqli_insert_id($mysqli);

        foreach ($reminders as $key => $value) {
          $reminder     = $value;
          $quantity     = cleanStr($reminder['quantity']);
          $periodicity  = cleanStr($reminder['periodicity']);

          $first_date   = generateReminderFirstDate(
            $quantity,
            $periodicity,
            $date_desde
          );

          $query_add_reminder = "INSERT INTO eventos_calendario_recordatorios (
              idEventoCalendario,
              idUsuario,
              Cantidad,
              Periodicidad,
              FechaInicial
            ) VALUES (
              $event_calendar_id,
              $user_id,
              $quantity,
              '$periodicity',
              '$first_date'
            )
          ";

          mysqli_query($mysqli, $query_add_reminder);
        }

        $response = array(
          'status' => 'success',
          'title' => '¡Datos guardados!',
          'message' => 'El recordatorio se agregó correctamente.'
        );
      }
    } catch (Exception $e) {
      $response['message'] = $e->getMessage();
    }
    break;

  case 'update-reminder':
    try {
      $parameters         = $json['parameters'];

      $user_id            = cleanStr($parameters['userId']);
      $business_id        = cleanStr($parameters['businessId']);
      $event_calendar_id  = cleanStr($parameters['eventCalendarId']);
      $values             = $parameters['values'];

      $title              = cleanStr($values['title']);
      $color              = cleanStr($values['color']);
      $description        = cleanStr($values['description']);
      $date_desde         = cleanStr($values['dateDesde']);
      $date_hasta         = cleanStr($values['dateHasta']);
      $reminders          = $values['reminders'];

      $today_date         = date('Y-m-d');

      if (strtotime($date_desde) < strtotime($today_date)) {
        $response = array(
          'status' => 'warning',
          'title' => '¡Cuidado!',
          'message' => '¡La fecha "desde" que ha seleccionado ya no esta disponible.'
        );

        echo json_encode($response);
        mysqli_close($mysqli);
        exit();
      }

      if (strtotime($date_hasta) < strtotime($date_desde)) {
        $response = array(
          'status' => 'warning',
          'title' => '¡Cuidado!',
          'message' => '¡La fecha "hasta" debe de ser mayor a la fecha "desde".'
        );

        echo json_encode($response);
        mysqli_close($mysqli);
        exit();
      }

      $query_update_event_calendar = "UPDATE eventos_calendario SET
            Titulo      = '$title',
            Descripcion = '$description',
            FechaDesde  = '$date_desde',
            FechaHasta  = '$date_hasta',
            Color       = '$color'
          WHERE
            idEventoCalendario  = $event_calendar_id  AND
            idUsuario           = $user_id            AND
            idNegocio           = $business_id
      ";

      $query_update_event_calendar_result = mysqli_query($mysqli, $query_update_event_calendar);

      if ($query_update_event_calendar_result) {
        $query = "DELETE FROM eventos_calendario_recordatorios WHERE idEventoCalendario = $event_calendar_id";
        $query_result = mysqli_query($mysqli, $query);

        if ($query_result) {
          foreach ($reminders as $key => $value) {
            $reminder     = $value;
            $quantity     = cleanStr($reminder['quantity']);
            $periodicity  = cleanStr($reminder['periodicity']);

            $first_date   = generateReminderFirstDate(
              $quantity,
              $periodicity,
              $date_desde
            );

            $query_add_reminder = "INSERT INTO eventos_calendario_recordatorios (
                idEventoCalendario,
                idUsuario,
                Cantidad,
                Periodicidad,
                FechaInicial
              ) VALUES (
                $event_calendar_id,
                $user_id,
                $quantity,
                '$periodicity',
                '$first_date'
              )
            ";

            mysqli_query($mysqli, $query_add_reminder);
          }
        }

        $response = array(
          'status' => 'success',
          'title' => '¡Datos guardados!',
          'message' => 'El recordatorio se actualizó correctamente.'
        );
      }
    } catch (Exception $e) {
      $response['message'] = $e->getMessage();
    }
    break;

  case 'delete-reminder':
    try {
      $parameters   = $json['parameters'];

      $user_id            = cleanStr($parameters['userId']);
      $business_id        = cleanStr($parameters['businessId']);
      $event_calendar_id  = cleanStr($parameters['eventCalendarId']);

      $query = "DELETE FROM eventos_calendario WHERE idEventoCalendario = $event_calendar_id";
      $query_result = mysqli_query($mysqli, $query);

      if ($query_result) {
        $query = "DELETE FROM eventos_calendario_recordatorios WHERE idEventoCalendario = $event_calendar_id";
        mysqli_query($mysqli, $query);

        $response = array(
          'status'  => 'success',
          'title'   => '!Recordatorio eliminado¡',
          'message' => 'El recordatorio se eliminó correctamente'
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
