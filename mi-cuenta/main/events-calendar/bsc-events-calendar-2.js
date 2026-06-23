$(function () {
  const businessId = $('#desktop-business').val();
  loadPackages({
    bid: businessId
  });
});

const dayStatusItem = dayStatus => `
  <li class="d-flex align-items-center mb-1">
    <span class="d-flex align-items-center justify-content-center mr-1 bg-success rounded-circle" style="height: 1.3rem;width: 1.3rem;">
      ${dayStatus === 'free' || dateStatus == undefined ? `<i class="fa fa-check-circle text-white" style="font-size: 0.8rem;"></i>` : ``}
    </span> Disponible
  </li>

  <li class="d-flex align-items-center mb-1">
    <span class="d-flex align-items-center justify-content-center mr-1 bg-warning rounded-circle" style="height: 1.3rem;width: 1.3rem;">
      ${dayStatus === 'with-spaces' ? `<i class="fa fa-check-circle text-white" style="font-size: 0.8rem;"></i>` : ``}
    </span> Con espacios
  </li>

  <li class="d-flex align-items-center mb-1">
    <span class="d-flex align-items-center justify-content-center mr-1 bg-danger rounded-circle" style="height: 1.3rem;width: 1.3rem;">
      ${dayStatus === 'occupied' ? `<i class="fa fa-check-circle text-white" style="font-size: 0.8rem;"></i>` : ``}
    </span> No disponible
  </li>
`;

const eventCalendarItem = data => `
  <div class="media d-flex flex-column mb-4 mx-0 px-0 border-bottom-1 pb-2">
    <div class="d-flex align-items-center">
      <div class="m1-0 mr-3 position-relative">
        <a href="javascript:void(0)">
          <img class="custom-img-thumbnail" src="../src/assets/images/listing/${data.Imagen}" alt="${data.Salon}">
        </a>
      </div>

      <div class="media-body">
        <a href="javascript:void(0)" class="text-dark hover-primary">
          <h5 class="fs-16 mb-0 lh-18">${data.Salon}</h5>
        </a>

        <p class="mb-1 fs-14">${data.Ciudad}, ${data.Estado}</p>

        <span class="text-heading lh-15 font-weight-bold fs-17">Paquete:</span>
        <span class="text-gray-light">${data.Paquete}</span>
      </div>
    </div>

    ${data.HoraInicio || data.HoraFinal ? `
      <div class="d-flex flex-column align-items-center w-100 mt-2">
        <div class="d-flex justify-content-center w-100 px-1" style="gap: 1rem;">
          ${data.HoraInicio ? `
            <div class="time-label">
              <span>Hora inicio</span>
              <p>${data.HoraInicio}</p>
            </div>
          ` : ``}

          ${data.HoraFinal ? `
            <div class="time-label">
              <span>Hora final</span>
              <p>${data.HoraFinal}</p>
            </div>
          ` : ``}
        </div>
      </div>
    ` : ``}

    <div class="d-flex flex-column align-items-center w-100 mt-2">
      <div class="business-total-cost">
        <span>Costo total: </span> $${data.CostoTotalFormat}
      </div>
    </div>

    <div class="d-flex align-items-center justify-content-center w-100 mt-3">
      <a href="javascript:void(0)" class="d-inline-block fs-18 text-dark hover-primary mr-5 btn-edit-reservation" data-reservation='${JSON.stringify(data)}'>
        <i class="fal fa-pencil-alt"></i>
      </a>

      <a href="javascript:void(0)" class="d-inline-block fs-18 text-dark hover-primary btn-delete-reservation" data-reservation='${JSON.stringify(data)}'>
        <i class="fal fa-trash-alt"></i>
      </a>
    </div>
  </div>
`;

const reminderCalendarItem = data => `
  <div class="media d-flex flex-column mb-4 mx-0 px-0 border-bottom-1 pb-2">
    <div class="d-flex align-items-center">
      <div class="m1-0 mr-3 position-relative">
        <a class="p-2" href="javascript:void(0)" style="background-color: ${data.color};"></a>
      </div>

      <div class="media-body">
        <a href="javascript:void(0)" class="text-dark hover-primary">
          <h5 class="fs-16 mb-0 lh-18">${data.title}</h5>
        </a>

        <p class="mb-1 fs-14">${data.description}</p>
      </div>
    </div>

    <div class="d-flex flex-column align-items-center w-100 mt-2">
      <div class="d-flex justify-content-center w-100 px-1" style="gap: 1rem;">
        ${data.dateDesde ? `
          <div class="time-label">
            <span>Hora inicio</span>
            <p>${data.dateDesdeFormat}</p>
          </div>
        ` : ``}

        ${data.dateHasta ? `
          <div class="time-label">
            <span>Hora final</span>
            <p>${data.dateHastaFormat}</p>
          </div>
        ` : ``}
      </div>
    </div>

    <div class="d-flex align-items-center justify-content-center w-100 mt-3">
      <a href="javascript:void(0)" class="d-inline-block fs-18 text-dark hover-primary mr-5 btn-edit-reminder" data-reminder='${JSON.stringify(data)}'>
        <i class="fal fa-pencil-alt"></i>
      </a>

      <a href="javascript:void(0)" class="d-inline-block fs-18 text-dark hover-primary btn-delete-reminder" data-reminder='${JSON.stringify(data)}'>
        <i class="fal fa-trash-alt"></i>
      </a>
    </div>
  </div>
`;

let dateStatus = 'Libre';
let selectedDate;

const renderCalendarDateData = data => {
  dateStatus = data.dateStatus;
  selectedDate = data.date;

  console.log(data.dateStatus);

  $('#list-day-status-container').html(dayStatusItem(data.dateStatus));

  const events = data.events;
  const reservationsContainer = $('#tab-reservations');

  reservationsContainer.html('');

  if (events) events.map(item => reservationsContainer.append(eventCalendarItem(item)));
  if (!events) reservationsContainer.html('No hay reservaciones en esta fecha.');

  const reminders = data.reminders;
  const remindersContainer = $('#tab-reminders');

  remindersContainer.html('');

  if (reminders) reminders.map(item => remindersContainer.append(reminderCalendarItem(item)));
  if (!reminders) remindersContainer.html('No hay recordatorios en esta fecha.');
}

const parseDate = (date, target = '#date') => {
  var newdate = new Date(date);
  newdate.setDate(newdate.getDate() + 1);

  var dd = String(newdate.getDate()).padStart(2, '0');
  var mm = String(newdate.getMonth() + 1).padStart(2, '0'); //January is 0!
  var yyyy = newdate.getFullYear();

  newdate = dd + '/' + mm + '/' + yyyy;

  $(target).data("DateTimePicker").date(newdate);
}

const changeModalState = state => {
  if (state === 'initial') {
    $('#form-container').removeClass('event');
    $('#form-container').removeClass('reminder');
    $('#modal-add-edit-event-calendar .modal-title').html('Elige una opción');
  }

  if (state === 'add-event') {
    $('#form-container').removeAttr('class');
    $('#form-container').addClass('col-md-12 form-container event');
    $('#modal-add-edit-event-calendar .modal-title').html('Agregar reservación');
  }

  if (state === 'add-reminder') {
    $('#form-container').removeAttr('class');
    $('#form-container').addClass('col-md-12 form-container reminder');
    $('#modal-add-edit-event-calendar .modal-title').html('Agregar recordatorio');
  }
}

async function loadPackages({ bid, pid }) {
  const businessId = !!bid ? bid : $(this).val();

  if (!businessId) return;

  showPageLoading();

  console.log(bid, ' - ', pid);

  const parameters = new FormData();

  parameters.append('businessId', businessId);
  parameters.append('action', 'list_packages');

  const response = await fetchData({
    place: 'selects',
    data: parameters
  });

  console.log(response);

  if (response.content) {
    await $('#package').html(decodeURIComponent(escape(atob(response.content))));

    if (pid) $('#package').val(pid);
  }

  hidePageLoading();
}

async function sendReservationData(e) {
  e.preventDefault();
  showPageLoading();

  const business = $('#desktop-business').val();
  const parameters = new FormData($(this)[0]);

  parameters.append('business', business);

  const response = await fetchData({
    place: 'events_calendar',
    data: parameters
  });

  hidePageLoading();

  if (response.message) showBigAlert({
    icon: response.status,
    title: response.title,
    subtitle: response.message
  });

  if (response.status === 'success') {
    $('#modal-add-edit-event-calendar').modal('hide');
    calendar.setCalendarData({
      events: response.calendar.reservations,
      reminders: response.calendar.reminders,
      dateStatus: response.calendar.dateStatus
    });

    const eventsData = calendar.getCalendarDataByDate(selectedDate);
    renderCalendarDateData(eventsData);

    $('#today-events-info-container').html(response.todayEvents);

    //calendar.addEvents(response.calendar.reservations);
    //calendar.addDateStatus(response.calendar.dateStatus);
    //calendar.createCalendar();
    //const eventsData = calendar.getCalendarDataByDate(selectedDate);
    //renderCalendarDateData(eventsData);
  }
}

async function saveReminder(e) {
  e.preventDefault();
  showPageLoading();

  const parameters = new FormData($(this)[0]);

  const response = await fetchData({
    place: 'events_calendar',
    data: parameters
  });

  hidePageLoading();

  if (response.message) showBigAlert({
    title: response.title,
    subtitle: response.message
  });

  if (response.status === 'success') {
    $('#modal-add-edit-event-calendar').modal('hide');

    calendar.setCalendarData({
      events: response.calendar.reservations,
      reminders: response.calendar.reminders,
      dateStatus: response.calendar.dateStatus
    });

    const dataSelected = calendar.getCalendarDataByDate(selectedDate);
    renderCalendarDateData(dataSelected);
  }
}

const handleAddReservation = () => {
  const date = selectedDate;
  changeModalState('add-event');
  $('.btn-modal-title').html('<i class="fal fa-check-circle"></i> Guardar');

  resetForm('#add-event-form');
  resetForm('#reminders-form');

  $('#payment-recordatory-container').html('');
  $('#event-reminder-container').html('');
  $('#event-reminder-container').html(addNewEventReminderItem({
    quantity: 1,
    periodicity: 'Dia'
  }));

  parseDate(date);
  parseDate(date, '#reminderDesde');
  //changeModalTitle('Agregar recordatorio', '-reminders');

  $('#action-events-calendar').val('add_reservation');
  $('#action-reminders').val('add_event_calendar');

  $('input:radio[name="status"]').attr('checked', false);
  $('input:radio[name="status"]').filter('[value="Libre"]').attr('checked', true);

  $('#modal-add-edit-event-calendar').modal('show');
}

const handleAddReminder = () => {
  const date = selectedDate;
  changeModalState('add-reminder');
  $('.btn-modal-title').html('<i class="fal fa-check-circle"></i> Guardar');

  resetForm('#add-event-form');
  resetForm('#reminders-form');

  $('#payment-recordatory-container').html('');
  $('#event-reminder-container').html('');
  $('#event-reminder-container').html(addNewEventReminderItem({
    quantity: 1,
    periodicity: 'Dia'
  }));

  parseDate(date);
  parseDate(date, '#reminderDesde');

  $('#action-events-calendar').val('add_reservation');
  $('#action-reminders').val('add_event_calendar');

  $('input:radio[name="status"]').attr('checked', false);
  $('input:radio[name="status"]').filter('[value="Libre"]').attr('checked', true);

  $('#modal-add-edit-event-calendar').modal('show');
  $('#modal-add-edit-event-calendar .modal-title').html('Agregar recordatorio');
}

async function handleRemoveReservation() {
  const data = JSON.parse($(this).attr('data-reservation'));

  const alertResponse = await showSweetConfirm({
    icon: 'warning',
    title: '!cuidado!',
    subtitle: '¿Realmente desea eliminar la reservación?'
  });

  if (!alertResponse) return;

  showPageLoading();

  const parameters = new FormData();

  parameters.append('action', 'delete_reservation');
  parameters.append('reservationId', data.idReservacion);
  parameters.append('year', calendar.getYear());

  const response = await fetchData({
    place: 'events_calendar',
    data: parameters
  });

  hidePageLoading();

  if (response.message) showSweetAlert({
    icon: response.status,
    title: response.message
  });

  if (response.status === 'success') {
    calendar.setCalendarData({
      events: response.calendar.reservations,
      reminders: response.calendar.reminders,
      dateStatus: response.calendar.dateStatus
    });

    const dataSelected = calendar.getCalendarDataByDate(selectedDate);
    renderCalendarDateData(dataSelected);

    $('#today-events-info-container').html(response.todayEvents);
  }
}

function handleEditReservation() {
  resetForm('#add-event-form');
  $('#payment-recordatory-container').html('');
  $('.btn-modal-title').html('<i class="fal fa-check-circle"></i> Guardar cambios');

  const data = JSON.parse($(this).attr('data-reservation'));
  const date = data.Fecha;

  let status = $(this).parent().parent().attr('data-status');

  if (dateStatus === 'with-spaces') status = 'Con espacios';
  else if (dateStatus === 'occupied') status = 'Ocupado';
  else if (dateStatus === 'free') status = 'Libre';
  else status = 'Libre';

  parseDate(date);

  //$('#desktop-business').val(data.idNegocio);
  $('#eventType').val(data.idTipoEvento);
  $('#name').val(data.NombreCompleto);
  $('#email').val(data.Correo);
  $('#phone').val(data.Telefono);
  if (!data.HoraInicio) $('#startTime').val('');
  if (data.HoraInicio) $('#startTime').val(data.HoraInicio);

  if (!data.HoraFinal) $('#endTime').val('');
  if (data.HoraFinal) $('#endTime').val(data.HoraFinal);
  $('#NPersons').val(data.NPersonas);
  $('#extras').val(data.Extras);
  $('#totalCost').val(data.CostoTotal);
  $('#deposit').val(data.Deposito);
  $('#advance').val(data.Anticipo);
  $('#status').val(data.DateStatus);
  $('#action-events-calendar').val('edit_reservation')
  $('#reservationId').val(data.idReservacion);

  //console.log(data.Status);

  changeModalState('add-event');

  loadPackages({
    bid: data.idNegocio,
    pid: data.idPaquete
  });

  loadPaymentReminders(data.idReservacion);

  $('input:radio[name="status"]').attr('checked', false);
  $('input:radio[name="status"]').filter('[value="' + status + '"]').attr('checked', true);

  $('#modal-add-edit-event-calendar').modal('show');
  $('#modal-add-edit-event-calendar .modal-title').html('Editar reservación');
}

async function handleRemoveReminder() {
  const data = JSON.parse($(this).attr('data-reminder'));

  const alertResponse = await showSweetConfirm({
    icon: 'warning',
    title: '!cuidado!',
    subtitle: '¿Realmente desea eliminar el recordatorio?'
  });

  if (!alertResponse) return;

  showPageLoading();

  const parameters = new FormData();

  parameters.append('action', 'delete_reminder');
  parameters.append('eventCalendarId', data.eventCalendarId);
  parameters.append('year', calendar.getYear());

  const response = await fetchData({
    place: 'events_calendar',
    data: parameters
  });

  hidePageLoading();

  if (response.message) showSweetAlert({
    icon: response.status,
    title: response.message
  });

  if (response.status === 'success') {
    calendar.setCalendarData({
      events: response.calendar.reservations,
      reminders: response.calendar.reminders,
      dateStatus: response.calendar.dateStatus
    });

    const dataSelected = calendar.getCalendarDataByDate(selectedDate);
    renderCalendarDateData(dataSelected);
  }
}

function handleEditReminder() {
  const data = JSON.parse($(this).attr('data-reminder'));
  $('.btn-modal-title').html('<i class="fal fa-check-circle"></i> Guardar cambios');
  resetForm('#reminders-form');
  changeModalState('add-reminder');

  $('#event-reminder-container').html('');

  $('#eventCalendarId').val(data.eventCalendarId);
  $('#reminderTitle').val(data.title);
  $('#reminderColor').val(data.color);
  $('#reminderDescription').val(data.description);

  parseDate(data.dateDesde, '#reminderDesde');
  parseDate(data.dateHasta, '#reminderHasta');

  $('#action-reminders').val('edit_event_calendar');

  loadEventsCalendarReminders(data.eventCalendarId);

  $('#modal-add-edit-event-calendar').modal('show');
  $('#modal-add-edit-event-calendar .modal-title').html('Editar recordatorio');
}

async function changeDayStatus(e) {
  e.preventDefault();
  showPageLoading();

  const parameters = new FormData($(this)[0]);
  parameters.append('action', 'change_day_status');
  parameters.append('year', calendar.getYear());

  const response = await fetchData({
    place: 'events_calendar',
    data: parameters
  });

  hidePageLoading();

  if (response.message) showSweetAlert({
    icon: response.status,
    title: response.message
  });

  if (response.status === 'success') {
    $('#modal-change-day-status').modal('hide');

    console.log(response.calendar);

    calendar.setCalendarData({
      events: response.calendar.reservations,
      reminders: response.calendar.reminders,
      dateStatus: response.calendar.dateStatus
    });

    const dataSelected = calendar.getCalendarDataByDate(selectedDate);
    renderCalendarDateData(dataSelected);
  }
}

$(document).on('click', '.btn-edit-reservation', handleEditReservation);
$(document).on('click', '.btn-delete-reservation', handleRemoveReservation);
$('.btn-add-reservation').on('click', handleAddReservation);
$('#add-event-form').on('submit', sendReservationData);

$(document).on('click', '.btn-edit-reminder', handleEditReminder);
$('#reminders-form').on('submit', saveReminder);
$('.btn-add-reminder').on('click', handleAddReminder);
$(document).on('click', '.btn-delete-reminder', handleRemoveReminder);

$('#btn-change-day-status').on('click', () => {
  let status = 'Libre';

  if (dateStatus === 'with-spaces') status = 'Con espacios';
  if (dateStatus === 'occupied') status = 'Ocupado';
  if (dateStatus === 'free') status = 'Libre';

  $('#change-day-status-form input[name="status"]').removeAttr('checked');
  $(`#change-day-status-form input[value="${status}"]`).prop('checked', true);
  $('#change-day-status-date').val(selectedDate);
});

$('#change-day-status-form').on('submit', changeDayStatus);