<?php

namespace App\Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

use App\Models\Ejemplo; //          USAR EL QUE REQUIERA, SE COMUNICA CON ESE QUE ES EL MODELO!
use App\Models\Mascota;
use App\Utils\RespuestaJson;
use App\Utils\TokenJwt;
use App\models\TipoMascota;
use App\models\Usuario;

class MascotaController{

    //$headers = $request->getHeaders();       HEADERS
    //$token = $headers["token"][0];        TOKEN
    //$queryParams = $request->getQueryParams(); //--->Los que vienen por parametro     PARAMETROS GET
    //args los que vienen por url  $args['nombreDat'] paara obtenerlo
    //$body = $request->getParsedBody();

    //Cliente tipo 1
    //veterinario tipo 2
    //Admin tipo 3


    public function CargarTipo(Request $request, Response $response, $args){     
        $headers = $request->getHeaders();
        $body = $request->getParsedBody();
        if(isset($headers["token"],$body["tipo"])){
            $tipoIngresado = $body["tipo"];
            $token = $headers["token"][0];
            $email = TokenJwt::MostrarDatos($token);
            $usuarioIngresado = Usuario::where("email", $email)->first();
            if($usuarioIngresado->tipo == 3){
                $nuevoTipo = new TipoMascota();
                $nuevoTipo->tipo = $tipoIngresado;
                $rta = $nuevoTipo->save();

                $rta = RespuestaJson::RespuestaJson($rta);
                $response->getBody()->write(json_encode($rta));
            }
            else{
                $response->getBody()->write("No es adm, no posee permiso");
            }                        
        }
        else{
            $response->getBody()->write("Complete los datos");    
        }
        
        return $response;
    }

    public function Insertar(Request $request, Response $response, $args){     
        $headers = $request->getHeaders();
        $token = $headers["token"][0];
        $body = $request->getParsedBody();
        if(isset($body["nombre"], $body["fecha_nacimiento"], $body["cliente_id"], $body["tipo_mascota_id"])){
            $nombreMascota = $body["nombre"];
            $fecha_nacimiento = $body["fecha_nacimiento"];
            $cliente_id = $body["cliente_id"];
            $tipo_mascota_id = $body["tipo_mascota_id"];

            $tipoMascotaPedido = TipoMascota::where("id", $tipo_mascota_id)->first();
            $dueñoPedido = Usuario::where("id", $cliente_id)->first();

            $emailIngresado = TokenJwt::MostrarDatos($token);
            $userIngresado = Usuario::where("email", $emailIngresado)->first();
            if($userIngresado->tipo!=1){
                $response->getBody()->write("Solo pueden registrar los clientes");    
                return $response;
            }

            if(empty($tipoMascotaPedido) || empty($dueñoPedido)){
                $response->getBody()->write("No existe el tipo de mascota o dueño");    
            }
            else{
                $mascotaNueva = new Mascota();
                $mascotaNueva->nombre = $nombreMascota;
                $mascotaNueva->fecha_nacimiento = $fecha_nacimiento;
                $mascotaNueva->cliente_id  = $cliente_id;
                $mascotaNueva->tipo_mascota_id = $tipo_mascota_id;

                $rta = $mascotaNueva->save();
                $rta = RespuestaJson::RespuestaJson($rta);
                $response->getBody()->write(json_encode($rta));
            }

        }
        else{
            $response->getBody()->write("Complete los datos");    
        }
        
        return $response;
    }

}