<?php
include 'inc/session.php';

$page_slug = 'editar-negocios';
include 'inc/verify-user-permissions.php';

$user_level = $_SESSION['session_user_level'];

$id_negocio = cleanStr($_GET['uid']);
$user_id = cleanStr($_GET['id']);

$id_usuario = ($user_level == 'Administrador' || $user_level == 'Super Usuario') ? $user_id : $_SESSION['session_user_id'];

if ($id_negocio == '') {
  header('location:mis-negocios');
  die();
}

$query = "SELECT
    idSalon,
    idUsuario,
    idTipoProveedor,
    Salon,
    Descripcion,
    CostoRenta,
    Capacidad,
    CapacidadMaxima,
    Facebook,
    Instagram,
    Latitud,
    Longitud,
    Direccion,
    Imagen,
    idMunicipio,
    Orientacion,
    Telefono,
    Celular,
    PerteneceATuxtla,
    Tipo,
    Fecha,
    idEstado,
    idCiudad
  FROM salones WHERE
    idSalon   = '$id_negocio' AND
    idUsuario = '$id_usuario'
  LIMIT 1
";

$data_neg = mysqli_query_one_row($query);
$style = $data_neg['Tipo'] == 'Salon' ? 'block' : 'none';

$meta_title = 'Editar negocio | ' . $data_neg['Salon'];
?>

<!doctype html>
<html lang="es">

<head>
  <?php include 'inc/meta-tags.php'; ?>
  <link rel="stylesheet" href="plugins/select2/css/select2.min.css">
  <link rel="stylesheet" href="plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css">
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.1/jquery.js" integrity="sha256-WpOohJOqMqqyKL9FccASB9O0KwACQJpFTUBLTYOVvVU=" crossorigin="anonymous"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.6/cropper.css" integrity="sha256-jKV9n9bkk/CTP8zbtEtnKaKf+ehRovOYeKoyfthwbC8=" crossorigin="anonymous" />
  <script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.6/cropper.js" integrity="sha256-CgvH7sz3tHhkiVKh05kSUgG97YtzYNnWt6OXcmYzqHY=" crossorigin="anonymous"></script>
  <style>
    .tipo-salon {
      display: <?= $style ?>;
    }

    img {
      display: block;
      max-width: 100%;
    }

    .preview {
      overflow: hidden;
      width: 160px;
      height: 160px;
      margin: 10px;
      border: 1px solid red;
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
          <div class="p-3">
            <div class="d-flex flex-wrap flex-md-nowrap mb-6">
              <div class="mr-0 mr-md-auto">
                <h2 class="mb-0 text-heading fs-22 lh-15">Editar negocio | <?= $data_neg['Salon'] ?></h2>
              </div>
            </div>

            <div class="alert alert-danger" role="alert" id="tab-alert" style="display: none;"></div>

            <div class="collapse-tabs new-property-step">
              <ul class="nav nav-pills border py-2 px-3 mb-6 d-none d-md-flex mb-6" role="tablist">
                <li class="nav-item col">
                  <a class="nav-link active bg-transparent shadow-none py-2 font-weight-500 text-center lh-214 d-block" id="tipo-proveedor-tab" data-number="" href="#tipo-proveedor" data-toggle="pill" role="tab" aria-controls="tipo-proveedor" aria-selected="false"><span class="number"></span> Tipo de proveedor</a>
                </li>

                <li class="nav-item col">
                  <a class="nav-link bg-transparent shadow-none py-2 font-weight-500 text-center lh-214 d-block" id="tipo-evento-tab" data-number="" href="#tipo-evento" data-toggle="pill" role="tab" aria-controls="tipo-evento" aria-selected="false"><span class="number"></span> Tipo de evento</a>
                </li>

                <li class="nav-item col">
                  <a class="nav-link bg-transparent shadow-none py-2 font-weight-500 text-center lh-214 d-block" id="negocio-tab" data-number="" href="#negocio" data-toggle="pill" role="tab" aria-controls="negocio" aria-selected="false"><span class="number"></span> Negocio</a>
                </li>

                <li class="nav-item col">
                  <a class="nav-link bg-transparent shadow-none py-2 font-weight-500 text-center lh-214 d-block" id="paquetes-tab" data-number="" href="#paquetes" data-toggle="pill" role="tab" aria-controls="paquetes" aria-selected="false"><span class="number"></span> Paquetes</a>
                </li>

                <li class="nav-item col">
                  <a class="nav-link bg-transparent shadow-none py-2 font-weight-500 text-center lh-214 d-block" id="ubicacion-negocio-tab" data-number="" href="#ubicacion-negocio" data-toggle="pill" role="tab" aria-controls="ubicacion-negocio" aria-selected="false"><span class="number"></span> Ubicación</a>
                </li>

                <li class="nav-item col tipo-salon">
                  <a class="nav-link bg-transparent shadow-none py-2 font-weight-500 text-center lh-214 d-block" id="servicios-amenidades-tab" data-number="" href="#servicios-amenidades" data-toggle="pill" role="tab" aria-controls="servicios-amenidades" aria-selected="false"><span class="number"></span> Servicios y amenidades</a>
                </li>

                <li class="nav-item col">
                  <a class="nav-link bg-transparent shadow-none py-2 font-weight-500 text-center lh-214 d-block" id="galeria-fotos-tab" data-number="" href="#galeria-fotos" data-toggle="pill" role="tab" aria-controls="galeria-fotos" aria-selected="false"><span class="number"></span> Galería de fotos</a>
                </li>
              </ul>

              <div class="tab-content tab-validate shadow-none p-0">
                <form id="form-tab-panel" class="form" autocomplete="off">
                  <div id="collapse-tabs-accordion">
                    <div class="tab-pane tab-pane-parent fade show active px-0" id="tipo-proveedor" role="tabpanel" aria-labelledby="tipo-proveedor-tab">
                      <div class="card bg-transparent border-0">
                        <div class="card-header d-block d-md-none bg-transparent px-0 py-1 border-bottom-0" id="heading-tipo-proveedor">
                          <h5 class="mb-0">
                            <button type="button" class="btn btn-lg collapse-parent btn-block border shadow-none" data-target="#tipo-proveedor-collapse" aria-expanded="true" aria-controls="tipo-proveedor-collapse" data-number="">
                              Tipo de proveedor
                            </button>
                          </h5>
                        </div>
                        <div id="tipo-proveedor-collapse" class="collapse show collapsible" aria-labelledby="heading-tipo-proveedor" data-parent="#collapse-tabs-accordion">
                          <div class="card-body py-4 py-md-0 px-0">
                            <?php $query_result = query("SELECT idTipoProveedor, TipoProveedor FROM tipo_proveedores"); ?>
                            <?php if ($query_result) : ?>
                              <div class="row">
                                <?php while ($row = mysqli_fetch_array($query_result)) : ?>
                                  <div class="col-sm-6 col-md-4 mb-2">
                                    <div class="card">
                                      <div class="card-body align-middle">
                                        <div class="form-check align-middle">
                                          <input class="form-check-input tipoProveedor" type="radio" name="tipoProveedor" id="tipoProveedor-<?= $row['idTipoProveedor'] ?>" value="<?= $row['idTipoProveedor'] ?>" <?php if ($data_neg['idTipoProveedor'] == $row['idTipoProveedor']) {
                                                                                                                                                                                                                      echo 'checked';
                                                                                                                                                                                                                    } ?>>
                                          <label class="form-check-label text-heading mb-0 fs-15 lh-15" for="tipoProveedor-<?= $row['idTipoProveedor'] ?>">
                                            <?= $row['TipoProveedor'] ?>
                                          </label>
                                        </div>
                                      </div>
                                    </div>
                                  </div>
                                <?php endwhile; ?>
                              </div>
                            <?php endif; ?>

                            <div class="text-right mt-4">
                              <button type="button" class="btn btn-lg btn-primary next-button">Continuar
                                <span class="d-inline-block ml-2 fs-16"><i class="fal fa-long-arrow-right"></i></span>
                              </button>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>

                    <?php
                    $query = "SELECT idCatalogo, idSalon, idTipoEvento FROM catalogo_salon_tipos_eventos WHERE idSalon = '$data_neg[idSalon]'";
                    $query_result_te = query($query);

                    $tipos_eventos = array();

                    while ($row = mysqli_fetch_array($query_result_te)) {
                      array_push($tipos_eventos, $row['idTipoEvento']);
                    }
                    ?>

                    <div class="tab-pane tab-pane-parent fade px-0" id="tipo-evento" role="tabpanel" aria-labelledby="tipo-evento-tab">
                      <div class="card bg-transparent border-0">
                        <div class="card-header d-block d-md-none bg-transparent px-0 py-1 border-bottom-0" id="heading-tipo-evento">
                          <h5 class="mb-0">
                            <button type="button" class="btn btn-block collapse-parent collapsed border shadow-none" data-target="#tipo-evento-collapse" aria-expanded="true" aria-controls="tipo-evento-collapse" data-number="2.">
                              Tipo de evento
                            </button>
                          </h5>
                        </div>
                        <div id="tipo-evento-collapse" class="collapse collapsible" aria-labelledby="heading-tipo-evento" data-parent="#collapse-tabs-accordion">
                          <div class="card-body py-4 py-md-0 px-0">
                            <?php $query_result = query("SELECT idTipoEvento, TipoEvento FROM tipo_eventos"); ?>

                            <?php if ($query_result) : ?>
                              <div class="row">
                                <?php while ($row = mysqli_fetch_array($query_result)) : ?>
                                  <?php
                                  $checked = in_array($row['idTipoEvento'], $tipos_eventos) ? 'checked' : '';
                                  $delete_tipo_evento = in_array($row['idTipoEvento'], $tipos_eventos) ? 'delete-tipo-evento' : '';
                                  ?>
                                  <div class="col-sm-6 col-md-4 mb-2">
                                    <div class="card">
                                      <div class="card-body align-middle">
                                        <div class="custom-control custom-checkbox">
                                          <input <?= $checked ?> type="checkbox" class="custom-control-input <?= $delete_tipo_evento; ?>" name="tipoEvento[]" id="tipoEvento-<?= $row['idTipoEvento'] ?>" value="<?= $row['idTipoEvento'] ?>">
                                          <label class="custom-control-label  mb-0 fs-15 lh-15" for="tipoEvento-<?= $row['idTipoEvento'] ?>">
                                            <?= $row['TipoEvento'] ?>
                                          </label>
                                        </div>
                                      </div>
                                    </div>
                                  </div>
                                <?php endwhile; ?>
                              </div>
                            <?php endif; ?>

                            <div class="d-flex flex-wrap">
                              <a href="#" class="btn btn-lg bg-hover-white border rounded-lg mb-3 mr-auto prev-button">
                                <span class="d-inline-block text-primary mr-2 fs-16"><i class="fal fa-long-arrow-left"></i></span>Regresar
                              </a>
                              <button class="btn btn-lg btn-primary next-button mb-3">Continuar
                                <span class="d-inline-block ml-2 fs-16"><i class="fal fa-long-arrow-right"></i></span>
                              </button>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>

                    <div class="tab-pane tab-pane-parent fade px-0" id="negocio" role="tabpanel" aria-labelledby="negocio-tab">
                      <div class="card bg-transparent border-0">
                        <div class="card-header d-block d-md-none bg-transparent px-0 py-1 border-bottom-0" id="heading-negocio">
                          <h5 class="mb-0">
                            <button type="button" class="btn btn-block collapse-parent collapsed border shadow-none" data-target="#negocio-collapse" aria-expanded="true" aria-controls="negocio-collapse" data-number="3.">
                              Negocio
                            </button>
                          </h5>
                        </div>
                        <div id="negocio-collapse" class="collapse collapsible" aria-labelledby="heading-negocio" data-parent="#collapse-tabs-accordion">
                          <div class="card-body py-4 py-md-0 px-0">
                            <div class="row">
                              <div class="col-lg-8 mx-auto">
                                <div class="card border-0 shadow-xxs-2">
                                  <div class="card-body p-6">
                                    <h3 class="card-title mb-3 text-heading fs-22 lh-15">Información basica de tu negocio</h3>

                                    <div class="row">
                                      <div class="col-sm-6 col-md-6">
                                        <div class="form-group">
                                          <label for="negocio" class="text-heading">Nombre de tu negocio</label>
                                          <input type="text" name="negocio" class="form-control form-control-lg" id="negocio" value="<?= $data_neg['Salon'] ?>">
                                        </div>
                                      </div>

                                      <?php if ($_SESSION['session_user_level'] === 'Super Usuario' || $_SESSION['session_user_level'] === 'Administrador') : ?>
                                        <?php
                                        $query = "SELECT idUsuario, Usuario FROM usuarios WHERE Nivel = 'Usuario' ORDER BY Usuario ASC";
                                        $query_result   = mysqli_query($mysqli, $query);
                                        ?>
                                        <div class="col-sm-6 col-md-6">
                                          <div class="form-group">
                                            <label for="userId" class="text-heading"><span class="text-danger">*</span>¿A que usuario pertenece el salón?</label>
                                            <div class="input-group">
                                              <select id="userId" name="userId" class="form-control form-control-lg select-usuarios" required>
                                                <option value="">Seleccionar</option>
                                                <?php while ($row = mysqli_fetch_array($query_result)) : ?>
                                                  <option <?php if ($data_neg['idUsuario'] == $row['idUsuario']) { ?> selected <?php } ?> value="<?= $row['idUsuario'] ?>"><?= $row['Usuario'] ?></option>
                                                <?php endwhile; ?>
                                              </select>
                                            </div>
                                          </div>
                                        </div>
                                      <?php endif; ?>
                                    </div>

                                    <div class="row">
                                      <!-- <div class="col-sm-12 col-md-6 tipo-salon">
                                        <div class="form-group">
                                          <label for="costo" class="text-heading">Costo de renta</label>
                                          <input type="number" name="costo" class="number-input form-control form-control-lg" id="costo" value="<?= $data_neg['CostoRenta'] ?>">
                                        </div>
                                      </div> -->

                                      <div class="col-xs-12 col-sm-6 col-md-3 tipo-salon">
                                        <div class="form-group">
                                          <label for="capacidad" class="text-heading">Capacidad</label>
                                          <input type="number" name="capacidad" class="number-input form-control form-control-lg" id="capacidad" value="<?= $data_neg['Capacidad'] ?>">
                                        </div>
                                      </div>

                                      <div class="col-xs-12 col-sm-6 col-md-3 tipo-salon">
                                        <div class="form-group">
                                          <label for="capacidadMaxima" class="text-heading"><span class="text-danger">*</span>Capacidad maxima</label>
                                          <input type="number" name="capacidadMaxima" class="number-input form-control form-control-lg" id="capacidadMaxima" value="<?= $data_neg['CapacidadMaxima'] ?>">
                                        </div>
                                      </div>
                                    </div>

                                    <div class="row">
                                      <div class="col-sm-12 col-md-12">
                                        <div class="form-group">
                                          <label for="descripcion" class="text-heading">Descripcion</label>
                                          <textarea name="descripcion" id="descripcion" rows="8" class="form-control form-control-lg"><?= $data_neg['Descripcion']; ?></textarea>
                                        </div>
                                      </div>
                                    </div>

                                    <h3 class="card-title mb-3 text-heading fs-22 lh-15">Información de contácto de tu negocio</h3>

                                    <div class="row">
                                      <div class="col-sm-12 col-md-6">
                                        <div class="form-group">
                                          <label for="celularNegocio" class="text-heading"><span class="text-danger">*</span> Celular / Whatsapp</label>
                                          <input type="text" name="celularNegocio" class="form-control form-control-lg" id="celularNegocio" placeholder="Celular de tu negocio" value="<?= $data_neg['Celular'] ?>" required>
                                        </div>
                                      </div>

                                      <div class="col-sm-12 col-md-6">
                                        <div class="form-group">
                                          <label for="telefonoNegocio" class="text-heading">Teléfono fijo</label>
                                          <input type="text" name="telefonoNegocio" class="form-control form-control-lg" id="telefonoNegocio" placeholder="Teléfono de tu negocio" value="<?= $data_neg['Telefono'] ?>">
                                        </div>
                                      </div>
                                    </div>

                                    <div class="row">
                                      <div class="col-sm-12 col-md-6">
                                        <div class="form-group">
                                          <label for="facebook" class="text-heading">Facebook</label>
                                          <input type="text" name="facebook" class="form-control form-control-lg" id="facebook" placeholder="ej: https://www.facebook.com/perfil" value="<?= $data_neg['Facebook'] ?>">
                                        </div>
                                      </div>

                                      <div class="col-sm-12 col-md-6">
                                        <div class="form-group">
                                          <label for="instagram" class="text-heading">Instagram</label>
                                          <input type="text" name="instagram" class="form-control form-control-lg" id="instagram" placeholder="ej: https://www.instagram.com/perfil" value="<?= $data_neg['Instagram'] ?>">
                                        </div>
                                      </div>
                                    </div>
                                  </div>
                                </div>
                              </div>

                            </div>

                            <div class="d-flex flex-wrap mt-4">
                              <a href="#" class="btn btn-lg bg-hover-white border rounded-lg mb-3 mr-auto prev-button">
                                <span class="d-inline-block text-primary mr-2 fs-16"><i class="fal fa-long-arrow-left"></i></span>Regresar
                              </a>

                              <button class="btn btn-lg btn-primary next-button mb-3">Continuar
                                <span class="d-inline-block ml-2 fs-16"><i class="fal fa-long-arrow-right"></i></span>
                              </button>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>

                    <div class="tab-pane tab-pane-parent fade px-0" id="paquetes" role="tabpanel" aria-labelledby="paquetes-tab">
                      <div class="card bg-transparent border-0">
                        <div class="card-header d-block d-md-none bg-transparent px-0 py-1 border-bottom-0" id="heading-paquetes">
                          <h5 class="mb-0">
                            <button class="btn btn-block collapse-parent collapsed border shadow-none" data-toggle="collapse" data-target="#paquetes-collapse" aria-expanded="true" aria-controls="paquetes-collapse" data-number="">
                              Paquetes
                            </button>
                          </h5>
                        </div>
                        <div id="paquetes-collapse" class="collapse collapsible" aria-labelledby="heading-paquetes" data-parent="#collapse-tabs-accordion">
                          <div class="card-body py-4 py-md-0 px-0">
                            <div class="row">
                              <div class="col-lg-10 mx-auto">
                                <div class="card border-0 shadow-xxs-2">
                                  <div class="card-body p-6">
                                    <h3 class="card-title mb-3 text-heading fs-22 lh-15">Paquetes</h3>
                                    <div class="col-md-12 text-center">
                                      <div class="row text-center" id="listar-paquetes"></div>
                                    </div>

                                    <div class="col-md-12 mt-2">
                                      <button type="button" class="btn btn-lg btn-secondary mb-3 btn-add-paquete">
                                        <i class="fal fa-plus-circle mr-1"></i>
                                        Agregar nuevo paquete
                                      </button>
                                    </div>
                                  </div>
                                </div>
                              </div>

                            </div>

                            <div class="d-flex flex-wrap mt-4">
                              <a href="#" class="btn btn-lg bg-hover-white border rounded-lg mb-3 mr-auto prev-button">
                                <span class="d-inline-block text-primary mr-2 fs-16"><i class="fal fa-long-arrow-left"></i></span>Regresar
                              </a>

                              <button class="btn btn-lg btn-primary next-button mb-3">Continuar
                                <span class="d-inline-block ml-2 fs-16"><i class="fal fa-long-arrow-right"></i></span>
                              </button>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>

                    <div class="tab-pane tab-pane-parent fade px-0" id="ubicacion-negocio" role="tabpanel" aria-labelledby="ubicacion-negocio-tab">
                      <div class="card bg-transparent border-0">
                        <div class="card-header d-block d-md-none bg-transparent px-0 py-1 border-bottom-0" id="heading-ubicacion-negocio">
                          <h5 class="mb-0">
                            <button type="button" class="btn btn-block collapse-parent collapsed border shadow-none" data-target="#ubicacion-negocio-collapse" aria-expanded="true" aria-controls="ubicacion-negocio-collapse" data-number="4.">
                              Ubicación
                            </button>
                          </h5>
                        </div>
                        <div id="ubicacion-negocio-collapse" class="collapse collapsible" aria-labelledby="heading-ubicacion-negocio" data-parent="#collapse-tabs-accordion">
                          <div class="card-body py-4 py-md-0 px-0">
                            <div class="row">
                              <div class="col-lg-10 mx-auto">
                                <div class="card border-0 shadow-xxs-2">
                                  <div class="card-body p-6">
                                    <h3 class="card-title mb-3 text-heading fs-22 lh-15">Ubicación de tu negocio</h3>

                                    <div class="row">
                                      <?php
                                      $query = "SELECT
                                          idEstado,
                                          Estado
                                        FROM estados
                                        ORDER BY Estado
                                      ";

                                      $query_result = mysqli_query($mysqli, $query);
                                      ?>
                                      <div class="col-md-4">
                                        <div class="form-group">
                                          <label class="text-heading" for="estado"><span class="text-danger">*</span>Estado</label>
                                          <select id="estado" class="form-control form-control-lg select2" name="estado">
                                            <option value="">Seleccionar</option>
                                            <?php while ($row = mysqli_fetch_array($query_result)) :
                                              $state_selected = $row['idEstado'] == $data_neg['idEstado'] ? 'selected' : '';
                                            ?>
                                              <option <?= $state_selected; ?> value="<?= $row['idEstado']; ?>"><?= $row['Estado']; ?></option>
                                            <?php endwhile; ?>
                                          </select>
                                        </div>
                                      </div>

                                      <?php
                                      $query = "SELECT
                                          EC.idEstadoCiudad,
                                          EC.idEstado,
                                          EC.idCiudad,
                                          C.Ciudad
                                        FROM estados_ciudades AS EC
                                          LEFT JOIN ciudades AS C ON (EC.idCiudad = C.idCiudad)
                                        WHERE EC.idEstado = $data_neg[idEstado]
                                        ORDER BY C.Ciudad ASC
                                      ";

                                      $query_result = mysqli_query($mysqli, $query);
                                      $num_rows     = mysqli_num_rows($query_result);
                                      ?>

                                      <div class="col-md-5">
                                        <div class="form-group">
                                          <label class="text-heading" for="ciudad"><span class="text-danger">*</span>Ciudad</label>
                                          <select id="ciudad" class="form-control form-control-lg select2" name="ciudad">
                                            <?php if ($num_rows) : ?>
                                              <option value="Seleccionar"></option>

                                              <?php while ($row = mysqli_fetch_array($query_result)) :
                                                $selected_city = $data_neg['idCiudad'] == $row['idCiudad'] ? 'selected' : '';
                                              ?>
                                                <option <?= $selected_city; ?> value="<?= $row['idCiudad']; ?>"><?= $row['Ciudad']; ?></option>
                                              <?php endwhile; ?>
                                            <?php endif; ?>
                                          </select>
                                        </div>
                                      </div>
                                    </div>

                                    <span class="mt-5"><b>INDICA LA UBICACIÓN EXACTA</b></span>
                                    <div class="row">
                                      <div class="col-sm-12 col-md-12">
                                        <div class="form-group">
                                          <label class="mb-0" for="pac-input"><span style="color: red;"><b>¡IMPORTANTE!</b></span> Arrastra el marcador hasta la ubicación de tu negocio ó buscalo aqui colocando el nombre de tu salón o su dirección completa.</label>
                                          <div class="input-group">
                                            <input type="text" id="pac-input" name="pac-input" class="form-control form-control-lg" placeholder="Buscar negocio">
                                          </div>
                                        </div>
                                      </div>
                                    </div>

                                    <input type="hidden" name="latitud" id="latitud" value="<?= $data_neg['Latitud'] ?>">
                                    <input type="hidden" name="longitud" id="longitud" value="<?= $data_neg['Longitud'] ?>">

                                    <div class="row" id="map-coords" data-latitude="<?= $data_neg['Latitud'] ?>" data-longitud="<?= $data_neg['Longitud'] ?>">
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
                                      <div class="col-sm-12 col-md-12 mt-2">
                                        <div class="form-group">
                                          <label for="direccion" class="text-heading">Dirección</label>
                                          <input type="text" name="direccion" id="direccion" class="form-control form-control-lg" value="<?= $data_neg['Direccion'] ?>">
                                        </div>
                                      </div>
                                    </div>
                                  </div>
                                </div>
                              </div>

                            </div>

                            <div class="d-flex flex-wrap mt-4">
                              <a href="#" class="btn btn-lg bg-hover-white border rounded-lg mb-3 mr-auto prev-button">
                                <span class="d-inline-block text-primary mr-2 fs-16"><i class="fal fa-long-arrow-left"></i></span>Regresar
                              </a>

                              <div id="btn-next-tab">
                                <?php if ($data_neg['Tipo'] !== 'Salon') : ?>
                                  <button type="button" class="btn btn-lg btn-primary mb-3" onclick="showGaleria()">Continuar
                                    <span class="d-inline-block ml-2 fs-16"><i class="fal fa-long-arrow-right"></i></span>
                                  </button>
                                <?php endif; ?>

                                <?php if ($data_neg['Tipo'] === 'Salon') : ?>
                                  <a href="#" class="btn btn-lg bg-hover-white border rounded-lg mb-3 mr-auto prev-button">
                                    <span class="d-inline-block text-primary mr-2 fs-16"><i class="fal fa-long-arrow-left"></i></span>Regresar
                                  </a>
                                <?php endif; ?>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>

                    <?php
                    $query = "SELECT idCatalogo, idSalon, idServicio FROM catalogo_salon_servicios WHERE idSalon = '$data_neg[idSalon]'";
                    $query_result_se = query($query);

                    $servicios = array();

                    if ($query_result_se) {
                      while ($row = mysqli_fetch_array($query_result_se)) {
                        array_push($servicios, $row['idServicio']);
                      }
                    }

                    $query = "SELECT idCatalogo, idSalon, idAmenidad FROM catalogo_salon_amenidades WHERE idSalon = '$data_neg[idSalon]'";
                    $query_result_am = query($query);

                    $amenidades = array();

                    if ($query_result_am) {
                      while ($row = mysqli_fetch_array($query_result_am)) {
                        array_push($amenidades, $row['idAmenidad']);
                      }
                    }
                    ?>
                    <div class="tab-pane tab-pane-parent fade px-0 tipo-salon" id="servicios-amenidades" role="tabpanel" aria-labelledby="servicios-amenidades-tab">
                      <div class="card bg-transparent border-0">
                        <div class="card-header d-block d-md-none bg-transparent px-0 py-1 border-bottom-0" id="heading-servicios-amenidades">
                          <h5 class="mb-0">
                            <button type="button" class="btn btn-block collapse-parent collapsed border shadow-none" data-target="#servicios-amenidades-collapse" aria-expanded="true" aria-controls="servicios-amenidades-collapse" data-number="5.">
                              Servicios y Amenidades
                            </button>
                          </h5>
                        </div>
                        <div id="servicios-amenidades-collapse" class="collapse collapsible" aria-labelledby="heading-servicios-amenidades" data-parent="#collapse-tabs-accordion">
                          <div class="card-body py-4 py-md-0 px-0">
                            <div class="row">
                              <div class="col-lg-10 mx-auto">
                                <div class="card border-0 shadow-xxs-2">
                                  <div class="card-body p-6">
                                    <h3 class="card-title mb-3 text-heading fs-22 lh-15">Servicios y Amenidades</h3>

                                    <span><b>SERVICIOS</b></span>
                                    <?php
                                    $query = "SELECT idServicio, Servicio FROM servicios";
                                    $query_result = mysqli_query($mysqli, $query);
                                    $num_rows = mysqli_num_rows($query_result);
                                    ?>

                                    <?php if ($num_rows) : ?>
                                      <div class="row">
                                        <?php while ($row = mysqli_fetch_array($query_result)) : ?>
                                          <?php
                                          $checked = in_array($row['idServicio'], $servicios) ? 'checked' : '';
                                          $delete_servicio = in_array($row['idServicio'], $servicios) ? 'delete-servicio' : '';
                                          ?>
                                          <div class="col-sm-6 col-md-4 mb-2">
                                            <div class="card">
                                              <div class="card-body align-middle">
                                                <div class="custom-control custom-checkbox">
                                                  <input <?= $checked ?> type="checkbox" class="custom-control-input <?= $delete_servicio ?>" name="servicios[]" id="servicio-<?= $row['idServicio'] ?>" value="<?= $row['idServicio'] ?>">
                                                  <label class="custom-control-label  mb-0 fs-15 lh-15" for="servicio-<?= $row['idServicio'] ?>">
                                                    <?= $row['Servicio'] ?>
                                                  </label>
                                                </div>
                                              </div>
                                            </div>
                                          </div>
                                        <?php endwhile; ?>
                                      </div>
                                    <?php endif; ?>

                                    <span class="mt-5"><b>AMENIDADES</b></span>
                                    <?php
                                    $query = "SELECT idAmenidad, Amenidad FROM amenidades";
                                    $query_result = mysqli_query($mysqli, $query);
                                    $num_rows = mysqli_num_rows($query_result);
                                    ?>

                                    <?php if ($num_rows) : ?>
                                      <div class="row">
                                        <?php while ($row = mysqli_fetch_array($query_result)) : ?>
                                          <?php
                                          $checked = in_array($row['idAmenidad'], $amenidades) ? 'checked' : '';
                                          $delete_amenidad = in_array($row['idAmenidad'], $amenidades) ? 'delete-amenidad' : '';
                                          ?>
                                          <div class="col-sm-6 col-md-4 mb-2">
                                            <div class="card">
                                              <div class="card-body align-middle">
                                                <div class="custom-control custom-checkbox">
                                                  <input <?= $checked ?> type="checkbox" class="custom-control-input <?= $delete_amenidad ?>" name="amenidades[]" id="amenidad-<?= $row['idAmenidad'] ?>" value="<?= $row['idAmenidad'] ?>">
                                                  <label class="custom-control-label  mb-0 fs-15 lh-15" for="amenidad-<?= $row['idAmenidad'] ?>">
                                                    <?= $row['Amenidad'] ?>
                                                  </label>
                                                </div>
                                              </div>
                                            </div>
                                          </div>
                                        <?php endwhile; ?>
                                      </div>
                                    <?php endif; ?>
                                  </div>
                                </div>
                              </div>
                            </div>

                            <div class="d-flex flex-wrap mt-4">
                              <a href="#" class="btn btn-lg bg-hover-white border rounded-lg mb-3 mr-auto prev-button">
                                <span class="d-inline-block text-primary mr-2 fs-16"><i class="fal fa-long-arrow-left"></i></span>Regresar
                              </a>

                              <div id="btn-next-tab">
                                <?php if ($data_neg['Tipo'] == 'Salon') : ?>
                                  <button type="button" class="btn btn-lg btn-primary next-button mb-3" onclick="showServicios()">Continuar
                                    <span class="d-inline-block ml-2 fs-16"><i class="fal fa-long-arrow-right"></i></span>
                                  </button>
                                <?php endif; ?>

                                <?php if ($data_neg['Tipo'] != 'Salon') : ?>
                                  <button type="button" class="btn btn-lg btn-primary mb-3" onclick="showGaleria()">Continuar
                                    <span class="d-inline-block ml-2 fs-16"><i class="fal fa-long-arrow-right"></i></span>
                                  </button>
                                <?php endif; ?>
                              </div>
                            </div>


                            <!-- <div class="d-flex flex-wrap">
                            <a href="#" class="btn btn-lg bg-hover-white border rounded-lg mb-3 mr-auto prev-button">
                              <span class="d-inline-block text-primary mr-2 fs-16"><i class="fal fa-long-arrow-left"></i></span>Prev step
                            </a>
                            <button class="btn btn-lg btn-primary mb-3" type="submit">Submit property
                            </button>
                          </div> -->
                          </div>
                        </div>
                      </div>
                    </div>

                    <div class="tab-pane tab-pane-parent fade px-0" id="galeria-fotos" role="tabpanel" aria-labelledby="galeria-fotos-tab">
                      <div class="card bg-transparent border-0">
                        <div class="card-header d-block d-md-none bg-transparent px-0 py-1 border-bottom-0" id="heading-galeria-fotos">
                          <h5 class="mb-0">
                            <button type="button" class="btn btn-block collapse-parent collapsed border shadow-none" data-target="#galeria-fotos-collapse" aria-expanded="true" aria-controls="galeria-fotos-collapse" data-number="6.">
                              Galería de fotos
                            </button>
                          </h5>
                        </div>
                        <div id="galeria-fotos-collapse" class="collapse collapsible" aria-labelledby="heading-galeria-fotos" data-parent="#collapse-tabs-accordion">
                          <div class="card-body py-4 py-md-0 px-0">
                            <div class="row">
                              <div class="col-lg-10 mx-auto">
                                <div class="card border-0 shadow-xxs-2">
                                  <div class="card-body p-6">
                                    <h3 class="card-title mb-3 text-heading fs-22 lh-15">Galería de fotos</h3>

                                    <div class="row">
                                      <div class="col-md-12 mt-2">
                                        <label class="mb-0">Imagen principal</label>
                                        <div id="imagen-salon" data-name="ImagenSalon" data-title="Agregar imagen"></div>
                                      </div>
                                    </div>

                                    <div class="row">
                                      <div class="col-md-12 mt-2">
                                        <label class="mb-0">Galería de imagenes</label>
                                        <div id="galeria-imagenes" data-name="Galeria" data-idListar="listar-galeria-imagenes"></div>
                                      </div>
                                    </div>
                                  </div>
                                </div>
                              </div>
                            </div>

                            <div class="d-flex flex-wrap mt-4">
                              <div id="btn-back-tab" class="mr-auto">
                                <?php if ($data_neg['Tipo'] !== 'Salon') : ?>
                                  <a href="javascript:void(0)" class="btn btn-lg bg-hover-white border rounded-lg mb-3" onclick="goBack()">
                                    <span class="d-inline-block text-primary mr-2 fs-16"><i class="fal fa-long-arrow-left"></i></span>Regresar
                                  </a>
                                <?php endif; ?>

                                <?php if ($data_neg['Tipo'] === 'Salon') : ?>
                                  <a href="#" class="btn btn-lg bg-hover-white border rounded-lg mb-3 mr-auto prev-button">
                                    <span class="d-inline-block text-primary mr-2 fs-16"><i class="fal fa-long-arrow-left"></i></span>Regresar
                                  </a>
                                <?php endif; ?>
                              </div>

                              <button class="btn btn-lg btn-primary mb-3" type="button" onclick="validateForm()">Actualizar negocio
                              </button>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>

                  <input type="hidden" name="idSalon" id="idSalon" value="<?= $data_neg['idSalon'] ?>">
                  <input type="hidden" name="action" value="edit_business">
                </form>
              </div>
            </div>
          </div>

          <!-- PAGE LOADING -->
          <div id="page-loading" class="overlay" data-animated-id="2">
            <div class="fa-3x overlay-icon">
              <i class="fa fa-spinner fa-spin fa-fw text-primary"></i>
            </div>
          </div>
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
  </script>

  <script src="js/google-search.js"></script>
  <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBcXIXiRSirvWVofs7wRolh-WjSSUF4jIE&callback=setMapa&libraries=places&v=weekly" async></script>
  <script>
    $(document).ready(function() {
      loadGaleryImages();
      loadPackages();
      loadEventTypes();
    });

    var image = `<?= $data_neg['Imagen']; ?>`;

    if (image) createGlobalFilePreviewCropped({
      idPicker: 'imagen-salon',
      idFile: <?= $data_neg['idSalon'] ?>,
      fileName: `<?= $data_neg['Imagen'] ?>`,
      extraClass: 'delete-principal-image',
      uriImage: `../src/assets/images/listing/`
    });

    $('.select2').select2({
      theme: 'bootstrap4'
    });
  </script>
</body>

</html>