<?php include 'inc/public-session.php'; ?>

<!DOCTYPE html>
<html lang="es">

<head>
  <?php include 'src/components/head.php'; ?>

  <link rel="stylesheet" href="<?= BASE_URL; ?>/src/css/pages/tips.css">
</head>

<body class="navbar-white">
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

      <div class="big-tip">
        <img src="<?= BASE_URL; ?>/src/assets/images/tips/manteleslargos_tip_01.png">

        <div>
          <h3>TIP 39</h3>
          <h2>BOTELLAS RECICLADAS</h2>

          <p>Lorem ipsum dolor sit amet consectetur, adipisicing elit. Tenetur esse debitis itaque odio facilis libero. Totam suscipit, dicta eligendi sequi reiciendis officiis magnam deserunt hic quae distinctio praesentium? Sed, inventore?</p>

          <a href="javascript:void(0)">
            ENSEÑAME A HACERLO
          </a>
        </div>
      </div>

      <div class="tips-slider">
        <a class="arrow left mobile" href="javascript:void(0)">
          <img src="<?= BASE_URL; ?>/src/assets/images/arrow.svg">
        </a>

        <a class="arrow left desktop" href="javascript:void(0)">
          <img src="<?= BASE_URL; ?>/src/assets/images/arrow.svg">
        </a>

        <div class="tips">
          <div class="tip-row active">
            <div class="tip active">
              <div class="tip-images">
                <img src="<?= BASE_URL; ?>/src/assets/images/tips/manteleslargos_tip_01.png">
                <img src="<?= BASE_URL; ?>/src/assets/images/tips/manteleslargos_tip_01.png">
                <img src="<?= BASE_URL; ?>/src/assets/images/tips/manteleslargos_tip_01.png">
              </div>

              <div class="tip-info">
                <h3>TIP 39</h3>
                <h2>BOTELLAS RECICLADAS</h2>

                <p>Lorem ipsum dolor sit amet consectetur, adipisicing elit. Tenetur esse debitis itaque odio facilis libero. Totam suscipit, dicta eligendi sequi reiciendis officiis magnam deserunt hic quae distinctio praesentium? Sed, inventore?</p>

                <a href="javascript:void(0)">
                  ENSEÑAME A HACERLO
                </a>
              </div>
            </div>

            <div class="tip">
              <div class="tip-images">
                <img src="<?= BASE_URL; ?>/src/assets/images/tips/manteleslargos_tip_01.png">
              </div>

              <div class="tip-info">
                <h3>TIP 40</h3>
                <h2>BOTELLAS RECICLADAS</h2>

                <p>Lorem ipsum dolor sit amet consectetur, adipisicing elit. Tenetur esse debitis itaque odio facilis libero. Totam suscipit, dicta eligendi sequi reiciendis officiis magnam deserunt hic quae distinctio praesentium? Sed, inventore?</p>

                <a href="javascript:void(0)">
                  ENSEÑAME A HACERLO
                </a>
              </div>
            </div>
          </div>

          <div class="tip-row">
            <div class="tip">
              <div class="tip-images">
                <img src="<?= BASE_URL; ?>/src/assets/images/tips/manteleslargos_tip_01.png">
              </div>

              <div class="tip-info">
                <h3>TIP 41</h3>
                <h2>BOTELLAS RECICLADAS</h2>

                <p>Lorem ipsum dolor sit amet consectetur, adipisicing elit. Tenetur esse debitis itaque odio facilis libero. Totam suscipit, dicta eligendi sequi reiciendis officiis magnam deserunt hic quae distinctio praesentium? Sed, inventore?</p>

                <a href="javascript:void(0)">
                  ENSEÑAME A HACERLO
                </a>
              </div>
            </div>

            <div class="tip">
              <div class="tip-images">
                <img src="<?= BASE_URL; ?>/src/assets/images/tips/manteleslargos_tip_01.png">
                <img src="<?= BASE_URL; ?>/src/assets/images/tips/manteleslargos_tip_01.png">
                <img src="<?= BASE_URL; ?>/src/assets/images/tips/manteleslargos_tip_01.png">
              </div>

              <div class="tip-info">
                <h3>TIP 42</h3>
                <h2>BOTELLAS RECICLADAS</h2>

                <p>Lorem ipsum dolor sit amet consectetur, adipisicing elit. Tenetur esse debitis itaque odio facilis libero. Totam suscipit, dicta eligendi sequi reiciendis officiis magnam deserunt hic quae distinctio praesentium? Sed, inventore?</p>

                <a href="javascript:void(0)">
                  ENSEÑAME A HACERLO
                </a>
              </div>
            </div>
          </div>
        </div>

        <a class="arrow right mobile" href="javascript:void(0)">
          <img src="<?= BASE_URL; ?>/src/assets/images/arrow.svg">
        </a>

        <a class="arrow right desktop" href="javascript:void(0)">
          <img src="<?= BASE_URL; ?>/src/assets/images/arrow.svg">
        </a>
      </div>
    </section>

    <!-- Modal for login and register -->
    <?php include 'src/modals/login-register.php'; ?>
  </main>

  <!-- Footer -->
  <?php include 'src/components/footer.php'; ?>

  <!-- Required scripts -->
  <?php include 'src/components/required-scripts.php'; ?>
</body>

</html>