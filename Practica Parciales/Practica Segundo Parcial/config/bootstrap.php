<?php
require_once __DIR__ . '/../vendor/autoload.php';

use Slim\Factory\AppFactory;
use Config\Database;

new Database();
$app = AppFactory::create();
$app->setBasePath("/practicaSegundoParcial/public");

// REGISTRO DE RUTAS

(require_once __DIR__ . '/routes.php')($app);


// REGISTRO DE MIDDLEWARES
(require_once __DIR__ . '/middlewares.php')($app);


// PARA MANEJO DE ERRORES
use Psr\Http\Message\ServerRequestInterface;

$app->addRoutingMiddleware();
$customErrorHandler = function (
    ServerRequestInterface $request,
    Throwable $exception,
    bool $displayErrorDetails,
    bool $logErrors,
    bool $logErrorDetails
) use ($app) {
    
    $payload = ['error' => $exception->getMessage()];

    $response = $app->getResponseFactory()->createResponse();
    $response->getBody()->write(
        json_encode($payload, JSON_UNESCAPED_UNICODE)
    );

    return $response;
};

// Add Error Middleware
$errorMiddleware = $app->addErrorMiddleware(true, true, true);
$errorMiddleware->setDefaultErrorHandler($customErrorHandler);


return $app;