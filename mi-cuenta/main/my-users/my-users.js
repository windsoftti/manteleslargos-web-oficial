$(document).ready(function () {
  loadUsers();
});

function searchUsers() {
  useSearch(loadUsers);
}

async function loadUsers(page = 1) {
  showPageLoading();
  const searchByUser = $('#search-by-user').val();
  const perPage = 15;

  const dataSend = new FormData();

  dataSend.append('page', page);
  dataSend.append('perPage', perPage);
  dataSend.append('searchByUser', searchByUser);
  dataSend.append('action', 'list_users');

  const response = await fetchData({
    place: 'my_users',
    data: dataSend
  });

  console.log(response)

  if (response.content) {
    const usersTable = decodeURIComponent(escape(atob(response.content)));
    $('#list-users').html(usersTable).show('slow');
  }

  hidePageLoading();
}

async function sendUserData() {
  showPageLoading();
  const form = '#users-form';

  const check = await checkInputValidate(form);

  if (!check) {
    hidePageLoading();
    return;
  }

  const dataSend = new FormData($(form)[0]);

  const response = await fetchData({
    place: 'my_users',
    data: dataSend
  });

  if (response) {
    showBigAlert({
      icon: response.state,
      title: response.title,
      subtitle: response.message
    });

    if (response.state === 'success') {
      closeModal('modal-add-edit-user');
      resetForm(form);
      loadUsers();
    }
  }

  hidePageLoading();
}

async function loadPermissions(userId) {
  showPageLoading();

  const parameters = new FormData();

  parameters.append('action', 'list_permissions');
  parameters.append('userId', userId);

  const response = await fetchData({
    place: 'my_users',
    data: parameters
  });

  if (response.content) {
    const content = decodeURIComponent(escape(atob(response.content)));
    $('#list-permissions').html(content);
  }

  hidePageLoading();
}

$(document).on('click', '.btn-delete-user', async function () {
  const userId = $(this).attr('data-userId');
  const user = $(this).attr('data-user');

  const alertResponse = await showSweetConfirm({
    title: '!Cuidado!',
    subtitle: `¿Realmente desea eliminar a "${user}"?`
  });

  if (!alertResponse) return;

  showPageLoading();

  const dataSend = new FormData();

  dataSend.append('userId', userId);
  dataSend.append('user', user);
  dataSend.append('action', 'delete_user');

  const response = await fetchData({
    place: 'my_users',
    data: dataSend
  });

  if (response) {
    showSweetAlert({
      icon: response.state,
      title: response.title
    });

    if (response.state === 'success') {
      loadUsers();
    }
  }

  hidePageLoading();
});

$(document).on('click', '.btn-send-credentials', async function () {
  const user = $(this).attr('data-user');
  const email = $(this).attr('data-email');

  const alertResponse = await showSweetConfirm({
    icon: 'info',
    title: 'Enviar credenciales',
    subtitle: `¿Realmente desea enviar las credenciales de acceso a "${user}"?`
  });

  if (!alertResponse) return;

  showPageLoading();

  const dataSend = new FormData();

  dataSend.append('user', user);
  dataSend.append('email', email);
  dataSend.append('action', 'send_credentials');

  const response = await fetchData({
    place: 'my_users',
    data: dataSend
  });

  if (response) {
    showSweetAlert({
      icon: response.state,
      title: response.title
    });
  }

  hidePageLoading();
});

$('.btn-add-user').on('click', function () {
  changeModalTitle('Agregar usuario');
  resetForm('#users-form');
  hideInputWarnings();
  $('#credentials').show();
  $('#action-users').val('add_user');
});

$('#addon-password').on('click', function () {
  const input = document.getElementById("password");

  if (input.type === 'password') {
    input.type = 'text';
    $('#icon-password').removeClass('fa-eye-slash');
    $('#icon-password').addClass('fa-eye');
  } else if (input.type === 'text') {
    input.type = 'password';
    $('#icon-password').removeClass('fa-eye');
    $('#icon-password').addClass('fa-eye-slash');
  }
});

$('#permissions-form').on('submit', async function (e) {
  e.preventDefault();
  showPageLoading();

  const parameters = new FormData($(this)[0]);

  const response = await fetchData({
    place: 'my_users',
    data: parameters
  });

  hidePageLoading();

  if (response) {
    showSweetAlert({
      icon: response.state,
      title: response.title
    });

    if (response.state === 'success') {
      closeModal('modal-permissions');
      loadUsers();
    }
  }
});