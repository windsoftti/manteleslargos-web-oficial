const initDatePicker = () => $('.datepicker').datetimepicker({
  format: 'DD-MM-YYYY',
  locale: 'es-es',
  icons: {
    time: "fal fa-clock",
    date: "fal fa-calendar",
    up: "fal fa-arrow-up",
    down: "fal fa-arrow-down",
    previous: "fal fa-chevron-left",
    next: "fal fa-chevron-right",
    today: "fal fa-clock",
    clear: "fal fa-trash",
    close: "fal fa-times"
  }
});

const addNewPaymentRecordatoryItem = ({
  percentage = '',
  date = ''
}) => {
  const paymentRecordatoryItem = `
    <div class="card mb-1 border-0">
      <div class="col-md-12">
        <div class="row">
          <div class="col-md-12 text-right">
            <a class="btn-remove-recordatory-item text-danger" href="javascript:void(0)">
              <b style="font-size:1.5rem">&times;</b>
            </a>
          </div>
        </div>

        <div class="row">
          <div class="col-6">
            <div class="form-group">
              <label class="mb-0" for="RCPercentage">Porcentaje (%)</label>
              <input id="RCPercentage" class="form-control number-input" type="text" name="RCPercentages[]" value="${percentage}" onkeyup="this.value = this.value > 100 ? 100 : this.value;" required>
            </div>
          </div>

          <div class="col-6">
            <div class="form-group">
              <label class="mb-0" for="RCDate">Fecha</label>
              <input id="RCDate" class="form-control datepicker" type="text" name="RCDates[]" value="${date}" required>
            </div>
          </div>
        </div>
      </div>
    </div>
  `;

  $('#payment-recordatory-container').append(paymentRecordatoryItem);

  initDatePicker();
  initNumberInput();
}

function removePaymentRecordatoryItem() {
  const item = $(this).parent().parent().parent().parent();
  item.remove();
}

const loadPaymentReminders = async reservationId => {
  showPageLoading();

  const parameters = new FormData();

  parameters.append('action', 'list_payment_recordatory');
  parameters.append('reservationId', reservationId);

  const response = await fetchData({
    place: 'events_calendar',
    data: parameters
  });

  if (response.status === 'success') response.reminders.map(reminder => addNewPaymentRecordatoryItem({
    percentage: reminder.percentage,
    date: reminder.date
  }));

  hidePageLoading();
}

$('#btn-add-recordatory-item').on('click', addNewPaymentRecordatoryItem);
$(document).on('click', '.btn-remove-recordatory-item', removePaymentRecordatoryItem)