<!DOCTYPE html>
<html lang="es">

<head>
  <?php include 'src/components/head.php'; ?>
  <link rel="stylesheet" href="src/plugins/jquery-ui-datepicker/jquery-ui.min.css">
  <link rel="stylesheet" href="src/css/pages/listing.css">
</head>

<body class="navbar-white">
  <!-- Preloader -->
  <?php include 'src/components/preloader.php'; ?>

  <!-- Navbar -->
  <?php include 'src/components/navbar.php'; ?>

  <!-- Main -->
  <main class="main">
    <section class="img-section">
      <img class="img-mobile" src="src/assets/images/listing/top/mateleslargos_xv_anios_mobile.png" alt="XV Años">
      <img class="img-desktop" src="src/assets/images/listing/top/mateleslargos_xv_anios_desktop.png" alt="XV Años">
    </section>

    <section class="breadcrumbs">
      <ul>
        <li>
          <a href=".">Home</a>
        </li>
        <li>
          <a href=".">XV Años</a>
        </li>
        <li>
          <a href=".">Salones y jardines</a>
        </li>
      </ul>

      <div>
        <img src="src/assets/images/eventtypes/manteleslargos_quince_hover.png" alt="XV Años">
        <h1>XV Años</h1>
      </div>

      <h2>Salones y jardines para eventos</h2>
    </section>

    <section class="global-search-section">
      <form method="POST">
        <input id="have-date" type="checkbox" name="haveDate" value="checked">
        <div class="checkbox">
          <label for="have-date">
            Tengo fecha del evento
          </label>
        </div>

        <div class="content">
          <div class="search">
            <ion-icon name="search"></ion-icon>
            <input type="text" placeholder="¿QUÉ BUSCAS?">
          </div>

          <select>
            <option value="">ESTADO</option>
          </select>

          <select>
            <option value="">CIUDAD</option>
          </select>

          <div class="dateinput">
            <ion-icon name="calendar-outline"></ion-icon>
            <input class="datepicker" type="text" placeholder="¿CUANDO?">
          </div>

          <button type="submit">
            BUSCAR
          </button>
        </div>
      </form>
    </section>

    <section id="listing" class="listing">
      <div class="listing-header">
        <p class="results"><span>60</span> RESULTADOS</p>

        <button class="btn-filters">
          Filtros <ion-icon name="options-outline"></ion-icon>
        </button>

        <label class="map-action">
          <ion-icon name="map-outline"></ion-icon>
          Mapa

          <label class="switch">
            <input id="map-mode" type="checkbox" value="true">
            <span class="slider"></span>
          </label>
        </label>
      </div>

      <div class="listing-body">
        <div id="listing-filters" class="filters">
          <div>
            <div class="top">
              <h1>Filtros</h1>

              <a class="btn-filters" href="javascript:void(0)">&times;</a>
            </div>

            <form class="bottom" autocomplete="off">
              <div class="form-group">
                <div class="input-group">
                  <input id="filterSearch" type="text" placeholder="Buscar">

                  <div class="prepend">
                    <ion-icon name='search'></ion-icon>
                  </div>
                </div>
              </div>

              <div class="form-group">
                <label for="supplierType">Tipo de proveedor</label>
                <select id="supplierType">
                  <option value="">Seleccionar</option>
                </select>
              </div>

              <div class="form-group">
                <label for="date">Disponibilidad</label>
                <input id="date" class="datepicker" type="text" placeholder="Fecha">
              </div>

              <div class="range-slider" format='text' symbol=' Per.' symbolPosition='right'>
                <label for="capacity">Capacidad</label>
                <input id="capacity" type="range" min="0" max="500" value="500">
              </div>

              <div class="range-slider" format='money'>
                <label for="price">Precio</label>
                <input id="price" type="range" min="0" max="50000" value="50000">
              </div>

              <h3 class="heading">Servicios</h3>
              <div class="checkbox-group between mb">
                <div>
                  <input id="desayuno-service" type="checkbox">
                  <label for="desayuno-service">Desayuno</label>
                </div>

                <div>
                  <input id="comida-service" type="checkbox">
                  <label for="comida-service">Comida</label>
                </div>

                <div>
                  <input id="cena-service" type="checkbox">
                  <label for="cena-service">Cena</label>
                </div>
              </div>

              <h3 class="heading">Amenidades</h3>
              <div class="checkbox-group between mb">
                <div>
                  <input id="alberca-amenity" type="checkbox">
                  <label for="alberca-amenity">Alberca</label>
                </div>

                <div>
                  <input id="jardin-amenity" type="checkbox">
                  <label for="jardin-amenity">Jardín</label>
                </div>

                <div>
                  <input id="estacionamiento-amenity" type="checkbox">
                  <label for="estacionamiento-amenity">Estacionamiento</label>
                </div>

                <div>
                  <input id="aire-amenity" type="checkbox">
                  <label for="aire-amenity">Aire acond.</label>
                </div>

                <div>
                  <input id="juegos-amenity" type="checkbox">
                  <label for="juegos-amenity">Juegos inf.</label>
                </div>

                <div>
                  <input id="cocina-amenity" type="checkbox">
                  <label for="cocina-amenity">Cocina</label>
                </div>
              </div>

              <button class="btn btn-block btn-primary" type="submit">
                VER RESULTADOS
              </button>
            </form>
          </div>
        </div>

        <div class="listing-content">
          <div class="listing">
            <div class="listing-item">
              <div class="top">
                <img src="src/assets/images/listing/manteleslargos_01.png" alt="Listing Item">
              </div>

              <div class="middle">
                <h3>Salón Florencia</h3>

                <p>Avenida Interlomas 20345, Tuxt...</p>

                <div class="stats">
                  <p>
                    <ion-icon name="wallet-outline"></ion-icon>
                    Desde $240.00
                  </p>

                  <p>
                    <ion-icon name="person-outline"></ion-icon>
                    50 a 1000
                  </p>
                </div>

                <p class="primary">
                  Consigue descuento del 20% con el código FGSS84
                </p>
              </div>

              <div class="bottom">
                <a href="javascript:void(0)">
                  <ion-icon name="arrow-redo"></ion-icon>
                  Compartir
                </a>

                <a href="negocio">
                  <ion-icon name="information-circle"></ion-icon>
                  Información
                </a>
              </div>
            </div>

            <div class="listing-item">
              <div class="top">
                <img src="src/assets/images/listing/manteleslargos_02.png" alt="Listing Item">
              </div>

              <div class="middle">
                <h3>Salón Florencia</h3>

                <p>Avenida Interlomas 20345, Tuxt...</p>

                <div class="stats">
                  <p>
                    <ion-icon name="wallet-outline"></ion-icon>
                    Desde $240.00
                  </p>

                  <p>
                    <ion-icon name="person-outline"></ion-icon>
                    50 a 1000
                  </p>
                </div>

                <p class="primary">
                  Consigue descuento del 20% con el código FGSS84
                </p>
              </div>

              <div class="bottom">
                <a href="javascript:void(0)">
                  <ion-icon name="arrow-redo"></ion-icon>
                  Compartir
                </a>

                <a href="javascript:void(0)">
                  <ion-icon name="information-circle"></ion-icon>
                  Información
                </a>
              </div>
            </div>

            <div class="listing-item">
              <div class="top">
                <img src="src/assets/images/listing/manteleslargos_03.png" alt="Listing Item">
              </div>

              <div class="middle">
                <h3>Salón Florencia</h3>

                <p>Avenida Interlomas 20345, Tuxt...</p>

                <div class="stats">
                  <p>
                    <ion-icon name="wallet-outline"></ion-icon>
                    Desde $240.00
                  </p>

                  <p>
                    <ion-icon name="person-outline"></ion-icon>
                    50 a 1000
                  </p>
                </div>

                <p class="primary">
                  Consigue descuento del 20% con el código FGSS84
                </p>
              </div>

              <div class="bottom">
                <a href="javascript:void(0)">
                  <ion-icon name="arrow-redo"></ion-icon>
                  Compartir
                </a>

                <a href="javascript:void(0)">
                  <ion-icon name="information-circle"></ion-icon>
                  Información
                </a>
              </div>
            </div>

            <div class="listing-item">
              <div class="top">
                <img src="src/assets/images/listing/manteleslargos_04.png" alt="Listing Item">
              </div>

              <div class="middle">
                <h3>Salón Florencia</h3>

                <p>Avenida Interlomas 20345, Tuxt...</p>

                <div class="stats">
                  <p>
                    <ion-icon name="wallet-outline"></ion-icon>
                    Desde $240.00
                  </p>

                  <p>
                    <ion-icon name="person-outline"></ion-icon>
                    50 a 1000
                  </p>
                </div>

                <p class="primary">
                  Consigue descuento del 20% con el código FGSS84
                </p>
              </div>

              <div class="bottom">
                <a href="javascript:void(0)">
                  <ion-icon name="arrow-redo"></ion-icon>
                  Compartir
                </a>

                <a href="javascript:void(0)">
                  <ion-icon name="information-circle"></ion-icon>
                  Información
                </a>
              </div>
            </div>

            <div class="listing-item">
              <div class="top">
                <img src="src/assets/images/listing/manteleslargos_05.png" alt="Listing Item">
              </div>

              <div class="middle">
                <h3>Salón Florencia</h3>

                <p>Avenida Interlomas 20345, Tuxt...</p>

                <div class="stats">
                  <p>
                    <ion-icon name="wallet-outline"></ion-icon>
                    Desde $240.00
                  </p>

                  <p>
                    <ion-icon name="person-outline"></ion-icon>
                    50 a 1000
                  </p>
                </div>

                <p class="primary">
                  Consigue descuento del 20% con el código FGSS84
                </p>
              </div>

              <div class="bottom">
                <a href="javascript:void(0)">
                  <ion-icon name="arrow-redo"></ion-icon>
                  Compartir
                </a>

                <a href="javascript:void(0)">
                  <ion-icon name="information-circle"></ion-icon>
                  Información
                </a>
              </div>
            </div>

            <div class="listing-item">
              <div class="top">
                <img src="src/assets/images/listing/manteleslargos_06.png" alt="Listing Item">
              </div>

              <div class="middle">
                <h3>Salón Florencia</h3>

                <p>Avenida Interlomas 20345, Tuxt...</p>

                <div class="stats">
                  <p>
                    <ion-icon name="wallet-outline"></ion-icon>
                    Desde $240.00
                  </p>

                  <p>
                    <ion-icon name="person-outline"></ion-icon>
                    50 a 1000
                  </p>
                </div>

                <p class="primary">
                  Consigue descuento del 20% con el código FGSS84
                </p>
              </div>

              <div class="bottom">
                <a href="javascript:void(0)">
                  <ion-icon name="arrow-redo"></ion-icon>
                  Compartir
                </a>

                <a href="javascript:void(0)">
                  <ion-icon name="information-circle"></ion-icon>
                  Información
                </a>
              </div>
            </div>

            <div class="listing-item">
              <div class="top">
                <img src="src/assets/images/listing/manteleslargos_07.png" alt="Listing Item">
              </div>

              <div class="middle">
                <h3>Salón Florencia</h3>

                <p>Avenida Interlomas 20345, Tuxt...</p>

                <div class="stats">
                  <p>
                    <ion-icon name="wallet-outline"></ion-icon>
                    Desde $240.00
                  </p>

                  <p>
                    <ion-icon name="person-outline"></ion-icon>
                    50 a 1000
                  </p>
                </div>

                <p class="primary">
                  Consigue descuento del 20% con el código FGSS84
                </p>
              </div>

              <div class="bottom">
                <a href="javascript:void(0)">
                  <ion-icon name="arrow-redo"></ion-icon>
                  Compartir
                </a>

                <a href="javascript:void(0)">
                  <ion-icon name="information-circle"></ion-icon>
                  Información
                </a>
              </div>
            </div>

            <div class="listing-item">
              <div class="top">
                <img src="src/assets/images/listing/manteleslargos_08.png" alt="Listing Item">
              </div>

              <div class="middle">
                <h3>Salón Florencia</h3>

                <p>Avenida Interlomas 20345, Tuxt...</p>

                <div class="stats">
                  <p>
                    <ion-icon name="wallet-outline"></ion-icon>
                    Desde $240.00
                  </p>

                  <p>
                    <ion-icon name="person-outline"></ion-icon>
                    50 a 1000
                  </p>
                </div>

                <p class="primary">
                  Consigue descuento del 20% con el código FGSS84
                </p>
              </div>

              <div class="bottom">
                <a href="javascript:void(0)">
                  <ion-icon name="arrow-redo"></ion-icon>
                  Compartir
                </a>

                <a href="javascript:void(0)">
                  <ion-icon name="information-circle"></ion-icon>
                  Información
                </a>
              </div>
            </div>

            <div class="listing-item">
              <div class="top">
                <img src="src/assets/images/listing/manteleslargos_09.png" alt="Listing Item">
              </div>

              <div class="middle">
                <h3>Salón Florencia</h3>

                <p>Avenida Interlomas 20345, Tuxt...</p>

                <div class="stats">
                  <p>
                    <ion-icon name="wallet-outline"></ion-icon>
                    Desde $240.00
                  </p>

                  <p>
                    <ion-icon name="person-outline"></ion-icon>
                    50 a 1000
                  </p>
                </div>

                <p class="primary">
                  Consigue descuento del 20% con el código FGSS84
                </p>
              </div>

              <div class="bottom">
                <a href="javascript:void(0)">
                  <ion-icon name="arrow-redo"></ion-icon>
                  Compartir
                </a>

                <a href="javascript:void(0)">
                  <ion-icon name="information-circle"></ion-icon>
                  Información
                </a>
              </div>
            </div>

            <div class="listing-item">
              <div class="top">
                <img src="src/assets/images/listing/manteleslargos_010.png" alt="Listing Item">
              </div>

              <div class="middle">
                <h3>Salón Florencia</h3>

                <p>Avenida Interlomas 20345, Tuxt...</p>

                <div class="stats">
                  <p>
                    <ion-icon name="wallet-outline"></ion-icon>
                    Desde $240.00
                  </p>

                  <p>
                    <ion-icon name="person-outline"></ion-icon>
                    50 a 1000
                  </p>
                </div>

                <p class="primary">
                  Consigue descuento del 20% con el código FGSS84
                </p>
              </div>

              <div class="bottom">
                <a href="javascript:void(0)">
                  <ion-icon name="arrow-redo"></ion-icon>
                  Compartir
                </a>

                <a href="javascript:void(0)">
                  <ion-icon name="information-circle"></ion-icon>
                  Información
                </a>
              </div>
            </div>

            <div class="listing-item">
              <div class="top">
                <img src="src/assets/images/listing/manteleslargos_011.png" alt="Listing Item">
              </div>

              <div class="middle">
                <h3>Salón Florencia</h3>

                <p>Avenida Interlomas 20345, Tuxt...</p>

                <div class="stats">
                  <p>
                    <ion-icon name="wallet-outline"></ion-icon>
                    Desde $240.00
                  </p>

                  <p>
                    <ion-icon name="person-outline"></ion-icon>
                    50 a 1000
                  </p>
                </div>

                <p class="primary">
                  Consigue descuento del 20% con el código FGSS84
                </p>
              </div>

              <div class="bottom">
                <a href="javascript:void(0)">
                  <ion-icon name="arrow-redo"></ion-icon>
                  Compartir
                </a>

                <a href="javascript:void(0)">
                  <ion-icon name="information-circle"></ion-icon>
                  Información
                </a>
              </div>
            </div>

            <div class="listing-item">
              <div class="top">
                <img src="src/assets/images/listing/manteleslargos_012.png" alt="Listing Item">
              </div>

              <div class="middle">
                <h3>Salón Florencia</h3>

                <p>Avenida Interlomas 20345, Tuxt...</p>

                <div class="stats">
                  <p>
                    <ion-icon name="wallet-outline"></ion-icon>
                    Desde $240.00
                  </p>

                  <p>
                    <ion-icon name="person-outline"></ion-icon>
                    50 a 1000
                  </p>
                </div>

                <p class="primary">
                  Consigue descuento del 20% con el código FGSS84
                </p>
              </div>

              <div class="bottom">
                <a href="javascript:void(0)">
                  <ion-icon name="arrow-redo"></ion-icon>
                  Compartir
                </a>

                <a href="javascript:void(0)">
                  <ion-icon name="information-circle"></ion-icon>
                  Información
                </a>
              </div>
            </div>
          </div>

          <div class="pagination">
            <ul>
              <li>
                <a href="javascript:void(0)">1</a>
              </li>

              <li class="active">
                <a href="javascript:void(0)">2</a>
              </li>

              <li>
                <a href="javascript:void(0)">3</a>
              </li>

              <li class="arrow">
                <a href="javascript:void(0)">
                  Siguiente
                  <ion-icon name="arrow-forward-outline"></ion-icon>
                </a>
              </li>
            </ul>
          </div>
        </div>

        <div class="map">
          <div id="map"></div>
        </div>
      </div>
    </section>
  </main>

  <!-- Footer -->
  <?php include 'src/components/footer.php'; ?>

  <!-- Required scripts -->
  <?php include 'src/components/required-scripts.php'; ?>

  <script src="src/plugins/jquery-ui-datepicker/jquery-ui.min.js"></script>
  <script src="src/plugins/jquery-ui-datepicker/init-default-datepicker.js"></script>

  <script src="src/plugins/google-maps/google-maps.js"></script>
  <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBcXIXiRSirvWVofs7wRolh-WjSSUF4jIE&callback=initMap&v=weekly" defer></script>
</body>

</html>