async function logIn(form) {
  showPageLoading();

  const parameters = new FormData(form);

  const response = await fetchData({
    place: 'authentication',
    parameters
  });

  if (response.status !== 'success') hidePageLoading();

  if (response.message) showSweetAlert({
    title: response.title,
    message: response.message
  });

  if (response.status == 'success') window.location = 'dashboard';
}

$initValidator({
  formId: 'login-form',
  onValidate: logIn
});