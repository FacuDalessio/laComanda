<?php
class Pedido implements JsonSerializable
{
    private $id;
    private $idMesa;
    private $idBartender;
    private $idCerveceros;
    private $idCocineros;
    private $idMozos;
    private $importe;
    private $estado;
    private $tiempo;
    private $productos;
    private $fecha;
    private $imagen;

    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    public function getIdMesa()
    {
        return $this->idMesa;
    }

    public function setIdMesa($idMesa)
    {
        $this->idMesa = $idMesa;
    }

    public function getIdBartender()
    {
        return $this->idBartender;
    }

    public function setIdBartender($idBartender)
    {
        $this->idBartender = $idBartender;
    }

    public function getIdCerveceros()
    {
        return $this->idCerveceros;
    }

    public function setIdCerveceros($idCerveceros)
    {
        $this->idCerveceros = $idCerveceros;
    }

    public function getIdCocineros()
    {
        return $this->idCocineros;
    }

    public function setIdCocineros($idCocineros)
    {
        $this->idCocineros = $idCocineros;
    }

    public function getIdMozos()
    {
        return $this->idMozos;
    }

    public function setIdMozos($idMozos)
    {
        $this->idMozos = $idMozos;
    }

    public function getImporte()
    {
        return $this->importe;
    }

    public function setImporte($importe)
    {
        $this->importe = $importe;
    }

    public function getEstado()
    {
        return $this->estado;
    }

    public function setEstado($estado)
    {
        $this->estado = $estado;
    }

    public function getTiempo()
    {
        return $this->tiempo;
    }

    public function setTiempo($tiempo)
    {
        $this->tiempo = $tiempo;
    }

    public function getProductos()
    {
        return $this->productos;
    }

    public function setProductos($productos)
    {
        $this->productos = $productos;
    }

    public function getFecha()
    {
        return $this->fecha;
    }

    public function setFecha($fecha)
    {
        $this->fecha = $fecha;
    }

    public function getImagen()
    {
        return $this->imagen;
    }

    public function setImagen($imagen)
    {
        $this->imagen = $imagen;
    }

    public function jsonSerialize()
    {
        return [
            'id' => $this->id,
            'idMesa' => $this->idMesa,
            'idBartender' => $this->idBartender,
            'idCerveceros' => $this->idCerveceros,
            'idCocineros' => $this->idCocineros,
            'idMozos' => $this->idMozos,
            'importe' => $this->importe,
            'estado' => $this->estado,
            'tiempo' => $this->tiempo,
            'productos' => $this->productos,
            'fecha' => $this->fecha,
            'imagen' => $this->imagen,
        ];
    }

    public function crearPedido()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("INSERT INTO pedidos (idMesa, idBartender, idCerveceros, idCocineros, idMozos, importe,
             estado, tiempo, productos, fecha, imagen) VALUES (:idMesa, :idBartender, :idCerveceros, :idCocineros, :idMozos, :importe, :estado, 
             :tiempo, :productos, :fecha, :imagen)");
        $consulta->bindValue(':idMesa', $this->idMesa, PDO::PARAM_INT);
        $consulta->bindValue(':idBartender', $this->idBartender, PDO::PARAM_INT);
        $consulta->bindValue(':idCerveceros', $this->idCerveceros, PDO::PARAM_INT);
        $consulta->bindValue(':idCocineros', $this->idCocineros, PDO::PARAM_INT);
        $consulta->bindValue(':idMozos', $this->idMozos, PDO::PARAM_INT);
        $consulta->bindValue(':importe', $this->importe, PDO::PARAM_STR);
        $consulta->bindValue(':estado', $this->estado, PDO::PARAM_STR);
        $consulta->bindValue(':tiempo', $this->tiempo, PDO::PARAM_INT);
        $consulta->bindValue(':productos', $this->productos, PDO::PARAM_STR);
        $consulta->bindValue(':fecha', $this->fecha, PDO::PARAM_STR);
        $consulta->bindValue(':imagen', $this->imagen, PDO::PARAM_STR);
        $consulta->execute();

        return $objAccesoDatos->obtenerUltimoId();
    }

    public static function obtenerTodos()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT * FROM pedidos");
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_CLASS, 'Pedido');
    }
    public static function buscarUno($idPedido)
    {
        $objAccesoDato = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDato->prepararConsulta("SELECT * FROM pedidos WHERE id = :id");
        $consulta->bindValue(':id', $idPedido, PDO::PARAM_INT);
        $consulta->execute();
        return $consulta->fetchObject('Pedido');
    }

    public static function borrarPedido($idPedido)
    {
        $objAccesoDato = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDato->prepararConsulta("UPDATE pedidos SET estado = 'cancelado' WHERE id = :id");
        $consulta->bindValue(':id', $idPedido, PDO::PARAM_INT);
        $consulta->execute();
    }

    public static function modificarPedido($pedido)
    {
        $objAccesoDato = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDato->prepararConsulta("UPDATE pedidos SET idMesa = :idMesa, idBartender = :idBartender, idCerveceros = :idCerveceros, 
                                idCocineros = :idCocineros, idMozos = :idMozos, importe = :importe, estado = :estado, tiempo = :tiempo,
                                productos = :productos WHERE id = :id");
        $consulta->bindValue(':idMesa', $pedido->idMesa, PDO::PARAM_INT);
        $consulta->bindValue(':idBartender', $pedido->idBartender, PDO::PARAM_INT);
        $consulta->bindValue(':idCerveceros', $pedido->idCerveceros, PDO::PARAM_INT);
        $consulta->bindValue(':idCocineros', $pedido->idCocineros, PDO::PARAM_INT);
        $consulta->bindValue(':idMozos', $pedido->idMozos, PDO::PARAM_INT);
        $consulta->bindValue(':importe', $pedido->importe, PDO::PARAM_STR);
        $consulta->bindValue(':estado', $pedido->estado, PDO::PARAM_STR);
        $consulta->bindValue(':tiempo', $pedido->tiempo, PDO::PARAM_INT);
        $consulta->bindValue(':productos', $pedido->productos, PDO::PARAM_STR);
        $consulta->bindValue(':id', $pedido->id);
        $consulta->execute();
    }

    public static function cargarDetallePedido($idPedido, $idProducto, $cantidad){
        $objAccesoDato = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDato->prepararConsulta("INSERT INTO detallepedidos (idPedido, idProducto, cantidad) 
                                VALUES (:idPedido, :idProducto, :cantidad)");
        $consulta->bindValue(':idPedido', $idPedido, PDO::PARAM_INT);
        $consulta->bindValue(':idProducto', $idProducto, PDO::PARAM_INT);
        $consulta->bindValue(':cantidad', $cantidad, PDO::PARAM_INT);
        $consulta->execute();
    }
}
