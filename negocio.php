<?php
include 'inc/public-session.php';

$business_slug = cleanStr($_GET['businessSlug']);
$business_data = getBusinessDataBySlug($business_slug);

if (!$business_data) :
  header('location:' . BASE_URL . '/negocios?search=' . $business_slug);
  die;
endif;

$business_id        = $business_data['idSalon'];

$business_dates     = getBusinessDates($business_id);
$business_services  = getBusinessServices($business_id);
$business_amenities = getBusinessAmenities($business_id);
$business_packages  = getBusinessPackages($business_id);
$business_gallery   = getBusinessGallery($business_id, $business_data['Salon']);

increaseBusinessVisit($business_id);

$slide_to_show = $business_gallery['count'] + 1;

$webpage_meta_data['title']       = "$business_data[Salon] - Manteles largos";
$webpage_meta_data['description'] = "$business_data[Descripcion]";
$webpage_meta_data['image']       = setBusinessImage($business_data['Imagen']);
?>

<!DOCTYPE html>
<html lang="es">

<head>
  <?php include 'src/components/head.php'; ?>

  <link rel="stylesheet" href="<?= BASE_URL; ?>/src/plugins/cs-slider/cs-slider.css">
  <link rel="stylesheet" href="<?= BASE_URL; ?>/src/css/pages/business.css">
  <link rel="stylesheet" href="<?= BASE_URL; ?>/src/plugins/calendar/calendar.css">
  <link rel="stylesheet" href="<?= BASE_URL; ?>/src/plugins/jquery-ui-datepicker/jquery-ui.min.css">

  <!-- Slick slider -->
  <link rel="stylesheet" href="<?= BASE_URL; ?>/src/plugins/slick-slider/slick.css">
  <link rel="stylesheet" href="<?= BASE_URL; ?>/src/plugins/slick-slider/slick-theme.css">
  <link rel="stylesheet" href="<?= BASE_URL; ?>/src/plugins/slick-slider/slick-lightbox.css">
</head>

<body class="navbar-white">
  <!-- Preloader -->
  <?php include 'src/components/preloader.php'; ?>

  <!-- Navbar -->
  <?php include 'src/components/navbar.php'; ?>

  <!-- Main -->
  <main class="main">
    <section class="business-header">
      <div>
        <h1><?= $business_data['Salon']; ?></h1>

        <div class="social-links">
          <?php if ($business_data['Facebook']) : ?>
            <a target="_blank" href="<?= checkIfHaveHttps($business_data['Facebook']); ?>">
              <ion-icon name="logo-facebook"></ion-icon>
            </a>
          <?php endif; ?>

          <?php if ($business_data['Instagram']) : ?>
            <a target="_blank" href="<?= checkIfHaveHttps($business_data['Instagram']); ?>">
              <ion-icon name="logo-instagram"></ion-icon>
            </a>
          <?php endif; ?>
        </div>

        <div class="supplier-logo">
          <?php if ($business_data['Logo']) : ?>
            <img src="<?= setBusinessImage($business_data['Logo']); ?>">
          <?php endif; ?>

          <?php if (!$business_data['Logo']) : ?>
            <img src="<?= setBusinessImage($business_data['Imagen']); ?>">
          <?php endif; ?>
        </div>
      </div>

      <img src="<?= setBusinessImage($business_data['Imagen']); ?>" alt="<?= $business_data['Salon']; ?>">
    </section>

    <section id="business-info" class="business-section" data-business="<?= base64_encode(json_encode($business_data)); ?>" data-latitude="<?= $business_data['Latitud']; ?>" data-longitude="<?= $business_data['Longitud']; ?>">
      <section class="content-left" style="margin-bottom: 1rem;">
        <section class="slider-section" style="width: 100%;">
          <div class="business-slider">
            <div>
              <a target="_blank" href="<?= setBusinessImage($business_data['Imagen']); ?>">
                <img src="<?= setBusinessImage($business_data['Imagen']); ?>" alt="<?= $business_data['Salon']; ?>">
              </a>
            </div>

            <?= $business_gallery['gallery']; ?>
          </div>

          <?php /* 
          <div class="business-slider-nav">
          <div>
            <img src="<?= setBusinessImage($business_data['Imagen']); ?>" alt="<?= $business_data['Salon']; ?>">
          </div>

          <?= $business_gallery['gallery']; ?>
        </div>
          */ ?>
        </section>

        <div class="business-share mobile">
          <a id="mobile-share-business" class="bs-btn" href="javascript:void(0)">
            <ion-icon name="share-outline"></ion-icon>
            Compartir
          </a>
        </div>

        <div id="business-share" class="business-share desktop">
          <div class="bs-icons">
            <a target="_blank" href="http://www.facebook.com/sharer/sharer.php?p[url]=<?= $webpage_meta_data['currentURL']; ?>&p[title]=<?= $business_data['Salon']; ?>">
              <ion-icon name="logo-facebook"></ion-icon>
            </a>

            <a target="_blank" href="http://twitter.com/share?text=<?= $business_data['Descripcion']; ?>&url=<?= $webpage_meta_data['currentURL']; ?>&hashtags=manteleslargos.com">
              <ion-icon name="logo-twitter"></ion-icon>
            </a>

            <a target="_blank" href="https://telegram.me/share/url?url=<?= $webpage_meta_data['currentURL']; ?>">
              <ion-icon name="paper-plane"></ion-icon>
            </a>
          </div>

          <a id="share-bs-btn" class="bs-btn" href="javascript:void(0)">
            <ion-icon name="share-outline"></ion-icon>
            Compartir
          </a>
        </div>

        <nav class="business-navigation">
          <ul>
            <?php if ($business_data['Descripcion']) : ?>
              <li>
                <a href="#descripcion">DESCRIPCIÓN</a>
              </li>
            <?php endif; ?>

            <?php if ($business_services) : ?>
              <li>
                <a href="#servicios">SERVICIOS</a>
              </li>
            <?php endif; ?>

            <?php if ($business_amenities) : ?>
              <li>
                <a href="#amenidades">AMENIDADES</a>
              </li>
            <?php endif; ?>

            <?php if ($business_packages) : ?>
              <li>
                <a href="#paquetes">PAQUETES</a>
              </li>
            <?php endif; ?>
          </ul>
        </nav>

        <div class="business-information">
          <div id="descripcion">
            <h2>Descripción</h2>
            <p><?= $business_data['Descripcion']; ?></p>
          </div>

          <?php if ($business_services) : ?>
            <div id="servicios">
              <h2>Servicios</h2>

              <?= $business_services; ?>
            </div>
          <?php endif; ?>

          <?php if ($business_amenities) : ?>
            <div id="amenidades">
              <h2>Amenidades</h2>

              <?= $business_amenities; ?>
            </div>
          <?php endif; ?>

          <?php if ($business_packages) : ?>
            <div id="paquetes">
              <h2>Paquetes</h2>

              <ul class="business-packages">
                <?php foreach ($business_packages as $key => $package) : ?>
                  <li class="recomendado-<?= $package['MasContratado'] ?>">
                    <a class="package-header" href="javascript:void(0)">
                      <?php if ($package['MasContratado'] == 'Si') : ?>
                        <div>
                          <span class="text-left">
                            <?= $package['Paquete'] ?> <b>(El mas recomendado)</b>&nbsp;

                            <ion-icon name="star-outline" style="font-size: 1rem;"></ion-icon>
                            <ion-icon name="star-outline" style="font-size: 1rem;"></ion-icon>
                            <ion-icon name="star-outline" style="font-size: 1rem;"></ion-icon>
                          </span>
                        </div>

                      <?php else : ?>
                        <?= $package['Paquete'] ?>
                      <?php endif ?>
                    </a>

                    <div class="package-body">
                      <div class="price">
                        <h2>$<?= number_format($package['Precio'], 2); ?></h2>

                        <!-- <a href="javascript:void(0)">
                          <ion-icon name="arrow-redo-outline"></ion-icon>
                          Compartir
                        </a> -->
                      </div>

                      <div class="modality">
                        <h2><?= $package['Orientacion']; ?></h2>
                        <h3>Catgorías: <?= $package['categories']; ?></h3>
                      </div>

                      <?php if ($business_data['MostrarCalendario'] === 'Si') : ?>
                        <a class="btn-request-package" data-packageId="<?= $package['idPaquete']; ?>" href="javascript:void(0)">
                          SOLICITAR PAQUETE
                        </a>
                      <?php endif; ?>

                      <div class="description">
                        <h2>Descripción</h2>
                        <?= $package['Descripcion']; ?>
                      </div>
                    </div>
                  </li>
                <?php endforeach; ?>
              </ul>
            </div>
          <?php endif; ?>
          <script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=ca-pub-8395752374471835" crossorigin="anonymous"></script>
          <!-- ads horizontal -->
          <ins class="adsbygoogle" style="display:block" data-ad-client="ca-pub-8395752374471835" data-ad-slot="1893954996" data-ad-format="auto" data-full-width-responsive="true"></ins>
          <script>
            (adsbygoogle = window.adsbygoogle || []).push({});
          </script>
        </div>
      </section>

      <section class="content-right" style="padding-top: 0;">
        <?php if ($business_data['Celular']) : ?>
          <div class="map" style="margin-top: 0rem;">
            <button class="btn btn-large btn-block btn-primary pulse event-counter" data-event="telefono-visto" data-toggle="modal" data-target="show-phone-modal" type="button">
              <ion-icon name="call-outline"></ion-icon> VER TELÉFONO
            </button>
          </div>
        <?php endif; ?>

        <div class="tabs" style="margin-top:10px;">
          <div class="tabs-header">
            <a class="active" href="javascript:void(0)" data-content="tab-request-quote">
              SOLICITAR COTIZACIÓN
            </a>

            <a href="javascript:void(0)" data-content="tab-direct-contact">
              CONTÁCTO DIRECTO
            </a>
          </div>

          <div class="tabs-body">
            <div id="tab-request-quote" class="active">
              <div id="calendar"></div>
              <input id="calendarStatus" value="<?= $business_data['MostrarCalendario'] === 'Si' ? '' : 'disabled' ?>" type="hidden">

              <div class="cs-calendar">
                <ul class="cs-calendar-stats">
                  <li>DISPONIBLE</li>
                  <li class="with-spaces">CON ESPACIOS</li>
                  <li class="occupied">NO DISPONIBLE</li>
                </ul>
              </div>
            </div>

            <div id="tab-direct-contact">
              <form id="direct-contact-form" autocomplete="off">
                <div class="form-group">
                  <label for="contactName"><span>*</span>Nombre completo</label>
                  <input id="contactName" type="text" name="contactName" required>
                </div>

                <div class="form-group">
                  <label for="contactEmail"><span>*</span>Correo</label>
                  <input id="contactEmail" type="email" name="contactEmail" required>
                </div>

                <div class="form-group">
                  <label for="contactPhone"><span>*</span>Teléfono</label>
                  <input id="contactPhone" class="number-input" type="text" name="contactPhone" required>
                </div>

                <div class="form-group">
                  <label for="contactMessage"><span>*</span>Mensaje</label>
                  <textarea id="contactMessage" name="contactMessage" rows="5">Hola, vi tu información en manteleslargos.com y me gustaría cotizar sus servicios.</textarea>
                </div>

                <input name="businessId" value="<?= $business_id; ?>" type="hidden">

                <button class="btn btn-block btn-primary" type="submit">
                  Enviar mensaje
                </button>

                <div id="direct-contact-alert" class="w-100"></div>
              </form>
            </div>
          </div>
        </div>

        <div class="map">
          <p>
            <?= $business_data['Direccion']; ?>
            <br>
            <a target="_blank" href="http://www.google.com/maps/place/<?= $business_data['Latitud']; ?>,<?= $business_data['Longitud']; ?>?q=<?= $business_data['Direccion']; ?>">Abrir en google maps</a>
          </p>

          <!-- <div id="map"></div> -->
          <?php

          $latitude   = $business_data['Latitud'];
          $longitude  = $business_data['Longitud'];
          $iframeUrl  = "https://maps.google.com/maps?q={$latitude},{$longitude}&hl=es&z=14&amp;output=embed";
          ?>
          <iframe
            src="<?= $iframeUrl; ?>"
            style="border:0; width: 100%; min-height: 300px"
            allowfullscreen=""
            loading="lazy"
            referrerpolicy="no-referrer-when-downgrade"></iframe>
        </div>
        <div class="map" style="margin-top: 10px;">
          <strong>¿ESTE NEGOCIO ES TUYO?</strong>
          Si no tienes control de esta cuenta, contáctanos al <a href="https://api.whatsapp.com/send?phone=5219611233856&text=He%20visto%20el%20negocio %20<?= $business_data['Salon']; ?>%20en%20Manteles%20Largos%20y%20soy%20en%20propietario." target="_blank">961 123 38 56</a> o da click en el sig. botón.
          <a href="https://api.whatsapp.com/send?phone=5219611233856&text=He%20visto%20el%20negocio%20<?= $business_data['Salon']; ?>%20en%20Manteles%20Largos%20y%20soy%20en%20propietario." class="btn btn-large btn-block btn-success" target="_blank" style="color:#fff;" type="button">
            Contactar a soporte
          </a>
        </div>


        <div style="
          display: flex;
          width: 100%;
          flex-direction: column;
          border: 0.15rem solid var(--primary-color);
          padding: 0.5em;
          margin-top: 1rem;
          border-radius: 0.8rem;
        ">
          <form id="global-search-form" method="GET" action="<?= BASE_URL; ?>/negocios" autocomplete="off">
            <div class="form-group">
              <label for="searchTerm">Nueva búsqueda</label>
              <div class="input-group">
                <input id="searchTerm" name="searchTerm" placeholder="¿Qué buscas?" value="<?= $search_term != '' ? $search_term : $supplier_type; ?>" type="text">

                <div class="prepend">
                  <ion-icon name='search'></ion-icon>
                </div>

                <div class="search-supplier-types">
                  <a class="autocomplete-supplier-type-item" data-value="Salones y Jardines para eventos" data-slug="salones-y-jardines-para-eventos" href="javascript:void(0)">
                    <img class="img-autocomplete" src="https://manteleslargos.com/src/assets/images/suppliertypes/salones.png">
                    <img class="img-autocomplete-hover" src="https://manteleslargos.com/src/assets/images/suppliertypes/salonesDorado.png">

                    <div>
                      Salones y Jardines para eventos
                    </div>
                  </a>
                  <!-- <?= getSupplierTypesForAutocomplete(); ?> -->
                </div>
              </div>
            </div>

            <div class="form-group">
              <label for="searchStateIdByLabel">Estado</label>
              <select id="searchStateIdByLabel" name="stateId">
                <?= businessStatesForSelect('ESTADO', $business_data['idEstado'], 'return-name'); ?>
              </select>
            </div>

            <div id="searchCity-container" class="form-group" <?= $city_display; ?>>
              <label for="searchCityId">Ciudad</label>
              <select id="searchCityId" name="cityId">
                <?= businessCitysForSelect('CIUDAD', $business_data['idEstado'], $business_data['idCiudad'], 'return-name'); ?>
              </select>
            </div>

            <div class="form-group">
              <label for="searchDate">Disponibilidad</label>
              <input id="searchDate" class="datepicker" name="date" type="text" placeholder="Fecha" value="<?= $have_date ? $date : ''; ?>">
            </div>

            <div class="form-group">
              <button class="btn btn-black btn-block" type="submit">
                Buscar
              </button>
            </div>
          </form>
        </div>
      </section>
    </section>

    <!-- Modal for login and register -->
    <?php include 'src/modals/login-register.php'; ?>

    <!-- Modal for login, register and request quote -->
    <?php include 'src/modals/business.php'; ?>

    <!-- Page loading -->
    <?php include 'src/components/page-loading.php'; ?>
  </main>

  <!-- Footer -->
  <?php include 'src/components/footer.php'; ?>

  <!-- Required scripts -->
  <?php include 'src/components/required-scripts.php'; ?>

  <script src="<?= BASE_URL; ?>/src/plugins/cs-slider/cs-slider.js"></script>
  <script src="<?= BASE_URL; ?>/src/plugins/calendar/calendar.js"></script>

  <!-- Slick slider -->
  <script src="<?= BASE_URL; ?>/src/plugins/slick-slider/slick.js"></script>
  <script src="<?= BASE_URL; ?>/src/plugins/slick-slider/slick-lightbox.js"></script>

  <script src="<?= BASE_URL; ?>/src/plugins/jquery-ui-datepicker/jquery-ui.min.js"></script>
  <script src="<?= BASE_URL; ?>/src/plugins/jquery-ui-datepicker/init-default-datepicker.js"></script>

  <!-- <script src="<?= BASE_URL; ?>/src/plugins/google-maps/business-map.js"></script> -->
  <!-- <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBcXIXiRSirvWVofs7wRolh-WjSSUF4jIE&callback=initMap&v=weekly" defer></script> -->

  <script>
    var businessDates = <?= json_encode($business_dates); ?>;
  </script>
  <script src="<?= BASE_URL; ?>/src/js/business.js"></script>

  <script>
    $('.business-slider').slick({
      slidesToShow: 1,
      slidesToScroll: 1,
      arrows: true,
      //fade: true,
      //asNavFor: '.business-slider-nav'
    });
    /* $('.business-slider-nav').slick({
      slidesToShow: <?= $slide_to_show; ?>,
      slidesToScroll: 1,
      asNavFor: '.business-slider',
      dots: true,
      centerMode: true,
      focusOnSelect: true
    }); */

    $('.business-slider').slickLightbox({
      useHistoryApi: 'true'
    });

    $('#share-bs-btn').on('click', function() {
      const isOpen = $('.business-share').hasClass('open');

      if (isOpen) $('.business-share').removeClass('open');
      if (!isOpen) $('.business-share').addClass('open');
    });

    $('#mobile-share-business').on('click', () => window.navigator.share({
      title: `<?= $business_data['Salon']; ?>`,
      text: `<?= $business_data['Descripcion']; ?>`,
      url: '<?= $webpage_meta_data['currentURL']; ?>'
    }));
  </script>
</body>

</html>