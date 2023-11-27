<?php
class Pedido implements JsonSerializable
{
    private $id;
    private $idMesa;
    private $idMozos;
    private $importe;
    private $estado;
    private $tiempo;
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
            'idMozos' => $this->idMozos,
            'importe' => $this->importe,
            'estado' => $this->estado,
            'tiempo' => $this->tiempo,
            'fecha' => $this->fecha,
            'imagen' => $this->imagen,
        ];
    }

    public function crearPedido()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("INSERT INTO pedidos (idMesa, idMozos, importe, estado, tiempo, fecha, imagen) 
                                VALUES (:idMesa, :idMozos, :importe, :estado, :tiempo, :fecha, :imagen)");
        $consulta->bindValue(':idMesa', $this->idMesa, PDO::PARAM_INT);
        $consulta->bindValue(':idMozos', $this->idMozos, PDO::PARAM_INT);
        $consulta->bindValue(':importe', $this->importe, PDO::PARAM_STR);
        $consulta->bindValue(':estado', $this->estado, PDO::PARAM_STR);
        $consulta->bindValue(':tiempo', $this->tiempo, PDO::PARAM_INT);
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
        $consulta = $objAccesoDato->prepararConsulta("UPDATE pedidos SET idMesa = :idMesa, idMozos = :idMozos, importe = :importe, estado = :estado,
                                                     tiempo = :tiempo, imagen= :imagen WHERE id = :id");
        $consulta->bindValue(':idMesa', $pedido->idMesa, PDO::PARAM_INT);
        $consulta->bindValue(':idMozos', $pedido->idMozos, PDO::PARAM_INT);
        $consulta->bindValue(':importe', $pedido->importe, PDO::PARAM_STR);
        $consulta->bindValue(':estado', $pedido->estado, PDO::PARAM_STR);
        $consulta->bindValue(':imagen', $pedido->imagen, PDO::PARAM_STR);
        $consulta->bindValue(':tiempo', $pedido->tiempo, PDO::PARAM_INT);
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
