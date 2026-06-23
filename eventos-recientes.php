<?php include 'inc/public-session.php'; ?>

<!DOCTYPE html>
<html lang="es">

<head>
  <?php include 'src/components/head.php'; ?>

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
      <h1>Eventos recientes</h1>
      <h2>Conoce el trabajo de nuestros proveedores</h2>

      <div>
        <div id="list-recent-events" class="recent-events"></div>

        <div id="pagination" class="pagination"></div>
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
  <script src="<?= BASE_URL; ?>/src/js/recent-events.js"></script>
</body>

</html>