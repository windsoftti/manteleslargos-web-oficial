<?php $table_row_number = 1; ?>

<div class="table-responsive">
  <table class="table table-hover table-striped">
    <thead>
      <th style="width: 10px;">#</th>
      <th>Tipo de proveedor</th>
      <th class="text-right">Acciones</th>
    </thead>

    <tbody>
      <?php while ($row = mysqli_fetch_array($query_result)) : ?>
        <?php $vendor_type_data = json_encode($row); ?>
        <tr>
          <td class="align-middle"><b><?= $table_row_number ?></b></td>
          <td class="align-middle"><?= $row['TipoProveedor'] ?></td>
          <td class="align-middle text-right">
            <div class="btn-group btn-group-sm dropleft">
              <button type="button"
                class="btn btn-danger btn-delete-vendor-type"
                data-vendorTypeId="<?= $row['idTipoProveedor'] ?>"
                data-vendorType="<?= $row['TipoProveedor'] ?>"
              >
                <i class="fa fa-trash-alt"></i>
              </button>
              <button type="button"
                class="btn btn-primary btn-edit-vendor-type"
                data-vendorType="<?= base64_encode($vendor_type_data) ?>"
                data-toggle="modal"
                data-target="#modal-add-edit-vendor-type"
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

<?php $pagination = paginate($page, $num_pages, 2, 'loadVendorTypes'); ?>
<?php echo $pagination; ?>

<script>
  $('.btn-edit-vendor-type').on('click', function () {
    const data = JSON.parse(atob($(this).attr('data-vendorType')));

    changeModalTitle('Editar tipo de proveedor');
    hideInputWarnings();

    $('#vendorTypeId').val(data.idTipoProveedor);
    $('#vendorType').val(data.TipoProveedor);
    $('#action-vendorTypes').val('edit_vendor_type');

    if (data.Imagen) createGlobalFilePreview({ 
      idPicker: 'image',
      idFile: data.idTipoProveedor,
      fileName: data.Imagen,
      extraClass: 'delete-image',
      uriImage: `${HOST_URL}images/tiposProveedores/`
    });

    if (!data.Imagen) {
      cleanPicker('image', 'image', 'Agregar imagen');
    }
  });
</script>