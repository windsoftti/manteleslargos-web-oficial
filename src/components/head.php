<?php
/* header("Cache-Control: no-cache, must-revalidate"); // HTTP/1.1
header("Expires: Sat, 1 Jul 2000 05:00:00 GMT"); */ // Fecha en el pasado
?>

<meta charset="UTF-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">

<!-- <meta http-equiv="Expires" content="0">

<meta http-equiv="Last-Modified" content="0">

<meta http-equiv="Cache-Control" content="no-cache, mustrevalidate">

<meta http-equiv="Pragma" content="no-cache"> -->

<title><?= $webpage_meta_data['title']; ?></title>

<meta name="title" content="<?= $webpage_meta_data['title']; ?>">
<meta name="description" content="<?= $webpage_meta_data['description']; ?>">
<meta name="image" content="<?= $webpage_meta_data['image']; ?>">

<meta property="og:title" content="<?= $webpage_meta_data['title']; ?>">
<meta property="og:description" content="<?= $webpage_meta_data['description']; ?>">
<meta property="og:image" content="<?= $webpage_meta_data['image']; ?>">
<meta property="og:url" content="<?= $webpage_meta_data['currentURL']; ?>">

<link rel="canonical" href="<?= $webpage_meta_data['currentURL']; ?>" />

<!-- TWITTER -->
<meta name="twitter:card" content="summary">
<meta name="twitter:site" content="@">
<meta name="twitter:creator" content="@">
<meta name="twitter:title" content="<?= $webpage_meta_data['title']; ?>">
<meta name="twitter:description" content="<?= $webpage_meta_data['description']; ?>">
<meta name="twitter:image" content="<?= $webpage_meta_data['image']; ?>">

<!-- FACEBOOK -->
<meta property="og:url" content="<?= $webpage_meta_data['currentURL']; ?>">
<meta property="og:title" content="<?= $webpage_meta_data['title']; ?>">
<meta property="og:description" content="<?= $webpage_meta_data['description']; ?>">
<meta property="og:type" content="website">
<meta property="og:image" content="<?= $webpage_meta_data['image']; ?>">
<!-- <meta property="og:image:type" content="image/png"> -->
<meta property="og:image:width" content="1200">
<meta property="og:image:height" content="630">

<link rel="icon" type="image/x-icon" href="<?= BASE_URL; ?>/src/assets/images/favicon.png">

<!-- GOOGLE FONTS -->
<link rel="stylesheet" href="https://fonts.googleapis.com/css?display=swap&family=Lato:bold,900,800,700,600,500,400,600,300">

<!-- SELECT2 -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

<!-- JQUERY UI -->
<link rel="stylesheet" href="<?= BASE_URL; ?>/src/plugins/jquery-ui/jquery-ui.min.css">

<!-- Normalize CSS -->
<link rel="stylesheet" href="<?= BASE_URL; ?>/src/plugins/normalize/normalize.css">

<!-- Main CSS -->
<link rel="stylesheet" href="<?= BASE_URL; ?>/src/css/main.css">

<style>
  .select2 {
    width: 100% !important;
    flex: 1;
  }

  button:disabled {
    opacity: 0.5;
  }

  input:disabled {
    opacity: 0.5;
  }

  .business-navigation ul li a {
    padding: 0.2rem;
    border: 0.1rem solid #fff;
    border-radius: 0.1rem;
  }
</style>

<style>
  /* Tooltip container */
  .tooltip {
    position: relative;
    display: inline-block;
    border-bottom: 1px dotted black;
    /* If you want dots under the hoverable text */
  }

  /* Tooltip text */
  .tooltip .tooltiptext {
    visibility: hidden;
    width: 120px;
    background-color: black;
    color: #fff;
    text-align: center;
    padding: 5px 0;
    border-radius: 6px;

    /* Position the tooltip text - see examples below! */
    position: absolute;
    z-index: 1;
  }

  /* Show the tooltip text when you mouse over the tooltip container */
  .tooltip:hover .tooltiptext {
    visibility: visible;
  }

  .tooltip .tooltiptext {
    top: -5px;
    right: 105%;
  }

  .ui-autocomplete-loading {
    background: url('<?= BASE_URL; ?>/src/assets/images/indicator.gif') no-repeat right center
  }

  .cs-tooltip {
    position: relative;
  }

  .cs-tooltip .cs-tooltip-text {
    display: none;
    min-width: 10rem;
    background-color: rgba(0, 0, 0, 0.8);
    align-items: center;
    justify-content: flex-start;
    padding: 0.2rem 0.3rem;
    color: #fff;
    position: absolute;
    bottom: 100%;
    border-radius: 0.3rem;
    text-align: left;
    font-size: 0.8rem;
  }

  .cs-tooltip:hover .cs-tooltip-text {
    display: flex;
  }

  @media screen and (min-width: 64em) {
    .listing.map-mode .listing-body .listing-item {
      min-height: auto;
    }
  }
</style>
<script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=ca-pub-8395752374471835"
  crossorigin="anonymous"></script>