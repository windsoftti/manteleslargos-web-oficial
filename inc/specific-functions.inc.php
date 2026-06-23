<?php
/* ::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::
//-- EVENT TYPES FOR NAVBAR
:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::: */
function navbarEventTypes()
{
  global $mysqli;

  $response = '';

  $query = "SELECT
      idTipoEvento,
      TipoEvento,
      slug
    FROM tipo_eventos
    ORDER BY idTipoEvento
    ASC
  ";

  $query_result = mysqli_query($mysqli, $query);
  $num_rows     = mysqli_num_rows($query_result);

  if ($num_rows) :
    $response .= '<ul class="submenu-content">';

    while ($row = mysqli_fetch_array($query_result)) :
      $event_type_id    = $row['idTipoEvento'];
      $event_type       = $row['TipoEvento'];
      $event_type_slug  = $row['slug'];

      //$style = $event_type_slug === 'otros' ? 'style="top:-10rem"' : '';
      $class = $event_type_slug === 'otros' ? 'desktop-others' : '';

      $supplier_types   = navbarSupplierTypes($event_type_slug, $event_type_id);

      $response .= '
        <li>
          <div class="submenu">
            <a class="submenu-toggle" href="javascript:void(0)">' . $event_type . '</a>

            <ul class="submenu-content ' . $class . '">
              ' . $supplier_types . '
            </ul>
          </div>
        </li>
      ';
    endwhile;

    $response .= '</ul>';
  endif;

  return $response;
}

function navbarSupplierTypes(
  $event_type_slug,
  $event_type_id
) {
  global $mysqli;

  $response = '';

  $query = "SELECT
      idTipoProveedor,
      TipoProveedor,
      slug
    FROM tipo_proveedores
    WHERE eventos LIKE '%$event_type_id%'
    ORDER BY idTipoProveedor
    ASC
  ";

  $query_result = mysqli_query($mysqli, $query);
  $num_rows     = mysqli_num_rows($query_result);

  if ($num_rows) :
    while ($row = mysqli_fetch_array($query_result)) :
      $supplier_type      = $row['TipoProveedor'];
      $supplier_type_slug = $row['slug'];
      #$absolute_url       = BASE_URL . '/negocios/' . $event_type_slug . '/' . $supplier_type_slug;
      $absolute_url       = BASE_URL . '/' . $event_type_slug . '/' . $supplier_type_slug;

      $response .= '
        <li>
          <a href="' . $absolute_url . '">' . $supplier_type . '</a>
        </li>
      ';
    endwhile;
  endif;

  return $response;
}

/* ::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::
//-- HOME PAGE
:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::: */
function getLastRecentEvent()
{
  global $mysqli;

  $query = "SELECT
      ER.idEvento,
      ER.Evento,
      ER.DescCorta,
      ER.Descripcion,
      ER.Imagen,
      ER.Fecha,
      ER.slug,
      S.Salon,
      TP.TipoProveedor,
      E.Estado,
      C.Ciudad
    FROM eventos_recientes AS ER
      LEFT JOIN salones           AS S  ON (ER.idSalon        = S.idSalon)
      LEFT JOIN tipo_proveedores  AS TP ON (S.idTipoProveedor = TP.idTipoProveedor)
      LEFT JOIN estados           AS E  ON (S.idEstado        = E.idEstado)
      LEFT JOIN ciudades          AS C  ON (S.idCiudad        = C.idCiudad)
    WHERE ER.Eliminado = 'No'
    ORDER BY ER.idEvento
    DESC
    LIMIT 1
  ";

  $query_result = mysqli_query($mysqli, $query);
  $num_rows     = mysqli_num_rows($query_result);

  if (!$num_rows) return false;

  $data = mysqli_fetch_array($query_result);

  $gallery          = getRecentEventGallery($data['idEvento'], $data['Evento']);
  $data['gallery']  = $gallery;

  return $data;
}

function getRecentEventGallery(
  $recent_event_id,
  $recent_event_name = '',
  $limit_images = null
) {
  global $mysqli;

  $response = '';

  $limit = $limit_images ? "LIMIT $limit_images" : "";
  $query = "SELECT Imagen FROM galeria_eventos_recientes WHERE idEvento = $recent_event_id $limit";

  $query_result = mysqli_query($mysqli, $query);
  $num_rows     = mysqli_num_rows($query_result);

  if ($num_rows) :
    while ($row = mysqli_fetch_array($query_result)) :
      $image = setRecentEventImage($row['Imagen'], true);

      $response .= '<img src="' . $image . '" alt="' . $recent_event_name . '" style="flex: 1;">';
    endwhile;
  endif;

  return $response;
}

/* ::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::
//-- RECENT EVENTS DEFAULT IMAGE
:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::: */
function setRecentEventImage(
  $img,
  $gallery = false
) {
  if (!$img) return BASE_URL . '/src/assets/images/500x500.png';

  $img_location = BASE_PATH . '/src/assets/images/recent-events/';
  $image        = BASE_URL . '/src/assets/images/recent-events/';

  if (!$gallery) {
    $img_location .= $img;
    $image        .= $img;
  }

  if ($gallery) {
    $img_location .= 'gallery/' . $img;
    $image        .= 'gallery/' . $img;
  }

  $img_exist = realpath($img_location);

  if ($img_exist) return $image;
  if (!$img_exist) return BASE_URL . '/src/assets/images/500x500.png';
}


/* ::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::
//-- SELECTS DATA
:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::: */
function countriesForSelect(
  $label = 'País',
  $value = ''
) {
  global $mysqli;

  $response = '';

  $query = "SELECT
      id,
      name,
      phonecode
    FROM countries
    ORDER BY name
    ASC
  ";

  $query_result = mysqli_query($mysqli, $query);

  $response .= '<option value="">' . $label . '</option>';

  while ($row = mysqli_fetch_array($query_result)) :
    $selected = $value == $row['id'] ? 'selected' : '';

    $response .= '<option ' . $selected . ' value="' . $row['id'] . '">+' . $row['phonecoe'] . '-' . $row['name'] . '</option>';
  endwhile;

  return $response;
}

function businessStatesForSelect(
  $label = 'Todas',
  $value = '',
  $return_mode = ''
) {
  global $mysqli;

  $response = '';

  $query = "SELECT
      S.idSalon,
      S.idEstado,
      E.Estado
    FROM salones AS S
      INNER JOIN estados AS E ON (S.idEstado = E.idEstado)
    WHERE S.Status != 'Eliminado'
    GROUP BY S.idEstado
    ORDER BY E.Estado
    ASC
  ";

  $query_result = mysqli_query($mysqli, $query);

  $response .= '<option value="">' . $label . '</option>';

  while ($row = mysqli_fetch_array($query_result)) :
    $selected = $value == $row['idEstado'] ? 'selected' : '';
    $option_value = $return_mode === 'return-name' ? $row['Estado'] : $row['idEstado'];

    $response .= '
      <option ' . $selected . ' value="' . $option_value . '">
        ' . $row['Estado'] . '
      </option>
    ';
  endwhile;

  return $response;
}

function businessStatesForSearch(
  $label      = 'Todas',
  $value      = '',
  $return_id  = false
) {
  global $mysqli;

  $response = '';

  $query = "SELECT
      S.idSalon,
      S.idEstado,
      E.Estado
    FROM salones AS S
      INNER JOIN estados AS E ON (S.idEstado = E.idEstado)
    WHERE S.Status != 'Eliminado'
    GROUP BY S.idEstado
    ORDER BY E.Estado
    ASC
  ";

  $query_result = mysqli_query($mysqli, $query);

  $response .= '<option value="">' . $label . '</option>';

  while ($row = mysqli_fetch_array($query_result)) :
    $selected     = $value == $row['Estado'] ? 'selected' : '';
    $option_value = $return_id ? $row['idEstado'] : $row['Estado'];

    $response .= '
      <option ' . $selected . ' value="' . $option_value . '">
        ' . $row['Estado'] . '
      </option>
    ';
  endwhile;

  return $response;
}

function businessCitysForSelect(
  $label = 'Todas',
  $state_id = '',
  $value = '',
  $return_mode = ''
) {
  global $mysqli;

  $response = '';

  $query = "SELECT
      S.idSalon,
      S.idCiudad,
      S.idEstado,
      C.Ciudad
    FROM salones AS S
      INNER JOIN ciudades AS C ON (S.idCiudad = C.idCiudad)
    WHERE
      S.idEstado = $state_id AND
      S.Status  != 'Eliminado'
    GROUP BY S.idCiudad
    ORDER BY C.Ciudad
    ASC
  ";

  $query_result = mysqli_query($mysqli, $query);

  $response .= '<option value="">' . $label . '</option>';

  while ($row = mysqli_fetch_array($query_result)) :
    $selected = $value == $row['idCiudad'] ? 'selected' : '';
    $option_value = $return_mode === 'return-name' ? $row['Ciudad'] : $row['idCiudad'];

    $response .= '<option ' . $selected . ' value="' . $option_value . '">' . $row['Ciudad'] . '</option>';
  endwhile;

  return $response;
}

function businessCitysForSearch(
  $label = 'Todas',
  $state = '',
  $value = '',
  $return_id = false
) {
  global $mysqli;

  $response = '';

  $query = "SELECT
      S.idSalon,
      S.idCiudad,
      S.idEstado,
      C.Ciudad
    FROM salones AS S
      INNER JOIN ciudades AS C ON (S.idCiudad = C.idCiudad)
      LEFT JOIN estados   AS E ON (S.idEstado = E.idEstado)
    WHERE
      E.Estado  = '$state' AND
      S.Status  != 'Eliminado'
    GROUP BY S.idCiudad
    ORDER BY C.Ciudad
    ASC
  ";

  $query_result = mysqli_query($mysqli, $query);

  $response .= '<option value="">' . $label . '</option>';

  while ($row = mysqli_fetch_array($query_result)) :
    $selected     = $value == $row['Ciudad'] ? 'selected' : '';
    $option_value = $return_id ? $row['idCiudad'] : $row['Ciudad'];

    $response .= '<option ' . $selected . ' value="' . $option_value . '">' . $row['Ciudad'] . '</option>';
  endwhile;

  return $response;
}

function statesForSelect(
  $label = 'Todas',
  $value = ''
) {
  global $mysqli;

  $response = '';

  $query = "SELECT
      idEstado,
      Estado,
      Latitud,
      Longitud
    FROM estados
    ORDER BY Estado
    ASC
  ";

  $query_result = mysqli_query($mysqli, $query);

  $response .= '<option value="">' . $label . '</option>';

  while ($row = mysqli_fetch_array($query_result)) :
    $selected = $value == $row['idEstado'] ? 'selected' : '';

    $response .= '<option 
        ' . $selected . '
        data-latitude="' . $row['Latitud'] . '"
        data-longitude="' . $row['Longitud'] . '"
        value="' . $row['idEstado'] . '"
      >
        ' . $row['Estado'] . '
      </option>
    ';
  endwhile;

  return $response;
}

function citysForSelect(
  $label = 'Todas',
  $state_id = '',
  $value = ''
) {
  global $mysqli;

  $response = '';

  $query = "SELECT
      ES.idEstadoCiudad,
      ES.idCiudad,
      ES.idEstado,
      C.Ciudad
    FROM estados_ciudades AS ES
      LEFT JOIN ciudades AS C ON (ES.idCiudad = C.idCiudad)
    WHERE ES.idEStado = $state_id
    ORDER BY C.Ciudad
    ASC
  ";

  $query_result = mysqli_query($mysqli, $query);

  $response .= '<option value="">' . $label . '</option>';

  while ($row = mysqli_fetch_array($query_result)) :
    $selected = $value == $row['idCiudad'] ? 'selected' : '';

    $response .= '<option ' . $selected . ' value="' . $row['idCiudad'] . '">' . $row['Ciudad'] . '</option>';
  endwhile;

  return $response;
}

function supplierTypesForSelect(
  $label = 'Todas',
  $value = ''
) {
  global $mysqli;

  $response = '';

  $query = "SELECT
      idTipoProveedor,
      TipoProveedor
    FROM tipo_proveedores
    ORDER BY TipoProveedor
    ASC
  ";

  $query_result = mysqli_query($mysqli, $query);

  $response .= '<option value="">' . $label . '</option>';

  while ($row = mysqli_fetch_array($query_result)) :
    $selected = $value == $row['idTipoProveedor'] ? 'selected' : '';

    $response .= '<option ' . $selected . ' value="' . $row['idTipoProveedor'] . '">' . $row['TipoProveedor'] . '</option>';
  endwhile;

  return $response;
}

function eventTypesForSelect(
  $label = 'Todas',
  $value = '',
  $mode  = ''
) {
  global $mysqli;

  $response = '';

  $query = "SELECT
      idTipoEvento,
      TipoEvento,
      slug
    FROM tipo_eventos
    ORDER BY idTipoEvento
    ASC
  ";

  $query_result = mysqli_query($mysqli, $query);

  $response .= '<option value="">' . $label . '</option>';

  while ($row = mysqli_fetch_array($query_result)) :
    $option_value = $mode   == 'label'        ? $row['TipoEvento']  : $row['idTipoEvento'];
    $selected     = $value  == $option_value  ? 'selected'          : '';

    $response .= '<option data-slug="' . $row['slug'] . '" ' . $selected . ' value="' . $option_value . '">' . $row['TipoEvento'] . '</option>';
  endwhile;

  return $response;
}

function businessPackagesForSelect(
  $label = 'Todas',
  $business_id,
  $value = ''
) {
  global $mysqli;

  $response = '';

  $query = "SELECT
      idPaquete,
      Paquete
    FROM paquetes_negocios
    WHERE
      idNegocio = $business_id AND
      Precio    > 0
  ";

  $query_result = mysqli_query($mysqli, $query);

  $response .= '<option value="">' . $label . '</option>';

  while ($row = mysqli_fetch_array($query_result)) :
    $selected = $value == $row['idPaquete'] ? 'selected' : '';

    $response .= '<option ' . $selected . ' value="' . $row['idPaquete'] . '">' . $row['Paquete'] . '</option>';
  endwhile;

  return $response;
}

function businessPackageEventTypesForSelect(
  $label = 'Todas',
  $package_id,
  $value = ''
) {
  global $mysqli;

  $response = '';

  $query = "SELECT
      CPTE.idTipoEvento,
      TE.TipoEvento
    FROM catalogo_paquete_tipos_eventos AS CPTE
      LEFT JOIN tipo_eventos AS TE ON (CPTE.idTipoEvento = TE.idTipoEvento)
    WHERE
      CPTE.idPaquete = $package_id
    ORDER BY CPTE.idTipoEvento
    ASC
  ";

  $query_result = mysqli_query($mysqli, $query);

  $response .= '<option value="">' . $label . '</option>';

  while ($row = mysqli_fetch_array($query_result)) :
    $selected = $value == $row['idTipoEvento'] ? 'selected' : '';

    $response .= '<option ' . $selected . ' value="' . $row['idTipoEvento'] . '">' . $row['TipoEvento'] . '</option>';
  endwhile;

  return $response;
}

/* ::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::
//-- GET SUPPLIER TYPE DATA AND EVENT TYPE DATA BY SLUG
:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::: */
function getEventDataBySlug(
  $event_type_slug = ''
) {
  global $mysqli;

  $query = "SELECT
      idTipoEvento,
      TipoEvento,
      Imagen
    FROM tipo_eventos
    WHERE slug = '$event_type_slug'
    LIMIT 1
  ";

  $query_result = mysqli_query($mysqli, $query);
  $num_rows     = mysqli_num_rows($query_result);

  if (!$num_rows) return false;

  $event_type_data = mysqli_fetch_array($query_result);

  return $event_type_data;
}

function getSupplierDataBySlug(
  $supplier_type_slug = ''
) {
  global $mysqli;

  $query = "SELECT
      idTipoProveedor,
      TipoProveedor
    FROM tipo_proveedores
    WHERE slug = '$supplier_type_slug'
    LIMIT 1
  ";

  $query_result = mysqli_query($mysqli, $query);
  $num_rows     = mysqli_num_rows($query_result);

  if (!$num_rows) return false;

  $supplier_type_data = mysqli_fetch_array($query_result);

  return $supplier_type_data;
}

function getSupplierTypeSlugById(
  $supplier_type_id
) {
  global $mysqli;

  if (cleanStr($supplier_type_id) == '') return null;

  $query = "SELECT slug FROM tipo_proveedores WHERE idTipoProveedor = $supplier_type_id";

  $query_result = mysqli_query($mysqli, $query);
  $num_rows     = mysqli_num_rows($query_result);

  if (!$num_rows) return false;

  $supplier_type_data = mysqli_fetch_array($query_result);
  $supplier_type_slug = $supplier_type_data['slug'];

  return $supplier_type_slug;
}

function getEventTypeSlugById(
  $event_type_id
) {
  global $mysqli;

  if (cleanStr($event_type_id) == '') return null;

  $query = "SELECT slug FROM tipo_eventos WHERE idTipoEvento = $event_type_id";

  $query_result = mysqli_query($mysqli, $query);
  $num_rows     = mysqli_num_rows($query_result);

  if (!$num_rows) return false;

  $event_type_data = mysqli_fetch_array($query_result);
  $event_type_slug = $event_type_data['slug'];

  return $event_type_slug;
}

/* ::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::
//-- FUNCTIONS FOR LISTING FILTER
:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::: */
function getServicesCheckbox(
  $default_value = [],
  $tag = ''
) {
  global $mysqli;

  $response = '';

  $query = "SELECT
      idServicio,
      Servicio
    FROM servicios
    ORDER BY idServicio
    ASC
  ";

  $query_result = mysqli_query($mysqli, $query);

  while ($row = mysqli_fetch_array($query_result)) :
    $item_id = $row['idServicio'];
    $item    = $row['Servicio'];

    $selected = in_array($item_id, $default_value) ? 'selected' : '';

    $input_class = $tag . 'services-checkbox';
    $input_name = $tag . 'services[]';
    $input_id = $tag . 'service-' . $item_id;

    $response .= '
      <div>
        <input id="' . $input_id . '"
          class="' . $input_class . '"
          name="' . $input_name . '"
          value="' . $item_id . '"
          type="checkbox"
          ' . $selected . '
          labelError="Selecciona los servicios de tu negocio"
        >

        <label for="' . $input_id . '">' . $item . '</label>
      </div>
    ';
  endwhile;

  return $response;
}

function getAmenitiesCheckbox(
  $default_value = [],
  $tag = ''
) {
  global $mysqli;

  $response = '';

  $query = "SELECT
      idAmenidad,
      Amenidad
    FROM amenidades
    ORDER BY idAmenidad
    ASC
  ";

  $query_result = mysqli_query($mysqli, $query);

  while ($row = mysqli_fetch_array($query_result)) :
    $item_id = $row['idAmenidad'];
    $item    = $row['Amenidad'];

    $selected = in_array($item_id, $default_value) ? 'selected' : '';

    $input_class = $tag . 'amenities-checkbox';
    $input_name = $tag . 'amenities[]';
    $input_id = $tag . 'amenity-' . $item_id;

    $response .= '
      <div>
        <input id="' . $input_id . '"
          class="' . $input_class . '"
          name="' . $input_name . '"
          value="' . $item_id . '"
          type="checkbox"
          ' . $selected . '
          labelError="Selecciona las amenidades de tu negocio"
        >

        <label for="' . $input_id . '">' . $item . '</label>
      </div>
    ';
  endwhile;

  return $response;
}

function getMaxBusinessPrice()
{
  global $mysqli;

  $max = 1000000;

  $query = "SELECT
      MAX(P.Precio) AS PrecioMaximo
    FROM paquetes_negocios AS P
      LEFT JOIN salones AS S ON (P.idNegocio = S.idSalon)
    LIMIT 1
  ";

  $query_result = mysqli_query($mysqli, $query);
  $num_rows     = mysqli_num_rows($query_result);

  if ($num_rows) {
    $data_max = mysqli_fetch_array($query_result);
    $max = $data_max['PrecioMaximo'];
  }

  return $max;
}

function getMaxBusinessCapacity()
{
  global $mysqli;

  $max = 1000;

  $query = "SELECT MAX(CapacidadMaxima) AS MaximaCapacidad FROM salones LIMIT 1";

  $query_result = mysqli_query($mysqli, $query);
  $num_rows     = mysqli_num_rows($query_result);

  if ($num_rows) {
    $data_capacity = mysqli_fetch_array($query_result);
    $max = $data_capacity['MaximaCapacidad'];
  }

  return $max;
}

/* ::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::
//-- BUSINESS DEFAULT IMAGE
:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::: */
function setBusinessImage(
  $img,
  $gallery = false
) {
  if (!$img) return BASE_URL . '/src/assets/images/500x500.png';

  $img_location = BASE_PATH . '/src/assets/images/listing/';
  $business_image = BASE_URL . '/src/assets/images/listing/';

  if (!$gallery) {
    $img_location   .= $img;
    $business_image .= $img;
  }

  if ($gallery) {
    $img_location   .= 'gallery/' . $img;
    $business_image .= 'gallery/' . $img;
  }

  $img_exist = realpath($img_location);

  if ($img_exist) return $business_image;
  if (!$img_exist) return BASE_URL . '/src/assets/images/500x500.png';
}

function getNumBusiness(
  $user_id
) {
  global $mysqli;

  $query = "SELECT COUNT(idSalon) AS Total FROM salones WHERE
      idUsuario = $user_id AND
      Status    = 'Activo'
    LIMIT 1
  ";

  $query_result = mysqli_query($mysqli, $query);

  $business_data  = mysqli_fetch_array($query_result);
  $num_business   = $business_data['Total'];

  return $num_business;
}

function getFirstBusinessId(
  $user_id
) {
  global $mysqli;

  $business_id = 0;

  $query_business = "SELECT
      idSalon,
      Salon
    FROM salones
    WHERE
      idUsuario = $user_id AND
      Status    = 'Activo'
    ORDER BY idSalon
    ASC LIMIT 1
  ";

  $query_business_result  = mysqli_query($mysqli, $query_business);
  $num_business           = mysqli_num_rows($query_business_result);

  if ($num_business) {
    $business_data  = mysqli_fetch_array($query_business_result);
    $business_id    = $business_data['idSalon'];

    $response = $business_id;
  }

  return $response;
}

/* ::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::
//-- BUSINESS
:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::: */
function increaseBusinessVisit(
  $business_id
) {
  global $mysqli;

  $query = "SELECT Visitas FROM salones WHERE idSalon = $business_id LIMIT 1";

  $query_result = mysqli_query($mysqli, $query);
  $num_rows     = mysqli_num_rows($query_result);

  if ($num_rows) :
    $business_data  = mysqli_fetch_array($query_result);
    $num_visits     = intval($business_data['Visitas']);
    $new_num_visits = $num_visits + 1;

    $query = "UPDATE salones SET Visitas = $new_num_visits WHERE idSalon = $business_id";
    $query_result = mysqli_query($mysqli, $query);

    if (!$query_result) return false;
    if ($query_result)  return true;
  endif;
}

function getBusinessDataBySlug(
  $business_slug
) {
  global $mysqli;

  $slug_estructure  = explode('-', $business_slug);

  $reference  = end($slug_estructure);
  $slug       = str_replace('-' . $reference, '', $business_slug);

  $query = "SELECT
      idSalon,
      Salon,
      Descripcion,
      Capacidad,
      CapacidadMaxima,
      Latitud,
      Longitud,
      Direccion,
      Telefono,
      Celular,
      Imagen,
      Logo,
      idEstado,
      idCiudad,
      MostrarCalendario,
      Facebook,
      Instagram
    FROM salones
    WHERE
      Referencia  = '$reference'  AND
      slug        = '$slug'       AND
      Status      = 'Activo'
    LIMIT 1
  ";

  $query_result = mysqli_query($mysqli, $query);
  $num_rows     = mysqli_num_rows($query_result);

  if ($num_rows == 0) return null;

  $business_data = mysqli_fetch_array($query_result);

  return $business_data;
}

function getBusinessDataById(
  $business_id
) {
  global $mysqli;

  $query = "SELECT
      S.idSalon,
      S.Salon,
      S.Descripcion,
      S.Capacidad,
      S.CapacidadMaxima,
      S.Latitud,
      S.Longitud,
      S.Direccion,
      S.Imagen,
      S.idEstado,
      S.idCiudad,
      S.slug,
      U.Correo,
      U.Usuario
    FROM salones AS S
      LEFT JOIN usuarios AS U ON (S.idUsuario = U.idUsuario)
    WHERE S.idSalon = $business_id
    LIMIT 1
  ";

  $query_result = mysqli_query($mysqli, $query);
  $num_rows     = mysqli_num_rows($query_result);

  if ($num_rows == 0) return null;

  $business_data = mysqli_fetch_array($query_result);

  return $business_data;
}

function getBusinessGallery(
  $business_id,
  $business_name = ''
) {
  global $mysqli;

  $response = '';
  $count    = 0;

  $query = "SELECT Imagen FROM galeria WHERE idSalon = $business_id";

  $query_result = mysqli_query($mysqli, $query);
  $num_rows     = mysqli_num_rows($query_result);

  if ($num_rows) :
    while ($row = mysqli_fetch_array($query_result)) :
      $image = setBusinessImage($row['Imagen'], true);

      $response .= '
        <div>
          <a target="_blank" href="' . $image . '">
            <img src="' . $image . '" alt="' . $business_name . '">
          </a>
        </div>
      ';

      $count++;
    endwhile;
  endif;

  return array(
    'gallery' => $response,
    'count'   => $count
  );
}

function getBusinessServices(
  $business_id
) {
  global $mysqli;

  $response = '';

  $query = "SELECT
      S.Servicio
    FROM catalogo_salon_servicios AS CS
     LEFT JOIN servicios AS S ON (CS.idServicio = S.idServicio)
    WHERE CS.idSalon = $business_id
  ";

  $query_result = mysqli_query($mysqli, $query);
  $num_rows     = mysqli_num_rows($query_result);

  if ($num_rows) :
    $response .= '<ul class="list-items">';
    while ($row = mysqli_fetch_array($query_result)) :
      $response .= '<li>' . $row['Servicio'] . '</li>';
    endwhile;
    $response .= '</ul>';
  endif;

  return $response;
}

function getBusinessAmenities(
  $business_id
) {
  global $mysqli;

  $response = '';

  $query = "SELECT
      A.Amenidad
    FROM catalogo_salon_amenidades AS CA
      LEFT JOIN amenidades AS A ON (CA.idAmenidad = A.idAmenidad)
    WHERE CA.idSalon = $business_id
  ";

  $query_result = mysqli_query($mysqli, $query);
  $num_rows     = mysqli_num_rows($query_result);

  if ($num_rows) :
    $response .= '<ul class="list-items">';
    while ($row = mysqli_fetch_array($query_result)) :
      $response .= '<li>' . $row['Amenidad'] . '</li>';
    endwhile;
    $response .= '</ul>';
  endif;

  return $response;
}

function getBusinessPackages(
  $business_id
) {
  global $mysqli;

  $query = "SELECT
      idPaquete,
      idNegocio,
      Paquete,
      Descripcion,
      Orientacion,
      Precio,
      MasContratado
    FROM paquetes_negocios
    WHERE
      idNegocio = $business_id AND
      Precio    > 0
  ";

  $query_result = mysqli_query($mysqli, $query);
  $num_rows     = mysqli_num_rows($query_result);

  if (!$num_rows) return false;

  if ($num_rows) :
    $business_packages = array();

    while ($package = mysqli_fetch_array($query_result)) :
      $categories             = getBusinessPackageCategories($package['idPaquete']);
      $package['categories']  = $categories;

      array_push($business_packages, $package);
    endwhile;

    return $business_packages;
  endif;
}

function getBusinessPackageCategories(
  $package_id
) {
  global $mysqli;

  $response = '';

  $query = "SELECT
      CP.idPaquete,
      CP.idTipoEvento,
      TE.TipoEvento
    FROM catalogo_paquete_tipos_eventos AS CP
      LEFT JOIN tipo_eventos AS TE ON (TE.idTipoEvento = CP.idTipoEvento)
    WHERE idPaquete = $package_id
  ";

  $query_result = mysqli_query($mysqli, $query);
  $num_rows     = mysqli_num_rows($query_result);

  if ($num_rows) :
    $count = 0;

    while ($row = mysqli_fetch_array($query_result)) :
      $response .= $count == 0 ? "$row[TipoEvento]" : " / $row[TipoEvento]";
      $count++;
    endwhile;
  endif;

  return $response;
}

function getBusinessDates(
  $business_id
) {
  global $mysqli;

  $dates = array();

  $query = "SELECT
      idCalendarioFecha,
      Fecha,
      DateStatus
    FROM calendario_fechas
    WHERE idNegocio = $business_id
  ";

  $query_result = mysqli_query($mysqli, $query);
  $num_rows     = mysqli_num_rows($query_result);

  if ($num_rows) :
    while ($row = mysqli_fetch_array($query_result)) :
      $date_status = '';

      if ($row['DateStatus'] == 'Libre')        $date_status = 'free';
      if ($row['DateStatus'] == 'Con espacios') $date_status = 'with-spaces';
      if ($row['DateStatus'] == 'Ocupado')      $date_status = 'occupied';

      $calendar_hours = getBusinessReservationHoursLabel(
        $business_id,
        $row['Fecha']
      );

      array_push($dates, array(
        'id'      => $row['idCalendarioFecha'],
        'date'    => $row['Fecha'],
        'status'  => $date_status,
        'hours'   => $date_status === 'with-spaces' ? $calendar_hours : ''
      ));
    endwhile;
  endif;

  return $dates;
}

function getBusinessReservationHoursLabel(
  $business_id,
  $date
) {
  global $mysqli;

  $response = '';

  $query = "SELECT
      idreservacion,
      DATE_FORMAT(HoraInicio, '%h:%i %p') AS HoraInicio,
      DATE_FORMAT(HoraFinal, '%h:%i %p')  AS HoraFinal
    FROM reservaciones
    WHERE
      idNegocio   = $business_id  AND
      Fecha       = '$date'       AND
      HoraInicio  <> ''
  ";

  $query_result = mysqli_query($mysqli, $query);
  $num_rows     = mysqli_num_rows($query_result);

  if ($num_rows > 0) :
    while ($row = mysqli_fetch_array($query_result)) :
      $response .= '* ' . $row['HoraInicio'] . ($row['HoraFinal'] ? ' - ' . $row['HoraFinal'] . '<br>' : '<br>');
    endwhile;
  endif;

  return $response;
}

function getBusinessDataByPackage(
  $package_id
) {
  global $mysqli;

  $query = "SELECT
      CPTE.idNegocio,
      S.Salon,
      S.idUsuario
    FROM catalogo_paquete_tipos_eventos AS CPTE
      LEFT JOIN salones AS S ON (CPTE.idNegocio = S.idSalon)
    WHERE
      CPTE.idPaquete = $package_id AND
      S.Status = 'Activo'
    LIMIT 1
  ";

  $query_result = mysqli_query($mysqli, $query);
  $num_rows     = mysqli_num_rows($query_result);

  if (!$num_rows) return false;

  $business_data  = mysqli_fetch_array($query_result);

  $business_id    = $business_data['idNegocio'];
  $business       = $business_data['Salon'];
  $supplier_id    = $business_data['idUsuario'];

  return array(
    'supplierId'  => $supplier_id,
    'businessId'  => $business_id,
    'business'    => $business
  );
}

function getBusinessDateStatus(
  $supplier_id,
  $business_id,
  $date
) {
  global $mysqli;

  $query = "SELECT
      DateStatus
    FROM calendario_fechas
    WHERE
      idUsuario = $supplier_id AND
      idNegocio = $business_id AND
      Fecha     = '$date'
    LIMIT 1
  ";

  $query_result = mysqli_query($mysqli, $query);
  $num_rows     = mysqli_num_rows($query_result);

  if (!$num_rows) return false;

  $date_data    = mysqli_fetch_array($query_result);
  $date_status  = $date_data['DateStatus'];

  return $date_status;
}

function getBusinessPackageItem(
  $counter,
  $close = true
) {
  $event_types = getEventTypesCheckbox([], 'package-' . $counter . '-');
  $btn_remove = $close ? '<span class="close btn-remove-package">&times;</span>' : '<span class="close"></span>';

  $response = '
    <div class="pure-u-1 pure-u-md-1-2" style="margin-bottom: 1rem;">
      <div class="card bordered">
        <div class="card-header">
          <h2 class="card-title"></h2>
          ' . $btn_remove . '
        </div>

        <div class="pure-g">
          <div class="pure-u-1">
            <div class="form-group">
              <label for="packageName-' . $counter . '">Nombre del paquete<span>*</span></label>
              <input id="packageName-' . $counter . '" name="packageName-' . $counter . '" type="text" validate>
            </div>
          </div>
        </div>

        <div class="pure-g">
          <div class="pure-u-1">
            <div class="form-group" style="padding: 0;">
              <label for="packagePrice">Precio y modalidad de tu paquete<span>*</span></label>
              <input type="hidden">
            </div>
          </div>

          <div class="pure-u-1">
            <div class="pure-g">
              <div class="pure-u-1-2">
                <div class="form-group">
                  <input id="packagePrice-' . $counter . '" class="number-input" name="packagePrice-' . $counter . '" type="number" validate>
                </div>
              </div>

              <div class="pure-u-1-2">
                <div class="radiobutton-group column small">
                  <div>
                    <input id="modality-per-person-' . $counter . '" name="modality-' . $counter . '" value="Por persona" type="radio" labelError="Selecciona la modalidad de tu paquete" validate>
                    <label for="modality-per-person-' . $counter . '">Por persona</label>
                  </div>

                  <div>
                    <input id="modality-per-event-' . $counter . '" name="modality-' . $counter . '" value="Por evento" type="radio" labelError="Selecciona la modalidad de tu paquete" validate>
                    <label for="modality-per-event-' . $counter . '">Por evento</label>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="pure-g" style="margin-bottom: 1rem;">
          <div class="pure-u-1">
            <div class="form-group">
              <label for="">Tipos de evento que tu paquete ofrece<span>*</span></label>
              <input type="hidden">
            </div>
          </div>

          <div class="pure-u-1">
            <div class="checkbox-group small">
              ' . $event_types . '
            </div>
          </div>
        </div>

        <div class="pure-g">
          <div class="pure-u-1">
            <div class="form-group">
              <label for="step-packages-editor-' . $counter . '">Descripción<span>*</span></label>
              <textarea id="step-packages-editor-' . $counter . '" name="packageDescription-' . $counter . '" rows="5" validate></textarea>
            </div>
          </div>
        </div>

        <div class="pure-g">
          <div class="pure-u-1">
            <div class="checkbox-group">
              <div>
                <input
                  id="moreContracted-' . $counter . '"
                  name="moreContracted-' . $counter . '"
                  value="Si"
                  type="checkbox"
                >

                <label for="moreContracted-' . $counter . '">Marcar como el mas contratado</label>
              </div>
            </div>
          </div>
        </div>

        <input name="packageCounter[]" value="' . $counter . '" type="hidden">
      </div>
    </div>
  ';

  return $response;
}

function getBusinessEventTypes(
  $business_id
) {
  global $mysqli;

  $event_types = array();

  $query = "SELECT
      CTE.idTipoEvento,
      TE.slug,
      TE.TipoEvento
    FROM catalogo_salon_tipos_eventos AS CTE
      LEFT JOIN tipo_eventos AS TE ON (CTE.idTipoEvento = TE.idTipoEvento)
    WHERE CTE.idSalon = $business_id
    ORDER BY CTE.idTipoEvento
    ASC
    LIMIT 1
  ";

  $query_result = mysqli_query($mysqli, $query);
  $num_rows     = mysqli_num_rows($query_result);

  if ($num_rows > 0) :
    while ($event_type = mysqli_fetch_array($query_result)) :
      array_push($event_types, $event_type);
    endwhile;
  endif;

  return $event_types;
}

/* ::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::
//-- QUOTES
:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::: */
function getQuoteFolio(
  $business_id,
  $mark = 'M-'
) {
  global $mysqli;
  $today_year = date('Y');

  $query_get_folio = "SELECT
      Folio
    FROM cotizaciones
    WHERE
      YEAR(FechaCreacion) = $today_year AND
      idNegocio = $business_id
    ORDER BY Folio
    DESC
    LIMIT 1
  ";

  $query_get_folio_result = mysqli_query($mysqli, $query_get_folio);
  $query_get_folio_num_rows = mysqli_num_rows($query_get_folio_result);

  if (!$query_get_folio_num_rows) return $mark . '0001/' . $today_year;

  if ($query_get_folio_num_rows) {
    $quote_data = mysqli_fetch_array($query_get_folio_result);
    $folio = $quote_data['Folio'];

    $folio = str_replace($mark, '', $folio);
    $folio = str_replace('/' . $today_year, '', $folio);
    $folio = ltrim($folio, '0');

    $new_num = intval($folio) + 1;
    $num_folio_length = strlen($new_num);
    $new_num_folio = '';

    if ($num_folio_length === 1) $new_num_folio = '000' . $new_num;
    if ($num_folio_length === 2) $new_num_folio = '00' . $new_num;
    if ($num_folio_length === 3) $new_num_folio = '0' . $new_num;
    if ($num_folio_length === 4) $new_num_folio = $new_num;

    $new_folio = $mark . $new_num_folio . '/' . $today_year;

    return $new_folio;
  }
}

function sendQuoteNotification(
  $user_id,
  $customer_name,
  $business
) {
  global $mysqli;

  $query = "SELECT
      idToken,
      idUsuario,
      Token
    FROM app_tokens
    WHERE idUsuario = $user_id
  ";

  $query_result = mysqli_query($mysqli, $query);
  $num_rows     = mysqli_num_rows($query_result);

  if ($num_rows) :
    $title = "Nueva cotización";
    $description = "$customer_name ha solicitado una cotización en $business";

    while ($row = mysqli_fetch_array($query_result)) :
      sendDeviceNotification(
        $row['Token'],
        $title,
        $description
      );
    endwhile;
  endif;
}

/* ::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::
//-- TIPS
:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::: */
function getLastTip()
{
  global $mysqli;

  $query = "SELECT
      idTip,
      Tip,
      DescCorta,
      Descripcion,
      Imagen,
      Fecha,
      slug
    FROM tips
    WHERE Eliminado = 'No'
    ORDER BY idTip
    DESC
    LIMIT 1
  ";

  $query_result = mysqli_query($mysqli, $query);
  $num_rows     = mysqli_num_rows($query_result);

  if (!$num_rows) return false;

  $tip_data = mysqli_fetch_array($query_result);

  return $tip_data;
}

function getTipsForSlider()
{
  global $mysqli;

  $query = "SELECT
      idTip,
      Tip,
      DescCorta,
      Descripcion,
      Imagen,
      Fecha,
      slug
    FROM tips
    WHERE Eliminado = 'No'
    ORDER BY idTip
    DESC
  ";

  $query_result = mysqli_query($mysqli, $query);
  $num_rows     = mysqli_num_rows($query_result);

  if (!$num_rows) return false;

  $tips = array();

  while ($row = mysqli_fetch_array($query_result)) :
    $gallery = getTipGallery($row['idTip'], $row['Tip']);
    $row['gallery'] = $gallery;

    array_push($tips, $row);
  endwhile;

  return $tips;
}

function getTipGallery(
  $tip_id,
  $tip_title
) {
  global $mysqli;

  $response = '';

  $query = "SELECT Imagen FROM tips_galeria WHERE idTip = $tip_id LIMIT 2";

  $query_result = mysqli_query($mysqli, $query);
  $num_rows     = mysqli_num_rows($query_result);

  if ($num_rows) :
    while ($row = mysqli_fetch_array($query_result)) :
      $image = setTipImage($row['Imagen'], true);
      $response .= '<img src="' . $image . '" alt="' . $tip_title . '">';
    endwhile;
  endif;

  return $response;
}

function setTipImage(
  $img = 'image.jpg',
  $gallery = false
) {
  if (!$img) return BASE_URL . '/src/assets/images/500x500.png';

  $img_location = BASE_PATH . '/src/assets/images/tips/';
  $tip_image = BASE_URL . '/src/assets/images/tips/';

  if (!$gallery) {
    $img_location .= $img;
    $tip_image    .= $img;
  }

  if ($gallery) {
    $img_location .= 'gallery/' . $img;
    $tip_image    .= 'gallery/' . $img;
  }

  $img_exist = realpath($img_location);

  if ($img_exist) return $tip_image;
  if (!$img_exist) return BASE_URL . '/src/assets/images/500x500.png';
}

function getTipDataBySlug(
  $tip_slug
) {
  global $mysqli;

  if (!$tip_slug) return false;

  $slug_estructure  = explode('-', $tip_slug);

  $reference  = end($slug_estructure);
  $slug       = str_replace('-' . $reference, '', $tip_slug);

  $query = "SELECT
      idTip,
      Tip,
      DescCorta,
      Descripcion,
      Imagen
    FROM tips
    WHERE
      Referencia = '$reference'  AND
      slug       = '$slug'       AND
      Eliminado  = 'No'
    LIMIT 1
  ";

  $query_result = mysqli_query($mysqli, $query);
  $num_rows     = mysqli_num_rows($query_result);

  if (!$num_rows) return false;

  $data = mysqli_fetch_array($query_result);

  $gallery          = getTipGallery($data['idTip'], $data['Tip']);
  $data['gallery']  = $gallery;

  return $data;
}

function getOtherTips()
{
  global $mysqli;

  $query = "SELECT
      idTip,
      Tip,
      DescCorta,
      Imagen,
      Referencia,
      Slug
    FROM tips
    ORDER BY RAND()
    LIMIT 3
  ";

  $query_result = mysqli_query($mysqli, $query);
  $num_rows     = mysqli_num_rows($query_result);

  if (!$num_rows) return false;

  $tips = array();

  while ($row = mysqli_fetch_array($query_result)) :
    array_push($tips, $row);
  endwhile;

  return $tips;
}

/* ::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::
//-- RECENT EVENTS
:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::: */
function getRecentEventDataBySlug(
  $recent_event_slug
) {
  global $mysqli;

  if (!$recent_event_slug) return false;

  $slug_estructure  = explode('-', $recent_event_slug);

  $reference  = end($slug_estructure);
  $slug       = str_replace('-' . $reference, '', $recent_event_slug);

  $query = "SELECT
      ER.idEvento,
      ER.Evento,
      ER.DescCorta,
      ER.Descripcion,
      ER.Imagen,
      ER.Fecha,
      ER.slug,
      S.Salon,
      TP.TipoProveedor,
      E.Estado,
      C.Ciudad
    FROM eventos_recientes AS ER
      LEFT JOIN salones           AS S  ON (ER.idSalon        = S.idSalon)
      LEFT JOIN tipo_proveedores  AS TP ON (S.idTipoProveedor = TP.idTipoProveedor)
      LEFT JOIN estados           AS E  ON (S.idEstado        = E.idEstado)
      LEFT JOIN ciudades          AS C  ON (S.idCiudad        = C.idCiudad)
    WHERE
      ER.Referencia = '$reference'  AND
      ER.slug       = '$slug'       AND
      ER.Eliminado  = 'No'
    LIMIT 1
  ";

  $query_result = mysqli_query($mysqli, $query);
  $num_rows     = mysqli_num_rows($query_result);

  if (!$num_rows) return false;

  $data = mysqli_fetch_array($query_result);

  $gallery          = getRecentEventGallery($data['idEvento'], $data['Evento']);
  $data['gallery']  = $gallery;

  return $data;
}

function getOtherRecentEvents()
{
  global $mysqli;

  $query = "SELECT
      idEvento,
      Evento,
      DescCorta,
      Imagen,
      Referencia,
      slug
    FROM eventos_recientes
    ORDER BY RAND()
    LIMIT 3
  ";

  $query_result = mysqli_query($mysqli, $query);
  $num_rows     = mysqli_num_rows($query_result);

  if (!$num_rows) return false;

  $recent_events = array();

  while ($row = mysqli_fetch_array($query_result)) :
    array_push($recent_events, $row);
  endwhile;

  return $recent_events;
}

/* ::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::
//-- INVITATIONS
:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::: */
function getInvitationDataBySlug(
  $invitation_slug
) {
  global $mysqli;
  global $_SESSION;

  $user_id          = $_SESSION['session_user_id'];
  $slug_estructure  = explode('-', $invitation_slug);

  $reference  = $slug_estructure[0];
  $slug       = str_replace($reference . '-', '', $invitation_slug);


  $query = "SELECT
      idInvitacion,
      idUsuario,
      NombrePersona,
      NombreEvento,
      Telefono,
      Frase,
      TipoInvitacion,
      ColorPrincipal,
      ColorSecundario,
      CRLugar,
      DATE_FORMAT(CRFecha, '%d/%m/%Y %H:%i %p') AS CRFecha,
      CRDireccion,
      CRLatitud,
      CRLongitud,
      CRImagen,
      RLugar,
      DATE_FORMAT(RFecha, '%d/%m/%Y %H:%i %p') AS RFecha,
      RDireccion,
      RLatitud,
      RLongitud,
      RImagen,
      ImagenIndividual,
      ImagenFamiliar,
      Plantilla,
      Slug
    FROM invitaciones_digitales
    WHERE
      Slug        = '$slug'       AND
      Referencia  = '$reference'  AND
      idUsuario   = $user_id
    LIMIT 1
  ";

  $query_result = mysqli_query($mysqli, $query);
  $num_rows     = mysqli_num_rows($query_result);

  if (!$num_rows) return false;

  $invitation_data = mysqli_fetch_array($query_result);

  return $invitation_data;
}

function getInvitationDataForTemplateBySlug(
  $invitation_slug,
  $template
) {
  global $mysqli;
  global $_SESSION;

  $user_id          = $_SESSION['session_user_id'];
  $slug_estructure  = explode('-', $invitation_slug);

  $reference  = $slug_estructure[0];
  $slug       = str_replace($reference . '-', '', $invitation_slug);


  $query = "SELECT
      idInvitacion,
      idUsuario,
      NombrePersona,
      NombreEvento,
      Telefono,
      Frase,
      TipoInvitacion,
      ColorPrincipal,
      ColorSecundario,
      CRLugar,
      DATE_FORMAT(CRFecha, '%d/%m/%Y %H:%i %p') AS CRFecha,
      CRFecha AS CRFechaWithOutFormat,
      CRDireccion,
      CRLatitud,
      CRLongitud,
      CRImagen,
      RLugar,
      DATE_FORMAT(RFecha, '%d/%m/%Y %H:%i %p') AS RFecha,
      RFecha AS RFechaWithOutFormat,
      RDireccion,
      RLatitud,
      RLongitud,
      RImagen,
      ImagenIndividual,
      ImagenFamiliar,
      Plantilla,
      Slug
    FROM invitaciones_digitales
    WHERE
      Slug        = '$slug'       AND
      Referencia  = '$reference'  AND
      Plantilla   = '$template'
    LIMIT 1
  ";

  $query_result = mysqli_query($mysqli, $query);
  $num_rows     = mysqli_num_rows($query_result);

  if (!$num_rows) return false;

  $invitation_data = mysqli_fetch_array($query_result);

  return $invitation_data;
}

function getInvitationGalleryById(
  $invitation_id
) {
  global $mysqli;

  $gallery = array();

  $query = "SELECT
      idGaleria,
      idInvitacion,
      Imagen
    FROM galeria_de_invitaciones_digitales
    WHERE idInvitacion = $invitation_id
  ";

  $query_result = mysqli_query($mysqli, $query);
  $num_rows     = mysqli_num_rows($query_result);

  if ($num_rows) :
    while ($row = mysqli_fetch_array($query_result)) :
      array_push($gallery, array(
        'imageId'   => $row['idGaleria'],
        'imageSrc'  => BASE_URL . '/src/assets/images/invitations/gallery/' . $row['Imagen'],
        'imageName' => $row['Imagen']
      ));
    endwhile;
  endif;

  return $gallery;
}

function getInvitationGalleryIds(
  $invitation_id
) {
  global $mysqli;

  $gallery = array();

  $query = "SELECT
      idGaleria,
      idInvitacion,
      Imagen
    FROM galeria_de_invitaciones_digitales
    WHERE idInvitacion = $invitation_id
  ";

  $query_result = mysqli_query($mysqli, $query);
  $num_rows     = mysqli_num_rows($query_result);

  if ($num_rows) :
    while ($row = mysqli_fetch_array($query_result)) :
      array_push($gallery, $row['idGaleria']);
    endwhile;
  endif;

  return $gallery;
}

function getInvitationImageNames(
  $invitation_id
) {
  global $mysqli;

  $query = "SELECT
      CRImagen,
      RImagen,
      ImagenIndividual,
      ImagenFamiliar
    FROM invitaciones_digitales
    WHERE idInvitacion = $invitation_id
    LIMIT 1
  ";

  $query_result = mysqli_query($mysqli, $query);
  $num_rows     = mysqli_num_rows($query_result);

  if (!$num_rows) return false;

  $invitation_data = mysqli_fetch_array($query_result);

  return $invitation_data;
}

function deleteInvitationImageGallery(
  $original_image_gallery = array(),
  $new_image_gallery = array()
) {
  global $mysqli;

  $array_diff = array();

  if (!$new_image_gallery)  $array_diff = $original_image_gallery;
  if ($new_image_gallery)   $array_diff = array_diff($original_image_gallery, $new_image_gallery);

  foreach ($array_diff as $key => $value) :
    $image_id = $value;

    $query = "SELECT Imagen FROM galeria_de_invitaciones_digitales WHERE idGaleria = $image_id LIMIT 1";

    $query_result = mysqli_query($mysqli, $query);
    $num_rows     = mysqli_num_rows($query_result);

    if ($num_rows) :
      $image_data = mysqli_fetch_array($query_result);
      $image_name = $image_data['Imagen'];

      $file_location = INVITATIONS_GALLERY_FOLDER . $image_name;

      $delete_file = deleteFile($file_location);

      if ($delete_file == 'deleted') :
        $query_delete = "DELETE FROM galeria_de_invitaciones_digitales WHERE idGaleria = $image_id";
        mysqli_query($mysqli, $query_delete);
      endif;
    endif;
  endforeach;
}

function setInvitationImage(
  $img,
  $gallery = false
) {
  if (!$img) return BASE_URL . '/src/assets/images/500x500.png';

  $img_location = BASE_PATH . '/src/assets/images/invitations/';
  $invitation_image = BASE_URL . '/src/assets/images/invitations/';

  if (!$gallery) {
    $img_location   .= $img;
    $invitation_image .= $img;
  }

  if ($gallery) {
    $img_location   .= 'gallery/' . $img;
    $invitation_image .= 'gallery/' . $img;
  }

  $img_exist = realpath($img_location);

  if ($img_exist) return $invitation_image;
  if (!$img_exist) return BASE_URL . '/src/assets/images/500x500.png';
}

/* ::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::
//-- ADD BUSINESS
:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::: */

function getSupplierTypesRadioButtons(
  $default_value = ''
) {
  global $mysqli;

  $response = '';

  $query = "SELECT
      idTipoProveedor AS item_id,
      TipoProveedor   AS item,
      eventos
    FROM tipo_proveedores
    ORDER BY idTipoProveedor
    ASC
  ";

  $query_result = mysqli_query($mysqli, $query);

  while ($row = mysqli_fetch_array($query_result)) :
    $item_id = $row['item_id'];
    $item    = $row['item'];

    $selected = $item_id == $default_value ? 'checked' : '';

    $response .= '
      <div>
        <input id="supplier-type-' . $item_id . '" data-events="[' . $row['eventos'] . ']" class="supplier-type" name="supplierType" value="' . $item_id . '" type="radio" ' . $selected . ' labelError="Selecciona el tipo de proveedor" validate>
        <label for="supplier-type-' . $item_id . '">' . $item . '</label>
      </div>
    ';
  endwhile;

  return $response;
}

function getEventTypesArray()
{
  global $mysqli;

  $event_types = array();

  $query = "SELECT
      idTipoEvento,
      TipoEvento
    FROM tipo_eventos
    ORDER BY idTipoEvento
    ASC
  ";

  $query_result = mysqli_query($mysqli, $query);

  while ($row = mysqli_fetch_array($query_result)) :
    array_push($event_types, array(
      'eventTypeId' => $row['idTipoEvento'],
      'eventType'   => $row['TipoEvento']
    ));
  endwhile;

  return $event_types;
}

function getEventTypesCheckboxBySupplierTypeEvents(
  $supplier_type_events,
  $default_value = [],
  $tag = ''
) {
  global $mysqli;

  $response = '';

  $query = "SELECT
      idTipoEvento  AS item_id,
      TipoEvento    AS item
    FROM tipo_eventos
    WHERE idTipoEvento LIKE '%$supplier_type_events%'
    ORDER BY idTipoEvento
    ASC
  ";

  $query_result = mysqli_query($mysqli, $query);

  while ($row = mysqli_fetch_array($query_result)) :
    $item_id = $row['item_id'];
    $item    = $row['item'];

    $selected = in_array($item_id, $default_value) ? 'selected' : '';

    $input_class = $tag . 'eventType-checkbox';
    $input_name = $tag . 'eventType[]';
    $input_id = $tag . 'event-type-' . $item_id;

    $response .= '
      <div>
        <input id="' . $input_id . '"
          class="' . $input_class . '"
          name="' . $input_name . '"
          value="' . $item_id . '"
          type="checkbox"
          ' . $selected . '
          labelError="Selecciona el tipo de evento"
          validate
        >

        <label for="' . $input_id . '">' . $item . '</label>
      </div>
    ';
  endwhile;

  return $response;
}

function getEventTypesCheckbox(
  $default_value = [],
  $tag = ''
) {
  global $mysqli;

  $response = '';

  $query = "SELECT
      idTipoEvento  AS item_id,
      TipoEvento    AS item
    FROM tipo_eventos
    ORDER BY idTipoEvento
    ASC
  ";

  $query_result = mysqli_query($mysqli, $query);

  while ($row = mysqli_fetch_array($query_result)) :
    $item_id = $row['item_id'];
    $item    = $row['item'];

    $selected = in_array($item_id, $default_value) ? 'selected' : '';

    $input_class = $tag . 'eventType-checkbox';
    $input_name = $tag . 'eventType[]';
    $input_id = $tag . 'event-type-' . $item_id;

    $response .= '
      <div>
        <input id="' . $input_id . '"
          class="' . $input_class . '"
          name="' . $input_name . '"
          value="' . $item_id . '"
          type="checkbox"
          ' . $selected . '
          labelError="Selecciona el tipo de evento"
          validate
        >

        <label for="' . $input_id . '">' . $item . '</label>
      </div>
    ';
  endwhile;

  return $response;
}

function getSupplierTypesForAutocomplete(
  $search_term = '',
  $event_type_id = ''
) {
  global $mysqli;

  $response = '';
  $supplier_types = array();

  $search_by_term = $search_term != '' ? "TipoProveedor LIKE _utf8'%$search_term%' collate utf8_unicode_ci" : "1=1";
  $search_by_event_type_id = $event_type_id != '' ? "eventos LIKE _utf8'%$search_term%' collate utf8_unicode_ci" : "1=1";

  $query = "SELECT
      idTipoProveedor,
      TipoProveedor,
      slug,
      Imagen
    FROM tipo_proveedores
    WHERE 
      ($search_by_term) AND
      ($search_by_event_type_id)
    ORDER BY idTipoProveedor
    ASC
  ";

  $query_result = mysqli_query($mysqli, $query);
  $num_rows     = mysqli_num_rows($query_result);

  if ($num_rows > 0) :
    while ($row = mysqli_fetch_array($query_result)) :
      $response .= '
        <a class="autocomplete-supplier-type-item" data-value="' . $row['TipoProveedor'] . '" data-slug="' . $row['slug'] . '" href="javascript:void(0)">
          <img class="img-autocomplete" src="' . BASE_URL . '/src/assets/images/suppliertypes/' . $row['Imagen'] . '.png">
          <img class="img-autocomplete-hover" src="' . BASE_URL . '/src/assets/images/suppliertypes/' . $row['Imagen'] . 'Dorado.png">

          <div>
            ' . $row['TipoProveedor'] . '
          </div>
        </a>
      ';

      array_push($supplier_types, array(
        'value' => $row['TipoProveedor'],
        'label' => $row['TipoProveedor'],
        'image' => BASE_URL . '/src/assets/images/suppliertypes/' . $row['Imagen'] . 'Dorado.png',
        'slug'  => $row['slug'],
        'type' =>  'header'
      ));
    endwhile;
  endif;

  if ($search_term != '') $response = $supplier_types;

  return $response;
}

function getSupplierTypes(
  $event_type_id
) {
  global $mysqli;

  $response = array();

  $query = "SELECT
      idTipoProveedor,
      TipoProveedor,
      slug,
      Imagen
    FROM tipo_proveedores
    WHERE eventos LIKE '%$event_type_id%'
    ORDER BY idTipoProveedor
    ASC
  ";

  $query_result = mysqli_query($mysqli, $query);
  $num_rows     = mysqli_num_rows($query_result);

  if ($num_rows > 0) :
    while ($row = mysqli_fetch_array($query_result)) :
      array_push($response, array(
        'value' => $row['TipoProveedor'],
        'label' => $row['TipoProveedor'],
        'image' => $row['Imagen'],
        'slug'  => $row['slug']
      ));
    endwhile;
  endif;

  return $response;
}

function getFinalUserDataById(
  $user_id
) {
  global $mysqli;

  $query = "SELECT
      idUsuario,
      Usuario,
      Correo,
      Telefono,
      Celular,
      Nivel,
      Username,
      Password,
      Pais,
      idEstado,
      AccessType
    FROM usuarios
    WHERE
      idUsuario = $user_id AND
      Status    = 'Activo'
    LIMIT 1
  ";

  $query_result = mysqli_query($mysqli, $query);
  $num_rows     = mysqli_num_rows($query_result);

  if ($num_rows == 0) return false;

  if ($num_rows) :
    $user_data = mysqli_fetch_array($query_result);
    return $user_data;
  endif;
}

/* function createReferenceForBusinessSlug(
  $business_id
) {
  global $mysqli;

  $query = "SELECT
      S.idSalon,
      S.idUsuario,
      U.Usuario
    FROM salones AS S
      LEFT JOIN usuarios AS U ON (S.idUsuario = U.idUsuario)
    WHERE S.idSalon = $business_id
    LIMIT 1
  ";

  $query_result = mysqli_query($mysqli, $query);
  $num_rows     = mysqli_num_rows($query_result);

  if ($num_rows > 0) :
    $user_data        = mysqli_fetch_array($query_result);
    $full_name        = createSlug($user_data['Usuario']);
    $full_name        = explode('-', $full_name);
    $user_name        = $full_name[0];

    $first_character  = $user_name[0];
    $last_character   = $user_name[strlen($user_name) - 1];

    $reference        = $first_character . $last_character;

    return $reference;
  endif;
} */

# :::::::::::::::
function getGridColsForSupplierTypes(
  $supplier_types
) {
  $grid_cols = "";

  $num_supplier_types = count($supplier_types);

  if ($num_supplier_types == 9) $grid_cols = "1fr 1fr 1fr 1fr 1fr 1fr 1fr 1fr 1fr";

  if ($num_supplier_types < 9) :
    foreach ($supplier_types as $key => $value) :
      $grid_cols .= "1fr ";
    endforeach;
  endif;

  if ($num_supplier_types > 9) :
    $new_num_cols = 0;

    $number_type = $num_supplier_types % 2 == 0 ? 'par' : 'impar';

    if ($number_type === 'par') $new_num_cols = $num_supplier_types / 2;
    if ($number_type === 'impar') $new_num_cols = $num_supplier_types / 3;

    for ($i = 0; $i < $new_num_cols; $i++) :
      $grid_cols .= "1fr ";
    endfor;
  endif;

  return $grid_cols;
}

/* FINAL USER DASHBOATD :::::::::::::::::::::::::::::::::::::::::::::::.. */
function getFinalUserQuotes(
  $page_quotes = 1
) {
  global $mysqli;
  global $_SESSION;

  $user_id = $_SESSION['session_user_id'];

  $page       = cleanStr($page_quotes);
  $per_page   =  12;
  $start_rows = ($page - 1) * $per_page;
  $stop_rows  = $per_page;

  $query = "SELECT
      C.idCotizacion,
      C.Folio,
      C.idNegocio,
      C.idPaquete,
      C.NombreCompleto,
      C.Email,
      C.Telefono,
      C.FechaSolicitada,
      C.FechaCreacion,
      C.Status,
      S.Salon,
      PN.Paquete
    FROM cotizaciones AS C
      LEFT JOIN salones           AS S  ON (C.idNegocio = S.idSalon)
      LEFT JOIN paquetes_negocios AS PN ON (C.idPaquete = PN.idPaquete)
    WHERE idUsuarioFinal = $user_id
    ORDER BY C.idCotizacion
    DESC
    LIMIT $start_rows, $stop_rows
  ";

  $query_result = mysqli_query($mysqli, $query);
  $num_rows     = mysqli_num_rows($query_result);

  if (!$num_rows) return null;

  return $query_result;
}

function decryptUserCookie(
  $cookie
) {
  $decrypt_cookie   = decrypt($cookie, MYSQLI_PASSWORD_SECRET);
  $user_id          = '';
  $reference_length = 16;
  $chars            = str_split($decrypt_cookie);

  foreach ($chars as $key => $value) :
    if (($key + 1) > $reference_length) :
      $user_id .= $value;
    endif;
  endforeach;

  return $user_id;
}

function verifyUserSession()
{
  global $mysqli;
  global $_SESSION;
  global $_COOKIE;

  $user_id      = $_SESSION['session_user_id'];
  $cookie_name  = 'MLSESSCOOID';
  $cookie       = $_COOKIE[$cookie_name];

  if ($user_id && !$cookie) :
    $query = "SELECT
        Usuario
      FROM usuarios
      WHERE idUsuario = $user_id
      LIMIT 1
    ";

    $query_result     = mysqli_query($mysqli, $query);
    $user_data        = mysqli_fetch_array($query_result);
    $full_name        = createSlug($user_data['Usuario']);
    $full_name        = explode('-', $full_name);
    $user_name        = $full_name[0];

    $first_character  = $user_name[0];
    $last_character   = $user_name[strlen($user_name) - 1];
    $today_date       = date('YmdHis');

    $reference        = $first_character . $last_character . $today_date . $user_id;
    $reference        = encrypt($reference, MYSQLI_PASSWORD_SECRET);

    setcookie($cookie_name, $reference, time() + (60 * 60 * 24 * 365));
  endif;

  if (!$user_id && $cookie) :
    $user_id    = decryptUserCookie($cookie);
    $user_data  = getUserData($user_id);

    if ($user_data['Nivel'] === 'Usuario Final') :
      $_SESSION['session_user_id']      = $user_id;
      $_SESSION['session_user_name']    = $user_data['Usuario'];
      $_SESSION['session_user_level']   = $user_data['Nivel'];
      $_SESSION['session_user_email']   = $user_data['Correo'];
      $_SESSION['session_user_status']  = $user_data['Status'];
    endif;

    if ($user_data['Nivel'] === 'Usuario') :
      $id_negocio   = $user_data['idNegocio'];
      $plan         = $user_data['Plan'];
      $user_level   = $user_data['Nivel'];
      $user_name    = $user_data['Usuario'];
      $pertenece_a  = $user_data['PerteneceA'];

      $session_id = ($pertenece_a != NUll && $pertenece_a != '') ? $pertenece_a : $user_id;
      $is_admin_supplier = !$pertenece_a ? 'Si' : 'No';

      $_SESSION['session_user_id']                = $session_id;
      $_SESSION['session_user_name']              = $user_name;
      $_SESSION['session_user_level']             = $user_level;
      $_SESSION['session_user_is_admin_supplier'] = $is_admin_supplier;
      $_SESSION['Plan']                           = $plan;

      if ($pertenece_a) $_SESSION['session_user_parent']      = $pertenece_a;
      if ($pertenece_a) $_SESSION['session_user_children_id'] = $user_id;
      if ($pertenece_a) $_SESSION['session_business_id']      = $id_negocio;

      if (!$id_negocio) :
        $query = "SELECT idSalon from salones WHERE idUsuario = $session_id AND Status = 'Activo' LIMIT 1";
        $query_result = mysqli_query($mysqli, $query);
        $data_negocio = mysqli_fetch_array($query_result);

        $id_negocio = $data_negocio['idSalon'];
        $_SESSION['session_business_id'] = $id_negocio;
      endif;
    endif;
  endif;
}

function getUserData(
  $user_id
) {
  global $mysqli;

  $query = "SELECT
      idUsuario,
      idNegocio,
      Plan,
      Usuario,
      Username,
      Password,
      Correo,
      Nivel,
      Status,
      PerteneceA
    FROM usuarios
    WHERE idUsuario = $user_id
    LIMIT 1
  ";

  $query_result = mysqli_query($mysqli, $query);
  $user_data    = mysqli_fetch_array($query_result);

  return $user_data;
}

function createReferenceForBusinessSlug(
  $business_id
) {
  global $mysqli;

  $query = "SELECT
      S.idSalon,
      S.idUsuario,
      U.Usuario
    FROM salones AS S
      LEFT JOIN usuarios AS U ON (S.idUsuario = U.idUsuario)
    WHERE S.idSalon = $business_id
    LIMIT 1
  ";

  $query_result = mysqli_query($mysqli, $query);
  $num_rows     = mysqli_num_rows($query_result);

  if ($num_rows > 0) :
    $user_data        = mysqli_fetch_array($query_result);
    $full_name        = createSlug($user_data['Usuario']);
    $full_name        = explode('-', $full_name);
    $user_name        = $full_name[0];
    $user_id          = $user_data['idUsuario'];

    $first_character  = $user_name[0];
    $last_character   = $user_name[strlen($user_name) - 1];

    $reference        = $first_character . $last_character . $user_id[0];

    return $reference;
  endif;
}

function get_ocurence($chaine, $rechercher)
{
  $lastPos = 0;
  $positions = array();
  while (($lastPos = strpos($chaine, $rechercher, $lastPos)) !== false) {
    $positions[] = $lastPos;
    $lastPos = $lastPos + strlen($rechercher);
  }
  return $positions;
}

function checkIfHaveHttps(
  $link
) {
  $have_http = get_ocurence($link, 'http');

  if (!count($have_http)) return 'https://' . $link;
  if (count($have_http)) return $link;
}
