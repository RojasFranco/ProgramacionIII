<?php

namespace App\Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

use App\Models\Ejemplo; //          USAR EL QUE REQUIERA, SE COMUNICA CON ESE QUE ES EL MODELO!
use App\Models\Usuario;

use App\Utils\TokenJwt;
use stdClass;
use App\Utils\RtaJson;

class UsuarioController{

    //$headers = $request->getHeaders();       HEADERS
    //$token = $headers["token"][0];        TOKEN
    //$queryParams = $request->getQueryParams(); //--->Los que vienen por parametro     PARAMETROS
    //args los que vienen por url               URL
    //$body = $request->getParsedBody();         BODY

    public function GetAll(Request $request, Response $response, $args){

        //Ejemplo::all(); TRAERIA TODOS LOS DE LA TABLA EJEMPLOS    ACA YA ESTOY USANDO ILUMINATE!!!
        $response->getBody()->write("LLEGUEEEE");
        return $response;
    }

    public function Insert(Request $request, Response $response, $args){
        $body = $request->getParsedBody();
        $email = $body["email"];
        $password = $body["password"];
        $tipoUsuario = $body["tipo_usuario"];


        $cantidadRegistrados = Usuario::where('email', $email)->count();
        if($cantidadRegistrados==0){
            $usuario = new Usuario();
            $usuario->email = $email;
            $usuario->password = $password;
            $usuario->tipo_usuario = $tipoUsuario;
            $rta = $usuario->save();
        }
        else{
            $rta = "El mail ya esta registrado";
        }   
        $response->getBody()->write(json_encode(RtaJson::RtaJson($rta)));
        return $response;
    }

    public function Login(Request $request, Response $response, $args){
        $body = $request->getParsedBody();
        $email = $body["email"];
        $password = $body["password"];

        $cantidadRegistrados = Usuario::where('email', $email)
        ->where('password', $password)->count();
        if($cantidadRegistrados==0){
            $rta = "Usuario incorrecto";
        }
        else{
            $usuario = Usuario::where('email', $email)->first();
            $objGuardar = new stdClass;
            $objGuardar->email = $email;
            $objGuardar->tipoUsuario = $usuario->tipo_usuario;
            $rta = TokenJwt::SolicitarToken($objGuardar);
        }
        $response->getBody()->write(json_encode(array("response"=>$rta)));
        return $response;
    }
    

}