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
  case 'get-reservation-payments':
    try {
      $parameters   = $json['parameters'];

      $user_id        = cleanStr($parameters['userId']);
      $reservation_id = cleanStr($parameters['reservationId']);
      $date           = cleanStr($parameters['date']);

      $by_date        = $date != '' ? "(RP.Fecha = '$date')" : "1=1";

      $from           = "FROM reservaciones_pagos AS RP";
      $left_join      = "LEFT JOIN reservaciones AS R ON (RP.idReservacion = R.idReservacion)";

      $where = "WHERE
          RP.idUsuario     = $user_id        AND
          RP.idReservacion = $reservation_id AND
          ($by_date)
        ORDER BY RP.idReservacionPago
        DESC
      ";

      # OBTENER EL TOTAL, TOTAL ABONADO Y SALDO RESTANTE
      $query_total_paid = "SELECT
          R.CostoTotal,
          SUM(RP.Pago)                  AS TotalAbonado,
          (R.CostoTotal - SUM(RP.Pago)) AS SaldoRestante
        FROM reservaciones AS R
          LEFT JOIN reservaciones_pagos AS RP ON (R.idReservacion = RP.idReservacion)
        WHERE
          R.idUsuario     = $user_id AND
          R.idReservacion = $reservation_id
        LIMIT 1
      ";

      $query_total_paid_result  = mysqli_query($mysqli, $query_total_paid);
      $paid_data                = mysqli_fetch_array($query_total_paid_result);

      # COSTO TOTAL DE LA RESERVACIÓN
      $total_cost                     = $paid_data['CostoTotal'];
      $total_cost_with_format         = '$' . number_format($total_cost, 2);

      # SALDO RESTANTE
      $remaining_balance              = $paid_data['SaldoRestante'] != null ? $paid_data['SaldoRestante'] : $total_cost;
      $remaining_balance_with_format  = '$' . number_format($remaining_balance, 2);

      # TOTAL ABONADO
      $total_paid                     = $paid_data['TotalAbonado'];
      $total_paid_with_format         = '$' . number_format($total_paid, 2);

      $reservation_details = array(
        'totalCost'                   => $total_cost,
        'totalCostWithFormat'         => $total_cost_with_format,
        'remainingBalance'            => $remaining_balance,
        'remainingBalanceWithFormat'  => $remaining_balance_with_format,
        'totalPaid'                   => $total_paid,
        'totalPaidWithFormat'         => $total_paid_with_format
      );

      if (!$total_paid) $response = array(
        'status' => 'success',
        'reservationPayments' => [],
        'reservationDetails'  => $reservation_details
      );

      if ($total_paid) :
        # OBTENER TODOS LOS PAGOS REALIZADOS
        $query_payments = "SELECT
            RP.idReservacionPago,
            RP.idUsuario,
            RP.idReservacion,
            RP.Pago,
            RP.Fecha,
            RP.Comentarios,
            DATE_FORMAT(RP.Fecha, '%d-%m-%Y') AS DateWithFormat
          $from
          $left_join
          $where
        ";

        $query_payments_result  = mysqli_query($mysqli, $query_payments);
        $num_payments           = mysqli_num_rows($query_payments_result);

        if (!$num_payments) $response = array(
          'status'              => 'success',
          'reservationPayments' => [],
          'reservationDetails'  => $reservation_details
        );

        if ($num_payments) :
          $reservation_payments = array();

          while ($row = mysqli_fetch_array($query_payments_result)) :
            $payment              = $row['Pago'];
            $payment_with_format  = '$' . number_format($payment, 2);

            $row['PaymentWithFormat'] = $payment_with_format;

            array_push($reservation_payments, $row);
          endwhile;

          $response = array(
            'status'              => 'success',
            'reservationPayments' => $reservation_payments,
            'reservationDetails'  => $reservation_details
          );
        endif;
      endif;
    } catch (Exception $e) {
      $response['message'] = $e->getMessage();
    }
    break;

  case 'add-reservation-payment':
    $parameters   = $json['parameters'];

    $user_id        = cleanStr($parameters['userId']);
    $business_id    = cleanStr($parameters['businessId']);
    $reservation_id = cleanStr($parameters['reservationId']);
    $values         = $parameters['values'];

    $payment        = cleanStr($values['payment']);
    $date           = cleanStr($values['date']);
    $comments       = cleanStr($values['comments']);

    $query = "SELECT
        R.idReservacion,
        R.CostoTotal,
        (R.CostoTotal - SUM(RP.Pago)) AS SaldoActual
      FROM reservaciones AS R
        LEFT JOIN reservaciones_pagos AS RP ON (R.idReservacion = RP.idReservacion)
      WHERE
        R.idReservacion = $reservation_id AND
        R.idUsuario     = $user_id
      LIMIT 1
    ";

    $query_result = mysqli_query($mysqli, $query);
    $num_rows     = mysqli_num_rows($query_result);

    if ($num_rows) :
      $reservation_data = mysqli_fetch_array($query_result);
      $current_balance  = $reservation_data['SaldoActual'];

      if (($payment <= $current_balance) || $current_balance == NULL) {
        $query = "INSERT INTO reservaciones_pagos (
            idUsuario,
            idReservacion,
            Pago,
            Fecha,
            Comentarios
          ) VALUES (
            $user_id,
            $reservation_id,
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
    endif;
    break;

  case 'update-reservation-payment':
    $parameters   = $json['parameters'];

    $user_id        = cleanStr($parameters['userId']);
    $business_id    = cleanStr($parameters['businessId']);
    $reservation_id = cleanStr($parameters['reservationId']);
    $reservation_payment_id = cleanStr($parameters['reservationPaymentId']);

    $values         = $parameters['values'];

    $payment        = cleanStr($values['payment']);
    $date           = cleanStr($values['date']);
    $comments       = cleanStr($values['comments']);

    $query = "UPDATE reservaciones_pagos SET
        Pago        = '$payment',
        Fecha       = '$date',
        Comentarios = '$comments'
      WHERE
        idReservacionPago = $reservation_payment_id AND
        idReservacion     = $reservation_id         AND
        idUsuario         = $user_id
    ";

    $query_result = mysqli_query($mysqli, $query);

    if ($query_result) $response = array(
      'status'  => 'success',
      'title'   => '¡Pago actualizado!',
      'message' => 'El pago se actualizó correctamente.'
    );
    break;

  case 'remove-reservation-payment':
    $parameters   = $json['parameters'];

    $user_id                = cleanStr($parameters['userId']);
    $business_id            = cleanStr($parameters['businessId']);
    $reservation_id         = cleanStr($parameters['reservationId']);
    $reservation_payment_id = cleanStr($parameters['reservationPaymentId']);

    $query = "DELETE FROM reservaciones_pagos WHERE
        idReservacionPago = $reservation_payment_id AND
        idReservacion     = $reservation_id         AND
        idUsuario         = $user_id
    ";

    $query_result = mysqli_query($mysqli, $query);

    if ($query_result) $response = array(
      'status'  => 'success',
      'title'   => '¡Operación exitosa!',
      'message' => 'El pago se eliminó correctamente.'
    );
    break;
}

echo json_encode($response);
mysqli_close($mysqli);
exit();
