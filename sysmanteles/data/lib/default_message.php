<?php
$def_message = $default_message ? $default_message : '¡No existen registros!';
$def_icon = $default_icon ? $default_icon : 'fa fa-info-circle';
?>

<div class="text-center m-5">
  <i class="<?= $def_icon ?> fa-7x text-gray"></i>
  <div class="mt-3">
    <p class="text-md">
      <?= $def_message ?>
    </p>
  </div>
</div>