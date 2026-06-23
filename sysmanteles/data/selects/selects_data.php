<?php
include '../lib/session-root.php';

$action = $_POST['action'];

switch ($action) {
  case 'categories':
    $query = "SELECT
        id,
        name AS Category
      FROM categories
      WHERE parent_id IS NULL
    ";

    $query_result = mysqli_query($mysqli, $query);
    $num_rows     = mysqli_num_rows($query_result);

    if ($num_rows) {
      ob_start();
      while ($row = mysqli_fetch_array($query_result)) : ?>
        <optgroup label="<?= $row['Category']; ?>">
          <?php
          $query_categories = "SELECT
              id,
              name AS Category
            FROM categories
            WHERE parent_id = $row[id]
            ORDER BY position
            ASC
          ";

          $query_categories_result = mysqli_query($mysqli, $query_categories);
          ?>
          <?php while ($category = mysqli_fetch_array($query_categories_result)) : ?>
            <option value="<?= $category['id']; ?>"><?= $category['Category']; ?></option>
          <?php endwhile; ?>
        </optgroup>
      <?php
      endwhile;

      $data_select = base64_encode(ob_get_clean());

      $response['content'] = $data_select;
    }
    break;

  case 'parent_categories':
    $query = "SELECT
        id,
        name AS Category
      FROM categories
      WHERE parent_id IS NULL
    ";

    $query_result = mysqli_query($mysqli, $query);
    $num_rows     = mysqli_num_rows($query_result);

    if ($num_rows) {
      ob_start(); ?>
      <option value="">Seleccionar</option>
      <?php while ($row = mysqli_fetch_array($query_result)) : ?>
        <option value="<?= $row['id']; ?>"><?= $row['Category']; ?></option>
<?php
      endwhile;

      $data_select = base64_encode(ob_get_clean());

      $response['content'] = $data_select;
    }
    break;

  default:
    $response = [];
    break;
}

echo json_encode($response);
