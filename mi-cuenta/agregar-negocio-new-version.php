<?php
include 'inc/session-proveedor.php';

$meta_title = 'Agregar negocio';

$page_slug  = 'agregar-negocios';
include 'inc/verify-user-permissions.php';

$num_business = getNumBusiness();

if ($session_user_plan === 'Free' && $num_business >= 1) {
  header('location:negocios');
  exit();
};
?>

<!doctype html>
<html lang="es">

<head>
  <?php include 'inc/meta-tags.php'; ?>
  <link rel="stylesheet" href="css/multiple-file-picker.css">
  <link rel="stylesheet" href="css/ckeditor5.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.6/cropper.css" integrity="sha256-jKV9n9bkk/CTP8zbtEtnKaKf+ehRovOYeKoyfthwbC8=" crossorigin="anonymous" />

  <style>
    .nav-item>a {
      font-weight: bold;
      font-size: 0.9rem;
    }

    .salon-type {
      display: none;
    }

    .card-title {
      font-weight: bold;
      font-size: 1.5rem;
      text-align: left;
    }

    .form-group label {
      margin-bottom: 0;
    }

    .form-group label span {
      color: red;
    }
  </style>
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
          <section class="pt-1">
            <div class="page-title mb-2">
              <div class="container">
                <nav aria-label="breadcrumb">
                  <ol class="breadcrumb pt-6 pt-lg-0 pb-0 justify-content-center">
                    <li class="breadcrumb-item"><a href="<?= $url_host; ?>">Home</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Acceso a proveedores</li>
                  </ol>
                </nav>
              </div>
            </div>

            <h2 class="fs-32 lh-16 mb-8 text-dark text-center">Área de registro</h2>

            <div class="collapse-tabs new-property-step">
              <ul class="nav nav-pills py-2 px-3 mb-6 border d-none d-md-flex" role="tablist" style="align-items: center;">
                <li class="nav-item col">
                  <a id="general-information-tab" data-target="general-information-tab" class="nav-link text-center bg-transparent active" href="javascript:void(0)">
                    Informacion general
                  </a>
                </li>

                <li class="nav-item col">
                  <a id="provider-type-tab" data-target="provider-type-tab" class="nav-link text-center" href="javascript:void(0)">
                    Tipo de proveedor
                  </a>
                </li>

                <li class="nav-item col">
                  <a id="event-types-tab" data-target="event-types-tab" class="nav-link text-center" href="javascript:void(0)">
                    Tipos de evento
                  </a>
                </li>

                <li class="nav-item col">
                  <a id="business-tab" data-target="business-tab" class="nav-link text-center" href="javascript:void(0)">
                    Salón/Negocio
                  </a>
                </li>

                <li class="nav-item col">
                  <a id="packages-tab" data-target="packages-tab" class="nav-link text-center" href="javascript:void(0)">
                    Paquetes
                  </a>
                </li>

                <li class="nav-item col">
                  <a id="location-tab" data-target="location-tab" class="nav-link text-center" href="javascript:void(0)">
                    Ubicación
                  </a>
                </li>

                <li class="nav-item col">
                  <a id="services-amenities-tab" data-target="services-amenities-tab" class="nav-link text-center" href="javascript:void(0)">
                    Servicios y Amenidades
                  </a>
                </li>

                <li class="nav-item col">
                  <a id="gallery-tab" data-target="gallery-tab" class="nav-link text-center" href="javascript:void(0)">
                    Galería
                  </a>
                </li>
              </ul>

              <div id="tab-alert" class="row" style="display: none;">
                <div class="col-11 col-md-8 mx-auto">
                  <div class="alert alert-danger" role="tab">
                    <b></b>
                  </div>
                </div>
              </div>

              <div class="tab-content tab-validate shadow-none p-0">
                <form id="supplier-register-form" class="form">
                  <div id="collapse-tabs-accordion">
                    <div id="general-information-content" class="tab-pane tab-pane-parent cs-tab-content show active" role="tabpanel">
                      <div class="card border-0">
                        <div class="card-header d-block d-md-none">
                          <h5>
                            <button type="button" class="btn btn-block border collapse-parent">
                              Información general
                            </button>
                          </h5>
                        </div>

                        <div id="general-information-collapse" class="collapse collapsible cs-tab-collapse show" data-parent="#collapse-tabs-accordion">
                          <div class="card-body">
                            <div class="col-md-9 mx-auto">
                              <div class="row">
                                <div class="col-md-6">
                                  <div class="card">
                                    <div class="card-body">
                                      <h3 class="card-title">Información general</h3>

                                      <div class="row">
                                        <div class="col-md-7">
                                          <div class="form-group">
                                            <label for="supplierName"><span>*</span>Nombre completo</label>
                                            <input id="supplierName" class="form-control" type="text" name="supplierName">
                                          </div>
                                        </div>

                                        <div class="col-md-5">
                                          <div class="form-group">
                                            <label for="supplierCellPhone"><span>*</span>Celular/Whatsapp</label>
                                            <input id="supplierCellPhone" class="form-control" type="text" name="supplierCellPhone">
                                          </div>
                                        </div>
                                      </div>

                                      <div class="row">
                                        <div class="col-md-10">
                                          <div class="form-group">
                                            <label for="supplierEmail"><span>*</span>Correo</label>
                                            <input id="supplierEmail" class="form-control" type="email" name="supplierEmail">
                                          </div>
                                        </div>
                                      </div>
                                    </div>
                                  </div>
                                </div>

                                <div class="col-md-6">
                                  <div class="card">
                                    <div class="card-body">
                                      <h3 class="card-title">Información de cuenta</h3>

                                      <div class="row">
                                        <div class="col-md-6">
                                          <div class="form-group">
                                            <label for="supplierUsername"><span>*</span>Nombre de usuario</label>
                                            <input id="supplierUsername" class="form-control" type="text" name="supplierUsername">
                                          </div>
                                        </div>
                                      </div>

                                      <div class="row">
                                        <div class="col-md-6">
                                          <div class="form-group">
                                            <label for="supplierPassword"><span>*</span>Contraseña</label>
                                            <input id="supplierPassword" class="form-control" type="password" name="supplierPassword">
                                          </div>
                                        </div>

                                        <div class="col-md-6">
                                          <div class="form-group">
                                            <label for="supplierConfirmPassword"><span>*</span>Confirma tu contraseña</label>
                                            <input id="supplierConfirmPassword" class="form-control" type="password" name="supplierConfirmPassword">
                                          </div>
                                        </div>
                                      </div>
                                    </div>
                                  </div>
                                </div>
                              </div>

                              <div class="text-right mt-4">
                                <button data-target="provider-type-tab" class="btn btn-primary btn-change-tab" type="button">
                                  Continuar <i class="fal fa-long-arrow-right ml-1"></i>
                                </button>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>

                    <div id="provider-type-content" class="tab-pane tab-pane-parent cs-tab-content" role="tabpanel">
                      <div class="card border-0">
                        <div class="card-header d-block d-md-none">
                          <h5>
                            <button type="button" class="btn btn-block border collapse-parent">
                              Tipo de proveedor
                            </button>
                          </h5>
                        </div>

                        <div id="provider-type-collapse" class="collapse collapsible cs-tab-collapse" data-parent="#collapse-tabs-accordion">
                          <div class="card-body">
                            <div class="col-md-10 mx-auto">
                              <div class="row">
                                <div class="col-md-12">
                                  <div class="card">
                                    <div class="card-body">
                                      <h3 class="card-title">Tipo de proveedor</h3>
                                      <p>Selecciona el giro de proveduria al que pertenece tu negocio.</p>

                                      <?php
                                      $query_provider_type = "SELECT
                                      idTipoProveedor,
                                      TipoProveedor
                                    FROM tipo_proveedores
                                    ORDER BY idTipoProveedor
                                    ASC
                                  ";

                                      $query_provider_type_result = mysqli_query($mysqli, $query_provider_type);
                                      ?>

                                      <div class="row">
                                        <?php while ($row = mysqli_fetch_array($query_provider_type_result)) : ?>
                                          <div class="col-sm-6 col-md-4 mb-2">
                                            <div class="card">
                                              <div class="card-body align-middle">
                                                <div class="form-check align-middle">
                                                  <input id="providerType-<?= $row['idTipoProveedor']; ?>" class="form-check-input providerType" type="radio" name="businessProviderType" value="<?= $row['idTipoProveedor']; ?>">

                                                  <label class="form-check-label fs-15 mb-0" for="providerType-<?= $row['idTipoProveedor']; ?>">
                                                    <?= $row['TipoProveedor']; ?></label>
                                                </div>
                                              </div>
                                            </div>
                                          </div>
                                        <?php endwhile; ?>
                                      </div>
                                    </div>
                                  </div>
                                </div>
                              </div>

                              <div class="d-flex flex-wrap mt-4">
                                <button data-target="general-information-tab" class="btn btn-secondary mr-auto btn-change-tab" type="button">
                                  <i class="fal fa-long-arrow-left mr-1"></i> Regresar
                                </button>

                                <button data-target="event-types-tab" class="btn btn-primary btn-change-tab" type="button">
                                  Continuar <i class="fal fa-long-arrow-right ml-1"></i>
                                </button>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>

                    <div id="event-types-content" class="tab-pane tab-pane-parent cs-tab-content" role="tabpanel">
                      <div class="card border-0">
                        <div class="card-header d-block d-md-none">
                          <h5>
                            <button type="button" class="btn btn-block border collapse-parent">
                              Tipos de evento
                            </button>
                          </h5>
                        </div>

                        <div id="event-types-collapse" class="collapse collapsible cs-tab-collapse" data-parent="#collapse-tabs-accordion">
                          <div class="card-body">
                            <div class="col-md-8 mx-auto">
                              <div class="row">
                                <div class="col-md-12">
                                  <div class="card">
                                    <div class="card-body">
                                      <h3 class="card-title">Tipos de evento</h3>
                                      <p>Selecciona las categorías de eventos en las que te gustaría aparecer.</p>

                                      <?php
                                      $query_event_types = "SELECT
                                      idTipoEvento,
                                      TipoEvento
                                    FROM tipo_eventos
                                    ORDER BY idTipoEvento
                                    ASC
                                  ";

                                      $query_event_types_result = mysqli_query($mysqli, $query_event_types);
                                      ?>

                                      <div class="row">
                                        <div class="col-sm-6 col-md-4 mb-2">
                                          <div class="card">
                                            <div class="card-body align-middle">
                                              <div class="custom-control custom-checkbox">
                                                <input id="allEventTypes" class="custom-control-input" type="checkbox" value="allEventTypes">
                                                <label class="custom-control-label fs-15 mb-0" for="allEventTypes">
                                                  Todas las categorías
                                                </label>
                                              </div>
                                            </div>
                                          </div>
                                        </div>
                                      </div>

                                      <div class="row">
                                        <?php while ($row = mysqli_fetch_array($query_event_types_result)) : ?>
                                          <div class="col-sm-6 col-md-4 mb-2">
                                            <div class="card">
                                              <div class="card-body align-middle">
                                                <div class="custom-control custom-checkbox">
                                                  <input id="eventType-<?= $row['idTipoEvento']; ?>" class="custom-control-input" type="checkbox" name="businessEventTypes[]" value="<?= $row['idTipoEvento']; ?>">
                                                  <label class="custom-control-label fs-15 mb-0" for="eventType-<?= $row['idTipoEvento']; ?>">
                                                    <?= $row['TipoEvento']; ?>
                                                  </label>
                                                </div>
                                              </div>
                                            </div>
                                          </div>
                                        <?php endwhile; ?>
                                      </div>
                                    </div>
                                  </div>
                                </div>
                              </div>

                              <div class="d-flex flex-wrap mt-4">
                                <button data-target="provider-type-tab" class="btn btn-secondary mr-auto btn-change-tab" type="button">
                                  <i class="fal fa-long-arrow-left mr-1"></i> Regresar
                                </button>

                                <button data-target="business-tab" class="btn btn-primary btn-change-tab" type="button">
                                  Continuar <i class="fal fa-long-arrow-right ml-1"></i>
                                </button>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>

                    <div id="business-content" class="tab-pane tab-pane-parent cs-tab-content" role="tabpanel">
                      <div class="card border-0">
                        <div class="card-header d-block d-md-none">
                          <h5>
                            <button type="button" class="btn btn-block border collapse-parent">
                              Salón/Negocio
                            </button>
                          </h5>
                        </div>

                        <div id="business-collapse" class="collapse collapsible cs-tab-collapse" data-parent="#collapse-tabs-accordion">
                          <div class="card-body">
                            <div class="col-md-8 mx-auto">
                              <div class="row">
                                <div class="col-md-12">
                                  <div class="card">
                                    <div class="card-body">
                                      <h3 class="card-title">Información básica de tu Salón/Negocio</h3>

                                      <div class="row">
                                        <div class="col-md-5">
                                          <div class="form-group">
                                            <label for="businessName"><span>*</span>Nombre de tu<br>Salón/Negocio</label>
                                            <input id="businessName" class="form-control" type="text" name="businessName">
                                          </div>
                                        </div>

                                        <div class="col-6 col-sm-4 col-md-2">
                                          <div class="form-group">
                                            <label for="businessMinCapacity"><span>*</span>Capacidad<br>mínima</label>
                                            <input id="businessMinCapacity" class="form-control number-input" type="number" min="1" name="businessMinCapacity">
                                          </div>
                                        </div>

                                        <div class="col-6 col-sm-4 col-md-2">
                                          <div class="form-group">
                                            <label for="businessMaxCapacity"><span>*</span>Capacidad<br>máxima</label>
                                            <input id="businessMaxCapacity" class="form-control number-input" type="number" min="2" name="businessMaxCapacity">
                                          </div>
                                        </div>
                                      </div>

                                      <div class="row">
                                        <div class="col-12">
                                          <div class="form-group">
                                            <label for="businessDescription"><span>*</span>Describe tu Salón/Negocio</label>
                                            <textarea id="businessDescription" class="form-control" rows="5"></textarea>
                                          </div>
                                        </div>
                                      </div>

                                      <h3 class="card-title">Información de contácto de tu Salón/Negocio</h3>

                                      <div class="row">
                                        <div class="col-12 col-sm-5 col-md-3">
                                          <div class="form-group">
                                            <label for="businessCellPhone"><span>*</span>Celular/Whatsapp de tu<br>Salón/Negocio</label>
                                            <input id="businessCellPhone" class="form-control number-input" type="text" name="businessCellPhone">
                                          </div>
                                        </div>

                                        <div class="col-12 col-sm-5 col-md-3">
                                          <div class="form-group">
                                            <label for="businessPhone">Teléfono fijo de tu<br>Salón/Negocio</label>
                                            <input id="businessPhone" class="form-control number-input" type="text" name="businessPhone">
                                          </div>
                                        </div>
                                      </div>

                                      <div class="row">
                                        <div class="col-md-6">
                                          <div class="form-group">
                                            <label for="businessFacebook">Facebook de tu Salón/Negocio</label>
                                            <input id="businessFacebook" class="form-control" type="text" name="businessFacebook" placeholder="ej: https://www.facebook.com/example">
                                          </div>
                                        </div>

                                        <div class="col-md-6">
                                          <div class="form-group">
                                            <label for="businessInstagram">Instagram de tu Salón/Negocio</label>
                                            <input id="businessInstagram" class="form-control" type="text" name="businessInstagram" placeholder="ej: https://www.instagram.com/example">
                                          </div>
                                        </div>
                                      </div>
                                    </div>
                                  </div>
                                </div>
                              </div>

                              <div class="d-flex flex-wrap mt-4">
                                <button data-target="event-types-tab" class="btn btn-secondary mr-auto btn-change-tab" type="button">
                                  <i class="fal fa-long-arrow-left mr-1"></i> Regresar
                                </button>

                                <button data-target="packages-tab" class="btn btn-primary btn-change-tab" type="button">
                                  Continuar <i class="fal fa-long-arrow-right ml-1"></i>
                                </button>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>

                    <div id="packages-content" class="tab-pane tab-pane-parent cs-tab-content" role="tabpanel">
                      <div class="card border-0">
                        <div class="card-header d-block d-md-none">
                          <h5>
                            <button type="button" class="btn btn-block border collapse-parent">
                              Paquetes
                            </button>
                          </h5>
                        </div>

                        <div id="packages-collapse" class="collapse collapsible cs-tab-collapse" data-parent="#collapse-tabs-accordion">
                          <div class="card-body">
                            <div class="col-md-8 mx-auto">
                              <div class="row">
                                <div class="col-md-12">
                                  <div class="card">
                                    <div class="card-body">
                                      <h3 class="card-title">Paquetes</h3>
                                      <p>Agrega los paquetes que tiene tu Salón/Negocio.</p>

                                      <div class="row">
                                        <div class="col-md-12">
                                          <button id="btn-add-package" class="btn btn-primary" type="button">
                                            <i class="fa fa-plus-circle mr-1"></i> Agregar paquete
                                          </button>
                                        </div>
                                      </div>
                                    </div>
                                  </div>
                                </div>
                              </div>

                              <div class="d-flex flex-wrap mt-4">
                                <button data-target="business-tab" class="btn btn-secondary mr-auto btn-change-tab" type="button">
                                  <i class="fal fa-long-arrow-left mr-1"></i> Regresar
                                </button>

                                <button data-target="location-tab" class="btn btn-primary btn-change-tab" type="button">
                                  Continuar <i class="fal fa-long-arrow-right ml-1"></i>
                                </button>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>

                    <div id="location-content" class="tab-pane tab-pane-parent cs-tab-content" role="tabpanel">
                      <div class="card border-0">
                        <div class="card-header d-block d-md-none">
                          <h5>
                            <button type="button" class="btn btn-block border collapse-parent">
                              Ubicación
                            </button>
                          </h5>
                        </div>

                        <div id="location-collapse" class="collapse collapsible cs-tab-collapse" data-parent="#collapse-tabs-accordion">
                          <div class="card-body">
                            <div class="col-md-8 mx-auto">
                              <div class="row">
                                <div class="col-md-12">
                                  <div class="card">
                                    <div class="card-body">
                                      <h3 class="card-title">Ubicación de tu Salón/Negocio</h3>

                                      <div class="row">
                                        <?php
                                        $query_states = "SELECT
                                        idEstado,
                                        Estado
                                      FROM estados
                                      ORDER BY Estado
                                      ASC
                                    ";

                                        $query_states_result = mysqli_query($mysqli, $query_states);
                                        ?>

                                        <div class="col-md-4">
                                          <div class="form-group">
                                            <label for="businessState"><span>*</span>Estado</label>
                                            <select id="businessState" class="form-control select2" name="businessState">
                                              <option value="">Seleccionar</option>

                                              <?php while ($row = mysqli_fetch_array($query_states_result)) :
                                                $state_selected = $row['idEstado'] == 7 ? 'selected' : '';
                                              ?>
                                                <option <?= $state_selected; ?> value="<?= $row['idEstado']; ?>"><?= $row['Estado']; ?></option>
                                              <?php endwhile; ?>
                                            </select>
                                          </div>
                                        </div>

                                        <?php
                                        $query_citys = "SELECT
                                        EC.idEstadoCiudad,
                                        EC.idEstado,
                                        EC.idCiudad,
                                        C.Ciudad
                                      FROM estados_ciudades AS EC
                                        LEFT JOIN ciudades AS C ON (EC.idCiudad = C.idCiudad)
                                      WHERE EC.idEstado = 7
                                      ORDER BY C.Ciudad ASC
                                    ";

                                        $query_citys_result = mysqli_query($mysqli, $query_citys);
                                        ?>

                                        <div class="col-md-4">
                                          <div class="form-group">
                                            <label for="businessCity"><span>*</span>Ciudad</label>
                                            <select id="businessCity" class="form-control select2" name="businessCity">
                                              <option value="">Seleccionar</option>

                                              <?php while ($row = mysqli_fetch_array($query_citys_result)) : ?>
                                                <option value="<?= $row['idCiudad']; ?>"><?= $row['Ciudad']; ?></option>
                                              <?php endwhile; ?>
                                            </select>
                                          </div>
                                        </div>
                                      </div>

                                      <span class="mt-5"><b>INDICA LA UBICACIÓN EXACTA</b></span>

                                      <div class="row">
                                        <div class="col-12">
                                          <div class="form-group">
                                            <label for="pac-input">
                                              <span><b>¡IMPORTANTE!</b></span>
                                              Arrastra el marcador hasta la ubicación de tu Salón/Negocio ó buscalo aqui colocando el nombre o dirección completa.
                                            </label>
                                            <input id="pac-input" class="form-control" type="text" placeholder="">
                                          </div>
                                        </div>
                                      </div>

                                      <input type="hidden" name="businessLatitude" id="latitud">
                                      <input type="hidden" name="businessLongitude" id="longitud">

                                      <div class="row">
                                        <div class="col-md-12 col-sm-12">
                                          <div class="p-0 table-bordered d-flex align-items-center justify-content-center rounded" style="min-height: 200px">
                                            <div id="map" style="width:100%;height: 300px;" class="d-flex align-items-center justify-content-center">
                                              <p>
                                                <i class="fas fa-map nav-icon fa-7x text-gray"></i>
                                                <br>
                                                Cargando mapa...
                                              </p>
                                            </div>
                                          </div>
                                        </div>
                                      </div>

                                      <div class="row">
                                        <div class="col-12 mt-2">
                                          <div class="form-group">
                                            <label for="direccion"><span>*</span>Dirección</label>
                                            <input type="text" name="businessDirection" id="direccion" class="form-control">
                                          </div>
                                        </div>
                                      </div>
                                    </div>
                                  </div>
                                </div>
                              </div>

                              <div class="d-flex flex-wrap mt-4">
                                <button data-target="packages-tab" class="btn btn-secondary mr-auto btn-change-tab" type="button">
                                  <i class="fal fa-long-arrow-left mr-1"></i> Regresar
                                </button>

                                <button data-target="services-amenities-tab" class="btn btn-primary btn-change-tab" type="button">
                                  Continuar <i class="fal fa-long-arrow-right ml-1"></i>
                                </button>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>

                    <div id="services-amenities-content" class="tab-pane tab-pane-parent cs-tab-content" role="tabpanel">
                      <div class="card border-0">
                        <div class="card-header d-block d-md-none">
                          <h5>
                            <button type="button" class="btn btn-block border collapse-parent">
                              Servicios y Amenidades
                            </button>
                          </h5>
                        </div>

                        <div id="services-amenities-collapse" class="collapse collapsible cs-tab-collapse" data-parent="#collapse-tabs-accordion">
                          <div class="card-body">
                            <div class="col-md-8 mx-auto">
                              <div class="row">
                                <div class="col-md-12">
                                  <div class="card">
                                    <div class="card-body">
                                      <h3 class="card-title">Servicios y Amenidades</h3>

                                      <span><b>SERVICIOS</b></span>
                                      <p>Selecciona el tipo de menús que puedes llevar a cabo.</p>

                                      <?php
                                      $query_services = "SELECT
                                      idServicio,
                                      Servicio
                                    FROM servicios
                                  ";

                                      $query_services_result = mysqli_query($mysqli, $query_services);
                                      ?>

                                      <div class="row">
                                        <?php while ($row = mysqli_fetch_array($query_services_result)) : ?>
                                          <div class="col-sm-6 col-md-4 mb-2">
                                            <div class="card">
                                              <div class="card-body align-middle">
                                                <div class="custom-control custom-checkbox">
                                                  <input id="service-<?= $row['idServicio']; ?>" class="custom-control-input" type="checkbox" name="businessServices[]" value="<?= $row['idServicio']; ?>">
                                                  <label class="custom-control-label fs-15 mb-0" for="service-<?= $row['idServicio']; ?>">
                                                    <?= $row['Servicio']; ?>
                                                  </label>
                                                </div>
                                              </div>
                                            </div>
                                          </div>
                                        <?php endwhile; ?>
                                      </div>

                                      <span><b>AMENIDADES</b></span>
                                      <p>las amenidades con que cuenta tu espacio.</p>

                                      <?php
                                      $query_amenities = "SELECT
                                      idAmenidad,
                                      Amenidad
                                    FROM amenidades
                                  ";

                                      $query_amenities_result = mysqli_query($mysqli, $query_amenities);
                                      ?>

                                      <div class="row">
                                        <?php while ($row = mysqli_fetch_array($query_amenities_result)) : ?>
                                          <div class="col-sm-6 col-md-4 mb-2">
                                            <div class="card">
                                              <div class="card-body align-middle">
                                                <div class="custom-control custom-checkbox">
                                                  <input id="amenity-<?= $row['idAmenidad']; ?>" class="custom-control-input" type="checkbox" name="businessAmenities[]" value="<?= $row['idAmenidad']; ?>">
                                                  <label class="custom-control-label fs-15 mb-0" for="amenity-<?= $row['idAmenidad']; ?>">
                                                    <?= $row['Amenidad']; ?>
                                                  </label>
                                                </div>
                                              </div>
                                            </div>
                                          </div>
                                        <?php endwhile; ?>
                                      </div>
                                    </div>
                                  </div>
                                </div>
                              </div>

                              <div class="d-flex flex-wrap mt-4">
                                <button data-target="location-tab" class="btn btn-secondary mr-auto btn-change-tab" type="button">
                                  <i class="fal fa-long-arrow-left mr-1"></i> Regresar
                                </button>

                                <button data-target="gallery-tab" class="btn btn-primary btn-change-tab" type="button">
                                  Continuar <i class="fal fa-long-arrow-right ml-1"></i>
                                </button>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>

                    <div id="gallery-content" class="tab-pane tab-pane-parent cs-tab-content" role="tabpanel">
                      <div class="card border-0">
                        <div class="card-header d-block d-md-none">
                          <h5>
                            <button type="button" class="btn btn-block border collapse-parent">
                              Galería de imagenes
                            </button>
                          </h5>
                        </div>

                        <div id="gallery-collapse" class="collapse collapsible cs-tab-collapse" data-parent="#collapse-tabs-accordion">
                          <div class="card-body">
                            <div class="col-md-6 mx-auto">
                              <div class="row">
                                <div class="col-md-12">
                                  <div class="card">
                                    <div class="card-body">
                                      <h3 class="card-title">Galería de imagenes</h3>

                                      <div class="row">
                                        <div class="col-12">
                                          <div class="form-group">
                                            <label>Imagen principal del Salón/Negocio</label>
                                            <div id="businessImage" data-name="businessImage" data-title="Adjuntar imagen"></div>
                                          </div>
                                        </div>
                                      </div>

                                      <div class="row">
                                        <div class="col-12">
                                          <div class="form-group">
                                            <label>Galería de imagenes</label>
                                            <div id="businessImageGallery" data-name="businessImageGallery" data-idListar=""></div>
                                          </div>
                                        </div>
                                      </div>
                                    </div>
                                  </div>
                                </div>
                              </div>

                              <div class="d-flex flex-wrap mt-4">
                                <button data-target="services-amenities-tab" class="btn btn-secondary mr-auto btn-change-tab" type="button">
                                  <i class="fal fa-long-arrow-left mr-1"></i> Regresar
                                </button>

                                <button class="btn btn-primary" type="submit">
                                  <i class="fa fa-check-circle mr-1"></i> Registrarse
                                </button>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </form>
              </div>
            </div>
          </section>
        </main>
      </div>
    </div>
  </div>

  <!-- REQUIRED SCRIPTS -->
  <?php include 'inc/required-scripts.php'; ?>
  <?php include 'inc/svg.php'; ?>

  <script src="plugins/ckeditor5/ckeditor5-build-classic/ckeditor.js"></script>
  <script src="plugins/select2/js/select2.full.min.js"></script>

  <script src="js/functions.js"></script>
  <script src="js/dynamic-multiple-picker.js"></script>
  <script src="js/dynamic-picker-with-editor.js"></script>

  <script src="main/business/add-business.js"></script>
  <script src="main/packages/packages.js"></script>

  <script>
    createMultiplePicker('galeria-imagenes');
    createPickerWithCropper('imagen-salon');
    loadEventTypes();

    $('.select2').select2({
      theme: 'bootstrap4'
    });
  </script>

  <script src="js/google-search.js"></script>
  <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBcXIXiRSirvWVofs7wRolh-WjSSUF4jIE&callback=setMapa&libraries=places&v=weekly" async></script>
</body>

</html>