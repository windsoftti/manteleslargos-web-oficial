<?php
function invitationsPaginate($page, $tpages, $limit, $funcion)
{
  if ($tpages == 1) return;

  $out = '';

  if ($page == 1) {
    $out .= '
      <a href="javascript:void(0)">
      </a>
    ';
  } else {
    if ($page < $tpages) {
      $out .= '
        <a onclick="' . $funcion . '(' . ($page - 1) . ')" href="javascript:void(0)">
          <ion-icon name="arrow-up-circle-outline"></ion-icon>
        </a>
      ';
    } else {
      $out .= '
        <a onclick="' . $funcion . '(' . ($page - 1) . ')" href="javascript:void(0)">
          <ion-icon name="arrow-up-circle-outline"></ion-icon>
        </a>
      ';
    }
  }

  // next
  if ($page < $tpages) {
    $out .= '
      <a onclick="' . $funcion . '(' . ($page + 1) . ')" href="#">
        <ion-icon name="arrow-down-circle-outline"></ion-icon>
      </a>
    ';
  } else {
    /* $out .= '
      <li class="arrow">
        <a href="javascript:void(0)">
          Siguiente
          <ion-icon name="arrow-forward-outline"></ion-icon>
        </a>
      </li>
    '; */
  }

  return $out;
}
