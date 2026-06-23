<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <title><?= $invitation['names']; ?> - <?= $invitation['eventName']; ?> | Manteles Largos</title>

  <meta name="keywords" content="<?= $invitation['names']; ?>, manteles, largos, invitacion, invitaciones" />

  <meta name="description" content="<?= $invitation['commemorativePhrase']; ?>">
  <meta name="image" content="<?= $invitation['individualPicture-preview']; ?>">

  <meta property="og:title" content="<?= $invitation['names']; ?>">
  <meta property="og:description" content="<?= $invitation['commemorativePhrase']; ?>">
  <meta property="og:image" content="<?= $invitation['individualPicture-preview']; ?>">
  <meta property="og:url" content="<?= $webpage_meta_data['currentURL']; ?>">

  <link rel="canonical" href="https://www.manteleslargos.com" />
  <link rel="icon" type="image/x-icon" href="<?= BASE_URL; ?>/src/assets/images/favicon.png">

  <!-- TWITTER -->
  <meta name="twitter:card" content="summary">
  <meta name="twitter:site" content="@">
  <meta name="twitter:creator" content="@">
  <meta name="twitter:title" content="<?= $invitation['names']; ?>">
  <meta name="twitter:description" content="<?= $invitation['commemorativePhrase']; ?>">
  <meta name="twitter:image" content="<?= $invitation['individualPicture-preview']; ?>">

  <!-- FACEBOOK -->
  <meta property="og:url" content="<?= $href ?>">
  <meta property="og:title" content="<?= $invitation['names']; ?>">
  <meta property="og:description" content="<?= $invitation['commemorativePhrase']; ?>">
  <meta property="og:type" content="website">
  <meta property="og:image" content="<?= $invitation['individualPicture-preview']; ?>">
  <meta property="og:image:width" content="1200">
  <meta property="og:image:height" content="630">

  <link rel="stylesheet" href="<?= BASE_URL; ?>/src/css/pages/invitation-template-01.css">
  <link rel="stylesheet" href="<?= BASE_URL; ?>/src/plugins/simple-lightbox/simple-lightbox.min.css">

  <style>
    :root {
      --primary-color: <?= $invitation['principalColor']; ?>;
      --secondary-color: <?= $invitation['secondaryColor']; ?>;
    }
  </style>
</head>

<body class="grid-container">
  <!-- NAVBAR -->
  <nav id="navbar" class="navbar">
    <div>
      <a class="navbar-brand" href="#">
        <b><?= $invitation['names']; ?></b><br><?= $invitation['eventName']; ?>
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

      <?php if ($invitation['CRPlace'] || $invitation['RPlace']) : ?>
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
    <img src="<?= $invitation['individualPicture-preview']; ?>" alt="<?= $invitation['names']; ?>">
  </section>

  <!-- EVENT TITLE -->
  <section class="event-title">
    <h3><?= $invitation['names']; ?></h3>
    <h4><?= $invitation['eventName']; ?></h4>
    <h4><small><?= $invitation['commemorativePhrase']; ?></small></h4>
  </section>

  <!-- CEREMONIES -->
  <?php if ($invitation['CRPlace'] || $invitation['RPlace']) : ?>
    <section id="ceremonies" class="ceremonies">
      <?php if ($invitation['CRPlace']) : ?>
        <div>
          <div class="content">
            <img src="<?= $invitation['CRPicture-preview']; ?>">

            <h3>CEREMONIA RELIGIOSA</h3>

            <p><?= getDateTimeWithStrFormat(date("Y-m-d H:i:s", strtotime(str_replace('/', '-', $invitation['CRDateTime'])))); ?></p>

            <p><?= $invitation['CRAddress']; ?></p>

            <a target="_blank" href="https://maps.google.com/?q=<?= $invitation['CRLatitude']; ?>, <?= $invitation['CRLongitude']; ?>">
              Ver en mapa
            </a>
          </div>
        </div>
      <?php endif; ?>

      <?php if ($invitation['RPlace']) : ?>
        <div>
          <div class="content">
            <img src="<?= $invitation['RPicture-preview']; ?>">

            <h3>RECEPCIÓN</h3>
            <p><?= getDateTimeWithStrFormat(date("Y-m-d H:i:s", strtotime(str_replace('/', '-', $invitation['RDateTime'])))); ?></p>

            <p><?= $invitation['RAddress']; ?></p>

            <a target="_blank" href="https://maps.google.com/?q=<?= $invitation['RLatitude']; ?>, <?= $invitation['RLongitude']; ?>">
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
        <?php foreach ($gallery as $key => $image) : ?>
          <li>
            <a href="<?= $image; ?>" title="<?= $invitation['names']; ?>">
              <img src="<?= $image; ?>" alt="<?= $invitation['names']; ?>">
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
        <input id="phone" value="<?= $invitation['contact']; ?>" type="hidden" required>
        <input id="personName" value="<?= $invitation['names']; ?>" type="hidden" required>

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