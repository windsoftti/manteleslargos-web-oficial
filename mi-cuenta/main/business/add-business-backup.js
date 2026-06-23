let tipoDeNegocio = '';

$(document).ready(function () {
  $("#form-tab-panel").bind("keypress", function (e) {
    if (e.keyCode == 13) {
      return false;
    }
  });
});

const btnNextTabSalon = `  
    <button type="button" class="btn btn-lg btn-primary next-button mb-3" onclick="showServicios()">Continuar
      <span class="d-inline-block ml-2 fs-16"><i class="fal fa-long-arrow-right"></i></span>
    </button>
`;
const btnBackTabSalon = `
    <a href="javascript:void(0)" class="btn btn-lg bg-hover-white border rounded-lg mb-3 mr-auto" onclick="goBackSA()">
      <span class="d-inline-block text-primary mr-2 fs-16"><i class="fal fa-long-arrow-left"></i></span>Regresar
    </a>
`;

const btnNextTabNoSalon = `  
    <button type="button" class="btn btn-lg btn-primary next-button mb-3" onclick="showGaleria()">Continuar
      <span class="d-inline-block ml-2 fs-16"><i class="fal fa-long-arrow-right"></i></span>
    </button>
`;
const btnBackTabNoSalon = `
    <a href="javascript:void(0)" class="btn btn-lg bg-hover-white border rounded-lg mb-3 mr-auto" onclick="goBack()">
      <span class="d-inline-block text-primary mr-2 fs-16"><i class="fal fa-long-arrow-left"></i></span>Regresar
    </a>
`;

function goBack(e) {
  $('[href="#ubicacion-negocio"]').tab('show');
  $("html, body").animate({ scrollTop: 0 }, "slow");
  //e.preventDefault()
}

function goBackSA(e) {
  $('[href="#servicios-amenidades"]').tab('show');
  $("html, body").animate({ scrollTop: 0 }, "slow");
  //e.preventDefault()
}

function showGaleria(e) {
  $('[href="#galeria-fotos"]').tab('show');
  $("html, body").animate({ scrollTop: 0 }, "slow");
  //e.preventDefault()
}

function showServicios(e) {
  $('[href="#servicios-amenidades"]').tab('show');
  $("html, body").animate({ scrollTop: 0 }, "slow");
  //e.preventDefault()
}

function showUbicacion(e) {
  $('[href="#ubicacion-negocio"]').tab('show');
  $("html, body").animate({ scrollTop: 0 }, "slow");
  //e.preventDefault()
}

let descripcionEditor;

ClassicEditor.create(document.querySelector('#descripcion')).then(editor => {
  descripcionEditor = editor;
}).catch(error => {
  console.error(error);
});

$('.next-button').on('click', function () {
  $("html, body").animate({ scrollTop: 0 }, "slow");
});

$('.prev-button').on('click', function () {
  $("html, body").animate({ scrollTop: 0 }, "slow");
});

$('#perteneceATuxtla').on('change', function () {
  if ($(this).val() === 'Si') {
    $('.municipio').hide();
    showTuxtlaInMap();
  } else {
    $('.municipio').show();
  }
});

$('.tipoProveedor').on('change', async function () {
  showPageLoading();
  const tipoProveedor = $(this).val();

  const dataSend = new FormData();

  dataSend.append('tipoProveedor', tipoProveedor);
  dataSend.append('action', 'verificar_tipo_proveedor');

  const response = await fetchData({
    place: 'business',
    data: dataSend
  });

  console.log(response);

  if (response === 'Salon') {
    tipoDeNegocio = 'Salon';
    $('.tipo-salon').show();
    $('#btn-next-tab').html(btnNextTabSalon);
    $('#btn-back-tab').html(btnBackTabSalon);
  }

  if (response !== 'Salon') {
    tipoDeNegocio = '';
    $('.tipo-salon').hide();
    $('#btn-next-tab').html(btnNextTabNoSalon);
    $('#btn-back-tab').html(btnBackTabNoSalon);
  }
  hidePageLoading();
});

function showAlertLabel(title) {
  $('#tab-alert').html(title);
  $('#tab-alert').show();
}

function validateForm() {
  var formdata = $("#form-tab-panel").serializeArray();
  const descripcion = descripcionEditor.getData();

  var data = {};

  $(formdata).each(function (index, obj) {
    data[obj.name] = obj.value;
  });

  console.log(data);

  if (!data.tipoProveedor) {
    showAlertLabel('Seleccione el tipo de proveedor.');
    $('[href="#tipo-proveedor"]').tab('show');
    $("html, body").animate({ scrollTop: 0 }, "slow");
    return;
  }

  if (!data['tipoEvento[]']) {
    showAlertLabel('Seleccione el tipo de evento.');
    $('[href="#tipo-evento"]').tab('show');
    $("html, body").animate({ scrollTop: 0 }, "slow");
    return;
  }

  if (!data.negocio) {
    showAlertLabel('Ingrese el nombre del negocio.');
    $('[href="#negocio"]').tab('show');
    $("html, body").animate({ scrollTop: 0 }, "slow");
    return;
  }

  /* if (!data.userId) {
    showAlertLabel('Seleccione a que usuario pertenece e negocio.');
    $('[href="#negocio"]').tab('show');
    $("html, body").animate({ scrollTop: 0 }, "slow");
    return;
  } */

  if (!descripcion) {
    showAlertLabel('Ingrese la descripción del negocio.');
    $('[href="#negocio"]').tab('show');
    $("html, body").animate({ scrollTop: 0 }, "slow");
    return;
  }

  if (!data.celularNegocio) {
    showAlertLabel('Ingresa el número de Celular / Whatsapp de tu negocio.');
    $('[href="#negocio"]').tab('show');
    $("html, body").animate({ scrollTop: 0 }, "slow");
    return;
  }

  if (tipoDeNegocio == 'Salon') {
    /* if (!data.costo) {
      showAlertLabel('Ingrese el costo del negocio.');
      $('[href="#negocio"]').tab('show');
      $("html, body").animate({ scrollTop: 0 }, "slow");
      return;
    } */

    if (!data.capacidad) {
      showAlertLabel('Ingrese la capacidad del negocio.');
      $('[href="#negocio"]').tab('show');
      $("html, body").animate({ scrollTop: 0 }, "slow");
      return;
    }

    if (!data.capacidadMaxima) {
      showAlertLabel('Ingrese la capacidad maxima de su negocio.');
      $('[href="#negocio"]').tab('show');
      $("html, body").animate({ scrollTop: 0 }, "slow");
      return;
    }

    if (parseInt(data.capacidad) > parseInt(data.capacidadMaxima)) {
      showAlertLabel('La capacidad mínima no puede ser mayor que la capacidad máxima.');
      $('[href="#negocio"]').tab('show');
      $("html, body").animate({ scrollTop: 0 }, "slow");
      return;
    }

    if (!data.estado) {
      showAlertLabel('Seleccione su estado.');
      $('[href="#ubicacion-negocio"]').tab('show');
      $("html, body").animate({ scrollTop: 0 }, "slow");
      return;
    }

    if (!data.ciudad) {
      showAlertLabel('Ingrese su ciudad.');
      $('[href="#ubicacion-negocio"]').tab('show');
      $("html, body").animate({ scrollTop: 0 }, "slow");
      return;
    }

    /* if (!data.perteneceATuxtla) {
      showAlertLabel('Especifique si el negocio se encuentra en la ciudad de Tuxtla.');
      $('[href="#ubicacion-negocio"]').tab('show');
      $("html, body").animate({ scrollTop: 0 }, "slow");
      return;
    }

    if (data.perteneceATuxtla == 'No') {
      if (!data.idMunicipio) {
        showAlertLabel('Indique en que municipio esta su negocio.');
        $('[href="#ubicacion-negocio"]').tab('show');
        $("html, body").animate({ scrollTop: 0 }, "slow");
        return;
      }
    } */

    /* if (!data.orientacion) {
      showAlertLabel('Seleccione la orientación del negocio.');
      $('[href="#ubicacion-negocio"]').tab('show');
      $("html, body").animate({ scrollTop: 0 }, "slow");
      return;
    } */

    if (!data.latitud || !data.longitud) {
      showAlertLabel('Ubique su negocio en el mapa.');
      $('[href="#ubicacion-negocio"]').tab('show');
      $("html, body").animate({ scrollTop: 0 }, "slow");
      return;
    }

    if (!data.direccion) {
      showAlertLabel('Ingrese la dirección de su negocio.');
      $('[href="#ubicacion-negocio"]').tab('show');
      $("html, body").animate({ scrollTop: 0 }, "slow");
      return;
    }

    /* if (!data['servicios[]']) {
      showAlertLabel('Seleccione los servicios de su negocio.');
      $('[href="#servicios-amenidades"]').tab('show');
      $("html, body").animate({ scrollTop: 0 }, "slow");
      return;
    }

    if (!data['amenidades[]']) {
      showAlertLabel('Seleccione las amenidades de su negocio.');
      $('[href="#servicios-amenidades"]').tab('show');
      $("html, body").animate({ scrollTop: 0 }, "slow");
      return;
    } */
  }

  for (let index = 0; index < packageEditorCount; index++) {
    const element = packageEditors[`editor-${index}`];

    console.log($(`#tipoEventoPaquete${index}`).val());

    if (element != null) {
      if ($(`#nombrePaquete${index}`).val() == '') {
        showAlertLabel(`Agergué el nombre del paquete ${index + 1}.`);
        $('[href="#paquetes"]').tab('show');
        $("html, body").animate({ scrollTop: 0 }, "slow");
        return;
      }

      if ($(`#orientacionPaquete${index}`).val() == '') {
        showAlertLabel(`Seleccione la orientación de su paquete ${index + 1}.`);
        $('[href="#paquetes"]').tab('show');
        $("html, body").animate({ scrollTop: 0 }, "slow");
        return;
      }

      if ($(`#precioPaquete${index}`).val() == '') {
        showAlertLabel(`Agergué el precio del paquete ${index + 1}.`);
        $('[href="#paquetes"]').tab('show');
        $("html, body").animate({ scrollTop: 0 }, "slow");
        return;
      }

      const descripcionPaquete = packageEditors[`editor-${index}`].getData();

      if (!descripcionPaquete) {
        showAlertLabel(`Agergué la descripción del paquete ${index + 1}.`);
        $('[href="#paquetes"]').tab('show');
        $("html, body").animate({ scrollTop: 0 }, "slow");
        return;
      }

      if ($(`#tipoEventoPaquete${index}`).val().length == 0) {
        showAlertLabel(`Seleccione los tipos de eventos que pertenezcan al paquete ${index + 1}.`);
        $('[href="#paquetes"]').tab('show');
        $("html, body").animate({ scrollTop: 0 }, "slow");
        return;
      }
    }
  }

  $('#tab-alert').hide();

  showPageLoading();

  const dataSend = new FormData($('#form-tab-panel')[0]);

  console.log($imageFile['imagen-salon']);

  //dataSend.append('action', 'add_business');
  dataSend.append('descripcion', descripcion);

  if ($imageFile['imagen-salon']) {
    dataSend.append('ImagenSalon', $imageFile['imagen-salon'], $imageName['imagen-salon']);
  }

  for (let index = 0; index < packageEditorCount; index++) {
    const element = packageEditors[`editor-${index}`];

    if (element != null) {
      const descripcionPaquete = packageEditors[`editor-${index}`].getData();
      dataSend.append('descripcionPaquete[]', descripcionPaquete)
    }
  }

  fetchData({
    place: 'business',
    data: dataSend
  }).then(function (resData) {
    console.log(resData);
    hidePageLoading();
    showBigAlert({
      icon: resData.state,
      title: resData.title,
      subtitle: resData.message
    }).then(() => {
      if (resData.state === 'success') {
        window.location.href = "negocios";
      }
    });
  });
}

async function loadGaleryImages() {
  showPageLoading();

  const businessId = $('#idSalon').val();

  const dataSend = new FormData();

  dataSend.append('businessId', businessId);
  dataSend.append('action', 'list_images_galery');

  const response = await fetchData({
    customURL: 'data/business/business_galery_data.php',
    data: dataSend
  });

  const images = decodeURIComponent(escape(atob(response.content)));
  $('#listar-galeria-imagenes').html(images).show();

  hidePageLoading();
}

$(document).on('click', '.btn-delete-image', async function () {
  const fileId = $(this).attr('data-fileId');
  const file = $(this).attr('data-file');

  const alertResponse = confirm(`¡Cuidado!, ¿Realmente desea eliminar a "${file}"`);

  if (!alertResponse) return;

  showPageLoading();

  const dataSend = new FormData();

  dataSend.append('fileId', fileId);
  dataSend.append('file', file);
  dataSend.append('action', 'delete_image_galery');

  const response = await fetchData({
    customURL: 'data/business/business_galery_data.php',
    data: dataSend
  });

  if (response) {
    showSweetAlert({
      icon: response.state,
      title: response.title
    });

    if (response.state === 'success') {
      $(this).remove();
    }
  }

  hidePageLoading();
});

$(document).on('click', '.delete-principal-image', async function () {
  const alertResponse = confirm('¡Cuidado!, ¿Realmente desea eliminar la imagen principal?');

  if (alertResponse) {
    showPageLoading();

    const idSalon = $(this).attr('data-idFile');

    const dataSend = new FormData();

    dataSend.append('action', 'delete_principal_image');
    dataSend.append('idSalon', idSalon);

    const response = await fetchData({
      place: 'business',
      data: dataSend
    });

    if (response) {
      showSweetAlert({
        icon: response.state,
        title: response.title
      });

      if (response.state === 'success') {
        cleanPickerWithCropper('imagen-salon', 'imagen-salon', 'Agregar imagen');
      }
    }

    hidePageLoading();
  }
});

$(document).on('click', '.delete-tipo-evento', async function () {
  if (!$(this).is(':checked')) {
    const alertResponse = showSweetConfirm({
      title: '¡Cuidado!',
      subtitle: '¿Realmente desea eliminar este tipo de evento?'
    });

    if (alertResponse) {
      showPageLoading();
      const idTipoEvento = $(this).val();
      const idSalon = $('#idSalon').val();

      const dataSend = new FormData();

      dataSend.append('idTipoEvento', idTipoEvento);
      dataSend.append('idSalon', idSalon);
      dataSend.append('action', 'eliminar_tipo_evento');

      const response = await fetchData({
        place: 'business',
        data: dataSend
      });

      if (response) {
        showSweetAlert({
          icon: response.state,
          title: response.title
        });

        if (response.state == 'error') {
          $(this).trigger('click');
        }
      }
      hidePageLoading();
    } else {
      $(this).trigger('click');
    }
  }
});

$(document).on('click', '.delete-servicio', async function () {
  if (!$(this).is(':checked')) {
    const alertResponse = confirm('¡Cuidado!, ¿Realmente desea eliminar este servicio?');

    if (alertResponse) {
      showPageLoading();
      const idServicio = $(this).val();
      const idSalon = $('#idSalon').val();

      const dataSend = new FormData();

      dataSend.append('idServicio', idServicio);
      dataSend.append('idSalon', idSalon);
      dataSend.append('action', 'eliminar_servicio');

      const response = await fetchData({
        place: 'business',
        data: dataSend
      });

      if (response) {
        showSweetAlert({
          icon: response.state,
          title: response.title
        });

        if (response.state == 'error') {
          $(this).trigger('click');
        }
      }
      hidePageLoading();
    } else {
      $(this).trigger('click');
    }
  }
});

$(document).on('click', '.delete-amenidad', async function () {
  if (!$(this).is(':checked')) {
    const alertResponse = confirm('¡Cuidado!, ¿Realmente desea eliminar esta amenidad?');

    if (alertResponse) {
      showPageLoading();
      const idAmenidad = $(this).val();
      const idSalon = $('#idSalon').val();

      const dataSend = new FormData();

      dataSend.append('idAmenidad', idAmenidad);
      dataSend.append('idSalon', idSalon);
      dataSend.append('action', 'eliminar_amenidad');

      const response = await fetchData({
        place: 'business',
        data: dataSend
      });

      if (response) {
        showSweetAlert({
          icon: response.state,
          title: response.title
        });

        if (response.state == 'error') {
          $(this).trigger('click');
        }
      }
      hidePageLoading();
    } else {
      $(this).trigger('click');
    }
  }
});

$('#idMunicipio').on('change', async function () {
  showPageLoading();
  const idMunicipio = $(this).val();

  const data = new FormData();
  data.append('idMunicipio', idMunicipio);
  data.append('action', 'obtener_coordenadas');

  const resData = await fetchData({
    place: 'business',
    data: data
  });

  const coords = {
    lat: resData.latitude,
    lng: resData.longitude
  }

  if (resData.latitude && resData.longitude) {
    $('#latitud').val('');
    $('#longitud').val('');
    $('#direccion').val('');
    changeMapa(coords);
  }

  hidePageLoading();
});

function showTuxtlaInMap() {
  $('#latitud').val('');
  $('#longitud').val('');
  $('#direccion').val('');
  setMapa();
}

async function loadCitys() {
  const stateId = $(this).val();

  if (!stateId) return;

  showPageLoading();

  const parameters = new FormData();

  parameters.append('stateId', stateId);
  parameters.append('action', 'list_citys');

  const response = await fetchData({
    place: 'selects',
    data: parameters
  });

  if (response.content) $('#ciudad').html(decodeURIComponent(escape(atob(response.content))));

  hidePageLoading();
}

function handleAllEventTypes() {
  const isChecked = $(this).is(':checked');

  if (!isChecked) $('.check-tipoEvento').attr('checked', false);
  if (isChecked) $('.check-tipoEvento').attr('checked', true);
}

$('#all-tipoEventos').on('click', handleAllEventTypes);

$('#estado').on('change', loadCitys);