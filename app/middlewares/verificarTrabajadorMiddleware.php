<?php

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Psr7\Response;
require_once './utils/autentificadorJWT.php';

class VerificarSocio
{
    public function __invoke(Request $request, RequestHandler $handler): Response
    {   
        $token = $request->getAttribute('token');
        
        $data = AutentificadorJWT::ObtenerData($token);
        $rol = $data->rol;

        if ($rol === 'socio') {
            $response = $handler->handle($request);
        } else {
            $response = new Response();
            $payload = json_encode(array("mensaje" => "No sos Socio"));
            $response->getBody()->write($payload);
        }
        return $response->withHeader('Content-Type', 'application/json');
    }
}

class VerificarMozo
{
    public function __invoke(Request $request, RequestHandler $handler): Response
    {   
        $token = $request->getAttribute('token');
        
        $data = AutentificadorJWT::ObtenerData($token);
        $rol = $data->rol;

        if ($rol === 'mozo') {
            $response = $handler->handle($request);
        } else {
            $response = new Response();
            $payload = json_encode(array("mensaje" => "No sos mozo"));
            $response->getBody()->write($payload);
        }
        return $response->withHeader('Content-Type', 'application/json');
    }
}
