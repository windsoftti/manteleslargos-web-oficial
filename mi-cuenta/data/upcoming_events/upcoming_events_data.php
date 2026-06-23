<?php
include '../session.php';
include '../../inc/functions.inc.php';
include '../lib/pagination.php';

$action = $_POST['action'];

switch ($action) {
  case 'list_upcoming_events':
    $id_user_create = $_SESSION['session_user_id'];

    $page           = isset($_POST['page']) && $_POST['page'] !== '' ? $_POST['page'] : 1;
    $per_page       = 15;

    $name           = cleanStr($_POST['searchByName']);
    $search_by_name = $name != '' ? "EC.NombreCompleto LIKE '%$name%'" : "1=1";

    $type           = cleanStr($_POST['tipo']);
    $search_by_type = "1=1";

    if ($type === 'proximos') $search_by_type = "EC.Fecha >= NOW()";
    if ($type === 'pasados')  $search_by_type = "EC.Fecha < NOW()";

    $from           = "FROM reservaciones AS EC";

    $left_join = "
      LEFT JOIN salones           AS S    ON (EC.idNegocio      = S.idSalon)
      LEFT JOIN paquetes_negocios AS PN   ON (EC.idPaquete      = PN.idPaquete)
      LEFT JOIN tipo_eventos      AS TE   ON (EC.idTipoEvento   = TE.idTipoEvento)
    ";

    $where = "WHERE
        ($search_by_name) AND
        (EC.idUsuario = '$id_user_create') AND
        EC.idNegocio  = $_SESSION[session_business_id] AND
        ($search_by_type)
      ORDER BY EC.Fecha ASC
    ";

    $start_rows        = ($page - 1) * $per_page;
    $stop_rows         = $per_page;

    $limit_rows        = "LIMIT $start_rows, $stop_rows";

    $query             = "SELECT COUNT(EC.idReservacion) AS Total $from $left_join $where LIMIT 1";
    $query_result      = mysqli_query($mysqli, $query);
    $row               = mysqli_fetch_array($query_result);

    $num_pages         = ceil($row['Total'] / $stop_rows);

    if (!$num_pages) {
      $default_message = '¡No hay eventos proximos!.';

      if ($name != '') $default_message = '¡No se encontraron resultados con su busqueda!.';

      ob_start();
      include '../default_message.php';
      $data_message = base64_encode(ob_get_clean());

      $response['content'] = $data_message;
    }

    if ($num_pages) {
      $query = "SELECT
          EC.idReservacion,
          EC.idUsuario,
          EC.idNegocio,
          EC.idPaquete,
          EC.idTipoEvento,
          EC.NombreCompleto,
          EC.Correo,
          EC.Telefono,
          EC.Fecha,
          DATE_FORMAT(EC.Fecha, '%d-%m-%Y') AS FechaFormat,
          DATE_FORMAT(EC.HoraInicio, '%h:%i %p') AS HoraInicio,
          DATE_FORMAT(EC.HoraFinal, '%h:%i %p') AS HoraFinal,
          EC.NPersonas,
          EC.Extras,
          EC.CostoTotal,
          EC.Deposito,
          EC.Anticipo,
          S.Salon,
          PN.Paquete,
          TE.TipoEvento
        $from
        $left_join
        $where
        $limit_rows
      ";

      $query_result = mysqli_query($mysqli, $query);

      ob_start();
      include 'upcoming_events_table.php';
      $data_table = base64_encode(ob_get_clean());

      $response['content'] = $data_table;
    }
    break;

  default:
    $response = array(
      'state' => 'error',
      'title' => '¡Error!',
      'message' => '¡Error!, Intentelo nuevamente.'
    );
    break;
}

echo json_encode($response);
mysqli_close($mysqli);
