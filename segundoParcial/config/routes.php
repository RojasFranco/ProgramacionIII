<?php
use Slim\Routing\RouteCollectorProxy;

//  ACA LOS CONTROLLERS
use App\Controllers\UsuarioController;
use App\Controllers\MascotaController;
use App\Controllers\TurnoController;


//ACA LOS MIDDLEWARES QUE QUIERA USAR EN RUTAS ESPECIFICAS
use App\Middlewares\AfterMiddleware;        
use App\Middlewares\ValidarTokenMiddleware;

return function($app){


    $app->post('/registro', UsuarioController::class . ':Registrar');

    $app->post('/login', UsuarioController::class . ':Login');

    $app->post('/tipo_mascota', MascotaController::class . ':CargarTipo');

    $app->post('/mascotas', MascotaController::class . ':Insertar')->add(new ValidarTokenMiddleware());


    
    $app->group('/turnos', function(RouteCollectorProxy $group){

        $group->post('[/mascota]', TurnoController::class . ':PedirTurno');//->add(new AfterMiddleware());    PARA EJECUTAR MIDDLEWARE
        $group->get('/:id', TurnoController::class . ':VerTurnos');
        $group->post('/', EsqueletoController::class . ':Insert');
        $group->put('/', EsqueletoController::class . ':Update');
        $group->delete('/', EsqueletoController::class . ':Delete');        
    })->add(new ValidarTokenMiddleware());
};