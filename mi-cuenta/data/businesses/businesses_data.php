<?php
include '../session.php';
include '../../inc/functions.inc.php';
include '../lib/pagination.php';

define('BUSINESS_IMAGE_FOLDER', '../../../src/assets/images/listing/');
define('BUSINESS_GALLERY_FOLDER', '../../../src/assets/images/listing/gallery/');

$action = isset($_POST['action']) && $_POST['action'] !== '' ? $_POST['action'] : '';
$valid_extensions = array('jpeg', 'jpg', 'png', 'JPEG', 'JPG', 'PNG', 'octet-stream');

//$images_location = $images_url;

switch ($action) {
  case 'add_business':
    try {
      /* ::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::
      //-- BUSINESS VALUES
      :::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::: */
      $user_id = $_SESSION['session_user_id'];

      $can_add_business = checkIfCanAddBusiness();

      if (!$can_add_business) :
        $response['message'] = '¡Actualiza al plan Básico para poder agregar mas negocios!';

        echo json_encode($response);
        mysqli_close($mysqli);
        die();
      endif;

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
      $package_counter = $_POST['packageCounter'];

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
      $gallery          = $_FILES['imageGallery'];

      # SALON MODE
      $salon_mode     = $supplier_type == '1' ? true : false;
      $business_type  = $salon_mode ? 'Salon' : 'Otros';

      # CREAR SLUG Y REFERENCIA
      #$reference      = date('YmdHis'); //-- REMPLAZADO POR EL ID DEL NEGOCIO
      $business_slug  = createSlug($business_name);

      # PICTURES QUERY
      $query_picture        = "";
      $query_insert_picture = "";

      /* $response['message'] = $_FILES;
      echo json_encode($response);
      return; */

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

      # AGREGAR LOGO DEL NEGOCIO
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

      $query_result = mysqli_query($mysqli, $query);

      $response['message'] = $_POST;

      if ($query_result) :
        # ID DEL NEGOCIO AGREGAO
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

        //$_SESSION['session_business_id'] = $business_id;

        /* ::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::
        //-- REGRESAR RESPUESTA
        :::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::: */
        $response = array(
          'status' => 'success',
          'message' => 'El negocio "' . $business_name . '" se agregó correctamente'
        );
      endif;
    } catch (Exception $e) {
      $response['message'] = $e->getMessage();
    }
    break;

  case 'edit_business':
    try {
      /* ::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::
      //-- BUSINESS VALUES
      :::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::: */
      $user_id      = $_SESSION['session_user_id'];
      $business_id  = cleanStr($_POST['businessId']);

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
      $package_counter        = $_POST['packageCounter'];
      $packages_ids           = $_POST['packageId'];
      $original_packages_ids  = getBusinessPackageIds($business_id);

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
      $gallery          = $_FILES['imageGallery'];

      $business_data    = getBusinessDataById($business_id);

      $business_image_name    = getBusinessImageName($business_id);
      $original_image_gallery = getBusinessGalleryIds($business_id);
      $new_image_gallery      = $_POST['imageGallery-items'];

      # SALON MODE
      $salon_mode     = $supplier_type == '1' ? true : false;
      $business_type  = $salon_mode ? 'Salon' : 'Otros';

      # CREAR SLUG Y REFERENCIA
      $reference      = date('YmdHis');
      $business_slug  = $business_data['slug'];

      # PICTURES QUERY
      $query_picture = "";

      /* $response['message'] = $_FILES;
        echo json_encode($response);
        return; */

      # AGREGAR LA IMAGEN DE PRINCIPAL
      if ($principal_image['name']) :
        $proccess_principal_image = processFile(
          $principal_image,
          $valid_extensions,
          BUSINESS_IMAGE_FOLDER,
          str_replace('-', '_', $business_slug)
          //$business_image_name
        );

        if ($proccess_principal_image !== 'no-move' && $proccess_principal_image !== 'no-valid') :
          deleteFile(BUSINESS_IMAGE_FOLDER . $business_image_name);
          $query_picture .= ", Imagen = '$proccess_principal_image'";
        endif;
      endif;

      # AGREGAR LOGO DEL NEGOCIO
      if ($business_logo['name']) :
        $proccess_business_logo = processFile(
          $business_logo,
          $valid_extensions,
          BUSINESS_IMAGE_FOLDER,
          str_replace('-', '_', $business_slug) . '_logo'
        );

        if ($proccess_business_logo !== 'no-move' && $proccess_business_logo !== 'no-valid') :
          deleteFile(BUSINESS_IMAGE_FOLDER . $business_data['Logo']);
          $query_picture .= ", Logo = '$proccess_business_logo'";
        endif;
      endif;

      /* ::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::
      //-- AGREAGAR LA INFORMACIÓN EN LA TABLA DE SALONES
      :::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::: */
      #EXTRA QUERY PARA EL MODO SALON
      $query_salon_mode = $salon_mode ? "
        ,Capacidad      = '$min_capacity',
        CapacidadMaxima = '$max_capacity'
      " : "
        ,Capacidad      = '0',
        CapacidadMaxima = '0'
      ";

      # QUERY PARA INSERTAR EL NEGOCIO
      $query = "UPDATE salones SET
          idTipoProveedor = $supplier_type,
          Salon           = '$business_name',
          Descripcion     = '$business_description',
          Telefono        = '$business_phone',
          Celular         = '$business_cell_phone',
          Facebook        = '$business_facebook',
          Instagram       = '$business_instagram',
          Tipo            = '$business_type',
          idEstado        = $state,
          idCiudad        = $city,
          Direccion       = '$address',
          Latitud         = '$latitude',
          Longitud        = '$longitude'
          $query_salon_mode
          $query_picture
        WHERE
          idUsuario = $user_id AND
          idSalon   = $business_id
      ";

      $query_result = mysqli_query($mysqli, $query);

      $response['message'] = $_POST;

      if ($query_result) :
        /* ::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::
        //-- AGREGAR LOS TIPOS DE EVENTOS DEL NEGOCIO
        :::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::: */
        # ELIMINAR LO ANTERIOR
        $query_delete_event_types = "DELETE FROM catalogo_salon_tipos_eventos WHERE
            idSalon = $business_id
        ";

        $query_delete_event_types_result = mysqli_query($mysqli, $query_delete_event_types);

        if ($query_delete_event_types_result) :
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
        //-- AGREGAR LOS PAQUETES DEL NEGOCIO
        :::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::: */
        deleteBusinessPackages(
          $original_packages_ids,
          $packages_ids
        );

        foreach ($package_counter as $key => $value) :
          $counter = $value;

          $initial_package_id   = cleanStr($_POST["packageId-$counter"]);
          $package_name         = cleanStr($_POST["packageName-$counter"]);
          $more_contracted      = $_POST["moreContracted-$counter"] === 'Si' ? cleanStr($_POST["moreContracted-$counter"]) : 'No';
          $modality             = cleanStr($_POST["modality-$counter"]);
          $price                = cleanStr($_POST["packagePrice-$counter"]);
          $package_event_types  = $_POST["package-$counter-eventType"];
          $package_description  = cleanStr($_POST["packageDescription-$counter"], 'html');

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
              '$more_contracted'
            )
          ";

          if ($initial_package_id) $query_packages = "UPDATE paquetes_negocios SET
              Paquete       = '$package_name',
              Descripcion   = '$package_description',
              Precio        = '$price',
              Orientacion   = '$modality',
              MasContratado = '$more_contracted'
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
        //-- AGREGAR LAS IMAGENES DE LA GALERÍA
        :::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::: */
        # ELIMINAR LAS IMAGENES QUE SE REMOVIERON
        deleteBusinessImageGallery(
          $original_image_gallery,
          $new_image_gallery
        );

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

        /* ::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::
        //-- REGRESAR RESPUESTA
        :::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::: */
        $response = array(
          'status' => 'success',
          'message' => 'El negocio "' . $business_name . '" se actualizó correctamente'
        );
      endif;
    } catch (Exception $e) {
      $response['message'] = $e->getMessage();
    }
    break;

  case 'add-package':
    $num_package  = $_POST['numPackage'];
    $package      = getBusinessPackageItem($num_package, true);

    $response = array(
      'status'  => 'success',
      'content' => base64_encode($package)
    );
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
