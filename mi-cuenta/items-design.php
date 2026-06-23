<?php
include 'inc/session-proveedor.php';
$meta_title = 'Items';
?>

<!doctype html>
<html lang="es">

<head>
  <?php include 'inc/meta-tags.php'; ?>
</head>

<body class="bg-gray-01">
  <div class="wrapper dashboard-wrapper">
    <div class="d-flex flex-wrap flex-xl-nowrap">
      <div class="db-sidebar bg-white" id="custom-sidebar">
        <nav class="navbar navbar-expand-xl navbar-light d-block px-0 header-sticky dashboard-nav py-0">
          <div class="sticky-area shadow-xs-1 py-3">
            <!-- MOBILE HEADER -->
            <?php include 'inc/mobile-header.php'; ?>

            <!-- SIDEBAR -->
            <?php include 'inc/sidebar.php' ?>
          </div>
        </nav>
      </div>

      <div class="page-content">
        <!-- HEADER -->
        <?php include 'inc/header.php'; ?>

        <main id="content" class="bg-gray-01">
          <div class="p-2">
            <div class="mr-md-auto">
              <h2 class="text-heading fs-22 lh-15">
                <?= $meta_title ?>
              </h2>
            </div>

            <div class="card">
              <div class="card-header">
                <h3 class="card-title">Items</h3>
              </div>

              <div class="card-body">
                <p>Cotizaciones</p>

                <div class="row">
                  <div class="col-12 col-lg-6">
                    <div class="card">
                      <div class="card-body">
                        <div class="d-flex align-items-center w-100">
                          <div class="d-flex flex-column flex-grow-1 pr-2">
                            <a class="font-weight-bold fs-5 text-blue" href="#" style="text-decoration: underline;">
                              Yonatan Salazar López
                              <i class="fal fa-info-circle text-danger"></i>
                            </a>

                            <span>jona0119973@gmail.com</span>
                            <span class="mb-2">9631893615</span>

                            <span><b>Día del evento:</b> 02-12-1997</span>
                            <span class="badge badge-danger w-25">Pendiente</span>

                            <div class="d-flex align-items-center mt-3" style="gap: 0.5rem;">
                              <a href="#" style="font-size: 1.3rem;">
                                <i class="fa fa-phone text-dark"></i>
                              </a>

                              <a href="#">
                                <img src="images/whatsapp-logo.png" style="height: 26px;">
                              </a>

                              <a href="#" style="font-size: 1.3rem;">
                                <i class="fa fa-envelope"></i>
                              </a>
                            </div>
                          </div>

                          <div>
                            <button class="btn btn-primary btn-sm">
                              <i class="fa fa-ellipsis-v"></i>
                            </button>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- PAGE LOADING -->
          <?php include 'inc/page-loading.php' ?>
        </main>
      </div>
    </div>
  </div>

  <!-- REQUIRED SCRIPTS -->
  <?php include 'inc/required-scripts.php'; ?>
  <?php include 'inc/svg.php'; ?>

  <script src="js/functions.js"></script>
</body>

</html>