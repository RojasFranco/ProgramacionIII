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
use App\Models\Turno;

class TurnoController{

    //$headers = $request->getHeaders();       HEADERS
    //$token = $headers["token"][0];        TOKEN
    //$queryParams = $request->getQueryParams(); //--->Los que vienen por parametro     PARAMETROS GET
    //args los que vienen por url  $args['nombreDat'] paara obtenerlo
    //$body = $request->getParsedBody();


    
    public function PedirTurno(Request $request, Response $response, $args){

        $body = $request->getParsedBody();
        $headers = $request->getHeaders();
        $token = $headers["token"][0];
        $emailIngresado = TokenJwt::MostrarDatos($token);
        $userIngresado = Usuario::where("email", $emailIngresado)->first();
        if($userIngresado->tipo!=1){
            $response->getBody()->write("Solo pueden registrar los clientes");    
        }
        else{
            if(isset($body["veterinario_id"], $body["mascota_id"], $body["fecha"])){
                $veterinarioId =$body["veterinario_id"];
                $mascota_id =$body["mascota_id"];
                $fechaRecib =$body["fecha"];
                $fecha = getdate($fechaRecib);
                $horaPedida = $fecha["hours"];

                if($horaPedida<9 || $horaPedida>17){
                    $response->getBody()->write("Fuera horario");    
                }
                else{                                                        
                    $turnoNuevo = new Turno();
                    $turnoNuevo->veterinario_id = $veterinarioId;
                    $turnoNuevo->mascota_id=$mascota_id;
                    $turnoNuevo->fecha = $fecha;




                    $veterinarioPedido = Usuario::where("id", $veterinarioId)->first();


                    $turnoNuevo->save();
                }
            }
            else{
                $response->getBody()->write("Complete los datos");
            }
        }
        
        
        //Ejemplo::all(); TRAERIA TODOS LOS DE LA TABLA EJEMPLOS    ACA YA ESTOY USANDO ILUMINATE!!!
        $response->getBody()->write(" ");
        return $response;
    }

    /*
    public function PedirTurno(Request $request, Response $response, $args){
        $body = $request->getParsedBody();
        $headers = $request->getHeaders();
        $token = $headers["token"][0];
        
        $emailIngresado = TokenJwt::MostrarDatos($token);
        $userIngresado = Usuario::where("email", $emailIngresado)->first();
        if($userIngresado->tipo!=2){
            $response->getBody()->write("Solo pueden ver Veterinarios");    
        }
        else{
            
            $turnosDelVeterinario = Turno::join("usuarios", "usuario.id", "turnos.veterinario_id")->first();

        }

        return $response;
    }
    */
    

}