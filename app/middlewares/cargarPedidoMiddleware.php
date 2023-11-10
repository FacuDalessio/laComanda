<?php

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Psr7\Response;

class CargarPedido
{
    public function __invoke(Request $request, RequestHandler $handler): Response
    {   
        $body = $request->getParsedBody();

        if (isset($body['idMesa']) && isset($body['idBartender']) && isset($body['idCerveceros']) && isset($body['idCocineros']) && isset($body['idMozos']) &&
            isset($body['importe']) && isset($body['estado']) && isset($body['tiempo']) && isset($body['productos']) && isset($body['fecha'])) {

            $response = $handler->handle($request);

        }else{
            $response = new Response();
            $payload = json_encode(array("mensaje" => "Falto enviar datos"));
            $response->getBody()->write($payload);
        }

        return $response->withHeader('Content-Type', 'application/json');
    }
}