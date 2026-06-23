$.validator.setDefaults({
  submitHandler: function () {
    showPageLoading();

    const form = '#recover-credentials-form';
    const data = new FormData($(form)[0]);

    fetchData({ place: 'authentication', data }).then(response => {
      if (!response) {
        hidePageLoading();
      }

      if (response) {
        hidePageLoading();

        showBigAlert({
          icon: response.state,
          title: response.title,
          subtitle: response.message
        }).then(() => {
          if (response.state === 'success') {
            window.location.href = 'login';
          }
        });
      }
    });
  }
});

$('#recover-credentials-form').validate({
  rules: {
    ignore: [],
    userEmail: {
      required: true,
      email: true
    }
  },
  messages: {
    userEmail: {
      required: 'Ingrese su correo o su username.',
      email: 'Ingresa un correo válido'
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