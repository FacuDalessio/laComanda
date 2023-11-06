<?php
    require_once './models/producto.php';

    class ProductoController{

        public function CargarUno($request, $response, $args)
    {
        $body = $request->getParsedBody();

        
       if (isset($body['nombre']) && isset($body['stock']) && isset($body['precio']) && isset($body['categoria'])) {
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
       }else{
            $payload = json_encode(array("error" => "Faltan enviar datos"));

            $response->getBody()->write($payload);
            return $response
            ->withHeader('Content-Type', 'application/json');
       }
    }

    public function TraerTodos($request, $response, $args)
    {
        $lista = Producto::obtenerTodos();
        $payload = json_encode(array("listaProductos" => $lista));

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }
    }
?>