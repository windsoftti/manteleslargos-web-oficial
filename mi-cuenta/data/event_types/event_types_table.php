<?php $table_row_number = 1; ?>

<div class="table-responsive">
  <table class="table table-hover table-striped">
    <thead>
      <th style="width: 10px;">#</th>
      <th>Tipo de evento</th>
      <th class="text-right">Acciones</th>
    </thead>

    <tbody>
      <?php while ($row = mysqli_fetch_array($query_result)) : ?>
        <?php $event_type_data = json_encode($row); ?>
        <tr>
          <td class="align-middle"><b><?= $table_row_number ?></b></td>
          <td class="align-middle"><?= $row['TipoEvento'] ?></td>
          <td class="align-middle text-right">
            <div class="btn-group btn-group-sm dropleft">
              <button type="button"
                class="btn btn-danger btn-delete-event-type"
                data-eventTypeId="<?= $row['idTipoEvento'] ?>"
                data-eventType="<?= $row['TipoEvento'] ?>"
              >
                <i class="fa fa-trash-alt"></i>
              </button>
              <button type="button"
                class="btn btn-primary btn-edit-event-type"
                data-eventType="<?= base64_encode($event_type_data) ?>"
                data-toggle="modal"
                data-target="#modal-add-edit-event-type"
              >
                <i class="fa fa-pencil-alt"></i>
              </button>
            </div>
          </td>
        </tr>

        <?php $table_row_number++; ?>
      <?php endwhile; ?>
    </tbody>
  </table>
</div>

<?php $pagination = paginate($page, $num_pages, 2, 'loadEventTypes'); ?>
<?php echo $pagination; ?>

<script>
  $('.btn-edit-event-type').on('click', function () {
    const data = JSON.parse(atob($(this).attr('data-eventType')));

    changeModalTitle('Editar tipo de evento');
    hideInputWarnings();

    $('#eventTypeId').val(data.idTipoEvento);
    $('#eventType').val(data.TipoEvento);
    $('#action-eventTypes').val('edit_event_type');

    if (data.Imagen) createGlobalFilePreview({ 
      idPicker: 'image',
      idFile: data.idTipoEvento,
      fileName: data.Imagen,
      extraClass: 'delete-image',
      uriImage: `${HOST_URL}images/tiposEventos/`
    });

    if (!data.Imagen) {
      cleanPicker('image', 'image', 'Agregar imagen');
    }
  });
</script>