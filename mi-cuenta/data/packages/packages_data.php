<?php
include '../session.php';
include '../../inc/functions.inc.php';

$action = isset($_POST['action']) && $_POST['action'] !== '' ? $_POST['action'] : '';

switch ($action) {
  case 'list_packages':
    $business_id = $_SESSION['session_business_id'];

    $tipos_eventos = array();

    $query = "SELECT idTipoEvento, TipoEvento FROM tipo_eventos";
    $query_result = mysqli_query($mysqli, $query);

    while ($row = mysqli_fetch_array($query_result)) {
      array_push($tipos_eventos, array(
        'idTipoEvento'  => $row['idTipoEvento'],
        'tipoEvento'    => $row['TipoEvento']
      ));
    }

    $query = "SELECT
        idPaquete,
        idNegocio,
        Paquete,
        Descripcion,
        Precio,
        Orientacion
      FROM paquetes_negocios
      WHERE idNegocio = '$business_id'
    ";

    $query_result = mysqli_query($mysqli, $query);
    $num_rows     = mysqli_num_rows($query_result);

    if (!$num_rows) {
      $response['content'] = '';
    }

    if ($num_rows) {
      ob_start();

      $count = 0;

      while ($row = mysqli_fetch_array($query_result)) : ?>
        <?php
        $cat_tipo_eventos = array();
        
        $query_pte = "SELECT idTipoEvento FROM catalogo_paquete_tipos_eventos WHERE idPaquete = '$row[idPaquete]'";
        $query_result_pte = mysqli_query($mysqli, $query_pte);

        while ($row_pte = mysqli_fetch_array($query_result_pte)) {
          array_push($cat_tipo_eventos, $row_pte['idTipoEvento']);
        }
        ?>
        <div class="col-md-6" id="col-paquete-<?= $count ?>">
          <div class="col-md-12 card">
            <div class="modal-header">
              <div class="d-flex justify-content-between align-items-center w-100">
                <h5 class="modal-title">Paquete <?= $count + 1 ?></h5>
                <button type="button" class="close btn-remove-paquete" data-id="<?= $count ?>" data-packageId="<?= $row['idPaquete'] ?>" data-package="<?= $row['Paquete'] ?>">
                  <i class="fal fa-times"></i>
                </button>
              </div>
            </div>

            <div class="row text-left">
              <div class="col-sm-12 col-md-12">
                <div class="form-group">
                  <label for="nombrePaquete<?= $count ?>" class="text-heading"><span class="text-danger">*</span>Nombre del paquete</label>
                  <input type="text" name="nombrePaquete[]" class="form-control form-control-lg" id="nombrePaquete<?= $count ?>" value="<?= $row['Paquete'] ?>">
                </div>
              </div>

              <div class="col-xs-12 col-sm-6 col-md-8">
                <div class="form-group">
                  <label for="orientacionPaquete<?= $count ?>" class="text-heading"><span class="text-danger">*</span>Modalidad</label>
                  <select name="orientacionPaquete[]" id="orientacionPaquete<?= $count ?>" class="form-control form-control-lg">
                    <option value="">Seleccionar</option>
                    <option 
                      value="Por persona"
                      <?php if($row['Orientacion'] == 'Por persona'): ?>
                        selected
                      <?php endif; ?>
                    >
                      Por persona
                    </option>
                    <option
                      value="Por evento"
                      <?php if($row['Orientacion'] == 'Por evento'): ?>
                        selected
                      <?php endif; ?>
                    >
                      Por evento
                    </option>
                  </select>
                </div>
              </div>
              
              <div class="col-xs-12 col-sm-6 col-md-4">
                <div class="form-group">
                  <label for="precioPaquete<?= $count ?>" class="text-heading"><span class="text-danger">*</span>Precio</label>
                  <input type="text" name="precioPaquete[]" class="form-control form-control-lg number-input input-number" id="precioPaquete<?= $count ?>" value="<?= $row['Precio'] ?>">
                </div>
              </div>

              <div class="col-sm-12 col-md-12">
                <div class="form-group">
                  <label for="descripcionPaquete<?= $count ?>" class="text-heading"><span class="text-danger">*</span>Descripción <i class="fal fa-question-circle fa-2x pointer m-0 text-info tooltip-icon" data-toggle="tooltip" data-placement="right" title="Describe de manera detallada las características del servicio que proporcionas para que tus posibles clientes conozcan de manera correcta el producto que van a adquirir." style="font-size: 18px;"></i></label>
                  <textarea rows="3" id="descripcionPaquete<?= $count ?>" class="form-control form-control-lg"><?= $row['Descripcion']; ?></textarea>
                </div>
              </div>

              <div class="col-sm-12 col-md-12">
                <div class="form-group">
                  <label for="tipoEventoPaquete<?= $count ?>">Tipo de evento</label>
                  <select id="tipoEventoPaquete<?= $count ?>" name="tipoEventoPaquete<?= $count ?>[]" class="form-control form-control-lg select2" multiple="multiple" data-placeholder="Seleccionar los tipos de eventos" style="width: 100%;">
                    <?php foreach ($tipos_eventos as $key => $value) : ?>
                      <option
                        value="<?= $tipos_eventos[$key]['idTipoEvento'] ?>"
                        <?php if(in_array($tipos_eventos[$key]['idTipoEvento'], $cat_tipo_eventos)): ?>
                          selected
                        <?php endif; ?>
                      >
                        <?= $tipos_eventos[$key]['tipoEvento'] ?>
                      </option>
                    <?php endforeach; ?>
                  </select>
                </div>
              </div>

              <input id="counter<?= $count ?>" type="hidden" name="counter[]" value="<?= $count ?>" />
            </div>

            <input type="hidden" name="idPaquete[]" value="<?= $row['idPaquete'] ?>">
          </div>
        </div>

        <script>
          createNewEditEditor(<?= $count; ?>);
        </script>
        <?php $count++; ?>
<?php endwhile;

      $attached_files = base64_encode(ob_get_clean());

      $response['content'] = $attached_files;
    }
    break;

  case 'delete_package':
    $package_id = cleanStr($_POST['packageId']);
    $package    = cleanStr($_POST['package']);

    $query        = "DELETE FROM paquetes_negocios WHERE idPaquete = '$package_id' AND Paquete = '$package'";
    $query_result = mysqli_query($mysqli, $query);

    if (!$query_result) {
      $response = array('state' => 'error', 'title' => '¡Error!, Intentelo nuevamente.');
    }

    if ($query_result) {
      $response = array('state' => 'success', 'title' => 'El paquete "' . $package . '" ha sido eliminado correctamente.');

      $query = "DELETE FROM catalogo_paquete_tipos_eventos WHERE idPaquete = '$package_id'";
      mysqli_query($mysqli, $query);
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
