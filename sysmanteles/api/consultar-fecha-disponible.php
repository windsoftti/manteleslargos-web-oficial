<?php
error_reporting(E_ALL);
ini_set("display_errors", 1);
ini_set("display_startup_errors", 1);

ini_set("ignore_repeated_errors", TRUE);
ini_set("display_errors", FALSE);
ini_set("log_errors", TRUE);
ini_set("error_log", __DIR__ . "/logs/php-error.log");

error_log("fjkladsjfdlkasjdfkldasñ");

$get = json_encode($_REQUEST);


$filePath = 'example.txt';

// Contenido que se escribirá en el archivo
$contenido = 'Este es un ejemplo de contenido en un archivo de texto.' . json_encode($get);

// Intenta abrir o crear el archivo en modo escritura
if ($archivo = fopen($filePath, 'w')) {
  // Escribe el contenido en el archivo
  fwrite($archivo, $contenido);
  // Cierra el archivo
  fclose($archivo);
} else {
}

error_log(json_encode([
  "GET" => $get
]));

$data = htmlentities(json_encode([
  "GET" => $get
]));

$response = [
  "messages" => [
    [
      "type" => "to_user",
      "content" => "Los datos en get es: {$data}"
    ]
  ]
];

echo json_encode($response);
