<?php
include '../lib/public-session.php';

$search_term    = cleanStr($_GET['term']);
$event_type_id  = !isset($_GET['eventTypeId']) ? cleanStr($_GET['eventTypeId']) : '';
$response       = array();

if ($search_term != '') :
  $supplier_types = getSupplierTypesForAutocomplete($search_term, $event_type_id);

  $query = "SELECT
      S.idSalon,
      S.idTipoProveedor,
      S.Salon,
      S.slug,
      S.Referencia,
      S.Imagen,
      TP.slug AS TipoProveedorSlug,
      TP.TipoProveedor
    FROM salones AS S
      LEFT JOIN tipo_proveedores AS TP ON (S.idTipoProveedor = TP.idTipoProveedor)
    WHERE
      Status = 'Activo' AND
      (
        S.Salon           LIKE _utf8'%$search_term%' collate utf8_unicode_ci OR
        TP.TipoProveedor  LIKE _utf8'%$search_term%' collate utf8_unicode_ci
      )
    LIMIT 10
  ";

  $query_result = mysqli_query($mysqli, $query);
  $num_rows     = mysqli_num_rows($query_result);
  $index        = 0;

  foreach ($supplier_types as $key => $value) :
    array_push($response, $value);
  endforeach;

  if ($num_rows > 0) :
    while ($row = mysqli_fetch_array($query_result)) :
      $business           = $row['Salon'];
      $business_slug      = $row['slug'] . '-' . $row['Referencia'];
      $event_type         = getBusinessEventTypes($row['idSalon']);
      $event_type_slug    = $event_type[0]['slug'];
      $supplier_type_slug = $row['TipoProveedorSlug'];
      $image              = setBusinessImage($row['Imagen']);
      $business_url       = BASE_URL . '/' . $business_slug;

      array_push($response, array(
        'value'         => $row['Salon'],
        'label'         => $label,
        'business'      => $business,
        'image'         => $image,
        'supplierType'  => $row['TipoProveedor'],
        'url'           => $business_url,
        'supplierTypes' => $supplier_types,
        'type'          => 'item'
      ));
    endwhile;
  endif;
endif;

echo json_encode($response);
mysqli_close($mysqli);
die();
