$(function () {
  const businessId = $('#desktop-business').val();

  loadReservations();
  loadPackages({
    bid: businessId
  });
});

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
  });

  if (response.status != 'success') cscCreateCalendar({
    locale: 'es',
    handleAdd: handleAddReservation,
    handleUnlock: unlockDay
  });
}

const parseDate = date => {
  var newdate = new Date(date);
  newdate.setDate(newdate.getDate() + 1);

  var dd = String(newdate.getDate()).padStart(2, '0');
  var mm = String(newdate.getMonth() + 1).padStart(2, '0'); //January is 0!
  var yyyy = newdate.getFullYear();

  newdate = dd + '/' + mm + '/' + yyyy;

  $('#date').data("DateTimePicker").date(newdate);
}

const handleAddReservation = date => {
  resetForm('#add-event-form');
  $('#payment-recordatory-container').html('');

  parseDate(date);

  $('#action-events-calendar').val('add_reservation')

  changeModalState('initial');

  $('input:radio[name="status"]').attr('checked', false);
  $('input:radio[name="status"]').filter('[value="Libre"]').attr('checked', true);

  $('#modal-add-edit-event-calendar').modal('show');
}

const changeModalState = state => {
  if (state === 'initial') $('#form-container').removeClass('event');

  if (state === 'add-event') {
    $('#form-container').removeAttr('class');
    $('#form-container').addClass('col-md-12 form-container event');
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

$('#desktop-business').on('change', loadReservations);
$('#desktop-business').on('change', loadPackages);
$('#add-event-form').on('submit', sendReservationData);
$(document).on('click', '.btn-csc-event', handleEditReservation);


/* $(function () {
  loadReservations();
}); */

/* const loadReservations = async () => {
  showPageLoading();

  const parameters = new FormData();

  parameters.append('action', 'list_reservations');

  const response = await fetchData({
    place: 'events_calendar',
    data: parameters
  });

  hidePageLoading();

  if (response.status === 'success') cscCreateCalendar({
    locale: 'es',
    handleAdd: handleAddReservation,
    handleUnlock: unlockDay
  }).then(() => {
    cscAddEvents(response.events);
    if (response.dates) cscAddDateStatus(response.dates);
  });

  if (response.status != 'success') cscCreateCalendar({
    locale: 'es',
    handleAdd: handleAddReservation,
    handleUnlock: unlockDay
  });
}

const parseDate = date => {
  var newdate = new Date(date);
  newdate.setDate(newdate.getDate() + 1);

  var dd = String(newdate.getDate()).padStart(2, '0');
  var mm = String(newdate.getMonth() + 1).padStart(2, '0'); //January is 0!
  var yyyy = newdate.getFullYear();

  newdate = dd + '/' + mm + '/' + yyyy;

  $('#date').data("DateTimePicker").date(newdate);
}

const handleAddReservation = date => {
  resetForm('#add-event-form');

  parseDate(date);

  $('#action-events-calendar').val('add_reservation')

  changeModalState('initial');

  $('input:radio[name="status"]').attr('checked', false);
  $('input:radio[name="status"]').filter('[value="Libre"]').attr('checked', true);

  $('#modal-add-edit-event-calendar').modal('show');
}

const changeModalState = state => {
  if (state === 'initial') $('#form-container').removeClass('event');

  if (state === 'add-event') {
    $('#form-container').removeAttr('class');
    $('#form-container').addClass('col-md-12 form-container event');
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

  const parameters = new FormData($(this)[0]);

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
    title: '¡Bloquear la fecha!',
    subtitle: '¿Realmente desea bloquear esta fecha?'
  });

  if (!alertResponse) return;

  showPageLoading();

  const date = $('#date').val();
  const parameters = new FormData();

  parameters.append('date', date);
  parameters.append('action', 'block_date');

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
    title: '¡Desbloquear fecha!',
    subtitle: '¿Realmente desea desbloquear esta fecha?'
  });

  if (!alertResponse) return;

  showPageLoading();

  const parameters = new FormData();

  parameters.append('date', date);
  parameters.append('action', 'unlock_date');

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

function handleEditReservation() {
  resetForm('#add-event-form');

  let dateStatus = 'Libre';
  const data = JSON.parse(atob($(this).attr('data-info')));
  const date = $(this).attr('data-date');

  const initialStatus = $(this).parent().parent().attr('data-status');

  if (initialStatus === 'with-spaces') dateStatus = 'Con espacios';
  if (initialStatus === 'occupied') dateStatus = 'Ocupado';

  parseDate(date);

  $('#desktop-business').val(data.idNegocio);
  $('#eventType').val(data.idTipoEvento);
  $('#name').val(data.NombreCompleto);
  $('#email').val(data.Correo);
  $('#phone').val(data.Telefono);
  $('#startTime').val(data.HoraInicio);
  $('#endTime').val(data.HoraFinal);
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

  $('input:radio[name="status"]').attr('checked', false);
  $('input:radio[name="status"]').filter('[value="' + dateStatus + '"]').attr('checked', true);

  $('#modal-add-edit-event-calendar').modal('show');
}

$('#desktop-business').on('change', loadPackages);
$('#add-event-form').on('submit', sendReservationData);
$(document).on('click', '.btn-csc-event', handleEditReservation); */

/* $(document).on('click', '.cs-calendar-days ul li', function () {
  resetForm('#add-event-form');

  const date = $(this).attr('data-date');
  const dataInfo = $(this).attr('data-info');

  var newdate = new Date(date);
  newdate.setDate(newdate.getDate() + 1);

  //var newdate = new Date(date);
  var dd = String(newdate.getDate()).padStart(2, '0');
  var mm = String(newdate.getMonth() + 1).padStart(2, '0'); //January is 0!
  var yyyy = newdate.getFullYear();

  newdate = dd + '/' + mm + '/' + yyyy;

  console.log(newdate);

  $('#date').data("DateTimePicker").date(newdate);
  $('#action-events-calendar').val('add_reservation')

  changeModalState('initial');

  $('input:radio[name="status"]').attr('checked', false);
  $('input:radio[name="status"]').filter('[value="Libre"]').attr('checked', true);

  if (dataInfo) {
    const data = JSON.parse(atob(dataInfo));

    $('#desktop-business').val(data.idNegocio);
    $('#eventType').val(data.idTipoEvento);
    $('#name').val(data.NombreCompleto);
    $('#email').val(data.Correo);
    $('#phone').val(data.Telefono);
    $('#startTime').val(data.HoraInicio);
    $('#endTime').val(data.HoraFinal);
    $('#NPersons').val(data.NPersonas);
    $('#extras').val(data.Extras);
    $('#totalCost').val(data.CostoTotal);
    $('#deposit').val(data.Deposito);
    $('#advance').val(data.Anticipo);
    //$('#status').val(data.Status);
    $('input:radio[name="status"]').filter('[value="' + data.Status + '"]').attr('checked', true);
    $('#action-events-calendar').val('edit_reservation')
    $('#reservationId').val(data.idReservacion);

    console.log(data.Status);

    changeModalState('add-event');
    loadPackages(data.idNegocio, data.idPaquete);
  }

  $('#modal-add-edit-event-calendar').modal('show');
});

const changeModalState = state => {
  if (state === 'initial') $('#form-container').removeClass('event');

  if (state === 'add-event') {
    $('#form-container').removeAttr('class');
    $('#form-container').addClass('col-md-12 form-container event');
  }
}

async function loadPackages(bid, pid) {
  const businessId = !!bid ? bid : $(this).val();

  if (!businessId) return;

  showPageLoading();

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

  const parameters = new FormData($(this)[0]);

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
    loadReservations();
  }
}

$('#desktop-business').on('change', loadPackages);

$('#add-event-form').on('submit', sendReservationData); */