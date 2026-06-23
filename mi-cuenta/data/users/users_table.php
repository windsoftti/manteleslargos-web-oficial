<?php $table_row_number = 1; ?>

<div class="table-responsive">
  <table class="table table-hover table-striped">
    <thead>
      <th style="width: 10px;">#</th>
      <th>Usuario</th>
      <th>Correo</th>
      <th>Telefono</th>
      <th>Celular</th>
      <th class="text-right">Acciones</th>
    </thead>

    <tbody>
      <?php while ($row = mysqli_fetch_array($query_result)) : ?>
        <?php $user_data = json_encode($row); ?>
        <tr>
          <td><b><?= $table_row_number ?></b></td>
          <td><?= $row['Usuario'] ?></td>
          <td>
            <?php if (strlen($row['Correo']) > 21) : ?>
              <?= substr($row['Correo'], 0, 21) ?>...
            <?php endif; ?>

            <?php if (strlen($row['Correo']) <= 21) : ?>
              <?= $row['Correo'] ?>
            <?php endif; ?>
          </td>
          <td>
            <?php if ($row['Telefono']) : ?>
              <?= formatPhoneNumber($row['Telefono']) ?>
            <?php endif; ?>

            <?php if (!$row['Telefono']) : ?>
              <span class="badge badge-danger">Sin telefono</span>
            <?php endif; ?>
          </td>
          <td><?= formatPhoneNumber($row['Celular']) ?></td>
          <td class="text-right">
            <div class="btn-group btn-group-sm dropleft">
              <?php if ($type == 'administrador') : ?>
                <button type="button"
                  class="btn btn-danger btn-delete-user"
                  data-userId="<?= $row['idUsuario'] ?>"
                  data-user="<?= $row['Usuario'] ?>"
                >
                  <i class="fas fa-trash-alt"></i>
                </button>

                <button type="button"
                  class="btn btn-primary btn-edit-user"
                  data-user="<?= base64_encode($user_data) ?>"
                  data-toggle="modal"
                  data-target="#modal-add-edit-user"
                >
                  <i class="fas fa-pencil-alt"></i>
                </button>
              <?php endif; ?>

              <button type="button"
                title="Enviar credenciales de acceso"
                class="btn btn-white btn-tone btn-send-credentials"
                data-user="<?= $row['Usuario'] ?>"
                data-email="<?= $row['Correo'] ?>"
              >
                <i class="fas fa-paper-plane"></i>
              </button>
            </div>
          </td>
        </tr>

        <?php $table_row_number++; ?>
      <?php endwhile; ?>
    </tbody>
  </table>
</div>

<?php $pagination = paginate($page, $num_pages, 2, 'loadUsers'); ?>
<?php echo $pagination; ?>

<script>
  $('.btn-edit-user').on('click', function () {
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
    $('#action-users').val('edit_user');
    $('#credentials').hide();
  });
</script>