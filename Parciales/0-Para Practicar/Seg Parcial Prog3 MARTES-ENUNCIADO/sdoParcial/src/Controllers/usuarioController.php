<?php

namespace App\Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

use App\Models\Usuario; //          USAR EL QUE REQUIERA, SE COMUNICA CON ESE QUE ES EL MODELO!
use App\Utils\RespuestaJson;
use App\Utils\TokenJwt;

class UsuarioController{

    //$headers = $request->getHeaders();       HEADERS
    //$token = $headers["token"][0];        TOKEN
    //$queryParams = $request->getQueryParams(); //--->Los que vienen por parametro     PARAMETROS GET
    //args los que vienen por url  $args['nombreDat'] paara obtenerlo
    //$body = $request->getParsedBody();


    /*
    TIPO:
    1-alumno
    2-profesor
    3-admin
    */
    public function Insertar(Request $request, Response $response, $args){

        //Ejemplo::all(); TRAERIA TODOS LOS DE LA TABLA EJEMPLOS    ACA YA ESTOY USANDO ILUMINATE!!!
        $body = $request->getParsedBody();
        
        if(isset($body["email"], $body["nombre"], $body["clave"], $body["tipo"], $body["legajo"])){
            $email = $body["email"];
            $nombre = $body["nombre"];
            $clave = $body["clave"];
            $tipo = $body["tipo"];
            $legajo = $body["legajo"];

            $legajo = (int) $legajo;
            $userMailExistente = Usuario::all()->where("email", $email)->first();
            if(empty($userMailExistente)){
                $userLegajoExistente = Usuario::where("legajo", $legajo)->first();
                if(empty($userLegajoExistente) && $legajo<=2000 && $legajo>=1000){
                    $usuarioNuevo = new Usuario();
                    $usuarioNuevo->email = $email;
                    $usuarioNuevo->nombre = $nombre;
                    $usuarioNuevo->clave = $clave;
                    $usuarioNuevo->tipo_id = $tipo;
                    $usuarioNuevo->legajo = $legajo;

                    $rta = $usuarioNuevo->save();
                    $rta = RespuestaJson::RespuestaJson($rta);
                    $response->getBody()->write(json_encode($rta));
                }
                else{
                    $response->getBody()->write("legajo ya registrado o invalido(rango 1000-2000)");        
                }
            }
            else{
                $response->getBody()->write("email ya registrado");    
            }
        }
        else{
            $response->getBody()->write("Complete los campos");
        }        
        return $response;
    }

    public function Login(Request $request, Response $response, $args){

        //Ejemplo::all(); TRAERIA TODOS LOS DE LA TABLA EJEMPLOS    ACA YA ESTOY USANDO ILUMINATE!!!
        $body = $request->getParsedBody();
        if(isset($body["email"], $body["clave"])){
            $email = $body["email"];
            $clave = $body["clave"];
            $userMailExistente = Usuario::where("email", $email)->first();
            if(!empty($userMailExistente)){
                if($userMailExistente->clave == $clave){
                    $tokenRetornar = TokenJwt::SolicitarToken($email);
                    $rta = RespuestaJson::RespuestaJson($tokenRetornar);
                    $response->getBody()->write(json_encode($rta));
                }
                else{
                    $response->getBody()->write("Clave invalida");        
                }
            }
            else{
                $response->getBody()->write("Mail no registrado");    
            }
        }
        else{
            $response->getBody()->write("Complete los campos");
        }
                
        return $response;
    }

}