<?php
namespace App\Utils;

class Validacion{

    public static function ValidarUsuario($body){
        $retorno = false;
        if(isset($body["tipo_usuario"], $body['email'], $body['password'])){
            if($body["tipo_usuario"]=="cliente" || $body["tipo_usuario"]=="veterinario"){
                $retorno=true;
            }
        }
        return $retorno;
    }
}