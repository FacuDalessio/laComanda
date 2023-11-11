<?php
    use Psr\Http\Message\ServerRequestInterface as Request;
    use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
    use Slim\Psr7\Response;
    use Slim\Routing\RouteContext;

    class AsignarMozo{
        public function __invoke(Request $request, RequestHandler $handler): Response
    {   
        $datos = file_get_contents("php://input");
        $datosJson = json_decode($datos, true);


        if(isset($datosJson['idMesa']) && $datosJson['idMesa'] != null && isset($datosJson['idMozo']) && $datosJson['idMozo'] != null){

            $request = $request->withAttribute('idMesa', $datosJson['idMesa']);
            $request = $request->withAttribute('idMozo', $datosJson['idMozo']);
            $response = $handler->handle($request);
        }else{
            $response = new Response();
            $payload = json_encode(array("Error" => "Faltaron enviar datos"));
            $response->getBody()->write($payload);
        }

        return $response;
    }
    }
?>