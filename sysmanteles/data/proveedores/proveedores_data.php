<?php
include '../lib/session-root.php';
include '../lib/pagination.php';

$action = $_POST['action'];

$initial_response = array(
  'status'  => 'error',
  'title'   => '¡Error!',
  'message' => 'Error inesperado, intentalo nuevamente'
);

$response = $initial_response;

switch ($action) {
  case 'load-proveedores':
    try {
      $per_page         = !empty($_POST['perPage']) ? $_POST['perPage'] : 15;
      $page             = $_POST['page'];

      $search           = cleanStr($_POST['search']);
      $status           = cleanStr($_POST['status']);
      $cuenta_proveedor = cleanStr($_POST['cuenta_proveedor']);

      $column_id        = "idUsuario";
      $c_from           = "usuarios";
      $c_order          = "ORDER BY idUsuario DESC";

      $fields = [
        "idUsuario",
        ["idUsuario", "uid"],
        "Usuario",
        "Correo",
        "Telefono",
        "VerificationCodeStatus",
        "Status"
      ];

      $c_join   = "";

      $c_where  = [];

      if (!empty($search)) array_push($c_where, [[
        ["Usuario", "%$search%", "LIKE"],
        ["Correo",  "%$search%", "LIKE", "OR"]
      ]]);

      if (!empty($status))                  array_push($c_where, ["Status", "$status"]);
      if ($cuenta_proveedor === 'Activo')   array_push($c_where, ["VerificationCodeStatus", "Usado"]);
      if ($cuenta_proveedor === 'Inactivo') array_push($c_where, ["VerificationCodeStatus", "Nuevo"]);

      $request = useDataTable([
        'column_id' => $column_id,
        'from'      => $c_from,
        'where'     => $c_where,
        'fields'    => $fields,
        'join'      => $c_join,
        'order'     => $c_order,
        'per_page'  => $per_page,
        'page'      => $page
      ]);

      # echo getEmptyTableMessage($request);
      # die;

      if (!$request)  echo getEmptyTableMessage();
      if ($request)   include 'proveedores_table.php';
    } catch (Exception $e) {
      echo getEmptyTableMessage($e->getMessage());
    }
    die;
    break;
}

echo json_encode($response);
mysqli_close($mysqli);
