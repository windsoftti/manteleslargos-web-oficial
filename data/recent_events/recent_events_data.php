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
  case 'load_recent_events':
    try {
      $page                     = cleanStr($_POST['page']);
      $page                     = $page != '' ? $page : 1;

      $per_page                 = cleanStr($_POST['perPage']);
      $per_page                 = $per_page != '' ? $per_page : 16;

      $start_rows               = ($page - 1) * $per_page;
      $stop_rows                = $per_page;

      $search_term              = cleanStr($_POST['searchTerm']);
      $search_by_term           = $search_term != '' ? "Evento LIKE '%$search_term%'" : "1=1";

      $c_from                   = "FROM eventos_recientes";

      $c_where = "WHERE
          ($search_by_term) AND
          (Eliminado = 'No')
        ORDER BY idEvento
        DESC
      ";

      $limit_rows         = "LIMIT $start_rows, $stop_rows";

      $query_count        = "SELECT idEvento $c_from $c_where";
      $query_result_count = mysqli_query($mysqli, $query_count);
      $total_recent_events   = mysqli_num_rows($query_result_count);
      $num_pages          = ceil($total_recent_events / $stop_rows);

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
            idEvento,
            Evento,
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
          $recent_event_item_data         = base64_encode(json_encode($row));
          $recent_event_item_title        = limitStr($row['Evento'], 45);
          $recent_event_item_description  = limitStr($row['DescCorta'], 70);
          $recent_event_item_slug         = $row['slug'] . '-' . $row['Referencia'];
          $recent_event_item_img          = setRecentEventImage($row['Imagen']);
          $recent_event_item_url          = BASE_URL . '/eventos-recientes/' . $recent_event_item_slug;

          include '../../src/components/recent-event-item.php';
        endwhile;

        $content = base64_encode(ob_get_clean());

        $pagination = paginate($page, $num_pages, 2, 'loadRecentEvents');

        $response = array(
          'status'      => 'success',
          'content'     => $content,
          'pagination'  => $pagination,
          'results'     => $total_recent_events
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
