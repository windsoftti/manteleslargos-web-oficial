$(initFunctions);

function initFunctions() {
  loadUsers();

  initSearchForm(loadUsers);

  initShowHidePassword();

  $initValidator({
    formId: 'users-form',
    onValidate: sendUserData
  });
}

const loadUsers = page => useLoadTable({
  page,
  place: 'users',
  action: 'list_users'
});

async function sendUserData(form) {
  showPageLoading();

  const parameters = new FormData(form);

  const response = await fetchData({
    place: 'users',
    parameters
  });

  hidePageLoading();

  if (response.message) showSweetAlert({
    title: response.title,
    message: response.message
  });

  if (response.status == 'success') {
    closeModal('modal-users');
    resetForm('#users-form');
    loadUsers();
  }
}

async function sendCretentials() {
  const data = JSON.parse(atob($(this).attr('data-user')));

  const user = data.FullName;
  const email = data.Email;

  const alertResponse = await showSweetConfirm({
    title: 'Enviar credenciales',
    message: `¿Realmente desea enviar las credenciales de acceso a "${user}"?`
  });

  if (!alertResponse) return;

  showPageLoading();

  const parameters = new FormData();

  parameters.append('user', user);
  parameters.append('email', email);
  parameters.append('action', 'send_credentials');

  const response = await fetchData({
    place: 'users',
    parameters
  });

  hidePageLoading();

  if (response.message) showSweetToast({
    icon: response.status,
    message: response.message
  });
}

async function deleteUser() {
  const data = JSON.parse(atob($(this).attr('data-user')));

  const userId = data.UserId;
  const user = data.FullName;

  const alertResponse = await showSweetConfirm({
    message: `¿Realmente desea eliminar a "${user}"?`
  });

  if (!alertResponse) return;

  showPageLoading();

  const parameters = new FormData();

  parameters.append('userId', userId);
  parameters.append('user', user);
  parameters.append('action', 'delete_user');

  const response = await fetchData({
    place: 'users',
    parameters
  });

  hidePageLoading();

  if (response.message) showSweetToast({
    icon: response.status,
    message: response.message
  });

  if (response.status === 'success') loadUsers();
}

function handleAddUser() {
  changeModalTitle('Agregar usuario');
  hideInputWarnings();
  resetForm('#users-form');

  $('#credentials').show();
  $('#action-users').val('add_user');
}

function handleEditUser() {
  const data = JSON.parse(atob($(this).attr('data-user')));

  const password = $(this).attr('data-password');

  changeModalTitle('Editar usuario');
  hideInputWarnings();

  $('#userId').val(data.UserId);
  $('#fullName').val(data.FullName);
  $('#email').val(data.Email);
  $('#phone').val(data.Phone);
  $('#username').val(data.Username);
  $('#userType').val(data.UserType);
  $('#action-users').val('edit_user');
  $('#password').val(password);
  //$('#credentials').hide();
}

$('.btn-add-user').on('click', handleAddUser);
$(document).on('click', '.btn-edit-user', handleEditUser);
$(document).on('click', '.btn-send-credentials', sendCretentials);
$(document).on('click', '.btn-delete-user', deleteUser);