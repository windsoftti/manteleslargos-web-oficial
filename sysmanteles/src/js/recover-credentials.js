async function recoverCredentials(form) {
  showPageLoading();

  const parameters = new FormData(form);

  const response = await fetchData({
    place: 'authentication',
    parameters
  });

  hidePageLoading();

  if (response.message) showSweetAlert({
    title: response.title,
    message: response.message
  }).then(() => {
    if (response.status == 'success') window.location = 'login';
  });
}

$initValidator({
  formId: 'recover-credentials-form',
  onValidate: recoverCredentials
});