<?php
include 'inc/public-session.php';

$have_date          = cleanStr($_GET['haveDate']);
$search_term        = cleanStr($_GET['search']);
$state              = cleanStr($_GET['state']);
$city_id            = cleanStr($_GET['city']);
$date               = cleanStr($_GET['date']);

$city_display       = $state != '' ? '' : 'style="display: none;"';

$event_type_id      = '';
$event_type_slug    = cleanStr($_GET['eventTypeSlug']);
$event_type_data    = getEventDataBySlug($event_type_slug);

$supplier_type_id   = '';
$supplier_type      = '';
$supplier_type_slug = cleanStr($_GET['supplierTypeSlug']);
$supplier_type_data = getSupplierDataBySlug($supplier_type_slug);

if ($event_type_data)     $event_type_id    = $event_type_data['idTipoEvento'];

if ($supplier_type_data) :
  $supplier_type_id = $supplier_type_data['idTipoProveedor'];
  $supplier_type    = $supplier_type_data['TipoProveedor'];
endif;

$max_capacity       = getMaxBusinessCapacity();
$max_price          = getMaxBusinessPrice();
?>

<!DOCTYPE html>
<html lang="es">

<head>
  <?php include 'src/components/head.php'; ?>
  <link rel="stylesheet" href="<?= BASE_URL; ?>/src/plugins/jquery-ui-datepicker/jquery-ui.min.css">
  <link rel="stylesheet" href="<?= BASE_URL; ?>/src/css/pages/listing.css">
</head>

<body class="navbar-white">
  <!-- Preloader -->
  <?php include 'src/components/preloader.php'; ?>

  <!-- Navbar -->
  <?php include 'src/components/navbar.php'; ?>

  <!-- Main -->
  <main class="main">
    <section class="img-section">
      <img class="img-mobile" src="<?= BASE_URL; ?>/src/assets/images/listing/top/<?= $event_type_data['Imagen']; ?>_mobile.png" alt="<?= $event_type_data['TipoEvento']; ?>">
      <img class="img-desktop" src="<?= BASE_URL; ?>/src/assets/images/listing/top/<?= $event_type_data['Imagen']; ?>_desktop.png" alt="<?= $event_type_data['TipoEvento']; ?>">
    </section>

    <section class="breadcrumbs center">
      <h1><?= $search_term . ' ' . $city_id . ' ' . $state ?> </h1>
      <?php /* ?>
      <ul>
        <li>
          <a href="<?= BASE_URL; ?>">Inicio</a>
        </li>

        <li>
          <a href="<?= BASE_URL; ?>/negocios">Negocios</a>
        </li>

        <?php if ($event_type_data) : ?>
          <li>
            <a href="<?= BASE_URL; ?>/<?= $event_type_slug; ?>"><?= $event_type_data['TipoEvento']; ?></a>
          </li>
        <?php endif; ?>

        <?php if ($supplier_type_data) : ?>
          <li>
            <a href="<?= BASE_URL; ?>/<?= $event_type_slug; ?>/<?= $supplier_type_slug; ?>"><?= $supplier_type_data['TipoProveedor']; ?></a>
          </li>
        <?php endif; ?>
      </ul>

      <?php if ($event_type_data) : ?>
        <div>
          <img src="<?= BASE_URL; ?>/src/assets/images/eventtypes/<?= $event_type_data['Imagen']; ?>_hover.png" alt="<?= $event_type_data['TipoEvento']; ?>">
          <h1><?= $event_type_data['TipoEvento']; ?></h1>
        </div>
      <?php endif; ?>

      <?php if ($supplier_type_data) : ?>
        <h2><?= $supplier_type_data['TipoProveedor']; ?></h2>
      <?php endif; */ ?>
    </section>

    <?php
    /*
    <section class="global-search-section">
      <form id="global-search-form" method="POST" action="<?= BASE_URL; ?>/negocios" autocomplete="off">
        <input id="have-date" type="checkbox" name="haveDate" value="checked" <?= $have_date; ?>>
        <div class="checkbox">
          <label for="have-date">
            Tengo fecha del evento
          </label>
        </div>

        <div class="content">
          <div class="search">
            <ion-icon name="search"></ion-icon>
            <input id="searchTerm" name="searchTerm" placeholder="¿QUÉ BUSCAS?" value="<?= $search_term; ?>" type="text">
            <div class="search-supplier-types">
              <?= getSupplierTypesForAutocomplete(); ?>
            </div>
          </div>

          <select id="searchStateId" name="stateId">
            <?= statesForSelect('ESTADO', $state); ?>
          </select>

          <select id="searchCityId" name="cityId" <?= $city_display; ?>>
            <?php if ($state) : ?>
              <?= citysForSelect('CIUDAD', $state, $city_id); ?>
            <?php endif; ?>
          </select>

          <div class="dateinput">
            <ion-icon name="calendar-outline"></ion-icon>
            <input id="searchDate" class="datepicker" name="date" placeholder="¿CUANDO?" value="<?= $have_date ? $date : ''; ?>" type="text">
          </div>

          <button type="submit">
            BUSCAR
          </button>
        </div>
      </form>
    </section>
    */
    ?>

    <section id="listing" class="listing">

      <div class="listing-header">
        <p class="results">
          <span id="results">0</span> RESULTADOS
        </p>
        <button class="btn-filters">
          Filtros <ion-icon name="options-outline"></ion-icon>
        </button>

        <label class="map-action">
          <ion-icon name="map-outline"></ion-icon> Mapa

          <label class="switch">
            <input id="map-mode" type="checkbox" value="true">
            <span class="slider"></span>
          </label>
        </label>
      </div>

      <div class="listing-body">
        <div id="listing-filters" class="filters">
          <div>
            <div class="top">
              <h1>Filtros</h1>

              <a class="btn-filters" href="javascript:void(0)">&times;</a>
            </div>

            <form id="businesses-filter" class="bottom" autocomplete="off">
              <div style="
                display: flex;
                width: 100%;
                flex-direction: column;
                border: 0.15rem solid var(--primary-color);
                padding: 0.5em;
                margin-bottom: 1rem;
                border-radius: 0.8rem;
              ">
                <div class="form-group">
                  <label for="eventTypeId">Tipo de evento</label>
                  <select id="eventTypeId" name="eventTypeId">
                    <?= eventTypesForSelect('Todos los tipos de eventos', $event_type_id) ?>
                  </select>
                </div>

                <div class="form-group">
                  <label for="searchTerm">Buscar aqui</label>
                  <div class="input-group">
                    <input id="searchTerm" name="searchTerm" placeholder="¿Qué buscas?" value="<?= $search_term != '' ? $search_term : $supplier_type; ?>" type="text">

                    <div class="prepend">
                      <ion-icon name='search'></ion-icon>
                    </div>

                    <div class="search-supplier-types">
                      <!-- <a class="autocomplete-supplier-type-item" data-value="Salones y Jardines para eventos" data-slug="salones-y-jardines-para-eventos" href="javascript:void(0)">
                        <img class="img-autocomplete" src="https://manteleslargos.com/src/assets/images/suppliertypes/salones.png">
                        <img class="img-autocomplete-hover" src="https://manteleslargos.com/src/assets/images/suppliertypes/salonesDorado.png">

                        <div>
                          Salones y Jardines para eventos
                        </div>
                      </a> -->
                      <?= getSupplierTypesForAutocomplete('', $event_type_id); ?>
                    </div>
                  </div>
                </div>

                <div class="form-group">
                  <label for="searchStateId">Estado</label>
                  <select id="searchStateId" name="stateId">
                    <?= businessStatesForSearch('Todos los estados', $state, true); ?>
                  </select>
                </div>

                <div id="searchCity-container" class="form-group" <?= $city_display; ?>>
                  <label for="searchCityId">Ciudad</label>
                  <select id="searchCityId" name="cityId">
                    <?php if ($state) : ?>
                      <?= businessCitysForSearch('Todas las ciudades', $state, $city_id, true); ?>
                    <?php endif; ?>
                  </select>
                </div>

                <div class="form-group">
                  <label for="date">Disponibilidad</label>
                  <input id="date" name="date" type="text" placeholder="Fecha" value="<?= $have_date ? $date : ''; ?>">
                </div>
              </div>

              <?php /* if ($event_type_slug && $supplier_type_slug) : */ ?>
              <!--  <div class="range-slider" format='text' symbol=' Per.' symbolPosition='right'>
                <label for="capacity">Capacidad</label>
                <input id="capacity" name="capacity" type="range" min="0" max="<?= $max_capacity; ?>" value="<?= $max_capacity; ?>">
                </div> -->

              <h3 class="heading">Capacidad</h3>
              <div class="checkbox-group mb" style="flex-direction: column;align-items: flex-start;">
                <div>
                  <input id="capacity-01" name="capacity[]" value="[0,99]" type="checkbox">
                  <label for="capacity-01">0 - 99</label>
                </div>

                <div>
                  <input id="capacity-02" name="capacity[]" value="[100,199]" type="checkbox">
                  <label for="capacity-02">100 - 199</label>
                </div>

                <div>
                  <input id="capacity-03" name="capacity[]" value="[2000,299]" type="checkbox">
                  <label for="capacity-03">200 - 299</label>
                </div>

                <div>
                  <input id="capacity-04" name="capacity[]" value="[300,399]" type="checkbox">
                  <label for="capacity-04">300 - 399</label>
                </div>

                <div>
                  <input id="capacity-0" name="capacity[]" value="[400,0]" type="checkbox">
                  <label for="capacity-0">400+</label>
                </div>
              </div>

              <!-- <div class="range-slider" format='money'>
                <label for="price">Precio</label>
                <input id="price" name="price" type="range" min="0" max="<?= $max_price; ?>" value="<?= $max_price; ?>">
                </div> -->

              <h3 class="heading">Precio</h3>
              <input id="modality" name="modality" value="Por persona" type="hidden">

              <div class="tabs">
                <div class="tabs-header" style="display: flex;">
                  <a class="modality active" href="javascript:void(0)" data-content="tab-for-person" data-value="Por persona" style="flex: 1;">
                    Por persona
                  </a>

                  <a class="modality" href="javascript:void(0)" data-content="tab-for-rent" data-value="Por evento" style="flex: 1;">
                    <?= $supplier_type_slug === 'salones-y-jardines-para-eventos' ? 'Por renta' : 'Por evento'; ?>
                  </a>
                </div>

                <div class="tabs-body">
                  <div id="tab-for-person" class="active">
                    <div class="checkbox-group mb" style="flex-direction: column;align-items: flex-start;">
                      <div>
                        <input id="priceForPerson-01" name="priceForPerson[]" value="[0,250]" type="checkbox">
                        <label for="priceForPerson-01">Menos de $250</label>
                      </div>

                      <div>
                        <input id="priceForPerson-02" name="priceForPerson[]" value="[250, 500]" type="checkbox">
                        <label for="priceForPerson-02">$250 - $500</label>
                      </div>

                      <div>
                        <input id="priceForPerson-03" name="priceForPerson[]" value="[500,0]" type="checkbox">
                        <label for="priceForPerson-03">Mas de $500</label>
                      </div>
                    </div>
                  </div>

                  <div id="tab-for-rent">
                    <div class="checkbox-group mb" style="flex-direction: column;align-items: flex-start;">
                      <div>
                        <input id="priceForRent-03" name="priceForRent[]" value="[0,500]" type="checkbox">
                        <label for="priceForRent-03">Menos de $500</label>
                      </div>

                      <div>
                        <input id="priceForRent-04" name="priceForRent[]" value="[500,10000]" type="checkbox">
                        <label for="priceForRent-04">$500- $10,000</label>
                      </div>

                      <div>
                        <input id="priceForRent-05" name="priceForRent[]" value="[10000,0]" type="checkbox">
                        <label for="priceForRent-05">Mas de $10,000</label>
                      </div>
                    </div>
                  </div>
                </div>
              </div>

              <?php if ($supplier_type_slug === 'salon-y-jardines-para-eventos') : ?>
                <h3 class="heading">Servicios</h3>
                <div class="checkbox-group between mb">
                  <?= getServicesCheckbox(); ?>
                </div>

                <h3 class="heading">Amenidades</h3>
                <div class="checkbox-group between mb">
                  <?= getAmenitiesCheckbox(); ?>
                </div>
              <?php endif; ?>

              <!-- <button class="btn btn-block btn-primary" type="submit">
                VER RESULTADOS
                </button> -->
              <?php /* endif; */ ?>
            </form>
          </div>
        </div>

        <div class="listing-content">
          <div id="list-businesses" class="listing"></div>

          <div id="pagination" class="pagination"></div>
        </div>

        <div class="map">
          <div id="map"></div>
        </div>
      </div>
    </section>

    <!-- Modal for login and register -->
    <?php include 'src/modals/login-register.php'; ?>

    <!-- Page loading -->
    <?php include 'src/components/page-loading.php'; ?>
  </main>

  <!-- Footer -->
  <?php include 'src/components/footer.php'; ?>

  <!-- Required scripts -->
  <?php include 'src/components/required-scripts.php'; ?>

  <script src="<?= BASE_URL; ?>/src/plugins/jquery-ui-datepicker/jquery-ui.min.js"></script>
  <script src="<?= BASE_URL; ?>/src/plugins/jquery-ui-datepicker/init-default-datepicker.js"></script>

  <script src="<?= BASE_URL; ?>/src/plugins/google-maps/google-maps.js"></script>
  <script src="https://maps.googleapis.com/maps/api/js?key=<?= GOOGLE_MAPS_API_KEY; ?>&callback=initMap&v=weekly" defer></script>
  <script src="<?= BASE_URL; ?>/src/js/businesses.js"></script>

  <script>
    $('#eventTypeId').on('change', function() {
      showPageLoading();

      const slug = $('option:selected', this).attr('data-slug');

      window.location = `${BASE_URL}/negocios${slug != undefined ? `/${slug}` : ``}`;
    });

    $('.search-supplier-types a').on('click', function(e) {
      e.stopPropagation();
      console.log('clickeds')
      loadBusinesses(1);
    });

    $(document).on('click', '.cs-autocomplete-item', function(e) {
      e.stopPropagation();
      const type = $(this).attr('data-type');
      console.log('clicked')
      if (type == 'header') loadBusinesses(1);
    });
  </script>
</body>

</html>