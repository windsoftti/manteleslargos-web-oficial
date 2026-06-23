<?php include 'inc/user-session.php'; ?>

<!DOCTYPE html>
<html lang="es">

<head>
  <?php include 'src/components/head.php'; ?>
  <link rel="stylesheet" href="<?= BASE_URL; ?>/src/css/pages/invitations.css">
</head>

<body class="navbar-white">
  <!-- Preloader -->
  <?php include 'src/components/preloader.php'; ?>

  <!-- Navbar -->
  <?php include 'src/components/navbar.php'; ?>

  <!-- Main -->
  <main class="main">
    <!-- Invitation navbar -->
    <?php include 'src/components/invitations-navbar.php'; ?>

    <div class="img-section">
      <div class="content top-align">
        <h1 class="desktop">¿Quieres que tu invitación sea muy especial?</h1>
      </div>

      <img src="<?= BASE_URL; ?>/src/assets/images/herramientas/top-banner.png">
    </div>

    <section class="page-section d-invitation">
      <h1 class="mobile">¿Quieres que tu invitación sea muy especial?</h1>

      <h3>Llámanos o escríbenos</h3>

      <h4>Te daremos la mejor versión de tu idea</h4>

      <a class="btn btn-success rounded" href="https://wa.link/jv7r8c" target="_blank">
        <ion-icon name="logo-whatsapp"></ion-icon>
        Whatsapp
      </a>
    </section>
  </main>

  <!-- Footer -->
  <?php include 'src/components/footer.php'; ?>

  <!-- Required scripts -->
  <?php include 'src/components/required-scripts.php'; ?>
</body>

</html>