<?php
include 'inc/public-session.php';

$last_tip   = getLastTip();
$tips       = getTipsForSlider();
$tips_count = $tips ? count($tips) : 0;
?>

<!DOCTYPE html>
<html lang="es">

<head>
  <?php include 'src/components/head.php'; ?>

  <!-- Slick slider -->
  <link rel="stylesheet" href="<?= BASE_URL; ?>/src/plugins/slick-slider/slick.css">
  <link rel="stylesheet" href="<?= BASE_URL; ?>/src/plugins/slick-slider/slick-theme.css">

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
      <iframe width="100%" src="https://www.youtube.com/embed/W8ZzU5aArc8"></iframe>

      <h2>CUANDO DE MEJORAR TUS EVENTOS SE TRATA, TENEMOS ALGUNOS CONSEJOS PARA TI</h2>

      <h1>
        SUSCRÍBETE A NUESTRO CANAL <ion-icon name="logo-youtube"></ion-icon>
      </h1>

      <?php if ($last_tip) : ?>
        <div class="big-tip">
          <img src="<?= setTipImage($last_tip['Imagen']); ?>" alt="<?= $last_tip['Tip']; ?>">

          <div>
            <h3>TIP <?= $tips_count; ?></h3>
            <h2><?= $last_tip['Tip']; ?></h2>

            <p><?= $last_tip['DescCorta']; ?></p>

            <a href="javascript:void(0)">
              ENSEÑAME A HACERLO
            </a>
          </div>
        </div>
      <?php endif; ?>

      <?php if ($tips) :
        $total_tips = count($tips);
      ?>
        <div class="tips-slider">
          <div class="tips-items">
            <?php foreach ($tips as $key => $tip) : ?>
              <div>
                <div class="tip">
                  <div class="tip-images">
                    <img src="<?= setTipImage($tip['Imagen']); ?>" alt="<?= $tip['Tip']; ?>">
                    <?= $tip['gallery']; ?>
                  </div>

                  <div class="tip-info">
                    <h3>TIP <?= $total_tips; ?></h3>
                    <h2><?= $tip['Tip']; ?></h2>

                    <p><?= $tip['DescCorta']; ?></p>

                    <a href="javascript:void(0)">
                      ENSEÑAME A HACERLO
                    </a>
                  </div>
                </div>
              </div>

              <?php $total_tips = $total_tips - 1; ?>
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

  <!-- Slick slider -->
  <script src="<?= BASE_URL; ?>/src/plugins/slick-slider/slick.js"></script>

  <?php if ($tips_count > 1) : ?>
    <script>
      $('.tips-items').slick({
        //dots: true,
        //infinite: false,
        slidesToShow: 2,
        slidesToScroll: 2,
        responsive: [{
            breakpoint: 1024,
            settings: {
              slidesToShow: 2,
              slidesToScroll: 2,
              infinite: true,
              dots: false
            }
          },
          {
            breakpoint: 600,
            settings: {
              slidesToShow: 1,
              slidesToScroll: 1,
              arrows: true
            }
          },
          {
            breakpoint: 480,
            settings: {
              slidesToShow: 1,
              slidesToScroll: 1,
              arrows: true
            }
          }
        ]
      });
    </script>
  <?php endif; ?>
</body>

</html>