<?php
date_default_timezone_set('America/Mexico_City');
include '../lib/user-session.php';
include '../lib/invitations-paginations.php';

$action = $_POST['action'];

$response = array(
  'status'  => 'error',
  'title'   => '¡Error!',
  'message' => 'Error inesperado, Intentalo nuevamente.',
  'content' => base64_encode('Error inesperado, Intentalo nuevamente.'),
);

$valid_extensions = array('jpeg', 'jpg', 'png', 'JPEG', 'JPG', 'PNG');

switch ($action) {
  case 'list_my_invitations':
    try {
      $user_id        = $_SESSION['session_user_id'];

      $page           = cleanStr($_POST['page']);
      $page           = $page != '' ? $page : 1;

      $per_page       = cleanStr($_POST['perPage']);
      $per_page       = $per_page != '' ? $per_page : 3;

      $start_rows     = ($page - 1) * $per_page;
      $stop_rows      = $per_page;

      $search_term    = cleanStr($_POST['searchTerm']);
      $search_by_term = $search_term != '' ? "
        NombrePersona LIKE '%$search_term%' OR
        NombreEvento  LIKE '%$search_term%'
      " : "1=1";

      $search_by_user = "idUsuario = $user_id";

      $c_from         = "FROM invitaciones_digitales";

      $c_where = "WHERE
          ($search_by_term) AND
          ($search_by_user) AND
          (Eliminado = 'No')
        ORDER BY idInvitacion
        DESC
      ";

      $limit_rows         = "LIMIT $start_rows, $stop_rows";

      $query_count        = "SELECT idInvitacion $c_from $c_where";
      $query_result_count = mysqli_query($mysqli, $query_count);
      $total              = mysqli_num_rows($query_result_count);
      $num_pages          = ceil($total / $stop_rows);

      $response['message'] = base64_encode($query_count);

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
            idInvitacion,
            idUsuario,
            NombrePersona,
            NombreEvento,
            ImagenIndividual,
            Plantilla,
            Referencia,
            Slug
          $c_from
          $c_where
          $limit_rows
        ";

        $query_result = mysqli_query($mysqli, $query);

        ob_start();

        while ($row = mysqli_fetch_array($query_result)) :
          $invitation_item_data     = base64_encode(json_encode($row));

          $invitation_item_title    = $row['NombrePersona'];
          $invitation_item_subtitle = $row['NombreEvento'];
          $invitation_item_template = $row['Plantilla'];
          $invitation_item_slug     = $row['Referencia'] . '-' . $row['Slug'];
          $invitation_item_img      = setInvitationImage($row['ImagenIndividual']);
          $invitation_item_url      = BASE_URL . '/invitaciones/' . $invitation_item_template . '/' . $invitation_item_slug;

          include '../../src/components/invitation-item.php';
        endwhile;

        $content = base64_encode(ob_get_clean());
        $pagination = invitationsPaginate($page, $num_pages, 2, 'loadInvitations');

        $response = array(
          'status'      => 'success',
          'content'     => $content,
          'pagination'  => $pagination,
          'results'     => $total
        );
      endif;
    } catch (Exception $e) {
      $response['message'] = $e->getMessage();
    }
    break;

  case 'add_invitation':
    try {
      $user_id              = $_SESSION['session_user_id'];

      # STEP 1
      $invitation_type      = cleanStr($_POST['invitationType']);
      $names                = cleanStr($_POST['names']);
      $event_name           = cleanStr($_POST['eventName']);
      $contact              = cleanStr($_POST['contact']);
      $commemorative_phrase = cleanStr($_POST['commemorativePhrase']);
      $template             = cleanStr($_POST['template']);
      $principal_color      = cleanStr($_POST['principalColor']);
      $secondary_color      = cleanStr($_POST['secondaryColor']);

      # STEP 2
      $cr_picture           = $_FILES['CRPicture'];
      $cr_place             = cleanStr($_POST['CRPlace']);
      $cr_date_time         = date("Y-m-d H:i:s", strtotime(str_replace('/', '-', $_POST['CRDateTime'])));
      $cr_latitude          = cleanStr($_POST['CRLatitude']);
      $cr_longitude         = cleanStr($_POST['CRLongitude']);
      $cr_address           = cleanStr($_POST['CRAddress']);

      $r_picture            = $_FILES['RPicture'];
      $r_place              = cleanStr($_POST['RPlace']);
      $r_date_time          = date("Y-m-d H:i:s", strtotime(str_replace('/', '-', $_POST['RDateTime'])));
      $r_latitude           = cleanStr($_POST['RLatitude']);
      $r_longitude          = cleanStr($_POST['RLongitude']);
      $r_address            = cleanStr($_POST['RAddress']);

      # STEP 3
      $individual_picture   = $_FILES['individualPicture'];
      $family_picture       = $_FILES['familyPicture'];
      $image_gallery        = $_FILES['imageGallery'];

      $reference            = date('YmdHis');
      $invitation_slug      = createSlug($names . '-' . $event_name);

      # PICTURES QUERY
      $query_picture        = "";
      $query_insert_picture = "";

      /* $response['message'] = json_encode($_FILES);
      echo json_encode($response);
      die; */

      # AGREGAR LA IMAGEN DE CEREMONIA RELIGIOSA
      if ($cr_picture['name']) :
        $proccess_cr_picture = processFile(
          $cr_picture,
          $valid_extensions,
          INVITATIONS_IMAGE_FOLDER,
          'ceremonia-religiosa'
        );

        if ($proccess_cr_picture !== 'no-move' && $proccess_cr_picture !== 'no-valid') :
          $query_picture         .= ", CRImagen";
          $query_insert_picture  .= ", '$proccess_cr_picture'";
        endif;
      endif;

      # AGREGAR LA IMAGEN DE RECEPCIÓN
      if ($r_picture['name']) :
        $proccess_r_picture = processFile(
          $r_picture,
          $valid_extensions,
          INVITATIONS_IMAGE_FOLDER,
          'recepcion'
        );

        if ($proccess_r_picture !== 'no-move' && $proccess_r_picture !== 'no-valid') :
          $query_picture         .= ", RImagen";
          $query_insert_picture  .= ", '$proccess_r_picture'";
        endif;
      endif;

      # AGREGAR LA IMAGEN INDIVIDUAL
      if ($individual_picture['name']) :
        $proccess_individual_picture = processFile(
          $individual_picture,
          $valid_extensions,
          INVITATIONS_IMAGE_FOLDER,
          'individual'
        );

        if ($proccess_individual_picture !== 'no-move' && $proccess_individual_picture !== 'no-valid') :
          $query_picture         .= ", ImagenIndividual";
          $query_insert_picture  .= ", '$proccess_individual_picture'";
        endif;
      endif;

      # AGREGAR LA IMAGEN FAMILIAR
      if ($family_picture['name']) :
        $proccess_family_picture = processFile(
          $family_picture,
          $valid_extensions,
          INVITATIONS_IMAGE_FOLDER,
          'familiar'
        );

        if ($proccess_family_picture !== 'no-move' && $proccess_family_picture !== 'no-valid') :
          $query_picture         .= ", ImagenFamiliar";
          $query_insert_picture  .= ", '$proccess_family_picture'";
        endif;
      endif;

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
          Slug,
          Referencia
          $query_picture
        ) VALUES (
          '$user_id',
          '$names',
          '$event_name',
          '$contact',
          '$commemorative_phrase',
          '$invitation_type',
          '$principal_color',
          '$secondary_color',
          '$cr_place',
          '$cr_date_time',
          '$cr_address',
          '$cr_latitude',
          '$cr_longitude',
          '$r_place',
          '$r_date_time',
          '$r_address',
          '$r_latitude',
          '$r_longitude',
          '$template',
          '$invitation_slug',
          '$reference'
          $query_insert_picture
        )
      ";

      $query_result = mysqli_query($mysqli, $query);

      if ($query_result) :
        $invitation_id = mysqli_insert_id($mysqli);

        # AGREGAR IMAGENES DE LA GALERÍA
        $image_of_gallery = processMultipleFiles(
          $image_gallery,
          $valid_extensions,
          INVITATIONS_GALLERY_FOLDER,
          'gallery'
        );

        foreach ($image_of_gallery as $key => $value) :
          $image = $image_of_gallery[$key];

          $query = "INSERT INTO galeria_de_invitaciones_digitales (
              idInvitacion,
              Imagen
            ) VALUES (
              $invitation_id,
              '$image'
            )
          ";

          mysqli_query($mysqli, $query);
        endforeach;

        $response = array(
          'status'  => 'success',
          'title'   => '¡Datos guardados!',
          'message' => 'La invitacion de "' . $names . '" ha sido creada correctamente.'
        );
      endif;
    } catch (Exception $e) {
      $response['message'] = $e->getMessage();
    }
    break;

  case 'edit_invitation':
    try {
      $user_id              = $_SESSION['session_user_id'];
      $invitation_id        = cleanStr($_POST['invitationId']);

      # STEP 1
      $invitation_type      = cleanStr($_POST['invitationType']);
      $names                = cleanStr($_POST['names']);
      $event_name           = cleanStr($_POST['eventName']);
      $contact              = cleanStr($_POST['contact']);
      $commemorative_phrase = cleanStr($_POST['commemorativePhrase']);
      $template             = cleanStr($_POST['template']);
      $principal_color      = cleanStr($_POST['principalColor']);
      $secondary_color      = cleanStr($_POST['secondaryColor']);

      # STEP 2
      $cr_picture           = $_FILES['CRPicture'];
      $cr_place             = cleanStr($_POST['CRPlace']);
      $cr_date_time         = date("Y-m-d H:i:s", strtotime(str_replace('/', '-', $_POST['CRDateTime'])));
      $cr_latitude          = cleanStr($_POST['CRLatitude']);
      $cr_longitude         = cleanStr($_POST['CRLongitude']);
      $cr_address           = cleanStr($_POST['CRAddress']);

      $r_picture            = $_FILES['RPicture'];
      $r_place              = cleanStr($_POST['RPlace']);
      $r_date_time          = date("Y-m-d H:i:s", strtotime(str_replace('/', '-', $_POST['RDateTime'])));
      $r_latitude           = cleanStr($_POST['RLatitude']);
      $r_longitude          = cleanStr($_POST['RLongitude']);
      $r_address            = cleanStr($_POST['RAddress']);

      # STEP 3
      $individual_picture   = $_FILES['individualPicture'];
      $family_picture       = $_FILES['familyPicture'];
      $image_gallery        = $_FILES['imageGallery'];
      $image_gallery_items  = $_FILES['imageGallery'];

      $invitation_slug      = createSlug($names . '-' . $event_name);
      $image_names          = getInvitationImageNames($invitation_id);

      $original_image_gallery = getInvitationGalleryIds($invitation_id);
      $new_image_gallery      = $_POST['imageGallery-items'];

      $array_diff = array_diff($original_image_gallery, $new_image_gallery);

      $query_picture = "";

      # AGREGAR LA IMAGEN DE CEREMONIA RELIGIOSA
      if ($cr_picture['name']) :
        $proccess_cr_picture = processFile(
          $cr_picture,
          $valid_extensions,
          INVITATIONS_IMAGE_FOLDER,
          'ceremonia-religiosa'
        );

        if ($proccess_cr_picture !== 'no-move' && $proccess_cr_picture !== 'no-valid') :
          deleteFile(INVITATIONS_IMAGE_FOLDER . $image_names['CRImagen']);
          $query_picture .= ", CRImagen = '$proccess_cr_picture'";
        endif;
      endif;

      # AGREGAR LA IMAGEN DE RECEPCIÓN
      if ($r_picture['name']) :
        $proccess_r_picture = processFile(
          $r_picture,
          $valid_extensions,
          INVITATIONS_IMAGE_FOLDER,
          'recepcion'
        );

        if ($proccess_r_picture !== 'no-move' && $proccess_r_picture !== 'no-valid') :
          deleteFile(INVITATIONS_IMAGE_FOLDER . $image_names['RImagen']);
          $query_picture .= ", RImagen = '$proccess_r_picture'";
        endif;
      endif;

      # AGREGAR LA IMAGEN INDIVIDUAL
      if ($individual_picture['name']) :
        $proccess_individual_picture = processFile(
          $individual_picture,
          $valid_extensions,
          INVITATIONS_IMAGE_FOLDER,
          'individual'
        );

        if ($proccess_individual_picture !== 'no-move' && $proccess_individual_picture !== 'no-valid') :
          deleteFile(INVITATIONS_IMAGE_FOLDER . $image_names['ImagenIndividual']);
          $query_picture .= ", ImagenIndividual = '$proccess_individual_picture'";
        endif;
      endif;

      # AGREGAR LA IMAGEN FAMILIAR
      if ($family_picture['name']) :
        $proccess_family_picture = processFile(
          $family_picture,
          $valid_extensions,
          INVITATIONS_IMAGE_FOLDER,
          'familiar'
        );

        if ($proccess_family_picture !== 'no-move' && $proccess_family_picture !== 'no-valid') :
          deleteFile(INVITATIONS_IMAGE_FOLDER . $image_names['ImagenFamiliar']);
          $query_picture .= ", ImagenFamiliar = '$proccess_family_picture'";
        endif;
      endif;

      $query = "UPDATE invitaciones_digitales SET
          NombrePersona   = '$names',
          NombreEvento    = '$event_name',
          Telefono        = '$contact',
          Frase           = '$commemorative_phrase',
          TipoInvitacion  = '$invitation_type',
          ColorPrincipal  = '$principal_color',
          ColorSecundario = '$secondary_color',
          CRLugar         = '$cr_place',
          CRFecha         = '$cr_date_time',
          CRDireccion     = '$cr_address',
          CRLatitud       = '$cr_latitude',
          CRLongitud      = '$cr_longitude',
          RLugar          = '$r_place',
          RFecha          = '$r_date_time',
          RDireccion      = '$r_address',
          RLatitud        = '$r_latitude',
          RLongitud       = '$r_longitude',
          Plantilla       = '$template',
          Slug            = '$invitation_slug'
          $query_picture
        WHERE
          idInvitacion  = $invitation_id AND
          idUsuario     = $user_id
      ";

      $query_result = mysqli_query($mysqli, $query);

      if ($query_result) :
        # ELIMINAR LAS IMAGENES QUE SE REMOVIERON
        deleteInvitationImageGallery(
          $original_image_gallery,
          $new_image_gallery
        );

        # AGREGAR IMAGENES DE LA GALERÍA
        $image_of_gallery = processMultipleFiles(
          $image_gallery,
          $valid_extensions,
          INVITATIONS_GALLERY_FOLDER,
          'gallery'
        );

        foreach ($image_of_gallery as $key => $value) :
          $image = $image_of_gallery[$key];

          $query = "INSERT INTO galeria_de_invitaciones_digitales (
              idInvitacion,
              Imagen
            ) VALUES (
              $invitation_id,
              '$image'
            )
          ";

          mysqli_query($mysqli, $query);
        endforeach;

        $response = array(
          'status'  => 'success',
          'title'   => '¡Datos guardados!',
          'message' => 'La invitacion de "' . $names . '" ha sido actualizada correctamente.'
        );
      endif;
    } catch (Exception $e) {
      $response['message'] = $e->getMessage();
    }
    break;

  case 'delete_invitation':
    $user_id        = $_SESSION['session_user_id'];
    $invitation_id  = cleanStr($_POST['itemId']);

    $query = "UPDATE invitaciones_digitales SET Eliminado = 'Si' WHERE
        idInvitacion  = $invitation_id AND
        idUsuario     = $user_id
    ";

    $query_result = mysqli_query($mysqli, $query);

    if ($query_result) $response = array(
      'status'  => 'success',
      'message' => 'La invitación se eliminó correctamente'
    );
    break;
}

echo json_encode($response);
mysqli_close($mysqli);
die();
