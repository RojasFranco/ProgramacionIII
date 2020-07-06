<?php

use Slim\App;
use App\Middlewares\AfterMiddleware;    //SI LO NECESITO


return function($app){
    $app->addBodyParsingMiddleware();


    //$app->add(new AfterMiddleware());     SI QUIERO QUE SE EJECUTE EN TODAS LAS RESPUESTAS
};