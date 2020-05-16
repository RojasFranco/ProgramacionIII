<?php
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;
use Slim\Routing\RoutingResults;

require_once __DIR__ . '/composer/vendor/autoload.php';
include_once './Clases/archivo.php';
include_once './Clases/marcaAgua.php';
include_once './Clases/tokenJwt.php';
include_once './Clases/respuestaJson.php';
include_once './Clases/Usuario.php';
include_once './Clases/Materia.php';
include_once './Clases/Profesor.php';
include_once './Clases/Asignacion.php';
$app = AppFactory::create();
$app->setBasePath("/practicaParcial2"); //***************  Cambiar Al nombre Que tenga *****************/


$app->post("/usuario", function(Request $request, Response $response){
    $ubicacionUsuarios = "./Archivos/users.xxx";
    $body = $request->getParsedBody();    

    if(isset($body["email"], $body["clave"])){
        $email=$body["email"];
        $clave=$body["clave"];
        $usuario = new Usuario($email, $clave);
        $manejadorArchivo = new Archivo($ubicacionUsuarios);
        $cantCaracteres = $manejadorArchivo->EscribirArchivo("w", $usuario);

        $respuesta = new RespuestaJson("ok", $cantCaracteres);
        $response->getBody()->write(json_encode($respuesta));
    }
    else{
        $response->getBody()->write("Complete mail y clave");
    }
    
    return $response
    ->withHeader('content-type', 'application/json');
});

$app->post("/login", function(Request $request, Response $response){
    $ubicacionUsuarios = "./Archivos/users.xxx";
    $body = $request->getParsedBody();    

    if(isset($body["email"], $body["clave"])){
        $email=$body["email"];
        $clave=$body["clave"];
        
        $manejadorArchivo = new Archivo($ubicacionUsuarios);
        $cuentaValida = $manejadorArchivo->ValidarCuenta($email, $clave);

        if($cuentaValida->valido){
            $guardar = new stdClass;
            $guardar->email = $email;
            $token = TokenJwt::SolicitarToken($guardar);
            $response->getBody()->write($token);
        }
        else{
            $response->getBody()->write("mail o clave invalidos");
        }
        //$respuesta = new RespuestaJson("ok", $cantCaracteres);
        
    }
    else{
        $response->getBody()->write("Complete mail y clave");
    }
    
    return $response
    ->withHeader('content-type', 'application/json');
});

$app->post("/materia", function(Request $request, Response $response){
    
    $body = $request->getParsedBody();    
    $headers = $request->getHeaders();
    $ubicacionMaterias = "./Archivos/materias.xxx";

    if(isset($body["nombre"], $body["cuatrimestre"],$headers["token"])){
        $nombre=$body["nombre"];
        $cuatrimestre=$body["cuatrimestre"];
        $token = $headers["token"][0];
        $materia = new Materia($nombre, $cuatrimestre);
        try{            
            TokenJwt::ValidarToken($token);            
            $manejadorArchivo = new Archivo($ubicacionMaterias);            
            $cantCaracteres = $manejadorArchivo->EscribirArchivo("w", $materia);
            $rta = new RespuestaJson("ok", $cantCaracteres);
            $response->getBody()->write(json_encode($rta));
        }
        catch(Exception $err){
            $response->getBody()->write($err->getMessage());
        }
    }
    else{
        $response->getBody()->write("Complete datos");
    }
    
    return $response
    ->withHeader('content-type', 'application/json');
});

$app->post("/profesor", function(Request $request, Response $response){
    
    $body = $request->getParsedBody();    
    $headers = $request->getHeaders();
    $ubicacionProfesores = "./Archivos/profesores.xxx";

    if(isset($body["nombre"], $body["legajo"],$headers["token"], $_FILES["imagen"])){
        $nombre=$body["nombre"];
        $legajo=$body["legajo"];
        $token = $headers["token"][0];
        $imagenKey = $_FILES["imagen"];
        $profesor = new Profesor($nombre, $legajo);
        try{            
            TokenJwt::ValidarToken($token);            
            $manejadorArchivo = new Archivo($ubicacionProfesores);   
            $esUnico = Profesor::esUnico($legajo, $manejadorArchivo->LeerArchivo("r"));
            if($esUnico){
                $cantCaracteres = $manejadorArchivo->EscribirArchivo("w", $profesor);
                Archivo::guardarImagen($imagenKey, "./Imagenes/");
                $rta = new RespuestaJson("ok", $cantCaracteres);
                $response->getBody()->write(json_encode($rta));
            }
            else{
                $response->getBody()->write("Este lejajo ya existe");
            }
        }
        catch(Exception $err){
            $response->getBody()->write($err->getMessage());
        }
    }
    else{
        $response->getBody()->write("Complete datos");
    }
    
    return $response
    ->withHeader('content-type', 'application/json');
});

$app->post("/asignacion", function(Request $request, Response $response){
    
    $body = $request->getParsedBody();    
    $headers = $request->getHeaders();
    $ubicacionMateriasProfesores = "./Archivos/materias-profesores.xxx";

    if(isset($body["id"], $body["legajo"],$headers["token"], $body["turno"])){
        $id=$body["id"];
        $legajo=$body["legajo"];
        $token = $headers["token"][0];
        $turno = $body["turno"];     
        try{            
            TokenJwt::ValidarToken($token);            
            $manejadorArchivo = new Archivo($ubicacionMateriasProfesores);   
            if($turno=="manana"|| $turno=="noche"){
                $listaAsignaciones = $manejadorArchivo->LeerArchivo("r");
                $seAgregoAsignacion = Asignacion::AgregarAsignacion($legajo, $turno, $id, $listaAsignaciones);
                if($seAgregoAsignacion==1){
                    $asignacion = new Asignacion($id, $legajo, $turno);
                    $cantCaracteres = $manejadorArchivo->EscribirArchivo("w", $asignacion);
                    $rta = new RespuestaJson("ok", $cantCaracteres);
                    $response->getBody()->write(json_encode($rta));
                }
                else if($seAgregoAsignacion==-1){
                    $response->getBody()->write("legajo o id materia inexistentes");
                }
                else{
                    $response->getBody()->write("Ya asignado");
                }
            }           
            else{
                $response->getBody()->write("Turno invalido");
            }
            
        }
        catch(Exception $err){
            $response->getBody()->write($err->getMessage());
        }
    }
    else{
        $response->getBody()->write("Complete datos");
    }
    
    return $response
    ->withHeader('content-type', 'application/json');
});

$app->get('/materia', function (Request $request, Response $response, $args) {
    $headers = $request->getHeaders();
    $ubicacionMaterias = "./Archivos/materias.xxx";
    if(isset($headers["token"])){
        $token = $headers["token"][0];

        try{
            TokenJwt::ValidarToken($token);
            $manejadorArchivo = new Archivo($ubicacionMaterias);
            $listaMaterias = $manejadorArchivo->LeerArchivo("r");
            $response->getBody()->write(json_encode($listaMaterias));
        }
        catch(Exception $err){
            $response->getBody()->write($err->getMessage());
        }
    }
    else{
        $response->getBody()->write("Ingrese token");
    }    
    return $response
    ->withHeader('content-type', 'application/json');
});

$app->get('/profesor', function (Request $request, Response $response, $args) {
    $headers = $request->getHeaders();
    $ubicacionProfesores = "./Archivos/profesores.xxx";
    if(isset($headers["token"])){
        $token = $headers["token"][0];

        try{
            TokenJwt::ValidarToken($token);
            $manejadorArchivo = new Archivo($ubicacionProfesores);
            $listaMaterias = $manejadorArchivo->LeerArchivo("r");
            $response->getBody()->write(json_encode($listaMaterias));
        }
        catch(Exception $err){
            $response->getBody()->write($err->getMessage());
        }
    }
    else{
        $response->getBody()->write("Ingrese token");
    }    
    return $response
    ->withHeader('content-type', 'application/json');
});

$app->get('/asignacion', function (Request $request, Response $response, $args) {
    $headers = $request->getHeaders();
    $ubicacionAsignaciones = "./Archivos/materias-profesores.xxx";
    if(isset($headers["token"])){
        $token = $headers["token"][0];

        try{
            TokenJwt::ValidarToken($token);
            $manejadorArchivo = new Archivo($ubicacionAsignaciones);
            $listaAsignaciones = $manejadorArchivo->LeerArchivo("r");
            $rta = Asignacion::MostrarAsignacion($listaAsignaciones);
            //$response->getBody()->write(json_encode($listaMaterias));
            $response->getBody()->write(json_encode($rta));
        }
        catch(Exception $err){
            $response->getBody()->write($err->getMessage());
        }
    }
    else{
        $response->getBody()->write("Ingrese token");
    }    
    return $response
    ->withHeader('content-type', 'application/json');
});

$app->run();