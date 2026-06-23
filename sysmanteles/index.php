<?php
include 'inc/session-auth.php';

if ($_SESSION['adm_session_user_id']) header('Location:' . BASE_URL . '/dashboard');
if (!$_SESSION['adm_session_user_id']) header('Location:' . BASE_URL . '/login');
