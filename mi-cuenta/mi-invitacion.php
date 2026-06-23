<?php
include 'inc/config.inc.php';
include 'inc/functions.inc.php';

# General data
$person_name          = cleanStr($_POST['personName']);
$event_name           = cleanStr($_POST['eventName']);
$phone                = cleanStr($_POST['phone']);
$commemorative_phrase = cleanStr($_POST['commemorativePhrase']);
$template             = cleanStr($_POST['template']);
$invitation_type      = cleanStr($_POST['invitationType']);
$principal_color      = cleanStr($_POST['principalColor']);
$secondary_color      = cleanStr($_POST['secondaryColor']);

# Religious ceremony
$cr_place     = cleanStr($_POST['CRPlace']);
$cr_date      = $_POST['CRDate'];
$cr_address   = cleanStr($_POST['addressCR']);
$cr_latitude  = cleanStr($_POST['latitudeCR']);
$cr_longitude = cleanStr($_POST['longitudeCR']);
$cr_image     = $_POST['crImage-preview'];

# Reception
$r_place     = cleanStr($_POST['RPlace']);
$r_date      = $_POST['RDate'];
$r_address   = cleanStr($_POST['addressRecepcion']);
$r_latitude  = cleanStr($_POST['latitudeRecepcion']);
$r_longitude = cleanStr($_POST['longitudeRecepcion']);
$r_image     = $_POST['rImage-preview'];

# Image gallery
$individual_picture = $_POST['individualPicture-preview'];
$family_picture     = $_POST['familyPicture-preview'];
$image_gallery      = $_POST['imageGallery-preview'];

$template             = $_POST['Plantilla'];

$host_url = $url_host . 'invitaciones/';
$href     = 'https://' . $_SERVER['HTTP_HOST'] . '/2021/mi-cuenta/' . 'mi-invitacion';
?>
<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= $person_name . '-' . $event_name; ?></title>

  <link rel="icon" href="<?= $url_host ?>images/manteleslargos_favicon.png">

  <link rel="stylesheet" href="<?= $host_url; ?>plugins/purecss/pure-min.css">
  <link rel="stylesheet" href="<?= $host_url; ?>plugins/purecss/grids-responsive-min.css">

  <link rel="stylesheet" href="<?= $host_url; ?>css/main.css">

  <link rel="stylesheet" href="<?= $host_url; ?>plugins/skippr/skippr.css">

  <script type="text/javascript" src="https://cdn.addevent.com/libs/stc/1.0.2/stc.min.js" async defer></script>

  <style>
    :root {
      --primary-color: <?= $principal_color; ?>
    }

    .section-header {
      width: 100%;
      display: flex;
      align-items: center;
      justify-content: center;
      padding-top: 20px;
      text-align: center;
    }

    .section-title {
      font-size: 40px;
      font-weight: bold;
    }

    .skippr-nav-container {
      display: flex;
      align-items: center;
      justify-content: center;
    }

    .skippr-next {
      right: 6%;
    }

    .card-button {
      position: relative;
      background-color: var(--primary-color) !important;
    }

    .addeventatc_dropdown {
      display: none;
      background-color: #ffffff;
      box-shadow: 2px 2px 2px 1px rgba(0, 0, 0, 0.2);
      border-radius: 10px;
    }

    .addeventatc-selected {
      position: absolute;
      display: flex !important;
      flex-direction: column;
      align-items: center;
      justify-content: center;
      padding: 10px;
      left: 0 !important;
    }
  </style>
</head>

<body class="grid-container">
  <!-- Navbar -->
  <nav class="navbar">
    <div class="navbar-content">
      <a class="navbar-brand" href="#">
        <img class="navbar-img" src="<?= $host_url; ?>assets/images/manteleslargos-logo.png" alt="Manteles largos logo">
      </a>

      <a class="navbar-toggle" href="javascript:void(0)">
        <div class="toggle-bar1"></div>
        <div class="toggle-bar2"></div>
        <div class="toggle-bar3"></div>
      </a>
    </div>

    <div class="navbar-list">
      <ul>
        <li class="navbar-list-item">
          <a class="navbar-list-link" href="<?= $href; ?>#home">Inicio</a>
        </li>

        <li class="navbar-list-item">
          <a class="navbar-list-link" href="<?= $href; ?>#where-and-when">Donde y cuando</a>
        </li>

        <li class="navbar-list-item">
          <a class="navbar-list-link" href="<?= $href; ?>#photo-gallery">Galería de fotos</a>
        </li>

        <li class="navbar-list-item">
          <a class="navbar-list-link" href="<?= $href; ?>#confirm-assistance">Confirmar asistencia</a>
        </li>
      </ul>
    </div>
  </nav>

  <!-- Main -->
  <main class="main">
    <section id="home">
      <div class="individual-img-container">
        <div class="individual-img-opacity">
          <h1 class="person-name"><?= $person_name; ?></h1>
          <h2 class="event-name"><?= $event_name; ?></h2>
        </div>

        <img class="individual-img" src="<?= $individual_picture; ?>" alt="Imagen individual">
      </div>
    </section>

    <?php if ($cr_place || $r_place) : ?>
      <section id="where-and-when" class="pure-g">
        <div class="pure-u-1 section-header">
          <h1 class="section-title">Donde y Cuando</h1>
        </div>

        <?php if ($cr_place) : ?>
          <div class="pure-u-1 pure-u-md-1-2">
            <div class="card">
              <div class="card-header">
                <h3 class="card-title">Ceremonia religiosa</h3>

                <img class="card-img" src="<?= $cr_image; ?>" alt="Ceremonia religiosa">
              </div>

              <div class="card-body">
                <h3 class="card-subtitle"><?= $cr_place; ?></h3>

                <p class="card-description">
                  <b>Cuando:</b> <?= $cr_date; ?>.<br>
                  <b>Dirección:</b> <?= $cr_address ?>.
                </p>
              </div>

              <div class="card-footer pure-g">
                <div class="pure-u-1 pure-u-md-1-2">
                  <a class="pure-button card-button" target="_blank" href="https://maps.google.com/?q=<?= $cr_latitude; ?>, <?= $cr_longitude; ?>">
                    <ion-icon name="map"></ion-icon>
                    Ver mapa
                  </a>
                </div>

                <div class="pure-u-1 pure-u-md-1-2">
                  <div title="Add to Calendar" class="addeventstc pure-button card-button" data-id="ic14">
                    Agendar en el calendario
                  </div>

                  <!-- <button class="pure-button card-button addeventatc">
                    <ion-icon name="calendar"></ion-icon>
                    Agendar en el calendario<br>
                  </button> -->
                </div>
              </div>
            </div>
          </div>
        <?php endif; ?>

        <?php if ($r_place) : ?>
          <div class="pure-u-1 pure-u-md-1-2">
            <div class="card">
              <div class="card-header">
                <h3 class="card-title">Recepción</h3>

                <img class="card-img" src="<?= $r_image; ?>" alt="Recepción">
              </div>

              <div class="card-body">
                <h3 class="card-subtitle"><?= $r_place; ?></h3>

                <p class="card-description">
                  <b>Cuando:</b> <?= $r_date; ?>.<br>
                  <b>Dirección:</b> <?= $r_address; ?>.
                </p>
              </div>

              <div class="card-footer pure-g">
                <div class="pure-u-1 pure-u-md-1-2">
                  <a class="pure-button card-button" target="_blank" href="https://maps.google.com/?q=<?= $r_latitude; ?>, <?= $r_longitude; ?>">
                    <ion-icon name="map"></ion-icon>
                    Ver mapa
                  </a>
                </div>

                <div class="pure-u-1 pure-u-md-1-2">
                  <div title="Add to Calendar" class="addeventstc pure-button card-button" data-id="ic14">
                    Agendar en el calendario
                  </div>

                  <!-- <button class="pure-button card-button addeventatc">
                    <ion-icon name="calendar"></ion-icon>
                    Agendar en el calendario<br>
                  </button> -->
                </div>
              </div>
            </div>
          </div>
        <?php endif; ?>
      </section>
    <?php endif; ?>

    <?php $num_rows = count($image_gallery); ?>

    <?php if ($num_rows) : ?>
      <section id="photo-gallery" class="pure-g">
        <div class="pure-u-1 section-header">
          <h1 class="section-title">Galería de imagenes</h1>
        </div>

        <div class="pure-u-1">
          <div id="container" style="height: 90vh;">
            <div id="theTarget">
              <?php foreach ($image_gallery as $key => $value) : ?>
                <div style="background-image: url(<?= $image_gallery[$key]; ?>)"></div>
              <?php endforeach; ?>
            </div>
          </div>
        </div>
      </section>
    <?php endif; ?>

    <section id="confirm-assistance" class="pure-g confirm-assistance-section">
      <div class="pure-u-1 section-header">
        <h1 class="section-title">Confirmar asistencia</h1>
      </div>

      <div class="pure-u-1 card-header">
        <h3 class="card-subtitle">
          ¿Nos confirmas?
          <br>
          Gracias
        </h3>
      </div>

      <div class="pure-u-1 confirm-assistance-button-container">
        <a class="pure-button confirm-assistance-button" target="_blank" href="https://api.whatsapp.com/send?phone=52<?= $phone; ?>&text=Confirmo%20asistencia%20al%20evento%20de%20<?= $person_name; ?>" style="background-color: #25D366!important;color:#ffffff;">
          <ion-icon name="logo-whatsapp"></ion-icon>
          Confirmar ahora
        </a>
      </div>
    </section>
  </main>

  <!-- Footer -->
  <footer class="footer">
    <div class="family-img-container">
      <div class="family-img-opacity">
        <h3 class="footer-title"><?= $person_name; ?></h3>
        <h4 class="footer-subtitle"><?= $commemorative_phrase; ?></h4>
      </div>

      <img class="family-img" src="<?= $family_picture; ?>" alt="Imagen familiar">
    </div>
  </footer>

  <script src="<?= $host_url; ?>plugins/jquery/jquery.min.js"></script>
  <script src="<?= $host_url; ?>plugins/skippr/skippr.js"></script>
  <!-- <script src="<?= $host_url; ?>plugins/addevent/addevent.js"></script> -->

  <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
  <script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>

  <script src="<?= $host_url; ?>js/main.js"></script>

  <script>
    $(document).ready(function() {
      $("#theTarget").skippr({
        transition: 'slide',
        speed: 1000,
        easing: 'easeOutQuart',
        navType: 'block',
        childrenElementType: 'div',
        arrows: true,
        autoPlay: false,
        autoPlayDuration: 5000,
        keyboardOnAlways: true,
        hidePrevious: false
      });
    });

    /* addeventatc.settings({
      license: "replace-with-your-licensekey",
      css: false
    }); */
  </script>
</body>

</html>