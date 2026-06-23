<?php
function paginate($page, $tpages, $limit, $funcion)
{
  if ($tpages == 1) return;

  $out = '<ul>';

  if ($page == 1) {
    /* $out .= '
      <li class="arrow">
        <a href="javascript:void(0)">
          <ion-icon name="arrow-back-outline"></ion-icon>
        </a>
      </li>
    '; */
  } else {
    if ($page < $tpages) {
      $out .= '
        <li class="arrow">
          <a onclick="' . $funcion . '(' . ($page - 1) . ')" href="javascript:void(0)">
            <ion-icon name="arrow-back-outline"></ion-icon>
          </a>
        </li>
      ';
    } else {
      $out .= '
        <li class="arrow">
          <a onclick="' . $funcion . '(' . ($page - 1) . ')" href="javascript:void(0)">
            <ion-icon name="arrow-back-outline"></ion-icon> Anterior
          </a>
        </li>
      ';
    }
  }

  // first label
  if ($page > ($limit + 1)) {
    $out .= '
      <li>
        <a onclick="' . $funcion . '(1)" href="javascript:void(0)">1</a>
      </li>
    ';
  }

  // interval
  if ($page > ($limit + 2)) {
    $out .= '
      <li>
        <a href="javascript:void(0)">...</a>
      </li>
    ';
  }

  $pmin = ($page > $limit) ? ($page - $limit) : 1;
  $pmax = ($page < ($tpages - $limit)) ? ($page + $limit) : $tpages;

  for ($i = $pmin; $i <= $pmax; $i++) {
    if ($i == $page) {
      $out .= '
        <li class="active">
          <a href="javascript:void(0)">' . $i . '</a>
        </li>
      ';
    } else if ($i == 1) {
      $out .= '
        <li>
          <a onclick="' . $funcion . '(1)" href="javascript:void(0)">1</a>
        </li>
      ';
    } else {
      $out .= '
        <li>
          <a onclick="' . $funcion . '(' . $i . ')" href="javascript:void(0)">' . $i . '</a>
        </li>
      ';
    }
  }

  // Interval
  if ($page < ($tpages - $limit - 1)) {
    $out .= '
      <li>
        <a href="javascript:void(0)">...</a>
      </li>
    ';
  }

  // last
  if ($page < ($tpages - $limit)) {
    $out .= '
      <li>
        <a onclick="' . $funcion . '(' . $tpages . ')" href="javascript:void(0)">' . $tpages . '</a>
      </li>
    ';
  }

  // next
  if ($page < $tpages) {
    $out .= '
      <li class="arrow">
        <a onclick="' . $funcion . '(' . ($page + 1) . ')" href="javascript:void(0)">
          Siguiente
          <ion-icon name="arrow-forward-outline"></ion-icon>
        </a>
      </li>
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

  $out .= '</ul>';

  return $out;
}
