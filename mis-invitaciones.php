<?php include 'inc/user-session.php'; ?>

<!DOCTYPE html>
<html lang="es">

<head>
  <?php include 'src/components/head.php'; ?>
  <link rel="stylesheet" href="<?= BASE_URL; ?>/src/css/pages/invitations.css">

  <!-- Pure css -->
  <link rel="stylesheet" href="https://unpkg.com/purecss@2.1.0/build/grids-min.css">
  <link rel="stylesheet" href="https://unpkg.com/purecss@2.1.0/build/grids-responsive-min.css">
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

    <section class="invitations-section">
      <div class="pure-g">
        <div class="pure-u-1 pure-u-lg-1-4">
          <div class="list">
            <form id="filters-form" class="search">
              <div class="form-group bold">
                <label for="searchTerm">Buscar</label>
                <input id="searchTerm" name="searchTerm" placeholder="Nombre(s)" type="text" autocomplete="off">
              </div>
            </form>

            <div class="content">
              <div id="my-invitations" class="items"></div>
              <div id="pagination" class="invitation-pagination"></div>
            </div>
          </div>
        </div>

        <div class="pure-u-1 pure-u-lg-3-4">
          <div id="invitation-preview" class="preview desktop">
            <b>SELECCIONA UNA INVITACIÓN</b>
          </div>
        </div>
      </div>
    </section>

    <!-- Page loading -->
    <?php include 'src/components/page-loading.php'; ?>
  </main>

  <!-- Footer -->
  <?php include 'src/components/footer.php'; ?>

  <!-- Required scripts -->
  <?php include 'src/components/required-scripts.php'; ?>

  <!-- Sweet alerts -->
  <!-- <script src="src/plugins/sweetalert/sweetalert.min.js"></script>
  <script src="src/plugins/sweetalert/sweetalert-functions.js"></script> -->

  <script src="<?= BASE_URL; ?>/src/js/my-invitations.js"></script>
</body>

</html>