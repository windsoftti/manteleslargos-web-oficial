<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<meta name="generator" content="WINDSOFTTI">
<title>Manteles Largos :: Administrador</title>

<!-- Google fonts -->
<!-- <link href="https://fonts.googleapis.com/css2?family=Libre+Baskerville:ital,wght@0,400;0,700;1,400&family=Poppins:ital,wght@0,400;0,500;0,600;0,700;1,400;1,500;1,600;1,700&display=swap" rel="stylesheet"> -->

<!-- Vendors CSS -->
<link rel="stylesheet" href="plugins/fontawesome-pro-5/css/all.css">
<link rel="stylesheet" href="plugins/slick/slick.min.css">
<link rel="stylesheet" href="plugins/jquery-ui/jquery-ui.min.css">
<link rel="stylesheet" href="plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css">

<!-- Themes core CSS -->
<link rel="stylesheet" href="css/custom-theme.css">
<link rel="stylesheet" href="css/custom.css">

<!-- Favicons -->
<link rel="icon" href="images/manteleslargos_favicon.png">

<style>
  .mobile-dropdown-content {
    display: none;
    position: absolute;
    top: 100%;
    right: 0;
    left: 0;
    width: 100%;
    height: 80vh;
    align-items: flex-start;
    justify-content: center;
    gap: 0.3rem;
    padding: 0.5rem;
  }

  .mobile-dropdown.open .mobile-dropdown-content {
    display: flex;
  }

  .mobile-dropdown-card {
    display: flex;
    width: 100%;
    height: 100%;
    flex-direction: column;
    background-color: #fff;
    align-items: flex-start;
    justify-content: flex-start;
    border-radius: 0.3rem;
    box-shadow: 0 1.5rem 4rem rgb(22 28 45 / 15%);
  }

  .mobile-dropdown-header {
    display: flex;
    align-items: center;
    justify-content: flex-start;
    width: 100%;
    border-bottom: 1px solid rgb(230, 230, 230);
    flex-wrap: wrap;
  }

  .mobile-dropdown-body {
    display: flex;
    width: 100%;
    height: 100%;
    flex-direction: column;
    align-items: flex-start;
    justify-content: flex-start;
    overflow-y: scroll;
  }

  .pulsate::before {
    content: '';
    display: block;
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    animation: pulse 1s ease infinite;
    border-radius: 50%;
    border: 4px double var(--primary);
  }

  @keyframes pulse {
    0% {
      transform: scale(1);
      opacity: 1;
    }

    60% {
      transform: scale(1.3);
      opacity: 0.4;
    }

    100% {
      transform: scale(1.5);
      opacity: 0;
    }
  }

  @keyframes pulse2 {
    0% {
      transform: scale(0.95);
      /* box-shadow: 0 0 0 0 rgba(0, 0, 0, 0.7); */
      box-shadow: 0 0 0 0 #b88c1c70;
    }

    70% {
      transform: scale(1);
      box-shadow: 0 0 0 10px #b88c1c00;
    }

    100% {
      transform: scale(0.95);
      box-shadow: 0 0 0 0 #b88c1c00;
    }
  }

  .pulse {
    animation: pulse2 1s infinite;
  }

  .swal2-popup.swal2-toast .swal2-header {
    gap: 0.5rem;
  }

  input.error,
  select.error,
  textarea.error {
    border-color: red;
  }

  .select2-container {
    width: 100% !important;
  }

  .form-group .select2-container--default .select2-selection--single {
    outline: none;
    border: 0.12rem solid rgb(229, 229, 229);
    height: 2.5rem;
    width: 100% !important;
    text-align: left;
    background: #fff;
    border-radius: 0.1rem;
    padding: 0 0.5rem;
    font-size: 0.9rem;
  }

  .select2-selection__rendered {
    height: 100%;
    align-items: center;
    justify-content: flex-start;
    display: flex !important;
  }

  .select2-container--default .select2-selection--single .select2-selection__arrow {
    height: 100%;
  }
</style>

<style>
  * {
    font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, 'Open Sans', 'Helvetica Neue', sans-serif;
  }

  :root {
    --border-radius: 1rem;
    --border-color: rgb(220, 220, 220);
  }

  html,
  body,
  main {
    background-color: #f0f0f0 !important;
  }

  .main-header {
    width: calc(100% - 1.25rem);
    margin: 0.625rem auto 0 auto;
    border-radius: var(--border-radius);
    border: 0.1rem solid var(--border-color);
    position: sticky !important;
    top: 0.625rem;
    z-index: 3;
  }

  .user-dropdown {
    padding: 0.5rem !important;
  }

  .card {
    border-radius: var(--border-radius);
    box-shadow: none;
    border-color: var(--border-color);
  }

  .card-header {
    background-color: transparent;
    border-color: var(--border-color);
    border-top-left-radius: var(--border-radius);
    border-top-right-radius: var(--border-radius);
  }

  .card-footer {
    background-color: transparent;
    border-color: var(--border-color);
  }

  .card-title {
    font-weight: 500;
    color: #000;
    font-size: 1.1rem;
    margin: 0.5rem 0;
  }

  /* .list-group-item.sidebar-item:hover {
    background-color: var(--primary) !important;
    color: #000 !important;
  } */

  .list-group-item>h5 {
    display: flex;
    width: 100%;
    align-items: center;
    justify-content: center;
    padding: 0rem 1rem;
    margin: 0;
    color: #000 !important;
    font-weight: bold;
  }

  .list-group-flush>.list-group-item {
    border-color: #999;
  }

  .list-group-item.pt-6.pb-4 {
    padding-left: 0 !important;
    padding-right: 0 !important;
  }

  .list-group-item.sidebar-item>a {
    display: flex;
    align-items: center;
    font-size: 0.85rem;
  }

  .list-group-item.sidebar-item.active {
    background-color: var(--primary) !important;
    color: #000 !important;
    border-radius: 0 !important;
  }

  .list-group-item.sidebar-item.active i {
    color: #000 !important;
    font-weight: 500;
  }

  .list-group-item.sidebar-item.active .sidebar-item-text {
    color: #000 !important;
    font-weight: 500;
  }

  .btn-custom-default {
    border: 0.1rem solid rgb(230, 230, 230);
    padding: 0.5rem;
    border-radius: 0.5rem;
  }

  .btn-custom-default span {
    font-weight: bold !important;
  }

  .text-secondary {
    color: #ec8fda !important;
  }

  /* .table thead tr {
    background: #fff;
    border-bottom: 4px solid #eceffa;
  } */

  .table thead th {
    border-top: none;
    padding-top: 1.2rem;
    padding-bottom: 1.2rem;
    font-weight: bold;
    font-size: 0.9rem;
    color: #000;
  }

  .table tbody td {
    font-size: 0.8rem;
    padding-top: 1rem;
    padding-bottom: 1rem;
  }

  /* .table tbody tr {
    margin-bottom: 10px;
    border-bottom: 4px solid #f8f9fd;
  } */

  .custom-img-thumbnail {
    height: 6rem;
    width: 6rem;
    object-fit: cover;
    border-radius: 0.3rem;
  }

  .time-label {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    padding: 0.3rem 1.3rem;
    border-radius: 0.3rem;
    border: 0.1rem solid var(--border-color);
  }

  .time-label>span {
    font-size: 0.8rem;
    color: gray;
    margin-bottom: 0.2rem;
  }

  .time-label>p {
    margin: 0;
    font-size: 0.9rem;
    color: #000;
    font-weight: 500;
  }

  .custom-navtabs {
    padding: 0;
  }

  .custom-navtabs>.nav-tabs {
    display: flex;
    width: 100%;
    align-items: center;
    justify-content: center;
    box-shadow: none !important;
    border-bottom: 0.1rem solid var(--border-color);
  }

  .custom-navtabs>.nav-tabs>li {
    display: flex;
    flex: 1;
    align-items: center;
    justify-content: center;
    padding: 0;
    box-shadow: none;
  }

  .custom-navtabs>.nav-tabs>li>a {
    margin: 0;
    display: flex;
    width: 100%;
    height: 2.8rem;
    background-color: transparent;
    align-items: center;
    justify-content: center;
    box-shadow: none;
    color: #000;
    font-size: 0.9rem;
    padding: 0.5rem;
  }

  .custom-navtabs>.nav-tabs>li>a.active {
    background-color: var(--primary);
    color: #000;
    box-shadow: none;
    position: static;
  }

  .business-total-cost {
    display: flex;
    width: 100%;
    background-color: rgba(230, 230, 230, 0.5);
    align-items: center;
    justify-content: center;
    padding: 0.6rem;
    font-size: 1rem;
    color: gray;
    border: 0.1rem solid var(--border-color);
    border-radius: 0.2rem;
    gap: 0.3rem;
  }

  .business-total-cost>span {
    font-weight: bold;
    color: #000;
  }

  .border-bottom-1 {
    border-bottom: 0.1rem solid var(--border-color);
  }

  .text-blue {
    color: var(--blue);
  }

  .sidebar-item-number {
    display: flex;
    height: 1.5rem;
    width: 1.5rem;
    background-color: var(--primary);
    align-items: center;
    justify-content: center;
    color: #000 !important;
    font-weight: normal !important;
    border-radius: 100%;
    border: 0.1rem solid #fff;
  }

  /***** Modal *****/
  .modal-content {
    border-radius: var(--border-radius);
    padding: 0;
  }

  .modal-header {
    background-color: var(--primary);
    border-top-left-radius: var(--border-radius);
    border-top-right-radius: var(--border-radius);
  }

  /***** End Modal *****/

  input[type="radio"] {
    cursor: pointer;
  }

  .form-check-label {
    cursor: pointer;
  }

  .fa-phone {
    transform: rotate(90deg);
  }

  /*======Estilos links menu PRO======*/
  .sidebar-link-premium {
    opacity: .85;
  }

  .sidebar-link-premium:hover {
      opacity: 1;
  }

  .sidebar-link-premium .sidebar-item-text {
      color: #b88c1c;
  }
  /*============*/

  @media (min-width: 1200px) {
    .dashboard-wrapper .db-sidebar .sticky-area {
      width: 12rem;
    }

    .dashboard-wrapper .db-sidebar {
      -ms-flex: 0 1 12rem;
      flex: 0 1 12rem;
      max-width: 12rem;
    }
  }
</style>