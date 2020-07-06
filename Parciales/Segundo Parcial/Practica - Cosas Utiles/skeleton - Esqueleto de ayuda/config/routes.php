<?php
use Slim\Routing\RouteCollectorProxy;

//  ACA LOS CONTROLLERS
use App\Controllers\EsqueletoController;


//ACA LOS MIDDLEWARES QUE QUIERA USAR EN RUTAS ESPECIFICAS
use App\Middlewares\AfterMiddleware;        
use App\Middlewares\ValidarTokenMiddleware;

return function($app){


    //$app->get('/algunoParticular', EsqueletoController::class . 'registrar');

    $app->group('/algunGroup', function(RouteCollectorProxy $group){
        $group->get('[/]', EsqueletoController::class . ':GetAll');//->add(new AfterMiddleware());    PARA EJECUTAR MIDDLEWARE
        $group->get('/:id', EsqueletoController::class . ':GetAll');
        $group->post('/', EsqueletoController::class . ':GetAll');
        $group->put('/', EsqueletoController::class . ':GetAll');
        $group->delete('/', EsqueletoController::class . ':GetAll');        
    });
};