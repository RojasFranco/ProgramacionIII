<?php

// $_GET $_POST $_REQUEST $_SERVER

// echo json_encode($_FILES);
var_dump($_FILES['archivo']);

$explode = explode('.', $_FILES["archivo"]["name"]);
var_dump($explode);
$origen = $_FILES['archivo']["tmp_name"];
$destino = './files/'. $explode[0] . '-' . rand(1000, 10000) . '-' . time() . '.' . array_reverse($explode)[0];

// echo "Resultado ". move_uploaded_file($origen, $destino);
$file = 'files/' . $_FILES["archivo"]["name"];
$destino = 'backup/' . $_FILES["archivo"]["name"];
copy($file, $destino);
unlink($file);