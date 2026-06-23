<?php
include '../session.php';
include '../../inc/functions.inc.php';
include '../lib/pagination.php';

$action = isset($_POST['action']) && $_POST['action'] !== '' ? $_POST['action'] : '';

$images_location = $images_url;

switch ($action) {
  case 'list_business':
    $id_user_create     = $_SESSION['session_user_id'];
    $user_level         = $_SESSION['session_user_level'];
    $admin_w            = ($user_level === 'Super Usuario' || $user_level === 'Administrador') ? '' : "AND (S.idUsuario = '$id_user_create')";

    $page               = isset($_POST['page']) && $_POST['page'] !== '' ? $_POST['page'] : 1;
    $per_page           = isset($_POST['perPage']) && $_POST['perPage'] !== '' ? $_POST['perPage'] : 15;

    $business  = cleanStr($_POST['searchByBusiness']);
    $search_by_business  = $business != '' ? "S.Salon LIKE '%$business%'" : "1=1";

    $from   = "FROM salones AS S";
    $left_join = "LEFT JOIN tipo_proveedores AS TP ON (S.idTipoProveedor = TP.idTipoProveedor)";
    $where  = "WHERE
        ($search_by_business) AND
        (S.Status = 'Activo')
        $admin_w
      ORDER BY S.idSalon
      DESC
    ";

    $start_rows = ($page - 1) * $per_page;
    $stop_rows  = $per_page;

    $limit_rows = "LIMIT $start_rows, $stop_rows";

    $business_count = mysqli_query_one_row("SELECT COUNT(idSalon) AS Total $from $where LIMIT 1");
    $num_pages      = ceil($business_count['Total'] / $stop_rows);

    if (!$num_pages) {
      $default_message = '¡No hay negocios registrados!.';
      if ($business != '') {
        $default_message = '¡No se encontraron resultados!. "' . $business . '"';
      }

      ob_start();
      include '../default_message.php';
      $data_message = base64_encode(ob_get_clean());

      $response['content'] = $data_message;
    }

    if ($num_pages) {
      $query = "SELECT
          S.idUsuario,
          S.idSalon,
          S.Salon,
          S.CostoRenta,
          S.Capacidad,
          S.Direccion,
          S.Telefono,
          S.slug,
          S.Referencia,
          DATE_FORMAT(S.Fecha, '%d-%m-%Y %h:%i %p') AS Fecha,
          TP.TipoProveedor
        $from
        $left_join
        $where
        $limit_rows
      ";

      $query_result = mysqli_query($mysqli, $query);

      ob_start();
      include 'business_table.php';
      $data_table = base64_encode(ob_get_clean());

      $response['content'] = $data_table;
    }
    break;

  case 'verificar_tipo_proveedor':
    $id_tipo_proveedor = cleanStr($_POST['tipoProveedor']);

    $query = "SELECT Tipo FROM tipo_proveedores WHERE idTipoProveedor = '$id_tipo_proveedor' LIMIT 1";
    $query_result = mysqli_query($mysqli, $query);
    $num_rows = mysqli_num_rows($query_result);

    if (!$num_rows) {
      $response = 'none';
    }

    if ($num_rows) {
      $row = mysqli_fetch_array($query_result);

      $tipo_proveedor = $row['Tipo'];

      $response = $tipo_proveedor;
    }
    break;

  case 'add_business':
    //$id_usuario = $_SESSION['session_user_id'];
    $id_usuario = $_SESSION['session_user_id'];

    if ($_SESSION['session_user_level'] != 'Usuario') {
      $id_usuario = (isset($_POST['userId']) && $_POST['userId'] != '') ? $_POST['userId'] : '';

      if ($id_usuario == '') {
        $response = array(
          'state'   => 'error',
          'title'   => '¡Error!',
          'message' => 'Seleccione a que usuario pertenece el negocio.'
        );

        echo json_encode($response);
        return;
      }
    }

    $id_tipo_proveedor  = cleanStr($_POST['tipoProveedor']);
    $tipos_eventos      = $_POST['tipoEvento'];

    if ($id_tipo_proveedor == '' || !count($tipos_eventos)) {
      $response = array(
        'state'   => 'error',
        'message' => 'Verifique que el tipo de proveedor y los tipos de eventos esten definidos.'
      );

      echo json_encode($response);
      mysqli_close($mysqli);
      return;
    }

    $query = "SELECT Tipo FROM tipo_proveedores WHERE idTipoProveedor = '$id_tipo_proveedor' LIMIT 1";
    $query_result = mysqli_query($mysqli, $query);

    $row            = mysqli_fetch_array($query_result);
    $tipo_proveedor = $row['Tipo'];

    $costo          = $tipo_proveedor == 'Salon' ? cleanStr($_POST['costo']) : '';
    $capacidad      = $tipo_proveedor == 'Salon' ? cleanStr($_POST['capacidad']) : '';
    $capacidad_max  = $tipo_proveedor == 'Salon' ? cleanStr($_POST['capacidadMaxima']) : '';
    $orientacion    = $tipo_proveedor == 'Salon' ? cleanStr($_POST['orientacion']) : '';

    $latitud        = cleanStr($_POST['latitud']);
    $longitud       = cleanStr($_POST['longitud']);
    $direccion      = cleanStr($_POST['direccion']);

    $servicios      = $_POST['servicios'];
    $amenidades     = $_POST['amenidades'];

    $nombre_paquetes      = $_POST['nombrePaquete'];
    $orientacion_paquetes = $_POST['orientacionPaquete'];
    $precio_paquetes      = $_POST['precioPaquete'];
    $descripcion_paquetes = $_POST['descripcionPaquete'];
    $counters = $_POST['counter'];

    // $pertenece_a_tuxtla   = cleanStr($_POST['perteneceATuxtla']);
    // $id_municipio         = $tipo_proveedor == 'Salon' && $pertenece_a_tuxtla == 'No' ? cleanStr($_POST['idMunicipio']) : '';

    $estado               = cleanStr($_POST['estado']);
    $ciudad               = cleanStr($_POST['ciudad']);

    if ($tipo_proveedor == 'Salon') {
      if ($capacidad == '' || $capacidad_max == '') {
        $response = array(
          'state'   => 'warning',
          'title'   => '¡Cuidado!',
          'message' => '¡Aun hay campos vacíos!'
        );

        echo json_encode($response);
        mysqli_close($mysqli);
        return;
      }

      /* if (count($servicios) == 0 || count($amenidades) == 0) {
        $response = array(
          'state'   => 'error',
          'title'   => '¡Error!',
          'message' => 'Aun no ha definido los servicios y/o amenidades'
        );

        echo json_encode($response);
        mysqli_close($mysqli);
        return;
      } */

      /* if ($pertenece_a_tuxtla == 'No' && $id_municipio == '') {
        $response = array(
          'state'   => 'error',
          'title'   => '¡Error!',
          'message' => 'Seleccione el municipio en el que se localiza su negocio.'
        );

        echo json_encode($response);
        return;
      } */
    }

    $extensions           = array('jpeg', 'jpg', 'png', 'JPEG', 'JPG', 'PNG');
    $file_folder          = $images_location . 'salones/';

    $query_imagen         = "";
    $query_imagen_insert  = "";

    if ($_FILES['ImagenSalon']['name']) {
      $imagen_salon = processOptimizedImage($_FILES['ImagenSalon'], $extensions, $file_folder);

      if ($imagen_salon == 'no-valid') {
        $response = array(
          'state'   => 'warning',
          'title'   => '¡Cuidado!',
          'message' => 'La imagen del negocio que intenta subir, no es valida.'
        );

        echo json_encode($response);
        mysqli_close($mysqli);
        return;
      }

      if ($imagen_salon == 'no-move') {
        $response = array(
          'state'   => 'error',
          'title'   => '¡Aviso!',
          'message' => 'La imagen del negocio no pudo moverse al servidor, intentelo mas tarde.'
        );

        echo json_encode($response);
        mysqli_close($mysqli);
        return;
      }

      $query_imagen .= ", Imagen";
      $query_imagen_insert .= ", '$imagen_salon[name]'";
    }

    # Agregar Negocio
    $negocio      = cleanStr($_POST['negocio']);
    $descripcion  = base64_encode($_POST['descripcion']);
    $celular_negocio  = cleanStr($_POST['celularNegocio']);
    $telefono_negocio     = cleanStr($_POST['telefonoNegocio']);
    $facebook     = $_POST['facebook'];
    $instagram    = $_POST['instagram'];
    $tipo         = $tipo_proveedor;

    $query = 'INSERT INTO salones (
          idUsuario, 
          idTipoProveedor,
          Capacidad,
          CapacidadMaxima,
          Facebook,
          Instagram,
          Salon, 
          Descripcion, 
          CostoRenta, 
          Latitud, 
          Longitud,
          idEstado,
          idCiudad,
          Tipo,
          Telefono,
          Celular,
          Direccion
          ' . $query_imagen . ') 
      VALUES (
          "' . $id_usuario . '",
          "' . $id_tipo_proveedor . '",
          "' . $capacidad . '",
          "' . $capacidad_max . '",
          "' . $facebook . '",
          "' . $instagram . '",
          "' . $negocio . '", 
          "' . $descripcion . '", 
          "' . $costo . '",
          "' . $latitud . '", 
          "' . $longitud . '", 
          "' . $estado . '",
          "' . $ciudad . '",
          "' . $tipo . '",
          "' . $telefono_negocio . '",
          "' . $celular_negocio . '",
          "' . $direccion . '" 
          ' . $query_imagen_insert . '
    )';

    $query_result = mysqli_query($mysqli, $query);

    if (!$query_result) {
      $response = array(
        'state'   => 'error',
        'title'   => '¡Error!',
        'message' => '¡Error!, Intentelo nuevamente.'
      );
    }

    if ($query_result) {
      $id_salon = mysqli_insert_id($mysqli);

      foreach ($tipos_eventos as $key => $value) {
        $id_tipo_evento = $tipos_eventos[$key];

        $query = "INSERT INTO catalogo_salon_tipos_eventos (idSalon, idTipoEvento) VALUES (
              '$id_salon', '$id_tipo_evento'
          )";

        mysqli_query($mysqli, $query);
      }

      foreach ($servicios as $key => $value) {
        $id_servicio = $servicios[$key];

        $query = "INSERT INTO catalogo_salon_servicios (idSalon, idServicio) VALUES (
              '$id_salon', '$id_servicio'
          )";

        mysqli_query($mysqli, $query);
      }

      foreach ($amenidades as $key => $value) {
        $id_amenidad = $amenidades[$key];

        $query = "INSERT INTO catalogo_salon_amenidades (idSalon, idAmenidad) VALUES (
              '$id_salon', '$id_amenidad'
          )";

        mysqli_query($mysqli, $query);
      }

      foreach ($nombre_paquetes as $key => $value) {
        $nombre_paquete       = cleanStr($nombre_paquetes[$key]);
        $orientacion_paquete  = cleanStr($orientacion_paquetes[$key]);
        $precio_paquete       = cleanStr($precio_paquetes[$key]);
        $descripcion_paquete  = base64_encode($descripcion_paquetes[$key]);
        $counter              = $counters[$key];
        $tipo_evento_paquetes = $_POST["tipoEventoPaquete$counter"];

        $query = "INSERT INTO paquetes_negocios (
            idNegocio,
            Paquete,
            Descripcion,
            Precio,
            Orientacion
          ) VALUES (
            '$id_salon',
            '$nombre_paquete',
            '$descripcion_paquete',
            '$precio_paquete',
            '$orientacion_paquete'
          )
        ";

        $query_result_paquete = mysqli_query($mysqli, $query);
        $id_paquete = mysqli_insert_id($mysqli);

        foreach ($tipo_evento_paquetes as $key2 => $value2) {
          $tipo_evento_paquete = cleanStr($tipo_evento_paquetes[$key2]);

          $query_tep = "INSERT INTO catalogo_paquete_tipos_eventos (
              idNegocio,
              idPaquete,
              idTipoEvento
            ) VALUES (
              '$id_salon',
              '$id_paquete',
              '$tipo_evento_paquete'
            )
          ";

          mysqli_query($mysqli, $query_tep);
        }
      }

      $galeria_imagenes  = $_FILES['Galeria'];

      $file_folder            = $images_location . 'salones/galeria/';
      $valid_extensions       = array('jpeg', 'jpg', 'png', 'JPEG', 'JPG', 'PNG');

      $move_files = true;
      $valid_files = true;

      foreach ($galeria_imagenes['tmp_name'] as $key => $value) {
        if ($galeria_imagenes['name'][$key]) {
          $imagen_galeria = processMultipleOptimizedImage($galeria_imagenes, $valid_extensions, $file_folder, $key);

          if ($imagen_galeria == 'no-move') $move_files = false;
          if ($imagen_galeria == 'no-valid') $valid_files = false;

          if ($imagen_galeria != 'no-move' && $imagen_galeria != 'no-valid') {
            $query = "INSERT INTO galeria (idSalon, Imagen) VALUES (
                      '$id_salon', '$imagen_galeria'
                  )";

            mysqli_query($mysqli, $query);
          }
        }
      }

      if (!$move_files) {
        $response = array(
          'state'   => 'success',
          'title'   => '¡Aviso!',
          'message' => "Algunas imagenes no pudieron moverse, verifique en el apartado de editar salón."
        );
      }
      if (!$valid_files) {
        $response = array(
          'state'   => 'success',
          'title'   => '¡Aviso!',
          'message' => "Algunas imagenes no son validos, verifique en el apartado de editar salón."
        );
      }

      if ($move_files && $valid_files) {
        $response = array(
          'state'   => 'success',
          'title'   => '¡Datos guardados!',
          'message' => 'El negocio "' . $_POST['negocio'] . '" ha sido agregado correctamente.'
        );
      }
    }
    break;

  case 'edit_business':
    $id_usuario = $_SESSION['session_user_id'];

    if ($_SESSION['session_user_level'] != 'Usuario') {
      $id_usuario = (isset($_POST['userId']) && $_POST['userId'] != '') ? $_POST['userId'] : '';

      if ($id_usuario == '') {
        $response = array(
          'state'   => 'error',
          'title'   => '¡Error!',
          'message' => 'Seleccione a que usuario pertenece el negocio.'
        );

        echo json_encode($response);
        return;
      }
    }

    $id_tipo_proveedor  = cleanStr($_POST['tipoProveedor']);
    $id_salon  = cleanStr($_POST['idSalon']);
    $tipos_eventos      = $_POST['tipoEvento'];

    if ($id_tipo_proveedor == '' || !count($tipos_eventos)) {
      $response = array(
        'state'   => 'error',
        'title'   => '¡Aviso!',
        'message' => 'Verifique que el tipo de proveedor y los tipos de eventos esten definidos.'
      );

      echo json_encode($response);
      mysqli_close($mysqli);
      return;
    }

    $query = "SELECT Tipo FROM tipo_proveedores WHERE idTipoProveedor = '$id_tipo_proveedor' LIMIT 1";
    $query_result = mysqli_query($mysqli, $query);

    $row            = mysqli_fetch_array($query_result);
    $tipo_proveedor = $row['Tipo'];

    $costo          = $tipo_proveedor == 'Salon' ? cleanStr($_POST['costo']) : '';
    $capacidad      = $tipo_proveedor == 'Salon' ? cleanStr($_POST['capacidad']) : '';
    $capacidad_max  = $tipo_proveedor == 'Salon' ? cleanStr($_POST['capacidadMaxima']) : '';
    $orientacion    = $tipo_proveedor == 'Salon' ? cleanStr($_POST['orientacion']) : '';

    $latitud        = cleanStr($_POST['latitud']);
    $longitud       = cleanStr($_POST['longitud']);
    $direccion      = cleanStr($_POST['direccion']);

    $servicios      = $_POST['servicios'];
    $amenidades     = $_POST['amenidades'];

    $id_paquetes          = $_POST['idPaquete'];
    $nombre_paquetes      = $_POST['nombrePaquete'];
    $orientacion_paquetes = $_POST['orientacionPaquete'];
    $precio_paquetes      = $_POST['precioPaquete'];
    $descripcion_paquetes = $_POST['descripcionPaquete'];
    $counters = $_POST['counter'];

    // $pertenece_a_tuxtla   = cleanStr($_POST['perteneceATuxtla']);
    // $id_municipio         = $tipo_proveedor == 'Salon' && $pertenece_a_tuxtla == 'No' ? cleanStr($_POST['idMunicipio']) : '';

    $estado               = cleanStr($_POST['estado']);
    $ciudad               = cleanStr($_POST['ciudad']);

    if ($tipo_proveedor == 'Salon') {
      if ($capacidad == '' || $capacidad_max == '') {
        $response = array(
          'state'   => 'error',
          'title'   => '¡Aviso!',
          'message' => '¡Aun hay campos vacíos!'
        );

        echo json_encode($response);
        mysqli_close($mysqli);
        return;
      }

      /* if (count($servicios) == 0 || count($amenidades) == 0) {
        $response = array(
          'state'   => 'error',
          'title'   => '¡Error!',
          'message' => 'Aun no ha definido los servicios y/o amenidades'
        );

        echo json_encode($response);
        mysqli_close($mysqli);
        return;
      } */

      /* if ($pertenece_a_tuxtla == 'No' && $id_municipio == '') {
        $response = array(
          'state'   => 'error',
          'title'   => '¡Aviso!',
          'message' => 'Seleccione el municipio en el que se localiza su negocio.'
        );

        echo json_encode($response);
        return;
      } */
    }

    $extensions           = array('jpeg', 'jpg', 'png', 'JPEG', 'JPG', 'PNG');
    $file_folder          = $images_location . 'salones/';

    $query_imagen         = "";

    if ($_FILES['ImagenSalon']['name']) {
      $imagen_salon = processOptimizedImage($_FILES['ImagenSalon'], $extensions, $file_folder);

      if ($imagen_salon == 'no-valid') {
        $response = array(
          'state'   => 'warning',
          'title'   => '¡Aviso!',
          'message' => 'La imagen del negocio que intenta subir, no es valida.'
        );

        echo json_encode($response);
        mysqli_close($mysqli);
        return;
      }

      if ($imagen_salon == 'no-move') {
        $response = array(
          'state'   => 'error',
          'title'   => '¡Error!',
          'message' => 'La imagen del negocio no pudo moverse al servidor, intentelo mas tarde.'
        );

        echo json_encode($response);
        mysqli_close($mysqli);
        return;
      }

      $query_imagen .= ", Imagen = '$imagen_salon[name]'";
    }

    # Actualizar Negocio
    $negocio      = cleanStr($_POST['negocio']);
    $descripcion  = base64_encode($_POST['descripcion']);
    $facebook     = $_POST['facebook'];
    $instagram    = $_POST['instagram'];
    $tipo         = $tipo_proveedor;
    $celular_negocio  = cleanStr($_POST['celularNegocio']);
    $telefono_negocio     = cleanStr($_POST['telefonoNegocio']);

    $query = "UPDATE salones SET
          idUsuario         = '$id_usuario',
          idTipoProveedor   = '$id_tipo_proveedor',
          Capacidad         = '$capacidad',
          CapacidadMaxima   = '$capacidad_max',
          Facebook          = '$facebook',
          Instagram         = '$instagram',
          Salon             = '$negocio', 
          Descripcion       = '$descripcion', 
          CostoRenta        = '$costo',
          Latitud           = '$latitud',
          Longitud          = '$longitud',
          idEstado          = '$estado',
          idCiudad          = '$ciudad',
          PerteneceATuxtla  = '$pertenece_a_tuxtla',
          Tipo              = '$tipo',
          Telefono          = '$telefono_negocio',
          Celular           = '$celular_negocio',
          Direccion         = '$direccion'
          $query_imagen
        WHERE idSalon = '$id_salon'
    ";

    $query_result = mysqli_query($mysqli, $query);

    if (!$query_result) {
      $response = array(
        'state'   => 'error',
        'title'   => '¡Aviso!',
        'message' => '¡Error!, Intentelo nuevamente.'
      );
    }

    if ($query_result) {
      //$id_salon = mysqli_insert_id($mysqli);
      foreach ($tipos_eventos as $key => $value) {
        $id_tipo_evento = $tipos_eventos[$key];

        $query = "SELECT idTipoEvento, idSalon FROM catalogo_salon_tipos_eventos WHERE idTipoEvento = '$id_tipo_evento' AND idSalon ='$id_salon' LIMIT 1";
        $query_result = mysqli_query($mysqli, $query);
        $num_rows = mysqli_num_rows($query_result);

        if (!$num_rows) {
          $query = "REPLACE INTO catalogo_salon_tipos_eventos SET
                    idSalon = '$id_salon',
                    idTipoEvento = '$id_tipo_evento'
          ";

          mysqli_query($mysqli, $query);
        }
      }

      foreach ($servicios as $key => $value) {
        $id_servicio = $servicios[$key];

        $query = "SELECT idServicio, idSalon FROM catalogo_salon_servicios WHERE idServicio = '$id_servicio' AND idSalon ='$id_salon' LIMIT 1";
        $query_result = mysqli_query($mysqli, $query);
        $num_rows = mysqli_num_rows($query_result);

        if (!$num_rows) {
          $query = "REPLACE INTO catalogo_salon_servicios SET
                    idSalon = '$id_salon',
                    idServicio = '$id_servicio'
            ";

          mysqli_query($mysqli, $query);
        }
      }

      foreach ($amenidades as $key => $value) {
        $id_amenidad = $amenidades[$key];

        $query = "SELECT idAmenidad, idSalon FROM catalogo_salon_amenidades WHERE idAmenidad = '$id_amenidad' AND idSalon ='$id_salon' LIMIT 1";
        $query_result = mysqli_query($mysqli, $query);
        $num_rows = mysqli_num_rows($query_result);

        if (!$num_rows) {
          $query = "REPLACE INTO catalogo_salon_amenidades SET
                    idSalon = '$id_salon',
                    idAmenidad = '$id_amenidad'
            ";

          mysqli_query($mysqli, $query);
        }
      }

      foreach ($nombre_paquetes as $key => $value) {
        $id_paquete           = cleanStr(($id_paquetes[$key]));
        $nombre_paquete       = cleanStr($nombre_paquetes[$key]);
        $orientacion_paquete  = cleanStr($orientacion_paquetes[$key]);
        $precio_paquete       = cleanStr($precio_paquetes[$key]);
        $descripcion_paquete  = base64_encode($descripcion_paquetes[$key]);

        $counter = $counters[$key];
        $tipo_evento_paquetes = $_POST["tipoEventoPaquete$counter"];

        if (!$id_paquete) {
          $query = "INSERT INTO paquetes_negocios (
              idNegocio,
              Paquete,
              Descripcion,
              Orientacion,
              Precio
            ) VALUES (
              '$id_salon',
              '$nombre_paquete',
              '$descripcion_paquete',
              '$orientacion_paquete',
              '$precio_paquete'
            )
          ";

          $query_result_in = mysqli_query($mysqli, $query);
          $id_paquete = mysqli_insert_id($mysqli);

          foreach ($tipo_evento_paquetes as $key2 => $value2) {
            $tipo_evento_paquete = cleanStr($tipo_evento_paquetes[$key2]);

            $query_tep = "INSERT INTO catalogo_paquete_tipos_eventos (
                idNegocio,
                idPaquete,
                idTipoEvento
              ) VALUES (
                '$id_salon',
                '$id_paquete',
                '$tipo_evento_paquete'
              )
            ";

            mysqli_query($mysqli, $query_tep);
          }
        } else {
          $query = "REPLACE INTO paquetes_negocios SET
            idPaquete   = '$id_paquete',
            idNegocio   = '$id_salon',
            Paquete     = '$nombre_paquete',
            Descripcion = '$descripcion_paquete',
            Orientacion = '$orientacion_paquete',
            Precio      = '$precio_paquete'
          ";

          mysqli_query($mysqli, $query);

          $query_delete = "DELETE FROM catalogo_paquete_tipos_eventos WHERE idPaquete = '$id_paquete'";
          mysqli_query($mysqli, $query_delete);

          foreach ($tipo_evento_paquetes as $key2 => $value2) {
            $tipo_evento_paquete = cleanStr($tipo_evento_paquetes[$key2]);

            $query_tep = "INSERT INTO catalogo_paquete_tipos_eventos (
              idNegocio,
              idPaquete,
              idTipoEvento
            ) VALUES (
              '$id_salon',
              '$id_paquete',
              '$tipo_evento_paquete'
            )
          ";

            mysqli_query($mysqli, $query_tep);
          }
        }
      }

      $galeria_imagenes  = $_FILES['Galeria'];

      $file_folder            = $images_location . 'salones/galeria/';
      $valid_extensions       = array('jpeg', 'jpg', 'png', 'JPEG', 'JPG', 'PNG');

      $move_files = true;
      $valid_files = true;

      foreach ($galeria_imagenes['tmp_name'] as $key => $value) {
        if ($galeria_imagenes['name'][$key]) {
          $imagen_galeria = processMultipleOptimizedImage($galeria_imagenes, $valid_extensions, $file_folder, $key);

          if ($imagen_galeria == 'no-move') $move_files = false;
          if ($imagen_galeria == 'no-valid') $valid_files = false;

          if ($imagen_galeria != 'no-move' && $imagen_galeria != 'no-valid') {
            $query = "INSERT INTO galeria (idSalon, Imagen) VALUES (
                      '$id_salon', '$imagen_galeria'
                  )";

            mysqli_query($mysqli, $query);
          }
        }
      }

      if (!$move_files) {
        $response = array('state' => 'success', 'title' => '¡Aviso!', 'message' => "Algunas imagenes no pudieron moverse, verifique en el apartado de editar salón.");
      }
      if (!$valid_files) {
        $response = array('state' => 'success', 'title' => '¡Aviso!', 'message' => "Algunas imagenes no son validos, verifique en el apartado de editar salón.");
      }

      if ($move_files && $valid_files) {
        $response = array('state' => 'success', 'title' => '¡Datos guardados!', 'message' => 'El negocio se actualizó correctamente.');
      }
    }
    break;

  case 'delete_business':
    $id_salon = cleanStr($_POST['idSalon']);
    $id_user_create = $_SESSION['session_user_id'];

    $query = "SELECT
        idSalon
      FROM salones
      WHERE
        idSalon   = '$id_salon' AND
        idUsuario = '$id_user_create'
    ";

    $query_result = mysqli_query($mysqli, $query);
    $num_rows     = mysqli_num_rows($query_result);

    if (!$num_rows) {
      $response = array(
        'state' => 'error',
        'title' => '¡Error!, Intentelo nuevamente.'
      );

      echo json_encode($response);
      mysqli_close($mysqli);
      die();
    }

    $query = "UPDATE salones SET Status = 'Eliminado' WHERE
        idSalon   = '$id_salon' AND
        idUsuario = '$id_user_create'
    ";

    $query_result = mysqli_query($mysqli, $query);

    if (!$query_result) {
      $response = array('state' => 'error', 'title' => '¡Error!, Intentelo nuevamente.');
    }

    if ($query_result) {
      mysqli_query($mysqli, "DELETE FROM usuarios WHERE idNegocio = $id_salon");
      if ($id_salon == $_SESSION['session_business_id']) $_SESSION['session_business_id'] = null;

      $response = array('state' => 'success', 'title' => 'Negocio eliminado correctamente.');

      /* $query = "DELETE FROM catalogo_salon_servicios WHERE idSalon = '$id_salon'";
      mysqli_query($mysqli, $query);

      $query = "DELETE FROM catalogo_salon_tipos_eventos WHERE idSalon = '$id_salon'";
      mysqli_query($mysqli, $query);

      $query = "DELETE FROM catalogo_salon_amenidades WHERE idSalon = '$id_salon'";
      mysqli_query($mysqli, $query);

      $query = "DELETE FROM paquetes_negocios WHERE idNegocio = '$id_salon'";
      mysqli_query($mysqli, $query);

      $query = "DELETE FROM catalogo_paquete_tipos_eventos WHERE idNegocio = '$id_salon'";
      mysqli_query($mysqli, $query);

      $query = "DELETE FROM calendario_fechas WHERE idNegocio = '$id_salon'";
      mysqli_query($mysqli, $query);

      $query = "SELECT idSalon, Imagen FROM salones WHERE idSalon = '$id_salon' LIMIT 1";
      $query_result = mysqli_query($mysqli, $query);

      $row = mysqli_fetch_array($query_result);
      $imagen = $row['Imagen'];
      $file_location = $images_location . 'salones/' . $imagen;

      deleteFile($file_location);

      $query = "SELECT idGaleria, idSalon, Imagen FROM galeria WHERE idSalon = '$id_salon'";
      $query_result = mysqli_query($mysqli, $query);
      $num_rows = mysqli_num_rows($query_result);

      if ($num_rows) {
        while ($row = mysqli_fetch_array($query_result)) {
          $imagen = $row['Imagen'];
          $file_location = $images_location . 'salones/galeria/' . $imagen;

          deleteFile($file_location);
        }
      } */
    }
    break;

  case 'delete_principal_image':
    $business_id = cleanStr($_POST['idSalon']);

    $query = "SELECT Imagen FROM salones WHERE idSalon = '$business_id' LIMIT 1";
    $query_result = mysqli_query($mysqli, $query);

    $row = mysqli_fetch_array($query_result);

    $principal_image = $row['Imagen'];
    $file_location    = $images_location . 'salones/' . $principal_image;

    $delete_principal_image = deleteFile($file_location);

    if ($delete_principal_image === 'not-deleted') {
      $response = array(
        'state'   => 'error',
        'title' => 'No se puede remover la imagen "' . $principal_image . '", intentelo nuevamente.'
      );
    }

    if ($delete_principal_image === 'deleted' || $delete_principal_image === 'not-exist') {
      $query = "UPDATE salones SET Imagen = '' WHERE idSalon = '$business_id'";
      $query_result = mysqli_query($mysqli, $query);

      if (!$query_result) {
        $response = array('state' => 'error', 'title' => '¡Error!, Intentelo nuevamente.');
      }

      if ($query_result) {
        $response = array('state' => 'success', 'title' => 'La imagen "' . $principal_image . '" ha sido eliminado correctamente.');
      }
    }
    break;

  case 'eliminar_tipo_evento':
    $id_tipo_evento = $_POST['idTipoEvento'];
    $id_salon = $_POST['idSalon'];

    $query = "DELETE FROM catalogo_salon_tipos_eventos WHERE idTipoEvento = '$id_tipo_evento' AND idSalon = '$id_salon'";
    $query_result = mysqli_query($mysqli, $query);

    if ($query_result) {
      $response = array(
        'state' => 'success',
        'title' => 'El servicio se removió con exito. '
      );
    }

    if (!$query_result) {
      $response = array(
        'state' => 'error',
        'title' => '¡Error!, Intentelo nuevamente.'
      );
    }
    break;

  case 'eliminar_servicio':
    $id_servicio = $_POST['idServicio'];
    $id_salon = $_POST['idSalon'];

    $query = "DELETE FROM catalogo_salon_servicios WHERE idServicio = '$id_servicio' AND idSalon = '$id_salon'";
    $query_result = mysqli_query($mysqli, $query);

    if ($query_result) {
      $response = array(
        'state' => 'success',
        'title' => 'El servicio se removió con exito.'
      );
    }

    if (!$query_result) {
      $response = array(
        'state' => 'error',
        'title' => '¡Error!, Intentelo nuevamente.'
      );
    }
    break;

  case 'eliminar_amenidad':
    $id_amenidad = $_POST['idAmenidad'];
    $id_salon = $_POST['idSalon'];

    $query = "DELETE FROM catalogo_salon_amenidades WHERE idAmenidad = '$id_amenidad' AND idSalon = '$id_salon'";
    $query_result = mysqli_query($mysqli, $query);

    if ($query_result) {
      $response = array(
        'state' => 'success',
        'title' => 'La amenidad se removió con exito.'
      );
    }

    if (!$query_result) {
      $response = array(
        'state' => 'error',
        'title' => '¡Error!, Intentelo nuevamente.'
      );
    }
    break;

  case 'obtener_coordenadas':
    $id_municipio = $_POST['idMunicipio'];

    $query = "SELECT idMunicipio, Municipio, Latitud, Longitud FROM municipios WHERE idMunicipio = '$id_municipio' LIMIT 1";
    $query_result = mysqli_query($mysqli, $query);
    $num_rows = mysqli_num_rows($query_result);

    if ($num_rows) {
      while ($row = mysqli_fetch_array($query_result)) {
        $coordenadas = array(
          "latitude" => floatval($row['Latitud']),
          "longitude" => floatval($row['Longitud'])
        );
      }

      $response = $coordenadas;
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
