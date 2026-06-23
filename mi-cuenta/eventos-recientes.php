<?php
include 'inc/session-admin.php';
$meta_title = 'Eventos recientes';
?>

<!doctype html>
<html lang="es">

<head>
  <?php include 'inc/meta-tags.php'; ?>
  <link rel="stylesheet" href="css/multiple-file-picker.css">
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
                    <div class="col-md-6">
                      <div class="input-group input-group-lg bg-white border">
                        <div class="input-group-prepend">
                          <button class="btn pr-0 shadow-none" type="button" onclick="searchRecentEvents()"><i class="far fa-search"></i></button>
                        </div>
                        <input type="text" id="search-by-recent-event" class="form-control bg-transparent border-0 shadow-none text-body" placeholder="Buscar tipo de evento" name="searchByRecentEvent" onkeyup="searchRecentEvents()">
                      </div>
                    </div>
                  </form>

                  <div class="col-md-3 text-right mt-1">
                    <button class="btn btn-primary btn-add-recent-event" data-toggle="modal" data-target="#modal-add-edit-recent-event">
                      <i class="fal fa-plus-circle mr-1"></i> Agregar nuevo
                    </button>
                  </div>
                </div>

                <?php include 'modals/recent-events.php'; ?>
              </div>

              <div class="card-body" id="list-recent-events"></div>
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

  <script src="plugins/ckeditor5/ckeditor5-build-classic/ckeditor.js"></script>
  <script src="plugins/jquery-validation/jquery.validate.min.js"></script>
  <script src="plugins/jquery-validation/additional-methods.min.js"></script>

  <script src="js/functions.js"></script>
  <script src="js/dynamic-picker.js"></script>
  <script src="js/dynamic-multiple-picker.js"></script>

  <script src="main/recent-events/recent-events.js"></script>
  <script src="main/recent-events/validate.js"></script>

  <script>
    createPicker('image');
    createMultiplePicker('gallery');
  </script>
</body>

</html>