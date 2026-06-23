<?php include 'inc/session-root.php'; ?>

<!DOCTYPE html>
<html lang="es">

<head>
  <?php include 'src/components/head.php'; ?>
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
              <h1 class="m-0">Proveedores</h1>
            </div>

            <div class="col-sm-6">
              <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="#"><i class="fas fa-tachometer-alt mr-1"></i>Dashboard</a></li>
                <li class="breadcrumb-item active">Proveedores</li>
              </ol>
            </div>
          </div>
        </div>
      </div>

      <div class="content">
        <div class="container-fluid">
          <div class="row">
            <form id="proveedores-filters-form" class="col-md-12">
              <div class="card">
                <div class="card-header">
                  <div class="row">
                    <div class="col-md-9">
                      <div class="row">
                        <div class="col-12 col-md-6 col-lg-3">
                          <div class="form-group">
                            <label class="form-label" for="search">Buscar</label>
                            <input id="search" class="form-control" type="text" name="search" placeholder="Nombre, Correo..." required>
                          </div>
                        </div>

                        <div class="col-12 col-md-6 col-lg-3">
                          <div class="form-group">
                            <label class="form-label" for="status">Estatus</label>
                            <select id="status" class="form-control" name="status">
                              <option value="">(Todas)</option>
                              <option value="Activo" selected>Activo</option>
                              <option value="Descartado">Eliminado</option>
                            </select>
                          </div>
                        </div>

                        <div class="col-12 col-md-6 col-lg-3">
                          <div class="form-group">
                            <label class="form-label" for="cuenta_proveedor">Cuenta de proveedor</label>
                            <select id="cuenta_proveedor" class="form-control" name="cuenta_proveedor">
                              <option value="">(Todas)</option>
                              <option value="Activo" selected>Activo</option>
                              <option value="Inactivo">Inactivo</option>
                            </select>
                          </div>
                        </div>
                      </div>
                    </div>

                    <div class="col-md-3 d-flex align-items-center justify-content-end p-1">
                      <div class="dropdown">
                        <div class="btn-group">
                          <button type="button" class="btn btn-primary btn-add-user" data-toggle="modal" data-target="#modal-users">
                            <i class="fa fa-plus-circle"></i><br>Nuevo
                          </button>

                          <?php include 'src/components/per-page.php'; ?>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>

                <div class="card-body p-0">
                  <div id="proveedores-table"></div>
                </div>
              </div>
            </form>
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

  <script src="src/plugins/bs-validator/bs-validator.js"></script>
  <script src="src/plugins/sweetalert/sweetalert.min.js"></script>
  <script src="src/plugins/sweetalert/sweetalert-functions.js"></script>
  <script src="src/plugins/datatable/datatable.js"></script>

  <script>
    // DATA TABLE
    const datatable = new DataTable({
      identifier: 'proveedores'
    });

    datatable._initDataTable();

    const load = (page = 1) => datatable._load(page);
  </script>
</body>

</html>