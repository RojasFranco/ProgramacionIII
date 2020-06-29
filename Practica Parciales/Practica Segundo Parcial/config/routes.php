<?php

use Slim\Routing\RouteCollectorProxy;

use App\Controllers\UsuarioController;
use App\Controllers\MascotaController;

use App\Middlewares\AfterMiddleware;        // LOS MIDDLEWARES QUE QUIERA USAR PARA RUTAS
use App\Middlewares\ValidarUsuarioMiddleware;
use App\Middlewares\ValidarTokenMiddleware;

return function($app){

    $app->post('/registro', UsuarioController::class . ':Insert')->add(new ValidarUsuarioMiddleware());   
/*    $app->group('/registro', function(RouteCollectorProxy $group){

        $group->post('/', UsuarioController::class . ':Insert')->add(new ValidarUsuarioMiddleware());   
    });*/

    $app->group('/login', function(RouteCollectorProxy $group){

        $group->post('/', UsuarioController::class . ':Login')->add(new ValidarUsuarioMiddleware());   
    });

    $app->group('/mascota', function(RouteCollectorProxy $group){

        $group->post('/', MascotaController::class . ':Insertar')->add(new ValidarTokenMiddleware());   
        $group->post('/{id}', MascotaController::class . ':prueba');   
    });
};