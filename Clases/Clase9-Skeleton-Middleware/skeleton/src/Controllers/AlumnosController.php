<?php

namespace App\Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use App\Models\Alumno;

class AlumnosController {

    public function getAll(Request $request, Response $response, $args)
    {
        $rta = json_encode(Alumno::all());

        // $response->getBody()->write("Controller");
        $response->getBody()->write($rta);

        return $response;
    }

    public function add(Request $request, Response $response, $args)
    {
        $alumno = new Alumno;
        $alumno->alumno = "Eloquent";
        $alumno->legajo = 4201;
        $alumno->localidad = 2;
        $alumno->cuatrimestre = 1;
        
        $rta = json_encode(array("ok" => $alumno->save()));

        $response->getBody()->write($rta);

        return $response;
    }
}