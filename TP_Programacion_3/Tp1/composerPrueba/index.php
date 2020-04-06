<?php

require_once __DIR__ . '/vendor/autoload.php';
require './Clases/paisPorContinente.php';
require './Clases/paisPorSubRegion.php';
require './Clases/paisPorCapital.php';
require './Clases/paisConDetalle.php';

use NNV\RestCountries;
$restCountries = new RestCountries;




//   POR CONTINENTE

/*
$paisesPorCont = new PaisesPorContinente($restCountries);
$paisesPorCont->MostrarPaises("americas");
*/

//POR SUBREGION
/*
PaisPorRegion::MostrarPaises($restCountries, "USAN");
*/

//POR CAPITAL

/*
$paisesPorCapital = new PaisPorCapital($restCountries);
$paisesPorCapital->MostrarPaises("buenos aires");
*/

//Pais Con Detalles


$paisDetallado = new PaisDetallado($restCountries);
$paisDetallado->MostrarDetalles("Argentina");

