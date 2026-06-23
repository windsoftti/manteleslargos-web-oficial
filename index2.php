<?php
include 'inc/public-session.php';
$last_recent_event = getLastRecentEvent();
?>

<!DOCTYPE html>
<html lang="es">

<head>
  <?php include 'src/components/head.php'; ?>

  <!--link rel="stylesheet" href="<?= BASE_URL; ?>/src/plugins/jquery-ui-datepicker/jquery-ui.min.css"-->
  <link rel="stylesheet" href="<?= BASE_URL; ?>/src/plugins/cs-slider/cs-slider.css">
  <link rel="stylesheet" href="<?= BASE_URL; ?>/src/css/pages/home.css">

  <!-- Slick slider -->
  <link rel="stylesheet" href="<?= BASE_URL; ?>/src/plugins/slick-slider/slick.css">
  <link rel="stylesheet" href="<?= BASE_URL; ?>/src/plugins/slick-slider/slick-theme.css">

  <style>
    .slick-slide img {
      height: 60vh;
      width: 100%;
      object-fit: cover;
    }

    .slider-ab-content {
      display: flex;
      height: 100%;
      width: 100%;
      flex-direction: column;
      align-items: flex-start;
      justify-content: center;
      position: absolute;
      padding: 1rem 8rem;
      top: 0;
    }

    .slider-title {
      font-size: 2.6rem;
      margin: 0;
      /*color: #222;*/
      font-weight: 500;
    }

    .slider-subtitle {
      font-size: 2rem;
      margin: 0;
      /*color: #000;*/
      margin-top: 0;
      font-weight: 400;
    }

    .slider-description {
      color: var(--primary-color-light);
      font-size: 1.1rem;
      margin: 0;
      margin-top: 1rem;
      font-weight: bold;
    }

    .slider-btn {
      display: flex;
      background-color: var(--primary-color-light);
      align-items: center;
      justify-content: center;
      padding: 0.5rem 1rem;
      /*color: #fff;*/
      font-size: 1.2rem;
      margin-top: 1rem;
      border-radius: 0.8rem;
      font-weight: bold;
    }

    .text-white {
      color: #fff;
    }

    .text-black {
      color: #222;
    }
  </style>

  <style>
    .youtube-id-video {
      display: flex;
      height: 45vh;
      width: 100%;
      max-width: 56rem;
      background-color: #000;
      align-items: center;
      justify-content: center;
      overflow: hidden;
      margin-top: 2rem;
      position: relative;
    }

    .youtube-id-video img {
      height: 100%;
      width: 100%;
      object-fit: cover;
    }

    .youtube-id-video>span {
      width: 5rem;
      height: 4rem;
      position: absolute;
      background: url(https://1.bp.blogspot.com/-94QEg1Pk9tw/WGhNhfqMs-I/AAAAAAAAA1w/u3gEPfwh4hwMkWeAu-_PteMTFlSfy8pqACLcB/s1600/1483251316_5295_-_Youtube_I.png) no-repeat;
      background-size: contain;
      left: 50%;
      top: 50%;
      margin-left: -47px;
      margin-top: -32px;
      opacity: 0.7;
      transition: 0.2s;
      /* -webkit-filter: brightness(75%); */
    }

    .youtube-id-video:hover>span {
      opacity: 1;
    }

    .youtube-id-video iframe {
      height: 100%;
      width: 100%;
      object-fit: cover;
    }

    @media screen and (min-width: 64em) {
      .youtube-id-video {
        height: 75vh;
      }
    }
  </style>
</head>

<body class="home-page">
  <!-- Preloader -->
  <?php include 'src/components/preloader.php'; ?>

  <!-- Header -->
  <?php include 'src/components/header.php'; ?>

  <!-- Navbar -->
  <?php include 'src/components/navbar.php'; ?>

  <!-- Main -->
  <main class="main">
    <section class="slider-section">
      <!-- <div class="cs-slider animation" data-interval="6000">
        <div class="imgs">
          <img class="d-block w-100" src="<?= BASE_URL; ?>/src/assets/images/banner/manteleslargos_002.jpg">
          <img class="d-block w-100" src="<?= BASE_URL; ?>/src/assets/images/banner/manteleslargos_001.jpg">
          <img class="d-block w-100" src="<?= BASE_URL; ?>/src/assets/images/banner/manteleslargos_003.jpg">
          <img class="d-block w-100" src="<?= BASE_URL; ?>/src/assets/images/banner/manteleslargos_004.jpg">
          <img class="d-block w-100" src="<?= BASE_URL; ?>/src/assets/images/banner/manteleslargos_005.jpg">
          <img class="d-block w-100" src="<?= BASE_URL; ?>/src/assets/images/banner/manteleslargos_006.jpg">
          <img class="d-block w-100" src="<?= BASE_URL; ?>/src/assets/images/banner/manteleslargos_007.jpg">
          <img class="d-block w-100" src="<?= BASE_URL; ?>/src/assets/images/banner/manteleslargos_008.jpg">
          <img class="d-block w-100" src="<?= BASE_URL; ?>/src/assets/images/banner/manteleslargos_009.jpg">
        </div>

        <div class="dots"></div>
      </div> -->
      <div style="position: relative;">
        <!-- <?php if (!$_SESSION['session_user_id'] || $_SESSION['session_user_level'] != 'Usuario Final') : ?>
          <a class="tab-toggle" data-toggle="modal" data-target="modal-login-register" data-content="tab-login" href="javascript:void(0)">
            <img src="<?= BASE_URL; ?>/src/assets/images/banner/crear_invitacion_manteles-largos.jpg">
          </a>
        <?php endif; ?>

        <?php if ($_SESSION['session_user_id'] && $_SESSION['session_user_level'] == 'Usuario Final') : ?>
          <a href="<?= BASE_URL; ?>/crear-invitacion">
            <img src="<?= BASE_URL; ?>/src/assets/images/banner/crear_invitacion_manteles-largos.jpg">
          </a>
        <?php endif; ?> -->

        <img src="<?= BASE_URL; ?>/src/assets/images/banner/crear_invitacion_digital_manteleslargos.webp">

        <div class="slider-ab-content">
          <h1 class="slider-title">Crea tu invitación</h1>
          <h2 class="slider-subtitle">completamente gratis.</h2>
          <p class="slider-description">DISEÑO FÁCIL Y RÁPIDO, 100% DIGITAL</p>

          <?php if (!$_SESSION['session_user_id'] || $_SESSION['session_user_level'] != 'Usuario Final') : ?>
            <a class="slider-btn tab-toggle text-black" data-toggle="modal" data-target="modal-login-register" data-content="tab-login" href="javascript:void(0)">
              CREAR AHORA
            </a>
          <?php endif; ?>

          <?php if ($_SESSION['session_user_id'] && $_SESSION['session_user_level'] == 'Usuario Final') : ?>
            <a class="slider-btn text-black" href="<?= BASE_URL; ?>/crear-invitacion">
              CREAR AHORA
            </a>
          <?php endif; ?>
        </div>
      </div>
      <!--div>
        <img src="<?= BASE_URL; ?>/src/assets/images/banner/manteleslargos_001.jpg">
      </div-->
      <div>
        <img src="<?= BASE_URL; ?>/src/assets/images/banner/promocinal_manteleslargos.webp">
        <div class="slider-ab-content">
          <h2 class="slider-subtitle text-white">
            ¿Eres proveedor de eventos<br>
            sociales? Regístrate, administra<br>
            y <strong>aumenta tus ventas</strong>
          </h2>
          <a class="slider-btn text-black" href="<?= BASE_URL; ?>/soy-proveedor">
            REGÍSTRATE AHORA
          </a>
        </div>
      </div>
      <!--div>
        <img src="<?= BASE_URL; ?>/src/assets/images/banner/manteleslargos_004.jpg">
      </div-->
      <!--div>
        <img src="<?= BASE_URL; ?>/src/assets/images/banner/manteleslargos_005.jpg">
      </div-->
      <div>
        <img src="<?= BASE_URL; ?>/src/assets/images/banner/florencia_manteleslargos.webp">
        <div class="slider-ab-content">
          <!--h1 class="slider-title">Festeja y conoce todos nuestros paquetes</h1>
            <h2 class="slider-subtitle">¡Haz la fiesta de tus sueños!</h2>
            <p class="slider-description">LA VIDA ES UNA FIESTA.</p-->
          <h2 class="slider-subtitle text-black">
            Festeja y conoce todos<br>
            nuestros paquetes.<br>
            ¡Haz la <strong>fiesta de tus sueños!</strong>
          </h2>
          <p class="slider-description">LA VIDA ES UNA FIESTA</p>
          <a class="slider-btn text-black" href="<?= BASE_URL; ?>/salon-florencia-1" target="_blank">
            COTIZAR AHORA
          </a>
        </div>
      </div>
      <!--div>
        <img src="<?= BASE_URL; ?>/src/assets/images/banner/manteleslargos_007.jpg">
      </div>
      <div>
        <img src="<?= BASE_URL; ?>/src/assets/images/banner/manteleslargos_008.jpg">
      </div>
      <div-->
      <div>
        <img src="<?= BASE_URL; ?>/src/assets/images/banner/cabina_manteleslargos.webp">
        <div class="slider-ab-content">
          <!--h1 class="slider-title">Dale a tus invitados un recuerdo innolvidadle</h1>
            <h2 class="slider-subtitle">Regístrate, administra</h2>
            <p class="slider-description">y aumenta tus ventas.</p-->
          <h2 class="slider-subtitle text-black">
            Dale a tus invitados un<br>
            recuerdo inolvidable, <strong>la mejor<br>cabina al mejor precio.</strong><br>
          </h2>
          <p class="slider-description">SHINE CABINA DE VIDEO 360</p>
          <a class="slider-btn text-black" href="<?= BASE_URL; ?>/soy-proveedor">
            COTIZAR AHORA
          </a>
        </div>
      </div>
    </section>

    <section class="search-section">
      <div>
        <h2>REÚNE EL MEJOR EQUIPO PARA PLANEAR TUS EVENTOS</h2>
        <h1>¿QUÉ QUIERES ORGANIZAR?</h1>

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
                <?= getSupplierTypesForAutocomplete(); ?>
              </div>
            </div>

            <select id="searchStateIdByLabel" name="state">
              <?= businessStatesForSearch('ESTADO'); ?>
            </select>

            <select id="searchCityId" name="city" style="display: none;">
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
        <a href="<?= BASE_URL; ?>/tipo-proveedores/bodas">
          <img src="<?= BASE_URL; ?>/src/assets/images/eventtypes/manteleslargos_boda.webp" alt="Tipo de evento">
          <img class="hover" src="<?= BASE_URL; ?>/src/assets/images/eventtypes/manteleslargos_boda_hover.png" alt="Tipo de evento">
          <h5>Bodas</h5>
        </a>

        <a href="<?= BASE_URL; ?>/tipo-proveedores/xv-anios">
          <img src="<?= BASE_URL; ?>/src/assets/images/eventtypes/manteleslargos_quince.webp" alt="Tipo de evento">
          <img class="hover" src="<?= BASE_URL; ?>/src/assets/images/eventtypes/manteleslargos_quince_hover.png" alt="Tipo de evento">
          <h5>XV Años</h5>
        </a>

        <a href="<?= BASE_URL; ?>/tipo-proveedores/infantiles">
          <img src="<?= BASE_URL; ?>/src/assets/images/eventtypes/manteleslargos_infantil.webp" alt="Tipo de evento">
          <img class="hover" src="<?= BASE_URL; ?>/src/assets/images/eventtypes/manteleslargos_infantil_hover.webp" alt="Tipo de evento">
          <h5>Infantiles</h5>
        </a>

        <a href="<?= BASE_URL; ?>/tipo-proveedores/bautizos">
          <img src="<?= BASE_URL; ?>/src/assets/images/eventtypes/manteleslargos_bautizo.png" alt="Tipo de evento">
          <img class="hover" src="<?= BASE_URL; ?>/src/assets/images/eventtypes/manteleslargos_bautizo_hover.png" alt="Tipo de evento">
          <h5>Bautizos</h5>
        </a>

        <a href="<?= BASE_URL; ?>/tipo-proveedores/convenciones">
          <img src="<?= BASE_URL; ?>/src/assets/images/eventtypes/manteleslargos_conv.png" alt="Tipo de evento">
          <img class="hover" src="<?= BASE_URL; ?>/src/assets/images/eventtypes/manteleslargos_conv_hover.png" alt="Tipo de evento">
          <h5>Convenciones</h5>
        </a>

        <a href="<?= BASE_URL; ?>/tipo-proveedores/otros">
          <img src="<?= BASE_URL; ?>/src/assets/images/eventtypes/manteleslargos_otros.webp" alt="Tipo de evento">
          <img class="hover" src="<?= BASE_URL; ?>/src/assets/images/eventtypes/manteleslargos_otros_hover.png" alt="Tipo de evento">
          <h5>Otros</h5>
        </a>
      </div>
    </section>

    <section class="page-section animation-gif" style="text-align:center;">
      <?php
      $attr_inv = (!$_SESSION['session_user_id'] || $_SESSION['session_user_level'] != 'Usuario Final') ? ' data-toggle="modal" data-target="modal-login-register" data-content="tab-login" href="javascript:void(0)" ' : 'href="' . BASE_URL . '/crear-invitacion"';
      ?>
      <a class="position-relative justify-start gap-05" <?= $attr_inv ?>>
        <img style="width: 80%" src="<?= BASE_URL; ?>/src/assets/images/manteleslargos_crear_invitacion.gif" alt="">
      </a>
    </section>

    <?php if ($last_recent_event) : ?>
      <section class="the-most-recent-section">
        <div class="top">
          <h1>LO MÁS RECIENTE</h1>
          <h2>Conoce el trabajo de nuestros proveedores</h2>
        </div>

        <div class="info">
          <div class="top">
            <h4><?= getDateWithMonthName($last_recent_event['Fecha']); ?></h4>
            <h4><?= $last_recent_event['Ciudad']; ?> | <?= $last_recent_event['Estado']; ?></h4>

            <h5><?= $last_recent_event['TipoProveedor']; ?></h5>
            <p><?= $last_recent_event['Salon']; ?></p>
          </div>

          <div class="divider"></div>

          <div class="bottom">
            <h3><?= $last_recent_event['Evento']; ?></h3>
            <p><?= $last_recent_event['DescCorta']; ?></p>
          </div>

          <div class="cs-slider animation" data-per-image="3" data-interval="6000">
            <div class="imgs">
              <img src="<?= setRecentEventImage($last_recent_event['Imagen']); ?>" alt="<?= $last_recent_event['Evento']; ?>" style="flex: 1;">
              <?= $last_recent_event['gallery']; ?>
            </div>

            <div class="dots"></div>
          </div>

          <div class="button">
            <a class="events-btn" href="eventos-recientes">
              VER OTROS EVENTOS
            </a>
          </div>
        </div>
      </section>
    <?php endif; ?>

    <section class="page-section tips-section">
      <h1>LOS MEJORES TIPS</h1>
      <h2>¡Suscríbete a nuestro canal y aprende los mejores tips para tus eventos!</h2>

      <a class="suscribe-btn" href="https://www.youtube.com/channel/UCXTOevC99RNKUvKQXwMQUTg" target="_blank">
        SUSCRÍBEME
      </a>

      <div>
        <a class="youtube-id-video" data-id="t_c3VrGwRNs" href="javascript:void(0)">
          <img src="https://i.ytimg.com/vi/t_c3VrGwRNs/hqdefault.jpg">
          <span></span>
        </a>
        <!-- <iframe width="100%" height="370" src="https://www.youtube.com/embed/t_c3VrGwRNs" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen style="margin-top: 3rem;max-width: 900px;"></iframe> -->
        <!-- <div class="youtube-video" data-id="t_c3VrGwRNs"></div> -->
        <!--iframe class="second" width="100%" height="370" src="https://www.youtube.com/embed/W8ZzU5aArc8" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen style="margin-top: 3rem;max-width: 900px;"></iframe-->
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
  <!-- <script src="<?= BASE_URL; ?>/src/plugins/bs-carousel/carousel.min.js"></script> -->
  <!-- Slick slider -->
  <script src="<?= BASE_URL; ?>/src/plugins/slick-slider/slick.min.js"></script>

  <script>
    $('.slider-section').slick({
      infinite: true,
      autoplay: true,
      autoplaySpeed: 3500,
      speed: 500,
      fade: true,
      cssEase: 'linear'
    });

    <?php if (($_GET['uid'] === 'login' || $_GET['uid'] === 'googlelogin' || $_GET['uid'] === 'facebooklogin') && !$_SESSION['session_user_id']) : ?>
      $('#btn-navbar-access').click();
    <?php endif; ?>
  </script>

  <script>
    document.addEventListener("DOMContentLoaded",
      function() {
        var a, n,
          v = document.getElementsByClassName("youtube-video");
        for (n = 0; n < v.length; n++) {
          a = document.createElement("div");
          a.setAttribute("data-id", v[n].dataset.id);
          a.innerHTML = videoThumb(v[n].dataset.id);
          a.onclick = videoIframe;
          v[n].appendChild(a);
        }
      });

    function videoThumb(id) {
      var thumb = '<img src="https://i.ytimg.com/vi/ID/hqdefault.jpg">',
        playBtn = '<span></span>';
      return thumb.replace("ID", id) + playBtn;
    }

    function videoIframe() {
      var iframe = document.createElement("iframe");
      iframe.setAttribute("src", "https://www.youtube.com/embed/" + this.dataset.id + "?autoplay=1");
      iframe.setAttribute("allowfullscreen", "1");
      iframe.setAttribute("frameborder", "0");
      this.parentNode.replaceChild(iframe, this);
    }

    $('.youtube-id-video').on('click', function() {
      const videoId = $(this).attr('data-id');
      const iframe = document.createElement('iframe');
      iframe.setAttribute('id', `youtube-iframe-${videoId}`);
      iframe.setAttribute('allow', 'accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture');
      iframe.setAttribute('src', `https://www.youtube.com/embed/${videoId}?autoplay=1`);
      iframe.setAttribute('allowfullscreen', '1');
      iframe.setAttribute('frameborder', '0');
      //this.parentNode.replaceChild(iframe, this);
      $(this).html(iframe);
    });
  </script>
</body>

</html>