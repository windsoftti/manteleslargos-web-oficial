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
              <h1 class="m-0">Dashboard</h1>
            </div>

            <div class="col-sm-6">
              <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="#"><i class="fas fa-tachometer-alt mr-1"></i>Home</a></li>
                <li class="breadcrumb-item active">Dashboard</li>
              </ol>
            </div>
          </div>
        </div>
      </div>

      <div class="content">
        <div class="container-fluid">
          <div class="row">
            <div class="col-12 col-sm-6 col-md-3">
              <div class="info-box elevation-0 border">
                <span class="info-box-icon bg-info elevation-1"><i class="fas fa-cog"></i></span>
                <div class="info-box-content">
                  <span class="info-box-text">CPU Traffic</span>
                  <span class="info-box-number">
                    10
                    <small>%</small>
                  </span>
                </div>
              </div>
            </div>

            <div class="col-12 col-sm-6 col-md-3">
              <div class="info-box elevation-0 border">
                <span class="info-box-icon bg-danger elevation-1"><i class="fas fa-thumbs-up"></i></span>
                <div class="info-box-content">
                  <span class="info-box-text">Likes</span>
                  <span class="info-box-number">41,410</span>
                </div>
              </div>
            </div>


            <div class="clearfix hidden-md-up"></div>
            <div class="col-12 col-sm-6 col-md-3">
              <div class="info-box elevation-0 border">
                <span class="info-box-icon bg-success elevation-1"><i class="fas fa-shopping-cart"></i></span>
                <div class="info-box-content">
                  <span class="info-box-text">Sales</span>
                  <span class="info-box-number">760</span>
                </div>
              </div>
            </div>

            <div class="col-12 col-sm-6 col-md-3">
              <div class="info-box elevation-0 border">
                <span class="info-box-icon bg-warning elevation-1"><i class="fas fa-users"></i></span>
                <div class="info-box-content">
                  <span class="info-box-text">New Members</span>
                  <span class="info-box-number">2,000</span>
                </div>
              </div>
            </div>
          </div>

          <div class="row">
            <div class="col-lg-12">
              <div class="card">
                <div class="card-body">
                  <h5 class="card-title mb-3">Actividades pendientes</h5>

                  <div class="mt-2">
                    <div class="table-responsive">
                      <table class="table table-sm">
                        <thead>
                          <tr>
                            <th>#</th>
                            <th>Cliente</th>
                            <th>Fecha</th>
                            <th>Hora</th>
                            <th>Pendiente</th>
                            <th></th>
                          </tr>
                        </thead>

                        <!--tbody>
                          <tr>
                            <td class="align-middle">1</td>
                            <td class="align-middle">Yonatan Salazar lópez</td>
                            <td class="align-middle">09/07/2018</td>
                            <td class="align-middle">12:00 PM</td>
                            <td class="align-middle">Lorem ipsum dolor sit, amet consectetur adipisicin...</td>
                            <td class="align-middle">
                              <div class="btn-group btn-group-sm dropleft">
                                <button type="button" class="btn btn-primary rounded" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                  <i class="fas fa-ellipsis-v"></i>
                                </button>
                                <div class="dropdown-menu">
                                  <a class="dropdown-item" href="#">
                                    <i class="fas fa-check-circle mr-1"></i> Marcar como realizado
                                  </a>
                                  <div class="dropdown-divider"></div>
                                  <a class="dropdown-item" href="#">
                                    <i class="fas fa-pencil-alt"></i> Editar
                                  </a>
                                  <a class="dropdown-item" href="#">
                                    <i class="fas fa-trash mr-1"></i> Eliminar
                                  </a>
                                </div>
                              </div>
                            </td>
                          </tr>

                          <tr>
                            <td class="align-middle">2</td>
                            <td class="align-middle">Yonatan Salazar lópez</td>
                            <td class="align-middle">09/07/2018</td>
                            <td class="align-middle">12:00 PM</td>
                            <td class="align-middle">Lorem ipsum dolor sit, amet consectetur adipisicin...</td>
                            <td class="align-middle">
                              <div class="btn-group btn-group-sm dropleft">
                                <button type="button" class="btn btn-primary rounded" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                  <i class="fas fa-ellipsis-v"></i>
                                </button>
                                <div class="dropdown-menu">
                                  <a class="dropdown-item" href="#">
                                    <i class="fas fa-check-circle mr-1"></i> Marcar como realizado
                                  </a>
                                  <div class="dropdown-divider"></div>
                                  <a class="dropdown-item" href="#">
                                    <i class="fas fa-pencil-alt"></i> Editar
                                  </a>
                                  <a class="dropdown-item" href="#">
                                    <i class="fas fa-trash mr-1"></i> Eliminar
                                  </a>
                                </div>
                              </div>
                            </td>
                          </tr>

                          <tr>
                            <td class="align-middle">3</td>
                            <td class="align-middle">Yonatan Salazar lópez</td>
                            <td class="align-middle">09/07/2018</td>
                            <td class="align-middle">12:00 PM</td>
                            <td class="align-middle">Lorem ipsum dolor sit, amet consectetur adipisicin...</td>
                            <td class="align-middle">
                              <div class="btn-group btn-group-sm dropleft">
                                <button type="button" class="btn btn-primary rounded" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                  <i class="fas fa-ellipsis-v"></i>
                                </button>
                                <div class="dropdown-menu">
                                  <a class="dropdown-item" href="#">
                                    <i class="fas fa-check-circle mr-1"></i> Marcar como realizado
                                  </a>
                                  <div class="dropdown-divider"></div>
                                  <a class="dropdown-item" href="#">
                                    <i class="fas fa-pencil-alt"></i> Editar
                                  </a>
                                  <a class="dropdown-item" href="#">
                                    <i class="fas fa-trash mr-1"></i> Eliminar
                                  </a>
                                </div>
                              </div>
                            </td>
                          </tr>
                        </tbody-->
                      </table>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <div class="row">
            <div class="col-lg-6">
              <div class="card">
                <div class="card-body">
                  <h5 class="card-title mb-3">Pagos vencidos</h5>

                  <div class="mt-2">
                    <div class="table-responsive">
                      <table class="table table-sm">
                        <thead>
                          <tr>
                            <th>#</th>
                            <th>Cliente</th>
                            <th>Vencido el</th>
                            <th>Monto</th>
                            <th>Saldo</th>
                          </tr>
                        </thead>

                        <!--tbody>
                          <tr>
                            <td class="align-middle">1</td>
                            <td class="align-middle">Yonatan Salazar lópez</td>
                            <td class="align-middle">09/07/2018</td>
                            <td class="align-middle">$16,000.00</td>
                            <td class="align-middle">$9,000.00</td>
                          </tr>

                          <tr>
                            <td class="align-middle">1</td>
                            <td class="align-middle">Yonatan Salazar lópez</td>
                            <td class="align-middle">09/07/2018</td>
                            <td class="align-middle">$16,000.00</td>
                            <td class="align-middle">$9,000.00</td>
                          </tr>

                          <tr>
                            <td class="align-middle">1</td>
                            <td class="align-middle">Yonatan Salazar lópez</td>
                            <td class="align-middle">09/07/2018</td>
                            <td class="align-middle">$16,000.00</td>
                            <td class="align-middle">$9,000.00</td>
                          </tr>
                        </tbody-->
                      </table>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <div class="col-lg-6">
              <div class="card">
                <div class="card-body">
                  <h5 class="card-title mb-3">Pagos vencidos</h5>

                  <div class="mt-2">
                    <div class="table-responsive">
                      <table class="table table-sm">
                        <thead>
                          <tr>
                            <th>#</th>
                            <th>Cliente</th>
                            <th>Vencido el</th>
                            <th>Monto</th>
                            <th>Saldo</th>
                          </tr>
                        </thead>

                        <!--tbody>
                          <tr>
                            <td class="align-middle">1</td>
                            <td class="align-middle">Yonatan Salazar lópez</td>
                            <td class="align-middle">09/07/2018</td>
                            <td class="align-middle">$16,000.00</td>
                            <td class="align-middle">$9,000.00</td>
                          </tr>

                          <tr>
                            <td class="align-middle">1</td>
                            <td class="align-middle">Yonatan Salazar lópez</td>
                            <td class="align-middle">09/07/2018</td>
                            <td class="align-middle">$16,000.00</td>
                            <td class="align-middle">$9,000.00</td>
                          </tr>

                          <tr>
                            <td class="align-middle">1</td>
                            <td class="align-middle">Yonatan Salazar lópez</td>
                            <td class="align-middle">09/07/2018</td>
                            <td class="align-middle">$16,000.00</td>
                            <td class="align-middle">$9,000.00</td>
                          </tr>
                        </tbody-->
                      </table>
                    </div>
                  </div>
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
</body>

</html>