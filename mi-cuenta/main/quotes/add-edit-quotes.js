$(function () {
  initBDatePicker('#quoteDate');
});

async function saveQuoteData(e) {
  e.preventDefault();
  showPageLoading();

  const parameters = new FormData($(this)[0]);

  const response = await fetchData({
    place: 'quotes',
    data: parameters
  });

  hidePageLoading();

  if (response.message) showBigAlert({
    title: response.title,
    subtitle: response.message
  });

  if (response.status === 'success') {
    closeModal('modal-add-edit-quote');
    resetForm('#add-edit-quotes-form');
    loadQuotes();
    $('#sidebar-recent-events-count').html(response.recentEventsTotal);
    $('#sidebar-quotes-count').html(response.total);
  }
}

function handleAddQuote() {
  changeModalTitle('Agregar cotización');
  resetForm('#add-edit-quotes-form');
  $('#action-add-edit-quotes').val('add_quote');
}

function handleEditQuote() {
  changeModalTitle('Editar cotización');
  resetForm('#add-edit-quotes-form');
  const data = JSON.parse(atob($(this).attr('data-quote')));

  $('#quotePackage').val(data.idPaquete);
  parseDate(data.FechaSolicitada, '#quoteDate');
  $('#quoteEventType').val(data.idTipoEvento);
  $('#quoteName').val(data.NombreCompleto);
  $('#quoteEmail').val(data.Email);
  $('#quotePhone').val(data.Telefono);
  $('#add-edit-quoteId').val(data.idCotizacion);

  $('#action-add-edit-quotes').val('edit_quote');
}

async function handleCancelQuote() {
  const data = JSON.parse(atob($(this).attr('data-quote')));

  const alertResponse = await showSweetConfirm({
    title: '¡Cuidado!',
    subtitle: '¿Realmente desea cancelar la cotización?'
  });

  if (!alertResponse) return;

  showPageLoading();

  const quoteId = data.idCotizacion;
  const parameters = new FormData();

  parameters.append('quoteId', quoteId);
  parameters.append('action', 'cancel_quote');

  const response = await fetchData({
    place: 'quotes',
    data: parameters
  });

  hidePageLoading();

  if (response.message) showSweetAlert({
    icon: response.status,
    title: response.message
  });

  if (response.status === 'success') loadQuotes();
}

async function handleResumeQuote() {
  const data = JSON.parse(atob($(this).attr('data-quote')));

  const alertResponse = await showSweetConfirm({
    icon: 'info',
    title: '¡Recuperar!',
    subtitle: '¿Recuperar la cotización?'
  });

  if (!alertResponse) return;

  showPageLoading();

  const quoteId = data.idCotizacion;
  const parameters = new FormData();

  parameters.append('quoteId', quoteId);
  parameters.append('action', 'resume_quote');

  const response = await fetchData({
    place: 'quotes',
    data: parameters
  });

  hidePageLoading();

  if (response.message) showSweetAlert({
    icon: response.status,
    title: response.message
  });

  if (response.status === 'success') loadQuotes();
}

async function handleContactQuote() {
  const data = JSON.parse(atob($(this).attr('data-quote')));

  const alertResponse = await showSweetConfirm({
    icon: 'info',
    title: '¡Cuidado!',
    subtitle: '¿Marcar como contestado?'
  });

  if (!alertResponse) return;

  showPageLoading();

  const quoteId = data.idCotizacion;
  const parameters = new FormData();

  parameters.append('quoteId', quoteId);
  parameters.append('action', 'contact_quote');

  const response = await fetchData({
    place: 'quotes',
    data: parameters
  });

  hidePageLoading();

  if (response.message) showSweetAlert({
    icon: response.status,
    title: response.message
  });

  if (response.status === 'success') loadQuotes();
}

$('#add-edit-quotes-form').on('submit', saveQuoteData);
$('.btn-add-quote').on('click', handleAddQuote);
$(document).on('click', '.btn-edit-quote', handleEditQuote);
$(document).on('click', '.btn-cancel-quote', handleCancelQuote);
$(document).on('click', '.btn-resume-quote', handleResumeQuote);
$(document).on('click', '.btn-contact-quote', handleContactQuote);