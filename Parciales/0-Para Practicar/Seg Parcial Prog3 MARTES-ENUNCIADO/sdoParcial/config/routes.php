<?php
use Slim\Routing\RouteCollectorProxy;

//  ACA LOS CONTROLLERS
use App\Controllers\EsqueletoController;
use App\Controllers\MateriaController;
use App\Controllers\UsuarioController;

//ACA LOS MIDDLEWARES QUE QUIERA USAR EN RUTAS ESPECIFICAS
use App\Middlewares\AfterMiddleware;        
use App\Middlewares\ValidarTokenMiddleware;

return function($app){


    //$app->get('/algunoParticular', EsqueletoController::class . 'registrar');


    $app->post('/usuario', UsuarioController::class . ':Insertar');
    $app->post('/login', UsuarioController::class . ':Login');

    $app->group('/materias', function(RouteCollectorProxy $group){
        $group->get('/{id}', MateriaController::class . ':GetMaterias');
        $group->post('[/]', MateriaController::class . ':Insertar');
        $group->put('/{id}/{profesor}', MateriaController::class . ':UpdateProfesor');
        $group->put('/{id}', MateriaController::class . ':InscribirMateria');
        $group->get('[/]', MateriaController::class . ':GetTodas');
    })->add(new ValidarTokenMiddleware());
};