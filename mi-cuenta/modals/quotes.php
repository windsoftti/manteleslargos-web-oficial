<div class="modal fade" id="modal-schedule-date">
  <div class="modal-dialog modal-dialog-centered">
    <form id="quotes-form" class="modal-content" autocomplete="off">
      <div class="modal-header">
        <div class="d-flex justify-content-between align-items-center w-100">
          <h5 class="modal-title">Agendar fecha</h5>
          <button type="button" class="close" data-dismiss="modal">
            <i class="fal fa-times"></i>
          </button>
        </div>
      </div>

      <div class="modal-body">
        <div class="row">
          <div class="col-md-6">
            <label class="mb-0" for="business"><span class="text-danger">*</span>Salón/Negocio</label>
            <input id="businessName" class="form-control" name="businessName" value="<?= getBusinessNameById($_SESSION['session_business_id']); ?>" type="text">
            <input id="business" name="business" value="<?= $_SESSION['session_business_id']; ?>" type="hidden">
          </div>

          <div class="col-md-6">
            <label class="mb-0" for="package"><span class="text-danger">*</span>Paquete</label>
            <div class="form-group mb-1">
              <?php
              $query = "SELECT idPaquete, Paquete FROM paquetes_negocios WHERE idNegocio = $_SESSION[session_business_id]";
              $query_result = mysqli_query($mysqli, $query);
              ?>
              <select id="package" class="form-control" name="package" required>
                <option value="">Seleccionar</option>

                <?php if ($row = mysqli_fetch_array($query_result)) : ?>
                  <option value="<?= $row['idPaquete']; ?>"><?= $row['Paquete']; ?></option>
                <?php endif; ?>
              </select>
            </div>
          </div>
        </div>

        <?php
        $query = "SELECT
            idTipoEvento,
            TipoEvento
          FROM tipo_eventos
          ORDER BY idTipoEvento
          ASC
        ";

        $query_result = mysqli_query($mysqli, $query);
        ?>
        <div class="row">
          <div class="col-md-6">
            <label class="mb-0" for="eventType"><span class="text-danger">*</span>Tipo de evento</label>
            <div class="form-group mb-1">
              <select id="eventType" class="form-control" name="eventType" required>
                <option value="">Seleccionar</option>
                <?php while ($row = mysqli_fetch_array($query_result)) : ?>
                  <option value="<?= $row['idTipoEvento']; ?>"><?= $row['TipoEvento']; ?></option>
                <?php endwhile; ?>
              </select>
            </div>
          </div>
        </div>

        <div class="row">
          <div class="col-md-6">
            <label class="mb-0" for="name"><span class="text-danger">*</span>Nombre completo</label>
            <div class="form-group mb-1">
              <input id="name" class="form-control" type="text" name="name" required>
            </div>
          </div>

          <div class="col-md-6">
            <label class="mb-0" for="email"><span class="text-danger">*</span>Correo</label>
            <div class="form-group mb-1">
              <input id="email" class="form-control" type="email" name="email" required>
            </div>
          </div>
        </div>

        <div class="row">
          <div class="col-md-6">
            <label class="mb-0" for="phone"><span class="text-danger">*</span>Teléfono</label>
            <div class="form-group mb-1">
              <div class="input-group">
                <span class="input-group-addon position-absolute" style="z-index: 10;">+52</span>
                <input id="phone" class="form-control" type="text" name="phone" required>
              </div>
            </div>
          </div>
        </div>

        <div class="row">
          <div class="col-sm-6 col-md-6">
            <label class="mb-0" for="date"><span class="text-danger">*</span>Fecha</label>
            <div class="form-group mb-1">
              <input id="date" class="form-control" type="text" name="date" required>
            </div>
          </div>

          <div class="col-sm-3 col-md-3">
            <label class="mb-0" for="startTime">Hora (inicio)</label>
            <div class="form-group mb-1">
              <input id="startTime" class="form-control time" type="text" name="startTime">
            </div>
          </div>

          <div class="col-sm-3 col-md-3">
            <label class="mb-0" for="endTime">Hora (fin)</label>
            <div class="form-group mb-1">
              <input id="endTime" class="form-control time" type="text" name="endTime">
            </div>
          </div>
        </div>

        <div class="row">
          <div class="col-6 col-sm-5 col-md-4">
            <label class="mb-0" for="NPersons"><span class="text-danger">*</span>N° personas</label>
            <div class="form-group mb-1">
              <input id="NPersons" class="form-control number-input" type="number" name="NPersons" min="1" required>
            </div>
          </div>
        </div>

        <div class="row">
          <div class="col-md-12">
            <label class="mb-0" for="extras">Extras</label>
            <div class="form-group mb-1">
              <textarea id="extras" class="form-control" name="extras" rows="3"></textarea>
            </div>
          </div>
        </div>

        <div class="row">
          <div class="col-md-4">
            <label class="mb-0" for="totalCost"><span class="text-danger">*</span>Costo total</label>
            <div class="form-group mb-1">
              <input id="totalCost" class="form-control number-input" type="text" name="totalCost" required>
            </div>
          </div>

          <div class="col-md-4">
            <label class="mb-0" for="deposit">Depósito</label>
            <div class="form-group mb-1">
              <input id="deposit" class="form-control number-input" type="text" name="deposit">
            </div>
          </div>

          <div class="col-md-4">
            <label class="mb-0" for="advance">Anticipo</label>
            <div class="form-group mb-1">
              <input id="advance" class="form-control number-input" type="text" name="advance">
            </div>
          </div>
        </div>

        <div class="row mt-2">
          <div class="col-md-12">
            <div class="row">
              <div class="col-md-12 text-center">
                <h5 class="text-center login-title">Recordatorio de pagos</h5>
              </div>
            </div>

            <div class="row">
              <div class="col-md-12">
                <div class="card p-1">
                  <div class="row">
                    <div id="payment-recordatory-container" class="col-md-12"></div>
                  </div>

                  <div class="row">
                    <?php if ($session_user_plan === 'Free') : ?>
                      <div class="col-md-12">
                        <button type="button" class="btn btn-secondary" onclick="<?= $session_target_free_plan; ?>">
                          <i class="fal fa-plus-circle mr-1"></i> Añadir
                        </button>
                      </div>
                    <?php endif; ?>

                    <?php if ($session_user_plan === 'Básico') : ?>
                      <div class="col-md-12">
                        <button id="btn-add-recordatory-item" type="button" class="btn btn-secondary">
                          <i class="fal fa-plus-circle mr-1"></i> Añadir
                        </button>
                      </div>
                    <?php endif; ?>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="row mt-2 mb-3">
          <div class="col-md-12 mx-auto">
            <div class="row">
              <div class="col-md-12 text-center">
                <h5 class="text-center login-title">Estatus del día</h5>
              </div>
            </div>

            <div class="row">
              <div class="col-md-12">
                <div class="card p-3">
                  <div class="row">
                    <div class="col-md-12">
                      <div class="custom-radio-button">
                        <input type="radio" id="status-libre" name="status" value="Libre" checked>
                        <label for="status-libre">
                          <div class="green bg-success"><i class="fa fa-check-circle text-white"></i></div>
                          <span>Libre</span>
                        </label>
                      </div>
                    </div>
                  </div>

                  <div class="row">
                    <div class="col-md-12">
                      <div class="custom-radio-button">
                        <input type="radio" id="status-with-spaces" name="status" value="Con espacios">
                        <label for="status-with-spaces">
                          <div class="yellow bg-warning"><i class="fa fa-check-circle text-white"></i></div>
                          <span>Con espacios</span>
                        </label>
                      </div>
                    </div>
                  </div>

                  <div class="row">
                    <div class="col-md-12">
                      <div class="custom-radio-button">
                        <input type="radio" id="status-occupied" name="status" value="Ocupado">
                        <label for="status-occupied">
                          <div class="red bg-danger"><i class="fa fa-check-circle text-white"></i></div>
                          <span>Ocupado</span>
                        </label>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <input type="hidden" name="quoteId" id="quoteId">
      <input type="hidden" name="action" id="action-quotes" value="schedule_date">

      <div class="modal-footer">
        <button type="button" class="btn btn-white" data-dismiss="modal">
          <i class="fa fa-times-circle"></i> Cancelar
        </button>
        <button type="submit" class="btn btn-primary">
          <i class="fa fa-check-circle"></i> Agendar
        </button>
      </div>
    </form>
  </div>
</div>

<?php if ($session_user_plan === 'Básico') : ?>
  <!-- Agregar cotización -->
  <div class="modal fade" id="modal-add-edit-quote">
    <div class="modal-dialog modal-dialog-centered">
      <form id="add-edit-quotes-form" class="modal-content" autocomplete="off">
        <div class="modal-header">
          <div class="d-flex justify-content-between align-items-center w-100">
            <h5 class="modal-title modal-dynamic-title"></h5>
            <button type="button" class="close" data-dismiss="modal">
              <i class="fal fa-times"></i>
            </button>
          </div>
        </div>

        <div class="modal-body">
          <div class="row">
            <div class="col-md-6">
              <?php
              $query_packages = "SELECT
                  idPaquete,
                  Paquete
                FROM paquetes_negocios
                WHERE idNegocio = $_SESSION[session_business_id]
              ";

              $query_packages_result = mysqli_query($mysqli, $query_packages);
              ?>
              <div class="form-group">
                <label for="quotePackage"><span>*</span>Paquete</label>
                <select id="quotePackage" class="form-control" name="package" required>
                  <option value="">Seleccionar</option>
                  <?php while ($row = mysqli_fetch_array($query_packages_result)) : ?>
                    <option value="<?= $row['idPaquete']; ?>"><?= $row['Paquete']; ?></option>
                  <?php endwhile; ?>
                </select>
              </div>
            </div>

            <div class="col-md-6">
              <div class="form-group">
                <label for="quoteDate"><span>*</span>Fecha solicitada</label>
                <input id="quoteDate" class="form-control datepicker" type="text" name="date" required>
              </div>
            </div>
          </div>

          <div class="row">
            <div class="col-md-6">
              <?php
              $query_event_types = "SELECT
                  idTipoEvento,
                  TipoEvento
                FROM tipo_eventos
                ORDER BY idTipoEvento
                ASC
              ";

              $query_event_types_result = mysqli_query($mysqli, $query_event_types);
              ?>
              <div class="form-group">
                <label for="quoteEventType"><span>*</span>Tipo de evento</label>
                <select id="quoteEventType" class="form-control" name="eventType" required>
                  <option value="">Seleccionar</option>
                  <?php while ($row = mysqli_fetch_array($query_event_types_result)) : ?>
                    <option value="<?= $row['idTipoEvento']; ?>"><?= $row['TipoEvento']; ?></option>
                  <?php endwhile; ?>
                </select>
              </div>
            </div>
          </div>

          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label for="quoteName"><span>*</span>Nombre completo</label>
                <input id="quoteName" class="form-control" name="name" type="text" required>
              </div>
            </div>

            <div class="col-md-6">
              <div class="form-group">
                <label for="quoteEmail"><span>*</span>Correo</label>
                <input id="quoteEmail" class="form-control" name="email" type="email" required>
              </div>
            </div>
          </div>

          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label for="quotePhone"><span>*</span>Teléfono</label>
                <div class="input-group">
                  <span class="input-group-addon position-absolute" style="z-index: 10;">+52</span>
                  <input id="quotePhone" class="form-control" name="phone" type="text" required>
                </div>
              </div>
            </div>
          </div>
        </div>

        <input type="hidden" name="quoteId" id="add-edit-quoteId">
        <input type="hidden" name="action" id="action-add-edit-quotes">

        <div class="modal-footer">
          <button type="button" class="btn btn-white" data-dismiss="modal">
            <i class="fa fa-times-circle"></i> Cancelar
          </button>

          <button type="submit" class="btn btn-primary btn-modal-title">
            <i class="fa fa-check-circle"></i> Guardar
          </button>
        </div>
      </form>
    </div>
  </div>
<?php endif; ?>

<div class="modal fade" id="modal-quote-info">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <div class="d-flex justify-content-between align-items-center w-100">
          <h5 class="modal-title">Información</h5>

          <button type="button" class="close" data-dismiss="modal">
            <i class="fal fa-times"></i>
          </button>
        </div>
      </div>

      <div class="moda-body">
        <ul class="list-group list-group-sm" style="font-size: 0.9rem;">
          <li id="infoName" class="list-group-item"><b>Nombre:</b> <span></span></li>
          <li id="infoEmail" class="list-group-item"><b>Correo:</b> <span></span></li>
          <li id="infoPhone" class="list-group-item"><b>Teléfono:</b> <span></span></li>
          <li id="infoEventDay" class="list-group-item"><b>Día del evento:</b> <span></span></li>
          <li id="infoStatus" class="list-group-item"><b>Estatus:</b> <span></span></li>
        </ul>
      </div>

      <div class="modal-footer">
        <button type="button" class="btn btn-white" data-dismiss="modal">
          <i class="fa fa-check-circle"></i> Aceptar
        </button>
      </div>
    </div>
  </div>
</div>