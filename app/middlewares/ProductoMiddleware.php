    <?php

    use Psr\Http\Message\ServerRequestInterface as Request;
    use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
    use Slim\Psr7\Response;
    use Slim\Routing\RouteContext;

    class CargarProducto
    {
        public function __invoke(Request $request, RequestHandler $handler): Response
        {   
            $body = $request->getParsedBody();

            if (isset($body['nombre']) && isset($body['stock']) && isset($body['precio']) && isset($body['categoria'])) {

                $response = $handler->handle($request);

            }else{
                $response = new Response();
                $payload = json_encode(array("mensaje" => "Falto enviar datos"));
                $response->getBody()->write($payload);
            }

            return $response->withHeader('Content-Type', 'application/json');
        }
    }

    class ModificarProducto
{
    public function __invoke(Request $request, RequestHandler $handler): Response
    {   
        $datos = file_get_contents("php://input");
        $datosJson = json_decode($datos, true);

        $atributos = ['nombre', 'stock', 'precio', 'categoria'];

        $routeContext = RouteContext::fromRequest($request);
        $route = $routeContext->getRoute();

        $producto = Producto::buscarUno($route->getArgument('idProducto'));

        if($producto != false){

            foreach($atributos as $atributo){
                if (isset($datosJson[$atributo]) && $datosJson[$atributo] != null) {

                    $setter = 'set' . ucfirst($atributo);

                    if (method_exists($producto, $setter)) {
                        
                        $producto->{$setter}($datosJson[$atributo]);
                    }
                }
            }
            $request = $request->withAttribute('producto', $producto);
            $response = $handler->handle($request);
        }else{
            $response = new Response();
            $payload = json_encode(array("Error" => "No se encontro un producto con ese id"));
            $response->getBody()->write($payload);
        }

        return $response->withHeader('Content-Type', 'application/json');
    }
}
?>