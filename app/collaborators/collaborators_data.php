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
  case 'get-collaborators':
    try {
      $parameters   = $json['parameters'];

      $user_id      = cleanStr($parameters['userId']);
      $business_id  = cleanStr($parameters['businessId']);
      $page         = cleanStr($parameters['page']);
      $search_term  = cleanStr($parameters['searchTerm']);

      $per_page     = 15;

      $from         = "FROM usuarios";

      $search_by_term = $search_term != '' ? "
          (Usuario  LIKE '%$search_term%') OR
          (Correo   LIKE '%$search_term%')
      " : "1=1";

      $where = "WHERE
          PerteneceA  = $user_id      AND
          idNegocio   = $business_id  AND
          ($search_by_term)
        ORDER BY idUsuario DESC
      ";

      $start_rows = ($page - 1) * $per_page;
      $stop_rows  = $per_page;

      $limit_rows = "LIMIT $start_rows, $stop_rows";

      $query      = "SELECT COUNT(idUsuario) AS Total $from $where LIMIT 1";
      $num_pages  = numPages($query, $stop_rows);

      if (!$num_pages) $response = array(
        'status' => 'empty'
      );

      if ($num_pages) {
        $query = "SELECT
            idUsuario,
            idNegocio,
            Usuario,
            Correo,
            Telefono,
            Celular,
            Nivel,
            Username,
            Password,
            Plan,
            PerteneceA
          $from
          $where
          $limit_rows
        ";

        $query_result = mysqli_query($mysqli, $query);

        $collaborators = array();

        while ($collaborator_data = mysqli_fetch_array($query_result)) :
          $collaborator_data['TelefonoFormat'] = formatPhoneNumber($collaborator_data['Telefono']);
          array_push($collaborators, $collaborator_data);
        endwhile;

        $response = array(
          'status'        => 'success',
          'totalPages'    => $num_pages,
          'collaborators' => $collaborators
        );
      }
    } catch (Exception $e) {
      $response['message'] = $e->getMessage();
    }
    break;

  case 'add-collaborator':
    try {
      $parameters   = $json['parameters'];

      $user_id      = cleanStr($parameters['userId']);
      $business_id  = cleanStr($parameters['businessId']);
      $values       = $parameters['values'];

      $full_name    = cleanStr($values['fullName']);
      $email        = cleanStr($values['email']);
      $phone        = cleanStr($values['phone']);
      $cell_phone   = cleanStr($values['cellPhone']);
      $username     = cleanStr($values['username']);
      $password     = encrypt($values['password'], $mysqli_secret);

      $query = "SELECT Correo, Username FROM usuarios WHERE
          Correo    = '$email' OR
          Username  = '$username'
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
          }
        }
      }

      if (!$num_rows) {
        $query_get_plan = "SELECT Plan FROM usuarios WHERE idUsuario = $user_id LIMIT 1";
        $query_get_plan_result  = mysqli_query($mysqli, $query_get_plan);
        $user_data              = mysqli_fetch_array($query_get_plan_result);
        $plan                   = $user_data['Plan'];

        $query = "INSERT INTO usuarios (
            PerteneceA,
            idNegocio,
            Usuario,
            Correo,
            Telefono,
            Celular,
            Nivel,
            Username,
            Password,
            Status,
            Plan
          ) VALUES (
            $user_id,
            $business_id,
            '$full_name',
            '$email',
            '$phone',
            '$cell_phone',
            'Usuario',
            '$username',
            '$password',
            'Activo',
            '$plan'
          )
        ";

        $query_result = mysqli_query($mysqli, $query);

        if ($query_result) {
          $collaborator_id = mysqli_insert_id($mysqli);

          $response = array(
            'status'          => 'success',
            'title'           => '¡Datos guardados!',
            'message'         => 'El colaborador se agregó correctamente',
            'collaboratorId'  => $collaborator_id
          );
        }
      }
    } catch (Exception $e) {
      $response['message'] = $e->getMessage();
    }
    break;

  case 'update-collaborator':
    try {
      $parameters       = $json['parameters'];

      $user_id          = cleanStr($parameters['userId']);
      $business_id      = cleanStr($parameters['businessId']);
      $collaborator_id  = cleanStr($parameters['collaboratorId']);
      $edit_password    = $parameters['editPassword'];

      $values           = $parameters['values'];

      $full_name        = cleanStr($values['fullName']);
      $email            = cleanStr($values['email']);
      $phone            = cleanStr($values['phone']);
      $cell_phone       = cleanStr($values['cellPhone']);
      $username         = cleanStr($values['username']);
      $password         = encrypt($values['password'], $mysqli_secret);

      $query = "SELECT Correo, Username FROM usuarios WHERE
          (
            Correo    = '$email' OR
            Username  = '$username'
          ) AND
          idUsuario   != $collaborator_id
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
          }
        }
      }

      if (!$num_rows) {
        $query_password = $edit_password ? "
          , Password = '$password'
        " : "";

        $query = "UPDATE usuarios SET
            Usuario   = '$full_name',
            Correo    = '$email',
            Telefono  = '$phone',
            Celular   = '$cell_phone',
            Username  = '$username'
            $query_password
          WHERE
            idUsuario   = $collaborator_id  AND
            PerteneceA  = $user_id          AND
            idNegocio   = $business_id
        ";

        $query_result = mysqli_query($mysqli, $query);

        if ($query_result) $response = array(
          'status'  => 'success',
          'title'   => '¡Datos guardados!',
          'message' => 'El colaborador se actualizó correctamente'
        );
      }
    } catch (Exception $e) {
      $response['message'] = $e->getMessage();
    }
    break;

  case 'remove-collaborator':
    $parameters       = $json['parameters'];

    $user_id          = cleanStr($parameters['userId']);
    $business_id      = cleanStr($parameters['businessId']);
    $collaborator_id  = cleanStr($parameters['collaboratorId']);

    $query = "DELETE FROM usuarios WHERE
        idUsuario   = $collaborator_id  AND
        idNegocio   = $business_id      AND
        PerteneceA  = $user_id
    ";

    $query_result = mysqli_query($mysqli, $query);

    if ($query_result) {
      $query_delete_permissions = "DELETE FROM ml_paginas_usuarios_permisos WHERE
          idUsuario     = $collaborator_id AND
          idUserCreate  = $user_id
      ";

      mysqli_query($mysqli, $query_delete_permissions);

      $response = array(
        'status'  => 'success',
        'title'   => '¡Colaborador eliminado!',
        'message' => 'El colaborador se eliminó correctamente.'
      );
    }
    break;

  case 'get-collaborator-permissions':
    try {
      $parameters       = $json['parameters'];

      $user_id          = cleanStr($parameters['userId']);
      $business_id      = cleanStr($parameters['businessId']);
      $collaborator_id  = cleanStr($parameters['collaboratorId']);

      # OBTENER LAS PAGINAS
      $query_permission_pages = "SELECT
          idPagina,
          NombrePagina
        FROM ml_paginas
        ORDER BY idPagina
        ASC
      ";

      $permission_pages = array();
      $query_permission_pages_result = mysqli_query($mysqli, $query_permission_pages);

      while ($row = mysqli_fetch_array($query_permission_pages_result)) :
        array_push($permission_pages, array(
          '_id'   => $row['idPagina'],
          'value' => $row['NombrePagina']
        ));
      endwhile;

      # OBTENRE LOS PERMISOS DEL COLABORADOR
      $collaborator_permissions = array();

      $query_collaborator_permissions = "SELECT idPagina FROM ml_paginas_usuarios_permisos WHERE
          idUsuario     = $collaborator_id AND
          idUserCreate  = $user_id
      ";

      $query_collaborator_permissions_result = mysqli_query($mysqli, $query_collaborator_permissions);
      $num_permissions = mysqli_num_rows($query_collaborator_permissions_result);

      if ($num_permissions) :
        while ($row = mysqli_fetch_array($query_collaborator_permissions_result)) :
          array_push($collaborator_permissions, $row['idPagina']);
        endwhile;
      endif;

      $response = array(
        'status'                  => 'success',
        'permissionPages'         => $permission_pages,
        'collaboratorPermissions' => $collaborator_permissions
      );
    } catch (Exception $e) {
      $response['message'] = $e->getMessage();
    }
    break;

  case 'save-collaborator-permissions':
    try {
      $parameters               = $json['parameters'];

      $user_id                  = cleanStr($parameters['userId']);
      $business_id              = cleanStr($parameters['businessId']);
      $collaborator_id          = cleanStr($parameters['collaboratorId']);
      $collaborator_permissions = $parameters['collaboratorPermissions'];

      $query_delete_old_permissions = "DELETE FROM ml_paginas_usuarios_permisos WHERE
          idUsuario     = $collaborator_id AND
          idUserCreate  = $user_id
      ";

      $query_delete_old_permissions_result = mysqli_query($mysqli, $query_delete_old_permissions);

      if ($query_delete_old_permissions) :
        foreach ($collaborator_permissions as $key => $value) :
          $permission = $value;

          $query_add_new_permissions = "INSERT INTO ml_paginas_usuarios_permisos (
              idUserCreate,
              idPagina,
              idUsuario
            ) VALUES (
              $user_id,
              $permission,
              $collaborator_id
            )
          ";

          mysqli_query($mysqli, $query_add_new_permissions);
        endforeach;

        $response = array(
          'status'  => 'success',
          'title'   => '¡Pemisos agregados!',
          'message' => 'Los permisos del colaborador se agregaron correctamente.'
        );
      endif;
    } catch (Exception $e) {
      $response['message'] = $e->getMessage();
    }
    break;
}

echo json_encode($response);
mysqli_close($mysqli);
exit();
