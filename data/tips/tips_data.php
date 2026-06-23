<?php
include '../lib/public-session.php';
include '../lib/pagination.php';

$response = array(
  'status'  => 'error',
  'title'   => '¡Error!',
  'message' => 'Error inesperado, Intentalo nuevamente.',
  'content' => 'Error inesperado, Intentalo nuevamente.'
);

$action = $_POST['action'];

switch ($action) {
  case 'load_tips':
    try {
      $page                     = cleanStr($_POST['page']);
      $page                     = $page != '' ? $page : 1;

      $per_page                 = cleanStr($_POST['perPage'] ?? ''); // cleanStr($_POST['perPage']);
      $per_page                 = $per_page != '' ? $per_page : 16;

      $start_rows               = ($page - 1) * $per_page;
      $stop_rows                = $per_page;

      $search_term              = cleanStr($_POST['searchTerm'] ?? '');
      $search_by_term           = $search_term != '' ? "Tip LIKE '%$search_term%'" : "1=1";

      $c_from                   = "FROM tips";

      $c_where = "WHERE
          ($search_by_term) AND
          (Eliminado = 'No')
        ORDER BY idTip
        DESC
      ";

      $limit_rows         = "LIMIT $start_rows, $stop_rows";

      $query_count        = "SELECT idTip $c_from $c_where";
      $query_result_count = mysqli_query($mysqli, $query_count);
      $total_businesses   = mysqli_num_rows($query_result_count);
      $num_pages          = ceil($total_businesses / $stop_rows);

      if (!$num_pages) :
        ob_start();
        echo '
            <div class="no-results">
              <ion-icon name="alert-circle-outline"></ion-icon>
              ¡No se encontraron resultados!
            </div>
        ';
        $content = base64_encode(ob_get_clean());

        $response = array(
          'status'      => 'success',
          'content'     => $content,
          'pagination'  => '',
          'results'     => '0'
        );
      endif;

      if ($num_pages) :
        $query = "SELECT
            idTip,
            Tip,
            DescCorta,
            Imagen,
            Referencia,
            slug
          $c_from
          $c_where
          $limit_rows
        ";

        $query_result = mysqli_query($mysqli, $query);

        ob_start();

        while ($row = mysqli_fetch_array($query_result)) :
          $tip_item_data               = base64_encode(json_encode($row));
          $tip_item_title              = limitStr($row['Tip'], 45);
          $tip_item_description        = limitStr($row['DescCorta'], 200);
          $tip_item_slug               = $row['slug'] . '-' . $row['Referencia'];
          $tip_item_img                = setTipImage($row['Imagen']);
          $tip_item_url                = BASE_URL . '/tips/' . $tip_item_slug;

          include '../../src/components/tip-item.php';
        endwhile;

        $content = base64_encode(ob_get_clean());

        $pagination = paginate($page, $num_pages, 2, 'loadTips');

        $response = array(
          'status'      => 'success',
          'content'     => $content,
          'pagination'  => $pagination,
          'results'     => $total_businesses
        );
      endif;
    } catch (Exception $e) {
      $response['message'] = $e->getMessage();
    }
    break;
}

echo json_encode($response);
mysqli_close($mysqli);
die();
