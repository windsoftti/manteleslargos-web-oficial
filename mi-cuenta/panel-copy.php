<?php
include 'inc/session.php';

$meta_title = 'Panel';

$idUsuario    = $_SESSION['session_user_id'];

// numero de negocios del proveedor
$query_result_ns = mysqli_query($mysqli, "SELECT idSalon FROM salones WHERE idUsuario = '$idUsuario'");
$rowcount_ns    = mysqli_num_rows($query_result_ns);

// numero de visitas a su negocio
$query_result_vs = mysqli_query($mysqli, "SELECT SUM(Visitas) as visitas FROM salones WHERE idUsuario = '$idUsuario'");
$row_vs          = mysqli_fetch_array($query_result_vs);

// numero de cotizaciones pendientes
$query_result_cs = mysqli_query($mysqli, "SELECT idCotizacion FROM cotizaciones WHERE idProveedor = '$idUsuario' AND Status = 'Pendiente'");
$rowcount_cs    = mysqli_num_rows($query_result_cs);

// numero de ventas concretadas
$query_result_ec = mysqli_query($mysqli, "SELECT idReservacion FROM reservaciones WHERE idUsuario = '$idUsuario'");
$rowcount_ec    = mysqli_num_rows($query_result_ec);

// Ventas concretadas
$today_year   = date('Y');
$today_month  = date('m');
$array_months = ['01', '02', '03', '04', '05', '06', '07', '08', '09', '10', '11', '12'];
$sales_made = "[";

foreach ($array_months as $key => $value) :
  $month = $array_months[$key];

  $query_sales_made = "SELECT
      COUNT(idReservacion) AS Total
    FROM reservaciones
    WHERE
      MONTH(FechaDeAgendado)  = $month AND
      YEAR(FechaDeAgendado)   = $today_year AND
      idUsuario               = $idUsuario
    LIMIT 1
  ";

  $query_sales_made_result = mysqli_query($mysqli, $query_sales_made);
  $sales_made_data  = mysqli_fetch_array($query_sales_made_result);
  $sales_made_count = $sales_made_data['Total'];

  if ($today_month != $month) $sales_made .= $sales_made_count . ',';
  if ($today_month == $month) {
    $sales_made .= $sales_made_count . ']';
    break;
  }
//$coma = $month != '12' ? ',' : '';
//$sales_made .= $sales_made_count . $coma;
endforeach;

//$sales_made .= "]"
?>

<!doctype html>
<html lang="es">

<head>
  <?php include 'inc/meta-tags.php'; ?>
  <link rel="stylesheet" href="plugins/chartjs/Chart.min.css">
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
          <div class="px-3 px-lg-6 px-xxl-13 py-5 py-lg-10">
            <div class="d-flex flex-wrap flex-md-nowrap mb-6">
              <div class="mr-0 mr-md-auto">
                <h2 class="mb-0 text-heading fs-22 lh-15">Bienvenido, <?= $_SESSION['session_user_name']; ?>!</h2>
                <p>¡Tenemos grandes herramientas que te ayudarán a vender más!</p>
              </div>
              <div>
                <a href="./agregar-negocio" class="btn btn-primary btn-lg">
                  <span>Agregar nuevo negocio</span>
                  <span class="d-inline-block ml-1 fs-20 lh-1"><svg class="icon icon-add-new">
                      <use xlink:href="#icon-add-new"></use>
                    </svg></span>
                </a>
              </div>
            </div>

            <div class="row">
              <div class="col-sm-6 col-xxl-3 mb-6">
                <div class="card">
                  <div class="card-body row align-items-center px-4 py-5">
                    <div class="col-5">
                      <span class="w-83px h-83 d-flex align-items-center justify-content-center fs-36 badge badge-blue badge-circle">
                        <i class="fal fa-solid fa-building"></i>
                        <!--svg class="icon icon-1">
                          <use xlink:href="#icon-1"></use>
                        </svg-->
                      </span>
                    </div>
                    <div class="col-7 text-center">
                      <p class="fs-42 lh-12 mb-0 counterup" data-start="0" data-end="<?= $rowcount_ns ?>" data-decimals="0" data-duration="0" data-separator=""><?= $rowcount_ns ?></p>
                      <p>Empresas<br>registradas</p>
                    </div>
                  </div>
                </div>
              </div>
              <div class="col-sm-6 col-xxl-3 mb-6">
                <div class="card">
                  <div class="card-body row align-items-center px-4 py-5">
                    <div class="col-5">
                      <span class="w-83px h-83 d-flex align-items-center justify-content-center fs-36 badge badge-blue badge-circle">
                        <i class="fal fa-solid fa-eye"></i>
                      </span>
                    </div>
                    <div class="col-7 text-center">
                      <p class="fs-42 lh-12 mb-0 counterup" data-start="0" data-end="<?= $row_vs['visitas'] ?>" data-decimals="0" data-duration="0" data-separator=""><?= $row_vs['visitas'] ?></p>
                      <p>Visitas<br>totales</p>
                    </div>
                  </div>
                </div>
              </div>
              <div class="col-sm-6 col-xxl-3 mb-6">
                <div class="card">
                  <div class="card-body row align-items-center px-4 py-5">
                    <div class="col-5">
                      <span class="w-83px h-83 d-flex align-items-center justify-content-center fs-36 badge badge-blue badge-circle">
                        <i class="fal fa-solid fa-handshake"></i>
                      </span>
                    </div>
                    <div class="col-7 text-center">
                      <p class="fs-42 lh-12 mb-0 counterup" data-start="0" data-end="<?= $rowcount_ec ?>" data-decimals="0" data-duration="0" data-separator=""><?= $rowcount_ec ?></p>
                      <p>Ventas<br>realizadas</p>
                    </div>
                  </div>
                </div>
              </div>
              <div class="col-sm-6 col-xxl-3 mb-6">
                <div class="card">
                  <div class="card-body row align-items-center px-4 py-5">
                    <div class="col-5">
                      <span class="w-83px h-83 d-flex align-items-center justify-content-center fs-36 badge badge-blue badge-circle">
                        <i class="fal fa-engine-warning"></i>
                      </span>
                    </div>
                    <div class="col-7 text-center">
                      <p class="fs-42 lh-12 mb-0 counterup" data-start="0" data-end="<?= $rowcount_cs ?>" data-decimals="0" data-duration="0" data-separator=""><?= $rowcount_cs ?></p>
                      <p>Cotizaciones<br>pendientes</p>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <div class="row">
              <div class="col-xxl-8 mb-6">
                <div class="card px-7 py-6 h-100 chart">
                  <div class="card-body p-0 collapse-tabs">
                    <div class="d-flex align-items-center mb-5">
                      <h2 class="mb-0 text-heading fs-22 lh-15 mr-auto">Estadisticas de ventas <?= $today_year; ?></h2>
                      <ul class="nav nav-pills justify-content-end d-none d-sm-flex nav-pills-01" role="tablist">
                        <li class="nav-item px-5 py-1">
                          <a class="nav-link active bg-transparent shadow-none p-0 letter-spacing-1" id="months-tab" data-toggle="tab" href="#months" role="tab" aria-controls="months" aria-selected="true">Ventas realizadas</a>
                        </li>
                        <!-- <li class="nav-item px-5 py-1">
                          <a class="nav-link bg-transparent shadow-none p-0 letter-spacing-1" id="weekly-tab" data-toggle="tab" href="#weekly" role="tab" aria-controls="weekly" aria-selected="false">Weekly</a>
                        </li> -->
                        <!-- <li class="nav-item px-5 py-1">
                          <a class="nav-link bg-transparent shadow-none p-0 letter-spacing-1" id="monthly-tab" data-toggle="tab" href="#monthly" role="tab" aria-controls="monthly" aria-selected="false">Monthly</a>
                        </li> -->
                      </ul>
                    </div>
                    <div class="tab-content shadow-none p-0">
                      <div id="collapse-tabs-accordion">
                        <div class="tab-pane tab-pane-parent fade show active px-0" id="months" role="tabpanel" aria-labelledby="months-tab">
                          <div class="card bg-transparent mb-sm-0 border-0">
                            <div class="card-header d-block d-sm-none bg-transparent px-0 py-1 border-bottom-0" id="heading-months">
                              <h5 class="mb-0">
                                <button class="btn collapse-parent font-size-h5 btn-block border shadow-none" data-toggle="collapse" data-target="#months-collapse" aria-expanded="true" aria-controls="months-collapse">
                                  Meses
                                </button>
                              </h5>
                            </div>
                            <div id="months-collapse" class="collapse show collapsible" aria-labelledby="heading-months" data-parent="#collapse-tabs-accordion">
                              <div class="card-body p-0 py-4">
                                <?php
                                $date = date('Y-m-d');
                                ?>
                                <canvas class="chartjs" data-chart-options="[]" data-chart-labels='["Ene","Feb","Mar","Abr","May","Jun","Jul","Ago","Sep","Oct","Nov","Dic"]' data-chart-datasets='[
                                    {
                                      "label":"Ventas realizadas",
                                      "data":<?= $sales_made; ?>,
                                      "backgroundColor":"rgba(105, 105, 235, 0.1)",
                                      "borderColor":"#6969eb",
                                      "borderWidth":3,
                                      "fill":true
                                    }
                                  ]'>
                                </canvas>
                              </div>
                            </div>
                          </div>
                        </div>

                        <!-- <div class="tab-pane tab-pane-parent fade px-0" id="weekly" role="tabpanel" aria-labelledby="weekly-tab">
                          <div class="card bg-transparent mb-sm-0 border-0">
                            <div class="card-header d-block d-sm-none bg-transparent px-0 py-1 border-bottom-0" id="headingWeekly">
                              <h5 class="mb-0">
                                <button class="btn collapse-parent font-size-h5 btn-block collapsed border shadow-none" data-toggle="collapse" data-target="#weekly-collapse" aria-expanded="true" aria-controls="weekly-collapse">
                                  Weekly
                                </button>
                              </h5>
                            </div>
                            <div id="weekly-collapse" class="collapse collapsible" aria-labelledby="headingWeekly" data-parent="#collapse-tabs-accordion">
                              <div class="card-body p-0 py-4">
                                <canvas class="chartjs" data-chart-options="[]" data-chart-labels='["Mar 12","Mar 13","Mar 14","Mar 15","Mar 16","Mar 17","Mar 18","Mar 19"]' data-chart-datasets='[{"label":"Clicked","data":[0,13,9,3,15,15,10,0],"backgroundColor":"rgba(105, 105, 235, 0.1)","borderColor":"#6969eb","borderWidth":3,"fill":true},{"label":"View","data":[10,20,18,15,28,33,27,10],"backgroundColor":"rgba(254, 91, 52, 0.1)","borderColor":"#ff6935","borderWidth":3,"fill":true}]'>
                                </canvas>
                              </div>
                            </div>
                          </div>
                        </div>

                        <div class="tab-pane tab-pane-parent fade px-0" id="monthly" role="tabpanel" aria-labelledby="monthly-tab">
                          <div class="card bg-transparent mb-sm-0 border-0">
                            <div class="card-header d-block d-sm-none bg-transparent px-0 py-1 border-bottom-0" id="headingMonthly">
                              <h5 class="mb-0">
                                <button class="btn btn-block collapse-parent collapsed border shadow-none" data-toggle="collapse" data-target="#monthly-collapse" aria-expanded="true" aria-controls="monthly-collapse">
                                  Monthly
                                </button>
                              </h5>
                            </div>
                            <div id="monthly-collapse" class="collapse collapsible" aria-labelledby="headingMonthly" data-parent="#collapse-tabs-accordion">
                              <div class="card-body p-0 py-4">
                                <canvas class="chartjs" data-chart-options="[]" data-chart-labels='["Ene","Feb","Mar","Abr","May","Jun","Jul","Ago","Sep","Oct","Nov","Dic"]' data-chart-datasets='[{"label":"Clicked","data":[2,15,20,10,15,20,10,0,20,30,10,0],"backgroundColor":"rgba(105, 105, 235, 0.1)","borderColor":"#6969eb","borderWidth":3,"fill":true},{"label":"View","data":[10,20,18,15,28,33,27,10,20,30,10,0],"backgroundColor":"rgba(254, 91, 52, 0.1)","borderColor":"#ff6935","borderWidth":3,"fill":true}]'>
                                </canvas>
                              </div>
                            </div>
                          </div>
                        </div> -->
                      </div>
                    </div>
                  </div>
                </div>
              </div>

              <div class="col-xxl-4 mb-6">
                <div class="card px-7 py-6 h-100">
                  <div class="card-body p-0">
                    <h2 class="mb-2 text-heading fs-22 lh-15">Recent Activities</h2>
                    <ul class="list-group list-group-no-border">
                      <li class="list-group-item px-0 py-2">
                        <div class="media align-items-center">
                          <div class="badge badge-blue w-40px h-40 d-flex align-items-center justify-content-center property fs-18 mr-3">
                            <svg class="icon icon-1">
                              <use xlink:href="#icon-1"></use>
                            </svg>
                          </div>
                          <div class="media-body">
                            Your listing <a href="#" class="text-heading"> Villa Called Archangel</a> has been
                            approved
                          </div>
                        </div>
                      </li>
                      <li class="list-group-item px-0 py-2">
                        <div class="media align-items-center">
                          <div class="badge badge-yellow w-40px h-40 d-flex align-items-center justify-content-center fs-18 mr-3">
                            <svg class="icon icon-review">
                              <use xlink:href="#icon-review"></use>
                            </svg>
                          </div>
                          <div class="media-body">
                            Dollie Horton left a review on
                            <a href="#" class="text-heading"> Villa
                              Called Archangel</a>
                          </div>
                        </div>
                      </li>
                      <li class="list-group-item px-0 py-2">
                        <div class="media align-items-center">
                          <div class="badge badge-pink w-40px h-40 d-flex align-items-center justify-content-center fs-18 mr-3">
                            <svg class="icon icon-heart">
                              <use xlink:href="#icon-heart"></use>
                            </svg>
                          </div>
                          <div class="media-body">
                            Someone favorites your <a href="#" class="text-heading"> Adorable Garden Gingerbread
                              House</a>
                            listing
                          </div>
                        </div>
                      </li>
                      <li class="list-group-item px-0 py-2">
                        <div class="media align-items-center">
                          <div class="badge badge-pink w-40px h-40 d-flex align-items-center justify-content-center fs-18 mr-3">
                            <svg class="icon icon-heart">
                              <use xlink:href="#icon-heart"></use>
                            </svg>
                          </div>
                          <div class="media-body">
                            Someone favorites your <a href="#" class="text-heading"> Adorable Garden Gingerbread
                              House</a>
                            listing
                          </div>
                        </div>
                      </li>
                      <li class="list-group-item px-0 py-2">
                        <div class="media align-items-center">
                          <div class="badge badge-blue w-40px h-40 d-flex align-items-center justify-content-center fs-18 mr-3">
                            <svg class="icon icon-1">
                              <use xlink:href="#icon-1"></use>
                            </svg>
                          </div>
                          <div class="media-body">
                            Your listing <a href="#" class="text-heading"> Villa Called Archangel</a> has been
                            approved
                          </div>
                        </div>
                      </li>
                      <li class="list-group-item px-0 py-2">
                        <div class="media align-items-center">
                          <div class="badge badge-yellow w-40px h-40 d-flex align-items-center justify-content-center fs-18 mr-3">
                            <svg class="icon icon-review">
                              <use xlink:href="#icon-review"></use>
                            </svg>
                          </div>
                          <div class="media-body">
                            Dollie Horton left a review on
                            <a href="#" class="text-heading"> Villa
                              Called Archangel</a>
                          </div>
                        </div>
                      </li>
                    </ul>
                    <a class="text-heading d-block text-center mt-4" role="button">
                      View more
                      <span class="text-primary d-inline-block ml-2"><i class="fal fa-angle-down"></i></span>
                    </a>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </main>
      </div>
    </div>
  </div>
  <!-- PAGE LOADING -->
  <?php include 'inc/page-loading.php' ?>

  <!-- REQUIRED SCRIPTS -->
  <?php include 'inc/required-scripts.php'; ?>

  <script src="plugins/counter/countUp.js"></script>
  <script src="plugins/chartjs/Chart.min.js"></script>

  <script src="js/functions.js"></script>
  <?php include 'inc/svg.php'; ?>
</body>

</html>