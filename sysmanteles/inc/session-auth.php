<?php
session_start();
include 'constants.inc.php';
include 'config.inc.php';
include 'global-functions.inc.php';
include 'specific-functions.inc.php';

if ($_SESSION['adm_session_user_id']) header('location:' . BASE_URL . '/dashboard');
