<?php
    require_once './models/pedido.php';
    require_once './models/mesa.php';
    require_once './models/trabajador.php';

    class PedidoController{

        public function CargarUno($request, $response, $args)
    {
        $body = $request->getParsedBody();

        $pedido = new Pedido();
        $pedido->setIdMesa($body['idMesa']);
        $pedido->setIdBartender($body['idBartender']);
        $pedido->setIdCerveceros($body['idCerveceros']);
        $pedido->setIdCocineros($body['idCocineros']);
        $pedido->setIdMozos($body['idMozos']);
        $pedido->setImporte($body['importe']);
        $pedido->setEstado($body['estado']);
        $pedido->setTiempo($body['tiempo']);
        $pedido->setProductos($body['productos']);
        $pedido->setFecha($body['fecha']);

        $idPedido = $pedido->crearPedido();

        $mesa = Mesa::buscarUno($body['idMesa']);
        $mesa->setIdPedido($idPedido);
        $mesa->setEstado('con cliente esperando pedido');
        Mesa::modificarMesa($mesa);

        $mozo = Trabajador::buscarUno($body['idMozos']);
        $mozo->setIdPedido($idPedido);
        Trabajador::modificarTrabajador($mozo);

        $payload = json_encode(array("mensaje" => "Pedido creado con exito"));

        $response->getBody()->write($payload);
        return $response
        ->withHeader('Content-Type', 'application/json');
    }

    public function TraerTodos($request, $response, $args)
    {
        $lista = Pedido::obtenerTodos();
        $payload = json_encode(array("listaPedidos" => $lista));

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }
    public function TraerUno($request, $response, $args)
    {
        $idPedido = $args['idPedido'];
        $pedido = Pedido::buscarUno($idPedido);
        if($pedido != false){
            $payload = json_encode($pedido);
        }else{
            $payload = json_encode(array("Error" => "No se encontro un pedido con ese id"));
        }
        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }

    public function BorrarUno($request, $response, $args)
    {
        $idPedido = $args['idPedido'];
        if (Pedido::buscarUno($idPedido) != false) {

            Pedido::borrarPedido($idPedido);
            $payload = json_encode(array("mensaje" => "Pedido borrado con exito"));
        }else{
            $payload = json_encode(array("Error" => "No se encontro un pedido con ese id"));
        }
        
        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }

    public function ModificarUno($request, $response, $args)
    {
        $pedido = $request->getAttribute('pedido');
        
        Pedido::modificarPedido($pedido);

        $payload = json_encode(array("mensaje" => "Pedido modificado con exito"));

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }
    }
?>