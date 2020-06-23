<?php
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;
use Illuminate\Database\Capsule\Manager as Capsule;
require_once __DIR__ . '/composer/vendor/autoload.php';
require_once './config/capsule.php';
include_once './Clases/archivo.php';
include_once './Clases/marcaAgua.php';
include_once './Clases/tokenJwt.php';
include_once './Clases/respuestaJson.php';

$app = AppFactory::create();
$app->setBasePath("/Iluminate"); //***************  Cambiar Al nombre Que tenga *****************/


//$headers = $request->getHeaders();
//$token = $headers["token"][0];

$app->get('/pepe', function (Request $request, Response $response, $args) {
    $queryParams = $request->getQueryParams(); //--->Los que vienen por parametro
    //$args ---->Los que vienen en URL
        
    $envios = Capsule::table('envios')
    ->find(1)    ;
    //->pluck('pNumero');


    $response->getBody()->write(json_encode($envios));
    return $response
    ->withHeader('content-type', 'application/json');
});

$app->post("/path_info", function(Request $request, Response $response){
    $body = $request->getParsedBody();    
    $response->getBody()->write($body["asdasd"]);
    return $response
    ->withHeader('content-type', 'application/json');
});

$app->run();