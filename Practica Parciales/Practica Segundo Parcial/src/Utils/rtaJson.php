<?php

namespace App\Utils;

use stdClass;

class RtaJson{


    public static function RtaJson($respuesta){
        $retorno = new stdClass;
        $retorno->respuesta = $respuesta;
        return $retorno;
    }
}