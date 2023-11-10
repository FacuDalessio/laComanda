<?php
    require_once './models/producto.php';

    class ProductoController{

        public function CargarUno($request, $response, $args)
    {
        $body = $request->getParsedBody();
        
        $producto = new Producto();

        $producto->setNombre($body['nombre']);
        $producto->setStock($body['stock']);
        $producto->setPrecio($body['precio']);
        $producto->setCategoria($body['categoria']);

        $producto->crearProducto();

        $payload = json_encode(array("mensaje" => "Producto creado con exito"));

        $response->getBody()->write($payload);
        return $response
        ->withHeader('Content-Type', 'application/json');
    }

    public function TraerTodos($request, $response, $args)
    {
        $lista = Producto::obtenerTodos();
        $payload = json_encode(array("listaProductos" => $lista));

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }

    public function BorrarUno($request, $response, $args)
    {
        $idProducto = $args['idProducto'];
        if (Producto::buscarUno($idProducto) != false) {

            Producto::borrarProducto($idProducto);
            $payload = json_encode(array("mensaje" => "Producto borrado con exito"));
        }else{
            $payload = json_encode(array("Error" => "No se encontro un producto con ese id"));
        }
        
        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }
    public function TraerUno($request, $response, $args)
    {
        $idProducto = $args['idProducto'];
        $producto = Producto::buscarUno($idProducto);
        if($producto != false){
            $payload = json_encode($producto);
        }else{
            $payload = json_encode(array("Error" => "No se encontro un producto con ese id"));
        }
        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }
    }
?>