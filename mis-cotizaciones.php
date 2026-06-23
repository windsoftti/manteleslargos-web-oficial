<?php
include 'inc/user-session.php';

$quotes = getFinalUserQuotes();
?>

<!DOCTYPE html>
<html lang="es">

<head>
  <?php include 'src/components/head.php'; ?>

  <!-- Pure css -->
  <link rel="stylesheet" href="<?= BASE_URL; ?>/src/plugins/purecss/pure-min.css">
  <link rel="stylesheet" href="<?= BASE_URL; ?>/src/plugins/purecss/grids-responsive-min.css">
  <link rel="stylesheet" href="<?= BASE_URL; ?>/src/plugins/purecss/pure-extras.css">

  <link rel="stylesheet" href="<?= BASE_URL; ?>/src/css/pages/invitations.css">
</head>

<body class="navbar-white">
  <!-- Preloader -->
  <?php include 'src/components/preloader.php'; ?>

  <!-- Navbar -->
  <?php include 'src/components/navbar.php'; ?>

  <!-- Main -->
  <main class="main">
    <section class="breadcrumbs">
      <ul>
        <li>
          <a href="<?= BASE_URL; ?>">Inicio</a>
        </li>

        <li>
          <a href="<?= BASE_URL; ?>/mis-cotizaciones">Mis cotizaciones</a>
        </li>
      </ul>

      <div>
        <h1>Mis cotizaciones</h1>
      </div>
    </section>

    <section class="pure-g" style="margin-top: 1rem;">
      <div class="pure-u-23-24 pure-u-lg-22-24 mx-auto">
        <div class="card">
          <?php if (!$quotes) : ?>
            <p>No hay cotizaciones realizadas.</p>
          <?php endif; ?>

          <?php if ($quotes) :
            $row_count = 0;
            $table_odd = true;
          ?>
            <table class="pure-table pure-table-bordered table-mobile">
              <thead>
                <tr>
                  <th>#</th>
                  <th>Datos personales</th>
                  <th>Negocio/Salón</th>
                  <th>Fecha solicitada</th>
                  <th>Estatus</th>
                </tr>
              </thead>
              <tbody>
                <?php while ($row = mysqli_fetch_array($quotes)) : ?>
                  <tr class="<?= $table_odd ? 'pure-table-odd' : '' ?>">
                    <td class="fw-bold"><?= $row_count + 1; ?></td>

                    <td mobile-title="Datos personales">
                      <?= $row['NombreCompleto']; ?><br>
                      <span>
                        <span class="fw-bold">Correo:</span> <?= $row['Email']; ?><br>
                      </span>
                      <span>
                        <span class="fw-bold">Teléfono:</span> <?= $row['Telefono']; ?>
                      </span>
                    </td>

                    <td mobile-title="Negocio/Salón"><?= $row['Salon']; ?></td>

                    <td mobile-title="Fecha"><?= getDateWithMonthName($row['FechaSolicitada']); ?></td>

                    <td>
                      <?php if ($row['Status'] === 'Cancelado') : ?>
                        <span class="pure-badge pure-badge-error">
                          <?= $row['Status']; ?>
                        </span>
                      <?php endif; ?>

                      <?php if ($row['Status'] === 'Pendiente') : ?>
                        <span class="pure-badge pure-badge-warning">
                          <?= $row['Status']; ?>
                        </span>
                      <?php endif; ?>

                      <?php if ($row['Status'] === 'Completado') : ?>
                        <span class="pure-badge pure-badge-success">
                          <?= $row['Status']; ?>
                        </span>
                      <?php endif; ?>
                    </td>
                  </tr>
                  <?php
                  $row_count++;
                  $table_odd = !$table_odd;
                  ?>
                <?php endwhile; ?>
              </tbody>
            </table>
          <?php endif; ?>
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

  <script src="<?= BASE_URL; ?>/src/js/my-quotes.js"></script>
</body>

</html>