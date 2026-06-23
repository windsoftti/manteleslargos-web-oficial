<?php $table_row_number = 1; ?>

<div class="row">
  <?php while ($row = mysqli_fetch_array($query_result)) : ?>
    <?php $user_data = json_encode($row); ?>

    <div class="col-12 col-lg-6 mb-3">
      <div class="card">
        <div class="card-body">
          <div class="d-flex w-100" style="gap: 0.8rem;flex-wrap: nowrap">
            <div class="d-flex flex-column mr-auto" style="font-size: 0.9rem;width: 85%;">
              <a class="font-weight-bold fs-5 text-dark" href="#">
                <?= $row['Usuario']; ?>
              </a>

              <span><?= $row['Correo']; ?></span>
              <span><?= formatPhoneNumber($row['Celular']); ?></span>
            </div>

            <div>
              <div class="dropdown dropleft">
                <button id="actions-dropdown" class="btn btn-primary btn-sm" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                  <i class="fa fa-ellipsis-v"></i>
                </button>

                <div class="dropdown-menu" aria-labelledby="actions-dropdown">
                  <a class="dropdown-item btn-edit-user" data-user="<?= base64_encode($user_data) ?>" data-toggle="modal" data-target="#modal-add-edit-user" href="javascript:void(0)">
                    <i class="fas fa-pencil-alt mr-1"></i>Editar
                  </a>

                  <a class="dropdown-item btn-user-permisions" data-user="<?= base64_encode($user_data) ?>" data-toggle="modal" data-target="#modal-permissions" href="javascript:void(0)">
                    <i class="fas fa-lock mr-1"></i>Permisos
                  </a>

                  <div class="dropdown-divider"></div>

                  <a class="dropdown-item btn-delete-user" data-userId="<?= $row['idUsuario'] ?>" data-user="<?= $row['Usuario'] ?>" href="javascript:void(0)">
                    <i class="fas fa-trash-alt text-danger mr-1"></i>Eliminar
                  </a>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  <?php endwhile; ?>
</div>

<?php $pagination = paginate($page, $num_pages, 2, 'loadUsers'); ?>
<?php echo $pagination; ?>

<script>
  $('.btn-edit-user').on('click', function() {
    const data = JSON.parse(atob($(this).attr('data-user')));

    changeModalTitle('Editar usuario');
    hideInputWarnings();

    $('#userId').val(data.idUsuario);
    $('#user').val(data.Usuario);
    $('#email').val(data.Correo);
    $('#phone').val(data.Telefono);
    $('#cellPhone').val(data.Celular);
    $('#username').val(data.Username);
    $('#level').val(data.Nivel);

    /* $('input:checkbox').removeAttr('checked');
  
    if (data.PermisoNuevoNegocio == 'Si') $('#permisoNuevoNegocio').prop('checked', true);
    if (data.PermisoListarNegocios == 'Si') $('#permisoListarNegocios').prop('checked', true);
    if (data.PermisoCotizaciones == 'Si') $('#permisoCotizaciones').prop('checked', true);
    if (data.PermisoEventosProximos == 'Si') $('#permisoEventosProximos').prop('checked', true); */

    $('#action-users').val('edit_user');
    $('#credentials').hide();
  });

  $('.btn-user-permisions').on('click', function() {
    const data = JSON.parse(atob($(this).attr('data-user')));

    $('#permissionsUserId').val(data.idUsuario);

    loadPermissions(data.idUsuario);
  });
</script>