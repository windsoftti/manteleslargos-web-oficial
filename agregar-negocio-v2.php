<?php
include 'inc/public-session.php';

if (!$_SESSION['session_user_id']) :
  header('location:' . BASE_URL);
  die();
endif;

if ($_SESSION['session_user_level'] != 'Usuario') :
  header('location:' . BASE_URL);
  die();
endif;

$supplier_access = checkSupplierAccessStatus();

if ($supplier_access['status'] == 'unverified') {
  header('location:' . BASE_URL . '/verificar-cuenta-proveedor');
} else if ($supplier_access['status'] == 'no-business') {
  //header('location:' . BASE_URL . '/mi-cuenta');
} else if ($supplier_access['status'] == 'logged') {
  header('location:' . BASE_URL . '/mi-cuenta');
} else {
  header('location:' . BASE_URL);
}

$all_event_types = getEventTypesArray();
?>

<!DOCTYPE html>
<html lang="es">

<head>
  <?php include 'src/components/head.php'; ?>

  <!-- Pure css -->
  <link rel="stylesheet" href="https://unpkg.com/purecss@2.1.0/build/grids-min.css">
  <link rel="stylesheet" href="https://unpkg.com/purecss@2.1.0/build/grids-responsive-min.css">

  <!-- CS File Pickers -->
  <link rel="stylesheet" href="<?= BASE_URL; ?>/src/plugins/cs-filepicker/cs-filepicker.css">
  <link rel="stylesheet" href="<?= BASE_URL; ?>/src/plugins/cs-multifilepicker/cs-multiple-filepicker.css">

  <style>
    .cke_chrome {
      width: 100% !important;
    }

    .salon-type.type-hidden {
      display: none !important;
    }
  </style>
</head>

<body class="navbar-white">
  <!-- Preloader -->
  <?php include 'src/components/preloader.php'; ?>

  <!-- Navbar -->
  <?php include 'src/components/navbar.php'; ?>

  <!-- Main -->
  <main class="main">
    <div class="modal-heading" style="margin-top: 1rem;">
      <h3>Que gusto verte <?= explode(' ', $_SESSION['session_user_name'])[0]; ?></h3>
      <p>Continua con tu registro</p>
      <a href="<?= BASE_URL; ?>/cerrar-sesion">Cerrar sesión</a>
    </div>
    <!-- STEPPER -->
    <form id="business-form" class="stepper" method="POST" target="_blank" autocomplete="off">
      <div class="stepper-header stepper-header-block">
        <a class="active" href="javascript:void(0)" data-content="step-supplier-type">
          Tipo de proveedor
        </a>

        <a href="javascript:void(0)" data-content="step-event-type">
          Tipo de evento
        </a>

        <a href="javascript:void(0)" data-content="step-business">
          Negocio
        </a>

        <a href="javascript:void(0)" data-content="step-packages">
          Paquetes
        </a>

        <a href="javascript:void(0)" data-content="step-location">
          Ubicación
        </a>

        <a class="salon-type type-hidden" href="javascript:void(0)" data-content="step-services-amenities">
          Servicios y Amenidades
        </a>

        <a href="javascript:void(0)" data-content="step-gallery">
          Imágenes
        </a>
      </div>

      <div id="business-alert" class="alert error" style="display: none;"></div>

      <div class="stepper-body">
        <div id="step-supplier-type" class="active">
          <div class="pure-g">
            <div class="pure-u-1 pure-u-sm-1-2 mx-auto">
              <div class="card">
                <div class="card-heading" style="margin-bottom: 2rem;">
                  <h3 class="bold">Tipo de proveedor</h3>
                  <p>Selecciona el giro de proveduria al que pertenece tu negocio.</p>
                </div>

                <div class="radiobutton-group column mb">
                  <?= getSupplierTypesRadioButtons(); ?>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div id="step-event-type">
          <div class="pure-g">
            <div class="pure-u-1 pure-u-sm-1-2 mx-auto">
              <div class="card">
                <div class="card-heading" style="margin-bottom: 2rem;">
                  <h3 class="bold">Tipo de evento</h3>
                  <p>Selecciona las categorías de eventos en las que te gustaría aparecer.</p>
                </div>

                <div id="event-types-container" class="checkbox-group between mb">
                  <?php /* <?= getEventTypesCheckbox(); ?> */ ?>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div id="step-business">
          <div class="pure-g">
            <div class="pure-u-1 pure-u-sm-4-5 pure-u-lg-2-3 mx-auto">
              <div class="card">
                <div class="card-heading" style="margin-bottom: 2rem;">
                  <h3 class="bold">Información de tu negocio</h3>
                </div>

                <div class="pure-g">
                  <div class="pure-u-1 pure-u-sm-1-3 pure-u-lg-1-2">
                    <div class="form-group">
                      <label for="businessName">Nombre de tu negocio<span>*</span></label>
                      <input id="businessName" name="businessName" type="text" labelError="Escribe el nombre de tu negocio" validate>
                    </div>
                  </div>
                </div>

                <div class="pure-g">
                  <div class="pure-u-1">
                    <div class="form-group">
                      <label for="businessSlug">URL de tu negocio</label>

                      <div class="input-group">
                        <div class="prepend">/</div>

                        <input id="businessSlug" name="businessSlug" type="text" validate>

                        <div class="append">
                          <button id="verify-slug" class="btn btn-primary" type="button">
                            Verificar
                          </button>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>

                <div class="pure-g salon-type type-hidden">
                  <div class="pure-u-1-2 pure-u-sm-1-3 pure-u-lg-1-4">
                    <div class="form-group">
                      <label for="minCapacity">Capacidad min<span>*</span></label>
                      <input id="minCapacity" class="number-input" name="minCapacity" min="1" type="number" labelError="Escribe la capacidad mínima de tu negocio" validate>
                    </div>
                  </div>

                  <div class="pure-u-1-2 pure-u-sm-1-3 pure-u-lg-1-4">
                    <div class="form-group">
                      <label for="maxCapacity">Capacidad max<span>*</span></label>
                      <input id="maxCapacity" class="number-input" name="maxCapacity" min="2" type="number" inputLabel="Capacidad max" greaterThan="minCapacity" greaterThanLabel="Capacidad mín." labelError="Escribe la capacidad máxima de tu negocio" validate>
                    </div>
                  </div>
                </div>

                <div class="pure-g">
                  <div class="pure-u-1">
                    <div class="form-group">
                      <label for="businessDescription">Describe tu negocio<span>*</span></label>
                      <textarea id="businessDescription" name="businessDescription" rows="8" labelError="Describe de manera detallada tu negocio" validate></textarea>
                    </div>
                  </div>
                </div>

                <div class="pure-g">
                  <div class="pure-u-1 pure-u-sm-1-2">
                    <div class="form-group">
                      <label for="businessPhone">Teléfono de tu negocio</label>
                      <input id="businessPhone" class="number-input" name="businessPhone" type="number">
                    </div>
                  </div>

                  <div class="pure-u-1 pure-u-sm-1-2">
                    <div class="form-group">
                      <label for="businessCellPhone">Celular/Whatsapp de tu negocio<span>*</span></label>

                      <div class="input-group">
                        <input id="businessCellPhone" class="number-input" name="businessCellPhone" type="text" maxlength="10" labelError="Escribe el celular/whatsapp de tu negocio" validate>
                        <div class="prepend">
                          <p>+52</p>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>

                <div class="pure-g">
                  <div class="pure-u-1">
                    <div class="form-group">
                      <label for="businessFacebook">Facebook</label>
                      <input id="businessFacebook" name="businessFacebook" placeholder="https://www.facebook.com" type="text">
                    </div>
                  </div>
                </div>


                <div class="pure-g">
                  <div class="pure-u-1">
                    <div class="form-group">
                      <label for="businessInstagram">Instagram</label>
                      <input id="businessInstagram" name="businessInstagram" placeholder="https://www.instagram.com" type="text">
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div id="step-packages">
          <div class="pure-g">
            <div class="pure-u-1 pure-u-sm-3-5 pure-u-lg-1-1 mx-auto">
              <div class="card">
                <div class="card-heading" style="margin-bottom: 2rem;">
                  <h3 class="bold">Paquetes</h3>
                </div>

                <div id="package-list" class="pure-g">
                  <?= getBusinessPackageItem(1, false); ?>
                </div>

                <div class="pure-g">
                  <div class="pure-u-1 pure-u-sm-1-2 pure-u-lg-1-3 mx-auto">
                    <a class="btn btn-primary btn-block btn-add-package" href="javascript:void(0)">
                      <ion-icon name="add-circle-outline"></ion-icon>
                      NUEVO PAQUETE
                    </a>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div id="step-location">
          <div class="pure-g">
            <div class="pure-u-1 pure-u-sm-4-5 mx-auto">
              <div class="card">
                <div class="card-heading">
                  <h3 class="bold">Ubicación de tu negocio</h3>
                </div>

                <div class="pure-g">
                  <div class="pure-u-1 pure-u-sm-1-2">
                    <div class="form-group">
                      <label for="state">Estado<span>*</span></label>
                      <select id="state" name="state" labelError="Selecciona el estado al que pertenece tu negocio" validate>
                        <?= statesForSelect('Seleccionar'); ?>
                      </select>
                    </div>
                  </div>

                  <div id="city-container" class="pure-u-1 pure-u-sm-1-2" style="display: none;">
                    <div class="form-group">
                      <label for="city">Ciudad/Municipio<span>*</span></label>
                      <select id="city" name="city" labelError="Selecciona el municipio al que pertenece tu negocio" validate></select>
                    </div>
                  </div>
                </div>

                <div class="form-group">
                  <label>
                    Ubicación<span>*</span> <br>
                    <span><b>¡IMPORTANTE!</b> Arrastra el marcador hasta la ubicación del lugar ó buscalo aqui colocando el nombre o su dirección completa.</span>
                  </label>
                </div>

                <div class="form-group">
                  <input id="search-business-map" placeholder="Buscar lugar" type="text">
                </div>

                <div class="map">
                  <div id="business-map" style="height: 18rem;"></div>
                </div>

                <input id="latitude-business-map" type="hidden" name="latitude" labelError="Ubica tu negocio en el mapa o en el buscador" validate>
                <input id="longitude-business-map" type="hidden" name="longitude" labelError="Ubica tu negocio en el mapa o en el buscador" validate>

                <div class="form-group">
                  <input id="address-business-map" name="address" placeholder="Direccción" type="text" labelError="Escribe la dirección de tu negocio" validate>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="salon-type type-hidden" id="step-services-amenities">
          <div class="pure-g">
            <div class="pure-u-1 pure-u-sm-1-2 mx-auto">
              <div class="card">
                <div class="card-heading">
                  <h3 class="bold">Servicios</h3>
                  <p>Selecciona el tipo de menús que puedes llevar a cabo.</p>
                </div>

                <div class="checkbox-group mb">
                  <?= getServicesCheckbox(); ?>
                </div>

                <div class="card-heading">
                  <h3 class="bold">Amenidades</h3>
                  <p>Selecciona las amenidades con que cuenta tu espacio.</p>
                </div>

                <div class="checkbox-group mb">
                  <?= getAmenitiesCheckbox(); ?>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div id="step-gallery">
          <div class="pure-g">
            <div class="pure-u-1 pure-u-sm-1-2 pure-u-lg-4-5 mx-auto">
              <div class="card">
                <div class="card-heading">
                  <h3 class="bold">Imágenes</h3>
                </div>

                <div class="pure-g">
                  <div class="pure-u-1 pure-u-lg-1-2">
                    <div class="form-group">
                      <div id="principalImage" data-name="principalImage" data-title="<span style='color:red'>*</span>Adjuntar imagen principal" data-subtitle="Para una correcta visualización recomendamos imagenes de 600x600 px" data-labelError="Ajunta la imagen principal de tu negocio" data-required="true"></div>
                    </div>
                  </div>

                  <div class="pure-u-1 pure-u-lg-1-2">
                    <div class="form-group">
                      <div id="businessLogo" data-name="businessLogo" data-title="<span style='color:red'>*</span>Adjuntar imagen principal" data-subtitle="Para una correcta visualización recomendamos imagenes de 600x600 px" data-labelError="Ajunta la imagen principal de tu negocio" data-required="true"></div>
                    </div>
                  </div>
                </div>

                <div class="form-group">
                  <div class="alert info">
                    <div>
                      <ion-icon name="information-circle-outline"></ion-icon>
                    </div>

                    Puedes agregar hasta 6 imágenes en la galería
                  </div>
                </div>

                <div class="form-group">
                  <label>Galería de imagenes</label>
                  <div id="imageGallery" data-name="imageGallery"></div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="stepper-footer">
        <div></div>

        <div class="btn-controls">
          <a class="prev" href="javascript:void(0)">
            <ion-icon name="arrow-back-circle-outline"></ion-icon>
          </a>

          <p class="step-label">Siguiente paso</p>
          <p class="finish-label">Finalizar</p>

          <button id="btn-next" class="next pulse" type="submit" style="border-radius: 100%;">
            <ion-icon name="arrow-forward-circle-outline"></ion-icon>
          </button>

          <button class="finish" type="submit">
            <ion-icon name="checkmark" style="font-size: 1.5rem;"></ion-icon>

            Registrar negocio
          </button>
        </div>
      </div>
    </form>

    <!-- Modal for login and register -->
    <?php include 'src/modals/login-register.php'; ?>

    <!-- Page loading -->
    <?php include 'src/components/page-loading.php'; ?>
    <?php include 'src/components/page-progressbar.php'; ?>
  </main>

  <!-- Footer -->
  <?php include 'src/components/footer.php'; ?>

  <!-- Required scripts -->
  <?php include 'src/components/required-scripts.php'; ?>

  <script>
    var allEventTypes = <?= json_encode($all_event_types); ?>;
  </script>

  <!-- CKEDITOR 4 -->
  <script src="https://cdn.ckeditor.com/4.19.0/basic/ckeditor.js"></script>

  <!-- CS File pickers -->
  <script src="<?= BASE_URL; ?>/src/plugins/cs-filepicker/cs-filepicker.js"></script>
  <script src="<?= BASE_URL; ?>/src/plugins/cs-multifilepicker/cs-multiple-filepicker.js"></script>

  <!-- Google maps -->
  <script src="<?= BASE_URL; ?>/src/plugins/google-maps/multiple-google-maps.js"></script>
  <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBcXIXiRSirvWVofs7wRolh-WjSSUF4jIE&libraries=places&v=weekly"></script>

  <script src="<?= BASE_URL; ?>/src/js/add-business.js"></script>

  <script>
    /* let packageDescription;
const createEditor = () => packageDescription = CKEDITOR.replace('packageDescription');
createEditor(); */

    const slugify = text =>
      text
      .toLowerCase()
      .replace('ñ', 'ni')
      .toString()
      .normalize('NFD')
      .replace(/[\u0300-\u036f]/g, '')
      .toLowerCase()
      .trim()
      .replace(/\s+/g, '-')
      .replace(/[^\w-]+/g, '')
      .replace(/--+/g, '-');

    $('#businessName').on('keyup', function() {
      const slug = slugify($(this).val());
      $('#businessSlug').val(slug);
    });

    $('#businessSlug').on('keyup', function() {
      const slug = slugify($(this).val());
      $(this).val(slug);
    });

    $('#verify-slug').on('click', () => callEndpoint({
      place: 'businesses',
      parameters: {
        action: 'verify-slug',
        data: $('#businessSlug')
      }
    }).then(response => {
      console.log(response)
    }));
  </script>
</body>

</html>