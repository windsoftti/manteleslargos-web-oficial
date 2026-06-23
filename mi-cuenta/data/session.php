<?php
if (empty($_SESSION)) {
    session_start();
    include '../../inc/config.inc.php';
}

if (empty($_SESSION['session_user_id'])) {
    header('Location:../../index');
}
