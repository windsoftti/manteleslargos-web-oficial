<?php
include 'inc/session.php';
$meta_title = 'Nueva invitación';
?>

<!doctype html>
<html lang="es">

<head>
  <?php include 'inc/meta-tags.php'; ?>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap4-datetimepicker@5.2.3/build/css/bootstrap-datetimepicker.min.css">
  <!-- <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css" integrity="sha384-AYmEC3Yw5cVb3ZcuHtOA93w35dYTsvhLPVnYs9eStHfGJvOvKxVfELGroGkvsg+p" crossorigin="anonymous" /> -->

  <style>
    .template-container {
      display: flex;
      height: 100%;
      width: 100%;
      position: relative;
      align-items: center;
      justify-content: center;
      transition: 0.1s;
    }

    .template-icon {
      display: none;
      position: absolute;
      font-size: 30px;
      color: #000;
      transition: 0.1s;
    }

    .template-container:hover {
      transform: scale(1.04);
    }

    .template-container:hover>img {
      opacity: 0.8;
    }

    .template-container:hover>i {
      display: block;
    }

    .ni-card>.card>.card-body {
      display: none;
      margin: auto;
    }

    .ni-card-open>.card>.card-body {
      display: block;
    }

    .ni-card.ni-card-open>.card>.btn-add-card {
      display: none;
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

            <div class="alert alert-warning" role="alert" id="tab-alert" style="display: none;"></div>

            <div class="collapse-tabs new-property-step mb-5">
              <ul class="nav nav-pills border py-2 px-3 mb-6 d-none d-md-flex mb-6" role="tablist">
                <li class="nav-item col">
                  <a class="nav-link active bg-transparent shadow-none py-2 font-weight-500 text-center lh-214 d-block" id="general-data-tab" data-toggle="pill" data-number="1." href="#general-data" role="tab" aria-controls="general-data" aria-selected="true">
                    <span class="number">1.</span> Datos generales
                  </a>
                </li>

                <li class="nav-item col">
                  <a class="nav-link bg-transparent shadow-none py-2 font-weight-500 text-center lh-214 d-block" id="who-and-where-tab" data-toggle="pill" data-number="2." href="#who-and-where" role="tab" aria-controls="who-and-where" aria-selected="false">
                    <span class="number">2.</span> Donde y Cuando
                  </a>
                </li>

                <li class="nav-item col">
                  <a class="nav-link bg-transparent shadow-none py-2 font-weight-500 text-center lh-214 d-block" id="images-galery-tab" data-toggle="pill" data-number="3." href="#images-galery" role="tab" aria-controls="images-galery" aria-selected="false">
                    <span class="number">3.</span> Galería de imagenes
                  </a>
                </li>
              </ul>
              <div class="tab-content shadow-none p-0">
                <form id="digital-invitations-form" method="POST" autocomplete="off" action="mi-invitacion" target="_blank">
                  <div id="collapse-tabs-accordion p-2">
                    <div class="tab-pane tab-pane-parent fade show active px-0" id="general-data" role="tabpanel" aria-labelledby="general-data-tab">
                      <div class="card bg-transparent border-0">
                        <div class="card-header d-block d-md-none bg-transparent px-0 py-1 border-bottom-0" id="heading-general-data">
                          <h5 class="mb-0">
                            <button class="btn btn-lg collapse-parent btn-block border shadow-none" data-toggle="collapse" data-number="1." data-target="#general-data-collapse" aria-expanded="true" aria-controls="general-data-collapse">
                              <span class="number">1.</span> Datos generales
                            </button>
                          </h5>
                        </div>
                        <div id="general-data-collapse" class="collapse show collapsible" aria-labelledby="heading-general-data" data-parent="#collapse-tabs-accordion">
                          <div class="card-body py-4 py-md-0 px-0">
                            <div class="col-md-10 mx-auto m-3">
                              <div class="card">
                                <div class="card-body">
                                  <h3 class="card-title">Datos generales</h3>

                                  <div class="row">
                                    <div class="col-md-6 mb-2">
                                      <label class="mb-0" for="invitationType">Tipo de invitación</label>
                                      <select id="invtationType" class="form-control" name="invitationType">
                                        <option value="">Seleccionar</option>
                                        <option value="Aniversario">Aniversario</option>
                                        <option value="Bodas">Bodas</option>
                                        <option value="XV Años">XV Años</option>
                                        <option value="Bautizos">Bautizos</option>
                                        <option value="Cumpleaños">Cumpleaños</option>
                                        <option value="Convenciones">Convenciones</option>
                                        <option value="Otros">Otros</option>
                                      </select>
                                    </div>
                                  </div>

                                  <div class="row">
                                    <div class="col-md-6 mb-2">
                                      <label class="mb-0" for="personName">Nombre(s) de los festejado(s)</label>
                                      <input id="personName" class="form-control" type="text" name="personName" placeholder="Aitana">
                                    </div>

                                    <div class="col-md-6 mb-2">
                                      <label class="mb-0" for="eventName">Nombre del evento (Subtitulo)</label>
                                      <input id="eventName" class="form-control" type="text" name="eventName" placeholder="Mi Bautizo">
                                    </div>
                                  </div>

                                  <div class="row">
                                    <div class="col-md-5 mb-2">
                                      <label class="mb-0" for="phone">Celular/WhatsApp</label>
                                      <input id="phone" class="form-control" type="text" name="phone" placeholder="9613652540">
                                    </div>
                                  </div>

                                  <div class="row mb-5">
                                    <div class="col-md-12">
                                      <label class="mb-0" for="commemorativePhrase">Frase conmemorativa</label>
                                      <textarea id="commemorativePhrase" class="form-control" rows="2" name="commemorativePhrase" placeholder="Mi familia y yo te esperamos para celebrar"></textarea>
                                    </div>
                                  </div>

                                  <span class="mt-4"><b>SELECCIONAR PLANTILLA</b></span>
                                  <div class="row text-center mb-4">
                                    <div class="col-sm-6 col-md-4 mb-2">
                                      <div class="form-check align-middle">
                                        <input id="template-01" class="form-check-input" type="radio" name="template" value="plantilla-01">

                                        <label class="form-check-label" for="template-01">Plantilla 1</label>
                                      </div>

                                      <a class="img-template" href="#" data-toggle="modal" data-target="#modal-templates" data-title="Plantilla 1" data-image="images/template.jpg">
                                        <div class="template-container">
                                          <img class="img-responsive m-1" src="images/template.jpg" alt="Plantilla 01">
                                          <i class="fal fa-search template-icon"></i>
                                        </div>
                                      </a>
                                    </div>

                                    <div class="col-sm-6 col-md-4 mb-2">
                                      <div class="form-check align-middle">
                                        <input id="template-02" class="form-check-input" type="radio" name="template" value="plantilla-02">

                                        <label class="form-check-label" for="template-02">Plantilla 2</label>
                                      </div>

                                      <a class="img-template" href="#" data-toggle="modal" data-target="#modal-templates" data-title="Plantilla 2" data-image="images/template.jpg">
                                        <div class="template-container">
                                          <img class="img-responsive m-1" src="images/template.jpg" alt="Plantilla 02">
                                          <i class="fal fa-search template-icon"></i>
                                        </div>
                                      </a>
                                    </div>

                                    <div class="col-sm-6 col-md-4 mb-2">
                                      <div class="form-check align-middle">
                                        <input id="template-03" class="form-check-input" type="radio" name="template" value="plantilla-03">

                                        <label class="form-check-label" for="template-03">Plantilla 3</label>
                                      </div>

                                      <a class="img-template" href="#" data-toggle="modal" data-target="#modal-templates" data-title="Plantilla 3" data-image="images/template.jpg">
                                        <div class="template-container">
                                          <img class="img-responsive m-1" src="images/template.jpg" alt="Plantilla 03">
                                          <i class="fal fa-search template-icon"></i>
                                        </div>
                                      </a>
                                    </div>

                                    <?php include 'modals/templates.php'; ?>
                                  </div>


                                  <span class="mt-4"><b>ELIGE LOS COLORES DE TU PLANTILLA</b></span>
                                  <div class="row">
                                    <div class="col-md-3 col-sm-4 mb-2">
                                      <label class="mb-0" for="principalColor">Color principal</label>
                                      <input id="principalColor" class="form-control form-control-lg" type="color" name="principalColor">
                                    </div>

                                    <div class="col-md-3 col-sm-4 mb-2">
                                      <label class="mb-0" for="secondarycolor">Color secundario</label>
                                      <input id="secondaryColor" class="form-control form-control-lg" type="color" name="secondaryColor">
                                    </div>
                                  </div>
                                </div>
                              </div>
                            </div>

                            <div class="text-right">
                              <button type="button" class="btn btn-primary btn-show-preview mr-2">
                                Visualizar Invitación
                              </button>

                              <button class="btn btn-lg btn-primary next-button">Siguiente
                                <span class="d-inline-block ml-2 fs-16"><i class="fal fa-long-arrow-right"></i></span>
                              </button>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>

                    <div class="tab-pane tab-pane-parent fade px-0" id="who-and-where" role="tabpanel" aria-labelledby="who-and-where-tab">
                      <div class="card bg-transparent border-0">
                        <div class="card-header d-block d-md-none bg-transparent px-0 py-1 border-bottom-0" id="heading-who-and-where">
                          <h5 class="mb-0">
                            <button class="btn btn-lg collapse-parent btn-block border shadow-none" data-toggle="collapse" data-number="2." data-target="#who-and-where-collapse" aria-expanded="true" aria-controls="who-and-where-collapse">
                              <span class="number">2.</span> Donde y Cuando
                            </button>
                          </h5>
                        </div>
                        <div id="who-and-where-collapse" class="collapse collapsible" aria-labelledby="heading-who-and-where" data-parent="#collapse-tabs-accordion">
                          <div class="card-body py-4 py-md-0 px-0">
                            <div class="row p-2">
                              <div id="cr-card" class="col-md-6 ni-card ni-card-open">
                                <div class="card">
                                  <button type="button" class="btn btn-primary btn-add-card">
                                    <i class="fal fa-plus-circle mr-1"></i> Agregar ceremonia religiosa
                                  </button>

                                  <div class="card-body">
                                    <h3 class="card-title d-flex justify-content-between">
                                      Ceremonia religiosa

                                      <a id="btn-remove-rc-card" href="javascript:void(0)">
                                        <i class="fal fa-times text-danger"></i>
                                      </a>
                                    </h3>

                                    <div class="row">
                                      <div class="col-md-12 mb-2">
                                        <label class="mb-0" for="cr-image">Foto del lugar</label>
                                        <div id="cr-image" data-name="crImage" data-title="Adjuntar imagen"></div>
                                      </div>
                                    </div>

                                    <div class="row">
                                      <div class="col-md-6 mb-2">
                                        <label class="mb-0" for="CRPlace">Lugar</label>
                                        <input id="CRPlace" class="form-control" type="text" name="CRPlace" placeholder="IGLESIA CATÓLICA SAN MARTÍN">
                                      </div>

                                      <div class="col-md-6 mb-2">
                                        <label class="mb-0" for="CRDate">Fecha y Hora</label>
                                        <input id="CRDate" class="form-control datetime" type="text" name="CRDate">
                                      </div>
                                    </div>

                                    <div id="CR"></div>
                                  </div>
                                </div>
                              </div>

                              <div id="r-card" class="col-md-6 ni-card ni-card-open">
                                <div class="card">
                                  <button type="button" class="btn btn-primary btn-add-card">
                                    <i class="fal fa-plus-circle mr-1"></i> Agregar ceremonia de recepción
                                  </button>

                                  <div class="card-body">
                                    <h3 class="card-title d-flex justify-content-between">
                                      Recepción
                                      <a id="btn-remove-r-card" href="javascript:void(0)">
                                        <i class="fal fa-times text-danger"></i>
                                      </a>
                                    </h3>

                                    <div class="row">
                                      <div class="col-md-12 mb-2">
                                        <label class="mb-0" for="r-image">Foto del lugar</label>
                                        <div id="r-image" data-name="rImage" data-title="Adjuntar imagen"></div>
                                      </div>
                                    </div>

                                    <div class="row">
                                      <div class="col-md-6 mb-2">
                                        <label class="mb-0" for="RPlace">Lugar</label>
                                        <input id="RPlace" class="form-control" type="text" name="RPlace" placeholder="SALÓN EL MESÓN">
                                      </div>

                                      <div class="col-md-6 mb-2">
                                        <label class="mb-0" for="RDate">Fecha y Hora</label>
                                        <input id="RDate" class="form-control datetime" type="text" name="RDate">
                                      </div>
                                    </div>

                                    <div id="Recepcion"></div>
                                  </div>
                                </div>
                              </div>
                            </div>

                            <div class="d-flex flex-wrap">
                              <a href="#" class="btn btn-lg bg-hover-white border rounded-lg mb-3 mr-auto prev-button">
                                <span class="d-inline-block text-primary mr-2 fs-16"><i class="fal fa-long-arrow-left"></i></span>Regresar
                              </a>

                              <div class="text-right">
                                <button type="button" class="btn btn-primary btn-show-preview mb-2 mr-2">
                                  Visualizar Invitación
                                </button>

                                <button class="btn btn-lg btn-primary next-button mb-3">Siguiente
                                  <span class="d-inline-block ml-2 fs-16"><i class="fal fa-long-arrow-right"></i></span>
                                </button>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>

                    <div class="tab-pane tab-pane-parent fade px-0" id="images-galery" role="tabpanel" aria-labelledby="images-galery-tab">
                      <div class="card bg-transparent border-0">
                        <div class="card-header d-block d-md-none bg-transparent px-0 py-1 border-bottom-0" id="heading-images-galery">
                          <h5 class="mb-0">
                            <button class="btn btn-block collapse-parent collapsed border shadow-none" data-toggle="collapse" data-number="3." data-target="#images-galery-collapse" aria-expanded="true" aria-controls="images-galery-collapse">
                              <span class="number">3.</span> Galería de imagenes
                            </button>
                          </h5>
                        </div>
                        <div id="images-galery-collapse" class="collapse collapsible" aria-labelledby="heading-images-galery" data-parent="#collapse-tabs-accordion">
                          <div class="card-body py-4 py-md-0 px-0">
                            <div class="row">
                              <div class="col-md-10 mx-auto">
                                <div class="card m-2">
                                  <div class="card-body">
                                    <h3 class="card-title">Galería de imagenes</h3>

                                    <div class="row">
                                      <div class="col-md-6 mb-2">
                                        <label class="mb-0" for="individual-picture">Imagen individual</label>
                                        <div id="individual-picture" data-name="individualPicture" data-title="Adjuntar imagen"></div>
                                      </div>

                                      <div class="col-md-6 mb-2">
                                        <label class="mb-0" for="family-picture">Imagen familiar</label>
                                        <div id="family-picture" data-name="familyPicture" data-title="Adjuntar imagen"></div>
                                      </div>
                                    </div>

                                    <div class="row">
                                      <div class="col-md-12 mb-2">
                                        <label class="mb-0">Galería de imagenes</label>
                                        <div id="image-gallery" data-name="imageGallery"></div>
                                      </div>
                                    </div>
                                  </div>
                                </div>
                              </div>
                            </div>

                            <input type="hidden" name="action" value="add_invitation">

                            <div class="d-flex flex-wrap">
                              <a href="#" class="btn btn-lg bg-hover-white border rounded-lg mb-3 mr-auto prev-button">
                                <span class="d-inline-block text-primary mr-2 fs-16"><i class="fal fa-long-arrow-left"></i></span>Regresar
                              </a>

                              <div class="text-right">
                                <button type="button" class="btn btn-primary btn-show-preview mb-2 mr-2">
                                  Visualizar Invitación
                                </button>

                                <button id="btn-send-data" class="btn btn-lg btn-primary mb-3" type="button">Crear invitación
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

  <script src="js/dynamic-picker.js"></script>
  <script src="js/dynamic-multiple-picker.js"></script>

  <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBcXIXiRSirvWVofs7wRolh-WjSSUF4jIE&libraries=places&v=weekly"></script>

  <script src="js/multiple-google-maps.js"></script>
  <script src="main/digital-invitations/digital-invitations.js"></script>

  <script>
    /* $(".datetime").datetimepicker({
      format: 'yyyy-mm-dd hh:ii'
    }); */

    $('.datetime').datetimepicker({
      format: 'DD/MM/YYYY hh:mm a',
      useCurrent: false,
      showTodayButton: true,
      showClear: true,
      toolbarPlacement: 'bottom',
      sideBySide: true,
      icons: {
        time: "fal fa-clock",
        date: "fal fa-calendar",
        up: "fal fa-arrow-up",
        down: "fal fa-arrow-down",
        previous: "fal fa-chevron-left",
        next: "fal fa-chevron-right",
        today: "fal fa-clock",
        clear: "fal fa-trash",
        close: "fal fa-times"
      },
      locale: 'es-es'
    });
  </script>
</body>

</html>