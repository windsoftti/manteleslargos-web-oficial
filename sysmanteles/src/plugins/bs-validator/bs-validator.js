const $validatorLabels = `
  <div class="invalid-feedback">
    Este campo es requerido
  </div>
`;

function $initValidator({
  formId,
  onValidate
}) {
  $(`#${formId} :input`).each(function () {
    const input = $(this);

    if (input.next('.input-group-append').length) {
      input.next().after($validatorLabels);
    } else {
      input.after($validatorLabels);
    }
  });

  $(`#${formId}`).submit(function (event) {
    event.preventDefault();
    if (!$(`#${formId}`)[0].checkValidity()) event.stopPropagation();

    if ($(`#${formId}`)[0].checkValidity()) !!onValidate && onValidate($(this)[0]);

    $(`#${formId}`).addClass('was-validated');
  });
}