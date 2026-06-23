async function navbarLogIn(e) {
  e.preventDefault();

  showPageLoading();

  const parameters = new FormData($(this)[0]);
  parameters.append('action', 'logIn');

  const response = await fetchData({
    place: 'authentication',
    parameters
  });

  hidePageLoading();

  /* if (response.message) showPageAlert({
    id: '#navbar-login-alert',
    status: response.status,
    message: response.message
  }); */

  if (response.message) showSweetToast({
    icon: response.status,
    message: response.message
  });

  if (response.status === 'success') {
    resetForm('#navbar-login-form');
    showPageLoading();
    location.reload();
  }
}

async function navbarSignUp(e) {
  e.preventDefault();

  showPageLoading();

  const parameters = new FormData($(this)[0]);
  parameters.append('action', 'signUp');

  const response = await fetchData({
    place: 'authentication',
    parameters
  });

  hidePageLoading();

  if (response.message && response.status !== 'success') showSweetToast({
    icon: response.status,
    message: response.message
  });

  /* if (response.message) showPageAlert({
    id: '#navbar-signup-alert-top',
    status: response.status,
    message: response.message,
    timeToClose: 5000
  }); */

  if (response.status === 'success') showSweetAlert({
    title: response.title,
    message: response.message
  }).then(() => {
    resetForm('#navbar-signup-form');
    handleToggleOuterTab('tab-login');
  });
}

async function navbarRecoverPassword(e) {
  e.preventDefault();

  showPageLoading();

  const parameters = new FormData($(this)[0]);
  parameters.append('action', 'recover_password');

  const response = await fetchData({
    place: 'authentication',
    parameters
  });

  hidePageLoading();

  /* if (response.message) showPageAlert({
    id: '#navbar-recover-password-alert',
    status: response.status,
    message: response.message,
    timeToClose: 5000
  }); */

  if (response.message && response.status !== 'success') showSweetToast({
    icon: response.status,
    message: response.message
  });

  if (response.status === 'success') showSweetAlert({
    title: response.title,
    message: response.message
  }).then(() => {
    resetForm('#navbar-recover-password-form');
    handleToggleOuterTab('tab-login');
  });
}

async function invitationUpdateAccount(e) {
  e.preventDefault();

  showPageLoading();

  const parameters = new FormData($(this)[0]);
  parameters.append('action', 'update-account');

  const response = await fetchData({
    place: 'my_account_configuration',
    parameters
  });

  hidePageLoading();

  if (response.message) showPageAlert({
    id: '#my-account-alert',
    status: response.status,
    message: response.message
  });

  if (response.status === 'success') setTimeout(() => {
    showPageLoading();
    location.reload();
    clearTimeout();
  }, 3000);
}

$('#navbar-login-form').on('submit', navbarLogIn);
$('#navbar-signup-form').on('submit', navbarSignUp);
$('#navbar-recover-password-form').on('submit', navbarRecoverPassword);
$('#my-account-form').on('submit', invitationUpdateAccount);