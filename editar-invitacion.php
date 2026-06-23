<?php
include 'inc/user-session.php';

$invitation_slug = cleanStr($_GET['invitationSlug']);

if (!$invitation_slug) :
  header('location:' . BASE_URL . '/mis-invitaciones');
  die();
endif;

$invitation = getInvitationDataBySlug($invitation_slug);

if (!$invitation) :
  header('location:' . BASE_URL . '/mis-invitaciones');
  die();
endif;

$gallery = getInvitationGalleryById($invitation['idInvitacion']);

$slider_position = 0;
$template = '';

if ($invitation['Plantilla'] == '02') $slider_position  = 1;
if ($invitation['Plantilla'] == '03') $slider_position  = 2;

if ($invitation['Plantilla'] == '01') $template         = 'template-1';
if ($invitation['Plantilla'] == '02') $template         = 'template-2';
if ($invitation['Plantilla'] == '03') $template         = 'template-3';
?>

<!DOCTYPE html>
<html lang="es">

<head>
  <?php include 'src/components/head.php'; ?>

  <!-- Slick slider -->
  <link rel="stylesheet" href="<?= BASE_URL; ?>/src/plugins/slick-slider/slick.css">
  <link rel="stylesheet" href="<?= BASE_URL; ?>/src/plugins/slick-slider/slick-theme.css">

  <!-- CS File Pickers -->
  <link rel="stylesheet" href="<?= BASE_URL; ?>/src/plugins/cs-filepicker/cs-filepicker.css">
  <link rel="stylesheet" href="<?= BASE_URL; ?>/src/plugins/cs-multifilepicker/cs-multiple-filepicker.css">

  <!-- Datetime picker -->
  <link rel="stylesheet" href="<?= BASE_URL; ?>/src/plugins/datetimepicker/css/bootstrap.css">
  <link rel="stylesheet" href="https://netdna.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.css">
  <link rel="stylesheet" href="<?= BASE_URL; ?>/src/plugins/datetimepicker/css/bootstrap-datetimepicker.min.css">

  <!-- Pure css -->
  <link rel="stylesheet" href="https://unpkg.com/purecss@2.1.0/build/grids-min.css">
  <link rel="stylesheet" href="https://unpkg.com/purecss@2.1.0/build/grids-responsive-min.css">

  <link rel="stylesheet" href="<?= BASE_URL; ?>/src/css/pages/invitations.css">

  <style>
    .slick-prev:before,
    .slick-next:before {
      color: var(--primary-color);
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
    <!-- Invitation navbar -->
    <?php include 'src/components/invitations-navbar.php'; ?>

    <!-- Stepper -->
    <form id="create-invitation-form" class="stepper" method="POST" action="<?= BASE_URL; ?>/visualizar-invitacion" target="_blank" autocomplete="off">
      <div class="stepper-header">
        <a class="active" href="javascript:void(0)" data-content="step-general-data">
          Datos Generales
        </a>

        <a href="javascript:void(0)" data-content="step-where-and-when">
          ¿Dónde y Cuando?
        </a>

        <a href="javascript:void(0)" data-content="step-image-gallery">
          Galería de imágenes
        </a>
      </div>

      <div id="create-invitation-alert" class="alert error" style="display: none;"></div>

      <div class="stepper-body">
        <div id="step-general-data" class="active">
          <div class="pure-g">
            <div class="pure-u-1 pure-u-sm-1-2">
              <div class="form-group">
                <label for="invitationType"><span>*</span>Tipo de invitación</label>
                <select id="invitationType" name="invitationType" validate>
                  <?= eventTypesForSelect('Seleccionar', $invitation['TipoInvitacion'], 'label'); ?>
                </select>
              </div>

              <div class="form-group">
                <label for="names"><span>*</span>Nombre de los festejados</label>
                <input id="names" type="text" name="names" placeholder="ej: Aitana" value="<?= $invitation['NombrePersona']; ?>" validate>
              </div>

              <div class="form-group">
                <label for="eventName"><span>*</span>Nombre del evento</label>
                <input id="eventName" type="text" name="eventName" placeholder="ej: Mi Bautizo" value="<?= $invitation['NombreEvento']; ?>" validate>
              </div>

              <div class="form-group">
                <label for="contact"><span>*</span>Celular/Whatsapp</label>
                <div class="input-group">
                  <input id="contact" class="number-input" type="text" name="contact" placeholder="ej: 9999999999" value="<?= $invitation['Telefono']; ?>" validate>

                  <div class="prepend">
                    <p>+52</p>
                  </div>
                </div>
              </div>

              <div class="form-group">
                <label for="commemorativePhrase"><span>*</span>Frase conmemorativa</label>
                <textarea id="commemorativePhrase" name="commemorativePhrase" rows="6" placeholder="ej: Mi familia y yo te esperamos para celebrar" validate><?= $invitation['Frase']; ?></textarea>
              </div>
            </div>

            <div class="pure-u-1 pure-u-sm-1-2 select-template">
              <div class="form-group">
                <label><span>*</span>Selecciona una plantilla.</label>
                <label><span><b>Nota:</b></span> Haz click en la plantilla para seleccionarla.</label>
                <div class="templates-content">
                  <div>
                    <div class="templates-slider">
                      <div>
                        <div class="radio-button-img">
                          <input id="template-1" name="template" type="radio" labelError='Selecciona una plantilla' value="01" <?= $invitation['Plantilla'] == '01' ? 'checked' : ''; ?> validate>
                          <?php if ($invitation['Plantilla'] == '01') : ?>
                            <label for="template-1" data-primaryColor="<?= $invitation['ColorPrincipal']; ?>" data-secondaryColor="<?= $invitation['ColorSecundario']; ?>">
                              <img src="<?= BASE_URL; ?>/src/assets/images/invitations/previews/preview-01.jpg">
                            </label>
                          <?php endif; ?>

                          <?php if ($invitation['Plantilla'] != '01') : ?>
                            <label for="template-1" data-primaryColor="#faa9ec" data-secondaryColor="#ededed">
                              <img src="<?= BASE_URL; ?>/src/assets/images/invitations/previews/preview-01.jpg">
                            </label>
                          <?php endif; ?>
                        </div>
                      </div>

                      <div>
                        <div class="radio-button-img">
                          <input id="template-2" name="template" type="radio" labelError='Seleccione una plantilla' value="02" <?= $invitation['Plantilla'] == '02' ? 'checked' : ''; ?> validate>

                          <?php if ($invitation['Plantilla'] == '02') : ?>
                            <label for="template-2" data-primaryColor="<?= $invitation['ColorPrincipal']; ?>" data-secondaryColor="<?= $invitation['ColorSecundario']; ?>">
                              <img src="<?= BASE_URL; ?>/src/assets/images/invitations/previews/preview-02.png">
                            </label>
                          <?php endif; ?>

                          <?php if ($invitation['Plantilla'] != '02') : ?>
                            <label for="template-2" data-primaryColor="#f6e2e4" data-secondaryColor="#8c6a3b">
                              <img src="<?= BASE_URL; ?>/src/assets/images/invitations/previews/preview-02.png">
                            </label>
                          <?php endif; ?>
                        </div>
                      </div>

                      <div>
                        <div class="radio-button-img">
                          <input id="template-3" name="template" type="radio" labelError='Seleccione una plantilla' value="03" <?= $invitation['Plantilla'] == '03' ? 'checked' : ''; ?> validate>

                          <?php if ($invitation['Plantilla'] == '03') : ?>
                            <label for="template-3" data-primaryColor="<?= $invitation['ColorPrincipal']; ?>" data-secondaryColor="<?= $invitation['ColorSecundario']; ?>">
                              <img src="<?= BASE_URL; ?>/src/assets/images/invitations/previews/preview-03.jpg">
                            </label>
                          <?php endif; ?>

                          <?php if ($invitation['Plantilla'] != '03') : ?>
                            <label for="template-3" data-primaryColor="#5d7791" data-secondaryColor="#ffa400">
                              <img src="<?= BASE_URL; ?>/src/assets/images/invitations/previews/preview-03.jpg">
                            </label>
                          <?php endif; ?>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>

              <div class="pure-g">
                <div class="pure-u-1-2">
                  <div class="form-group color">
                    <label for="principalColor"><span>*</span>Color<br>principal</label>
                    <input id="principalColor" name="principalColor" type="color" value="<?= $invitation['ColorPrincipal']; ?>">
                  </div>
                </div>

                <div class="pure-u-1-2">
                  <div class="form-group color">
                    <label for="secondaryColor"><span>*</span>Color<br>secundario</label>
                    <input id="secondaryColor" name="secondaryColor" type="color" value="<?= $invitation['ColorSecundario']; ?>">
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div id="step-where-and-when">
          <div class="pure-g">
            <div class="pure-u-1-1 pure-u-md-1-2 invitation-card <?= $invitation['CRImagen'] ? 'active' : ''; ?>">
              <a id="btn-add-religious" class="btn btn-block btn-primary btn-add-invitation-content" href="javascript:void(0)">
                <ion-icon name='add-circle'></ion-icon>
                Agregar ceremonia religiosa
              </a>

              <div class="pure-g invitation-content">
                <div class="pure-u-1">
                  <h3 class="heading-bold place-heading">
                    Lugar de la ceremonia

                    <a class="btn-remove-invitation-content" href="javascript:void(0)">
                      <ion-icon name="close"></ion-icon>
                    </a>
                  </h3>

                  <div class="form-group">
                    <div id="CRPicture" data-name="CRPicture" data-title="<span style='color:red'>*</span>Adjuntar fotografía del lugar" data-subtitle="" data-labelError="Ajunta la imagen de la ceremonia religiosa" data-required="true"></div>
                  </div>

                  <div class="form-group">
                    <div class="form-group">
                      <label for="CRPlace"><span>*</span>Lugar de la ceremonia</label>
                      <input id="CRPlace" type="text" name="CRPlace" placeholder="ej: Iglesia de San Martín" value="<?= $invitation['CRLugar']; ?>" validate>
                    </div>

                    <div class="form-group">
                      <label for="CRDateTime"><span>*</span>Fecha y Hora</label>
                      <input id="CRDateTime" class="datetimepicker" type="text" name="CRDateTime" inputmode='none' value="<?= $invitation['CRFecha']; ?>" validate>
                    </div>

                    <div class="form-group">
                      <label>
                        *Ubicación <br>
                        <span><b>¡IMPORTANTE!</b> Arrastra el marcador hasta la ubicación del lugar ó buscalo aqui colocando el nombre o su dirección completa.</span>
                      </label>
                    </div>

                    <div class="form-group">
                      <input id="search-CRMap" placeholder="Buscar lugar" type="text">
                    </div>

                    <div class="map">
                      <div id="CRMap" latitude="<?= $invitation['CRLatitud']; ?>" longitude="<?= $invitation['CRLongitud']; ?>"></div>
                    </div>

                    <input id="latitude-CRMap" type="hidden" name="CRLatitude" value="<?= $invitation['CRLatitud']; ?>">
                    <input id="longitude-CRMap" type="hidden" name="CRLongitude" value="<?= $invitation['CRLongitud']; ?>">

                    <div class="form-group">
                      <input id="address-CRMap" name="CRAddress" placeholder="Direccción" type="text" value="<?= $invitation['CRDireccion']; ?>" validate>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <div class="pure-u-1-1 pure-u-md-1-2 invitation-card <?= $invitation['RImagen'] ? 'active' : ''; ?>">
              <a id="btn-add-reception" class="btn btn-block btn-primary btn-add-invitation-content" href="javascript:void(0)">
                <ion-icon name='add-circle'></ion-icon>
                Agregar ceremonia de recepción
              </a>

              <div class="pure-g invitation-content">
                <div class="pure-u-1">
                  <h3 class="heading-bold place-heading">
                    Lugar de recepción

                    <a class="btn-remove-invitation-content" href="javascript:void(0)">
                      <ion-icon name="close"></ion-icon>
                    </a>
                  </h3>

                  <div class="form-group">
                    <div id="RPicture" data-name="RPicture" data-title="<span style='color:red'>*</span>Adjuntar fotografía del lugar" data-subtitle="" data-labelError="Ajunta la imagen de la ceremonia de recepción" data-required="true"></div>
                  </div>

                  <div class="form-group">
                    <div class="form-group">
                      <label for="RPlace"><span>*</span>Lugar de la recepción</label>
                      <input id="RPlace" name="RPlace" placeholder="ej: Salón el mesón" type="text" value="<?= $invitation['RLugar']; ?>" validate>
                    </div>

                    <div class="form-group">
                      <label for="RDateTime"><span>*</span>Fecha y Hora</label>
                      <input id="RDateTime" class="datetimepicker" name="RDateTime" inputmode='none' type="text" value="<?= $invitation['RFecha']; ?>" validate>
                    </div>

                    <div class="form-group">
                      <label>
                        *Ubicación <br>
                        <span><b>¡IMPORTANTE!</b> Arrastra el marcador hasta la ubicación del lugar ó buscalo aqui colocando el nombre o su dirección completa.</span>
                      </label>
                    </div>

                    <div class="form-group">
                      <input id="search-RMap" placeholder="Buscar lugar" type="text">
                    </div>

                    <div class="map">
                      <div id="RMap" latitude="<?= $invitation['RLatitud']; ?>" longitude="<?= $invitation['RLongitud']; ?>"></div>
                    </div>

                    <input id="latitude-RMap" type="hidden" name="RLatitude" value="<?= $invitation['RLatitud']; ?>">
                    <input id="longitude-RMap" type="hidden" name="RLongitude" value="<?= $invitation['RLongitud']; ?>">

                    <div class="form-group">
                      <input id="address-RMap" name="RAddress" placeholder="Direccción" type="text" value="<?= $invitation['RDireccion']; ?>" validate>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div id="step-image-gallery">
          <div class="pure-g">
            <div class="pure-u-1 pure-u-sm-1-2" style="padding: 0.5rem;margin:0">
              <div class="form-group">
                <div id="individualPicture" data-name="individualPicture" data-title="<span style='color:red'>*</span>Adjuntar fotografía individual" data-subtitle="" data-labelError="Ajunta la fotografía individual" data-required="true"></div>
              </div>
            </div>

            <!-- <div class="pure-u-1 pure-u-sm-1-2" style="padding: 0.5rem;">
              <div class="form-group">
                <div id="familyPicture" data-name="famyliPicture" data-title="<span style='color:red'>*</span>Adjuntar fotografía familiar" data-subtitle="" data-labelError="Ajunta la fotografía familiar" data-required="true"></div>
              </div>
            </div> -->
          </div>

          <div class="form-group">
            <label>Galería de imágenes</label>
            <div id="imageGallery" data-name="imageGallery"></div>
          </div>
        </div>
      </div>

      <div id="image-gallerypreview-container"></div>

      <input type="hidden" name="invitationId" value="<?= $invitation['idInvitacion']; ?>">

      <div class="stepper-footer">
        <div>
          <button class="visualize" type="submit" style="width: auto; gap: 1rem;">
            <ion-icon name="eye"></ion-icon>
            <span class="desktop" style="font-size: 1rem;">Visualizar invitación</span>
          </button>
        </div>

        <div class="btn-controls">
          <a class="prev" href="javascript:void(0)">
            <ion-icon name="arrow-back-circle-outline"></ion-icon>
          </a>

          <p class="step-label">Siguiente paso</p>
          <p class="finish-label">Finalizar</p>

          <button class="next" type="submit">
            <ion-icon class="pulse" style="border-radius: 100%;" name="arrow-forward-circle-outline"></ion-icon>
          </button>

          <button class="finish" type="submit">
            <ion-icon name="checkmark" style="font-size: 1.5rem;"></ion-icon>

            Guardar cambios
          </button>
        </div>
      </div>
    </form>

    <!-- Page loading -->
    <?php include 'src/components/page-loading.php'; ?>
    <?php include 'src/components/page-progressbar.php'; ?>
  </main>

  <!-- Footer -->
  <?php include 'src/components/footer.php'; ?>

  <!-- Required scripts -->
  <?php include 'src/components/required-scripts.php'; ?>

  <!-- Datetimepicker -->
  <script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/moment.js/2.9.0/moment-with-locales.min.js"></script>
  <script src="<?= BASE_URL; ?>/src/plugins/datetimepicker/js/bootstrap-datetimepicker.min.js"></script>

  <!-- Slick slider -->
  <script src="<?= BASE_URL; ?>/src/plugins/slick-slider/slick.js"></script>

  <!-- CS File pickers -->
  <script src="<?= BASE_URL; ?>/src/plugins/cs-filepicker/cs-filepicker.js"></script>
  <script src="<?= BASE_URL; ?>/src/plugins/cs-multifilepicker/cs-multiple-filepicker.js"></script>

  <!-- Google maps -->
  <script src="<?= BASE_URL; ?>/src/plugins/google-maps/multiple-google-maps.js"></script>
  <script src="https://maps.googleapis.com/maps/api/js?key=<?= GOOGLE_MAPS_API_KEY; ?>&libraries=places&v=weekly"></script>

  <script>
    var sliderPosition = <?= $slider_position; ?>;
    var imagesForGallery = <?= json_encode($gallery); ?>;

    var invitationImages = {
      crImage: `<?= $invitation['CRImagen']; ?>`,
      rImage: `<?= $invitation['RImagen']; ?>`
    };

    var crPictureData = invitationImages.crImage ? {
      imageSrc: `${BASE_URL}/src/assets/images/invitations/<?= $invitation['CRImagen']; ?>`,
      imageName: 'Ceremonia Religiosa'
    } : null;

    var rPictureData = invitationImages.rImage ? {
      imageSrc: `${BASE_URL}/src/assets/images/invitations/<?= $invitation['RImagen']; ?>`,
      imageName: 'Ceremonia de Recepción'
    } : null;

    var individualPictureData = {
      imageSrc: `${BASE_URL}/src/assets/images/invitations/<?= $invitation['ImagenIndividual']; ?>`,
      imageName: 'Imagen Individual'
    };

    var familyPictureData = {
      imageSrc: `${BASE_URL}/src/assets/images/invitations/<?= $invitation['ImagenFamiliar']; ?>`,
      imageName: 'Imagen Familiar'
    };
  </script>

  <script src="<?= BASE_URL; ?>/src/js/edit-invitation.js"></script>

  <script>
    $('.templates-slider').on('beforeChange', function(event, slick, currentSlide, nextSlide) {
      const slider = $('.templates-slider label')[nextSlide + 1];
      const primaryColor = $(slider).attr('data-primaryColor');
      const secondaryColor = $(slider).attr('data-secondaryColor');

      $('#principalColor').val(primaryColor);
      $('#secondaryColor').val(secondaryColor);
    });

    $(window).on('load', () => $(`#<?= $template; ?>`).click());
  </script>
</body>

</html>