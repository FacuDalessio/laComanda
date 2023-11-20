<?php
require_once './models/pedido.php';
require_once './models/mesa.php';
require_once './models/trabajador.php';
require_once './models/producto.php';
require_once './models/pendientes.php';

class PedidoController
{

    public function CargarUno($request, $response, $args)
    {
        $body = $request->getParsedBody();

        $pedido = new Pedido();
        $pedido->setIdMesa($body['idMesa']);
        $pedido->setIdMozos($body['idMozos']);
        $pedido->setEstado('pendiente');
        $pedido->setImporte(0);
        $pedido->setTiempo(0);
        $pedido->setFecha(date('d/m/Y H:i'));

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
        if ($pedido != false) {
            $payload = json_encode($pedido);
        } else {
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
        } else {
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

    public function cargarProductoAPedido($request, $response, $args){

        $body = $request->getParsedBody();
        $idPedido = $body['idPedido'];
        $idProducto = $body['idProducto'];
        $cantidad = $body['cantidad'];

        Pedido::cargarDetallePedido($idPedido, $idProducto, $cantidad);

        $producto = Producto::buscarUno($idProducto);

        $pendientes = new Pendientes();
        $pendientes->setIdPedido($idPedido);
        $pendientes->setSector($producto->getCategoria());
        $pendientes->setIdProducto($idProducto);
        $pendientes->setCantidad($cantidad);
        $pendientes->crearPendiente();

        $pedido = Pedido::buscarUno($idPedido);
        $importe = $pedido->getImporte();
        $importe+= $producto->getPrecio() * $cantidad;
        $pedido->setImporte($importe);
        Pedido::modificarPedido($pedido);

        $payload = json_encode(array("mensaje" => "Producto cargado exitoso"));

        $response->getBody()->write($payload);
        return $response
            ->withHeader('Content-Type', 'application/json');
    }

    public function listarPendientes($request, $response, $args)
    {
        $lista = Pendientes::obtenerTodos();
        $payload = json_encode(array("listaPendientes" => $lista));
        $response->getBody()->write($payload);
        return $response;
    }
}
