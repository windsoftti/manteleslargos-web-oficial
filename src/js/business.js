//$(window).on('load', () => initBusinessesMap('.business-section'));
const calendarStatus = $('#calendarStatus').val();

const calendar = new Calendar({
  id: 'calendar',
  disabled: calendarStatus === 'disabled' ? true : false,
  dateStatus: businessDates,
  //onChangeYear: year => alert(year),
  onPressDate: data => {
    if (!data.dateStatus || data.dateStatus === 'free') onPressDay(data.dateWithFormat);

    if (data.dateStatus === 'with-spaces' && data.hours) onPressWithSpacesDay(data);
    if (data.dateStatus === 'with-spaces' && !data.hours) onPressDay(data.dateWithFormat);

    if (data.dateStatus === 'occupied') showSweetToast({
      icon: 'warning',
      message: 'El día no está disponible'
    });
  }
});

calendar.createCalendar();

const onPressWithSpacesDay = data => {
  const date = data.dateWithFormat;
  $('#quote-requestedDate').val(date);
  $('#busy-hours').html(data.hours);

  handleToggleOuterTab('tab-busy-hours');
  handleOpenModal('modal-request-quote');
}

const onPressDay = (date, packageId = null) => {
  resetForm('#request-quote-form');
  handleToggleOuterTab('tab-quote-login');
  handleOpenModal('modal-request-quote');
  $('#quote-requestedDate').val(date);

  if (packageId) {
    //$('#quote-package').select2('val', packageId);
    $('#quote-package').val(packageId);

    useLoadSelect({
      select: '#quote-eventType',
      action: 'business_package_event_types',
      data: packageId
    });
  }
}

async function requestInfo(e) {
  e.preventDefault();

  showPageLoading();

  const parameters = new FormData($(this)[0]);
  parameters.append('action', 'request_info');

  const response = await fetchData({
    place: 'business',
    parameters
  });

  hidePageLoading();

  if (response.message) showSweetToast({
    id: '#direct-contact-alert',
    icon: response.status,
    message: response.message
  });

  if (response.status === 'success') resetForm('#direct-contact-form');
}

async function requestQuote(e) {
  e.preventDefault();

  showPageLoading();

  const parameters = new FormData($(this)[0]);
  parameters.append('action', 'request_quote');

  const response = await fetchData({
    place: 'business',
    parameters
  });

  hidePageLoading();

  if (response.message) showSweetToast({
    id: '#request-quote-alert',
    icon: response.status,
    message: response.message
  });

  if (response.status === 'success') {
    resetForm('#request-quote-form');
    closeModal();
  };
}

function getBusinessPackageEventTypes() {
  const packageId = $(this).val();

  if (!packageId) return;

  useLoadSelect({
    select: '#quote-eventType',
    action: 'business_package_event_types',
    data: packageId
  });
}

async function quoteLogIn(e) {
  e.preventDefault();

  showPageLoading();

  const parameters = new FormData($(this)[0]);
  parameters.append('action', 'logIn');

  const response = await fetchData({
    place: 'authentication',
    parameters
  });

  hidePageLoading();

  if (response.message) showSweetToast({
    id: '#quote-login-alert',
    icon: response.status,
    message: response.message
  });

  if (response.status === 'success') {
    $('.for-hide').remove();
    $('#tab-quote-request-quote').attr('id', 'tab-quote-login');
    handleToggleOuterTab('tab-quote-login');

    $('#quote-fullName').val(response.fullName);
    $('#quote-email').val(response.email);
    $('#quote-phone').val(response.phone);
  }
}

async function quoteSignUp(e) {
  e.preventDefault();

  showPageLoading();

  const parameters = new FormData($(this)[0]);
  parameters.append('action', 'quote-signUp');

  const response = await fetchData({
    place: 'authentication',
    parameters
  });

  hidePageLoading();

  if (response.message && response.status !== 'success') showSweetToast({
    id: '#quote-signup-alert',
    icon: response.status,
    message: response.message
  });

  if (response.status === 'success') showSweetAlert({
    title: response.title,
    message: response.message
  }).then(() => {
    resetForm('#quote-signup-form');
    $('.for-hide').remove();
    $('#tab-quote-request-quote').attr('id', 'tab-quote-login');
    handleToggleOuterTab('tab-quote-login');

    $('#quote-fullName').val(response.fullName);
    $('#quote-email').val(response.email);
    $('#quote-phone').val(response.phone);
  });
}

function handleRequestPackage() {
  const packageId = $(this).attr('data-packageId');

  const dateData = new Date();
  const day = dateData.getDate();
  const month = dateData.getMonth() + 1;
  const year = dateData.getFullYear();

  const dayParsed = day < 10 ? `0${day}` : day;
  const dayMonth = month < 10 ? `0${month}` : month;

  const date = `${dayParsed}/${dayMonth}/${year}`;
  onPressDay(date, packageId);
}

$('#direct-contact-form').on('submit', requestInfo);

$('#request-quote-form').on('submit', requestQuote);
$('#quote-package').on('change', getBusinessPackageEventTypes);

$('#quote-login-form').on('submit', quoteLogIn);
$('#quote-signup-form').on('submit', quoteSignUp);

$('.btn-request-package').on('click', handleRequestPackage);

initNumberInput();

/* cscCreateCalendar({
  locale: 'es',
  handleAdd: onPressDay
}).then(() => {
  cscAddDateStatus(businessDates);
}); */


function addEventCounter() {
  const event = $(this).attr('data-event');

  callEndpoint({
    showLoading: false,
    place: 'business',
    parameters: {
      action: 'add_counter',
      event,
      businessId: $('input[name="businessId"]').val()
    }
  });
}

$('.event-counter').on('click', addEventCounter);