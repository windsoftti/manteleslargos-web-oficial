<!-- Modal -->
<div class="modal fade" id="select-business-modal" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <div class="d-flex justify-content-between align-items-center w-100">
          <h5 class="modal-title">
            <i class="fa fa-building"></i> Seleccionar un negocio
          </h5>

          <button type="button" class="close" data-dismiss="modal">
            <i class="fal fa-times"></i>
          </button>
        </div>
      </div>

      <div class="modal-body">
        <table class="table table-hover">
          <thead class="bg-default">
            <tr>
              <th>Nombre</th>
              <th>ID</th>
            </tr>
          </thead>

          <tbody>
            <?php foreach ($session_user_business as $key => $row) : ?>
              <tr>
                <td class="align-middle">
                  <!-- <a class="text-underline" href="?business_ref=<?= $row['idSalon']; ?>-<?= $row['slug']; ?>"><?= $row['Salon']; ?></a> -->

                  <div class="form-check d-flex align-items-center">
                    <label class="form-check-label d-flex align-items-center fs-14">
                      <input class="form-check-input mt-0" name="selectBusiness" value="<?= $row['idSalon']; ?>-<?= $row['slug']; ?>" type="radio" <?= $row['idSalon'] == $_SESSION['session_business_id'] ? 'checked' : '' ?>>
                      <?= $row['Salon']; ?>
                    </label>
                  </div>
                </td>

                <td class="align-middle">
                  <?= $row['Referencia']; ?>-<?= $row['slug']; ?>
                </td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
      <div class="modal-footer">
        <!-- <button type="button" class="btn btn-custom-default" data-dismiss="modal">Cancelar</button> -->
        <div class="d-flex w-100 align-items-center justify-content-between">
          <a class="text-blue" href="agregar-negocio">
            <i class="fa fa-plus-circle mr-1"></i> Agregar nuevo
          </a>

          <button id="change-business-btn" class="btn btn-secondary btn-sm" type="button">
            Aplicar
          </button>
        </div>
      </div>
    </div>
  </div>
</div>