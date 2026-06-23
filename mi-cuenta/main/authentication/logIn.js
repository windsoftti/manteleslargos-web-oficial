$.validator.setDefaults({
  submitHandler: function () {
    showPageLoading();

    const form = '#login-form';
    const data = new FormData($(form)[0]);

    fetchData({ place: 'authentication', data }).then(response => {
      if (!response) {
        hidePageLoading();
      }

      if (response) {
        if (response.state !== 'success') {
          showBigAlert({
            icon: response.state,
            title: response.title,
            subtitle: response.message
          });
        }

        if (response.state === 'success') {
          window.location.href = 'index';
        }

        if (response.state !== 'success') {
          hidePageLoading();
        }
      }
    });
  }
});

$('#login-form').validate({
  rules: {
    ignore: [],
    userEmail: {
      required: true
    },
    userPassword: {
      required: true
    }
  },
  messages: {
    userEmail: {
      required: 'Ingrese su correo o su username.'
    },
    userPassword: {
      required: 'Ingrese su contraseña.'
    }
  },
  errorElement: 'span',
  errorPlacement: function (error, element) {
    error.addClass('invalid-feedback');
    element.closest('.form-group-sm').append(error);
    element.closest('.form-group').append(error);
  },
  highlight: function (element, errorClass, validClass) {
    $(element).addClass('is-invalid');
  },
  unhighlight: function (element, errorClass, validClass) {
    $(element).removeClass('is-invalid');
  }
});