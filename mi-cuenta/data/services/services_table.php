<?php $table_row_number = 1; ?>

<div class="table-responsive">
  <table class="table table-hover table-striped">
    <thead>
      <th style="width: 10px;">#</th>
      <th>Servicio</th>
      <th class="text-right">Acciones</th>
    </thead>

    <tbody>
      <?php while ($row = mysqli_fetch_array($query_result)) : ?>
        <?php $service_data = json_encode($row); ?>
        <tr>
          <td class="align-middle"><b><?= $table_row_number ?></b></td>
          <td class="align-middle"><?= $row['Servicio'] ?></td>
          <td class="align-middle text-right">
            <div class="btn-group btn-group-sm dropleft">
              <button type="button"
                class="btn btn-danger btn-delete-service"
                data-serviceId="<?= $row['idServicio'] ?>"
                data-service="<?= $row['Servicio'] ?>"
              >
                <i class="fa fa-trash-alt"></i>
              </button>
              <button type="button"
                class="btn btn-primary btn-edit-service"
                data-service="<?= base64_encode($service_data) ?>"
                data-toggle="modal"
                data-target="#modal-add-edit-service"
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

<?php $pagination = paginate($page, $num_pages, 2, 'loadServices'); ?>
<?php echo $pagination; ?>

<script>
  $('.btn-edit-service').on('click', function () {
    const data = JSON.parse(atob($(this).attr('data-service')));

    changeModalTitle('Editar servicio');
    hideInputWarnings();

    $('#serviceId').val(data.idServicio);
    $('#service').val(data.Servicio);
    $('#action-services').val('edit_service');
  });
</script>