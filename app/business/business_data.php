<?php
include '../inc/session.php';
date_default_timezone_set('America/Mexico_City');

$action = $_POST['action'] ? $_POST['action'] : $json['action'];

$response = array(
  'status'  => 'error',
  'title'   => '¡Error inesperado!',
  'message' => 'Intentalo nuevamente.'
);

$valid_extensions = array('jpeg', 'jpg', 'png', 'JPEG', 'JPG', 'PNG');

switch ($action) {
  case 'get-businesses':
    try {
      $parameters   = $json['parameters'];

      $user_id      = cleanStr($parameters['userId']);

      $page         = cleanStr($parameters['page']);
      $search_term  = cleanStr($parameters['searchTerm']);

      $per_page     = 15;

      $from         = "FROM salones";

      $search_by_term = $search_term != '' ? "
          (Salon LIKE '%$search_term%')
      " : "1=1";

      $where = "WHERE
          idUsuario = $user_id  AND
          ($search_by_term)     AND
          Status = 'Activo'
        ORDER BY idSalon DESC
      ";

      $start_rows = ($page - 1) * $per_page;
      $stop_rows  = $per_page;

      $limit_rows = "LIMIT $start_rows, $stop_rows";

      $query      = "SELECT COUNT(idSalon) AS Total $from $where LIMIT 1";
      $num_pages  = numPages($query, $stop_rows);

      if (!$num_pages) $response = array(
        'status' => 'empty'
      );

      if ($num_pages) {
        $query = "SELECT
            idSalon,
            idUsuario,
            Salon,
            Descripcion,
            Capacidad,
            CapacidadMaxima,
            Latitud,
            Longitud,
            Direccion,
            Imagen,
            idTipoProveedor,
            Tipo,
            Fecha,
            slug,
            Facebook,
            Instagram,
            Telefono,
            Celular,
            idEstado,
            idCiudad,
            Referencia,
            Logo
          $from
          $where
          $limit_rows
        ";

        $query_result = mysqli_query($mysqli, $query);

        $businesses = array();

        while ($business = mysqli_fetch_array($query_result)) :
          $business_id                = $business['idSalon'];

          $date                       = getDateWithMonthName($business['Fecha']);
          $principal_image            = IMAGES_URL . 'listing/' . $business['Imagen'];
          $logo                       = $business['Logo'] ? IMAGES_URL . 'listing/' . $business['Logo'] : '';

          $event_types                = getBusinessEventTypes($business_id);
          $packages                   = getBusinesspackages($business_id);
          $services                   = getBusinessServices($business_id);
          $amenities                  = getBusinessAmenities($business_id);
          $gallery                    = getBusinessGallery($business_id);

          $business['DateFormat']     = $date;
          $business['Imagen']         = $principal_image;
          $business['Logo']           = $logo;

          $business['eventTypes']     = $event_types;
          $business['packages']       = $packages;
          $business['services']       = $services;
          $business['amenities']      = $amenities;
          $business['gallery']        = $gallery;

          $business['TelefonoFormat'] = formatPhoneNumber($business['Telefono']);

          array_push($businesses, $business);
        endwhile;

        $response = array(
          'status'      => 'success',
          'totalPages'  => $num_pages,
          'businesses'  => $businesses
        );
      }
    } catch (Exception $e) {
      $response['message'] = $e->getMessage();
    }
    break;
  case 'add-business':
    try {
      $parameters = $json['parameters'];

      $user_id    = $parameters['userId'];
      $values     = $parameters['values'];

      /* ::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::
      //-- BUSINESS VALUES
      :::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::: */
      # SALON MODE
      $salon_mode     = $values['salonMode'];

      # TIPO DE PROVEEDOR.
      $supplier_type  = cleanStr($values['supplierType']);

      # TIPOS DE EVENTOS.
      $event_types    = $values['eventTypes'];

      # INFORMACIÓN DEL NEGOCIO.
      $business_name  = cleanStr($values['businessName']);
      $min_capacity   = cleanStr($values['minCapacity']); //-- MODO SALON
      $max_capacity   = cleanStr($values['maxCapacity']); //-- MODO SALON
      $description    = cleanStr($values['description']);
      $phone          = cleanStr($values['phone']);
      $cell_phone     = cleanStr($values['cellPhone']);
      $facebook       = cleanStr($values['facebook'], 'low');
      $instagram      = cleanStr($values['instagram'], 'low');
      $business_type  = $salon_mode ? 'Salon' : 'Otros';

      # PAQUETES
      $packages       = $values['packages'];

      # UBICACIÓN.
      $state          = cleanStr($values['state']);
      $city           = cleanStr($values['city']);
      $address        = cleanStr($values['address']);
      $latitude       = cleanStr($values['latitude']);
      $longitude      = cleanStr($values['longitude']);

      # SERVICIOS Y AMENIDADES. //-- APLICA SOLO PARA EL MODO SALON
      $services       = $values['services'];
      $amenities      = $values['amenities'];

      # CREAR SLUG
      # $reference      = date('YmdHis');
      $business_slug  = createSlug($business_name);

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
        ) VALUES (
          $user_id,
          $supplier_type,
          '$business_name',
          '$description',
          '$phone',
          '$cell_phone',
          '$facebook',
          '$instagram',
          '$business_type',
          $state,
          $city,
          '$address',
          '$latitude',
          '$longitude',
          '$business_slug'
          $query_salon_mode_insert
        )
      ";

      $query_result = mysqli_query($mysqli, $query);

      if ($query_result) :
        # ID DEL NEGOCIO AGREGADO
        $business_id  = mysqli_insert_id($mysqli);
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
        foreach ($packages as $key => $value) :
          $package_name         = cleanStr($value['packageName']);
          $modality             = cleanStr($value['modality']);
          $price                = cleanStr($value['price']);
          $package_event_types  = $value['eventTypes'];
          $package_description  = cleanStr($value['description'], 'html');
          $most_recommended     = $value['mostRecommended'] === 'Si' ? 'Si' : 'No';

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
              '$most_recommended'
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

        /* ::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::
        //-- REGRESAR RESPUESTA EXITOSA
        :::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::: */
        $response = array(
          'status'      => 'success',
          'businessId'  => $business_id
        );
      endif;
    } catch (Exception $e) {
      $response['message'] = $e->getMessage();
    }
    break;

  case 'save-business-images':
    try {
      $user_id          = cleanStr($_POST['userId']);
      $business_id      = cleanStr($_POST['businessId']);
      //$user_id          = 93;
      //$business_id      = 8;

      /* $response['message'] = json_encode($_POST);

      echo json_encode($response);
      return; */

      $principal_image  = $_FILES['principalImage'];
      $business_logo    = $_FILES['businessLogo'];
      $gallery          = $_FILES['gallery'];

      # OBTENER EL SLUG DEL NEGOCIO
      $query = "SELECT slug, Imagen, Logo FROM salones WHERE
          idSalon   = $business_id AND
          idUsuario = $user_id
        LIMIT 1
      ";

      $query_result   = mysqli_query($mysqli, $query);
      $business_data  = mysqli_fetch_array($query_result);

      $business_slug  = $business_data['slug'];
      $business_slug  = str_replace('-', '_', $business_slug);

      $business_image = $business_data['Imagen'];
      $image_name     = !$business_image ? null : $business_image;

      $bs_logo        = $business_data['Logo'];
      $logo_name      = !$bs_logo ? null : $bs_logo;

      # AGREGAR LA IMAGEN PRINCIPAL
      if ($principal_image['name']) :
        $proccess_image = processFile(
          $principal_image,
          $valid_extensions,
          BUSINESS_IMAGE_FOLDER,
          $business_slug
        );

        if ($proccess_image !== 'no-move' && $proccess_image !== 'no-valid') :
          deleteFile(BUSINESS_IMAGE_FOLDER . $image_name);

          $query = "UPDATE salones SET
              Imagen = '$proccess_image'
            WHERE
              idSalon   = $business_id AND
              idUsuario = $user_id
          ";

          mysqli_query($mysqli, $query);
        endif;
      endif;

      # AGREGAR LA IMAGEN PRINCIPAL
      if ($business_logo['name']) :
        $proccess_logo = processFile(
          $business_logo,
          $valid_extensions,
          BUSINESS_IMAGE_FOLDER,
          $business_slug . '_logo'
        );

        if ($proccess_logo !== 'no-move' && $proccess_logo !== 'no-valid') :
          deleteFile(BUSINESS_IMAGE_FOLDER . $logo_name);

          $query = "UPDATE salones SET
              Logo = '$proccess_logo'
            WHERE
              idSalon   = $business_id AND
              idUsuario = $user_id
          ";

          mysqli_query($mysqli, $query);
        endif;
      endif;

      # AGREGAR IMAGENES DE LA GALERÍA
      $image_gallery = processMultipleFiles(
        $gallery,
        $valid_extensions,
        BUSINESS_GALLERY_FOLDER,
        $business_slug
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

      $response = array(
        'status' => 'success'
      );
    } catch (Exception $e) {
      $response['message'] = $e->getMessage();
    }
    break;

  case 'delete-image-gallery':
    try {
      $parameters   = $json['parameters'];

      $user_id      = $parameters['userId'];
      $business_id  = $parameters['businessId'];
      $image_id     = $parameters['imageId'];

      $query = "SELECT
          idGaleria,
          Imagen
        FROM galeria
        WHERE
          idGaleria = $image_id AND
          idSalon   = $business_id
        LIMIT 1
      ";

      $query_result = mysqli_query($mysqli, $query);
      $num_rows     = mysqli_num_rows($query_result);

      if ($num_rows) :
        $gallery_data   = mysqli_fetch_array($query_result);

        $image          = $gallery_data['Imagen'];
        $file_location  = BUSINESS_GALLERY_FOLDER . $image;

        $delete         = deleteFile($file_location);

        if ($delete == 'not-exist' || $delete == 'deleted') :
          $query = "DELETE FROM galeria WHERE
              idGaleria = $image_id AND
              idSalon   = $business_id
          ";

          mysqli_query($mysqli, $query);

          $gallery = getBusinessGallery($business_id);

          $response = array(
            'status'  => 'success',
            'title'   => '¡Operación exitosa!',
            'message' => '¡La imagen se removió de la galería!',
            'gallery' => $gallery
          );
        endif;
      endif;
    } catch (Exception $e) {
      $response['message'] = $e->getMessage();
    }
    break;

  case 'delete-package':
    try {
      $parameters   = $json['parameters'];

      $user_id      = $parameters['userId'];
      $business_id  = $parameters['businessId'];
      $package_id   = $parameters['packageId'];

      $query = "DELETE FROM paquetes_negocios WHERE
          idPaquete = $package_id AND
          idNegocio = $business_id
      ";

      $query_result = mysqli_query($mysqli, $query);

      if ($query_result) :
        $query_delete_event_types = "DELETE FROM catalogo_paquete_tipos_eventos WHERE
            idPaquete = $package_id AND
            idNegocio = $business_id
        ";

        mysqli_query($mysqli, $query_delete_event_types);

        $packages = getBusinesspackages($business_id);

        $response = array(
          'status'    => 'success',
          'title'     => '¡Operación exitosa!',
          'message'   => 'El paquete se removió correctamente',
          'packages'  => $packages
        );
      endif;
    } catch (Exception $e) {
      $response['message'] = $e->getMessage();
    }
    break;

  case 'update-business':
    try {
      $parameters = $json['parameters'];

      $user_id      = $parameters['userId'];
      $business_id  = $parameters['businessId'];
      $values       = $parameters['values'];

      /* ::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::
      //-- BUSINESS VALUES
      :::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::: */
      # SALON MODE
      $salon_mode     = $values['salonMode'];

      # TIPO DE PROVEEDOR.
      $supplier_type  = cleanStr($values['supplierType']);

      # TIPOS DE EVENTOS.
      $event_types    = $values['eventTypes'];

      # INFORMACIÓN DEL NEGOCIO.
      $business_name  = cleanStr($values['businessName']);
      $min_capacity   = cleanStr($values['minCapacity']); //-- MODO SALON
      $max_capacity   = cleanStr($values['maxCapacity']); //-- MODO SALON
      $description    = cleanStr($values['description']);
      $phone          = cleanStr($values['phone']);
      $cell_phone     = cleanStr($values['cellPhone']);
      $facebook       = cleanStr($values['facebook'], 'low');
      $instagram      = cleanStr($values['instagram'], 'low');
      $business_type  = $salon_mode ? 'Salon' : 'Otros';

      # PAQUETES
      $packages       = $values['packages'];

      # UBICACIÓN.
      $state          = cleanStr($values['state']);
      $city           = cleanStr($values['city']);
      $address        = cleanStr($values['address']);
      $latitude       = cleanStr($values['latitude']);
      $longitude      = cleanStr($values['longitude']);

      # SERVICIOS Y AMENIDADES. //-- APLICA SOLO PARA EL MODO SALON
      $services       = $values['services'];
      $amenities      = $values['amenities'];

      /* ::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::
        //-- AGREAGAR LA INFORMACIÓN EN LA TABLA DE SALONES
        :::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::: */
      #EXTRA QUERY PARA EL MODO SALON
      $query_salon_mode = $salon_mode ? "
          ,Capacidad = '$min_capacity',
          CapacidadMaxima = '$max_capacity'
        " : "
          ,Capacidad = '0',
          CapacidadMaxima = '0'
      ";

      $query_salon_mode_insert = $salon_mode ? "
          , '$min_capacity',
          '$max_capacity'
        " : "";

      # CREAR SLUG Y REFERENCIA
      $business_slug  = createSlug($business_name);

      # QUERY PARA INSERTAR EL NEGOCIO
      $query = "UPDATE salones SET
          idTipoProveedor = $supplier_type,
          Salon           = '$business_name',
          Descripcion     = '$description',
          Telefono        = '$phone',
          Celular         = '$cell_phone',
          Facebook        = '$facebook',
          Instagram       = '$instagram',
          Tipo            = '$business_type',
          idEstado        = $state,
          idCiudad        = $city,
          Direccion       = '$address',
          Latitud         = '$latitude',
          Longitud        = '$longitude',
          slug            = '$business_slug'
          $query_salon_mode
        WHERE
          idSalon   = $business_id AND
          idUsuario = $user_id
      ";

      $query_result = mysqli_query($mysqli, $query);

      if ($query_result) :
        /* ::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::
        //-- AGREGAR LOS TIPOS DE EVENTOS DEL NEGOCIO
        :::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::: */
        # ELIMINAR LO ANTERIOR
        $query_delete_event_types = "DELETE FROM catalogo_salon_tipos_eventos WHERE
            idSalon = $business_id
        ";

        $query_delete_event_types_result = mysqli_query($mysqli, $query_delete_event_types);

        if ($query_delete_event_types) :
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
        endif;

        /* ::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::
        //-- AGREGAR LOS SERVICIOS Y AMENIDADES DEL NEGOCIO
        :::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::: */
        # ELIMINAR SERVICIOS
        $query_delete_services = "DELETE FROM catalogo_salon_servicios WHERE
            idSalon = $business_id
        ";
        mysqli_query($mysqli, $query_delete_services);

        # ELIMINAR AMENIDADES
        $query_delete_amenities = "DELETE FROM catalogo_salon_amenidades WHERE
            idSalon = $business_id
        ";
        mysqli_query($mysqli, $query_delete_amenities);

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
        //-- ACTUALIZAR Y AGREGAR LOS PAQUETES DEL NEGOCIO
        :::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::: */
        foreach ($packages as $key => $value) :
          $initial_package_id   = cleanStr($value['packageId']);
          $package_name         = cleanStr($value['packageName']);
          $modality             = cleanStr($value['modality']);
          $price                = cleanStr($value['price']);
          $package_event_types  = $value['eventTypes'];
          $package_description  = cleanStr($value['description'], 'html');
          $most_recommended     = $value['mostRecommended'] === 'Si' ? 'Si' : 'No';

          $query_packages = "";

          if (!$initial_package_id) $query_packages = "INSERT INTO paquetes_negocios (
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
              '$most_recommended'
            )
          ";

          if ($initial_package_id) $query_packages = "UPDATE paquetes_negocios SET
              Paquete       = '$package_name',
              Descripcion   = '$package_description',
              Precio        = '$price',
              Orientacion   = '$modality',
              MasContratado = '$most_recommended'
            WHERE
              idPaquete = $initial_package_id AND
              idNegocio = $business_id
          ";

          $query_packages_result = mysqli_query($mysqli, $query_packages);

          # AGREGAR LOS TIPOS DE EVENTO QUE TIENE EL PAQUETE
          if ($query_packages_result) :
            $package_id = !$initial_package_id ? mysqli_insert_id($mysqli) : $initial_package_id;

            # ELIMINAR LOS TPOS DE EVENTO SI EL PAQUETE YA EXISTIA PARA PODER AGREGAR LOS NUEVOS
            if ($initial_package_id) :
              $query_delete_package_event_types = "DELETE FROM catalogo_paquete_tipos_eventos WHERE
                  idPaquete = $initial_package_id
              ";

              mysqli_query($mysqli, $query_delete_package_event_types);
            endif;

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

        /* ::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::
          //-- REGRESAR RESPUESTA EXITOSA
          :::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::: */
        $response = array(
          'status'      => 'success',
          'businessId'  => $business_id
        );
      endif;
    } catch (Exception $e) {
      $response['message'] = $e->getMessage();
    }
    break;

  case 'remove-business':
    try {
      $parameters = $json['parameters'];

      $user_id      = $parameters['userId'];
      $business_id  = $parameters['businessId'];

      $query = "UPDATE salones SET
          Status = 'Eliminado'
        WHERE
          idSalon   = $business_id AND
          idUsuario = $user_id
      ";

      $query_result = mysqli_query($mysqli, $query);

      if ($query_result) {
        mysqli_query($mysqli, "DELETE FROM usuarios WHERE idNegocio = $business_id");

        $num_business = getNumBusiness($user_id);

        $response = array(
          'status'      => 'success',
          'title'       => '¡Operación exitosa!',
          'message'     => 'El negocio se eliminó correctamente.',
          'numBusiness' => $num_business
        );
      }
    } catch (Exception $e) {
      $response['message'] = $e->getMessage();
    }
    break;

  case 'get-business-name':
    $business_id = $json['parameters'];

    $query = "SELECT Salon FROM salones WHERE idSalon = $business_id LIMIT 1";
    $query_result = mysqli_query($mysqli, $query);
    $business_data = mysqli_fetch_array($query_result);

    $business_name = $business_data['Salon'];

    $response = array(
      'status' => 'success',
      'businessName' => $business_name
    );
    break;
}

echo json_encode($response);
mysqli_close($mysqli);
exit();
