<?php $table_row_number = (($page - 1) * $per_page) + 1; ?>

<div class="table-responsive">
  <table class="table card-table table-vcenter">
    <thead class="bg-primary">
      <tr>
        <th>#</th>
        <th>Proveedor</th>
        <th>Correo</th>
        <th>Telefono</th>
        <th>Cuenta de vendedor</th>
        <th>Estatus</th>
        <th class="text-right">Acciones</th>
      </tr>
    </thead>
    <tbody>
      <?php while ($row = mysqli_fetch_array($request['query_result'])) : ?>
        <tr>
          <th scope="row">
            <?= $table_row_number; ?>
          </th>

          <td>
            <?= $row['Usuario']; ?>
          </td>

          <td>
            <?= $row['Correo']; ?>
          </td>

          <td>
            <?php if ($row['Telefono']) : ?>
              <?= formatPhoneNumber($row['Telefono']) ?>
            <?php endif; ?>

            <?php if (!$row['Telefono']) : ?>
              Sin telefono
            <?php endif; ?>
          </td>

          <td>
            <?php if ($row['VerificationCodeStatus'] === 'Nuevo') : ?>
              <span class="badge badge-danger">
                Cuenta inactiva
              </span>
            <?php endif; ?>

            <?php if ($row['VerificationCodeStatus'] === 'Usado') : ?>
              <span class="badge badge-success">
                Cuenta activa
              </span>
            <?php endif; ?>
          </td>

          <td>
            <?php if ($row['Status'] == 'Activo') : ?>
              <span class="badge badge-success">
                <?= $row['Status']; ?>
              </span>
            <?php endif; ?>

            <?php if ($row['Status'] == 'Inactivo') : ?>
              <span class="badge badge-warning">
                <?= $row['Status']; ?>
              </span>
            <?php endif; ?>

            <?php if ($row['Status'] == 'Eliminado') : ?>
              <span class="badge badge-danger">
                <?= $row['Status']; ?>
              </span>
            <?php endif; ?>
          </td>

          <td class="text-right">
            <div class="dropdown">
              <div class="btn-group btn-group-sm">
                <button id="actions-menu" class="btn btn-primary" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" type="button">
                  <i class="fa fa-ellipsis-v"></i>
                </button>

                <div class="dropdown-menu" aria-labelledby="actions-menu">
                  <?php if ($row['Status'] !== 'Eliminado') : ?>
                    <?php if ($row['VerificationCodeStatus'] === 'Nuevo') : ?>
                      <a href="javascript:void(0)" class="dropdown-item btn-action" data-row="<?= htmlspecialchars(json_encode($row)); ?>" data-action='{
                          "action":"activar-cuenta",
                          "message":"¿Realmente desea activar la cuenta de <?= $row['Usuario'] ?>?"
                        }
                      '>
                        <i class="fa fa-check text-success"></i> Activar cuenta
                      </a>
                    <?php endif; ?>

                    <div class="dropdown-divider"></div>
                  <?php endif; ?>

                  <?php if ($row['Status'] !== 'Eliminado') : ?>
                    <a class="dropdown-item btn-delete" data-row="<?= htmlspecialchars(json_encode($row)); ?>" href="javascript:void(0)">
                      <i class="fa fa-trash text-danger"></i> Eliminar
                    </a>
                  <?php endif; ?>

                  <?php if ($row['Status'] === 'Eliminado') : ?>
                    <a class="dropdown-item btn-action" data-row="<?= htmlspecialchars(json_encode($row)); ?>" data-action='{"action":"recuperar"}' href="javascript:void(0)">
                      <i class="fa fa-undo text-info"></i> Recuperar
                    </a>
                  <?php endif; ?>
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

<?= paginate($page, $request['num_pages'], 2, 'load'); ?>