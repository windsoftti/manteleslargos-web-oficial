<?php
$table_row_number = 1;
$edit_permissions = verifyUserPermissions('editar-negocios');
?>

<div class="row">
  <?php while ($row = mysqli_fetch_array($query_result)) : ?>
    <div class="col-12 col-lg-6 mb-3">
      <div class="card">
        <div class="card-body">
          <div class="d-flex w-100" style="gap: 0.8rem;flex-wrap: nowrap">
            <div class="d-flex flex-column mr-auto" style="font-size: 0.9rem;width: 85%;">
              <a class="font-weight-bold fs-5 text-dark" href="#">
                <?= $row['Salon']; ?>
              </a>

              <span class="mb-1"><?= $row['TipoProveedor']; ?></span>
              <span><?= formatPhoneNumber($row['Telefono']); ?></span>
              <!--<span><?php //echo $row['Correo']; ?></span>-->
            </div>

            <div>

              <div class="btn-group btn-group-sm dropleft">
                <div class="dropdown dropleft">
                  <button id="actions-dropdown" class="btn btn-primary btn-sm" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="fa fa-ellipsis-v"></i>
                  </button>

                  <div class="dropdown-menu" aria-labelledby="actions-dropdown">
                    <a class="dropdown-item" target="_blank" href="https://manteleslargos.com/<?= $row['slug']; ?>-<?= $row['Referencia']; ?>">
                      <i class="fas fa-eye mr-1"></i>Ver en línea
                    </a>

                    <a class="dropdown-item" href="javascript:copyLink(`https://manteleslargos.com/<?= $row['slug']; ?>-<?= $row['Referencia']; ?>`)">
                      <i class="fas fa-paperclip mr-1"></i>Copiar link
                    </a>

                    <?php if (verifyUserPermissions('editar-negocios')) : ?>
                      <!--<a class="dropdown-item" href="editar-negocio?uid=<?= $row['idSalon'] ?>">
                        <i class="fas fa-pencil-alt mr-1"></i>Editar
                      </a>-->
                    <?php endif; ?>
                    <?php if (userCan('listar-negocios', 'edit')) : ?>
                    <a class="dropdown-item" href="editar-negocio?uid=<?= $row['idSalon'] ?>">
                      <i class="fas fa-pencil-alt mr-1"></i>Editar
                    </a>
                    <?php endif; ?>

                    <?php //if (verifyUserPermissions('eliminar-negocios')) : ?>
                      <!--<div class="dropdown-divider"></div>

                      <a class="dropdown-item" href="javascript:void(0)" onclick="eliminarNegocio(<?= $row['idSalon'] ?>, `<?= htmlspecialchars($row['Salon']) ?>`)">
                        <i class="fas fa-trash-alt text-danger mr-1"></i>Eliminar
                      </a>-->
                    <?php //endif; ?>

                    <?php if (userCan('listar-negocios', 'delete')) : ?>
                      <div class="dropdown-divider"></div>

                      <a class="dropdown-item" href="javascript:void(0)" onclick="eliminarNegocio(<?= $row['idSalon'] ?>, `<?= htmlspecialchars($row['Salon']) ?>`)">
                        <i class="fas fa-trash-alt text-danger mr-1"></i>Eliminar
                      </a>
                    <?php endif; ?>

                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  <?php endwhile; ?>
</div>