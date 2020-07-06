<?php

namespace App\Utils;

use stdClass;

class RespuestaJson{

    public static function RespuestaJson($rta){
        $retorno = new stdClass;
        $retorno->respuesta = $rta;
        return $retorno;
    }
}