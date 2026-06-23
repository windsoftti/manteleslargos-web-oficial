<?php
include '../session.php';
include '../../inc/functions.inc.php';
include '../lib/pagination.php';
date_default_timezone_set('America/Mexico_City');

$action = $_POST['action'];

$extensions           = array('jpeg', 'jpg', 'png', 'JPEG', 'JPG', 'PNG');
$file_folder          = $images_url . 'invitaciones-digitales/';

switch ($action) {
  case 'list_invitations':
    $user_level       = $_SESSION['session_user_level'];
    $user_id          = $_SESSION['session_user_id'];

    $page             = isset($_POST['page']) && $_POST['page'] !== '' ? $_POST['page'] : 1;
    $per_page         = 15;

    $person           = cleanStr($_POST['searchByPerson']);
    $search_by_person = $person != '' ? "NombrePersona LIKE '%$person%'" : "1=1";

    $from             = "FROM invitaciones_digitales";

    $level_where      = $user_level == 'Super Usuario' || $user_level == 'Administrador' ? "" : "AND idUsuario = '$user_id'";

    $where = "WHERE
        ($search_by_person)
        $level_where
      ORDER BY idInvitacion DESC
    ";

    $start_rows       = ($page - 1) * $per_page;
    $stop_rows        = $per_page;
    $limit_rows       = "LIMIT $start_rows, $stop_rows";

    $query            = "SELECT COUNT(idInvitacion) AS Total $from $where LIMIT 1";
    $query_result     = mysqli_query($mysqli, $query);
    $row              = mysqli_fetch_array($query_result);

    $num_pages        = ceil($row['Total'] / $stop_rows);

    if (!$num_pages) {
      $default_message = '¡No hay invitaciones registradas!.';

      if ($person != '') {
        $default_message = '¡No hay invitaciones que coincidan con la busqueda "' . $person . '"!.';
      }

      ob_start();
      include '../default_message.php';
      $data_message = base64_encode(ob_get_clean());

      $response['content'] = $data_message;
    }

    if ($num_pages) {
      $query = "SELECT
          idInvitacion,
          NombrePersona,
          NombreEvento,
          Plantilla,
          Slug
        $from
        $where
        $limit_rows
      ";

      $query_result = mysqli_query($mysqli, $query);

      ob_start();
      include 'digital_invitations_table.php';
      $data_table = base64_encode(ob_get_clean());

      $response['content'] = $data_table;
    }
    break;

  case 'add_invitation':
    $user_id = $_SESSION['session_user_id'];

    # General data
    $person_name          = cleanStr($_POST['personName']);
    $event_name           = cleanStr($_POST['eventName']);
    $phone                = cleanStr($_POST['phone']);
    $commemorative_phrase = cleanStr($_POST['commemorativePhrase']);
    $template             = cleanStr($_POST['template']);
    $invitation_type      = cleanStr($_POST['invitationType']);
    $principal_color      = cleanStr($_POST['principalColor']);
    $secondary_color      = cleanStr($_POST['secondaryColor']);

    # Slug
    $slug = generateSlug($person_name . '-' . $event_name);

    # Religious ceremony
    $cr_place     = cleanStr($_POST['CRPlace']);
    $cr_date      = date("Y-m-d H:i:s", strtotime(str_replace('/', '-', $_POST['CRDate'])));
    $cr_address   = cleanStr($_POST['addressCR']);
    $cr_latitude  = cleanStr($_POST['latitudeCR']);
    $cr_longitude = cleanStr($_POST['longitudeCR']);
    $cr_image     = $_FILES['crImage'];

    # Reception
    $r_place     = cleanStr($_POST['RPlace']);
    $r_date      = date("Y-m-d H:i:s", strtotime(str_replace('/', '-', $_POST['RDate'])));
    $r_address   = cleanStr($_POST['addressRecepcion']);
    $r_latitude  = cleanStr($_POST['latitudeRecepcion']);
    $r_longitude = cleanStr($_POST['longitudeRecepcion']);
    $r_image     = $_FILES['rImage'];

    # Image gallery
    $individual_picture = $_FILES['individualPicture'];
    $family_picture     = $_FILES['familyPicture'];
    $image_gallery      = $_FILES['imageGallery'];

    # Pictures query
    $query_picture         = "";
    $query_insert_picture  = "";

    if ($cr_image['name']) {
      $cr_image_name = processOptimizedImage($cr_image, $extensions, $file_folder);

      if ($cr_image_name == 'no-valid') {
        $response = array(
          'status'  => 'warning',
          'title'   => '¡Cuidado!',
          'message' => 'La imagen individual que intenta subir, no es valida.'
        );

        echo json_encode($response);
        mysqli_close($mysqli);
        return;
      }

      if ($cr_image_name == 'no-move') {
        $response = array(
          'status'  => 'error',
          'title'   => '¡Aviso!',
          'message' => 'La imagen individual no pudo moverse al servidor, intentelo mas tarde.'
        );

        echo json_encode($response);
        mysqli_close($mysqli);
        return;
      }

      $query_picture         .= ", CRImagen";
      $query_insert_picture  .= ", '$cr_image_name[name]'";
    }

    if ($r_image['name']) {
      $r_image_name = processOptimizedImage($r_image, $extensions, $file_folder);

      if ($r_image_name == 'no-valid') {
        $response = array(
          'status'  => 'warning',
          'title'   => '¡Cuidado!',
          'message' => 'La imagen individual que intenta subir, no es valida.'
        );

        echo json_encode($response);
        mysqli_close($mysqli);
        return;
      }

      if ($r_image_name == 'no-move') {
        $response = array(
          'status'  => 'error',
          'title'   => '¡Aviso!',
          'message' => 'La imagen individual no pudo moverse al servidor, intentelo mas tarde.'
        );

        echo json_encode($response);
        mysqli_close($mysqli);
        return;
      }

      $query_picture         .= ", RImagen";
      $query_insert_picture  .= ", '$r_image_name[name]'";
    }

    if ($individual_picture['name']) {
      $individual_picture_name = processOptimizedImage($individual_picture, $extensions, $file_folder);

      if ($individual_picture_name == 'no-valid') {
        $response = array(
          'status'  => 'warning',
          'title'   => '¡Cuidado!',
          'message' => 'La imagen individual que intenta subir, no es valida.'
        );

        echo json_encode($response);
        mysqli_close($mysqli);
        return;
      }

      if ($individual_picture_name == 'no-move') {
        $response = array(
          'status'  => 'error',
          'title'   => '¡Aviso!',
          'message' => 'La imagen individual no pudo moverse al servidor, intentelo mas tarde.'
        );

        echo json_encode($response);
        mysqli_close($mysqli);
        return;
      }

      $query_picture         .= ", ImagenIndividual";
      $query_insert_picture  .= ", '$individual_picture_name[name]'";
    }

    if ($family_picture['name']) {
      $family_picture_name = processOptimizedImage($family_picture, $extensions, $file_folder);

      if ($family_picture_name == 'no-valid') {
        $response = array(
          'status'  => 'warning',
          'title'   => '¡Cuidado!',
          'message' => 'La imagen familiar que intenta subir, no es valida.'
        );

        echo json_encode($response);
        mysqli_close($mysqli);
        return;
      }

      if ($family_picture_name == 'no-move') {
        $response = array(
          'status'  => 'error',
          'title'   => '¡Aviso!',
          'message' => 'La imagen familiar no pudo moverse al servidor, intentelo mas tarde.'
        );

        echo json_encode($response);
        mysqli_close($mysqli);
        return;
      }

      $query_picture         .= ", ImagenFamiliar";
      $query_insert_picture  .= ", '$family_picture_name[name]'";
    }

    $query = "INSERT INTO invitaciones_digitales (
        idUsuario,
        NombrePersona,
        NombreEvento,
        Telefono,
        Frase,
        TipoInvitacion,
        ColorPrincipal,
        ColorSecundario,
        CRLugar,
        CRFecha,
        CRDireccion,
        CRLatitud,
        CRLongitud,
        RLugar,
        RFecha,
        RDireccion,
        RLatitud,
        RLongitud,
        Plantilla,
        Slug
        $query_picture
      ) VALUES (
        '$user_id',
        '$person_name',
        '$event_name',
        '$phone',
        '$commemorative_phrase',
        '$invitation_type',
        '$principal_color',
        '$secondary_color',
        '$cr_place',
        '$cr_date',
        '$cr_address',
        '$cr_latitude',
        '$cr_longitude',
        '$r_place',
        '$r_date',
        '$r_address',
        '$r_latitude',
        '$r_longitude',
        '$template',
        '$slug'
        $query_insert_picture
      )
    ";

    $query_result = mysqli_query($mysqli, $query);

    if (!$query_result) {
      $response = array(
        'status'  => 'error',
        'title'   => '¡Error!',
        'message' => 'Intentalo nuevamente.'
      );

      echo json_encode($response);
      mysqli_close($mysqli);
      return;
    }

    $invitation_id = mysqli_insert_id($mysqli);

    $move_files = true;
    $valid_files = true;

    foreach ($image_gallery['tmp_name'] as $key => $value) :
      if ($image_gallery['name'][$key]) :
        $image_gallery_name = processMultipleOptimizedImage($image_gallery, $extensions, $file_folder . 'galeria/', $key);

        if ($image_gallery_name == 'no-move') $move_files = false;
        if ($image_gallery_name == 'no-valid') $valid_files = false;

        if ($image_gallery_name != 'no-move' && $image_gallery_name != 'no-valid') {
          $query = "INSERT INTO galeria_de_invitaciones_digitales (
              idInvitacion,
              Imagen
            ) VALUES (
              '$invitation_id',
              '$image_gallery_name'
            )
          ";

          mysqli_query($mysqli, $query);
        }
      endif;
    endforeach;

    if (!$move_files) $response = array(
      'status'  => 'success',
      'title'   => '¡Aviso!',
      'message' => "Algunas imagenes no pudieron moverse, verifique en el apartado de editar invitación."
    );

    if (!$valid_files) $response = array(
      'status'  => 'success',
      'title'   => '¡Aviso!',
      'message' => "Algunas imagenes no son validos, verifique en el apartado de editar invitación."
    );

    if ($move_files && $valid_files) $response = array(
      'status'  => 'success',
      'title'   => '¡Datos guardados!',
      'message' => 'La invitacion de "' . $person_name . '" ha sido creada correctamente.'
    );
    break;

  case 'edit_invitation':
    $user_id        = $_SESSION['session_user_id'];
    $invitation_id  = cleanStr($_POST['invitationId']);

    # General data
    $person_name          = cleanStr($_POST['personName']);
    $event_name           = cleanStr($_POST['eventName']);
    $phone                = cleanStr($_POST['phone']);
    $commemorative_phrase = cleanStr($_POST['commemorativePhrase']);
    $template             = cleanStr($_POST['template']);
    $invitation_type      = cleanStr($_POST['invitationType']);
    $principal_color      = cleanStr($_POST['principalColor']);
    $secondary_color      = cleanStr($_POST['secondaryColor']);

    # Slug
    $slug = generateSlug($person_name . '-' . $event_name);

    # Religious ceremony
    $cr_place     = cleanStr($_POST['CRPlace']);
    $cr_date      = date("Y-m-d H:i:s", strtotime(str_replace('/', '-', $_POST['CRDate'])));
    $cr_address   = cleanStr($_POST['addressCR']);
    $cr_latitude  = cleanStr($_POST['latitudeCR']);
    $cr_longitude = cleanStr($_POST['longitudeCR']);
    $cr_image     = $_FILES['crImage'];

    # Reception
    $r_place     = cleanStr($_POST['RPlace']);
    $r_date      = date("Y-m-d H:i:s", strtotime(str_replace('/', '-', $_POST['RDate'])));
    $r_address   = cleanStr($_POST['addressRecepcion']);
    $r_latitude  = cleanStr($_POST['latitudeRecepcion']);
    $r_longitude = cleanStr($_POST['longitudeRecepcion']);
    $r_image     = $_FILES['rImage'];

    # Image gallery
    $individual_picture = $_FILES['individualPicture'];
    $family_picture     = $_FILES['familyPicture'];
    $image_gallery      = $_FILES['imageGallery'];

    # Pictures query
    $query_picture         = "";

    if ($cr_image['name']) {
      $cr_image_name = processOptimizedImage($cr_image, $extensions, $file_folder);

      if ($cr_image_name == 'no-valid') {
        $response = array(
          'status'  => 'warning',
          'title'   => '¡Cuidado!',
          'message' => 'La imagen individual que intenta subir, no es valida.'
        );

        echo json_encode($response);
        mysqli_close($mysqli);
        return;
      }

      if ($cr_image_name == 'no-move') {
        $response = array(
          'status'  => 'error',
          'title'   => '¡Aviso!',
          'message' => 'La imagen individual no pudo moverse al servidor, intentelo mas tarde.'
        );

        echo json_encode($response);
        mysqli_close($mysqli);
        return;
      }

      $query_picture .= ", CRImagen = '$cr_image_name[name]'";
    }

    if ($r_image['name']) {
      $r_image_name = processOptimizedImage($r_image, $extensions, $file_folder);

      if ($r_image_name == 'no-valid') {
        $response = array(
          'status'  => 'warning',
          'title'   => '¡Cuidado!',
          'message' => 'La imagen individual que intenta subir, no es valida.'
        );

        echo json_encode($response);
        mysqli_close($mysqli);
        return;
      }

      if ($r_image_name == 'no-move') {
        $response = array(
          'status'  => 'error',
          'title'   => '¡Aviso!',
          'message' => 'La imagen individual no pudo moverse al servidor, intentelo mas tarde.'
        );

        echo json_encode($response);
        mysqli_close($mysqli);
        return;
      }

      $query_picture .= ", RImagen = '$r_image_name[name]'";
    }

    if ($individual_picture['name']) {
      $individual_picture_name = processOptimizedImage($individual_picture, $extensions, $file_folder);

      if ($imagen_salon == 'no-valid') {
        $response = array(
          'status'  => 'warning',
          'title'   => '¡Cuidado!',
          'message' => 'La imagen individual que intenta subir, no es valida.'
        );

        echo json_encode($response);
        mysqli_close($mysqli);
        return;
      }

      if ($imagen_salon == 'no-move') {
        $response = array(
          'status'  => 'error',
          'title'   => '¡Aviso!',
          'message' => 'La imagen individual no pudo moverse al servidor, intentelo mas tarde.'
        );

        echo json_encode($response);
        mysqli_close($mysqli);
        return;
      }

      $query_picture .= ", ImagenIndividual = '$individual_picture_name[name]'";
    }

    if ($family_picture['name']) {
      $family_picture_name = processOptimizedImage($family_picture, $extensions, $file_folder);

      if ($imagen_salon == 'no-valid') {
        $response = array(
          'status'  => 'warning',
          'title'   => '¡Cuidado!',
          'message' => 'La imagen familiar que intenta subir, no es valida.'
        );

        echo json_encode($response);
        mysqli_close($mysqli);
        return;
      }

      if ($imagen_salon == 'no-move') {
        $response = array(
          'status'  => 'error',
          'title'   => '¡Aviso!',
          'message' => 'La imagen familiar no pudo moverse al servidor, intentelo mas tarde.'
        );

        echo json_encode($response);
        mysqli_close($mysqli);
        return;
      }

      $query_picture .= ", ImagenFamiliar = '$family_picture_name[name]'";
    }

    $query = "UPDATE invitaciones_digitales SET
        NombrePersona   = '$person_name',
        NombreEvento    = '$event_name',
        Telefono        = '$phone',
        Frase           = '$commemorative_phrase',
        TipoInvitacion  = '$invitation_type',
        ColorPrincipal  = '$principal_color',
        ColorSecundario = '$secondary_color',
        CRLugar         = '$cr_place',
        CRFecha         = '$cr_date',
        CRDireccion     = '$cr_address',
        CRLatitud       = '$cr_latitude',
        CRLongitud      = '$cr_longitude',
        RLugar          = '$r_place',
        RFecha          = '$r_date',
        RDireccion      = '$r_address',
        RLatitud        = '$r_latitude',
        RLongitud       = '$r_longitude',
        Plantilla       = '$template',
        Slug            = '$slug'
        $query_picture
      WHERE
        idUsuario     = '$user_id' AND
        idInvitacion  = '$invitation_id'
    ";

    $query_result = mysqli_query($mysqli, $query);

    if (!$query_result) {
      $response = array(
        'status'  => 'error',
        'title'   => '¡Error!',
        'message' => 'Intentalo nuevamente.'
      );

      echo json_encode($response);
      mysqli_close($mysqli);
      return;
    }

    $move_files = true;
    $valid_files = true;

    foreach ($image_gallery['tmp_name'] as $key => $value) :
      if ($image_gallery['name'][$key]) :
        $image_gallery_name = processMultipleOptimizedImage($image_gallery, $extensions, $file_folder . 'galeria/', $key);

        if ($image_gallery_name == 'no-move') $move_files = false;
        if ($image_gallery_name == 'no-valid') $valid_files = false;

        if ($image_gallery_name != 'no-move' && $image_gallery_name != 'no-valid') {
          $query = "INSERT INTO galeria_de_invitaciones_digitales (
                idInvitacion,
                Imagen
              ) VALUES (
                '$invitation_id',
                '$image_gallery_name'
              )
            ";

          mysqli_query($mysqli, $query);
        }
      endif;
    endforeach;

    if (!$move_files) $response = array(
      'status'  => 'success',
      'title'   => '¡Aviso!',
      'message' => "Algunas imagenes no pudieron moverse, verifique en el apartado de editar invitación."
    );

    if (!$valid_files) $response = array(
      'status'  => 'success',
      'title'   => '¡Aviso!',
      'message' => "Algunas imagenes no son validos, verifique en el apartado de editar invitación."
    );

    if ($move_files && $valid_files) $response = array(
      'status'  => 'success',
      'title'   => '¡Datos guardados!',
      'message' => 'La invitacion de "' . $person_name . '" ha sido actualizada correctamente.'
    );
    break;

  case 'delete_cr_image':
    $invitation_id = cleanStr($_POST['invitationId']);

    $query = "SELECT
        CRImagen
      FROM invitaciones_digitales
      WHERE idInvitacion = '$invitation_id'
      LIMIT 1
    ";

    $query_result       = mysqli_query($mysqli, $query);
    $row                = mysqli_fetch_array($query_result);

    $cr_image = $row['CRImagen'];
    $file_location      = $images_url . 'invitaciones-digitales/' . $cr_image;

    $delete_cr_image = deleteFile($file_location);

    if ($delete_cr_image === 'not-deleted') {
      $response = array(
        'status'  => 'error',
        'title'   => 'No se puede remover la imagen "' . $cr_image . '", intentelo nuevamente.'
      );
    }

    if ($delete_cr_image === 'deleted' || $delete_cr_image === 'not-exist') {
      $query = "UPDATE invitaciones_digitales SET
          CRImagen = ''
        WHERE idInvitacion = '$invitation_id'
      ";

      $query_result = mysqli_query($mysqli, $query);

      if (!$query_result) {
        $response = array(
          'status' => 'error',
          'title' => '¡Error!, Intentelo nuevamente.'
        );
      }

      if ($query_result) {
        $response = array(
          'status' => 'success',
          'title' => 'La imagen "' . $cr_image . '" ha sido eliminado correctamente.'
        );
      }
    }
    break;

  case 'delete_r_image':
    $invitation_id = cleanStr($_POST['invitationId']);

    $query = "SELECT
        RImagen
      FROM invitaciones_digitales
      WHERE idInvitacion = '$invitation_id'
      LIMIT 1
    ";

    $query_result       = mysqli_query($mysqli, $query);
    $row                = mysqli_fetch_array($query_result);

    $r_image = $row['RImagen'];
    $file_location      = $images_url . 'invitaciones-digitales/' . $r_image;

    $delete_r_image = deleteFile($file_location);

    if ($delete_r_image === 'not-deleted') {
      $response = array(
        'status'  => 'error',
        'title'   => 'No se puede remover la imagen "' . $r_image . '", intentelo nuevamente.'
      );
    }

    if ($delete_r_image === 'deleted' || $delete_r_image === 'not-exist') {
      $query = "UPDATE invitaciones_digitales SET
          RImagen = ''
        WHERE idInvitacion = '$invitation_id'
      ";

      $query_result = mysqli_query($mysqli, $query);

      if (!$query_result) {
        $response = array(
          'status' => 'error',
          'title' => '¡Error!, Intentelo nuevamente.'
        );
      }

      if ($query_result) {
        $response = array(
          'status' => 'success',
          'title' => 'La imagen "' . $r_image . '" ha sido eliminado correctamente.'
        );
      }
    }
    break;

  case 'delete_individual_picture':
    $invitation_id = cleanStr($_POST['invitationId']);

    $query = "SELECT
        ImagenIndividual
      FROM invitaciones_digitales
      WHERE idInvitacion = '$invitation_id'
      LIMIT 1
    ";

    $query_result       = mysqli_query($mysqli, $query);
    $row                = mysqli_fetch_array($query_result);

    $individual_picture = $row['ImagenIndividual'];
    $file_location      = $images_url . 'invitaciones-digitales/' . $individual_picture;

    $delete_individual_picture = deleteFile($file_location);

    if ($delete_individual_picture === 'not-deleted') {
      $response = array(
        'status'  => 'error',
        'title'   => 'No se puede remover la imagen "' . $individual_picture . '", intentelo nuevamente.'
      );
    }

    if ($delete_individual_picture === 'deleted' || $delete_individual_picture === 'not-exist') {
      $query = "UPDATE invitaciones_digitales SET
          ImagenIndividual = ''
        WHERE idInvitacion = '$invitation_id'
      ";

      $query_result = mysqli_query($mysqli, $query);

      if (!$query_result) {
        $response = array(
          'status' => 'error',
          'title' => '¡Error!, Intentelo nuevamente.'
        );
      }

      if ($query_result) {
        $response = array(
          'status' => 'success',
          'title' => 'La imagen "' . $individual_picture . '" ha sido eliminado correctamente.'
        );
      }
    }
    break;

  case 'delete_family_picture':
    $invitation_id = cleanStr($_POST['invitationId']);

    $query = "SELECT
        ImagenFamiliar
      FROM invitaciones_digitales
      WHERE idInvitacion = '$invitation_id'
      LIMIT 1
    ";

    $query_result   = mysqli_query($mysqli, $query);
    $row            = mysqli_fetch_array($query_result);

    $family_picture = $row['ImagenFamiliar'];
    $file_location  = $images_url . 'invitaciones-digitales/' . $family_picture;

    $delete_family_picture = deleteFile($file_location);

    if ($delete_family_picture === 'not-deleted') {
      $response = array(
        'status'  => 'error',
        'title'   => 'No se puede remover la imagen "' . $family_picture . '", intentelo nuevamente.'
      );
    }

    if ($delete_family_picture === 'deleted' || $delete_family_picture === 'not-exist') {
      $query = "UPDATE invitaciones_digitales SET
          ImagenFamiliar = ''
        WHERE idInvitacion = '$invitation_id'
      ";

      $query_result = mysqli_query($mysqli, $query);

      if (!$query_result) {
        $response = array(
          'status' => 'error',
          'title' => '¡Error!, Intentelo nuevamente.'
        );
      }

      if ($query_result) {
        $response = array(
          'status' => 'success',
          'title' => 'La imagen "' . $family_picture . '" ha sido eliminado correctamente.'
        );
      }
    }
    break;

  case 'delete_invitation':
    $invitation_id  = cleanStr($_POST['invitationId']);
    $invitation     = cleanStr($_POST['invitation']);

    $query = "SELECT
        CRImagen,
        RImagen,
        ImagenIndividual,
        ImagenFamiliar
      FROM invitaciones_digitales
      WHERE idInvitacion = '$invitation_id'
      LIMIT 1
    ";

    $query_result       = mysqli_query($mysqli, $query);
    $row                = mysqli_fetch_array($query_result);

    $cr_image           = $row['CRImagen'];
    $r_image            = $row['RImagen'];
    $individual_picture = $row['ImagenIndividual'];
    $family_picture     = $row['ImagenFamiliar'];

    $file_location = $images_url . 'invitaciones-digitales/' . $cr_image;
    deleteFile($file_location);

    $file_location = $images_url . 'invitaciones-digitales/' . $r_image;
    deleteFile($file_location);

    $file_location = $images_url . 'invitaciones-digitales/' . $family_picture;
    deleteFile($file_location);

    $file_location = $images_url . 'invitaciones-digitales/' . $individual_picture;
    deleteFile($file_location);

    $query = "SELECT
        idGaleria,
        Imagen
      FROM galeria_de_invitaciones_digitales
      WHERE idInvitacion = '$invitation_id'
    ";

    $query_result = mysqli_query($mysqli, $query);
    $num_rows     = mysqli_num_rows($query_result);

    if ($num_rows) {
      while ($row = mysqli_fetch_array($query_result)) {
        $image_gallery = $row['Imagen'];

        $file_location  = $images_url . 'invitaciones-digitales/galeria/' . $image_gallery;
        deleteFile($file_location);
      }

      $query = "DELETE FROM galeria_de_invitaciones_digitales WHERE idInvitacion = '$invitation_id'";
      $query_result = mysqli_query($mysqli, $query);
    }

    $query = "DELETE FROM invitaciones_digitales WHERE idInvitacion = '$invitation_id'";
    $query_result = mysqli_query($mysqli, $query);

    if (!$query_result) {
      $response = array(
        'status' => 'error',
        'title' => '¡Error!, Intentelo nuevamente.'
      );
    }

    if ($query_result) {
      $response = array(
        'status' => 'success',
        'title' => 'La invitacion de "' . $invitation . '" ha sido eliminado correctamente.'
      );
    }
    break;

  default:
    break;
}

echo json_encode($response);
mysqli_close($mysqli);
