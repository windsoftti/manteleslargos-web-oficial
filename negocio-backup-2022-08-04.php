<?php
include 'inc/public-session.php';

$business_slug      = cleanStr($_GET['businessSlug']);
$event_type_slug    = cleanStr($_GET['eventTypeSlug']);
$supplier_type_slug = cleanStr($_GET['supplierTypeSlug']);

$business_data      = getBusinessDataBySlug($business_slug);
$event_type_data    = getEventDataBySlug($event_type_slug);
$supplier_type_data = getSupplierDataBySlug($supplier_type_slug);

if (!$business_data || !$event_type_data || !$supplier_type_data) :
  header('location:' . BASE_URL . '/negocios');
  die;
endif;

$business_id        = $business_data['idSalon'];
$event_type_id      = $event_type_data['idTipoEvento'];
$supplier_type_id   = $supplier_type_data['idTipoProveedor'];

$business_dates     = getBusinessDates($business_id);
$business_services  = getBusinessServices($business_id);
$business_amenities = getBusinessAmenities($business_id);
$business_packages  = getBusinessPackages($business_id);

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
  <link rel="stylesheet" href="<?= BASE_URL; ?>/src/plugins/cs-calendar/cs-calendar.css">
  <link rel="stylesheet" href="<?= BASE_URL; ?>/src/plugins/jquery-ui-datepicker/jquery-ui.min.css">
</head>

<body class="navbar-white">
  <!-- Preloader -->
  <?php include 'src/components/preloader.php'; ?>

  <!-- Navbar -->
  <?php include 'src/components/navbar.php'; ?>

  <!-- Main -->
  <main class="main">
    <section class="global-search-section">
      <form id="global-search-form" method="POST" action="<?= BASE_URL; ?>/negocios" autocomplete="off">
        <input id="have-date" type="checkbox" name="haveDate" value="checked">
        <div class="checkbox">
          <label for="have-date">
            Tengo fecha del evento
          </label>
        </div>

        <div class="content">
          <div class="search">
            <ion-icon name="search"></ion-icon>
            <input id="searchTerm" name="searchTerm" placeholder="¿QUÉ BUSCAS?" type="text">
            <div class="search-supplier-types">
              <?= getSupplierTypesForAutocomplete(); ?>
            </div>
          </div>

          <select id="searchStateId" name="stateId">
            <?= statesForSelect('ESTADO', $business_data['idEstado']); ?>
          </select>

          <select id="searchCityId" name="cityId">
            <?= citysForSelect('CIUDAD', $business_data['idEstado'], $business_data['idCiudad']); ?>
          </select>

          <div class="dateinput">
            <ion-icon name="calendar-outline"></ion-icon>
            <input id="searchDate" class="datepicker" name="date" placeholder="¿CUANDO?" type="text">
          </div>

          <button type="submit">
            BUSCAR
          </button>
        </div>
      </form>
    </section>

    <section class="breadcrumbs">
      <ul>
        <li>
          <a href=".">Home</a>
        </li>

        <li>
          <a href="<?= BASE_URL; ?>/negocios">Negocios</a>
        </li>

        <li>
          <a href="<?= BASE_URL; ?>/negocios/<?= $event_type_slug; ?>"><?= $event_type_data['TipoEvento']; ?></a>
        </li>

        <li>
          <a href="<?= BASE_URL; ?>/negocios/<?= $event_type_slug; ?>/<?= $supplier_type_slug; ?>"><?= $supplier_type_data['TipoProveedor']; ?></a>
        </li>

        <li>
          <a href="<?= BASE_URL; ?>/negocios/<?= $event_type_slug; ?>/<?= $supplier_type_slug; ?>/<?= $business_slug; ?>"><?= $business_data['Salon']; ?></a>
        </li>
      </ul>

      <div>
        <img src="<?= BASE_URL; ?>/src/assets/images/eventtypes/manteleslargos_quince_hover.png" alt="<?= $event_type_data['TipoEvento']; ?>">
        <h1><?= $event_type_data['TipoEvento']; ?></h1>
      </div>

      <!-- <h2><?= $supplier_type_data['TipoProveedor']; ?></h2> -->

      <h2><?= $business_data['Salon']; ?></h2>
    </section>

    <section class="business-section" data-business="<?= base64_encode(json_encode($business_data)); ?>">
      <section class="content-left">
        <section class="slider-section" style="width: 100%;">
          <div class="cs-slider h40 thumbs animation" data-interval="5000">
            <div class="imgs" style="width: 100%;">
              <img src="<?= setBusinessImage($business_data['Imagen']); ?>" alt="<?= $business_data['Salon']; ?>">
              <?= getBusinessGallery($business_data['idSalon'], $business_data['Salon']); ?>
            </div>

            <div class="dots"></div>

            <div class="thumbs"></div>
          </div>
        </section>

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
                  <li class="<?= $key == 0 ? 'open' : ''; ?>">
                    <a class="package-header" href="javascript:void(0)">
                      <?= $package['Paquete'] ?>
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

                      <a class="btn-request-package" data-packageId="<?= $package['idPaquete']; ?>" href="javascript:void(0)">
                        SOLICITAR PAQUETE
                      </a>

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
        </div>
      </section>

      <section class="content-right">
        <div class="tabs">
          <div class="tabs-header">
            <a class="active" href="javascript:void(0)" data-content="tab-request-quote">
              SOLICITAR COTIZACIÓN
            </a>

            <a href="javascript:void(0)" data-content="tab-direct-contact">
              CONTACTO DIRECTO
            </a>
          </div>

          <div class="tabs-body">
            <div id="tab-request-quote" class="active">
              <div class="cs-calendar"></div>

              <ul class="cs-calendar-stats">
                <li class="occupied">NO DISPONIBLE</li>
                <li>DISPONIBLE</li>
                <li class="with-spaces">PARCIALMENTE DISPONIBLE</li>
              </ul>
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
                  <textarea id="contactMessage" name="contactMessage" rows="5">Hola, vi tu salón en manteleslargos.com y me gustaría contratar sus servicios.</textarea>
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

          <div id="map"></div>
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
  <script src="<?= BASE_URL; ?>/src/plugins/cs-calendar/cs-calendar.js"></script>

  <script src="<?= BASE_URL; ?>/src/plugins/jquery-ui-datepicker/jquery-ui.min.js"></script>
  <script src="<?= BASE_URL; ?>/src/plugins/jquery-ui-datepicker/init-default-datepicker.js"></script>

  <script src="<?= BASE_URL; ?>/src/plugins/google-maps/google-maps.js"></script>
  <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBcXIXiRSirvWVofs7wRolh-WjSSUF4jIE&callback=initMap&v=weekly" defer></script>

  <script>
    var businessDates = <?= json_encode($business_dates); ?>;
  </script>
  <script src="<?= BASE_URL; ?>/src/js/business.js"></script>
</body>

</html>