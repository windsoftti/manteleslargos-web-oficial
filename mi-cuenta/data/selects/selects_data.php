<?php
include '../session.php';
include '../../inc/functions.inc.php';

$action = isset($_POST['action']) && $_POST['action'] !== '' ? $_POST['action'] : '';

$response = [];

switch ($action) {
  case 'business':
    $query        = "SELECT idSalon, Salon FROM salones";
    $query_result = mysqli_query($mysqli, $query);
    $num_rows     = mysqli_num_rows($query_result);

    if ($num_rows) {
      ob_start();
      while ($row = mysqli_fetch_array($query_result)) : ?>
        <option value="<?= $row['idSalon'] ?>"><?= $row['Salon'] ?></option>
      <?php
      endwhile;
      $data_select = base64_encode(ob_get_clean());
      $response['content'] = $data_select;
    }
    break;

  case 'list_event_types':
    $query        = "SELECT idTipoEvento, TipoEvento FROM tipo_eventos";
    $query_result = mysqli_query($mysqli, $query);
    $num_rows     = mysqli_num_rows($query_result);

    if ($num_rows) {
      ob_start();
      while ($row = mysqli_fetch_array($query_result)) : ?>
        <option value="<?= $row['idTipoEvento'] ?>"><?= $row['TipoEvento'] ?></option>
      <?php
      endwhile;
      $data_select = base64_encode(ob_get_clean());
      $response['content'] = $data_select;
    }
    break;

  case 'list_citys':
    $state_id = cleanStr($_POST['stateId']);

    $query = "SELECT
            EC.idEstadoCiudad,
            EC.idEstado,
            EC.idCiudad,
            C.Ciudad
          FROM estados_ciudades AS EC
            LEFT JOIN ciudades AS C ON (EC.idCiudad = C.idCiudad)
          WHERE EC.idEstado = $state_id
          ORDER BY C.Ciudad ASC
        ";

    $query_result = mysqli_query($mysqli, $query);
    $num_rows     = mysqli_num_rows($query_result);

    if ($num_rows) {
      ob_start(); ?>
      <option value="">Seleccionar</option>
      <?php while ($row = mysqli_fetch_array($query_result)) : ?>
        <option value="<?= $row['idCiudad'] ?>"><?= $row['Ciudad'] ?></option>
      <?php endwhile;

      $data_select = base64_encode(ob_get_clean());
      $response['content'] = $data_select;
    }
    break;

  case 'list_packages':
    $business_id = cleanStr($_POST['businessId']);

    $query = "SELECT
        idPaquete,
        Paquete
      FROM paquetes_negocios
      WHERE idNegocio = $business_id
      ORDER BY Paquete ASC
    ";

    $query_result = mysqli_query($mysqli, $query);
    $num_rows     = mysqli_num_rows($query_result);

    if ($num_rows) {
      ob_start(); ?>
      <option value="">Seleccionar</option>
      <?php while ($row = mysqli_fetch_array($query_result)) : ?>
        <option value="<?= $row['idPaquete']; ?>"><?= $row['Paquete']; ?></option>
<?php endwhile;

      $data_select = base64_encode(ob_get_clean());
      $response['content'] = $data_select;
    }
    break;

  case 'citys':
    $state_id = cleanStr($_POST['data']);

    $citys = citysForSelect(
      'CIUDAD',
      $state_id
    );

    $response = array(
      'status'  => 'success',
      'content' => base64_encode($citys)
    );
    break;

  default:
    $response = [];
    break;
}

echo json_encode($response);
