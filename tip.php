<?php
include 'inc/public-session.php';

$tip_slug = cleanStr($_GET['tipSlug']);
$tip_data = getTipDataBySlug($tip_slug);

if (!$tip_data) :
  header('location:' . BASE_URL . '/tips');
  die;
endif;

$webpage_meta_data['title']       = "$tip_data[Tip] - Manteles largos";
$webpage_meta_data['description'] = "$tip_data[DescCorta]";
$webpage_meta_data['image']       = setTipImage($tip_data['Imagen']);

$other_tips = getOtherTips();
?>

<!DOCTYPE html>
<html lang="es">

<head>
  <?php include 'src/components/head.php'; ?>

  <!-- CS SLIDER -->
  <link rel="stylesheet" href="<?= BASE_URL; ?>/src/plugins/cs-slider/cs-slider.css">

  <link rel="stylesheet" href="<?= BASE_URL; ?>/src/css/pages/tips.css">
</head>

<body class="navbar-white">
  <!-- Preloader -->
  <?php include 'src/components/preloader.php'; ?>

  <!-- Navbar -->
  <?php include 'src/components/navbar.php'; ?>

  <!-- Main -->
  <main class="main">
    <section class="tips-section">
      <div>
        <h1><?= $tip_data['Tip']; ?></h1>

        <div class="cs-slider animation" data-interval="6000" style="width: 100%;">
          <div class="imgs">
            <img src="<?= setTipImage($tip_data['Imagen']); ?>" alt="<?= $tip_data['Tip']; ?>">
            <?= $tip_data['gallery']; ?>
          </div>

          <div class="dots"></div>
        </div>

        <div class="description">
          <?= $tip_data['Descripcion']; ?>
        </div>
      </div>

      <?php if ($other_tips) : ?>
        <div class="other-tips">
          <h2>Otros tips</h2>

          <div>
            <?php foreach ($other_tips as $key => $row) :
              $tip_item_title       = limitStr($row['Tip'], 45);
              $tip_item_description = limitStr($row['DescCorta'], 70);
              $tip_item_slug        = $row['Slug'] . '-' . $row['Referencia'];
              $tip_item_img         = setTipImage($row['Imagen']);
              $tip_item_url         = BASE_URL . '/tips/' . $tip_item_slug;
            ?>
              <?php include 'src/components/tip-item.php'; ?>
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