<?php
$page_slug  = 'editar-negocios';
include 'inc/session-proveedor.php';
$meta_title = 'Editar negocio';
$required_action = "edit";
include 'inc/verify-user-permissions.php';

$business_id    = cleanStr($_GET['uid']);
$business_dta  = getBusinessDataById($business_id);

/* echo $business_id;
die; */

if (!$business_dta) :
  header('location:negocios');
  die();
endif;

$type_hidden = $business_dta['idTipoProveedor'] == 1 ? '' : 'type-hidden';
$package_counter = 0;

$all_event_types = getEventTypesArray();
?>

<!doctype html>
<html lang="es">

<head>
  <!-- SELECT 2  -->
  <link rel="stylesheet" href="plugins/select2/css/select2.min.css">

  <?php include 'inc/meta-tags.php'; ?>

  <!-- CS File Pickers -->
  <link rel="stylesheet" href="<?= BASE_URL; ?>/plugins/cs-filepicker/cs-filepicker.css">
  <link rel="stylesheet" href="<?= BASE_URL; ?>/plugins/cs-multifilepicker/cs-multiple-filepicker.css">

  <link rel="stylesheet" href="<?= BASE_URL; ?>/../src/plugins/ckeditor/ckeditor.css">

  <style>
    .cke_chrome {
      width: 100% !important;
    }

    .salon-type.type-hidden {
      display: none !important;
    }

    .main-header {
      position: static !important;
    }
  </style>
</head>

<body>
  <div class="wrapper dashboard-wrapper">
    <div class="d-flex flex-wrap flex-xl-nowrap">
      <div class="db-sidebar bg-white" id="custom-sidebar">
        <nav class="navbar navbar-expand-xl navbar-light d-block px-0 header-sticky dashboard-nav py-0">
          <div class="sticky-area shadow-xs-1 py-3">
            <!-- MOBILE HEADER -->
            <?php include 'inc/mobile-header.php'; ?>

            <!-- SIDEBAR -->
            <?php include 'inc/sidebar.php' ?>
          </div>
        </nav>
      </div>

      <div class="page-content">
        <!-- HEADER -->
        <?php include 'inc/header.php'; ?>

        <main id="content" class="bg-gray-01">
          <div class="p-3">
            <div class="d-flex flex-wrap flex-md-nowrap mb-6">
              <div class="mr-0 mr-md-auto">
                <h2 class="mb-0 text-heading fs-22 lh-15">Editar negocio | <?= $business_dta['Salon']; ?></h2>
              </div>
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

                <a class="salon-type <?= $type_hidden; ?>" href="javascript:void(0)" data-content="step-services-amenities">
                  Servicios y Amenidades
                </a>

                <a href="javascript:void(0)" data-content="step-gallery">
                  Imágenes
                </a>
              </div>

              <div id="business-alert" class="alert alert-danger" role="alert" style="display: none;"></div>

              <div class="stepper-body">
                <div id="step-supplier-type" class="active">
                  <div class="row">
                    <div class="col-12 col-sm-6 mx-auto">
                      <div class="cs-card">
                        <div class="cs-card-heading" style="margin-bottom: 2rem;">
                          <h3 class="bold">Tipo de proveedor</h3>
                          <p>Selecciona el giro de proveduria al que pertenece tu negocio.</p>
                        </div>

                        <div class="radiobutton-group column mb">
                          <?= getSupplierTypesRadioButtons($business_dta['idTipoProveedor']); ?>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>

                <div id="step-event-type">
                  <div class="row">
                    <div class="col-12 col-sm-6 mx-auto">
                      <div class="cs-card">
                        <div class="cs-card-heading" style="margin-bottom: 2rem;">
                          <h3 class="bold">Tipo de evento</h3>
                          <p>Selecciona las categorías de eventos en las que te gustaría aparecer.</p>
                        </div>

                        <div id="event-types-container" class="checkbox-group between mb">
                          <?= getEventTypesCheckboxBySupplierTypeEvents($business_dta['eventos'], $business_dta['TipoEventos']); ?>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>

                <div id="step-business">
                  <div class="row">
                    <div class="col-12 col-sm-10 col-lg-10 mx-auto">
                      <div class="cs-card">
                        <div class="cs-card-heading" style="margin-bottom: 2rem;">
                          <h3 class="bold">Información de tu negocio</h3>
                        </div>

                        <div class="row">
                          <div class="col-12 col-sm-4 col-lg-6">
                            <div class="form-group">
                              <label for="businessName">Nombre de tu negocio<span>*</span></label>
                              <input id="businessName" name="businessName" value="<?= $business_dta['Salon']; ?>" type="text" validate>
                            </div>
                          </div>
                        </div>

                        <div class="row salon-type <?= $type_hidden; ?>">
                          <div class="col-6 col-sm-4 col-lg-3">
                            <div class="form-group">
                              <label for="minCapacity">Capacidad min<span>*</span></label>
                              <input id="minCapacity" class="number-input" name="minCapacity" value="<?= $business_dta['Capacidad']; ?>" type="number" validate>
                            </div>
                          </div>

                          <div class="col-6 col-sm-4 col-lg-3">
                            <div class="form-group">
                              <label for="maxCapacity">Capacidad max<span>*</span></label>
                              <input id="maxCapacity" class="number-input" name="maxCapacity" value="<?= $business_dta['CapacidadMaxima']; ?>" type="number" inputLabel="Capacidad max" greaterThan="minCapacity" greaterThanLabel="Capacidad mín." validate>
                            </div>
                          </div>
                        </div>

                        <div class="row">
                          <div class="col-12">
                            <div class="form-group">
                              <label for="businessDescription">Describe tu negocio<span>*</span></label>
                              <textarea id="businessDescription" name="businessDescription" rows="8" validate><?= $business_dta['Descripcion']; ?></textarea>
                            </div>
                          </div>
                        </div>

                        <div class="row">
                          <div class="col-12 col-sm-6">
                            <div class="form-group">
                              <label for="businessPhone">Teléfono de tu negocio</label>
                              <input id="businessPhone" class="number-input" name="businessPhone" value="<?= $business_dta['Telefono']; ?>" type="number">
                            </div>
                          </div>

                          <div class="col-12 col-sm-6">
                            <div class="form-group">
                              <label for="businessCellPhone">Celular/Whatsapp de tu negocio<span>*</span></label>

                              <div class="input-group">
                                <span class="input-group-addon position-absolute" id="prefixCellPhone">+52</span>
                                <input id="businessCellPhone" class="number-input" name="businessCellPhone" maxlength="10" aria-describedby="prefixCellPhone" value="<?= $business_dta['Celular']; ?>" type="text" validate>
                              </div>
                            </div>
                          </div>
                        </div>

                        <div class="row">
                          <div class="col-12">
                            <div class="form-group">
                              <label for="businessFacebook">Facebook</label>
                              <input id="businessFacebook" name="businessFacebook" placeholder="https://www.facebook.com" value="<?= $business_dta['Facebook']; ?>" type="text">
                            </div>
                          </div>

                          <div class="col-12">
                            <div class="form-group">
                              <label for="businessInstagram">Instagram</label>
                              <input id="businessInstagram" name="businessInstagram" placeholder="https://www.instagram.com" value="<?= $business_dta['Instagram']; ?>" type="text">
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>

                <div id="step-packages">
                  <div class="row">
                    <div class="col-12 col-sm-10 col-lg-12 mx-auto">
                      <div class="cs-card">
                        <div class="cs-card-heading" style="margin-bottom: 2rem;">
                          <h3 class="bold">Paquetes</h3>
                        </div>

                        <div id="package-list" class="row">
                          <?php foreach ($business_dta['Paquetes'] as $key => $value) :
                            $package_counter  = $key + 1;
                            //$close_icon       = $key == 0 ? false : true;
                            $close_icon       = true;
                          ?>
                            <?= getBusinessPackageItem($package_counter, $close_icon, $value); ?>
                          <?php endforeach; ?>
                        </div>

                        <div class="row">
                          <div class="col-12 col-sm-6 col-lg-4 mx-auto">
                            <a class="btn btn-primary btn-block btn-add-package" href="javascript:void(0)">
                              <i class="fa fa-plus-circle"></i>
                              NUEVO PAQUETE
                            </a>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>

                <div id="step-location">
                  <div class="row">
                    <div class="col-12 col-sm-10 mx-auto">
                      <div class="cs-card">
                        <div class="cs-card-heading">
                          <h3 class="bold">Ubicación de tu negocio</h3>
                        </div>

                        <div class="row">
                          <div class="col-12 col-sm-6">
                            <div class="form-group">
                              <label for="state">Estado<span>*</span></label>
                              <select id="state" class="select2" name="state" validate>
                                <?= statesForSelect('Seleccionar', $business_dta['idEstado']); ?>
                              </select>
                            </div>
                          </div>

                          <div id="city-container" class="col-12 col-sm-6">
                            <div class="form-group">
                              <label for="city">Ciudad/Municipio<span>*</span></label>
                              <select id="city" class="select2" name="city" validate>
                                <?= citysForSelect('Seleccionar', $business_dta['idEstado'], $business_dta['idCiudad']); ?>
                              </select>
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

                        <div class="map" style="width: 100%;">
                          <div id="business-map" latitude="<?= $business_dta['Latitud']; ?>" longitude="<?= $business_dta['Longitud']; ?>" style="width: 100%;height: 18rem;"></div>
                        </div>

                        <input id="latitude-business-map" type="hidden" name="latitude" value="<?= $business_dta['Latitud']; ?>" labelError="Ubique su negocio en el mapa o en el buscador" validate>
                        <input id="longitude-business-map" type="hidden" name="longitude" value="<?= $business_dta['Longitud']; ?>" labelError="Ubique su negocio en el mapa o en el buscador" validate>

                        <div class="form-group">
                          <input id="address-business-map" name="address" placeholder="Direccción" value="<?= $business_dta['Direccion']; ?>" type="text" labelError="Escribe tu dirección" validate>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>

                <div class="salon-type <?= $type_hidden; ?>" id="step-services-amenities">
                  <div class="row">
                    <div class="col-12 col-sm-6 col-lg-8 mx-auto">
                      <div class="cs-card">
                        <div class="cs-card-heading">
                          <h3 class="bold">Servicios</h3>
                          <p>Selecciona el tipo de menús que puedes llevar a cabo.</p>
                        </div>

                        <div class="checkbox-group mb">
                          <?= getServicesCheckbox($business_dta['Servicios']); ?>
                        </div>

                        <div class="cs-card-heading">
                          <h3 class="bold">Amenidades</h3>
                          <p>Selecciona las amenidades con que cuenta tu espacio.</p>
                        </div>

                        <div class="checkbox-group mb">
                          <?= getAmenitiesCheckbox($business_dta['Amenidades']); ?>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>

                <div id="step-gallery">
                  <div class="row">
                    <div class="col-12 col-sm-10 mx-auto">
                      <div class="cs-card">
                        <div class="cs-card-heading">
                          <h3 class="bold">Imágenes</h3>
                        </div>

                        <div class="row">
                          <div class="col-12 col-lg-6">
                            <div class="form-group">
                              <div id="principalImage" data-name="principalImage" data-title="<span style='color:red'>*</span>Adjuntar imagen principal" data-subtitle="La imagen debe de ser de 600x600 de lo contrario la imagen será rechazada" data-labelError="Ajunta la imagen principal" data-required="true"></div>
                            </div>
                          </div>

                          <div class="col-12 col-lg-6">
                            <div class="form-group">
                              <div id="businessLogo" data-name="businessLogo" data-title="<span style='color:red'>*</span>Adjuntar imagen principal" data-subtitle="La imagen debe de ser de 600x600 de lo contrario la imagen será rechazada" data-labelError="Ajunta la imagen principal" data-required="true"></div>
                            </div>
                          </div>
                        </div>

                        <div class="col-12">
                          <div class="alert alert-info">
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

              <input type="hidden" name="businessId" value="<?= $business_id; ?>">

              <div class="stepper-footer">
                <div></div>

                <div class="btn-controls">
                  <a class="prev" href="javascript:void(0)">
                    <i class="far fa-arrow-alt-circle-left"></i>
                  </a>

                  <p class="step-label">Siguiente paso</p>
                  <p class="finish-label">Finalizar</p>

                  <button class="next pulse" type="submit">
                    <i class="far fa-arrow-alt-circle-right"></i>
                  </button>

                  <button class="finish" type="submit" style="
                    font-size: 0.90rem;
                    background: var(--primary);
                    width: auto;
                    line-height: 1;
                    font-weight: bold;
                    padding: 0.5rem 1rem;
                  ">
                    Guardar cambios
                  </button>
                </div>
              </div>
            </form>
          </div>

          <!-- PAGE LOADING -->
          <?php include 'inc/page-loading.php' ?>
          <?php include 'inc/page-progressbar.php' ?>
        </main>
      </div>
    </div>
  </div>

  <!-- REQUIRED SCRIPTS -->
  <?php include 'inc/required-scripts.php'; ?>
  <?php include 'inc/svg.php'; ?>

  <script>
    var allEventTypes = <?= json_encode($all_event_types); ?>;
  </script>

  <!-- CKEDITOR 4 -->
  <!-- <script src="https://cdn.ckeditor.com/4.19.0/basic/ckeditor.js"></script> -->
  <script src="<?= BASE_URL; ?>/../src/plugins/ckeditor/ckeditor.min.js"></script>
  <script src="<?= BASE_URL; ?>/../src/plugins/ckeditor/main.js"></script>

  <!-- <script src="plugins/ckeditor5/ckeditor5-build-classic/ckeditor.js"></script> -->
  <script src="plugins/select2/js/select2.full.min.js"></script>

  <script src="js/functions.js"></script>
  <!-- <script src="js/dynamic-multiple-picker.js"></script>
  <script src="js/dynamic-picker-with-editor.js"></script> -->

  <!-- CS File pickers -->
  <script src="<?= BASE_URL; ?>/plugins/cs-filepicker/cs-filepicker.js"></script>
  <script src="<?= BASE_URL; ?>/plugins/cs-multifilepicker/cs-multiple-filepicker.js"></script>

  <!-- Google maps -->
  <script src="<?= BASE_URL; ?>/plugins/google-maps/multiple-google-maps.js"></script>
  <script src="https://maps.googleapis.com/maps/api/js?key=<?= GOOGLE_MAPS_API_KEY; ?>&libraries=places&v=weekly"></script>

  <script>
    var packageCounter = <?= $package_counter; ?>;
    var imagesForGallery = <?= json_encode($business_dta['Galeria']); ?>;

    var principalImageData = {
      imageSrc: '<?= BASE_URL_FRONTED . '/src/assets/images/listing/' . $business_dta['Imagen']; ?>',
      imageName: '<?= $business_dta['Salon']; ?>'
    };

    <?php if ($business_dta['Logo']) : ?>
      var businessLogoData = {
        imageSrc: '<?= BASE_URL_FRONTED . '/src/assets/images/listing/' . $business_dta['Logo']; ?>',
        imageName: '<?= $business_dta['Salon']; ?>'
      };
    <?php endif; ?>

    <?php if (!$business_dta['Logo']) : ?>
      var businessLogoData = null;
    <?php endif; ?>
  </script>

  <script src="main/business/edit-business.js"></script>

  <script>
    /* $csFilePickerCreateElement('principalImage').then(() => $csFilePickerCreateImagePreview({
      id: 'principalImage',
      imageSrc: `<?= BASE_URL_FRONTED; ?>/src/assets/images/listing/<?= $business_dta['Imagen']; ?>`,
      imageName: '<?= $business_dta['Salon']; ?>'
    })); */

    /* $csMultipleFilePickerCreateElement('imageGallery').then(() => $csMultipleFilePickerCreateImagePreview({
      id: 'imageGallery',
      imageData: <?= json_encode($business_dta['Galeria']); ?>
    })); */
  </script>

  <!-- <script src="main/business/add-business.js"></script>
  <script src="main/packages/packages.js"></script> -->

  <!-- <script>
    createMultiplePicker('galeria-imagenes');
    createPickerWithCropper('imagen-salon');
    loadEventTypes();

    $('.select2').select2({
      theme: 'bootstrap4'
    });

    $(document).ready(() => hidePageLoading());
  </script> -->

  <!-- <script src="js/google-search.js"></script>
  <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBcXIXiRSirvWVofs7wRolh-WjSSUF4jIE&callback=setMapa&libraries=places&v=weekly" async></script> -->,

  <script>
    $('.select2').select2();
  </script>
</body>

</html>