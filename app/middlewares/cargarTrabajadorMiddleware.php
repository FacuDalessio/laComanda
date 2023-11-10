<?php

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Psr7\Response;

class CargarTrabajador
{
    public function __invoke(Request $request, RequestHandler $handler): Response
    {   
        $body = $request->getParsedBody();

        if (isset($body['nombre']) && isset($body['rol']) && isset($body['sector'])) {

            $response = $handler->handle($request);

        }else{
            $response = new Response();
            $payload = json_encode(array("mensaje" => "Falto enviar datos"));
            $response->getBody()->write($payload);
        }

        return $response->withHeader('Content-Type', 'application/json');
    }
}