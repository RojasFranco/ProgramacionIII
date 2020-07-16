<?php

namespace App\Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

use App\Models\Materia; //          USAR EL QUE REQUIERA, SE COMUNICA CON ESE QUE ES EL MODELO!
use App\Models\Usuario;
use App\Models\Inscripto;

use App\Utils\RespuestaJson;
use App\Utils\TokenJwt;
use DateTime;
use stdClass;

class MateriaController{

    //$headers = $request->getHeaders();       HEADERS
    //$token = $headers["token"][0];        TOKEN
    //$queryParams = $request->getQueryParams(); //--->Los que vienen por parametro     PARAMETROS GET
    //args los que vienen por url  $args['nombreDat'] paara obtenerlo
    //$body = $request->getParsedBody();

    public function Insertar(Request $request, Response $response, $args){

        //Solo para admin (tipo de usuario: 3)

        $body = $request->getParsedBody();
        $headers = $request->getHeaders();
        $token = $headers["token"][0]; // YA VALIDADO
        if(isset($body["materia"],$body["cuatrimestre"],$body["vacantes"],$body["profesor"])){
            $materia = $body["materia"];
            $cuatrimestre = $body["cuatrimestre"];
            $vacantes = $body["vacantes"];
            $profesor = $body["profesor"];

            $emailIngresado = TokenJwt::MostrarDatos($token);

            $usuarioIngresado = Usuario::all()->where("email", $emailIngresado)->first();
            if($usuarioIngresado->tipo_id==3){
                $materiaNueva = new Materia();
                $materiaNueva->materia = $materia;
                $materiaNueva->cuatrimestre = $cuatrimestre;
                $materiaNueva->vacantes = $vacantes;
                $materiaNueva->profesor_id = $profesor;
                $rta = $materiaNueva->save();

                $rta = RespuestaJson::RespuestaJson($rta);
                $response->getBody()->write(json_encode($rta));
            }
            else{
                $response->getBody()->write("no posee permiso, solo admin");
            }
        }
        else{
            $response->getBody()->write("Complete los datos");
        }        
        return $response;
    }

    public function GetMaterias(Request $request, Response $response, $args){

        //Ejemplo::all(); TRAERIA TODOS LOS DE LA TABLA EJEMPLOS    ACA YA ESTOY USANDO ILUMINATE!!!

        $idMateria = $args["id"];
        $body = $request->getParsedBody();
        $headers = $request->getHeaders();
        $token = $headers["token"][0]; // YA VALIDADO

        $emailIngresado = TokenJwt::MostrarDatos($token);
        $usuarioIngresado = Usuario::where("email", $emailIngresado)->first();

        $materiaMostrar = Materia::where("id", $idMateria)->first();
        if(empty($materiaMostrar)){
            $response->getBody()->write("no existe esa materiaId");
        }
        else{
            if($usuarioIngresado->tipo_id==1){      //ALUMNO            
                $response->getBody()->write(json_encode($materiaMostrar));    
            }
            else{       //Prof o Admin
                $rta = new stdClass;
                $rta->materia = $materiaMostrar;
                $listaInscriptos = Inscripto::where("materia_id", $idMateria)
                ->join("users","users.id", "=", "inscriptos.alumno_id")
                ->select("users.nombre", "users.email", "users.legajo")->get();
                $rta->inscriptos = $listaInscriptos;

                $response->getBody()->write(json_encode($rta));
            }
        }                
        return $response;
    }
    

    public function UpdateProfesor(Request $request, Response $response, $args){

        //Ejemplo::all(); TRAERIA TODOS LOS DE LA TABLA EJEMPLOS    ACA YA ESTOY USANDO ILUMINATE!!!
        $body = $request->getParsedBody();
        $headers = $request->getHeaders();
        $token = $headers["token"][0]; // YA VALIDADO
        $idMateria = $args["id"];
        $profesorAgregar = $args["profesor"];

        $materiaActualizar = Materia::where("id", $idMateria)->first();

        $emailIngresado = TokenJwt::MostrarDatos($token);
        $usuarioIngresado = Usuario::where("email", $emailIngresado)->first();
        if($usuarioIngresado->tipo_id==3){
            $materiaActualizar->profesor_id = $profesorAgregar;
            $rta=$materiaActualizar->save();
            $rta = RespuestaJson::RespuestaJson($rta);
            $response->getBody()->write(json_encode($rta));
        }
        else{
            $response->getBody()->write("no posee aut, solo admin");
        }        
        return $response;
    }

    public function InscribirMateria(Request $request, Response $response, $args){
        $headers = $request->getHeaders();
        $token = $headers["token"][0]; // YA VALIDADO        
        if(isset($args["id"])){
            $idMateria = $args["id"];
            $emailIngresado = TokenJwt::MostrarDatos($token);
            $usuarioIngresado = Usuario::where("email", $emailIngresado)->first();
            $materiaIngresada = Materia::where("id", $idMateria)->first();
            if(empty($materiaIngresada)){
                $response->getBody()->write("no existe esa materia");
                return $response;
            }
            if($usuarioIngresado->tipo_id==1){
                if($materiaIngresada->vacantes>0){
                    $materiaIngresada->vacantes -=1;
                    $materiaIngresada->save();

                    $nuevaInscripcion = new Inscripto();
                    $nuevaInscripcion->alumno_id = $usuarioIngresado->id;
                    $nuevaInscripcion->materia_id = $idMateria;
                    $nuevaInscripcion->date = date("y/m/d H:i:s");
                    $rta = $nuevaInscripcion->save();
                    $rta = RespuestaJson::RespuestaJson($rta);                    
                    $response->getBody()->write(json_encode($rta));
                }
                else{
                    $response->getBody()->write("No hay vacantes para la materia");    
                }
            }
            else{
                $response->getBody()->write("No posee permiso, solo alumnos");
            }
        }
        else
        {
            $response->getBody()->write("Ingrese id materia");
        }
        
        return $response;
    }

    public function GetTodas(Request $request, Response $response, $args){

        //Ejemplo::all(); TRAERIA TODOS LOS DE LA TABLA EJEMPLOS    ACA YA ESTOY USANDO ILUMINATE!!!
        $materiasMostrar = Materia::
        join("users", "users.legajo", "=", "materias.profesor_id")
        ->join("inscriptos", "inscriptos.materia_id", "=", "materias.id")
        ->select("materias.materia", "materias.vacantes", "materias.cuatrimestre", "users.nombre", "users.email")
        ->get();
        $response->getBody()->write(json_encode($materiasMostrar));
        return $response;
    }
    
}