<?php

namespace App\Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

use App\Models\Ejemplo; //          USAR EL QUE REQUIERA, SE COMUNICA CON ESE QUE ES EL MODELO!
use App\Models\Mascota;
use App\Models\Usuario;

use App\Utils\TokenJwt;

class MascotaController{

    //$headers = $request->getHeaders();       HEADERS
    //$token = $headers["token"][0];        TOKEN
    //$queryParams = $request->getQueryParams(); //--->Los que vienen por parametro     PARAMETROS
    //args los que vienen por url               URL
    //$body = $request->getParsedBody();         BODY

    public function Insertar(Request $request, Response $response, $args){
        $body = $request->getParsedBody();

        $nombre = $body['nombre'];
        $edad = $body['edad'];
        $idCliente = $body['idCliente'];

        $mascota = new Mascota();
        $mascota->nombre = $nombre;
        $mascota->edad=$edad;
        $mascota->id_cliente = $idCliente;
        $rta = $mascota->save();
        
        $response->getBody()->write(json_encode(array( "Guardado"=>$rta))) ;
        return $response;
    }

    public function prueba(Request $request, Response $response, $args){

        $idBuscado = $args['id'];

        $joins = Mascota::join('usuarios', 'usuarios.id', 'mascotas.id_cliente')->get();
        $response->getBody()->write(json_encode($joins[0])) ;
        return $response;
    }


}