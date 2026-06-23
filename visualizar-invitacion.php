<?php
include 'inc/user-session.php';

$invitation = $_POST;
$gallery    = $invitation['imageGallery-preview'];

if ($invitation['template'] == '01') include './visualizar-invitacion-plantilla-01.php';
if ($invitation['template'] == '02') include './visualizar-invitacion-plantilla-02.php';
if ($invitation['template'] == '03') include './visualizar-invitacion-plantilla-03.php';

die();
