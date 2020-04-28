<?php

use \Firebase\JWT\JWT;
class TokenJwt{    
    private $key = "miClaveSecreta";
    public $payload;

    public function __construct()
    {        
    }

    public function SolicitarToken($datos){
        $this->payload = array(
            "iss" => "http://example.org",
            "aud" => "autentificacion",
            "iat" => time(),
            "exp" => time()+180,
            "datos" => $datos
            );
        $jwtRetorno = JWT::encode($this->payload, $this->key);
        return $jwtRetorno;
    
    }

    public function ValidarToken($token){    
        try{
            $decoded = JWT::decode($token, $this->key, array('HS256'));            
            return $decoded;
        }        
        catch(Exception $error){            
            //echo "error en validar token con token enviado";
            throw $error;
        }        
    }

    public function MostrarDatos($token){
        try{            
            $retorno = $this->ValidarToken($token);
            return $retorno->datos;
        }        
        catch(Exception $err){
            //echo "error en mostrar datos con token enviado";
            throw $err;
        }        
    }
}