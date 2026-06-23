<?php
include '../lib/public-session.php';
include '../lib/pagination.php';

$response = array(
  'status'  => 'error',
  'title'   => '¡Error!',
  'message' => 'Error inesperado, Intentalo nuevamente.',
  'content' => 'Error inesperado, Intentalo nuevamente.'
);

$action = $_POST['action'];
$valid_extensions = array('jpeg', 'jpg', 'png', 'JPEG', 'JPG', 'PNG', 'octet-stream');

switch ($action) {
  case 'load_businesses':
    try {
      $page                     = cleanStr($_POST['page']);
      $page                     = $page != '' ? $page : 1;

      $per_page                 = cleanStr($_POST['perPage'] ?? ''); // cleanStr($_POST['perPage']);
      $per_page                 = $per_page != '' ? $per_page : 12;

      $start_rows               = ($page - 1) * $per_page;
      $stop_rows                = $per_page;

      $search_term              = cleanStr($_POST['searchTerm']);
      $search_by_term           = $search_term != '' ? "
          S.Salon LIKE '%$search_term%' OR
          TP.TipoProveedor LIKE '%$search_term%'
      " : "1=1";

      $event_type               = cleanStr($_POST['eventTypeId']);
      $event_type_slug          = getEventTypeSlugById($event_type);
      $search_by_event_type     = $event_type != '' ? "cTE.idTipoEvento = $event_type" : "1=1";

      $event_types              = filterAndCleanArray($_POST['eventType'] ?? '');
      $search_by_event_types    = $event_types != '' ? "cTE.idTipoEvento IN ($event_types)" : "1=1";

      $supplier_type            = cleanStr($_POST['supplierType'] ?? '');
      $supplier_type_slug       = getSupplierTypeSlugById($supplier_type);
      $search_by_supplier_type  = $supplier_type != '' ? "S.idTipoProveedor = $supplier_type" : "1=1";

      $availability             = parseDate($_POST['date']);
      $search_by_availability   = $availability != '' ? "
          IF (EXISTS(SELECT
            idCalendarioFecha
            FROM calendario_fechas
            WHERE
                idNegocio   = S.idSalon       AND
                Fecha       = '$availability' AND
                DateStatus  = 'Ocupado'
          ), 0, 1) = 1
      " : "1=1";

      $capacity = checkArray($_POST['capacity'] ?? '');
      $search_by_capacity = "1=1";

      if ($capacity) :
        $search_by_capacity = "";

        foreach ($capacity as $key => $row) :
          $value = json_decode($row);
          $desde = $value[0];
          $hasta = $value[1];

          $search_by_capacity .= $key > 0 ? " OR " : "";
          $search_by_capacity .= $hasta != 0 ? "(S.Capacidad BETWEEN $desde AND $hasta)" : "(S.Capacidad > $desde)";
        endforeach;
      endif;

      $modality           = cleanStr($_POST['modality']);
      $search_by_modality = "P.Orientacion = '$modality'";
      $price_for_person   = checkArray($_POST['priceForPerson'] ?? '');
      $price_for_rent     = checkArray($_POST['priceForRent'] ?? '');
      $search_by_price    = "1=1";

      if ($modality == 'Por persona' && $price_for_person) :
        $search_by_price = "($search_by_modality) AND (";

        foreach ($price_for_person as $key => $row) :
          $value = json_decode($row);
          $desde = $value[0];
          $hasta = $value[1];

          $search_by_price .= $key > 0 ? " OR " : "";
          $search_by_price .= $hasta != 0 ? "(P.Precio BETWEEN $desde AND $hasta)" : "(P.Precio > $desde)";
        endforeach;

        $search_by_price .= ")";
      endif;

      if ($modality == 'Por evento' && $price_for_rent) :
        $search_by_price = "($search_by_modality) AND (";

        foreach ($price_for_rent as $key => $row) :
          $value = json_decode($row);
          $desde = $value[0];
          $hasta = $value[1];

          $search_by_price .= $key > 0 ? " OR " : "";
          $search_by_price .= $hasta != 0 ? "(P.Precio BETWEEN $desde AND $hasta)" : "(P.Precio > $desde)";
        endforeach;

        $search_by_price .= ")";
      endif;

      $services                 = filterAndCleanArray($_POST['services'] ?? '');
      $search_by_services       = $services != '' ? "CS.idServicio IN ($services)" : "1=1";

      $amenities                = filterAndCleanArray($_POST['amenities'] ?? '');
      $search_by_amenities      = $amenities != '' ? "CA.idAmenidad IN ($amenities)" : "1=1";

      $state                    = cleanStr($_POST['state']);
      $search_by_state          = $state != '' ? "S.idEstado = '$state'" : "1=1";

      $city                     = cleanStr($_POST['city']);
      $search_by_city           = $city != '' ? "S.idCiudad = '$city'" : "1=1";

      $c_from                   = "FROM salones AS S";

      $c_left_join = "
        LEFT JOIN tipo_proveedores              AS TP   ON (S.idTipoProveedor = TP.idTipoProveedor)
        LEFT JOIN catalogo_salon_tipos_eventos  AS cTE  ON (S.idSalon  = cTE.idSalon)
        LEFT JOIN catalogo_salon_servicios      AS CS   ON (S.idSalon   = CS.idSalon)
        LEFT JOIN catalogo_salon_amenidades     AS CA   ON (S.idSalon   = CA.idSalon)
        LEFT JOIN paquetes_negocios             AS P    ON (S.idSalon   = P.idNegocio)
        LEFT JOIN calendario_fechas             AS CF   ON (S.idSalon   = CF.idNegocio)
      ";

      $c_where = "WHERE
          ($search_by_term)           AND
          ($search_by_event_type)     AND
          ($search_by_event_types)    AND
          ($search_by_supplier_type)  AND
          ($search_by_availability)   AND
          ($search_by_capacity)       AND
          ($search_by_price)          AND
          ($search_by_services)       AND
          ($search_by_amenities)      AND
          ($search_by_state)          AND
          ($search_by_city)           AND
          (Status = 'Activo')
        GROUP BY S.idSalon
        ORDER BY S.Visitas
        DESC
      ";

      $limit_rows         = "LIMIT $start_rows, $stop_rows";

      $query_count        = "SELECT S.idSalon $c_from $c_left_join $c_where";

      /* $content = base64_encode($query_count);

      $response = array(
        'status'      => 'success',
        'content'     => $content,
        'pagination'  => '',
        'results'     => '0'
      );

      echo json_encode($response);
      return; */

      $query_result_count = mysqli_query($mysqli, $query_count);
      $total_businesses   = mysqli_num_rows($query_result_count);
      $num_pages          = ceil($total_businesses / $stop_rows);

      if (!$num_pages) :
        ob_start();
        echo '
            <div class="no-results">
              <ion-icon name="alert-circle-outline"></ion-icon>
              ¡No se encontraron resultados!
            </div>
        ';
        $content = base64_encode(ob_get_clean());

        $response = array(
          'status'      => 'success',
          'content'     => $content,
          'pagination'  => '',
          'results'     => '0'
        );
      endif;

      if ($num_pages) :
        $query = "SELECT
            S.idSalon,
            S.idUsuario,
            S.idTipoProveedor,
            S.Salon,
            S.Descripcion,
            S.CostoRenta,
            S.Capacidad,
            S.CapacidadMaxima,
            S.Latitud,
            S.Longitud,
            S.Direccion,
            S.Imagen,
            S.Referencia,
            S.slug,
            S.Visitas,
            P.Precio,
            cTE.idTipoEvento,
            CF.Fecha,
            CF.DateStatus
          $c_from
          $c_left_join
          $c_where
          $limit_rows
        ";

        $query_result = mysqli_query($mysqli, $query);

        ob_start();

        while ($row = mysqli_fetch_array($query_result)) :
          $business_item_data               = base64_encode(json_encode($row));
          $business_item_name               = $row['Salon'];
          $business_item_address            = $row['Direccion'];
          $business_item_price              = $row['Precio'];
          $business_item_capacity           = $row['Capacidad'];
          $business_item_max_capacity       = $row['CapacidadMaxima'];
          $business_item_slug               = $row['slug'] . '-' . $row['Referencia'];
          $business_item_img                = setBusinessImage($row['Imagen']);
          $business_item_event_type_slug    = $event_type_slug    ? $event_type_slug    : getEventTypeSlugById($row['idTipoEvento']);
          $business_item_supplier_type_slug = $supplier_type_slug ? $supplier_type_slug : getSupplierTypeSlugById($row['idTipoProveedor']);
          $business_item_url                = BASE_URL . '/'  . $business_item_slug;

          include '../../src/components/business-item.php';
        endwhile;

        $content = base64_encode(ob_get_clean());

        $pagination = paginate($page, $num_pages, 2, 'loadBusinesses');

        $response = array(
          'status'      => 'success',
          'content'     => $content,
          'pagination'  => $pagination,
          'results'     => $total_businesses
        );
      endif;
    } catch (Exception $e) {
      $response['message'] = $e->getMessage();
    }
    break;

  case 'add_business':
    try {
      /* ::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::
      //-- BUSINESS VALUES
      :::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::: */
      $user_id = $_SESSION['session_user_id'];

      # STEP 1 //-- Tipo de proveedor
      $supplier_type = cleanStr($_POST['supplierType']);

      # STEP 2 //-- Tipo de eventos
      $event_types = $_POST['eventType'];

      # STEP 3 //-- Información del negocio
      $business_name        = cleanStr($_POST['businessName']);
      $min_capacity         = cleanStr($_POST['minCapacity']);
      $max_capacity         = cleanStr($_POST['maxCapacity']);
      $business_description = cleanStr($_POST['businessDescription']);
      $business_phone       = cleanStr($_POST['businessPhone']);
      $business_cell_phone  = cleanStr($_POST['businessCellPhone']);
      $business_facebook    = cleanStr($_POST['businessFacebook'], 'low');
      $business_instagram   = cleanStr($_POST['businessInstagram'], 'low');

      # STEP 4 //-- Paquetes
      $package_counter = $_POST['packageCounter'] ?? [];

      # STEP 5 //-- Ubicación del negocio
      $state      = cleanStr($_POST['state']);
      $city       = cleanStr($_POST['city']);
      $latitude   = cleanStr($_POST['latitude']);
      $longitude  = cleanStr($_POST['longitude']);
      $address    = cleanStr($_POST['address']);

      # STEP 6 //-- Servicios y amenidades
      $services   = $_POST['services'];
      $amenities  = $_POST['amenities'];

      # STEP 7 //-- Galría de imagenes
      $principal_image  = $_FILES['principalImage'];
      $business_logo    = $_FILES['businessLogo'];
      $gallery          = $_FILES['imageGallery'] ?? null;

      /* $response['message'] = $principal_image['type'];
      echo json_encode($response);
      die; */

      # SALON MODE
      $salon_mode     = $supplier_type == '1' ? true : false;
      $business_type  = $salon_mode ? 'Salon' : 'Otros';

      # CREAR SLUG Y REFERENCIA
      # $reference      = date('YmdHis'); //-- Remplazado por el id del negocio
      $business_slug  = createSlug($business_name);

      # PICTURES QUERY
      $query_picture        = "";
      $query_insert_picture = "";

      # AGREGAR LA IMAGEN DE PRINCIPAL
      if ($principal_image['name']) :
        $proccess_principal_image = processFile(
          $principal_image,
          $valid_extensions,
          BUSINESS_IMAGE_FOLDER,
          str_replace('-', '_', $business_slug)
        );

        if ($proccess_principal_image !== 'no-move' && $proccess_principal_image !== 'no-valid') :
          $query_picture         .= ", Imagen";
          $query_insert_picture  .= ", '$proccess_principal_image'";
        endif;
      endif;

      if ($business_logo['name']) :
        $proccess_business_logo = processFile(
          $business_logo,
          $valid_extensions,
          BUSINESS_IMAGE_FOLDER,
          str_replace('-', '_', $business_slug) . '_logo'
        );

        if ($proccess_business_logo !== 'no-move' && $proccess_business_logo !== 'no-valid') :
          $query_picture         .= ", Logo";
          $query_insert_picture  .= ", '$proccess_business_logo'";
        endif;
      endif;

      /* ::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::
      //-- AGREAGAR LA INFORMACIÓN EN LA TABLA DE SALONES
      :::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::: */
      #EXTRA QUERY PARA EL MODO SALON
      $query_salon_mode = $salon_mode ? "
        ,Capacidad,
        CapacidadMaxima
      " : "";

      $query_salon_mode_insert = $salon_mode ? "
        , '$min_capacity',
        '$max_capacity'
      " : "";

      # QUERY PARA INSERTAR EL NEGOCIO
      $query = "INSERT INTO salones (
          idUsuario,
          idTipoProveedor,
          Salon,
          Descripcion,
          Telefono,
          Celular,
          Facebook,
          Instagram,
          Tipo,
          idEstado,
          idCiudad,
          Direccion,
          Latitud,
          Longitud,
          slug
          $query_salon_mode
          $query_picture
        ) VALUES (
          $user_id,
          $supplier_type,
          '$business_name',
          '$business_description',
          '$business_phone',
          '$business_cell_phone',
          '$business_facebook',
          '$business_instagram',
          '$business_type',
          $state,
          $city,
          '$address',
          '$latitude',
          '$longitude',
          '$business_slug'
          $query_salon_mode_insert
          $query_insert_picture
        )
      ";

      /* $response['message'] = $query;
      echo json_encode($response);
      die; */

      $query_result = mysqli_query($mysqli, $query);

      $response['message'] = $_POST;

      if ($query_result) :
        # ID DEL NEGOCIO AGREGAO
        $business_id = mysqli_insert_id($mysqli);
        $reference    = createReferenceForBusinessSlug($business_id);

        /* ::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::
        //-- AGREGAR LA REFERENCIA DEL NEGOCIO
        :::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::: */
        $query_add_reference = "UPDATE salones SET Referencia = '$reference' WHERE idSalon = $business_id";
        mysqli_query($mysqli, $query_add_reference);

        /* ::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::
        //-- AGREGAR LOS TIPOS DE EVENTOS DEL NEGOCIO
        :::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::: */
        foreach ($event_types as $key => $value) :
          $event_type = cleanStr($value);

          $query_event_types = "INSERT INTO catalogo_salon_tipos_eventos (
              idSalon,
              idTipoEvento
            ) VALUES (
              '$business_id',
              '$event_type'
            )
          ";

          mysqli_query($mysqli, $query_event_types);
        endforeach;

        /* ::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::
        //-- AGREGAR LOS SERVICIOS Y AMENIDADES DEL NEGOCIO
        :::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::: */
        if ($salon_mode) :
          # SERVICIOS
          foreach ($services as $key => $value) :
            $service = cleanStr($value);

            $query_services = "INSERT INTO catalogo_salon_servicios (
                idSalon,
                idServicio
              ) VALUES (
                '$business_id',
                '$service'
              )
            ";

            mysqli_query($mysqli, $query_services);
          endforeach;

          # AMENIDADES
          foreach ($amenities as $key => $value) :
            $amenity = cleanStr($value);

            $query_amenities = "INSERT INTO catalogo_salon_amenidades (
                idSalon,
                idAmenidad
              ) VALUES (
                '$business_id',
                '$amenity'
              )
            ";

            mysqli_query($mysqli, $query_amenities);
          endforeach;
        endif;

        /* ::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::
        //-- AGREGAR LOS PAQUETES DEL NEGOCIO
        :::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::: */
        if (is_array($package_counter)) {
          foreach ($package_counter as $key => $value) :
            $counter = $value;

            $package_name         = cleanStr($_POST["packageName-$counter"]);
            $more_contracted      = $_POST["moreContracted-$counter"] === 'Si' ? cleanStr($_POST["moreContracted-$counter"]) : 'No';
            $modality             = cleanStr($_POST["modality-$counter"]);
            $price                = cleanStr($_POST["packagePrice-$counter"]);
            $package_event_types  = $_POST["package-$counter-eventType"];
            $package_description  = cleanStr($_POST["packageDescription-$counter"], 'html');

            $query_packages = "INSERT INTO paquetes_negocios (
                idNegocio,
                Paquete,
                Descripcion,
                Precio,
                Orientacion,
                MasContratado
              ) VALUES (
                '$business_id',
                '$package_name',
                '$package_description',
                '$price',
                '$modality',
                '$more_contracted'
              )
            ";

            $query_packages_result = mysqli_query($mysqli, $query_packages);

            # AGREGAR LOS TIPOS DE EVENTO QUE TIENE EL PAQUETE
            if ($query_packages_result) :
              $package_id = mysqli_insert_id($mysqli);

              foreach ($package_event_types as $key => $value) :
                $package_event_type = cleanStr($value);

                $query_package_event_types = "INSERT INTO catalogo_paquete_tipos_eventos (
                    idNegocio,
                    idPaquete,
                    idTipoEvento
                  ) VALUES (
                    '$business_id',
                    '$package_id',
                    '$package_event_type'
                  )
                ";

                mysqli_query($mysqli, $query_package_event_types);
              endforeach;
            endif;
          endforeach;
        }

        /* ::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::
        //-- AGREGAR LAS IMAGENES DE LA GALERÍA
        :::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::: */
        $image_gallery = processMultipleFiles(
          $gallery,
          $valid_extensions,
          BUSINESS_GALLERY_FOLDER,
          str_replace('-', '_', $business_slug)
        );

        foreach ($image_gallery as $key => $value) :
          $image = $image_gallery[$key];

          $query = "INSERT INTO galeria (
              idSalon,
              Imagen
            ) VALUES (
              $business_id,
              '$image'
            )
          ";

          mysqli_query($mysqli, $query);
        endforeach;

        $_SESSION['session_business_id'] = $business_id;

        /* ::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::
        //-- REGRESAR RESPUESTA
        :::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::: */
        $response = array(
          'status' => 'success',
          'title'   => '¡Datos guardados!',
          'message' => 'El negocio "' . $business_name . '" se registró correctamente, da click en continuar para redirigirte a tu panel de control.'
        );
      endif;
    } catch (Exception $e) {
      $response['message'] = $e->getMessage();
    }
    break;

  case 'edit-business':
    /* ::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::
    //-- BUSINESS VALUES
    :::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::: */
    break;

  case 'add-package':
    $num_package  = $_POST['numPackage'];
    $package      = getBusinessPackageItem($num_package, true);

    $response = array(
      'status'  => 'success',
      'content' => base64_encode($package)
    );
    break;
}

echo json_encode($response);
mysqli_close($mysqli);
die();
