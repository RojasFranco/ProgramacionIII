<?php

//require_once __DIR__ . './'
///composer/vendor/autoload.php';
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
            "exp" => time()+60,
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



/******
$jwt = JWT::encode($payload, $key);
$decoded = JWT::decode($jwt, $key, array('HS256'));

print_r($decoded);


 NOTE: This will now be an object instead of an associative array. To get
 an associative array, you will need to cast it as such:


$decoded_array = (array) $decoded;*/
}