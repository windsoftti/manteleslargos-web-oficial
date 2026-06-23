<?php include 'inc/session-root.php'; ?>

<!DOCTYPE html>
<html lang="es">

<head>
  <?php include 'src/components/head.php'; ?>
</head>

<body class="hold-transition sidebar-mini layout-fixed layout-navbar-fixed">

<div class="wrapper">

  <?php include 'src/components/preloader.php'; ?>
  <?php include 'src/components/navbar.php'; ?>
  <?php include 'src/components/sidebar.php'; ?>

  <div class="content-wrapper">

    <div class="content-header">
      <div class="container-fluid">

        <div class="row mb-2">

          <div class="col-sm-6">
            <h1 class="m-0">Suscripciones</h1>
          </div>

          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item">
                <a href="#">
                  <i class="fas fa-tachometer-alt mr-1"></i>
                  Dashboard
                </a>
              </li>
              <li class="breadcrumb-item active">
                Suscripciones
              </li>
            </ol>
          </div>

        </div>

      </div>
    </div>

    <div class="content">

      <div class="container-fluid">

        <div class="card">

          <div class="card-header">

            <form id="search-form" class="row">

              <div class="col-md-9">

                <label>Buscar negocio</label>

                <div class="input-group">

                  <input
                    type="text"
                    class="form-control"
                    name="search"
                    id="search">

                  <div class="input-group-append">
                    <button class="btn btn-default">
                      <i class="fas fa-search"></i>
                    </button>
                  </div>

                </div>

              </div>

              <div class="col-md-3 d-flex justify-content-end">

                <div class="btn-group">

                  <button
                    type="button"
                    class="btn btn-primary btn-add-subscription"
                    data-toggle="modal"
                    data-target="#modal-subscriptions">

                    <i class="fas fa-plus-circle"></i><br>
                    Nueva

                  </button>

                  <?php include 'src/components/per-page.php'; ?>

                </div>

              </div>

            </form>

          </div>

          <?php include 'src/modals/suscripciones.php'; ?>

          <div class="card-body p-0">

            <div id="list_subscriptions"></div>

          </div>

        </div>

      </div>

    </div>

  </div>

  <?php include 'src/components/page-loading.php'; ?>
  <?php include 'src/components/footer.php'; ?>

</div>

<?php include 'src/components/required-scripts.php'; ?>

<script src="src/plugins/bs-validator/bs-validator.js"></script>
<script src="src/plugins/sweetalert/sweetalert.min.js"></script>
<script src="src/plugins/sweetalert/sweetalert-functions.js"></script>

<script src="src/js/suscripciones.js"></script>

</body>
</html>