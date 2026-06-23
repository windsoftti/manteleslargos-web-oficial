<?php $table_row_number = (($page - 1) * $per_page) + 1; ?>

<div class="table-responsive">
  <table class="table table-hover">
    <thead>
      <th style="width: 10px;">#</th>
      <th>Tip</th>
      <th class="text-right">Acciones</th>
    </thead>

    <tbody>
      <?php while ($row = mysqli_fetch_array($query_result)) :
        $row['gallery'] = getTipGallery($row['idTip']);
        $row['image']   = setTipImage($row['Imagen']);
        $tip_data       = base64_encode(json_encode($row));
      ?>
        <tr>
          <td><b><?= $table_row_number; ?></b></td>
          <td><?= $row['Tip']; ?></td>
          <td class="text-right">
            <div class="btn-group">
              <button class="btn btn-danger btn-delete-tip" 
                type="button"
                data-tip="<?= $tip_data; ?>"
              >
                <i class="fas fa-trash-alt"></i>
              </button>

              <button class="btn btn-primary btn-edit-tip"
                type="button"
                data-tip="<?= $tip_data; ?>"
                data-toggle="modal"
                data-target="#modal-tips"
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

<?= paginate($page, $num_pages, 2, 'loadTips'); ?>