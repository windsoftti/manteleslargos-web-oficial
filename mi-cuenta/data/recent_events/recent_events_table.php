<?php $table_row_number = 1; ?>

<div class="table-responsive">
  <table class="table table-hover table-striped">
    <thead>
      <th style="width: 10px;">#</th>
      <th>Evento</th>
      <th>Negocio</th>
      <th>Fecha</th>
      <th class="text-right">Acciones</th>
    </thead>

    <tbody>
      <?php while ($row = mysqli_fetch_array($query_result)) : ?>
        <?php $recent_event_data = json_encode($row); ?>
        <tr>
          <td class="align-middle"><b><?= $table_row_number ?></b></td>
          <td class="align-middle"><?= $row['Evento'] ?></td>
          <td class="align-middle"><?= $row['Salon'] ?></td>
          <td class="align-middle"><?= $row['Fecha'] ?></td>
          <td class="align-middle text-right">
            <div class="btn-group btn-group-sm dropleft">
              <button type="button"
                class="btn btn-danger btn-delete-recent-event"
                data-recentEventId="<?= $row['idEvento'] ?>"
                data-recentEvent="<?= $row['Evento'] ?>"
              >
                <i class="fa fa-trash-alt"></i>
              </button>
              <button type="button"
                class="btn btn-primary btn-edit-recent-event"
                data-recentEvent="<?= base64_encode($recent_event_data) ?>"
                data-toggle="modal"
                data-target="#modal-add-edit-recent-event"
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

<?php $pagination = paginate($page, $num_pages, 2, 'loadRecentEvents'); ?>
<?php echo $pagination; ?>