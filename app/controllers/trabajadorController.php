<?php
    require_once './models/trabajador.php';

    class TrabajadorController{

        public function CargarUno($request, $response, $args)
    {
        $body = $request->getParsedBody();
       
        $trabajador = new Trabajador();

        $trabajador->setNombre($body['nombre']);
        $trabajador->setRol($body['rol']);
        $trabajador->setSector($body['sector']);
        $trabajador->setPendientes(0);
        $trabajador->setSoftDelete(false);

        $trabajador->crearTrabajador();

        $payload = json_encode(array("mensaje" => "Trabajador creado con exito"));

        $response->getBody()->write($payload);
        return $response
        ->withHeader('Content-Type', 'application/json');
    }

    public function TraerTodos($request, $response, $args)
    {
        $lista = Trabajador::obtenerTrabajadoresActivos();
        $payload = json_encode(array("listaTrabajadores" => $lista));

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }

    public function TraerUno($request, $response, $args)
    {
        $trabajadorId = $args['idTrabajador'];
        $trabajador = Trabajador::buscarUno($trabajadorId);
        if($trabajador != false){
            $payload = json_encode($trabajador);
        }else{
            $payload = json_encode(array("Error" => "No se encontro un trabajador con ese id"));
        }
        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }

    public function BorrarUno($request, $response, $args)
    {
        $trabajadorId = $args['idTrabajador'];
        if (Trabajador::buscarUno($trabajadorId) != false) {

            Trabajador::borrarTrabajador($trabajadorId);
            $payload = json_encode(array("mensaje" => "Trabajador borrado con exito"));
        }else{
            $payload = json_encode(array("Error" => "No se encontro un trabajador con ese id"));
        }
        
        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }

    public function ModificarUno($request, $response, $args)
    {
        $trabajadorId = $args['idTrabajador'];

        $payload = json_encode(array("mensaje" => "Usuario modificado con exito"));

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }
}
