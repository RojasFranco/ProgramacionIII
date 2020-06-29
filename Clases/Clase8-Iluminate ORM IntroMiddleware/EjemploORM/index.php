<?php

require __DIR__ . '/vendor/autoload.php';
require './config/capsule.php';
require './models/alumno.php';

use Illuminate\Database\Capsule\Manager as Capsule;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;
use Slim\Exception\NotFoundException;

$app = AppFactory::create();
$app->setBasePath('/prog3');
$app->addErrorMiddleware(true, false, false);

$app->get('/alumnos', function (Request $request, Response $response) {

    // CREAR ALUMNO
    // $alumno = new Alumno;
    // $alumno->alumno = 'Nuevo alumno';
    // $alumno->legajo = 6666;
    // $alumno->localidad = 4;
    // $alumno->cuatrimestre = 2;

    // $alumno->save();
    // MODIFICAR / BORRAR
    $alumno = Alumno::find(14);
    $alumno->alumno = 'Nuevo alumno';

    // $alumno->delete;
    $response->getBody()->write(json_encode($alumno->save()));
    return $response;
});

$app->get('/', function (Request $request, Response $response) {
    // $users = Capsule::table('alumnos')->get();
    $users = Capsule::table('alumnos')
        ->where('legajo', '>', 1238)
        ->select(['id', 'legajo', 'alumno'])
        ->get();

    $response->getBody()->write(json_encode($users));
    return $response;
});

$app->get('/join', function (Request $request, Response $response) {
    // $users = Capsule::table('alumnos')->get();
    $users = Capsule::table('alumnos')
        ->join('localidades', 'localidades.id', '=', 'alumnos.localidad')
        ->join('cuatrimestres', 'cuatrimestres.id', '=', 'alumnos.cuatrimestre')
        ->get();

    $response->getBody()->write(json_encode($users));
    return $response;
});

$app->get('/increment', function (Request $request, Response $response) {
    // $users = Capsule::table('alumnos')->get();
    $users = Capsule::table('alumnos')
    ->decrement('localidad');

    $response->getBody()->write(json_encode($users));
    return $response;
});

$app->get('/schema', function (Request $request, Response $response) {
    $schema = Capsule::schema()->create('users', function ($table) {
        $table->increments('id');
        $table->string('email')->unique();
        $table->timestamps();
    });
    
    $response->getBody()->write(json_encode($schema));
    return $response;
});

$app->run();
