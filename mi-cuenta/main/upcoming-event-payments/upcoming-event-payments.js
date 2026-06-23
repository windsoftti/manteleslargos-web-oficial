const loadUpcomingEventPayments = async () => {
  showPageLoading();

  const form = document.getElementById('search-filters-form');
  const paymentContainer = document.getElementById('list_upcoming_event_payments');

  const parameters = new FormData(form);

  parameters.append('action', 'list_upcoming_event_payments');

  const response = await fetchData({
    place: 'upcoming_event_payments',
    data: parameters
  });

  console.log(response);

  hidePageLoading();

  if (response.content) {
    document.getElementById('currentBalance').value = response.balance;
    document.getElementById('newBalance').value = '';
    document.getElementById('payment').value = '';
    document.getElementById('comments').value = '';
    document.getElementById('edit-currentBalance').value = response.balance;

    const options2 = { style: 'currency', currency: 'MXN' };
    const numberFormat2 = new Intl.NumberFormat('es-MX', options2);

    if (response.balance <= 0) {
      document.getElementById('payment-container').style = 'display: none';
      $('#txt-saldo').html('$0.00');
    }

    if (response.balance > 0) {
      document.getElementById('payment-container').style = 'display: block';
      $('#txt-saldo').html(numberFormat2.format(response.balance.toFixed(2)));
    }

    if (response.totalPayments == 0) $('#txt-total-abonado').html('$0.00');
    if (response.totalPayments) $('#txt-total-abonado').html(numberFormat2.format(response.totalPayments.toFixed(2)));

    const paymentTable = decodeURIComponent(escape(atob(response.content)));
    paymentContainer.innerHTML = paymentTable;
  }
}

const calcNewBalance = () => {
  const currentBalance = parseFloat(document.getElementById('currentBalance').value);
  const payment = parseFloat(document.getElementById('payment').value);

  if (!payment) document.getElementById('newBalance').value = '';
  if (isNaN(payment)) return;

  const newBalance = currentBalance - payment;

  if (payment > currentBalance) showBigAlert({
    title: '¡Cuidado!',
    subtitle: 'El pago sobrepasa el saldo actual'
  }).then(() => {
    document.getElementById('payment').value = currentBalance;
    calcNewBalance();
  });

  if (payment <= currentBalance) {
    document.getElementById('newBalance').value = newBalance.toFixed(2);
  }
}

const calcEditBalance = () => {
  const currentBalance = parseFloat(document.getElementById('edit-currentBalance').value);
  const initialPayment = parseFloat(document.getElementById('edit-initialPayment').value);
  const payment = parseFloat(document.getElementById('edit-payment').value);

  const totalBalance = currentBalance + initialPayment;

  if (!payment) document.getElementById('edit-newBalance').value = totalBalance;
  if (isNaN(payment)) return;

  const newBalance = totalBalance - payment;

  if (payment > totalBalance) showBigAlert({
    title: '¡Cuidado!',
    subtitle: 'El pago sobrepasa el saldo actual'
  }).then(() => {
    document.getElementById('edit-payment').value = totalBalance;
    calcEditBalance();
  });

  if (payment <= totalBalance) {
    document.getElementById('edit-newBalance').value = newBalance.toFixed(2);
  }
}

async function addNewPayment(e) {
  e.preventDefault();

  showPageLoading();

  const form = document.getElementById(this.id);
  const parameters = new FormData(form);

  parameters.append('action', 'add_new_payment');

  const response = await fetchData({
    place: 'upcoming_event_payments',
    data: parameters
  });

  hidePageLoading();

  if (response.message) showBigAlert({
    title: response.title,
    subtitle: response.message
  });

  if (response.status === 'success') loadUpcomingEventPayments();
}

async function editPayment(e) {
  e.preventDefault();

  showPageLoading();

  const form = document.getElementById(this.id);
  const parameters = new FormData(form);

  parameters.append('action', 'edit_payment');

  const response = await fetchData({
    place: 'upcoming_event_payments',
    data: parameters
  });

  hidePageLoading();

  if (response.message) showBigAlert({
    title: response.title,
    subtitle: response.message
  });

  if (response.status === 'success') {
    loadUpcomingEventPayments();
    $('#modal-edit-payment').modal('hide');
  }
}

async function deletePayment() {
  const alertResponse = await showSweetConfirm({
    title: '¡Cuidado!',
    subtitle: '¿Realmente quiere eliminar este pago?'
  });

  if (!alertResponse) return;

  showPageLoading();

  const data = JSON.parse(atob(this.getAttribute('data-payment')));
  const reservationPaymentId = data.idReservacionPago;

  const parameters = new FormData();

  parameters.append('action', 'delete_payment');
  parameters.append('reservationPaymentId', reservationPaymentId);

  const response = await fetchData({
    place: 'upcoming_event_payments',
    data: parameters
  });

  hidePageLoading();

  if (response.message) showSweetAlert({
    icon: response.status,
    title: response.message
  });

  if (response.status === 'success') loadUpcomingEventPayments();
}

function handleEditPayment() {
  const data = JSON.parse(atob(this.getAttribute('data-payment')));

  document.getElementById('edit-payment').value = data.Pago;
  document.getElementById('edit-initialPayment').value = data.Pago;
  document.getElementById('edit-comments').value = data.Comentarios;
  document.getElementById('edit-newBalance').value = '';
  document.getElementById('reservationPaymentId').value = data.idReservacionPago;
  document.getElementById('edit-date').value = data.FechaFormat;

  //$("#edit-date").datepicker("setDate", data.FechaFormat);
}

// Event listeners
const payment = document.getElementById('payment');
payment.addEventListener('keyup', calcNewBalance);

const editInputPayment = document.getElementById('edit-payment');
editInputPayment.addEventListener('keyup', calcEditBalance);

const addPaymentForm = document.getElementById('add-payment-form');
addPaymentForm.addEventListener('submit', addNewPayment);

const editPaymentForm = document.getElementById('edit-payment-form');
editPaymentForm.addEventListener('submit', editPayment);

$(document).on('click', '.btn-delete-payment', deletePayment);
$(document).on('click', '.btn-edit-payment', handleEditPayment);

/* $("#search-date").datepicker({
  onSelect: loadUpcomingEventPayments
}); */

loadUpcomingEventPayments();