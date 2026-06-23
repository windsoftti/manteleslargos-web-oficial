<?php
include 'inc/public-session.php';

$event_type_slug = cleanStr($_GET['eventTypeSlug']);
$event_type_data = getEventDataBySlug($event_type_slug);

if (!$event_type_data) :
  header('location:' . BASE_URL);
  die();
endif;

$event_type_id  = $event_type_data['idTipoEvento'];
$supplier_types = getSupplierTypes($event_type_id);
$grid_colums    = getGridColsForSupplierTypes($supplier_types);
?>

<!DOCTYPE html>
<html lang="es">

<head>
  <?php include 'src/components/head.php'; ?>
  <link rel="stylesheet" href="<?= BASE_URL; ?>/src/css/pages/home.css">

  <style>
    @media screen and (min-width: 64em) {
      .event-types-section.small>div {
        width: 90%;
        grid-template-columns: <?= $grid_colums; ?>;
      }

      .event-types-section.small>div>a {
        margin-bottom: 1rem;
      }
    }
  </style>
</head>

<body class="navbar-white">
  <!-- Preloader -->
  <?php include 'src/components/preloader.php'; ?>

  <!-- Navbar -->
  <?php include 'src/components/navbar.php'; ?>

  <!-- Main -->
  <main class="main ">
    <section class="search-section">
      <div>
        <h2>REUNE EL MEJOR EQUIPO PARA PLANEAR TUS EVENTOS</h2>
        <h1>¿QUE QUIERES ORGANIZAR?</h1>

        <form id="global-search-form" method="GET" action="<?= BASE_URL; ?>/negocios" autocomplete="off">
          <input id="have-date" type="checkbox" name="haveDate" value="checked">
          <div class="checkbox">
            <label for="have-date">
              Tengo fecha del evento
            </label>
          </div>

          <div class="content">
            <div class="search">
              <ion-icon name="search"></ion-icon>
              <input id="searchTerm" name="search" placeholder="¿QUÉ BUSCAS?" type="text">

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

            <select id="searchStateIdByLabel" name="state">
              <?= businessStatesForSearch('ESTADO', 'Chiapas'); ?>
            </select>

            <select id="searchCityId" name="city">
              <?= businessCitysForSearch('CIUDAD', 'Chiapas', 'Tuxtla Gutiérrez'); ?>
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
      </div>
    </section>

    <section class="page-section event-types-section small">
      <h1>¿Qué tipo de proveedor necesitas?</h1>

      <div>
        <?php foreach ($supplier_types as $key => $row) : ?>
          <a href="<?= BASE_URL; ?>/<?= $event_type_slug; ?>/<?= $row['slug']; ?>">
            <img src="<?= BASE_URL; ?>/src/assets/images/suppliertypes/<?= $row['image']; ?>.png" alt="<?= $row['label']; ?>">
            <img class="hover" src="<?= BASE_URL; ?>/src/assets/images/suppliertypes/<?= $row['image']; ?>Dorado.png" alt="<?= $row['label']; ?>">
            <h5><?= $row['label']; ?></h5>
          </a>
        <?php endforeach; ?>
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

  <script src="<?= BASE_URL; ?>/src/plugins/jquery-ui-datepicker/init-default-datepicker.js"></script>
  <script src="<?= BASE_URL; ?>/src/plugins/cs-slider/cs-slider.js"></script>
</body>

</html>