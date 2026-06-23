<div class="modal fade" id="modal-add-edit-event-calendar">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <div class="d-flex justify-content-between align-items-center w-100">
          <h5 class="modal-title">Elige una opción</h5>
          <button type="button" class="close" data-dismiss="modal">
            <i class="fal fa-times"></i>
          </button>
        </div>
      </div>

      <div class="modal-body">
        <div class="row">
          <div id="form-container" class="col-md-12 form-container">
            <div class="row actions">
              <div class="col-md-12 mb-2">
                <div class="row">
                  <div class="col-md-12 text-canter">
                    <button class="btn btn-block btn-primary btn-lg" onclick="changeModalState('add-event')">
                      Agregar una reservación
                    </button>
                  </div>
                </div>
              </div>

              <div class="col-md-12 mb-2">
                <div class="row">
                  <div class="col-md-12 text-canter">
                    <button class="btn btn-block btn-primary btn-lg" onclick="blockDay()">
                      Marcar como día inhabil
                    </button>
                  </div>
                </div>
              </div>

              <div class="col-md-12 mb-2">
                <div class="row">
                  <div class="col-md-12 text-canter">
                    <button class="btn btn-block btn-primary btn-lg" onclick="changeModalState('add-reminder')">
                      Agregar recordatorio
                    </button>
                  </div>
                </div>
              </div>
            </div>

            <div id="add-new-event" class="row event-form">
              <form id="add-event-form" class="col-md-12">
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
                      <input id="email" class="form-control" type="email" name="email">
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
                      <input id="totalCost" class="form-control number-input" type="text" name="totalCost" value="0.00">
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

                <div class="row mt-2">
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

                <input id="action-events-calendar" type="hidden" name="action" value="add_reservation">
                <input id="reservationId" type="hidden" name="reservationId" value="">

                <div class="row mt-3">
                  <div class="col-md-12 text-center">
                    <button class="btn btn-primary btn-block btn-modal-title" type="submit">
                      <i class="fa fa-check-circle"></i> Guardar
                    </button>
                  </div>
                </div>
              </form>
            </div>

            <div id="add-new-reminder" class="row reminder-form">
              <form id="reminders-form" class="col-md-12">
                <div class="row">
                  <div class="col-md-12 text center">
                    <h5 class="text-center login-title modal-dynamic-title-reminders"></h5>
                    <!-- <p class="text-center">¿Ya tienes cuenta? <b><a href="javascript:void(0)" onclick="changeQuoteState('login')"> Inícia sesión</a></b></p> -->
                  </div>
                </div>

                <div class="row">
                  <div class="col-md-6">
                    <div class="form-group">
                      <label for="reminderTitle"><span>*</span>Titulo recordatorio</label>
                      <input id="reminderTitle" class="form-control" type="text" name="reminderTitle" required>
                    </div>
                  </div>

                  <div class="col-md-6">
                    <div class="form-group">
                      <label for="reminderColor"><span>*</span>Color recordatorio</label>
                      <input id="reminderColor" class="form-control" type="color" name="reminderColor" value="#3374FF" required>
                    </div>
                  </div>
                </div>

                <div class="row">
                  <div class="col-md-12">
                    <div class="form-group">
                      <label for="reminderDescription"><span>*</span>Descripción recordatorio</label>
                      <textarea id="reminderDescription" class="form-control" name="reminderDescription" rows="2" required></textarea>
                    </div>
                  </div>
                </div>

                <div class="row">
                  <div class="col-md-6">
                    <div class="form-group">
                      <label for="reminderDesde"><span>*</span>Fecha desde</label>
                      <input id="reminderDesde" class="form-control reminder-datepicker" type="text" name="reminderDesde" required>
                    </div>
                  </div>

                  <div class="col-md-6">
                    <div class="form-group">
                      <label for="reminderHasta"><span>*</span>Fecha hasta</label>
                      <input id="reminderHasta" class="form-control reminder-datepicker" type="text" name="reminderHasta" required>
                    </div>
                  </div>
                </div>

                <div class="row mt-2">
                  <div class="col-md-12">
                    <div class="row">
                      <div class="col-md-12 text-center">
                        <h5 class="text-center login-title">Notificar</h5>
                      </div>
                    </div>

                    <div class="row">
                      <div class="col-md-12">
                        <div class="card p-1">
                          <div class="row">
                            <div id="event-reminder-container" class="col-12">

                            </div>
                          </div>

                          <div class="row">
                            <div class="col-md-12">
                              <button id="btn-add-event-reminder-item" type="button" class="btn btn-secondary">
                                <i class="fa fa-plus-circle mr-1"></i> Añadir
                              </button>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>

                <input id="action-reminders" type="hidden" name="action" value="add_event_calendar">
                <input id="eventCalendarId" type="hidden" name="eventCalendarId" value="">

                <div class="row mt-3">
                  <div class="col-md-12 text-center">
                    <button class="btn btn-primary btn-block btn-modal-title" type="submit">
                      <i class="fa fa-check-circle"></i> Guardar
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
</div>

<!-- Recordatorios :::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::: -->
<!-- <div class="modal fade" id="modal-add-edit-reminders">
  <div class="modal-dialog modal-dialog-centered">
    <form id="reminders-form" class="modal-content" autocomplete="off">
      <div class="modal-header">
        <div class="d-flex justify-content-between align-items-center w-100">
          <h5 class="modal-title modal-dynamic-title">Agregar recordatorio</h5>
          <button type="button" class="close" data-dismiss="modal">
            <i class="fal fa-times"></i>
          </button>
        </div>
      </div>

      <div class="modal-body">
        <div class="row">
          <div class="col-md-12">
            <div class="form-group">
              <label for="reminderTitle">Titulo recordatorio</label>
              <input id="reminderTitle" class="form-control" type="text" name="reminderTitle">
            </div>
          </div>
        </div>

        <div class="row">
          <div class="col-md-6">
            <div class="form-group">
              <label for="reminderDesde">Fecha desde</label>
              <input id="reminderDesde" class="form-control reminder-datepicker" type="text" name="reminderDesde">
            </div>
          </div>

          <div class="col-md-6">
            <div class="form-group">
              <label for="reminderHasta">Fecha hasta</label>
              <input id="reminderHasta" class="form-control reminder-datepicker" type="text" name="reminderHasta">
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

        <button type="submit" class="btn btn-primary">
          <i class="fa fa-check-circle"></i> Guardar
        </button>
      </div>
    </form>
  </div>
</div> -->

<div id="modal-change-day-status" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="modal-change-day-status-title" aria-hidden="true">
  <div class="modal-dialog modal-sm" role="document">
    <form id="change-day-status-form" class="modal-content" autocomplete="off">
      <div class="modal-header">
        <div class="d-flex justify-content-between align-items-center w-100">
          <h5 class="modal-title">Cambiar estatus del día</h5>
          <button type="button" class="close" data-dismiss="modal">
            <i class="fal fa-times"></i>
          </button>
        </div>
      </div>

      <div class="modal-body">
        <div class="row">
          <div class="col-md-12">
            <div class="card p-3">
              <div class="row">
                <div class="col-md-12 mb-1">
                  <div class="custom-radio-button">
                    <input type="radio" id="change-status-libre" name="status" value="Libre" checked>
                    <label for="change-status-libre">
                      <div class="green bg-success"><i class="fa fa-check-circle text-white"></i></div>
                      <span>Libre</span>
                    </label>
                  </div>
                </div>
              </div>

              <div class="row">
                <div class="col-md-12 mb-1">
                  <div class="custom-radio-button">
                    <input type="radio" id="change-status-with-spaces" name="status" value="Con espacios">
                    <label for="change-status-with-spaces">
                      <div class="yellow bg-warning"><i class="fa fa-check-circle text-white"></i></div>
                      <span>Con espacios</span>
                    </label>
                  </div>
                </div>
              </div>

              <div class="row">
                <div class="col-md-12 mb-1">
                  <div class="custom-radio-button">
                    <input type="radio" id="change-status-occupied" name="status" value="Ocupado">
                    <label for="change-status-occupied">
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

      <input id="change-day-status-date" name="date" type="hidden">

      <div class="modal-footer">
        <button type="button" class="btn btn-white btn-sm" data-dismiss="modal">
          <i class="fa fa-times-circle"></i> Cancelar
        </button>

        <button type="submit" class="btn btn-primary btn-sm">
          <i class="fa fa-check-circle"></i> Guardar cambios
        </button>
      </div>
    </form>
  </div>
</div>