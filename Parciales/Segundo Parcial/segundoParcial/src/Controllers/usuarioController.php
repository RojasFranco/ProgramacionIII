<?php

namespace App\Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

use App\Models\Ejemplo; //          USAR EL QUE REQUIERA, SE COMUNICA CON ESE QUE ES EL MODELO!
use App\Models\Usuario;
use App\Utils\RespuestaJson;
use App\Utils\TokenJwt;

class UsuarioController{

    //$headers = $request->getHeaders();       HEADERS
    //$token = $headers["token"][0];        TOKEN
    //$queryParams = $request->getQueryParams(); //--->Los que vienen por parametro     PARAMETROS GET
    //args los que vienen por url  $args['nombreDat'] paara obtenerlo
    //$body = $request->getParsedBody();

    //Cliente tipo 1
    //veterinario tipo 2
    //Admin tipo 3
    public function Registrar(Request $request, Response $response, $args){        
        $body = $request->getParsedBody();
        if(isset($body["email"],$body["clave"], $body['tipo'], $body['usuario'])){
            $email=$body["email"];
            $clave=$body["clave"];
            $tipo=$body["tipo"];
            $usuario=$body["usuario"];

            $usuarioEncontrado = Usuario::where("email", $email)->first();
            if(empty($usuarioEncontrado)){
                if($tipo == 1 || $tipo== 2 || $tipo== 3){
                    $usuarioNuevo = new Usuario();
                    $usuarioNuevo->usuario = $usuario;
                    $usuarioNuevo->email = $email;
                    $usuarioNuevo->tipo = $tipo;
                    $usuarioNuevo->clave = $clave;
                    $rta = $usuarioNuevo->save();
                    $rta = RespuestaJson::RespuestaJson($rta);
                    $response->getBody()->write(json_encode($rta));
                }
                else{
                    $response->getBody()->write("Tipo de usuario invalido");
                }                
            }
            else{
                $response->getBody()->write("Mail ya registrado");
            }


        }
        else{
            $response->getBody()->write("Complete los datos");    
        }
        //$response->getBody()->write("LLEGUEEEE");
        return $response;
    }

    public function Login(Request $request, Response $response, $args){
        $body = $request->getParsedBody();
        if(isset($body["email"],$body["clave"])){
            $email = $body["email"];
            $clave = $body["clave"];
            $usuarioBuscado = Usuario::where("email", $email)->first();

            if(empty($usuarioBuscado)){
                $response->getBody()->write("Usuario no registrado");    
            }
            else{
                if($usuarioBuscado->clave == $clave){
                    $token = TokenJwt::SolicitarToken($email);
                    $rta = RespuestaJson::RespuestaJson($token);
                    $response->getBody()->write(json_encode($rta));
                }
                else{
                    $response->getBody()->write("Clave incorrecta");    
                }
            }
        }
        else{
            $response->getBody()->write("Complete los datos");    
        }        
        return $response;
    }

}