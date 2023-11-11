<?php
    use Psr\Http\Message\ServerRequestInterface as Request;
    use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
    use Slim\Psr7\Response;
    use Slim\Routing\RouteContext;

    class Sentarse{
        public function __invoke(Request $request, RequestHandler $handler): Response {
            $datos = file_get_contents("php://input");
            $datosJson = json_decode($datos, true);

            if(isset($datosJson['cliente'])){

                $request = $request->withAttribute('cliente', $datosJson['cliente']);
                $response = $handler->handle($request);

            }else{
                $response = new Response();
                $payload = json_encode(array("Error" => "No se encontro una mesa con ese id"));
                $response->getBody()->write($payload);
            }

            return $response->withHeader('Content-Type', 'application/json');
        }
    }
?>