<?php

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Psr7\Response;
use Slim\Routing\RouteContext;

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

class ModificarTrabajador
{
    public function __invoke(Request $request, RequestHandler $handler): Response
    {   
        $datos = file_get_contents("php://input");
        $datosJson = json_decode($datos, true);

        $atributos = ['nombre', 'rol', 'sector', 'idMesa', 'pendientes', 'idPedido', 'ingresoSistema'];

        $routeContext = RouteContext::fromRequest($request);
        $route = $routeContext->getRoute();

        $trabajador = Trabajador::buscarUno($route->getArgument('idTrabajador'));

        if($trabajador != false){

            foreach($atributos as $atributo){
                if (isset($datosJson[$atributo]) && $datosJson[$atributo] != null) {

                    $setter = 'set' . ucfirst($atributo);

                    if (method_exists($trabajador, $setter)) {
                        
                        $trabajador->{$setter}($datosJson[$atributo]);
                    }
                }
            }
            $request = $request->withAttribute('trabajador', $trabajador);
            $response = $handler->handle($request);
        }else{
            $response = new Response();
            $payload = json_encode(array("Error" => "No se encontro un trabajador con ese id"));
            $response->getBody()->write($payload);
        }

        return $response->withHeader('Content-Type', 'application/json');
    }
}

class FiltrarMozos
{
    public function __invoke(Request $request, RequestHandler $handler): Response
    {   
        
        $response = $handler->handle($request);

        
        $bodyContent = (string) $response->getBody();
        $responseData = json_decode($bodyContent, true);
        
        $mozos = array_filter($responseData['listaTrabajadores'], function ($trabajador) {
            return $trabajador['rol'] === 'mozo';
        });

        
        $filteredData = ['listaTrabajadores' => $mozos];

        
        $filteredJson = json_encode($filteredData, JSON_PRETTY_PRINT);

        $filteredResponse = new Response();
        $filteredResponse->getBody()->write($filteredJson);

        return $filteredResponse;
        
    }
}
?>