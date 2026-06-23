<?php
include 'inc/session-proveedor.php';
$meta_title = 'Egresos';

$page_slug = 'egresos';
include 'inc/verify-user-permissions.php';
?>

<!doctype html>
<html lang="es">

<head>
  <?php include 'inc/meta-tags.php'; ?>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap4-datetimepicker@5.2.3/build/css/bootstrap-datetimepicker.min.css">

  <style>
    .form-group label {
      margin-bottom: 0;
    }

    .form-group label span {
      color: #c92525;
    }
  </style>
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
                <div class="row align-middle">
                  <form id="search-filters-form" class="col-md-9 text-left">
                    <div class="row">
                      <div class="col-md-4">
                        <div class="form-group">
                          <label for="search-date">Buscar por fecha</label>
                          <input id="search-date" class="form-control datepicker" type="text" name="date" onchange="searchEgresos();">
                        </div>
                      </div>

                      <div class="col-md-4">
                        <div class="form-group">
                          <label for="search-concept">Concepto o descripción</label>
                          <input id="search-concept" class="form-control" type="text" name="concept" onkeyup="searchEgresos();">
                        </div>
                      </div>
                    </div>
                  </form>

                  <div class="col-md-3 text-right mt-1">
                    <button class="btn btn-primary btn-add-egreso" data-toggle="modal" data-target="#modal-add-edit-egreso">
                      <i class="fal fa-plus-circle mr-1"></i> Agregar nuevo
                    </button>
                  </div>
                </div>

                <?php include 'modals/egresos.php'; ?>
              </div>

              <div class="card-body" id="list-egresos"></div>
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

  <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment-with-locales.min.js" integrity="sha512-LGXaggshOkD/at6PFNcp2V2unf9LzFq6LE+sChH7ceMTDP0g2kn6Vxwgg7wkPP7AAtX+lmPqPdxB47A0Nz0cMQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap4-datetimepicker@5.2.3/src/js/bootstrap-datetimepicker.min.js"></script>

  <script src="js/functions.js"></script>

  <script src="main/egresos/egresos.js"></script>

  <script>
    $('.datepicker').datetimepicker({
      format: 'DD-MM-YYYY',
      locale: 'es-es'
    });

    $("#search-date").datetimepicker({
      format: 'DD-MM-YYYY',
      locale: 'es-es'
    }).on('dp.change', () => loadEgresos(1));
  </script>
</body>

</html>