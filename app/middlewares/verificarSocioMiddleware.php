<?php

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Psr7\Response;

class VerificarSocio
{
    public function __invoke(Request $request, RequestHandler $handler): Response
    {   
        $parametros = $request->getQueryParams();

        if (isset($parametros['rol'])) {
            $rol = $parametros['rol'];

            if ($rol === 'socio') {
                $response = $handler->handle($request);
            } else {
                $response = new Response();
                $payload = json_encode(array("mensaje" => "No sos Socio"));
                $response->getBody()->write($payload);
            }
        }else{
            $response = new Response();
            $payload = json_encode(array("mensaje" => "No enviaste el rol"));
            $response->getBody()->write($payload);
        }
        return $response->withHeader('Content-Type', 'application/json');
    }
}
