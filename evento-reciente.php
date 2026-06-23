<?php
include 'inc/public-session.php';

$recent_event_slug = cleanStr($_GET['recentEventSlug']);
$recent_event_data = getRecentEventDataBySlug($recent_event_slug);

if (!$recent_event_data) :
  header('location:' . BASE_URL . '/eventos-recientes');
  die;
endif;

$webpage_meta_data['title']       = "$recent_event_data[Evento] - Manteles largos";
$webpage_meta_data['description'] = "$recent_event_data[DescCorta]";
$webpage_meta_data['image']       = setRecentEventImage($recent_event_data['Imagen']);

$other_recent_events = getOtherRecentEvents();
?>

<!DOCTYPE html>
<html lang="es">

<head>
  <?php include 'src/components/head.php'; ?>

  <!-- CS SLIDER -->
  <link rel="stylesheet" href="<?= BASE_URL; ?>/src/plugins/cs-slider/cs-slider.css">

  <link rel="stylesheet" href="<?= BASE_URL; ?>/src/css/pages/recent-events.css">
</head>

<body class="navbar-white">
  <!-- Preloader -->
  <?php include 'src/components/preloader.php'; ?>

  <!-- Navbar -->
  <?php include 'src/components/navbar.php'; ?>

  <!-- Main -->
  <main class="main">
    <section class="recent-events-section">
      <div>
        <h4><?= $recent_event_data['Ciudad']; ?> | <?= $recent_event_data['Estado']; ?></h4>
        <h3><?= $recent_event_data['Evento']; ?></h3>

        <div class="cs-slider animation" data-interval="6000" style="width: 100%;">
          <div class="imgs">
            <img src="<?= setRecentEventImage($recent_event_data['Imagen']); ?>" alt="<?= $recent_event_data['Evento']; ?>">
            <?= $recent_event_data['gallery']; ?>
          </div>

          <div class="dots"></div>
        </div>

        <div class="description">
          <?= $recent_event_data['Descripcion']; ?>
        </div>
      </div>

      <?php if ($other_recent_events) : ?>
        <div class="other-events">
          <h2>Otros eventos</h2>

          <div>
            <?php foreach ($other_recent_events as $key => $row) :
              $recent_event_item_title        = limitStr($row['Evento'], 45);
              $recent_event_item_description  = limitStr($row['DescCorta'], 70);
              $recent_event_item_slug         = $row['slug'] . '-' . $row['Referencia'];
              $recent_event_item_img          = setRecentEventImage($row['Imagen']);
              $recent_event_item_url          = BASE_URL . '/eventos-recientes/' . $recent_event_item_slug;
            ?>
              <?php include 'src/components/recent-event-item.php'; ?>
            <?php endforeach; ?>
          </div>
        </div>
      <?php endif; ?>
    </section>

    <!-- Modal for login and register -->
    <?php include 'src/modals/login-register.php'; ?>
  </main>

  <!-- Footer -->
  <?php include 'src/components/footer.php'; ?>

  <!-- Required scripts -->
  <?php include 'src/components/required-scripts.php'; ?>

  <!-- CS SLIDER -->
  <script src="<?= BASE_URL; ?>/src/plugins/cs-slider/cs-slider.js"></script>
</body>

</html>