<?php
include 'inc/public-session.php';

$invitation_slug = cleanStr($_GET['invitationSlug']);

if (!$invitation_slug) :
  header('location:' . BASE_URL);
  die();
endif;

$invitation = getInvitationDataForTemplateBySlug($invitation_slug, '02');

if (!$invitation) :
  header('location:' . BASE_URL);
  die();
endif;

$gallery = getInvitationGalleryById($invitation['idInvitacion']);
?>

<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <title><?= $invitation['NombrePersona']; ?> - <?= $invitation['NombreEvento']; ?> | Manteles Largos</title>

  <meta name="keywords" content="<?= $invitation['NombrePersona']; ?>, manteles, largos, invitacion, invitaciones" />

  <meta name="description" content="<?= $invitation['Frase']; ?>">
  <meta name="image" content="<?= setInvitationImage($invitation['ImagenIndividual']); ?>">

  <meta property="og:title" content="<?= $invitation['NombrePersona']; ?>">
  <meta property="og:description" content="<?= $invitation['Frase']; ?>">
  <meta property="og:image" content="<?= setInvitationImage($invitation['ImagenIndividual']); ?>">
  <meta property="og:url" content="<?= $webpage_meta_data['currentURL']; ?>">

  <link rel="canonical" href="https://www.manteleslargos.com" />
  <link rel="icon" type="image/x-icon" href="<?= BASE_URL; ?>/src/assets/images/favicon.png">

  <!-- TWITTER -->
  <meta name="twitter:card" content="summary">
  <meta name="twitter:site" content="@">
  <meta name="twitter:creator" content="@">
  <meta name="twitter:title" content="<?= $invitation['NombrePersona']; ?>">
  <meta name="twitter:description" content="<?= $invitation['Frase']; ?>">
  <meta name="twitter:image" content="<?= setInvitationImage($invitation['ImagenIndividual']); ?>">

  <!-- FACEBOOK -->
  <meta property="og:url" content="<?= $href ?>">
  <meta property="og:title" content="<?= $invitation['NombrePersona']; ?>">
  <meta property="og:description" content="<?= $invitation['Frase']; ?>">
  <meta property="og:type" content="website">
  <meta property="og:image" content="<?= setInvitationImage($invitation['ImagenIndividual']); ?>">
  <meta property="og:image:width" content="1200">
  <meta property="og:image:height" content="630">

  <link rel="stylesheet" href="<?= BASE_URL; ?>/src/css/pages/invitation-template-02.css">
  <link rel="stylesheet" href="<?= BASE_URL; ?>/src/plugins/simple-lightbox/simple-lightbox.min.css">

  <style>
    :root {
      --primary-color: <?= $invitation['ColorPrincipal']; ?>;
      --secondary-color: <?= $invitation['ColorSecundario']; ?>;
    }
  </style>
</head>

<body class="grid-container">
  <!-- NAVBAR -->
  <nav id="navbar" class="navbar">
    <div>
      <a class="navbar-brand" href="#">
        <b><?= $invitation['NombrePersona']; ?></b><br><?= $invitation['NombreEvento']; ?>
      </a>

      <a class="toggle" href="javascript:void(0)">
        <div class="bar1"></div>
        <div class="bar2"></div>
        <div class="bar3"></div>
      </a>
    </div>

    <ul>
      <li>
        <a href="#home">Inicio</a>
      </li>

      <?php if ($invitation['CRLugar'] || $invitation['RLugar']) : ?>
        <li>
          <a href="#ceremonies">Donde y cuando</a>
        </li>
      <?php endif; ?>

      <?php if ($gallery) : ?>
        <li>
          <a href="#gallery">Galería de fotos</a>
        </li>
      <?php endif; ?>

      <li>
        <a href="#confirmation">Confirmar asistencia</a>
      </li>
    </ul>
  </nav>

  <!-- IMAGEN INDIVIDUAL -->
  <section id="home" class="individual-picture">
    <img src="<?= setInvitationImage($invitation['ImagenIndividual']); ?>" alt="<?= $invitation['NombrePersona']; ?>">
  </section>

  <!-- EVENT TITLE -->
  <section class="event-title">
    <h3><?= $invitation['NombrePersona']; ?></h3>
    <h4><?= $invitation['NombreEvento']; ?></h4>
    <h4><small><?= $invitation['Frase']; ?></small></h4>
  </section>

  <!-- CEREMONIES -->
  <?php if ($invitation['CRLugar'] || $invitation['RLugar']) : ?>
    <section id="ceremonies" class="ceremonies">
      <?php if ($invitation['CRLugar']) : ?>
        <div>
          <div class="date">
            <?= getDateTimeWithStrFormat($invitation['CRFechaWithOutFormat']); ?>
          </div>

          <div class="content">
            <img src="<?= setInvitationImage($invitation['CRImagen']); ?>">

            <h3>CEREMONIA RELIGIOSA</h3>

            <p><?= $invitation['CRDireccion']; ?></p>

            <a target="_blank" href="https://maps.google.com/?q=<?= $invitation['CRLatitud']; ?>, <?= $invitation['CRLongitud']; ?>">
              Ver en mapa
            </a>
          </div>
        </div>
      <?php endif; ?>

      <?php if ($invitation['RLugar']) : ?>
        <div>
          <div class="date">
            <?= getDateTimeWithStrFormat($invitation['RFechaWithOutFormat']); ?>
          </div>

          <div class="content">
            <img src="<?= setInvitationImage($invitation['RImagen']); ?>">

            <h3>RECEPCIÓN</h3>

            <p><?= $invitation['RDireccion']; ?></p>

            <a target="_blank" href="https://maps.google.com/?q=<?= $invitation['RLatitud']; ?>, <?= $invitation['RLongitud']; ?>">
              Ver en mapa
            </a>
          </div>
        </div>
      <?php endif; ?>
    </section>
  <?php endif; ?>

  <!-- GALLERY -->
  <?php if ($gallery) : ?>
    <section id="gallery" class="gallery">
      <div></div>

      <h3>GALERÍA</h3>

      <ul>
        <?php foreach ($gallery as $key => $row) : ?>
          <li>
            <a href="<?= $row['imageSrc']; ?>" title="<?= $invitation['NombrePersona']; ?>">
              <img src="<?= $row['imageSrc']; ?>" alt="<?= $invitation['NombrePersona']; ?>">
            </a>
          </li>
        <?php endforeach; ?>
      </ul>
    </section>
  <?php endif; ?>

  <!-- CONFIRMATION -->
  <section id="confirmation" class="confirmation">
    <form id="confirmation-form" autocomplete="off">
      <h3>CONIFIRMACIÓN</h3>

      <div>
        <label for="name">Ingresa tu nombre para confirmar tu asistencia</label>

        <input id="name" type="text" required>
        <input id="phone" value="<?= $invitation['Telefono']; ?>" type="hidden" required>
        <input id="personName" value="<?= $invitation['NombrePersona']; ?>" type="hidden" required>

        <button type="submit">
          ENVIAR
        </button>
      </div>
    </form>
  </section>

  <script src="<?= BASE_URL; ?>/src/plugins/jquery/jquery.min.js"></script>
  <script src="<?= BASE_URL; ?>/src/plugins/simple-lightbox/simple-lightbox.jquery.min.js"></script>
  <script src="<?= BASE_URL; ?>/src/js/invitation-template-02.js"></script>
</body>

</html>