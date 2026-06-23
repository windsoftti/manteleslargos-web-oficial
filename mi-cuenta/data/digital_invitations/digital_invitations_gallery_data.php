<?php
include '../session.php';
include '../../inc/functions.inc.php';

$action       = isset($_POST['action']) && $_POST['action'] !== '' ? $_POST['action'] : '';
$file_url     = $images_absolute_url . 'invitaciones-digitales/galeria/';
$file_folder  = $images_url . 'invitaciones-digitales/galeria/';

switch ($action) {
  case 'list_image_gallery':
    $invitation_id = cleanStr($_POST['invitationId']);

    $query = "SELECT
        idGaleria,
        idInvitacion,
        Imagen
      FROM galeria_de_invitaciones_digitales
      WHERE idInvitacion = '$invitation_id'
    ";

    $query_result = mysqli_query($mysqli, $query);
    $num_rows     = mysqli_num_rows($query_result);

    if (!$num_rows) {
      $response['content'] = '';
    }

    if ($num_rows) {
      ob_start();

      while ($row = mysqli_fetch_array($query_result)) {
        $content_file_type = '';

        $content_file_type = '<img src="' . $file_url . $row['Imagen'] . '" class="img-fluid rounded" style="height: 48px; width: 48px">';
?>
        <div class="btn btn-default border d-flex flex-column justify-content-center align-items-center mr-2 p-0 multiple-input-item-server btn-delete-image-gallery" title="Click para eliminar a '<?= $row['Imagen'] ?>'" data-fileId="<?= $row['idGaleria'] ?>" data-file="<?= $row['Imagen'] ?>">
          <?= $content_file_type ?>
          <i class="fa fa-trash-alt text-danger fa-2x icon-hidden"></i>
          <input type="hidden" name="imageGallery-preview[]" value="<?= $file_url . $row['Imagen']; ?>">
        </div>
<?php
      }

      $attached_files = base64_encode(ob_get_clean());

      $response['content'] = $attached_files;
    }
    break;

  case 'delete_image_gallery':
    $file_id = cleanStr($_POST['fileId']);
    $file    = cleanStr($_POST['file']);

    $file_location = $file_folder . $file;

    $delete_file = deleteFile($file_location);

    if ($delete_file === 'not-deleted') {
      $response = array(
        'state'   => 'error',
        'title' => 'No se puede remover la imagen "' . $file . '", intentelo nuevamente.'
      );
    }

    if ($delete_file === 'deleted' || $delete_file === 'not-exist') {
      $query        = "DELETE FROM galeria_de_invitaciones_digitales WHERE idGaleria = '$file_id' AND Imagen = '$file'";
      $query_result = mysqli_query($mysqli, $query);

      if (!$query_result) {
        $response = array('state' => 'error', 'title' => '¡Error!, Intentelo nuevamente.');
      }

      if ($query_result) {
        $response = array('state' => 'success', 'title' => 'La imagen "' . $file . '" ha sido eliminado correctamente.');
      }
    }
    break;

  default:
    $response = array(
      'state'   => 'error',
      'title'   => '¡Error!',
      'message' => '¡Error!, Intentelo nuevamente.'
    );
    break;
}

echo json_encode($response);
mysqli_close($mysqli);
