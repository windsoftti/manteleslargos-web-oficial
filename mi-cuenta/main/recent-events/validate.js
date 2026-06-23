$.validator.setDefaults({
  submitHandler: function () {
    sendRecentEventData();
  }
});

$('#recent-events-form').validate({
  rules: {
    ignore: [],
    recentEvent: {
      required: true
    },
    businessId: {
      required: true
    },
    shortDescription: {
      required: true
    }
  },
  messages: {
    recentEvent: {
      required: 'Este dato es requerido.'
    },
    businessId: {
      required: 'Este dato es requerido.'
    },
    shortDescription: {
      required: 'Este dato es requerido.'
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