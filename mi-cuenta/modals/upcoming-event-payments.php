<div class="modal fade" id="modal-edit-payment">
  <div class="modal-dialog modal-dialog-centered">
    <form id="edit-payment-form" class="modal-content" autocomplete="off">
      <div class="modal-header">
        <div class="d-flex justify-content-between align-items-center w-100">
          <h5 class="modal-title">Editar pago</h5>
          <button type="button" class="close" data-dismiss="modal">
            <i class="fal fa-times"></i>
          </button>
        </div>
      </div>

      <div class="modal-body">
        <div class="row">
          <div class="col-6">
            <div class="form-group">
              <label for="edit-currentBalance">Saldo actual<span>*</span></label>
              <input id="edit-currentBalance" class="form-control" type="text" name="currentBalance" value="<?= $event_data['SaldoTotal']; ?>" required readonly>
            </div>
          </div>

          <div class="col-6">
            <div class="form-group">
              <label for="edit-newBalance">Nuevo saldo<span>*</span></label>
              <input id="edit-newBalance" class="form-control" type="text" name="newBalance" required readonly>
            </div>
          </div>

          <div class="col-6">
            <div class="form-group">
              <label for="edit-payment">Pago<span>*</span></label>
              <input id="edit-payment" class="form-control number-input" type="text" name="payment" required>
              <input id="edit-initialPayment" type="hidden" name="initialPayment">
            </div>
          </div>

          <div class="col-6">
            <div class="form-group">
              <label for="date">Fecha<span>*</span></label>
              <input id="edit-date" class="form-control datepicker" type="text" name="date" required>
            </div>
          </div>
        </div>

        <div class="row">
          <div class="col-12">
            <div class="form-group">
              <label for="edit-comments">Comentarios</label>
              <textarea id="edit-comments" class="form-control" name="comments" rows="2"></textarea>
            </div>
          </div>
        </div>
      </div>

      <input type="hidden" name="reservationPaymentId" id="reservationPaymentId">
      <input type="hidden" name="action" value="edit_payment">

      <div class="modal-footer">
        <button type="button" class="btn btn-white" data-dismiss="modal">
          <i class="fa fa-times-circle"></i> Cancelar
        </button>
        <button type="submit" class="btn btn-primary">
          <i class="fa fa-check-circle"></i> Guardar cambios
        </button>
      </div>
    </form>
  </div>
</div>