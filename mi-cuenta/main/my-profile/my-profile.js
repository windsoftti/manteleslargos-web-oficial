function saveData(e) {
  e.preventDefault();

  const changePassword = $('#changePassword').is(':checked');

  if (changePassword) {
    const newPassword = $('#userPassword').val();
    const confirmPassword = $('#userConfirmPassword').val();

    if (!newPassword) {
      showBigAlert({
        title: '¡Contraseña vacía!',
        subtitle: 'Escribe tu contraseña'
      });

      return;
    }

    if (newPassword.length < 8) {
      showBigAlert({
        title: '¡Contraseña no válida!',
        subtitle: 'La contraseña debe de tener mínimo 8 caracteres'
      });

      return;
    }

    if (newPassword != confirmPassword) {
      showBigAlert({
        title: '¡Error de confimación!',
        subtitle: 'Las contraseñas no coinciden'
      });

      return;
    }
  }

  showPageLoading();

  const dataSend = new FormData($(this)[0]);
  dataSend.append('action', 'update_my_profile');

  fetchData({
    place: 'authentication',
    data: dataSend
  }).then(response => {
    if (response == 'Error del servidor') {
      alert('¡Error!, Intentelo nuevamente.');
      hidePageLoading();
    }

    if (response) {
      hidePageLoading()

      showBigAlert({
        icon: response.state,
        title: response.title,
        subtitle: response.message
      }).then(() => {
        if (response.state == 'success') window.location.reload()
      });
    }
  });
}

$('#register-form').on('submit', saveData);