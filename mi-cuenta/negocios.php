<?php
include 'inc/session-proveedor.php';
$meta_title = 'Negocios';

$page_slug = 'listar-negocios';
include 'inc/verify-user-permissions.php';
?>

<!doctype html>
<html lang="es">

<head>
  <?php include 'inc/meta-tags.php'; ?>
</head>

<body>
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
                          <button class="btn pr-0 shadow-none" type="button" onclick="searchBusiness()"><i class="far fa-search"></i></button>
                        </div>
                        <input type="text" id="search-by-business" class="form-control bg-transparent border-0 shadow-none text-body" placeholder="Buscar tipo de evento" name="searchByEventType" onkeyup="searchBusiness()">
                      </div>
                    </div>
                  </form>

                  <?php if (verifyUserPermissions('agregar-negocios')) :
                    $add_business_attribute = $session_target_free_plan;
                    $num_business = getNumBusiness();
                    if ($session_user_plan === 'Básico' || $num_business < 1) $add_business_attribute = 'agregar-negocio';
                  ?>
                    <div class="col-md-3 text-right mt-1">
                      <a class="btn btn-primary btn-add-event-type" href="<?= $add_business_attribute; ?>">
                        <i class="fal fa-plus-circle mr-1"></i> Agregar nuevo
                      </a>
                    </div>
                  <?php endif; ?>
                </div>
              </div>

              <div class="card-body" id="list-business"></div>
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
  <script src="main/business/business.js"></script>
</body>

</html>