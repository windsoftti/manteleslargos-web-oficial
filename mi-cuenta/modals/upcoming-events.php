<div class="modal fade" id="modal-upcoming-events">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <div class="d-flex justify-content-between align-items-center w-100">
          <h5 class="modal-title">Editar evento</h5>
          <button type="button" class="close" data-dismiss="modal">
            <i class="fal fa-times"></i>
          </button>
        </div>
      </div>

      <div class="modal-body">
        <div class="row">
          <div class="col-md-12">
            <form id="upcoming-events-form" class="col-md-12">
              <div class="row">
                <div class="col-md-12 text center">
                  <h5 class="text-center login-title">Datos de la reservación</h5>
                  <!-- <p class="text-center">¿Ya tienes cuenta? <b><a href="javascript:void(0)" onclick="changeQuoteState('login')"> Inícia sesión</a></b></p> -->
                </div>
              </div>

              <div class="row">
                <div class="col-md-6">
                  <label class="mb-0" for="package"><span class="text-danger">*</span>Paquete</label>
                  <div class="form-group mb-1">
                    <select id="package" class="form-control" name="package" required>
                      <option value="">Seleccionar</option>
                    </select>
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
                    <input id="advance" class="form-control number-input" type="text" name="advance" readonly>
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
                          <div class="col-md-12">
                            <button id="btn-add-recordatory-item" type="button" class="btn btn-secondary">
                              <i class="fal fa-plus-circle mr-1"></i> Añadir
                            </button>
                          </div>
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
                                <div class="green"><i class="fal fa-check-circle text-white"></i></div>
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
                                <div class="yellow"><i class="fal fa-check-circle text-white"></i></div>
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
                                <div class="red"><i class="fal fa-check-circle text-white"></i></div>
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

              <input id="action-events-calendar" type="hidden" name="action" value="edit_reservation">
              <input id="reservationId" type="hidden" name="reservationId" value="">
              <input id="businessId" type="hidden" name="business">

              <div class="row">
                <div class="col-md-12 text-center">
                  <button class="btn btn-primary btn-block btn-modal-title" type="submit">
                    <i class="fal fa-check-circle"></i> Guardar
                  </button>
                </div>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="modal-upcoming-events-info">
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
          <li id="infoPackage" class="list-group-item"><b>Paquete:</b> <span></span></li>
          <li id="infoEventType" class="list-group-item"><b>Tipo de evento:</b> <span></span></li>
          <li id="infoPhone" class="list-group-item"><b>Teléfono:</b> <span></span></li>
          <li id="infoDate" class="list-group-item"><b>Fecha:</b> <span></span></li>
          <li id="infoHour" class="list-group-item"><b>Hora:</b> <span></span></li>
          <li id="infoNPersons" class="list-group-item"><b>N° de personas:</b> <span></span></li>
          <li id="infoCost" class="list-group-item"><b>Costo:</b> <span></span></li>
          <li id="infoDeposit" class="list-group-item"><b>Deposito:</b> <span></span></li>
          <li id="infoAnticipo" class="list-group-item"><b>Anticipo:</b> <span></span></li>
          <li id="infoExtras" class="list-group-item"><b>Extras:</b>
            <div></div>
          </li>
          <li id="infoDayStatus" class="list-group-item"><b>Estatus del día:</b> <span></span></li>
        </ul>
      </div>

      <div class="modal-footer">
        <button class="btn btn-white" data-dismiss="modal" type="button">
          <i class="fa fa-times-circle"></i> Cerrar
        </button>

        <button id="btn-edit-event" class="btn btn-primary" data-dismiss="modal" type="button">
          <i class="fa fa-pencil"></i> Editar información
        </button>
      </div>
    </div>
  </div>
</div>