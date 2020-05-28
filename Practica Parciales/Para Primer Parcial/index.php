<?php
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;
require_once __DIR__ . '/composer/vendor/autoload.php';
include_once './Clases/archivo.php';
include_once './Clases/marcaAgua.php';
include_once './Clases/tokenJwt.php';
include_once './Clases/respuestaJson.php';

$app = AppFactory::create();
$app->setBasePath("/practicaParcial2"); //***************  Cambiar Al nombre Que tenga *****************/


//$headers = $request->getHeaders();
//$token = $headers["token"][0];

$app->get('/path_info/{parametroPorURL}', function (Request $request, Response $response, $args) {
    $queryParams = $request->getQueryParams(); //--->Los que vienen por parametro
    //$args ---->Los que vienen en URL
        
    //$response->getBody()->write("Hello world!");
    $response->getBody()->write(json_encode($args));
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