<?php $table_row_number = 1; ?>

<div class="table-responsive">
  <table class="table table-hover table-striped">
    <thead>
      <th>Fecha</th>
      <th>Concepto</th>
      <th>Descripción</th>
      <th class="text-right">Costo</th>
      <th class="text-right">Acciones</th>
    </thead>

    <tbody>
      <?php while ($row = mysqli_fetch_array($query_egresos_result)) : ?>
        <?php $egreso_data = json_encode($row); ?>
        <tr>
          <td class="align-middle"><?= $row['Fecha'] ?></td>
          <td class="align-middle"><?= $row['Concepto'] ?></td>
          <td class="align-middle"><?= $row['Descripcion'] ?></td>
          <td class="align-middle text-right"><?= $row['Costo'] ?></td>
          <td class="align-middle text-right">
            <div class="btn-group btn-group-sm dropleft">
              <div class="dropdown dropleft">
                <button id="actions-dropdown" class="btn btn-primary btn-sm" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                  <i class="fa fa-ellipsis-v"></i>
                </button>

                <div class="dropdown-menu" aria-labelledby="actions-dropdown">
                  <a class="dropdown-item btn-edit-egreso" data-egreso="<?= base64_encode($egreso_data) ?>" data-toggle="modal" data-target="#modal-add-edit-egreso" href="javascript:void(0)">
                    <i class="fas fa-pencil-alt mr-1"></i>Editar
                  </a>

                  <div class="dropdown-divider"></div>

                  <a class="dropdown-item btn-delete-egreso" data-idEgreso="<?= $row['idEgreso'] ?>" data-egreso="<?= $row['Concepto'] ?>" href="javascript:void(0)">
                    <i class="fas fa-trash-alt text-danger mr-1"></i>Eliminar
                  </a>
                </div>
              </div>
            </div>
          </td>
        </tr>

        <?php $table_row_number++; ?>
      <?php endwhile; ?>
    </tbody>
  </table>
</div>

<?php $pagination = paginate($page, $num_pages, 2, 'loadEgresos'); ?>
<?php echo $pagination; ?>

<script>
  $('.btn-edit-egreso').on('click', function() {
    const data = JSON.parse(atob($(this).attr('data-egreso')));

    changeModalTitle('Editar egreso');
    hideInputWarnings();

    $('#idEgreso').val(data.idEgreso);
    $('#date').val(data.Fecha);
    $('#concept').val(data.Concepto);
    $('#cost').val(data.Costo);
    $('#description').val(data.Descripcion);
    $('#action-egresos').val('edit_egreso');
  });
</script>