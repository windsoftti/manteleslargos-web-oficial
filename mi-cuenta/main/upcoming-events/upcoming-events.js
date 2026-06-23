$(document).ready(function () {
  $("#search-filter-form").bind("keypress", function (e) {
    if (e.keyCode == 13) {
      return false;
    }
  });

  loadUpcomingEvents();
});

function searchUpcomingEvents() {
  useSearch(loadUpcomingEvents);
}

async function loadUpcomingEvents(page = 1) {
  console.log('load');
  showPageLoading();
  const dataSend = new FormData($('#search-filters-form')[0]);

  dataSend.append('page', page);
  dataSend.append('action', 'list_upcoming_events');

  const response = await fetchData({
    place: 'upcoming_events',
    data: dataSend
  });

  if (response.content) {
    const table = decodeURIComponent(escape(atob(response.content)));
    $('#list-upcoming-events').html(table);
  }

  hidePageLoading();
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

async function loadPackages({ bid, pid }) {
  const businessId = bid;

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

  /* console.log(response); */

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
    $('#modal-upcoming-events').modal('hide');
    loadUpcomingEvents();
  }
}

function handleEditReservation() {
  resetForm('#upcoming-events-form');
  $('#payment-recordatory-container').html('');

  //let dateStatus = 'Libre';

  const data = JSON.parse(atob($(this).attr('data-event')));
  const dateStatus = $(this).attr('data-dateStatus');
  const date = data.Fecha;

  //const initialStatus = $(this).parent().parent().attr('data-status');

  //if (initialStatus === 'with-spaces') dateStatus = 'Con espacios';
  //if (initialStatus === 'occupied') dateStatus = 'Ocupado';

  parseDate(date);

  $('#businessId').val(data.idNegocio);
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

  //changeModalState('add-event');

  loadPackages({
    bid: data.idNegocio,
    pid: data.idPaquete
  });

  loadPaymentReminders(data.idReservacion);

  $('input:radio[name="status"]').attr('checked', false);
  $('input:radio[name="status"]').filter('[value="' + dateStatus + '"]').attr('checked', true);

  $('#modal-add-edit-event-calendar').modal('show');
}

$(document).on('click', '.btn-edit-event', handleEditReservation);
$('#upcoming-events-form').on('submit', sendReservationData);