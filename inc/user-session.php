<?php
include 'public-session.php';

if (!$_SESSION['session_user_id'] || $_SESSION['session_user_level'] != 'Usuario Final') :
  header('location:' . BASE_URL);
endif;

if ($_SESSION['session_user_status'] != 'Activo') :
  header('location:' . BASE_URL);
endif;
