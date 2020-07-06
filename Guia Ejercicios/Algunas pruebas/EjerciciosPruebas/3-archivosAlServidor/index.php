<?php
include_once './marcaAgua.php';
//echo json_encode($_FILES);
//"archivo es el key de lo enviado. name es el nombre, tem_name es donde se guarda temporalmente
$nombreOriginal = $_FILES["archivo"]["name"];
$nombreSeparadoPorPunto = explode(".", $nombreOriginal);


$origen = $_FILES["archivo"]["tmp_name"];
$destino = "./files/".$nombreSeparadoPorPunto[0]."-".time().".".$nombreSeparadoPorPunto[count($nombreSeparadoPorPunto)-1];

move_uploaded_file($origen,$destino);

MarcaAgua::AddImageWatermark($destino, "./pepe2.png", $destino, 50);