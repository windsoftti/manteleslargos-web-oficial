<?php $table_row_number = 1; ?>

<div class="table-responsive">
  <table class="table table-hover table-striped">
    <thead>
      <th style="width: 10px;">#</th>
      <th>Amenidad</th>
      <th class="text-right">Acciones</th>
    </thead>

    <tbody>
      <?php while ($row = mysqli_fetch_array($query_result)) : ?>
        <?php $amenity_data = json_encode($row); ?>
        <tr>
          <td class="align-middle"><b><?= $table_row_number ?></b></td>
          <td class="align-middle"><?= $row['Amenidad'] ?></td>
          <td class="align-middle text-right">
            <div class="btn-group btn-group-sm dropleft">
              <button type="button"
                class="btn btn-danger btn-delete-amenity"
                data-amenityId="<?= $row['idAmenidad'] ?>"
                data-amenity="<?= $row['Amenidad'] ?>"
              >
                <i class="fa fa-trash-alt"></i>
              </button>
              <button type="button"
                class="btn btn-primary btn-edit-amenity"
                data-amenity="<?= base64_encode($amenity_data) ?>"
                data-toggle="modal"
                data-target="#modal-add-edit-amenity"
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

<?php $pagination = paginate($page, $num_pages, 2, 'loadAmenities'); ?>
<?php echo $pagination; ?>

<script>
  $('.btn-edit-amenity').on('click', function () {
    const data = JSON.parse(atob($(this).attr('data-amenity')));

    changeModalTitle('Editar amenidad');
    hideInputWarnings();

    $('#amenityId').val(data.idAmenidad);
    $('#amenity').val(data.Amenidad);
    $('#action-amenities').val('edit_amenity');
  });
</script>