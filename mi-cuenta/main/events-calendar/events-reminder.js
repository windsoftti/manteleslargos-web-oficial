const addNewEventReminderItem = ({
  quantity = 1,
  periodicity = ''
}) => {
  const eventReminderItem = `
    <div class="card mb-1 border-0">
      <div class="col-12">
        <div class="row">
          <div class="col-md-12 text-right">
            <a class="btn-remove-event-reminder-item text-danger" href="javascript:void(0)">
              <b style="font-size:1.5rem">&times;</b>
            </a>
          </div>
        </div>

        <div class="row">
          <div class="col-4 col-sm-3">
            <div class="form-group">
              <input class="form-control number-input" type="number" name="quantitys[]" min="1" value="${quantity}" required>
            </div>
          </div>

          <div class="col-8 col-sm-9">
            <div class="form-group">
              <select class="form-control" name="periodicitys[]" required>
                <option value="">Seleccionar</option>
                <option ${periodicity === 'Dia' ? 'selected' : ''} value="Dia">Dias antes</option>
                <option ${periodicity === 'Semanal' ? 'selected' : ''} value="Semanal">Semanas antes</option>
                <option ${periodicity === 'Mensual' ? 'selected' : ''} value="Mensual">Meses antes</option>
              </select>
            </div>
          </div>
        </div>
      </div>
    </div>
  `;

  $('#event-reminder-container').append(eventReminderItem);
  initNumberInput();
}

function removeEventReminderItem() {
  const item = $(this).parent().parent().parent().parent();
  item.remove();
}

const loadEventsCalendarReminders = async eventCalendarId => {
  showPageLoading();

  const parameters = new FormData();

  parameters.append('action', 'list_events_calendar_reminders');
  parameters.append('eventCalendarId', eventCalendarId);

  const response = await fetchData({
    place: 'events_calendar',
    data: parameters
  });

  if (response.status === 'success') response.reminders.map(reminder => addNewEventReminderItem({
    quantity: reminder.quantity,
    periodicity: reminder.periodicity
  }));

  hidePageLoading();
}

$('#btn-add-event-reminder-item').on('click', addNewEventReminderItem);
$(document).on('click', '.btn-remove-event-reminder-item', removeEventReminderItem);