<?php
    require_once './models/mesa.php';

    class MesaController{

        public function CargarUno($request, $response, $args)
    {
        $mesa = new Mesa();
        $mesa->setEstado("cerrada");
        $mesa->setSoftDelete(false);
        $mesa->crearMesa();

        $payload = json_encode(array("mensaje" => "Mesa creada con exito"));

        $response->getBody()->write($payload);
        return $response
        ->withHeader('Content-Type', 'application/json');
    }

        public function TraerTodos($request, $response, $args)
    {
        $lista = Mesa::obtenerTodos();
        $payload = json_encode(array("listaMesas" => $lista));

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }

        public function TraerMesasCerradas($request, $response, $args)
    {
        $lista = Mesa::obtenerMesasCerradas();
        $payload = json_encode(array("listaMesas" => $lista));

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }

    public function TraerUno($request, $response, $args)
    {
        $idMesa = $args['idMesa'];
        $mesa = Mesa::buscarUno($idMesa);
        if($mesa != false){
            $payload = json_encode($mesa);
        }else{
            $payload = json_encode(array("Error" => "No se encontro una mesa con ese id"));
        }
        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }

    public function BorrarUno($request, $response, $args)
    {
        $idMesa = $args['idMesa'];
        if (Mesa::buscarUno($idMesa) != false) {

            Mesa::borrarMesa($idMesa);
            $payload = json_encode(array("mensaje" => "Mesa borrado con exito"));
        }else{
            $payload = json_encode(array("Error" => "No se encontro una mesa con ese id"));
        }
        
        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }

    public function ModificarUno($request, $response, $args)
    {
        $mesa = $request->getAttribute('mesa');
        
        Mesa::modificarMesa($mesa);

        $payload = json_encode(array("mensaje" => "Mesa modificada con exito"));

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }
    }
?>