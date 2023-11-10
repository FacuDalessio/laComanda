<?php

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Psr7\Response;
use Slim\Routing\RouteContext;

class ModificarTrabajador
{
    public function __invoke(Request $request, RequestHandler $handler): Response
    {   
        // $body = $request->getParsedBody();
        $idMesa = $_POST['idMesa'];

        var_dump($idMesa);

        $atributos = ['nombre', 'rol', 'sector', 'idMesa', 'pendientes', 'idPedido', 'ingresoSistema'];

        $routeContext = RouteContext::fromRequest($request);
        $route = $routeContext->getRoute();

        $trabajador = Trabajador::buscarUno($route->getArgument('idTrabajador'));

        if($trabajador != false){
            // $trabajadorArray = json_decode($trabajador);
            foreach($atributos as $atributo){
                if (isset($body[$atributo]) && $body[$atributo] != null) {  
                    echo "entro al if"; 
                }
            }
    
            $response = $handler->handle($request);
        }else{
            $response = new Response();
            $payload = json_encode(array("Error" => "No se encontro un trabajador con ese id"));
            $response->getBody()->write($payload);
        }

        return $response->withHeader('Content-Type', 'application/json');
    }
}