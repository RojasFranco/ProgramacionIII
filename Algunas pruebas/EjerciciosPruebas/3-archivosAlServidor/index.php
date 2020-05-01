<?php

//echo json_encode($_FILES);
$nombreOriginal = $_FILES["archivo"]["name"];
$nombreSeparadoPorPunto = explode(".", $nombreOriginal);


$origen = $_FILES["archivo"]["tmp_name"];
$destino = "./files/".$nombreSeparadoPorPunto[0]."-".time().".".$nombreSeparadoPorPunto[count($nombreSeparadoPorPunto)-1];

move_uploaded_file($origen,$destino);
