$.validator.setDefaults({
    submitHandler: function () {
        sendEventTypesData();
    }
});

$('#event-types-form').validate({
    rules: {
        ignore: [],
        eventType: {
            required: true
        }
    },
    messages: {
        eventType: {
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