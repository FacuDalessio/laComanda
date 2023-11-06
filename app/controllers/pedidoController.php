<?php
    require_once './models/pedido.php';

    class PedidoController{

        public function CargarUno($request, $response, $args)
    {
        $body = $request->getParsedBody();

        
       if (isset($body['idMesa']) && isset($body['idBartender']) && isset($body['idCerveceros']) && isset($body['idCocineros']) && isset($body['idMozos']) &&
            isset($body['importe']) && isset($body['estado']) && isset($body['tiempo']) && isset($body['productos']) && isset($body['fecha'])) {

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

            $pedido->crearPedido();

            $payload = json_encode(array("mensaje" => "Pedido creado con exito"));

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
        $lista = Pedido::obtenerTodos();
        $payload = json_encode(array("listaPedidos" => $lista));

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }
    }
?>