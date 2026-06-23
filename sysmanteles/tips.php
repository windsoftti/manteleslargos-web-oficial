<?php include 'inc/session-root.php'; ?>

<!DOCTYPE html>
<html lang="es">

<head>
  <?php include 'src/components/head.php'; ?>

  <!-- CS FILEPICKERS -->
  <link rel="stylesheet" href="src/plugins/cs-filepicker/cs-filepicker.css">
  <link rel="stylesheet" href="src/plugins/cs-multifilepicker/cs-multiple-filepicker.css">
</head>

<body class="hold-transition sidebar-mini layout-fixed layout-navbar-fixed">
  <div class="wrapper">
    <!-- Preloader -->
    <?php include 'src/components/preloader.php'; ?>

    <!-- Navbar -->
    <?php include 'src/components/navbar.php'; ?>

    <!-- Sidebar -->
    <?php include 'src/components/sidebar.php'; ?>

    <div class="content-wrapper">
      <div class="content-header">
        <div class="container-fluid">
          <div class="row mb-2">
            <div class="col-sm-6">
              <h1 class="m-0">Tips</h1>
            </div>

            <div class="col-sm-6">
              <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="#"><i class="fas fa-tachometer-alt mr-1"></i>Dashboard</a></li>
                <li class="breadcrumb-item active">Tips</li>
              </ol>
            </div>
          </div>
        </div>
      </div>

      <div class="content">
        <div class="container-fluid">
          <div class="row">
            <div class="col-md-12">
              <div class="card">
                <div class="card-header">
                  <form id="search-form" class="row" autocomplete="off">
                    <div class="col-md-9">
                      <div class="row">
                        <div class="col-md-4">
                          <label for="search">Buscar tip</label>
                          <div class="input-group">
                            <input id="search" class="form-control" type="text" name="search" required>
                            <div class="input-group-append">
                              <button class="btn btn-default" type="submit">
                                <i class="fas fa-search"></i>
                              </button>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>

                    <div class="col-md-3 d-flex align-items-center justify-content-end p-1">
                      <div class="dropdown">
                        <div class="btn-group">
                          <button type="button" class="btn btn-primary btn-add-tip" data-toggle="modal" data-target="#modal-tips">
                            <i class="fa fa-plus-circle"></i><br>Nuevo
                          </button>

                          <?php include 'src/components/per-page.php'; ?>
                        </div>
                      </div>
                    </div>
                  </form>
                </div>

                <?php include 'src/modals/tips.php'; ?>

                <div class="card-body p-0">
                  <div id="list-tips"></div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Page loading -->
    <?php include 'src/components/page-loading.php'; ?>

    <!-- Footer -->
    <?php include 'src/components/footer.php'; ?>
  </div>

  <!-- Required scripts -->
  <?php include 'src/components/required-scripts.php'; ?>

  <!-- SWEETALERTS -->
  <script src="src/plugins/sweetalert/sweetalert.min.js"></script>
  <script src="src/plugins/sweetalert/sweetalert-functions.js"></script>

  <!-- CS FILEPICKERS -->
  <script src="src/plugins/cs-filepicker/cs-filepicker.js"></script>
  <script src="src/plugins/cs-multifilepicker/cs-multiple-filepicker.js"></script>

  <!-- CKEDITOR 4 -->
  <script src="https://cdn.ckeditor.com/4.19.0/standard/ckeditor.js"></script>

  <script src="src/js/tips.js"></script>
</body>

</html>