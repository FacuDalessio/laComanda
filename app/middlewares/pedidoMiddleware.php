<?php

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Psr7\Response;
use Slim\Routing\RouteContext;

class CargarPedido
{
    public function __invoke(Request $request, RequestHandler $handler): Response
    {   
        $body = $request->getParsedBody();

        if (isset($body['idMesa']) && isset($body['idMozos'])) {

            $response = $handler->handle($request);

        }else{
            $response = new Response();
            $payload = json_encode(array("mensaje" => "Falto enviar datos"));
            $response->getBody()->write($payload);
        }

        return $response->withHeader('Content-Type', 'application/json');
    }
}

class ModificarPedido
{
    public function __invoke(Request $request, RequestHandler $handler): Response
    {   
        $datos = file_get_contents("php://input");
        $datosJson = json_decode($datos, true);

        $atributos = ['idMesa', 'idBartender', 'idCocineros', 'idMozos', 'importe', 'estado', 'tiempo', 'productos'];

        $routeContext = RouteContext::fromRequest($request);
        $route = $routeContext->getRoute();

        $pedido = Pedido::buscarUno($route->getArgument('idPedido'));

        if($pedido != false){

            foreach($atributos as $atributo){
                if (isset($datosJson[$atributo]) && $datosJson[$atributo] != null) {

                    $setter = 'set' . ucfirst($atributo);

                    if (method_exists($pedido, $setter)) {
                        
                        $pedido->{$setter}($datosJson[$atributo]);
                    }
                }
            }
            $request = $request->withAttribute('pedido', $pedido);
            $response = $handler->handle($request);
        }else{
            $response = new Response();
            $payload = json_encode(array("Error" => "No se encontro un pedido con ese id"));
            $response->getBody()->write($payload);
        }

        return $response->withHeader('Content-Type', 'application/json');
    }
}
class CargarDetallePedido
{
    public function __invoke(Request $request, RequestHandler $handler): Response
    {   
        $body = $request->getParsedBody();

        if (isset($body['idPedido']) && isset($body['idProducto']) && isset($body['cantidad'])) {

            $response = $handler->handle($request);

        }else{
            $response = new Response();
            $payload = json_encode(array("mensaje" => "Falto enviar datos"));
            $response->getBody()->write($payload);
        }

        return $response->withHeader('Content-Type', 'application/json');
    }
}
?>