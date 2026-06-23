<?php
if ($_POST) {
  $session_business_id = cleanStr($_POST['s_business_id']);

  if ($session_business_id != '') $_SESSION['session_business_id'] = $session_business_id;
}
?>

<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<!-- <meta name="description" content="Real Estate Html Template"> -->
<!-- <meta name="author" content=""> -->
<meta name="generator" content="WINDSOFT TI">
<title><?= $meta_title ?> :: Manteles Largos</title>
<link rel="icon" href="images/manteleslargos_favicon.png">
<!-- Google fonts -->
<link href="https://fonts.googleapis.com/css2?family=Libre+Baskerville:ital,wght@0,400;0,700;1,400&family=Poppins:ital,wght@0,400;0,500;0,600;0,700;1,400;1,500;1,600;1,700&display=swap" rel="stylesheet">
<!-- plugins CSS -->
<link rel="stylesheet" href="plugins/fontawesome-pro-5/css/all.css">
<link rel="stylesheet" href="plugins/bootstrap-select/css/bootstrap-select.min.css">
<link rel="stylesheet" href="plugins/slick/slick.min.css">
<!-- <link rel="stylesheet" href="plugins/magnific-popup/magnific-popup.min.css"> -->
<link rel="stylesheet" href="plugins/jquery-ui/jquery-ui.min.css">
<link rel="stylesheet" href="plugins/chartjs/Chart.min.css">
<!-- <link rel="stylesheet" href="plugins/dropzone/css/dropzone.min.css"> -->
<!-- <link rel="stylesheet" href="plugins/animate.css"> -->
<!-- <link rel="stylesheet" href="plugins/timepicker/bootstrap-timepicker.min.css"> -->
<!-- <link rel="stylesheet" href="plugins/mapbox-gl/mapbox-gl.min.css"> -->
<!-- Themes core CSS -->
<!-- <link rel="stylesheet" href="css/themes.css"> -->
<link rel="stylesheet" href="css/custom-theme.css">
<link rel="stylesheet" href="css/custom.css">

<link rel="stylesheet" href="plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css">

<style>
  .dashboard-wrapper .page-content {
    width: calc(70%);
    max-width: 100%;
    -ms-flex: 1 1 auto;
    flex: 1 1 auto;
  }

  /* .dashboard-wrapper .db-sidebar {
    -ms-flex: 0 1 280px !important;
    flex: 0 1 280px !important;
    max-width: 280px !important;
  }

  .dashboard-wrapper .db-sidebar .sticky-area {
    position: relative;
    width: 280px !important;
    height: 100vh !important;
    overflow-y: auto;
    -webkit-transform: translate(0, 0) !important;
    transform: translate(0, 0) !important;
  } */
</style>