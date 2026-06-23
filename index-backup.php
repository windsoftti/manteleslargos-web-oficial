<?php include 'inc/public-session.php'; ?>

<!DOCTYPE html>
<html lang="es">

<head>
  <?php include 'src/components/head.php'; ?>

  <link rel="stylesheet" href="<?= BASE_URL; ?>/src/plugins/jquery-ui-datepicker/jquery-ui.min.css">
  <link rel="stylesheet" href="<?= BASE_URL; ?>/src/plugins/cs-slider/cs-slider.css">
  <link rel="stylesheet" href="<?= BASE_URL; ?>/src/css/pages/home.css">
</head>

<body>
  <!-- Preloader -->
  <?php include 'src/components/preloader.php'; ?>

  <!-- Header -->
  <?php include 'src/components/header.php'; ?>

  <!-- Navbar -->
  <?php include 'src/components/navbar.php'; ?>

  <!-- Main -->
  <main class="main">
    <section class="slider-section">
      <div class="cs-slider">
        <div class="imgs">
          <img src="<?= BASE_URL; ?>/src/assets/images/banner/manteleslargos_002.jpg">
          <img src="<?= BASE_URL; ?>/src/assets/images/banner/manteleslargos_001.jpg">
          <img src="<?= BASE_URL; ?>/src/assets/images/banner/manteleslargos_003.jpg">
          <img src="<?= BASE_URL; ?>/src/assets/images/banner/manteleslargos_004.jpg">
          <img src="<?= BASE_URL; ?>/src/assets/images/banner/manteleslargos_005.jpg">
          <img src="<?= BASE_URL; ?>/src/assets/images/banner/manteleslargos_006.jpg">
          <img src="<?= BASE_URL; ?>/src/assets/images/banner/manteleslargos_007.jpg">
          <img src="<?= BASE_URL; ?>/src/assets/images/banner/manteleslargos_008.jpg">
          <img src="<?= BASE_URL; ?>/src/assets/images/banner/manteleslargos_009.jpg">
        </div>

        <div class="dots"></div>
      </div>
    </section>

    <section class="search-section">
      <div>
        <h2>REUNE EL MEJOR EQUIPO PARA PLANEAR TUS EVENTOS</h2>
        <h1>¿QUE QUIERES ORGANIZAR?</h1>

        <form method="POST" action="<?= BASE_URL; ?>/negocios" autocomplete="off">
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
            </div>

            <select id="searchStateId" name="stateId">
              <?= statesForSelect('ESTADO'); ?>
            </select>

            <select id="searchCityId" name="cityId" style="display: none;">
              <option value="">CIUDAD</option>
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

    <section class="page-section event-types-section">
      <h1>ENCUENTRA TODO LO QUE NECESITAS</h1>

      <div>
        <a href="<?= BASE_URL; ?>/negocios/bodas">
          <img src="<?= BASE_URL; ?>/src/assets/images/eventtypes/manteleslargos_boda_white.png" alt="Tipo de evento">
          <img class="hover" src="<?= BASE_URL; ?>/src/assets/images/eventtypes/manteleslargos_boda_hover.png" alt="Tipo de evento">
          <h5>Bodas</h5>
        </a>

        <a href="<?= BASE_URL; ?>/negocios/xv-anios">
          <img src="<?= BASE_URL; ?>/src/assets/images/eventtypes/manteleslargos_quince_white.png" alt="Tipo de evento">
          <img class="hover" src="<?= BASE_URL; ?>/src/assets/images/eventtypes/manteleslargos_quince_hover.png" alt="Tipo de evento">
          <h5>XV Años</h5>
        </a>

        <a href="<?= BASE_URL; ?>/negocios/infantiles">
          <img src="<?= BASE_URL; ?>/src/assets/images/eventtypes/manteleslargos_infantil_white.png" alt="Tipo de evento">
          <img class="hover" src="<?= BASE_URL; ?>/src/assets/images/eventtypes/manteleslargos_infantil_hover.png" alt="Tipo de evento">
          <h5>Infantiles</h5>
        </a>

        <a href="<?= BASE_URL; ?>/negocios/bautizos">
          <img src="<?= BASE_URL; ?>/src/assets/images/eventtypes/manteleslargos_bautizo_white.png" alt="Tipo de evento">
          <img class="hover" src="<?= BASE_URL; ?>/src/assets/images/eventtypes/manteleslargos_bautizo_hover.png" alt="Tipo de evento">
          <h5>Bautizos</h5>
        </a>

        <a href="<?= BASE_URL; ?>/negocios/convenciones">
          <img src="<?= BASE_URL; ?>/src/assets/images/eventtypes/manteleslargos_conv_white.png" alt="Tipo de evento">
          <img class="hover" src="<?= BASE_URL; ?>/src/assets/images/eventtypes/manteleslargos_conv_hover.png" alt="Tipo de evento">
          <h5>Convenciones</h5>
        </a>

        <a href="<?= BASE_URL; ?>/negocios/otros">
          <img src="<?= BASE_URL; ?>/src/assets/images/eventtypes/manteleslargos_otros_white.png" alt="Tipo de evento">
          <img class="hover" src="<?= BASE_URL; ?>/src/assets/images/eventtypes/manteleslargos_otros_hover.png" alt="Tipo de evento">
          <h5>Otros</h5>
        </a>
      </div>
    </section>

    <section class="the-most-recent-section">
      <div class="top">
        <h1>LO MAS RECIENTE</h1>
        <h2>Conoce el trabajo de nuestros proveedores</h2>
      </div>

      <div class="info">
        <div class="top">
          <h4>ABRIL 2022</h4>
          <h4>CDMX | MEXICO</h4>

          <h5>FIESTA DE CUMPLEAÑOS</h5>
          <p>FLORISTERÍA "ROSAS BONITAS"</p>
        </div>

        <div class="divider"></div>

        <div class="bottom">
          <h3>Sorprende a todos con flores de loto</h3>
          <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Quis libero, nobis fuga harum ut earum saepe dignissimos impedit sapiente eligendi debitis ullam nostrum dolor, repellendus eos minima, itaque omnis aspernatur.</p>
        </div>

        <div class="cs-slider">
          <div class="imgs">
            <img src="<?= BASE_URL; ?>/src/assets/images/slider/01.jpg">
            <img src="<?= BASE_URL; ?>/src/assets/images/slider/02.jpg">
            <img src="<?= BASE_URL; ?>/src/assets/images/slider/03.jpg">
          </div>

          <div class="dots"></div>
        </div>

        <div class="button">
          <a class="events-btn" href="javascript:void(0)">
            VER OTROS EVENTOS
          </a>
        </div>
      </div>
    </section>

    <section class="page-section tips-section">
      <h1>LOS MEJORES TIPS</h1>
      <h2>¡Suscribete a nuestro canal y aprende los mejores tips para tus eventos</h2>

      <a class="suscribe-btn" href="javascript:void(0)">
        SUSCRÍBEME
      </a>

      <div>
        <iframe width="100%" height="370" src="https://www.youtube.com/embed/W8ZzU5aArc8" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen style="margin-top: 3rem;max-width: 900px;"></iframe>
        <iframe class="second" width="100%" height="370" src="https://www.youtube.com/embed/W8ZzU5aArc8" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen style="margin-top: 3rem;max-width: 900px;"></iframe>
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

  <script src="<?= BASE_URL; ?>/src/plugins/cs-slider/cs-slider.js"></script>
</body>

</html>