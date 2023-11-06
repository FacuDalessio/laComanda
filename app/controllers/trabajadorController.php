<?php
    require_once './models/trabajador.php';

    class TrabajadorController{

        public function CargarUno($request, $response, $args)
    {
        $body = $request->getParsedBody();

        
       if (isset($body['nombre']) && isset($body['rol'])) {
            $trabajador = new Trabajador();

            $trabajador->setNombre($body['nombre']);
            $trabajador->setRol($body['rol']);
            $trabajador->setPendientes(0);
            $trabajador->setSoftDelete(false);

            $trabajador->crearTrabajador();

            $payload = json_encode(array("mensaje" => "Trabajador creado con exito"));

            $response->getBody()->write($payload);
            return $response
            ->withHeader('Content-Type', 'application/json');
       }else{
            $payload = json_encode(array("error" => "Faltan enviar datos"));

            $response->getBody()->write($payload);
            return $response
            ->withHeader('Content-Type', 'application/json');
       }
    }

    public function TraerTodos($request, $response, $args)
    {
        $lista = Trabajador::obtenerTodos();
        $payload = json_encode(array("listaTrabajadores" => $lista));

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }
    }
?>