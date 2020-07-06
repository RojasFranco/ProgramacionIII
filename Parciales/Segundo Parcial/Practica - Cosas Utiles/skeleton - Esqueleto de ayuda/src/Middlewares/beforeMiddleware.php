<?php
namespace App\Middlewares;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Psr7\Response;

class ExampleBeforeMiddleware
{
    /**
     * Example middleware invokable class
     *
     * @param  ServerRequest  $request PSR-7 request
     * @param  RequestHandler $handler PSR-15 request handler
     *
     * @return Response
     */
    public function __invoke(Request $request, RequestHandler $handler): Response
    {
        //  VALIDACION
        //$esValido = Validar::Usuario();
        /*
        if($esvalido) {
            $response = $handler->handle($request);            //OJO esta linea va al controlleR. Hacer validacion antes
            $existingContent = (string) $response->getBody();    
            $response = new Response();
            $response->getBody()->write($existingContent); 
            return $response;
        }
        throw new \Slim\Exception\HttpBadRequestException($request);
         
         */
        
    }
}