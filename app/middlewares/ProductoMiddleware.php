    <?php

    use Psr\Http\Message\ServerRequestInterface as Request;
    use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
    use Slim\Psr7\Response;

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