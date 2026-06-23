<?php $table_row_number = (($page - 1) * $per_page) + 1; ?>

<div class="table-responsive">
  <table class="table table-hover">
    <thead>
      <th style="width: 10px;">#</th>
      <th>Usuario</th>
      <th>Correo</th>
      <th>Celular</th>
      <th class="text-right">Acciones</th>
    </thead>

    <tbody>
      <?php while ($row = mysqli_fetch_array($query_result)) :
        $user_data = base64_encode(json_encode($row));
      ?>
        <tr>
          <td><b><?= $table_row_number; ?></b></td>
          <td><?= $row['FullName']; ?></td>
          <td><?= $row['Email']; ?></td>
          <td>
            <?php if($row['Phone']): ?>
              <?= formatPhoneNumber($row['Phone']); ?>
            <?php endif; ?>

            <?php if(!$row['Phone']): ?>
              <span class="badge badge-danger">Sin teléfono</span>
            <?php endif; ?>
          </td>
          <td class="text-right">
            <div class="btn-group">
              <button class="btn btn-danger btn-delete-user" 
                type="button"
                data-user="<?= $user_data; ?>"
              >
                <i class="fas fa-trash-alt"></i>
              </button>

              <button class="btn btn-primary btn-edit-user"
                type="button"
                data-user="<?= $user_data; ?>"
                data-password="<?= decrypt($row['UserPassword'], MYSQLI_PASSWORD_SECRET); ?>"
                data-toggle="modal"
                data-target="#modal-users"
              >
                <i class="fas fa-pencil-alt"></i>
              </button>

              <button class="btn btn-default btn-send-credentials"
                type="button"
                data-user="<?= $user_data; ?>"
                title="Enviar credenciales de acceso"
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