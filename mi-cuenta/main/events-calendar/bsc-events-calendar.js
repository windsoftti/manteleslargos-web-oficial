$(function () {
  const businessId = $('#desktop-business').val();

  loadReservations();
  loadPackages({
    bid: businessId
  });
});

const loadEventsCalendar = async () => {
  showPageLoading();

  const parameters = new FormData();
  parameters.append('action', 'load_events_calendar');

  const response = await fetchData({
    place: 'events_calendar',
    data: parameters
  });

  hidePageLoading();

  if (response.status === 'success') cscAddReminders(response.reminders);
}

async function loadReservations() {
  showPageLoading();

  const businessId = $('#desktop-business').val();
  const parameters = new FormData();

  parameters.append('businessId', businessId);
  parameters.append('action', 'list_reservations');

  const response = await fetchData({
    place: 'events_calendar',
    data: parameters
  });

  hidePageLoading();

  console.log(response);

  if (response.status === 'success') cscCreateCalendar({
    locale: 'es',
    handleAdd: handleAddReservation,
    handleUnlock: unlockDay
  }).then(() => {
    cscAddEvents(response.events);
    if (response.dates) cscAddDateStatus(response.dates);
    loadEventsCalendar();
  });

  if (response.status != 'success') cscCreateCalendar({
    locale: 'es',
    handleAdd: handleAddReservation,
    handleUnlock: unlockDay
  });
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

const handleAddReservation = date => {
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
  changeModalTitle('Agregar recordatorio', '-reminders');

  $('#action-events-calendar').val('add_reservation');
  $('#action-reminders').val('add_event_calendar');

  changeModalState('initial');

  $('input:radio[name="status"]').attr('checked', false);
  $('input:radio[name="status"]').filter('[value="Libre"]').attr('checked', true);

  $('#modal-add-edit-event-calendar').modal('show');
}

const changeModalState = state => {
  if (state === 'initial') {
    $('#form-container').removeClass('event');
    $('#form-container').removeClass('reminder');
  }

  if (state === 'add-event') {
    $('#form-container').removeAttr('class');
    $('#form-container').addClass('col-md-12 form-container event');
  }

  if (state === 'add-reminder') {
    $('#form-container').removeAttr('class');
    $('#form-container').addClass('col-md-12 form-container reminder');
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

  console.log(response);

  if (response.message) showBigAlert({
    icon: response.status,
    title: response.title,
    subtitle: response.message
  });

  if (response.status === 'success') {
    $('#modal-add-edit-event-calendar').modal('hide');
    loadReservations();
  }
}

async function blockDay() {
  const alertResponse = await showSweetConfirm({
    title: '¡Inhabilitar día!',
    subtitle: '¿Realmente desea inhabilitar este día?'
  });

  if (!alertResponse) return;

  showPageLoading();

  const date = $('#date').val();
  const businessId = $('#desktop-business').val();
  const parameters = new FormData();

  parameters.append('date', date);
  parameters.append('action', 'block_date');
  parameters.append('business', businessId);

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
    $('#modal-add-edit-event-calendar').modal('hide');
    loadReservations();
  }
}

async function unlockDay(date) {
  const alertResponse = await showSweetConfirm({
    title: '¡Habilitar día!',
    subtitle: '¿Realmente desea habilitar esta día?'
  });

  if (!alertResponse) return;

  showPageLoading();

  const businessId = $('#desktop-business').val();
  const parameters = new FormData();

  parameters.append('date', date);
  parameters.append('action', 'unlock_date');
  parameters.append('business', businessId);

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
    loadReservations();
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
    loadReservations();
  }
}

function handleEditReservation() {
  resetForm('#add-event-form');
  $('#payment-recordatory-container').html('');

  let dateStatus = 'Libre';
  const data = JSON.parse(atob($(this).attr('data-info')));
  const date = $(this).attr('data-date');

  const initialStatus = $(this).parent().parent().attr('data-status');

  if (initialStatus === 'with-spaces') dateStatus = 'Con espacios';
  if (initialStatus === 'occupied') dateStatus = 'Ocupado';

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
  $('input:radio[name="status"]').filter('[value="' + dateStatus + '"]').attr('checked', true);

  $('#modal-add-edit-event-calendar').modal('show');
}

function handleEditReminder() {
  const data = JSON.parse(atob($(this).attr('data-info')));
  resetForm('#reminders-form');
  changeModalTitle('Editar recordatorio', '-reminders');

  $('#event-reminder-container').html('');

  $('#eventCalendarId').val(data.eventCalendarId);
  $('#reminderTitle').val(data.title);
  $('#reminderColor').val(data.color);
  $('#reminderDescription').val(data.description);

  parseDate(data.dateDesde, '#reminderDesde');
  parseDate(data.dateHasta, '#reminderHasta');

  $('#action-reminders').val('edit_event_calendar');

  loadEventsCalendarReminders(data.eventCalendarId);

  changeModalState('add-reminder');
  $('#modal-add-edit-event-calendar').modal('show');
}

$('#desktop-business').on('change', loadReservations);
$('#desktop-business').on('change', loadPackages);
$('#add-event-form').on('submit', sendReservationData);

$(document).on('click', '.btn-csc-event', handleEditReservation);
$(document).on('click', '.btn-csc-reminder', handleEditReminder);

$('#reminders-form').on('submit', saveReminder);