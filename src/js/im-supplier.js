async function supplierLogin(e) {
  e.preventDefault();

  showPageLoading();

  const parameters = new FormData($(this)[0]);
  parameters.append('action', 'logIn');

  const response = await fetchData({
    place: 'supplier_authentication',
    parameters
  });

  hidePageLoading();

  if (response.message) showSweetToast({
    icon: response.status,
    message: response.message
  });

  if (response.status === 'success') {
    resetForm('#supplier-login-form');
    showPageLoading();
    location.reload();
  }
}

async function supplierSignUp(e) {
  e.preventDefault();

  showPageLoading();

  const parameters = new FormData($(this)[0]);
  parameters.append('action', 'signUp');

  const response = await fetchData({
    place: 'supplier_authentication',
    parameters
  });

  hidePageLoading();

  /* if (response.message) showPageAlert({
    id: '#supplier-signup-alert',
    status: response.status,
    message: response.message
  }); */

  if (response.message && response.status !== 'success') showSweetToast({
    icon: response.status,
    message: response.message
  });

  if (response.status === 'success') showSweetAlert({
    title: response.title,
    message: response.message,
    confirmButtonText: 'Continuar'
  }).then(() => {
    resetForm('#supplier-signup-form');
    showPageLoading();
    //location.href = `${BASE_URL}/soy-proveedor`;
    location.href = `${BASE_URL}/verificar-cuenta-proveedor`;
  });

  /* if (response.status === 'success') {
    resetForm('#supplier-signup-form');

    setTimeout(() => {
      showPageLoading();
      location.href = `${BASE_URL}/soy-proveedor`;
      clearTimeout();
    }, 5000);
  } */
}

async function supplierRecoverPassword(e) {
  e.preventDefault();

  showPageLoading();

  const parameters = new FormData($(this)[0]);
  parameters.append('action', 'recover_password');

  const response = await fetchData({
    place: 'supplier_authentication',
    parameters
  });

  hidePageLoading();

  /* if (response.message) showPageAlert({
    id: '#supplier-recover-password-alert',
    status: response.status,
    message: response.message,
    timeToClose: 6000
  });

  if (response.status === 'success') {
    resetForm('#supplier-recover-password-form');

    setTimeout(() => {
      showPageLoading();
      location.href = `${BASE_URL}/soy-proveedor`;
      clearTimeout();
    }, 6000);
  } */

  if (response.message && response.status !== 'success') showSweetToast({
    icon: response.status,
    message: response.message
  });

  if (response.status === 'success') showSweetAlert({
    title: response.title,
    message: response.message
  }).then(() => {
    resetForm('#supplier-recover-password-form');
    showPageLoading();
    location.href = `${BASE_URL}/soy-proveedor`;
  });
}

async function supplierVerifyAccount(e) {
  e.preventDefault();

  showPageLoading();

  const parameters = new FormData($(this)[0]);
  parameters.append('action', 'verify_account');

  const response = await fetchData({
    place: 'supplier_authentication',
    parameters
  });

  hidePageLoading();

  if (response.message) showSweetToast({
    icon: response.status,
    message: response.message
  });

  if (response.status === 'success') {
    resetForm('#supplier-verify-acccount-form');
    //location.reload();
    location.href = `${BASE_URL}/agregar-negocio`;
  }
}

async function supplierResendCode(e) {
  e.preventDefault();

  showPageLoading();

  const parameters = new FormData();

  parameters.append(
    'action',
    'resend_verification_code'
  );

  const response = await fetchData({
    place: 'supplier_authentication',
    parameters
  });

  hidePageLoading();

  if (response.message) {
    showSweetToast({
      icon: response.status,
      message: response.message
    });
  }

  if (response.status === 'success') {
    startResendCountdown(60);
  }
}

function startResendCountdown(seconds) {

  const timerContainer =
    document.getElementById('resend-timer');

  const countdown =
    document.getElementById('countdown');

  const resendLink =
    document.getElementById('resend-code-link');

  resendLink.style.display = 'none';

  timerContainer.style.display = 'block';

  countdown.textContent = seconds;

  const interval = setInterval(() => {

    seconds--;

    countdown.textContent = seconds;

    if (seconds <= 0) {

      clearInterval(interval);

      timerContainer.style.display = 'none';

      resendLink.style.display = 'inline-block';
    }

  }, 1000);
}

document.addEventListener('DOMContentLoaded', () => {

  const timer =
    document.getElementById('resend-timer');

  if (!timer) {
    return;
  }

  const seconds = parseInt(
    timer.dataset.seconds || 0
  );

  if (seconds > 0) {
    startResendCountdown(seconds);
  }
});

$('#supplier-login-form').on('submit', supplierLogin);
$('#supplier-signup-form').on('submit', supplierSignUp);
$('#supplier-recover-password-form').on('submit', supplierRecoverPassword);
$('#supplier-verify-account-form').on('submit', supplierVerifyAccount);

$('#resend-code-link').on(
  'click',
  supplierResendCode
);