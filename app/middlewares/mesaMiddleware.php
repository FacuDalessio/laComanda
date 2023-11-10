<?php

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Psr7\Response;
use Slim\Routing\RouteContext;

class ModificarMesa
{
    public function __invoke(Request $request, RequestHandler $handler): Response
    {   
        $datos = file_get_contents("php://input");
        $datosJson = json_decode($datos, true);

        $atributos = ['cliente', 'idPedido', 'estado', 'idMozo'];

        $routeContext = RouteContext::fromRequest($request);
        $route = $routeContext->getRoute();

        $mesa = Mesa::buscarUno($route->getArgument('idMesa'));

        if($mesa != false){

            foreach($atributos as $atributo){
                if (isset($datosJson[$atributo]) && $datosJson[$atributo] != null) {

                    $setter = 'set' . ucfirst($atributo);

                    if (method_exists($mesa, $setter)) {
                        
                        $mesa->{$setter}($datosJson[$atributo]);
                    }
                }
            }
            $request = $request->withAttribute('mesa', $mesa);
            $response = $handler->handle($request);
        }else{
            $response = new Response();
            $payload = json_encode(array("Error" => "No se encontro una mesa con ese id"));
            $response->getBody()->write($payload);
        }

        return $response->withHeader('Content-Type', 'application/json');
    }
}