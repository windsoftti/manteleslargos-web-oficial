$(document).ready(function () {
  $("#search-filter-form").bind("keypress", function (e) {
    if (e.keyCode == 13) {
      return false;
    }
  });

  loadQuotes();
});

function searchQuotes() {
  useSearch(loadQuotes);
}

async function loadQuotes(page = 1) {
  showPageLoading();
  const dataSend = new FormData($('#search-filters-form')[0]);

  dataSend.append('page', page);
  dataSend.append('action', 'list_quotes');

  const response = await fetchData({
    place: 'quotes',
    data: dataSend
  });

  console.log(response);

  if (response.content) {
    const table = decodeURIComponent(escape(atob(response.content)));
    $('#list-quotes').html(table).show('slow');
    $('#sidebar-quotes-count').html(response.total);
    $('#sidebar-recent-events-count').html(response.recentEventsTotal);
  }

  hidePageLoading();
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

function handleScheduleDate() {
  const data = JSON.parse(atob($(this).attr('data-quote')));

  resetForm('#quotes-form');
  $('#payment-recordatory-container').html('');

  $('#quoteId').val(data.idCotizacion);
  $('#business').val(data.idNegocio);
  $('#eventType').val(data.idTipoEvento);
  $('#name').val(data.NombreCompleto);
  $('#email').val(data.Email);
  $('#phone').val(data.Telefono);
  /* $('#startTime').val('');
  $('#endTime').val('');
  $('#NPersons').val('');
  $('#extras').val('');
  $('#totalCost').val('');
  $('#deposit').val('');
  $('#advance').val(''); */

  console.log(data.idNegocio);

  parseDate(data.FechaSolicitada);

  loadPackages({
    bid: data.idNegocio,
    pid: data.idPaquete
  });

  $('input:radio[name="status"]').attr('checked', false);
  $('input:radio[name="status"]').filter('[value="Libre"]').attr('checked', true);
}

async function sendQuoteData(e) {
  e.preventDefault();
  showPageLoading();
  const form = '#quotes-form';

  const dataSend = new FormData($(form)[0]);

  const response = await fetchData({
    place: 'quotes',
    data: dataSend
  });

  if (response) {
    showBigAlert({
      icon: response.status,
      title: response.title,
      subtitle: response.message
    });

    if (response.status === 'success') {
      closeModal('modal-schedule-date');
      resetForm(form);
      loadQuotes();
      $('#sidebar-recent-events-count').html(response.recentEventsTotal);
    }
  }

  hidePageLoading();
}

$(document).on('click', '.btn-schedule-date', handleScheduleDate);
$('#quotes-form').on('submit', sendQuoteData);



/* const handleAddReservation = date => {
  resetForm('#add-event-form');

  parseDate(date);

  $('#action-events-calendar').val('add_reservation')

  changeModalState('initial');

  $('input:radio[name="status"]').attr('checked', false);
  $('input:radio[name="status"]').filter('[value="Libre"]').attr('checked', true);

  $('#modal-add-edit-event-calendar').modal('show');
} */



/* async function sendQuoteData() {
  showPageLoading();
  const form = '#quotes-form';

  const check = await checkInputValidate(form);

  if (!check) {
    hidePageLoading();
    return;
  }

  const dataSend = new FormData($(form)[0]);

  const response = await fetchData({
    place: 'quotes',
    data: dataSend
  });

  if (response) {
    showBigAlert({
      icon: response.state,
      title: response.title,
      subtitle: response.message
    });

    if (response.state === 'success') {
      closeModal('modal-add-edit-quote');
      resetForm(form);
      loadQuotes();
    }
  }

  hidePageLoading();
}

$(document).on('click', '.btn-delete-quote', async function () {
  const quoteId = $(this).attr('data-quoteId');
  const quote = $(this).attr('data-quote');

  const alertResponse = await showSweetConfirm({
    title: '!Cuidado!',
    subtitle: `¿Realmente desea eliminar a "${quote}"?`
  });

  if (!alertResponse) return;

  showPageLoading();

  const dataSend = new FormData();

  dataSend.append('quoteId', quoteId);
  dataSend.append('quote', quote);
  dataSend.append('action', 'delete_quote');

  const response = await fetchData({
    place: 'quotes',
    data: dataSend
  });

  if (response) {
    showSweetAlert({
      icon: response.state,
      title: response.title
    });

    if (response.state === 'success') {
      loadQuotes();
    }
  }

  hidePageLoading();
}); */

/* $('.btn-add-quote').on('click', function () {
  changeModalTitle('Agregar amenidad');
  resetForm('#quotes-form');
  hideInputWarnings();
  $('#action-quotes').val('add_quote');
}); */