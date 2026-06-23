<?php
/*$permissions = verifyUserPermissions($page_slug);

if (!$permissions) {
  //header("location:javascript://history.go(-1)");
  header('location:panel');
  exit();
}*/
if (!isset($page_slug)) {
    die('Missing page slug');
}

$required_action = $required_action ?? 'view';

requirePermission(
    $page_slug,
    $required_action
);