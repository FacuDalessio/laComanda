<?php
    class Producto implements JsonSerializable{
        private $id;
        private $nombre;
        private $stock;
        private $precio;
        private $categoria;

        public function getId() {
            return $this->id;
        }
    
        public function setId($id) {
            $this->id = $id;
        }
    
        public function getNombre() {
            return $this->nombre;
        }
    
        public function setNombre($nombre) {
            $this->nombre = $nombre;
        }
    
        public function getStock() {
            return $this->stock;
        }
    
        public function setStock($stock) {
            $this->stock = $stock;
        }
    
        public function getPrecio() {
            return $this->precio;
        }
    
        public function setPrecio($precio) {
            $this->precio = $precio;
        }
    
        public function getCategoria() {
            return $this->categoria;
        }
    
        public function setCategoria($categoria) {
            $this->categoria = $categoria;
        }

        public function jsonSerialize() {
            return [
                'id' => $this->id,
                'nombre' => $this->nombre,
                'stock' => $this->stock,
                'precio' => $this->precio,
                'categoria' => $this->categoria,
            ];
        }

        public function crearProducto()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("INSERT INTO productos (nombre, stock, precio, categoria)
         VALUES (:nombre, :stock, :precio, :categoria)");
        $consulta->bindValue(':nombre', $this->nombre, PDO::PARAM_STR);
        $consulta->bindValue(':stock', $this->stock, PDO::PARAM_INT);
        $consulta->bindValue(':precio', $this->precio, PDO::PARAM_STR);
        $consulta->bindValue(':categoria', $this->categoria, PDO::PARAM_STR);
        $consulta->execute();

        return $objAccesoDatos->obtenerUltimoId();
    }

        public static function obtenerTodos()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT * FROM productos");
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_CLASS, 'Producto');
    }

    public static function buscarUno($idProducto)
    {
        $objAccesoDato = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDato->prepararConsulta("SELECT * FROM productos WHERE id = :id");
        $consulta->bindValue(':id', $idProducto, PDO::PARAM_INT);
        $consulta->execute();
        return $consulta->fetchObject('Producto');
    }

    public static function borrarProducto($idProducto)
    {
        $objAccesoDato = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDato->prepararConsulta("DELETE FROM productos WHERE id = :id");
        $consulta->bindValue(':id', $idProducto, PDO::PARAM_INT);
        $consulta->execute();
    }

    public static function modificarProducto($producto)
    {
        $objAccesoDato = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDato->prepararConsulta("UPDATE productos SET nombre = :nombre, stock = :stock, precio = :precio, categoria = :categoria 
                                                WHERE id = :id");
        $consulta->bindValue(':nombre', $producto->nombre, PDO::PARAM_STR);
        $consulta->bindValue(':stock', $producto->stock, PDO::PARAM_INT);
        $consulta->bindValue(':precio', $producto->precio, PDO::PARAM_STR);
        $consulta->bindValue(':categoria', $producto->categoria, PDO::PARAM_STR);
        $consulta->bindValue(':id', $producto->id);
        $consulta->execute();
    }
}
?>