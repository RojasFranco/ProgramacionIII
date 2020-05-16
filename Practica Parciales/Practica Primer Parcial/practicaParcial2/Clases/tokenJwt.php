<?php

use \Firebase\JWT\JWT;
class TokenJwt{    
    private static $key = "pro3-parcial";
    //public $payload;

    public static function SolicitarToken($datos){
        $payload = array(
            "iss" => "http://example.org",
            "aud" => "autentificacion",
            "iat" => time(),
            "exp" => time()+600,
            "datos" => $datos
            );
        $jwtRetorno = JWT::encode($payload,TokenJwt::$key);
        return $jwtRetorno;
    
    }

    public static function ValidarToken($token){    
        try{
            $decoded = JWT::decode($token, TokenJwt::$key, array('HS256'));     
            return $decoded;
        }        
        catch(Exception $error){            
            //echo "error en validar token con token enviado";
            throw $error;
        }        
    }

    public static function MostrarDatos($token){
        try{            
            $retorno = TokenJwt::ValidarToken($token);
            return $retorno->datos;
        }        
        catch(Exception $err){
            //echo "error en mostrar datos con token enviado";
            throw $err;
        }        
    }
}