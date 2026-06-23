<?php $table_row_number = (($page - 1) * $per_page) + 1; ?>

<div class="table-responsive">
  <table class="table table-hover">
    <thead>
      <th style="width: 10px;">#</th>
      <th>Evento</th>
      <th>Negocio</th>
      <th class="text-right">Acciones</th>
    </thead>

    <tbody>
      <?php while ($row = mysqli_fetch_array($query_result)) :
        $row['gallery']     = getRecentEventGallery($row['idEvento']);
        $row['image']       = setRecentEventImage($row['Imagen']);
        $recent_event_data  = base64_encode(json_encode($row));
      ?>
        <tr>
          <td><b><?= $table_row_number; ?></b></td>
          <td><?= $row['Evento']; ?></td>
          <td><?= $row['Salon']; ?></td>
          <td class="text-right">
            <div class="btn-group">
              <button class="btn btn-danger btn-delete-recent-event" 
                type="button"
                data-recent-event="<?= $recent_event_data; ?>"
              >
                <i class="fas fa-trash-alt"></i>
              </button>

              <button class="btn btn-primary btn-edit-recent-event"
                type="button"
                data-recent-event="<?= $recent_event_data; ?>"
                data-toggle="modal"
                data-target="#modal-recent-events"
              >
                <i class="fas fa-pencil-alt"></i>
              </button>
            </div>
          </td>
        </tr>
        <?php $table_row_number++; ?>
      <?php endwhile; ?>
    </tbody>
  </table>
</div>

<?= paginate($page, $num_pages, 2, 'loadRecentEvents'); ?>