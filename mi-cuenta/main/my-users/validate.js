$.validator.setDefaults({
  submitHandler: function () {
    sendUserData();
  }
});

$('#users-form').validate({
  rules: {
    user: {
      required: true
    },
    email: {
      required: true,
      email: true
    },
    cellPhone: {
      required: true
    },
    level: {
      required: true
    },
    username: {
      required: true
    },
    password: {
      required: true,
      minlength: 8
    }
  },
  messages: {
    user: {
      required: 'Este cámpo es requerido.'
    },
    email: {
      required: 'Este cámpo es requerido.',
      email: "Ingrese un corréo valido."
    },
    cellPhone: {
      required: 'Este cámpo es requerido.'
    },
    level: {
      required: 'Este cámpo es requerido.'
    },
    username: {
      required: 'Este cámpo es requerido.'
    },
    password: {
      required: 'Este cámpo es requerido.',
      minlength: 'Se requiere minimo 8 caracteres'
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