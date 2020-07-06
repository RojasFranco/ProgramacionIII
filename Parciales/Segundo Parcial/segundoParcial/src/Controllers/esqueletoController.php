<?php

namespace App\Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

use App\Models\Ejemplo; //          USAR EL QUE REQUIERA, SE COMUNICA CON ESE QUE ES EL MODELO!

class EsqueletoController{

    //$headers = $request->getHeaders();       HEADERS
    //$token = $headers["token"][0];        TOKEN
    //$queryParams = $request->getQueryParams(); //--->Los que vienen por parametro     PARAMETROS GET
    //args los que vienen por url  $args['nombreDat'] paara obtenerlo
    //$body = $request->getParsedBody();

    public function GetAll(Request $request, Response $response, $args){

        //Ejemplo::all(); TRAERIA TODOS LOS DE LA TABLA EJEMPLOS    ACA YA ESTOY USANDO ILUMINATE!!!
        $response->getBody()->write("LLEGUEEEE");
        return $response;
    }

}