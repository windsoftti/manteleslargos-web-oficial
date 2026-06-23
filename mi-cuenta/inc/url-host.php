<?php
$protocol = stripos($_SERVER['SERVER_PROTOCOL'], 'https') === 0 ? 'https://' : 'http://';
$url_host = $protocol . $_SERVER['HTTP_HOST'] . '/2021/web/';
