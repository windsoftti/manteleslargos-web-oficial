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

    <section class="invitation-section">
      <div class="invitation-list">
        <div class="invitation-list-header">
          <form id="filters-form" class="filters" autocomplete="off">
            <div class="pure-g">
              <div class="pure-u-1 pure-u-sm-1-4">
                <div class="form-group">
                  <label for="searchTerm">Buscar</label>
                  <input id="searchTerm" name="searchTerm" placeholder="Invitados" type="text">
                </div>
              </div>
            </div>
          </form>
        </div>

        <div class="invitation-list-body">
          <div id="list_my_invitations" class="invitations"></div>
        </div>

        <div class="invitation-list-footer">
          <div class="pagination">
            <ul>
              <li>
                <a href="javascript:void(0)">1</a>
              </li>

              <li class="active">
                <a href="javascript:void(0)">2</a>
              </li>

              <li>
                <a href="javascript:void(0)">3</a>
              </li>

              <li class="arrow">
                <a href="javascript:void(0)">
                  Siguiente
                  <ion-icon name="arrow-forward-outline"></ion-icon>
                </a>
              </li>
            </ul>
          </div>
        </div>
      </div>
    </section>
  </main>

  <!-- Footer -->
  <?php include 'src/components/footer.php'; ?>

  <!-- Required scripts -->
  <?php include 'src/components/required-scripts.php'; ?>

  <script src="<?= BASE_URL; ?>/src/js/my-invitations.js"></script>
</body>

</html>